# Küberturvalisuse uurimistöö

## Allikate ja uurimiskanalite juhend

**Õppeaine:** Küberturvalisus
**Sihtgrupp:** IT-eriala õppijad
**Teema:** Operatsioonisüsteemide haavatavused ja nende tegelik ärakasutamine
**Kasutamine:** 2026. aasta haavatavuste uurimistöö

---

# 1. CVE ametlik andmebaas – CVE.org

**CVE – Common Vulnerabilities and Exposures**

[CVE programmi ametlik veebileht](https://www.cve.org/?utm_source=chatgpt.com)

CVE on süsteem, mille abil antakse tarkvara- ja riistvara turvanõrkustele unikaalne tunnus.

Näide:

```text
CVE-2026-12345
```

### Mida siit otsida?

* CVE kirjeldus
* haavatavuse identifikaator
* mõjutatud toode
* mõjutatud tootja
* viited teistele allikatele
* haavatavuse avalikustamisega seotud informatsioon

### Õpilase ülesanne

Kui uurid mõnda haavatavust, alusta otsingut alati CVE-ID-st.

Näiteks:

```text
CVE-2026-28604
```

Seejärel kontrolli, kas sama CVE kohta leidub:

* tootja turvateade;
* NVD kirje;
* CISA KEV kirje;
* tehniline analüüs;
* exploit või PoC;
* info reaalse ründe kohta.

---

# 2. NIST National Vulnerability Database – NVD

**NVD – National Vulnerability Database**

[NIST National Vulnerability Database (NVD)](https://nvd.nist.gov/?utm_source=chatgpt.com)

NVD on USA National Institute of Standards and Technology hallatav haavatavuste andmebaas. NVD sisaldab muu hulgas CVE-de tehnilisi andmeid, tooteinfot ja haavatavuse mõju kirjeldavaid mõõdikuid. ([NIST][2])

### Mida NVD-st otsida?

* CVE kirjeldus
* CVSS
* ründevektor
* vajalikud õigused
* kasutaja tegevuse vajadus
* konfidentsiaalsuse mõju
* tervikluse mõju
* käideldavuse mõju
* CWE
* mõjutatud tarkvaraversioonid
* välised viited

### Õpilase uurimisküsimused

1. Kui suur on CVSS?
2. Kas rünne toimub võrgust või lokaalselt?
3. Kas ründaja peab olema autentitud?
4. Kas kasutaja peab midagi tegema?
5. Kas võimalik on koodi käivitamine?
6. Kas võimalik on õiguste suurendamine?
7. Kas mõjutatud on konfidentsiaalsus?
8. Kas mõjutatud on terviklus?
9. Kas mõjutatud on käideldavus?

### Oluline märkus

NVD ei ole ainus allikas, mille põhjal tuleb haavatavust hinnata. NIST muutis 2026. aastal NVD rikastamise prioriseerimist ning kõik CVE-d ei pruugi saada samal ajal sama detailsusega täiendatud infot. CVE võib NVD-s olemas olla ka siis, kui selle rikastatud andmed pole veel täielikud. ([NIST][3])

---

# 3. CISA Known Exploited Vulnerabilities – KEV

**KEV – Known Exploited Vulnerabilities**

[CISA Known Exploited Vulnerabilities Catalog](https://www.cisa.gov/known-exploited-vulnerabilities-catalog?utm_source=chatgpt.com)

See on üks olulisemaid allikaid, kui soovid teada saada, kas haavatavust on **päriselus ära kasutatud**.

CISA kirjeldab KEV kataloogi kui autoriteetset allikat haavatavuste kohta, mida on teadaolevalt looduses ära kasutatud. ([CISA][1])

### Mida kontrollida?

Otsi:

```text
CVE-XXXX-XXXXX
```

Kui CVE on KEV-is olemas, kontrolli:

* millal see kataloogi lisati;
* milline on tootja;
* milline on haavatavuse kirjeldus;
* millal parandust rakendama soovitatakse;
* kas CISA märgib seose lunavararünnakutega.

### Väga oluline erinevus

Õpilane peab eristama kolme väidet:

> **Haavatavus eksisteerib**

ei tähenda automaatselt:

> **Selle jaoks on exploit olemas**

ega:

> **Seda on reaalses ründes kasutatud.**

Kui CVE ei ole KEV-is, ei tohi kirjutada:

> "Seda haavatavust ei ole kunagi rünnetes kasutatud."

Õige sõnastus on näiteks:

> "Uurimise ajal ei olnud CVE CISA KEV kataloogis."

---

# 4. Microsoft Security Response Center – MSRC

Windowsi haavatavuste uurimisel kasuta eelkõige Microsofti enda allikaid.

[Microsoft Security Update Guide](https://msrc.microsoft.com/update-guide/en-us/?utm_source=chatgpt.com)

Microsoft nimetab Security Update Guide'i oma turvauuenduste autoriteetseks infoallikaks. ([Microsoft][4])

### Mida siit otsida?

* CVE
* mõjutatud Windowsi versioon
* haavatavuse tüüp
* severity
* security update
* KB-number
* parandatud versioon
* exploitability
* reboot requirement
* teadaolevad probleemid pärast uuendust

### Näiteks

```text
CVE-2026-XXXX
```

Otsi Microsofti Security Update Guide'is selle CVE järgi.

---

# 5. Microsoft Security Response Center – tunnustused ja uurijad

Microsofti turvateadetest võib leida ka info selle kohta, kes haavatavuse avastas ja Microsoftile teatas.

See aitab uurida:

* milline turvateadlane haavatavuse leidis;
* milline ettevõte või uurimisrühm seda uuris;
* kas avaldati tehniline analüüs;
* kas leidub täiendavaid uurimismaterjale.

---

# 6. Microsoft Threat Intelligence

[Microsoft Threat Intelligence](https://www.microsoft.com/en-us/security/security-insider/threat-landscape?utm_source=chatgpt.com)

Seda allikat võib kasutada juhul, kui uuritakse Windowsi haavatavuse seost reaalse ründetegevusega.

### Otsi näiteks

```text
"CVE-2026-XXXX" Microsoft threat intelligence
```

või

```text
"CVE-2026-XXXX" attacker
```

### Uuri

* kas Microsoft on kirjeldanud ründekampaaniat;
* kas nimetatakse ründajarühma;
* millist süsteemi rünnati;
* kuidas haavatavust kasutati;
* milline oli rünnaku eesmärk.

---

# 7. Android Security Bulletins

Androidi haavatavuste puhul kasuta esmajärjekorras Androidi ametlikke turvabülletääne.

[Android Security Bulletins](https://source.android.com/docs/security/bulletin?utm_source=chatgpt.com)

Androidi ametlikus keskkonnas avaldatakse Androidi platvormi, Linuxi kerneli ja tootjapõhiste komponentide turvaparandusi. ([Android Open Source Project][5])

### Mida otsida?

* CVE
* Android component
* severity
* vulnerability type
* affected versions
* security patch level
* AOSP reference

### Näiteks

```text
CVE-2026-28604
```

Kontrolli:

1. millises Androidi komponendis probleem asub;
2. kas tegemist on RCE, EoP, DoS või muu probleemiga;
3. milline on severity;
4. milline Android Security Patch Level parandab probleemi.

---

# 8. Android Pixel Security Bulletins

Androidi üldine bulletin ei pruugi kirjeldada kõiki konkreetse seadmetootja parandusi.

Google Pixel seadmete puhul vaata ka Pixel Update Bulletins.

[Android Pixel Security Bulletins](https://source.android.com/docs/security/bulletin/pixel?utm_source=chatgpt.com)

### Uuri

* milline Pixel mudel on mõjutatud;
* milline patch paranduse sisaldab;
* kas tegemist on Androidi platvormi või Pixel-spetsiifilise probleemiga.

---

# 9. Apple Security Releases

macOS-i, iOS-i ja iPadOS-i uurimisel kasuta Apple'i ametlikke turvateateid.

[Apple Security Releases](https://support.apple.com/en-us/100100?utm_source=chatgpt.com)

Apple avaldab turvauuenduste kohta infot ja viitab võimalusel CVE-ID-dele. ([Apple Support][6])

### Otsi

* macOS
* iOS
* iPadOS
* CVE
* Kernel
* WebKit
* ImageIO
* IOGPUFamily
* Bluetooth
* Safari
* sandbox

### Õpilase ülesanne

Leia:

1. mõjutatud Apple'i operatsioonisüsteem;
2. mõjutatud komponent;
3. CVE;
4. probleemi kirjeldus;
5. mõju;
6. parandatud versioon;
7. väljalaske kuupäev.

---

# 10. Apple Product Security / Apple Security Research

[Apple Security Research](https://security.apple.com/?utm_source=chatgpt.com)

Apple'i turbeuurimise leht sisaldab Apple'i turvatehnoloogiate ja turbeuuringutega seotud materjale. ([Apple Security Research][7])

Seda võib kasutada eelkõige siis, kui õpilane soovib liikuda CVE tavakirjeldusest sügavamale tehnilisse analüüsi.

---

# 11. Linuxi distributsioonide turvateated

Linuxi puhul ei piisa alati ainult sõnast "Linux".

Õpilane peab välja selgitama, millise distributsiooniga on tegemist.

Näiteks:

* Ubuntu
* Debian
* Red Hat Enterprise Linux
* Rocky Linux
* AlmaLinux
* SUSE

---

# 12. Red Hat Security

[Red Hat Security Advisories](https://access.redhat.com/security/?utm_source=chatgpt.com)

Red Hati kasutamisel otsi:

```text
CVE-XXXX-XXXXX
```

### Uuri

* mõjutatud RHEL versioon;
* pakett;
* parandatud pakett;
* errata;
* severity;
* CVSS;
* workaround.

---

# 13. Ubuntu Security Notices

[Ubuntu Security Notices](https://ubuntu.com/security/notices?utm_source=chatgpt.com)

Ubuntu serverite puhul kontrolli:

* CVE;
* Ubuntu versioon;
* mõjutatud pakett;
* parandatud paketi versioon;
* USN-number.

Näiteks:

```text
CVE-XXXX-XXXXX
```

ja

```text
site:ubuntu.com/security/notices CVE-XXXX-XXXXX
```

---

# 14. Debian Security Tracker

[Debian Security Tracker](https://security-tracker.debian.org/?utm_source=chatgpt.com)

Debiani puhul saab uurida:

* kas pakett on mõjutatud;
* millised Debiani versioonid on mõjutatud;
* kas probleem on parandatud;
* milline paketi versioon sisaldab parandust.

---

# 15. Linux Kernel

[Linux Kernel Archives](https://www.kernel.org/?utm_source=chatgpt.com)

Linuxi kerneli probleemide puhul aitab see kontrollida:

* kerneli versiooni;
* väljalaskeid;
* paranduste olemasolu;
* upstream-kerneli arengut.

### Õpilase oluline küsimus

Kui CVE mõjutab Linuxi kernelit:

> Kas haavatavus mõjutab kõiki Linuxi distributsioone või ainult konkreetset kerneli/paketi versiooni?

See erinevus tuleb töös välja tuua.

---

# 16. MITRE ATT&CK

[MITRE ATT&CK](https://attack.mitre.org/?utm_source=chatgpt.com)

MITRE ATT&CK ei ole CVE-andmebaas.

Seda kasutatakse selleks, et kirjeldada **rünnaku taktikaid ja tehnikaid**.

Näiteks võib õpilane uurida, millise ATT&CK tehnikaga seostub:

* privilege escalation;
* credential access;
* lateral movement;
* persistence;
* command and control;
* execution.

### Näiteks rünnakuahel

```text
CVE
 ↓
Initial Access
 ↓
Exploitation
 ↓
Privilege Escalation
 ↓
Credential Access
 ↓
Lateral Movement
 ↓
Impact
```

---

# 17. FIRST CVSS

**CVSS – Common Vulnerability Scoring System**

[FIRST CVSS](https://www.first.org/cvss/?utm_source=chatgpt.com)

CVSS aitab kirjeldada haavatavuse tehnilist tõsidust ja arvutada selle põhjal skoori. FIRST haldab CVSS-i standardit ning praegune standard on CVSS 4.0. ([first.org][8])

### Õpilane peab vaatama vähemalt

* Attack Vector
* Attack Complexity
* Attack Requirements
* Privileges Required
* User Interaction
* Confidentiality
* Integrity
* Availability

### Oluline

CVSS ei tähenda automaatselt:

> "Kui suure tõenäosusega organisatsiooni rünnatakse."

See kirjeldab haavatavuse omadusi ja mõju. Organisatsiooni tegelik risk sõltub ka keskkonnast ja muudest teguritest.

---

# 18. FIRST EPSS

**EPSS – Exploit Prediction Scoring System**

[FIRST EPSS](https://www.first.org/epss/?utm_source=chatgpt.com)

EPSS-i kasutatakse selle hindamiseks, kui tõenäoline on CVE ärakasutamine lähitulevikus.

See on kasulik võrdlus:

```text
CVSS = kui tõsine on haavatavus?
EPSS = kui tõenäoline on selle ärakasutamine?
KEV  = kas seda on teadaolevalt juba looduses kasutatud?
```

EPSS-i andmeid avaldatakse regulaarselt ning 2026. aastal kasutusel olev EPSS v5 alustas avaldamist 15. juunil 2026. ([first.org][9])

---

# 19. Google Project Zero

[Google Project Zero](https://googleprojectzero.blogspot.com/?utm_source=chatgpt.com)

Project Zero avaldab süvatehnilisi turbeuuringuid.

See on eriti kasulik:

* kernelivigade;
* brauserivigade;
* sandbox escape probleemide;
* privilege escalation probleemide;
* exploit chain'ide

uurimiseks.

### Otsing

```text
site:googleprojectzero.blogspot.com CVE-XXXX-XXXXX
```

---

# 20. Google Threat Intelligence / Mandiant

[Google Threat Intelligence](https://cloud.google.com/security/mandiant?utm_source=chatgpt.com)

Seda allikat saab kasutada reaalse ründetegevuse uurimiseks.

Otsi näiteks:

```text
"CVE-XXXX-XXXXX" Mandiant
```

või

```text
"CVE-XXXX-XXXXX" "Google Threat Intelligence"
```

### Uuri

* threat actor;
* campaign;
* malware;
* attack vector;
* target;
* exploitation;
* timeline.

---

# 21. Palo Alto Networks Unit 42

[Palo Alto Networks Unit 42](https://unit42.paloaltonetworks.com/?utm_source=chatgpt.com)

Unit 42 avaldab threat intelligence'i ja rünnakute tehnilisi analüüse.

Kasuta seda näiteks juhul, kui tahad teada:

> Kas seda CVE-d kasutati reaalses rünnakukampaanias?

---

# 22. Cisco Talos

[Cisco Talos Intelligence](https://blog.talosintelligence.com/?utm_source=chatgpt.com)

Cisco Talos avaldab pahavara, rünnakute ja haavatavuste tehnilisi analüüse.

Kasulik otsing:

```text
"CVE-XXXX-XXXXX" "Talos"
```

---

# 23. ESET WeLiveSecurity

[ESET WeLiveSecurity](https://www.welivesecurity.com/?utm_source=chatgpt.com)

ESET avaldab infot:

* pahavara;
* APT-rühmade;
* ründekampaaniate;
* zero-day'de;
* haavatavuste ärakasutamise

kohta.

---

# 24. BleepingComputer

[BleepingComputer](https://www.bleepingcomputer.com/?utm_source=chatgpt.com)

BleepingComputer on kasulik sõltumatu tehnilise ajakirjanduse allikas.

Näiteks:

```text
"CVE-XXXX-XXXXX" BleepingComputer
```

### Kasuta seda eelkõige

* reaalse rünnaku leidmiseks;
* ransomware-kampaaniate uurimiseks;
* zero-day juhtumite uurimiseks;
* tootja teate täiendamiseks.

### Tähtis

Uudisartikkel ei tohiks olla sinu töö ainus allikas.

---

# 25. The Record

[The Record by Recorded Future News](https://therecord.media/?utm_source=chatgpt.com)

Sobib eriti:

* ründekampaaniate;
* threat actor'ite;
* riiklikult toetatud rünnete;
* kriitilise infrastruktuuri;
* nullpäevarünnete

uurimiseks.

---

# 26. Exploit-DB

[Exploit Database](https://www.exploit-db.com/?utm_source=chatgpt.com)

Exploit-DB sisaldab avalikult dokumenteeritud exploite.

Otsi:

```text
CVE-XXXX-XXXXX
```

### Õpilane peab eristama

**PoC olemasolu**

ja

**reaalne rünnak**

Need ei ole sama asi.

Kui Exploit-DB-st leitakse exploit, võib kirjutada:

> "Selle CVE kohta on avalikult dokumenteeritud exploit/PoC."

Ei tohi sellest automaatselt järeldada:

> "Seda exploit'i kasutati reaalses rünnakus."

---

# 27. GitHub

[GitHub](https://github.com/?utm_source=chatgpt.com)

GitHubist võib leida:

* PoC-e;
* exploit'e;
* turbeuurijate tööriistu;
* analüüse;
* exploitide reproduktsioone;
* patch-diffe.

### Otsing

```text
"CVE-XXXX-XXXXX"
```

või

```text
"CVE-XXXX-XXXXX" PoC
```

### TURVANÕUE

Õpilane **ei tohi käivitada tundmatut exploit'i kooli arvutis, tootmisserveris ega päris võrgus**.

Kui praktiline analüüs on õpetaja poolt lubatud:

* kasuta isoleeritud virtuaalmasinat;
* kasuta snapshot'i;
* kasuta eraldatud laborivõrku;
* kasuta ainult õpetaja määratud keskkonda.

---

# 28. CERT-EU

[CERT-EU](https://cert.europa.eu/?utm_source=chatgpt.com)

CERT-EU on Euroopa Liidu institutsioonide ja organite küberturbeüksus.

Kasulik Euroopa konteksti uurimiseks.

Otsi:

```text
site:cert.europa.eu CVE-XXXX-XXXXX
```

---

# 29. RIA / CERT-EE

[Riigi Infosüsteemi Amet (RIA)](https://www.ria.ee/?utm_source=chatgpt.com)

Eesti konteksti puhul tasub uurida RIA ja CERT-EE teateid.

Kasulik küsimus:

> Kas uuritaval haavatavusel on olnud Eesti organisatsioonide või Eesti küberruumiga seotud mõju?

Otsi näiteks:

```text
site:ria.ee CVE-XXXX-XXXXX
```

---

# 30. Kuidas leida CVE kohta päris rünnak?

Kasuta järgmise skeemi.

## Samm 1 – alusta CVE-st

```text
CVE-XXXX-XXXXX
```

## Samm 2 – otsi tehnilist kirjeldust

```text
"CVE-XXXX-XXXXX"
```

## Samm 3 – otsi exploit'i

```text
"CVE-XXXX-XXXXX" exploit
```

## Samm 4 – otsi reaalselt toimunud rünnakut

```text
"CVE-XXXX-XXXXX" attack
```

```text
"CVE-XXXX-XXXXX" exploited
```

```text
"CVE-XXXX-XXXXX" campaign
```

## Samm 5 – otsi ründajat

```text
"CVE-XXXX-XXXXX" "threat actor"
```

```text
"CVE-XXXX-XXXXX" APT
```

## Samm 6 – otsi pahavara

```text
"CVE-XXXX-XXXXX" malware
```

```text
"CVE-XXXX-XXXXX" ransomware
```

## Samm 7 – kontrolli KEV-ist

```text
CVE-XXXX-XXXXX
```

CISA KEV:

[CISA KEV Catalog](https://www.cisa.gov/known-exploited-vulnerabilities-catalog?utm_source=chatgpt.com)

---

# 31. Õpilase tõendite hierarhia

Uurimistöös kasuta võimalusel järgmises järjekorras allikaid.

| Tase | Allikas                   | Milleks?                         |
| ---- | ------------------------- | -------------------------------- |
| 1    | Tootja ametlik turvateade | Parandus ja mõjutatud versioon   |
| 2    | CVE.org                   | CVE põhiinfo                     |
| 3    | NVD                       | CVSS ja tehnilised andmed        |
| 4    | CISA KEV                  | Teadaolev aktiivne ärakasutamine |
| 5    | Threat Intelligence       | Reaalne ründetegevus             |
| 6    | Tehniline uurimus         | Exploit'i tehniline analüüs      |
| 7    | Sõltumatu meedia          | Ründe sündmuse kirjeldus         |
| 8    | GitHub / Exploit-DB       | PoC ja exploit                   |

---

# 32. Kolm tõendit iga uuritava CVE kohta

Iga õpilane peaks püüdma leida vähemalt kolm erinevat tüüpi tõendit.

## Tõend 1 – tehniline

Näiteks:

* NVD;
* CVE.org;
* tootja bulletin.

Näitab:

> **Mis probleemiga on tegemist?**

---

## Tõend 2 – parandamine

Näiteks:

* Microsoft MSRC;
* Apple Security;
* Android Security Bulletin;
* Ubuntu Security Notice;
* Red Hat Security.

Näitab:

> **Kuidas probleem parandati?**

---

## Tõend 3 – reaalne rünnak

Näiteks:

* CISA KEV;
* Mandiant;
* Unit 42;
* ESET;
* Microsoft Threat Intelligence;
* Google Threat Intelligence;
* Cisco Talos;
* usaldusväärne tehniline meedia.

Näitab:

> **Kas ja kuidas seda tegelikult rünnakus kasutati?**

---

# 33. Reaalse rünnaku kirjeldamise vorm

Kui leiad konkreetse rünnaku, täida järgmine tabel.

| Küsimus                           | Vastus |
| --------------------------------- | ------ |
| CVE                               |        |
| Ründaja / threat actor            |        |
| Rünnaku periood                   |        |
| Ohver / sihtmärk                  |        |
| Ründevektor                       |        |
| Haavatav komponent                |        |
| Kuidas CVE-d kasutati?            |        |
| Kas kasutati exploit'i?           |        |
| Kas tegemist oli zero-day'ga?     |        |
| Milline ligipääs saadi?           |        |
| Milline oli mõju?                 |        |
| Milline pahavara kasutati?        |        |
| Kas järgnes privilege escalation? |        |
| Kas toimus lateral movement?      |        |
| Kas kasutati ransomware'i?        |        |
| Milline allikas seda tõendab?     |        |

---

# 34. Väga oluline mõistete eristus

Uurimistöös kasuta järgmisi mõisteid täpselt.

### Vulnerability

Turvanõrkus tarkvaras või süsteemis.

### Exploit

Meetod või kood, millega haavatavust ära kasutatakse.

### PoC

**Proof of Concept** – demonstratsioon, mis näitab, et haavatavust on võimalik ära kasutada.

### Exploitation in the wild

Haavatavuse kasutamine päris ründes.

### Zero-day

Haavatavus, mille kohta puudub kaitsjatel piisav etteteatamisaeg enne selle aktiivset ärakasutamist; mõiste täpne kasutus sõltub kontekstist.

### CVSS

Haavatavuse tehnilise tõsiduse hindamise süsteem.

### EPSS

Mudelpõhine hinnang CVE ärakasutamise tõenäosusele.

### KEV

CISA kataloog teadaolevalt looduses ära kasutatud haavatavustest.

---

# 35. Õpilase lõplik uurimisrada

Soovitatav uurimisjärjekord on:

```text
                CVE
                 │
                 ▼
          ┌──────────────┐
          │   CVE.org    │
          └──────┬───────┘
                 │
                 ▼
          ┌──────────────┐
          │     NVD      │
          └──────┬───────┘
                 │
                 ▼
        ┌──────────────────┐
        │ Tootja bulletin  │
        └────────┬─────────┘
                 │
        ┌────────┴─────────┐
        ▼                  ▼
      CVSS                KEV
        │                  │
        │                  ▼
        │          Kas kasutati
        │          päris ründes?
        │                  │
        └────────┬─────────┘
                 ▼
        Threat Intelligence
                 │
                 ▼
          Reaalne rünnak
                 │
                 ▼
           MITRE ATT&CK
                 │
                 ▼
           Rünnakuahel
```

---

# 36. Soovitatav minimaalne allikate arv

Ühe CVE kohta soovitan kasutada vähemalt:

* **1 tootja ametlik allikas**
* **1 CVE/NVD allikas**
* **1 ekspluateerimise või threat intelligence'i allikas**

Kui võimalik:

* 1 CISA KEV kontroll;
* 1 sõltumatu tehniline analüüs;
* 1 rünnakukampaaniat kirjeldav allikas.

---

# 37. Allika kasutamise näide

Ära kirjuta:

> CVE-XXXX-XXXXX on väga ohtlik ja seda kasutati häkkerite poolt.

Kirjuta:

> NVD kirjeldab CVE-XXXX-XXXXX haavatavuse tehnilisi omadusi ja mõju. Tootja turvateade kirjeldab mõjutatud versioone ning parandust. CISA KEV kataloogis on CVE märgitud teadaolevalt looduses ära kasutatud haavatavusena. Sõltumatu threat-intelligence'i analüüs kirjeldab selle kasutamist konkreetse ründekampaania käigus.

Nii on iga väide seotud konkreetse tõendiga.

---

# 38. Õpilase töö lõpus kasutatav allikate tabel

Iga õpilane võiks lisada oma töö lõppu tabeli:

| Nr | Allikas             | Tüüp      | Mida sellest kasutasin? | Kuupäev |
| -: | ------------------- | --------- | ----------------------- | ------- |
|  1 | CVE.org             | Ametlik   | CVE kirjeldus           |         |
|  2 | NVD                 | Tehniline | CVSS                    |         |
|  3 | Tootja bulletin     | Ametlik   | Parandus                |         |
|  4 | CISA KEV            | Ametlik   | Exploitation            |         |
|  5 | Threat Intelligence | Analüüs   | Reaalne rünnak          |         |
|  6 | Sõltumatu meedia    | Uudis     | Rünnaku sündmus         |         |

---

# 39. Kõige olulisem reegel

**Ära otsi ainult infot CVE kohta.**

Uurimistöö eesmärk on liikuda:

```text
CVE
 ↓
tehniline viga
 ↓
ekspluateerimise võimalus
 ↓
exploit / PoC
 ↓
reaalne kasutamine
 ↓
rünnakuahel
 ↓
mõju organisatsioonile
 ↓
parandus ja kaitse
```

Just see muudab CVE numbri lihtsalt nimekirjast **küberturvalisuse uurimisobjektiks**.


[1]: https://www.cisa.gov/known-exploited-vulnerabilities-catalog?_cldee=jpUNG-sft0Lr74jsazzEoZ17jTSFEy6lQDvCJArCUYT0C1B4SWuT29F0dx7GSSfN&esid=f8736f67-efb4-ec11-983e-000d3a5a7186&f%5B0%5D=vendor_project%3A813&f%5B1%5D=vendor_project%3A855&f%5B2%5D=vendor_project%3A924&f%5B3%5D=vendor_project%3A1277&f%5B4%5D=vendor_project%3A1320&page=1&recipientid=contact-41de9e007aace811a963000d3a363879-a794569726594743b07ecd7aaff0e97c&utm_source=chatgpt.com "Known Exploited Vulnerabilities Catalog | CISA"
[2]: https://www.nist.gov/itl/nvd?utm_source=chatgpt.com "National Vulnerability Database | NIST"
[3]: https://www.nist.gov/news-events/news/2026/04/nist-updates-nvd-operations-address-record-cve-growth?utm_source=chatgpt.com "NIST Updates NVD Operations to Address Record CVE Growth | NIST"
[4]: https://www.microsoft.com/en-us/msrc/faqs-security-update-guide?utm_source=chatgpt.com "Security Update Guide FAQs"
[5]: https://source.android.com/docs/security/bulletin?utm_source=chatgpt.com "Android Security and Update Bulletins  |  Android Open Source Project"
[6]: https://support.apple.com/en-by/100100?utm_source=chatgpt.com "Apple security releases - Apple Support (BY)"
[7]: https://security.apple.com/?utm_source=chatgpt.com "Apple Security Research"
[8]: https://www.first.org/cvss/?utm_source=chatgpt.com "Common Vulnerability Scoring System SIG"
[9]: https://www.first.org/epss/data?utm_source=chatgpt.com "Get the Data"
