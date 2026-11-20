# Õpilase samm-sammuline juhend — AD ründeteede labor (BloodHound)

> See on **sinu tööjuhend**. Järgi samme ülevalt alla. Iga sammu juures on **„Miks:"** lõik — loe see läbi, sest eksamil/arvestusel küsitakse just *miks*, mitte ainult *mida*.
>
> Seotud materjalid: veebiliidese üksikasjad → [BloodHound-CE-kasutusjuhend.md](./BloodHound-CE-kasutusjuhend.md); käskude täpne süntaks → [Rundeteede-lahenduskaik.md](./Rundeteede-lahenduskaik.md).

---

## Stsenaarium ja eesmärk

Kujuta ette, et oled saanud kätte **ühe tavalise kasutaja konto** (nagu päris ründaja pärast õnnestunud õngitsuskirja). Sul ei ole administraatori õigusi. **Sinu eesmärk:** jõuda sellest ühest tavakontost **täieliku domeeni kontrollini**, kasutades BloodHoundi, et leida AD-s peidus olevad vead.

**Mida sa selle labori jooksul õpid:**
1. miks juba üks tavakonto on ohtlik (näed, kui palju AD-d ta „näeb");
2. kuidas BloodHound muudab keeruka õiguste võrgu nähtavaks ründeteeks;
3. kuidas viit erinevat AD väärseadistust ära kasutada (GenericAll, DCSync, ForceChangePassword, Kerberoast, WriteDacl);
4. kuidas kaitsja samad augud sulgeb.

**⚠️ Eetika:** kõik toimub **isoleeritud laborikeskkonnas**. Needsamad tegevused võõra või kooli päris-võrgu vastu on **seadusevastased** (KarS § 206/217). Sa töötad ainult sulle antud laboridomeenis.

**Mis sul on olemas:**
- **Kali Linux** ründejaam
- **Windows 11** domeeniga liitunud tööjaam
- **Laboridomeen** `hkhk.local`, domeenikontroller `SERVER.hkhk.local` (IP `10.0.30.10`) NB! Vajadusel lisa ise AD ja DNS teenused Windows2019 CVE serverile ning domeeninimeks pane siis hkhk.local ja vaata, et AD serveri IP oleks 10.0.XXX.10, kus siis XXX on sinu VMi vmbr number.
- **Üks konto:** `jaan.tamm` / `Suvi2026!` — rohkem sulle ei anta, ülejäänu pead ise avastama.

> **Paroolimärkus:** laboriparoolis on `!`. Kali bash'is kasuta paroolide ümber **ülakomasid** `'...'` (nt `'Suvi2026!'`), mitte jutumärke — muidu jääd `dquote>` ootejärge.

---

## Samm 0 — Kontrolli, et keskkond töötab

Enne tööriistu veendu, et Kali **näeb** domeenikontrollerit ja oskab domeeni nime lahendada.

```bash
ping -c2 10.0.30.10                                    # kas DC vastab?
```
Kui DC ei vasta nimega, sea DC nimeserveriks (DC on ühtlasi DNS-server):
```bash
echo "10.0.30.10  SERVER.hkhk.local hkhk.local SERVER" | sudo tee -a /etc/hosts
dig @10.0.30.10 _ldap._tcp.dc._msdcs.hkhk.local SRV +short   # peab tagastama DC nime
```

> **Miks:** BloodHoundi kogumistööriist ja Kerberos vajavad domeeni nime (`hkhk.local`) lahendamist IP-ks. Kali ei tunne vaikimisi AD domeeni, seega peab talle ütlema, et DNS-päringud lähevad DC-le. Kui seda ei tee, saad vea `Could not find a domain controller`.

---

## Samm 1 — Käivita BloodHound CE

BloodHound koosneb andmebaasist (Neo4j + Postgres) ja veebiliidesest, mis käivad Dockeris.

```bash
cd ~/BloodHound
docker compose up -d
docker compose logs bloodhound 2>&1 | grep -i "Initial Password"
```
Ava brauseris `http://localhost:8080`. Logi sisse: **Email Address** väljale `admin`, parooliks logist saadud string. Esimesel korral palutakse uus parool seada.

> **Miks:** BloodHound ei „ründa" ise midagi — see on **analüüsitööriist**. Esmalt paneme selle püsti, et oleks kuhu kogutud andmed laadida ja kus neid graafina uurida. (Docker/podmani tõrgetega tegeleb põhitööleht, osa B1.)

---

## Samm 2 — Kogu AD andmed (sinu tavakontoga!)

Nüüd kogume kogu domeeni struktuuri — **jaan.tamm'i tavaõigustega**.

```bash
bloodhound-python -u jaan.tamm -p 'Suvi2026!' -d hkhk.local \
  -ns 10.0.30.10 -dc SERVER.hkhk.local -c All
```

> **Miks (TÄHTIS õppetund):** pane tähele, et sa kasutad **tavakasutaja** kontot, mitte administraatorit — ja ometi saad kätte **kogu** AD struktuuri: kõik kasutajad, grupid, õigused. See ongi AD põhiline oht: kataloog on disainilt „loetav" igale autentitud kontole. Seega juba **üks** kompromiteeritud tavakonto annab ründajale terve kaardi.

Kogumine tekitab `~/BloodHound` kausta JSON-failid. Paki need ja impordi:
```bash
zip bloodhound.zip *.json
```
Veebiliideses: **Administration → File Ingest** → lohista `bloodhound.zip` → oota, kuni töötlemine lõpeb.

---

## Samm 3 — Tee jaan.tamm „hõivatuks" ja tutvu graafiga

1. Vali üleval **SEARCH**, otsi `jaan.tamm`, klõpsa tulemusel.
2. **Paremklõps** sõlmel `jaan.tamm` → **Mark as Owned**.

> **Miks:** „Owned" tähendab „see konto on juba minu käes". BloodHound oskab siis arvutada, mida kõike *sellest lähtepunktist* on võimalik saavutada. See peegeldab reaalsust: ründajal on üks jalgealune ja ta tahab teada, kui kaugele sellega jõuab.

Kui sa pole kindel, kuidas sõlmi/servi lugeda (värvid, ikoonid, Inbound/Outbound), loe läbi [BloodHound-CE-kasutusjuhend.md](./BloodHound-CE-kasutusjuhend.md) jaod 3–5.

---

## Samm 4 — Leia oma esimene ründetee

1. Vali **PATHFINDING**.
2. Ülemine väli (algus): `jaan.tamm`. Alumine väli (siht): alusta domeeniga `HKHK.LOCAL` (või `Administrator`).
3. Vaata, millise ahela BloodHound joonistab.

Peaksid nägema umbes:
`jaan.tamm —GenericAll→ Serveri-Admins —DCSync→ HKHK.LOCAL`

**Loe seda nii:** jaan.tamm'il on `GenericAll` (täielik kontroll) grupi `Serveri-Admins` üle; sellel grupil omakorda on `DCSync` õigus domeenil.

> **Miks:** käsitsi ACL-e lugedes poleks sa seda kunagi märganud — tee peidab end mitme objekti taha. BloodHound leiab **lühima tee** madalast kontost kõrgete õigusteni. See ongi tööriista mõte.

**Uuri serva lähemalt:** klõpsa serval `GenericAll` → paremal avaneb **Abuse Info** — seal on täpsed käsud, kuidas seda õigust kasutada. Loe ka **OPSEC** vaheleht (mis jälg jääb).

---

## Samm 5 — Kasuta esimest teed ära (GenericAll → DCSync)

**Samm 5a — lisa end gruppi** (kasutad oma `GenericAll` õigust):
```bash
bloodyAD --host 10.0.30.10 -d hkhk.local -u jaan.tamm -p 'Suvi2026!' \
  add groupMember 'Serveri-Admins' 'jaan.tamm'
```
Kontrolli:
```bash
net rpc group members 'Serveri-Admins' -U 'HKHK/jaan.tamm%Suvi2026!' -S 10.0.30.10
```

> **Miks `bloodyAD`, mitte pildil olev `net rpc`?** `net rpc` kasutab SAMR-protokolli, mis karmistatud DC-l võib anda `ACCESS_DENIED`. `bloodyAD` teeb sama LDAP kaudu ja austab `GenericAll`-i. (Win11-l töötaks ka lihtne `net group "Serveri-Admins" jaan.tamm /add /domain`.)

**Samm 5b — kasuta grupi DCSync'i ja tõmba „kuningavõti":**
```bash
secretsdump.py 'hkhk.local/jaan.tamm:Suvi2026!'@10.0.30.10 -just-dc-user krbtgt
```

> **Miks see on võit:** `Serveri-Admins` grupil on **DCSync** — õigus teeselda domeenikontrollerit ja küsida paroolihashe. Kuna sa oled nüüd grupis, saad tõmmata **krbtgt** konto hashi. krbtgt on konto, millega allkirjastatakse **kõik** Kerberose piletid — selle hashiga saad teha **Golden Ticketi** = igavene, piiramatu domeeni kontroll. Sa alustasid tavakontost ja jõudsid täieliku ülevõtuni. 🎯

**Pane tähele terviklikku ahelat:**
```
jaan.tamm (tavakasutaja)
   → GenericAll → Serveri-Admins
      → DCSync → domeen
         → krbtgt hash → Golden Ticket → TÄIELIK KONTROLL
```

---

## Samm 6 — Uuri ülejäänud teid iseseisvalt

Nüüd tee sama töö **ise** teiste kontode jaoks. Iga konto puhul: SEARCH → klõpsa sõlmel → vaata **Outbound Object Control** → näed, mis õigus tal on. Seejärel ava serva **Abuse Info**. Täpsed käsud on [Rundeteede-lahenduskaik.md](./Rundeteede-lahenduskaik.md), aga **proovi esmalt ise** leida.

| Uuri kontot | Mis õigus tal on? | Mida see õigus lubab? |
|---|---|---|
| `mari.kask` | `ForceChangePassword` → `peeter.saar` | lähtestada peetri parool ilma vana teadmata → võtta ta konto üle |
| `liis.oja` | `DCSync` → domeen | tõmmata kõik paroolihashid (sama võit kui Samm 5b) |
| `svc-sql` | `HasSPN` (Kerberoastitav) | küsida ta Kerberose pilet ja murda ta parool offline |
| `peeter.saar` | `WriteDacl` → `Helpdesk` | kirjutada endale Helpdeskile õigusi juurde |

**Iga tee puhul küsi endalt kolm küsimust** (need tulevad ka hindamisel):
1. **Mis õigus** sellel kontol on ja mida see täpselt lubab?
2. **Kuidas** ma seda ära kasutaksin (milline käsk, mis juhtub)?
3. **Kuidas kaitsja** selle sulgeks ja millise logikirje järgi avastaks?

> **Miks iseseisev osa:** ründaja töö ongi „vaata õigusi → leia, mida saab ära kasutada → korda". Kui sa suudad selle ise läbi mõelda, oskad sa ka päris AD-d auditeerida. Lahenduskäik on olemas, aga vaata sinna alles siis, kui oled ise proovinud.

---

## Samm 7 — Kaitsja vaade: kuidas need augud sulgeda

Ründe mõistmine on pool võitu — teine pool on parandamine. Iga leitud tee kohta kirjelda parandus:

| Tee | Parandus | Avastamine logis (Event ID) |
|---|---|---|
| GenericAll grupile | eemalda liigne ACE; delegeeri kitsalt | 5136 (DACL muutus) |
| DCSync (grupp/liis.oja) | eemalda GetChanges/All tavakontodelt | 4662 (replikatsiooni-GUID) |
| ForceChangePassword | paroolilähtestus ainult helpdeski rollile | 4724 |
| Kerberoast (svc-sql) | gMSA / pikk juhuparool; keela RC4 | 4769 (RC4 = `0x17`) |
| WriteDacl | eemalda liigne ACL-õigus; Tier-0 haldus | 5136 |

> **Miks:** sama BloodHound, millega sina ründasid, on **administraatori** parim tööriist — ta jooksutab seda **oma** AD vastu, et leida ja sulgeda needsamad teed enne, kui ründaja need leiab. Tööriist on sama; erineb ainult eesmärk.

---

## Samm 8 — Mida esitada (arvestustöö)

Koosta lühike raport, mis sisaldab:
1. **Kõik kuus ründeteed**, mille leidsid — iga kohta: alguskonto, serv(ad), lõppsiht.
2. **Ekraanipilt** vähemalt ühest BloodHoundi teest (nt jaan.tamm → domeen).
3. **Üks tee lõpuni** tehtud (käsud + tulemus, nt krbtgt hash või murtud svc-sql parool).
4. **Iga tee kohta parandus + logi-Event ID** (Samm 7 tabel oma sõnadega).
5. **Vastused kontrollküsimustele** (vt põhitööleht [BloodHound-labor.md](./BloodHound-labor.md), jagu 7).

> **Vihje:** hindamisel ei loe mitte käskude kopeerimine, vaid see, kas sa suudad **seletada**, miks iga tee töötab ja kuidas seda sulgeda. Keskendu „miks"-lõikudele.

---

## Kiire tõrkeotsing

| Probleem | Kus lahendus |
|---|---|
| `docker compose` viga (podman, compose puudu) | põhitööleht, B1 |
| `Could not find a domain controller` | Samm 0 (DNS / `/etc/hosts`) |
| `dquote>` bash'is | kasuta ülakomasid `'...'` parooli ümber |
| `net rpc` → `ACCESS_DENIED` | kasuta `bloodyAD`-d või Win11 `net group` (kasutusjuhend 8.2) |
| zip-i ei leia pärast kogumist | bloodhound-python teeb JSON-id, mitte zipi (põhitööleht B3) |
