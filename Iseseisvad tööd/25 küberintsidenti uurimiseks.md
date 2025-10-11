## 25 Kodutöö Teemat Konkreetsete Juhtumitega

Iga ülesande eesmärk on uurida toodud näidet ja analüüsida seda läbi NIST-i intsidentide käsitlemise nelja faasi (Ettevalmistus, Tuvastamine, Ohjeldamine, Järeltegevused).

### Etapp I: Ettevalmistus ja Poliitikad 📝

1.  **Varundusstrateegiad lunavara (Ransomware) vastu.**
    * **Juhtum:** **Colonial Pipeline (2021)**. Uuri, kas lunarahast hoolimata oli neil tagatud töö taastamine varundustest ja miks kogu torujuhe suleti.
2.  **Küberintsidentide sideplaanide kriitilisus.**
    * **Juhtum:** **Target Corporation (2013/2014)**. Analüüsi, kuidas juhtumi *pärast* intsidendi avalikuks tulemist edastatud ebaühtlane ja viivitatud kommunikatsioon kahjustas ettevõtte mainet ja tegevjuhti.
3.  **Intsidendile reageerimise meeskond (CSIRT/CERT) – struktuur ja rahastus.**
    * **Juhtum:** Uuri **CERT-EE (Eesti)** või **CISA (USA)** näitel, kuidas riiklikul tasandil CSIRT on üles ehitatud ja milliseid teenuseid nad pakuvad VKE-dele (väikestele ja keskmistele ettevõtetele) *enne* intsidenti.
4.  **Lauaõppuste (*Tabletop Exercises*) roll valmisolekus.**
    * **Juhtum:** **WannaCry (2017)**. Uuri, kuidas oleks regulaarsed lauaõppused (eriti paikamata turvaparanduste/patchide teemal) aidanud tervishoiuasutustel (nt. Suurbritannia NHS) rünnakut ennetada või kiiremini ohjeldada.
5.  **Kindlustuse ja küberriskide maandamine.**
    * **Juhtum:** **Maersk (NotPetya, 2017)**. Uuri, kuidas ulatuslik kahju (hinnanguliselt $300 miljonit) oleks võinud olla kaetud või mõjutatud küberkindlustuspoliitikast ja kuidas kindlustusandjad oleksid võinud nõuda paremat ettevalmistust.

---

### Etapp II: Tuvastamine ja Analüüs 🔎

6.  **Ebatavaliste sisselogimiste tuvastamine.**
    * **Juhtum:** **SolarWinds (2020/2021)**. Fookus ründaja (tõenäoliselt APT29/Cozy Bear) *pikaajalisele viibimisele* süsteemides ja kuidas ebatavalisi sisselogimisi oleks SIEM-i ja käitumisanalüüsi abil pidanud varem tuvastama.
7.  **Siseinfo lekete (*Insider Threat*) avastamine logide abil.**
    * **Juhtum:** **Edward Snowden (NSA, 2013)**. Uuri, kuidas Snowden sai ligipääsu ja eksfiltreeris salastatud dokumente. Analüüsi, millised auditi- ja ligipääsulogid oleksid tema tegevusele varem viidanud.
8.  **Digitaalse forensika roll rünnaku ajajoone loomisel.**
    * **Juhtum:** **Equifax (2017)**. Keskendu sellele, kuidas uurijad tuvastasid ründaja ligi 76 päeva pikkuse viibimise võrgus ja andmete varguse (*exfiltration*) aja.
9.  **Kahe-faktorilise autentimise (MFA) möödaminek ja selle tuvastamine.**
    * **Juhtum:** **Uber (2022)**. Uuri, kuidas teismeline häkker kasutas sotsiaalmanipulatsiooni (MFA fatigue) ja teisi tehnikaid, et pääseda ligi Uberi süsteemidele, ja kuidas ebaõnnestunud MFA-katsed pidid logides kajastuma.
10. **Aktiivne ohujaht (*Threat Hunting*) vs. reaktiivne tuvastamine.**
    * **Juhtum:** Uuri näiteid, kus küberkaitseettevõtted (nt. Mandiant, CrowdStrike) avastasid pikaajalisi ründajaid (APT gruppe) oma klientide võrkudest *enne* suurt kahju.

---

### Etapp III: Ohjeldamine, Kaotamine ja Taastamine 🛑

11. **Võrgusegmentimise (Network Segmentation) tähtsus ohjeldamisel.**
    * **Juhtum:** **NotPetya (2017)**. Analüüsi, kuidas see pahavara levis kiiresti võrkudes, millel puudus korralik segmentatsioon, tabades ettevõtteid nagu Maersk.
12. **Nullpäeva (Zero-Day) haavatavuse kiirparanduse protsess.**
    * **Juhtum:** **Log4Shell (2021, Log4j viga)**. Uuri, kui kriitiline oli ajaaken tuvastamise ja parandamise vahel ning kuidas organisatsioonid püüdsid ohjeldada haavatavust, mis puudutas lugematuid tarkvarasid.
13. **Teenusetõkestusrünnaku (DDoS) ohjeldamise strateegiad.**
    * **Juhtum:** **GitHubi DDoS (2018) või mõni Eesti riigiasutuse vastane rünnak (2022)**. Uuri, kuidas rünnaku ohjeldamine toimus (sh. välise teenuse/kaitse sisselülitamine).
14. **Pahavara (*Malware*) eemaldamine ja süsteemide puhtuse tagamine.**
    * **Juhtum:** **Mirai Botnet (2016)**. Uuri, kuidas pahavara nakatas massiliselt turvaseadmeid (IoT-seadmeid) ja kuidas nende "puhastamine" oli raskendatud.
15. **Krüpteeritud andmete taastamine (v.a lunavara).**
    * **Juhtum:** Uuri mõne **suure IT-rikke** näitel, kus andmed muutusid kättesaamatuks süsteemivea tõttu ja kuidas taastumine toimus krüpteeritud varukoopiate abil.
16. **Äritegevuse järjepidevuse (BCP) ja taasteplaanide (DRP) roll.**
    * **Juhtum:** **Code Spaces (2014)**. Uuri, kuidas see pilveettevõte läks pankrotti, kuna ründaja kustutas nende andmed ja varundused – ja kuidas DRP puudumine oli saatuslik.

---

### Etapp IV: Järeltegevused ja Õppetunnid 📝

17. **Isikuandmete kaitse määruse (GDPR) rikkumisest teavitamise protsess.**
    * **Juhtum:** **British Airways (2018)** või **Marriott Internationali andmeleke**. Analüüsi, kas ja kuidas täideti **72-tunnine teavitamiskohustus** Andmekaitse Inspektsioonile ja avalikkusele.
18. **Intsidendi lõpparuande ja õppetundide (Lessons Learned) dokumenteerimine.**
    * **Juhtum:** **Mandiant/FireEye'i aruanne mõne APT grupi (nt. APT30) kohta**. Uuri, kuidas välised uurijad kirjeldavad oma analüüsi tulemusi ja milliseid konkreetseid parandusi nad soovitavad.
19. **Tõendite ahela (*Chain of Custody*) tähtsus ja dokumenteerimine.**
    * **Juhtum:** Uuri mõne **kriminaaljuhtumi** näitel, kus digitaalseid tõendeid kahtluse alla seati, kuna forensika meeskond ei järginud rangelt tõendite ahelat.
20. **Reageerimise protsessi automatiseerimine (SOAR).**
    * **Juhtum:** Uuri, kuidas kaasaegsed **EDR/SOAR** lahendused (nt. SentinelOne/Cortex XSOAR) reageeriksid automaatselt tuntud lunavararünnakule, isoleerides masina ilma inimsekkumiseta.

---

### Eriintsidendid ja Kaasaegsed Teemad 🌐

21. **Andmepüügi (*Phishing*) kampaaniate terviklik käsitlemine.**
    * **Juhtum:** **RSA SecurID (2011)**. Ründajad kasutasid andmepüüki, et saada ligipääs turvafirma RSA võrku, mis viis SecurID tokenite võtmete varastamiseni.
22. **Pilveintsidentide (Cloud Incident) käsitlemine ja vastutuse jagamine.**
    * **Juhtum:** **Capital One andmeleke (2019)**. Andmeleke toimus valesti konfigureeritud **Amazon Web Services (AWS)** tulemüüri kaudu.
    * *Fookus:* Kes vastutas antud juhul (Capital One või AWS) vastavalt **Jagatud Vastutuse Mudelile**.
23. **Tööstuslike kontrollsüsteemide (ICS/OT) küberintsidendid.**
    * **Juhtum:** **Triton/Trisis pahavara rünnak (2017)** Saudi Araabia naftatöötlemistehasele.
    * *Fookus:* Miks on OT-süsteemide käsitlemine eluohtlike tagajärgede tõttu erinev.
24. **Supply Chain rünnakute käsitlemine.**
    * **Juhtum:** **Kaseya VSA lunavararünnak (2021)**. Uuri, kuidas VSA haldustarkvara haavatavuse ärakasutamine lubas ründajatel rünnata korraga tuhandeid allkasutajaid.
25. **Tehisintellekti (AI) kasutamine intsidentide tuvastamisel ja reageerimisel.**
    * **Juhtum:** Vali näide turvalahenduse (nt. EDR) kasutamisest, mis tuvastas **varjatud liikumise** (*lateral movement*) võrgus, mida inimanalüütikud ei märganud. Analüüsi AI rolli selles tuvastamises.
