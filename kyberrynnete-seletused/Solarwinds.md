Allpool on **ühiseks õppematerjaliks kokku pandud terviklik, loogiliselt struktureeritud ja õpetlik tekst**, mis ühendab mõlemad eelnevad selgitused üheks ühtseks materjaliks.

Saad kasutada seda loengus, töötoas või õppematerjalina.

---

# **Software Supply Chain Attack – Päris eluline näide: SolarWinds Orion rünne (2020)**

**Õppematerjal IT-õpilastele**

Software supply chain attack ehk tarkvaratarneahela rünne on küberrünne, kus ründaja ei püüa otse murda sisse sihtorganisatsiooni võrku, vaid kompromiteerib **tarnija** – näiteks tarkvaraarendaja, kolmanda osapoole teegi või uuenduste süsteemi. Seejärel levib pahatahtlik kood legitiimsete tarkvarauuenduste kaudu otse ohvrite süsteemidesse. Kõige ohtlikumad rünnakud toimuvad tootja **CI/CD (Continuous Integration / Continuous Delivery)** protsessis, sest seal tehakse automaatseid kompileerimisi, testimisi ja allkirjastamisi, mida kliendid usaldavad.

2020. aastal leidis aset üks ajaloo suurimaid ja tehniliselt keerukamaid supply chain rünnakuid: **SolarWinds Orion kompromiteerimine**. See rünnak on suurepärane praktiline näide, kuidas tarneahela nõrkused võivad ohustada tuhandeid organisatsioone korraga.

---

# 1. **Kuidas ründajad tungisid SolarWinds’i arenduskeskkonda?**

Uurimise käigus selgus, et SolarWinds’i sisevõrk oli juba enne rünnet tehniliselt nõrk ja sisaldas mitmeid olulisi turvaprobleeme. Ründajad kasutasid neid haavatavusi nutikalt ära ning said samm-sammult ligipääsu ettevõtte kriitilisele arendusinfrastruktuurile.

---

## **1.1 Nõrgad ja lekkinud paroolid**

Uurijad leidsid, et SolarWinds’i Git-repositooriumis oli avalikult näha kontopaar:
**kasutaja: solarwinds, parool: solarwinds123**.

See parool lekkis kogemata GitHubi kaudu ning andis ründajatele otsese ligipääsu ettevõtte serveritele, sh võimaliku ligipääsu **update-serverile**.

Sellised nõrgad ja avalikult lekkivad paroolid olid ründajatele ideaalne alguspunkt.

---

## **1.2 Mitmefaktorilise autentimise puudumine (MFA)**

Mitmel administraatori kontol **puudus MFA**, mis võimaldas:

* kasutada varastatud paroole otse VPN-is,
* logida sisse sisevõrku ilma täiendavate kontrollideta,
* kasutada arendajaid ja teenusekontosid esmase sisenemispunktina.

See oligi üks peamisi põhjusi, miks rünnak õnnestus nii vaikselt.

---

## **1.3 Avalikud ja nõrgalt piiratud teenused internetis**

Mitu SolarWinds’i teenust olid internetist otse ligipääsetavad, sh:

* FTP serverid
* Orion'i haldusserverid

Mitmel neist puudus IP-põhine piirang või ranged tulemüürireeglid.

---

## **1.4 Ründajate esimene ligipääs (tõenäoline ahel)**

Kõige tõenäolisem sisenemisahel, mida uurijad kinnitasid:

1. **Parool lekkis GitHubi kaudu.**
2. Ründajad kasutasid kontot, et pääseda sisse VPN-i.
3. **MFA puudumine** võimaldas siseneda ainult parooliga.
4. Ründajad said ligipääsu **arendaja tööjaamale**.
5. Kasutati Windowsi sisseehitatud vahendeid:

   * Pass-the-Hash
   * Pass-the-Ticket
   * PowerShell Remoting
   * WMI / RDP
6. Tõusti privileegidega kuni **Domain Admin** tasemeni.
7. Ligipääs build-serverile.
8. Püsiv tagauks ja ligipääs arendusvõrgule kuude kaupa.

---

## **1.5 Build-serveri halb eraldatus – ründe pöördepunkt**

Arendajate tööjaamad ja build-server **olid ühes ja samas võrgus**, ilma tugevate piiranguteta.
See tähendas, et:

* üks kompromiteeritud arendaja =
* otsene tee CI/CD keskkonda =
* võimalus muuta tarkvara enne selle kompileerimist ja allkirjastamist.

Just see arhitektuurne viga võimaldas ründajatel taktikat **“compromise the build pipeline”**.

---

# 2. **Kuidas rünnak arenduskeskkonnas ellu viidi?**

Pärast ligi pool aastat SolarWinds’i arenduskeskkonnas varjatult tegutsenud ründajad jõudsid järgmisesse sammu: Orion tarkvara muutmine.

---

## **2.1 Malicious koodi lisamine SolarWinds Orion tarkvarasse**

Ründajad:

* sisestasid pahatahtliku backdoor’i koodi nimega **SUNBURST**,
* tegid seda **build-protsessi hilises faasis**, et vältida koodireview’d,
* peitsid backdoor'i C# koodi nagu see oleks legitiimne klass,
* obfuskeeritud funktsioonid ja stringid muutsid analüüsi keeruliseks.

SolarWinds arendajad ei näinud muudatust, sest:

* CI/CD oli detailideni automatiseeritud,
* build toimus automaatselt pärast muudatuste kinnitamist,
* pahavara süstiti koodi **vahetult enne kompileerimist**, mitte lähtekoodi faasis.

---

## **2.2 Allkirjastamine SolarWinds’i enda sertifikaadiga**

Legitiimne build-protsess tegi uuendusega automaatselt:

✔ kompileerimise
✔ pakendamise
✔ versioonihalduse
✔ digitaalse allkirjastamise

Seega SUNBURST kandis:

* SolarWinds’i **kehtivat koodiallkirjastamise sertifikaati**
* varianti “SolarWinds.Orion.Core.BusinessLayer.dll”

Kõik kliendid usaldasid seda sadade organisatsioonide turvapoliitikate tõttu.

---

## **2.3 Pahavara levik klientidele**

Pahavara sisaldav uuendus laaditi SolarWinds’i ametlikku update-serverisse.
Selle installisid automaatselt:

* USA valitsusasutused
* Suurkorporatsioonid
* Militaarorganisatsioonid
* Tehnoloogiaettevõtted
* Turvafirmad

Kokku **~18 000 süsteemi**.

Kõik paigaldasid * ise * rünnaku oma keskserverisse, sest uuendus oli:

* pärit ametlikult tootjalt,
* digitaalselt allkirjastatud,
* kontrollsummadega õigesti märgitud.

See ongi supply chain rünnakute suurim oht.

---

# 3. **Mis juhtus pärast kliendi kompromiteerimist?**

Kui SUNBURST oli paigaldatud, toimus järgmine:

---

## **3.1 Pahavara aktivaator – väga vaikne ja varjatud käitumine**

SUNBURST tegi:

* 2-nädalase vaikse “uneperioodi”, et varjata jälgi,
* kogus süsteemi, domeeni ja kontode infot,
* kodeeris andmed DNS-päringute sisse,
* suhtles C2 serveriga varjatud kanalil.

---

## **3.2 Ründajate edasine tegevus (AINULT valitud sihtmärkides)**

Kõiki 18 000 ohvrit ei rünnatud. Ründajad valisid sihtmärke, kust:

* varastati SAML võtmed (võimaldas võltsida Azure AD sisselogimisi),
* murti e-posti- ja dokumentide serveritesse,
* tehti lateraalliikumist domeeniseadmetesse,
* kasutati vaikseid ja logideta tehnikaid.

SUNBURST oli ainult **esimene faas**; tõeline spionaaž toimus hiljem käsitsi.

---

# 4. **Miks SolarWinds juhtum on ideaalne tarneahela rünnaku näide?**

| Supply chain tunnus                 | SolarWinds näites                                  |
| ----------------------------------- | -------------------------------------------------- |
| Rünnati tarkvara tarnijat           | SolarWinds arenduskeskkond                         |
| CI/CD kompromiteerimine             | Pahavara lisati build-protsessis                   |
| Tarkvara allkirjastati legitiimselt | Kasutati SolarWinds’i sertifikaati                 |
| Klient paigaldas rünnaku ise        | Uuendus levitati ametliku kanali kaudu             |
| Ründaja peitis oma jäljed           | Automatiseeritud ja tavalise tarkvarauuenduse sees |

See rünnak näitab, et:

* usaldus tarkvaratootja vastu on ründajatele suurepärane relv,
* CI/CD turvalisus on kriitiline,
* digitaalsed sertifikaadid ei päästa, kui build-server on kompromiteeritud,
* üks haavatav tarnija võib ohustada tuhandeid kliente.

---

# **Kokkuvõte õpilastele**

SolarWinds’i juhtum on õppematerjalina oluline, sest see näitab:

1. **Tarkvaraarenduse tarneahel on ründajate peamine sihtmärk.**
2. Ühe arendaja konto kompromiteerimine võib viia kogu toote kompromiteerimiseni.
3. CI/CD protsessid peavad olema tugevalt turvatud ja eraldatud.
4. Pahavara võib tulla “ametliku uuenduse” kujul, ilma, et ohver midagi kahtlustaks.
5. Supply chain rünnakud on äärmiselt ohtlikud ja raskesti avastatavad.

See rünnak on seni üks detailsemaid ja professionaalsemaid näiteid tarneahela kompromiteerimisest maailmas.

