# Laboriharjutus: Active Directory ründeteede kaardistamine BloodHoundiga

> **Moodul:** Operatsioonisüsteemi ja küberturbe alused
> **Kestus:** ~4 akadeemilist tundi (1 teooriaplokk + labor)
> **Vorm:** praktiline labor isoleeritud virtuaalkeskkonnas

---

## ⚠️ Eetika ja piirangud

See harjutus toimub **ainult isoleeritud laboridomeenis** (eraldi virtuaalvõrk, ilma internetiühenduse ja ilma kooli/tootmis-AD-ühenduseta). BloodHoundi ja SharpHoundi kasutamine võõra organisatsiooni võrgus ilma kirjaliku loata on **seadusevastane** (KarS § 206/217 — arvutikuritegevus). Õppija allkirjastab enne laborit vastutuse teadmiseks võtmise.

---

## Õpiväljundid

Pärast harjutust oskab õppija:

1. selgitada, **mis probleemi BloodHound lahendab** (AD õiguste graafi analüüs);
2. **koguda** AD andmeid SharpHoundi / bloodhound-pythoniga tavakasutaja õigustes;
3. **lugeda ründeteed** (attack path) graafil ja seletada iga serva (edge) tähendust;
4. kirjeldada, kuidas ründaja iga seadistust **ära kasutaks** ja kuidas administraator selle **parandab**;
5. seostada leitud teed **monitooringu ja logidega** (kaitsja vaade).

---

## 1. Mis rünne / tehnika?

BloodHound ei murra ise kuhugi sisse — see on **luure- ja analüüsietapi** tööriist (ingl *reconnaissance*, MITRE ATT&CK **T1069 Permission Groups Discovery**, **T1482 Domain Trust Discovery**). Ründaja, kellel on juba üks jalgealune (nt phishinguga saadud tavakonto), kasutab seda, et leida **lühim tee** oma madalast kontost kuni Domain Adminsini.

Tööpõhimõte:

- **Kogumine (SharpHound):** üks autentitud domeenikasutaja pärib LDAP-i ja SMB kaudu kokku kõik objektid ja seosed — grupiliikmelisused, ACL-id, aktiivsed sessioonid, local admin õigused, delegeerimised.
- **Graaf (Neo4j):** andmed laaditakse graafiandmebaasi, kus **sõlmed** on kasutajad/grupid/arvutid ja **servad** on õigussuhted (`MemberOf`, `GenericAll`, `ForceChangePassword`, `DCSync`, `HasSPN`, `AdminTo`, …).
- **Analüüs:** päring „Shortest Path to Domain Admins" leiab ahela, mida inimsilm käsitsi ei märkaks.

Peamine oht: **tavakasutaja õigustest piisab kogumiseks** — AD on „loetav" igale autentitud kontole.

---

## 2. Laboritopoloogia

| Masin | Roll | OS |
|---|---|---|
| `DC01` | Domeenikontroller, domeen `labor.local` | Windows Server 2022 |
| `WIN11` | Domeeniga liidetud tööjaam (ründaja jalgealune) | Windows 11 |
| `KALI` | Ründaja analüüsijaam (valikuline) | Kali Linux |

Kõik ühes isoleeritud host-only / internal võrgus. Kellad DC ja klientide vahel **peavad olema sünkroonis** (ajahälve lõhub Kerberose ja kogumise).

---

## 3. Osa A — Keskkonna ettevalmistus (õpetaja)

1. Püstita `labor.local` domeen (vt varasem WS2022 labor).
2. Kopeeri `Seed-ADLabor.ps1` DC01-le ja jooksuta **Domain Admins** õigustes:

   ```powershell
   Set-ExecutionPolicy -Scope Process RemoteSigned
   .\Seed-ADLabor.ps1
   ```

3. Skript loob OU `Labor` alla kasutajad, grupid ja **6 tahtlikku väärseadistust**. Lõpus kuvatakse istutatud teede kokkuvõte.
4. Tee DC01-st **VM hetktõmmis** — nii saab labori järgmise grupi jaoks sekunditega lähtestada.
5. Anna õppijale ainult stardikonto: **`jaan.tamm` / `Suvi2026!`**. Ülejäänu peab ta ise avastama.

---

## 4. Osa B — BloodHound CE paigaldus ja andmete kogumine (õppija)

> **Märkus:** vanad juhendid (sh paljud Mediumi postitused) näitavad *legacy* BloodHoundi lahtipakitava `.exe` + Neo4j Desktopiga. Praegune versioon on **BloodHound Community Edition (CE)**, mis käib Dockeris. Mõlemad loevad sama SharpHoundi andmeid; allpool CE.

### B1. BloodHound CE käivitus (KALI või WIN11 + Docker)

```bash
curl -L https://ghst.ly/getbhce -o docker-compose.yml
docker compose up -d
# Ava http://localhost:8080 ; esmase parooli leiad logist:
docker compose logs bloodhound | grep -i "password"
```

### B2. Andmete kogumine

**Variant 1 — SharpHound Windowsi tööjaamalt (`WIN11`, kasutaja `jaan.tamm`):**

```powershell
# SharpHound.exe on BloodHoundi Collectors kaustas
.\SharpHound.exe -c All --zipfilename labor
```
> Windows Defender märgib SharpHoundi pahavaraks — see on õige, tegu on tuntud ründekomponendiga. Laboris lisa ajutine erand; **selgita õppijale, miks** viirusetõrje reageerib.

**Variant 2 — bloodhound-python Kalist:**

```bash
bloodhound-python -u jaan.tamm -p 'Suvi2026!' -d labor.local -ns <DC01_IP> -c All
```

### B3. Import

Lohista tekkinud `.zip` BloodHound CE veebiliidesesse (**Administration → File Ingest**) ja oota töötlemine lõpuni.

---

## 5. Osa C — Ülesanded (avasta ründeteed)

Kasuta otsingut, serva-filtreid ja päringut **„Shortest Paths to Domain Admins"**. Iga leitud tee kohta täida tabel (ülesanne C7).

| # | Ülesanne | Vihje (mida otsida) |
|---|---|---|
| C1 | Leia `jaan.tamm` tee Domain Adminsini | Alusta sõlmest `jaan.tamm`, „Shortest Path to Here" Domain Adminsini |
| C2 | Millise **serva** kaudu jaan grupiõigusi võidab? | Otsi serva `GenericAll` grupile `Serveri-Admins` |
| C3 | Miks viib `Serveri-Admins` Domain Adminsini? | Vaata `MemberOf` ahelat (pesastatud grupp) |
| C4 | Leia konto, kes saab **teise kasutaja parooli lähtestada** | Serv `ForceChangePassword` |
| C5 | Leia konto, kes saab domeenist **paroolihashid välja replitseerida** | Serv `DCSync` domeeni objektile |
| C6 | Leia **Kerberoastitav** teenusekonto ja seleta rünne | Sõlme omadus `HasSPN = true` |
| C7 | Leia konto, kes saab ühe grupi **ACL-i ümber kirjutada** | Serv `WriteDacl` |

### C7 — Vormista iga tee kohta:

| Tee | Servad (edges) | Kuidas ründaja ära kasutab | Kuidas kaitsja parandab |
|---|---|---|---|
| nt C4 | `mari.kask —ForceChangePassword→ peeter.saar` | Lähtestab peetri parooli, logib tema kontona sisse | Eemalda liigne ACE; jälgi event 4724 |

---

## 6. Osa D — Kaitsja vaade (iga vea parandus)

Iga istutatud väärseadistuse kohta arutage ja proovige:

| Väärseadistus | BloodHoundi serv | Parandus | Avastamine logis / monitooringus |
|---|---|---|---|
| Teenusekonto Domain Adminsis | `MemberOf` | Teenusekonto välja privgrupist; gMSA + least privilege | AD grupimuutuste audit (4728/4732) |
| Pesastatud grupp DA-s | `MemberOf` | Lameda grupistruktuur; tiering (0/1/2) | Perioodiline BloodHound audit (sinine tiim) |
| `GenericAll` grupile | `GenericAll` | Eemalda liigne ACE; delegeeri kitsalt | Event 5136 (DACL muutus) |
| `ForceChangePassword` | `ForceChangePassword` | Eemalda õigus; parooli-lähtestus ainult helpdeskile | Event 4724 |
| `DCSync` õigus | `DCSync` | Eemalda GetChanges/All tavakontolt | Event 4662 replikatsiooni-GUID-iga |
| Kerberoastitav SPN + nõrk parool | `HasSPN` | Pikk juhuslik parool / gMSA; AES-krüpt | Event 4769 (TGS, RC4 lipuga) |

**Kesksõnum õppijale:** sama BloodHound, millega ründaja teid otsib, on **administraatori parim tööriist** samade teede enne sulgemiseks. Jooksuta seda regulaarselt oma AD vastu.

---

## 7. Kontrollküsimused (hindamine)

1. Miks piisab AD kogumiseks tavakasutaja õigustest, mitte administraatori omadest?
2. Mis vahe on `GenericAll` ja `WriteDacl` serval? Kumb on ohtlikum ja miks?
3. Selgita Kerberoasting rünne samm-sammult: miks SPN seadistus selle võimaldab?
4. Mida tähendab DCSync praktikas — mida ründaja sellega saab?
5. Nimeta kaks Windowsi event ID-d, millega eelnevaid tegevusi tuvastada.
6. Kuidas AD „tiering" (Tier 0/1/2) katkestaks mitu siin leitud teed korraga?

---

## 8. Puhastus

Grupi vahetamisel taasta DC VM hetktõmmisest **või** jooksuta:

```powershell
.\Clear-ADLabor.ps1
```

---

## Lisad

- `Seed-ADLabor.ps1` — labori külvskript (6 väärseadistust)
- `Clear-ADLabor.ps1` — puhastusskript
- Soovituslik taust: GOAD (Game of Active Directory) — avatud lähtekoodiga suurem harjutuskeskkond sama põhimõttega.
