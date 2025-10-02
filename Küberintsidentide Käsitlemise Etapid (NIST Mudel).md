Intsidentide käsitlemise eesmärk on nagu tuletõrje- ja kiirabiteenuse kombineerimine: olla valmis, jõuda kohale, peatada kahju levik, parandada vigastused ning analüüsida juhtunut, et vältida kordumist.

## Küberintsidentide Käsitlemise Etapid (NIST Mudel - National Institute of Standards and Technology (eesti keeles: Riiklik Standardite ja Tehnoloogia Instituut))

Käsitlemise protsess koosneb neljast põhietapist, mis jagunevad detailsemateks alametappideks.

### 1. Ettevalmistus (Preparation) 🛠️

See faas toimub **enne** igasugust intsidenti ja on eduka reageerimise alus.

| Tegevus | Eesmärk ja Selgitus |
| :--- | :--- |
| **Poliitikad ja plaanid** | Luua kirjalik **Intsidentide Käsitlemise Plaan**, mis määrab ära, keda teavitada, kes vastutab ja milliseid protseduure järgida. See on "reegliraamat". |
| **Meeskonna loomine (CSIRT/CERT)** | Määrata kindlaks **Intsidendi Käsitlemise Meeskond** (Cyber Security Incident Response Team), nende rollid (juht, sideohvitser, tehniline analüütik) ja sidekanalid. |
| **Tehnilised vahendid** | Seadistada ja hooldada turvatööriistu: **SIEM** (Security Information and Event Management) logide kogumiseks ja analüüsiks, **IDS/IPS** (sissetungi avastamise/vältimise süsteemid) ning ajakohased **varundussüsteemid**. |
| **Koolitus ja õppused** | Viia regulaarselt läbi personali (nii IT- kui tavakasutajate) koolitusi ja korraldada lauaõppusi (**tabletop exercises**) meeskonna reageerimisvalmiduse testimiseks. |

***

### 2. Tuvastamine ja Analüüs (Detection & Analysis) 🔎

Selles etapis avastatakse intsident ja kogutakse esmased faktid.

| Tegevus | Eesmärk ja Selgitus |
| :--- | :--- |
| **Intsidendi allikad** | Jälgida sissetulevaid andmeid: **turva- ja võrguseadmete hoiatused**, kasutajate kaebused, viirustõrje teated või välised teated (nt. partneritelt). |
| **Valepositiivsete välistamine** | Veenduda, et tegu on tõelise intsidendiga, mitte süsteemi veaga (nt. tavapärase varundusprotsessi ekslik tõlgendus rünnakuna). |
| **Mõju hindamine (Triage)** | **Prioriseerida intsident** vastavalt selle tõsidusele ja mõjule äritegevusele (nt. kas puudutab kriitilist infrastruktuuri, avalikke andmeid või piiratud osa töötajatest). |
| **Digitaalforensika** | Koguda koheselt andmeid süsteemi hetkeoleku kohta (**mälu pildid, võrguliiklus, logid**), et hiljem saaks uurida, mis täpselt juhtus. Jälgida **tõendite ahelat** (*Chain of Custody*). |
| **Rünnaku iseloomustus** | Määrata kindlaks rünnaku tüüp (DDoS, pahavara, andmepüük), **algpõhjus** (haavatavus) ja sissetungija eesmärk. |

***

### 3. Ohjeldamine, Kaotamine ja Taastamine (Containment, Eradication & Recovery) 🛑

See on aktiivse tegevuse faas, mille eesmärk on peatada kahju ja taastada kord.

| Tegevus | Eesmärk ja Selgitus |
| :--- | :--- |
| **Ohjeldamine (Containment)** | Rünnaku leviku piiramine. Tuleb isoleerida nakatunud süsteemid, ilma et see aitaks ründajal tõendeid peita või süsteeme rohkem kahjustada. See võib olla lühiajaline (kohene eraldamine) või pikaajaline (uue, puhta võrgu loomine). |
| **Kaotamine (Eradication)** | Eemaldada ründaja tekitatud oht: kustutada pahavara, sulgeda ründaja loodud tagauksed (*backdoors*) ja parandada **haavatavused**, mida ründaja kasutas. |
| **Taastamine (Recovery)** | Taastada puhtad süsteemid ja teenused tavapärasesse tööolekusse. See hõlmab sageli puhtatest varundustest taastamist ja **põhjalikku monitoorimist**, et veenduda ründaja puudumises. Paroolide vahetamine on tihti kohustuslik. |

***

### 4. Järeltegevused (Post-Incident Activity) 📝

Pärast kriisi lahendamist on vaja õppida ja dokumenteerida.

| Tegevus | Eesmärk ja Selgitus |
| :--- | :--- |
| **Intsidendi dokumenteerimine** | Koostada detailne **lõpparuanne**, mis kirjeldab intsidendi aega, olemust, reageerimise samme, kulusid ja taastumist. See on aluseks hilisemale analüüsile. |
| **Õppetunnid (Lessons Learned)** | Meeskonna kohtumine, kus arutatakse, mis läks hästi ja mis halvasti. Tulemused viivad tagasi esimesse faasi: **uuendatakse küberkaitse strateegiat, poliitikaid ja tehakse süsteemidele vajalikke parandusi**. |
| **Õiguslikud nõuded** | Tagada vastavus regulatsioonidele, näiteks teavitada **Andmekaitse Inspektsiooni** isikuandmete lekke korral (GDPR). |

***

## 2 Praktilist Lisavõimalikku Näidet

### Näide 3: Teenusetõkestusrünnak (DDoS - Distributed Denial of Service)

#### Intsidendi Kirjeldus
Suure riigiasutuse veebiteenused muutuvad kättesaamatuks. Kasutajad ja kliendid ei pääse ligi kriitilisele avalikule teenusele, kuna võrk on üle ujutatud **ebatavalise ja massiivse andmeliiklusega**.

#### Käsitlemise Protsess

1.  **Tuvastamine ja Analüüs:**
    * **Tuvastamine:** Võrgu monitooringu süsteemid annavad kõrge prioriteediga hoiatuse ebatavalisest sissetulevast liiklusest. Kasutajatelt tuleb massiliselt teateid teenuse kättesaamatusest.
    * **Analüüs:** Tehakse kindlaks, et liiklus tuleb tuhandetelt erinevatelt IP-aadressidelt üle maailma (tegemist on **botnet'i** rünnakuga). Liikluse eesmärk on teenuse ülekoormamine.

2.  **Ohjeldamine:**
    * **Välise spetsialisti kaasamine:** Kuna DDoS rünnakud vajavad massiivset võimsust, suunatakse kogu sissetulev liiklus kohe **DDoS kaitseteenuse pakkuja** (nt. Cloudflare, Akamai) kaudu.
    * **Piirangute kehtestamine:** Rünnaku ajaks seadistatakse **geograafilised piirangud** ja **liiklusfilterdamine** tuntud pahaloomuliste allikate blokeerimiseks.

3.  **Kaotamine ja Taastamine:**
    * **Kaotamine:** Kaitseteenus "puhastab" sissetuleva liikluse, lubades serveriteni ainult seadusliku liikluse. Ründaja ressursid on endiselt aktiivsed, aga nad ei suuda enam eesmärgini jõuda.
    * **Taastamine:** Teenused taastuvad kättesaadavaks niipea, kui liiklus on puhastatud. Jälgitakse, et teenuse maht ja kiirus oleksid normaalsed.

4.  **Järeltegevused:**
    * **Õppetunnid:** Hinnatakse praeguse **DDoS kaitseplaani vastupidavust** ja sõlmitakse püsiv leping professionaalse kaitseteenuse pakkujaga. Võrguarhitektuuri ülevaatamine ja tugevdamine.

***

### Näide 4: Andmepüügi (Phishing) Kampaania

#### Intsidendi Kirjeldus
Suure logistikaettevõtte mitmed töötajad saavad e-kirja, mis näib olevat IT-osakonnalt. Kiri palub neil koheselt sisse logida uuele "turvaportaalile", et vältida parooli aegumist. Mitu töötajat sisestab oma paroolid pahatahtlikule saidile.

#### Käsitlemise Protsess

1.  **Tuvastamine ja Analüüs:**
    * **Tuvastamine:** Üks teadlik töötaja märkab kahtlast aadressi ja teatab kohe IT-le.
    * **Analüüs:** IT-meeskond tuvastab koheselt **andmepüügisaidi URL-i** ja teeb kindlaks, millised töötajad on lingile klõpsanud ja millal.

2.  **Ohjeldamine:**
    * **Ohu peatamine:** **Kõikide mõjutatud kasutajate paroolid vahetatakse koheselt** (parimal juhul sunnitakse neid ise uued paroolid looma) ja **kahe-faktoriline autentimine (MFA)** rakendatakse kohustuslikuks kõikidele kontodele.
    * **Pahaloomulise URL-i blokeerimine:** Ettevõtte võrgufiltrites ja tulemüürides blokeeritakse koheselt andmepüügisaidi URL ja sellega seotud IP-aadressid. E-posti süsteemis eemaldatakse kiri kõigi töötajate postkastidest.

3.  **Kaotamine ja Taastamine:**
    * **Kaotamine:** Mõjutatud kontodel kontrollitakse koheselt, kas ründaja on juba sisse loginud ja loonud endale tagauksed või uusi reegleid (nt. e-kirjade edasisaatmise reeglid). Kahtluse korral süsteemid pühitakse ja taastatakse.
    * **Taastamine:** Tavapärane töö jätkub pärast paroolide vahetamist ja MFA juurutamist.

4.  **Järeltegevused:**
    * **Õppetunnid:** Viivitamatult viiakse läbi **kogu ettevõtte ülene andmepüügi teadlikkuse koolitus**. E-posti süsteemi täiustatakse paremate automaatsete andmepüügi filtrite ja hoiatusmehanismidega. Tunnustatakse töötajat, kes intsidenti teatas.
