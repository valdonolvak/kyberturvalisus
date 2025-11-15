Praktiline ülevaade IT-eriala teemadel: **Riskid**, **riskiregister (risk register) ja riskianalüüsi (risk analysis) koostamine**, **riskide realiseerumise tõenäosus (likelihood) ja mõju (impact)**, **leevenduskava (mitigation plan)** ja **muudatuste haldus (change management)**.


# 1. Sissejuhatus riskide mõistesse

**Risk (risk)** on võimalus, et mingi sündmus realiseerub ja põhjustab negatiivse mõju eesmärgile, süsteemile või teenusele. Risk koosneb kahest tootest: **tõenäosus (likelihood)** × **mõju (impact)**. IT-valdkonnas tuleb riske vaadelda nii tehnilisest, protsessilisest kui inimlikust vaatenurgast.

Riskihaldus (risk management) on süsteemne protsess riskide tuvastamiseks, hindamiseks, leevendamiseks ja kontrollimiseks projekti või teenuse elutsükli vältel.

# 2. Riskiregister ja riskianalüüsi koostamine

### 2.1 Mis on riskiregister (risk register)?

Riskiregister on dokument või andmebaas, kuhu kogutakse kõik identifitseeritud riskid koos nende kirjelduste, hindamiste, leevendusmeetmete, vastutajate ja staatusega. See on elav dokument — pidevalt uuendatav.

**Peamised väljad, mis riskiregistrisse panna:**

* ID
* Riskikirjeldus (risk description)
* Põhjus / tingimused
* Tõenäosus (1–5 skaala) (likelihood)
* Mõju (1–5 skaala) (impact)
* Riskitase (risk score = tõenäosus × mõju)
* Riskikategooria (tehniline / protsess / inim / välis)
* Leevendusmeetmed (mitigation actions)
* Vastutaja (owner)
* Tähtaeg / hoolduskuupäev
* Staatus (avatud / jälgimisel / suletud)
* Kommentaarid / ajaloolised muudatused

### 2.2 Riskianalüüsi (risk analysis) sammud

1. **Identifitseerimine (identify)** — kogu võimalikud ohud (häälte, loetelude, töötoad, post-mortemid).
2. **Klassifitseerimine (classify)** — kategooria, varad, äriefekt.
3. **Hindamine (assess)** — hinnata tõenäosus ja mõju ning arvutada riskiskoor.
4. **Prioriseerimine (prioritize)** — kõrge riskiga objektid esmajärjekorras.
5. **Leevenduse planeerimine (plan mitigation)** — detailne leevenduskava.
6. **Monitoorimine ja ülevaatamine (monitor & review)** — jälgi, mõõda ja uuenda.

### 2.3 Skaalad ja tõlgendused

Soovituslik lihtne skaala:

* Tõenäosus: 1=Väga madal (<5% aastas), 2=Madal (5–20%), 3=Keskmine (20–50%), 4=Kõrge (50–80%), 5=Väga kõrge (>80%).
* Mõju: 1=Väike (kosmeetikameede), 2=Mõõdukas (operatiivne häire), 3=Tõsine (kasu/kulude mõju), 4=Väga tõsine (suured rahalised kaotused/mainekahju), 5=Katastroofiline (eesmärgi/ettevõtte jätkusuutlikkus ohus).

# 3. Riskide realiseerumise tõenäosus ja mõju — põhjalik selgitus

**Tõenäosus (likelihood)** on hind, kui sageli risk võib realiseeruda antud tingimustes. Selle hindamiseks kasutatakse ajaloolisi andmeid, ekspertarvamusi ja kvantitatiivseid mudeleid (nt statistika, Poisson, binomiaal jms).

**Mõju (impact)** mõõdab tagajärge, kui risk realiseerub: finantskaotus, teenusekatkestus, mainekahju, regulatiivne trahv, andmeleke jne. Mõju võib olla mitmemõõtmeline — rahaline (EUR), operatiivne (tunnid), õiguslik (trahvid), maine (skaleeritav).

Kombineerides neid, saab määrata **riskitunnuse (risk score)** ja otsustada, millised riskid nõuavad aktiivset tegutsemist (tõrje) või piiramist.

# 4. Leevenduskava (mitigation plan) — mis, kuidas ja miks

Leevenduskava on rea tegevusi, mille eesmärk on vähendada riski tõenäosust ja/või mõju. Leevenduskavas tuleb selgelt määratleda:

* tegevus (mis täpselt tehakse),
* vastutaja (kes teeb),
* tähtaeg (millal tehtud),
* mõõdik/indikaator (kuidas mõõta, et see töötab),
* rollback-plan (tagasipööramise plaan),
* eelarve ja ressursid.

Leevendusstrateegiad:

* **Vältimine (avoidance)** — ei tee tegevust, mis tekitab riski.
* **Vähendamine (mitigation)** — vähendatakse tõenäosust või mõju.
* **Ülekandmine (transfer)** — kindlustus või tunnustamine kolmandale osapoolele (nt pilveteenus, SLA).
* **Aktsepteerimine (acceptance)** — risk võetakse teadlikult vastu (nt madala taseme riskid).

# 5. Muudatuste haldus (change management) — põhialused

Muudatuste haldus (change management) tagab, et kõik infrastruktuuri ja süsteemi muudatused tehakse kontrollitud viisil. See vähendab võimalikke regressioone ja teenusekatkestusi.

Põhielemendid:

1. **Muudatuse taotlus (Change Request, CR)** — kirjeldus, põhjendus, mõju, rollback.
2. **Mõju- ja riskihindamine** — hindab miten muudatus mõjutab teenust.
3. **Heakskiit (Change Advisory Board, CAB)** — otsustab suuremate muudatuste üle.
4. **Testimine** — testkeskkond, regressioonitestid.
5. **Ajastamine** — aknad, maintenance windows.
6. **Rollback-protseduur** — valmis plaan vigade korral.
7. **Dokumentatsioon ja audit** — mis tehti, kes tegi, millal.
8. **Post-implementation review (PIR)** — õppetunnid.

Muudatuste haldus ja riskihaldus peaksid olema tihedalt integreeritud: igale suuremale muudatusele tehakse eraldi riskianalüüs.

---

# 6. Iga ala kohta 3 näidet koos analüüsi ja selgitusega

(Alljärgnevalt iga näide sisaldab: riskikirjeldus, tõenäosus (1–5), mõju (1–5), riskitase, leevendus, lühianalüüs.)

## 6.1 Riskiregister ja riskianalüüsi koostamise näited

### Näide 1 — Pilveandmete ebaõige märgistamine

* **Kirjeldus:** Andmed pilves on valesti kategooriastatud (nt konfidentsiaalsed andmed pole krüpteeritud).
* **Tõenäosus:** 3
* **Mõju:** 4
* **Riskitase:** 12 (kõrge)
* **Leevendus:** Andmete klassifikatsioonipoliitika, automaatne märgistamine (DLP), krüpteerimine.
* **Analüüs:** Risk tabab eriti organisatsioone, kus andmete klassifitseerimine on manuaalne. Automatiseerimine ja audit vähendavad nii tõenäosust kui mõju.

### Näide 2 — Tarneahela sõltuvus ühisest teenusest

* **Kirjeldus:** Kolmanda osapoole API teenuse katkestus peatab meie teenuse.
* **Tõenäosus:** 2
* **Mõju:** 5
* **Riskitase:** 10 (kõrge)
* **Leevendus:** Multi-provider strateegia, circuit-breaker, fallback-mehhanismid.
* **Analüüs:** Kuigi tõenäosus võib olla madal, on mõju suur; vajalik arhitektuurne lahendus ja SLAd.

### Näide 3 — Regulatiivne nõue (GDPR / andmekaitse)

* **Kirjeldus:** Uus seadus loob täitmise kohustuse, mis nõuab muutusi süsteemis.
* **Tõenäosus:** 3
* **Mõju:** 4
* **Riskitase:** 12
* **Leevendus:** Jälgida regulatsioone, juristi kaasamine, regulaarne compliance-audit.
* **Analüüs:** Regulatiivsed riskid nõuavad etteplaneerimist ja järelevalvet. Need põhjustavad otseseid rahalisi ja mainekaotuse mõjusid.

## 6.2 Riskide realiseerumise tõenäosuse ja mõju näited

### Näide 1 — Ransomware rünnak kontorivõrgus

* **Tõenäosus:** 4
* **Mõju:** 5
* **Riskitase:** 20 (väga kõrge)
* **Leevendus:** Pidev varundamine, võrgu segmentatsioon, endpoint protection, kasutajakoolitus.
* **Analüüs:** Kõrge tõenäosus koos katastroofilise mõjuga nõuab mitmekihilist kaitset.

### Näide 2 — Võrguühenduse lühiajaline langus (ISP probleem)

* **Tõenäosus:** 3
* **Mõju:** 2
* **Riskitase:** 6 (keskmine)
* **Leevendus:** Mitme ISP kasutamine, offline funktsionaalsus.
* **Analüüs:** Mõju mõõdukas, leevendused suhteliselt madala kuluga.

### Näide 3 — Arendaja eksituse tõttu andmete kadumine testkeskkonnas

* **Tõenäosus:** 3
* **Mõju:** 2
* **Riskitase:** 6
* **Leevendus:** Testandmete haldus, piiratud ligipääs, varundused.
* **Analüüs:** Kuigi mõju ei pruugi mõjutada tootmist, on teadmiste- ja ajakadu reaalne; protseduurid vähendavad riski.

## 6.3 Leevenduskava (mitigation plan) näited

### Näide 1 — Teenuse ülekoormus (scalability risk)

* **Probleem:** Plahvatuslik kasutaja kasv võib kergesti teenuse üle koormata.
* **Leevendusplaan:**

  1. Autoscaling grupid → vastutaja: DevOps → täht: 2 nädalat.
  2. Load testing → vastutaja: QA → täht: 1 nädal.
  3. CDN & cache strateegia → vastutaja: Infra → täht: 3 nädalat.
* **Oodatav tulemus:** Tõenäosus langeb 2→1, mõju 4→2.
* **Analüüs:** Kombineeritud lahendused annavad nii kiire kui püsiva kasvu katmise.

### Näide 2 — Sisemine pahatahtlik kasutaja

* **Probleem:** Töötaja kuritarvitab ligipääsu.
* **Leevendusplaan:**

  1. Juurdepääsu minimaalsuse (least privilege) rakendamine.
  2. Auditilogide reaalajas analüüs.
  3. Sisemise juurdluse protseduur.
* **Analüüs:** Sotsiaalse ja juriidilise riski vähendamiseks kombineerida poliitika ja tehnilisi meetmeid.

### Näide 3 — Tarkvara haavatavus

* **Probleem:** Avastatakse 0-day haavatavus kolmanda osapoole komponendis.
* **Leevendusplaan:**

  1. Paigaldada WAF & IPS reeglid ajutiselt.
  2. Paigaldada hotfix test- ja seejärel tootmiskeskkonda.
  3. Käivitada vulnerability scanning ja dependency management.
* **Analüüs:** Kiire reageerimine + püsiv haldus on kriitilised.

## 6.4 Muudatuste halduse (change management) näited

### Näide 1 — Andmebaasi skeemi muutus

* **Kirjeldus:** Uue välja lisamine tootmissüsteemi kriitilisse tabelisse.
* **Mõju ja risk:** Võib rikkuda päringuid ja põhjustada vigu.
* **Protsess:** Muudatusetaotlus → testkeskkond → regressioonitestid → CAB heakskiit → maintenance window → rollout → PIR.
* **Analüüs:** Rollback-plaan peab olema testitud; muudatus ei tohi olla improviseeritud.

### Näide 2 — Turvapaigad (patch management)

* **Kirjeldus:** Igakuine turvapaigakomplekt.
* **Protsess:** Patch review → staging → automatiseeritud deploy → smoke tests → tootmusdeploy öösel.
* **Analüüs:** Standardiseeritud protsess vähendab riski, samas peab säilima võimalus kiireks hädaparanduseks.

### Näide 3 — Uue vendor-lahenduse juurutus

* **Kirjeldus:** Kolmanda osapoole teenuse integreerimine.
* **Protsess:** PoC → turvalisuse hindamine → SLA & leping → integratsioonitest → koolitus → deploy.
* **Analüüs:** Riskid kattuvad tarneahela ja lepinguliste aspektidega; juriidiline ja tehniline audit vajalik.

---

# 7. Kuus õpiülesannet (õpilastele, praktilised)

Iga ülesande juurde lühijuhised ja hindamiskriteeriumid.

## Ülesanne 1 — Koosta riskiregister (praktika)

* **Töö:** Valige kooli IT-projekt (nt kursuse veebirakendus). Koostage riskiregister vähemalt 10 riskiga. Iga riskil: kirjeldus, tõenäosus (1–5), mõju (1–5), riskiskoor, leevendusplaan 2–3 meetmega, vastutaja ja staatus.
* **Hindamine:** riskide relevantsus, leevenduskonkreetus, realism.

## Ülesanne 2 — Kvantitatiivne riskihindamine

* **Töö:** Vali 5 suurt riskitegijat ja proovi hinnata rahalist (EUR) mõju, kasutades lihtsat modellit (oodatav kahju = tõenäosus × mõju(EUR)). Koosta prioriteetide loend.
* **Hindamine:** arvutuste korrektsus, põhjendused.

## Ülesanne 3 — Leevenduskava detailplaneerimine

* **Töö:** Vali üks kõrge riskiskooriga kirje oma riskiregistrist ja koosta detailne leevenduskava: samm-sammult, vajalikud ressursid, KPI-d, rollback ja testimiskava.
* **Hindamine:** teostatavus, mõõdikute selgus.

## Ülesanne 4 — Muudatuste halduse protsess ja muudatusdokument

* **Töö:** Koosta muudatuse taotlus (Change Request) ühe valitud muudatuse kohta (nt andmebaasi uuendamine). Kaasa riskihindamine, testplaan, rollback ja CAB-i võimalikud küsimused.
* **Hindamine:** täielikkus, riskide käsitlus.

## Ülesanne 5 — Simulatsioon ja hädaolukorra reaktsioon

* **Töö:** Simuleerige klassis rünnak (nt ransomware). Koostage kohene reageerimisplaan (incident response) ja seejärel pikaajaline leevenduskava. Analüüsige mis töötas ja mis mitte (PIR).
* **Hindamine:** operatiivsus, rollide selgus, õppetunnid.

## Ülesanne 6 — Audit ja jälgimissüsteemi planeerimine

* **Töö:** Planeerige logide kogumise ja auditeerimise arhitektuur (mida logida, kust, kuidas säilitada, kui kaua, kes analüüsib). Kirjeldage, kuidas logid aitavad riskide tuvastamisel.
* **Hindamine:** privaatsuse ja andmekaitse arvestamine, tehniline realism.

---

# 8. Näidis — täielik riskiregister ja leevenduskava (mall + täidetud näide)

Allpool on praktiline tabel ning üks risk välja toodud ja detailselt läbi töödeldud.

## Riskiregister — näidis (lühendatud tabel)

| ID    | Risk                                          | Kategooria         | Tõenäosus (1–5) | Mõju (1–5) | Riskiskoor | Leevendus (lühike)                  | Vastutaja | Tähtaeg    |      Staatus |
| ----- | --------------------------------------------- | ------------------ | --------------: | ---------: | ---------: | ----------------------------------- | --------- | ---------- | -----------: |
| R-001 | Ransomware tootmiskeskkonnas                  | Turvalisus         |               4 |          5 |         20 | Varundus + EDR + kasutajakoolitus   | Turbejuht | 30.11.2025 |     Aktiivne |
| R-002 | Andmete valed õigused pilves                  | Protsess/tehniline |               3 |          4 |         12 | DLP + RBAC + audit                  | IT-juht   | 15.12.2025 | Parandamisel |
| R-003 | Teenuse katkestus 3rd party API-st            | Tarneahel          |               2 |          5 |         10 | Circuit-breaker + fallback          | Arendus   | 01.01.2026 |      Plaanis |
| R-004 | Andmebaasi skeemi ebaõnnestunud migratsioon   | Muudatus           |               3 |          4 |         12 | Testkeskkond + rollback             | DBA       | 05.12.2025 |      Plaanis |
| R-005 | Töötaja teadmiste puudumine (key-person risk) | Inimene            |               3 |          3 |          9 | Dokumenteerimine + pair-programming | HR/IT     | 20.12.2025 |       Avatud |

## Detailne näide: R-001 — Ransomware tootmiskeskkonnas

**Riskikirjeldus:** Ransomware nakatab tootmiskeskkonna serverid, krüpteerib andmed ja nõuab lunaraha.

**Mõju:** Tootetööseisak, andmete kadu, mainekahju, võimalik juriidiline vastutus — hinnanguline kahju 50 000–200 000 EUR (sõltuvalt skaalast).

**Tõenäosus:** 4 (antud organisatsiooni profiil ja ajaloolised andmed).

**Riskiskoor:** 20 (väga kõrge).
**Prioriteet:** Kõrge — tegevused kohe.

### Leevenduskava (detail)

1. **Varundus ja taastamine**

   * Tegevus: Igapäevane off-site varundus + perioodiline täielik restore-test.
   * Vastutaja: Infra-administraator (DevOps).
   * Tähtaeg: 7 päeva (paigaldus), taastetest iga kuu.
   * KPI: Restore test õnnestub 100% juhul (faili taastamine <4h).

2. **Endpoint Detection & Response (EDR) ja Network Monitoring**

   * Tegevus: EDR paigaldus tööjaamadesse ja serveritesse; NDR (network detection) logide korrelatsioon.
   * Vastutaja: Turbejuht.
   * Tähtaeg: 14 päeva.
   * KPI: tõrjutud ründeetsüklite tuvastamine <15 min.

3. **Võrgu segmentatsioon ja least-privilege**

   * Tegevus: Isolatsiooni rakendamine, pääsupiirangud, admin-paarituvastus (MFA).
   * Vastutaja: Infra.
   * Tähtaeg: 30 päeva.
   * KPI: vähendab lateral movement võimalusi X → Y.

4. **Kasutajakoolitus (phishing simulatsioon)**

   * Tegevus: Kvartaalne koolitus ja phishing-test.
   * Vastutaja: HR + Turbejuht.
   * Tähtaeg: Esimene tsükkel 2 nädalat.
   * KPI: Klick-rate <5% peale kahe koolituse rida.

5. **Kindlustus ja kommunikatsiooniplaan**

   * Tegevus: Kontrollida küberkindlustuse katvust; koostada kommunikatsiooniplaan klientidele ja regulatoritele.
   * Vastutaja: Juhtkond.
   * Tähtaeg: 30 päeva.

6. **Rollback / Remediation plan**

   * Tegevus: Proceduur, kuidas süsteemi offline-olekust taastada, sh kontaktid, prioriteetsed teenused.
   * Vastutaja: IT juht.
   * Tähtaeg: 7 päeva.

**Eeldatav paranenud seis (pärast rakendamist):**

* Tõenäosus langeks 4 → 2 (vähenenud edukate rünnete hulk).
* Mõju langeks 5 → 3 (varundamine ja restore vähendavad kahjusid).
* Uus riskiskoor = 2 × 3 = 6 (hallatav).

**Mõõdikute jälgimine:** iganädalased security dashboardid, kvartaalne riskiaruanne.

---
