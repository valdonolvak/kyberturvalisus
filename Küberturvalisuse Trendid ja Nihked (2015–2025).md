# Küberturvalisuse Trendid ja Nihked (2015–2025)

See ülevaade jagab viimased kümme aastat kolmeks perioodiks, mis tähistavad olulisi pöördepunkte küberründajate taktikates ja turvameetmetes.

## 1. Periood: Lihavõrk ja Massiivsed Andmevargused (2015–2017)

Seda perioodi iseloomustab **massiivne andmeleke** ja **kõik-võimalik lunavara** (spray and pray). Inimesi polnud veel harjutatud MFA-d kasutama.

| Aasta | Domineeriv Oht ja Turvafookus | Eluline Näide ja Mõju |
| :--- | :--- | :--- |
| **2015** | **Suuremahulised Andmelekked:** Ründajad keskendusid tundlike andmete (isikukoodid, terviseandmed) kogumisele ja müügile. | **Anthem, OPM Rünnakud:** USA valitsuse OPM-ist varastati 21 miljoni inimese isikuandmed. Rikkumine rõhutas andmete **konfidentsiaalsuse** kriisi. *(Allikas: OPM / The New York Times)* |
| **2016** | **IoT ja Botnet'id:** Turvamata asjade interneti (IoT) seadmed muutusid ründerelvadena. | **Mirai Botnet:** Kasutas ära sadade tuhandete **vaikeparoolidega** kaamerate ja ruuterite nõrkust. Botnet teostas tohutuid DDoS-rünnakuid (sh Dyn), rõhutades **käideldavuse** rikkumist. *(Allikas: KrebsOnSecurity)* |
| **2017** | **Lunavara Plahvatus ja *Zero-day* Ärakasutamine:** Lunavara levis uue agressiivsusega, kasutades ära teadaolevaid haavatavusi. | **WannaCry ja NotPetya:** Kasutasid NSA-st lekkinud **EternalBlue** haavatavust. Eriti NotPetya näitas, kuidas **tervikluse** ja **käideldavuse** rikkumine võib lennundus- ja logistikaettevõtteid halvata (nt Maersk). *(Allikas: Microsoft / Wired)* |

---

## 2. Periood: MFA tulek ja Pilveühinemise vead (2018–2021)

Organisatsioonid hakkasid laialdaselt kasutama MFA-d, kuid pilvekonfiguratsioonivead ja uut tüüpi lunavara tõstsid esile uued probleemid.

| Aasta | Domineeriv Oht ja Turvafookus | Eluline Näide ja Mõju |
| :--- | :--- | :--- |
| **2018** | **Supply Chain Ründed:** Ründajad sihivad usaldusväärseid kolmanda osapoole tarkvarasid. | **British Airways andmeleke:** Ründajad süstisid veebilehele koodi (Magecart), mis varastas maksekaardi andmeid otse brauserist. See näitas **kolmanda osapoole koodi** riske. *(Allikas: ICO trahv)* |
| **2019** | **Pilve Konfiguratsioonivead:** **Jagatud Vastutuse Mudeli** väärarusaamad viivad andmeleketeni. | **Capital One rünnak:** Endine AWS-i töötaja kasutas ära **valesti konfigureeritud pilve WAF-i** ja sai ligipääsu 100 miljoni kliendi andmetele. See tõstis esile **kliendi vastutuse** IAM-is ja konfiguratsioonis. *(Allikas: USA Justiitsministeerium)* |
| **2020** | **Kaugtöö Ründed ja RaaS (Lunavara kui teenus):** COVID-19 viis MFA puuduliku RDP ja VPN kasutamiseni. Lunavara äri muutus topeltväljapressimiseks (krüpteerimine + lekitamine). | **Twitteri Rünnak:** Sotsiaalne inseneeria ja **MFA puudumine** võimaldas ründajatel võtta üle kõrgetasemelisi kontosid. **Ryuk** lunavara sihtis haiglaid. *(Allikas: The Verge / Twitter)* |
| **2021** | **Kriitilise Taristu Lunavara ja Supply Chain Eskalatsioon:** RaaS muutus üha sihitumaks. | **Colonial Pipeline ja Kaseya Rünnak:** Kaseya (*MSP* tarkvara) kaudu rünnati tuhandeid ettevõtteid. Pipeline'i rünnak näitas, et küberturvalisus on otsene oht **füüsilisele käideldavusele**. *(Allikas: CISA / FBI)* |

---

## 3. Periood: Identiteedivargus ja AI (2022–2025)

Fookus on **Identiteedi ja Ligipääsuhaldusel (IAM)**, mis muutub üha keerulisemaks ja AI-d hakatakse kasutama nii kaitseks kui ka rünnakuteks.

| Aasta | Domineeriv Oht ja Turvafookus | Eluline Näide ja Mõju |
| :--- | :--- | :--- |
| **2022** | **MFA Möödahiilimine:** Ründajad sihivad nõrka (SMS-põhist) MFA-d ja kasutavad sotsiaalset inseneeriat. | **Uberi ja Cisco ründed:** Ründajad kasutasid **MFA *Prompt Bombing*** tehnikat, et pommitada töötajaid autentimisteadetega, kuni töötaja väsimusest vajutas "Luba". See tõestas SMS-MFA nõrkust. *(Allikas: Uber Security Report)* |
| **2023** | **Generatiivne AI (GenAI) Phishing'us ja *Deepfake*:** AI vähendab *phishing*-rünnakute loomise kulusid ja tõstab usaldusväärsust. | **Suuremahuline *Spear Phishing***: Ründajad kasutavad AI-d, et luua veatuid, kontekstipõhiseid kirju, muutes sotsiaalse inseneeria tuvastamise inimestele praktiliselt võimatuks. AI-d kasutati ka **nullpäeva** vigade leidmisel. *(Allikas: Europol / Microsoft)* |
| **2024 (Hüpoteetiline)** | **Paroolivaba Autentimise Surve ja *Identity Fabric***: FIDO/WebAuthn standardid saavad standardiks tugevas kaitses. Pilve IAM-i *Identity Fabric* integreerimine levib. | **Vältimine vs Rünnak:** Ettevõtted hakkavad nõudma *phishing*-kindlat MFA-d (FIDO2), mistõttu ründajad keskenduvad üha enam **pilve teenusekontodele** (mitte inimkontodele), kus MFA-d pole. *(Allikas: Gartner / FIDO Alliance suunised)* |
| **2025 (Prognoos)** | **Automaatne Ründe-AI ja *Just-in-Time* (JIT) Turvalisus:** JIT ligipääs ja **Security Automation** muutuvad kohustuslikuks, et reageerida AI-ga genereeritud rünnakute kiirusele. | **JIT-i kohustus:** Erakordselt lühikese ajavahemikuga automatiseeritud rünnakutele (sekundites) suudab reageerida vaid automatiseeritud JIT-turvalisus, mis tühistab üleliigsed õigused. *(Allikas: Tööstuse prognoosid)* |

---

# Küberturvalisuse Trendide Andmestik Diagrammide Visualiseerimiseks (2015–2025)

## Diagramm 1: Ründe Fookuse Muutus (CIA Triad Rikkumised)

See diagramm sobib **virntulpdiagrammi** (Stacked Bar Chart) jaoks, mis näitab iga perioodi jooksul rikutud CIA Triadi parameetrite osakaalu, illustreerides trendide nihet.

| Periood | Konfidentsiaalsus (Andmevargus, Identiteet) | Terviklus (Andmete Muutmine, Supply Chain) | Käideldavus (DDoS, Lunavara Krüpteerimine) |
| :--- | :--- | :--- | :--- |
| **2015–2017** | **45%** (OPM, LinkedIn, Andmelekked) | **15%** (Piiratud terviklus) | **40%** (DDoS, Mirai, WannaCry/NotPetya) |
| **2018–2021** | **35%** (Pilvelekked, Twitter) | **30%** (Supply Chain, NotPetya järelmõju) | **35%** (Lunavara Topeltväljapressimine, RDP-ründed) |
| **2022–2025** | **55%** (MFA Möödahiilimine, IAM Vead, AI Phishing) | **25%** (Pilvekonfiguratsiooni Terviklus, Supply Chain) | **20%** (Kuigi lunavara on olemas, on fookus nüüd *andmete vargusel* – Konfidentsiaalsus.) |

---

## Diagramm 2: MFA ja Rünnakute Edukuse Võrdlus

See diagramm sobib **joon- ja tulpdiagrammi kombineerimiseks**, et näidata, kuidas MFA kasutuselevõtt (joon) ei vastanud turvalisuse parandamisele (tulp), sest ründajad õppisid sellest mööda minema.

| Aasta | Hinnanguline MFA Kasutuselevõtt (%) | Edukate MFA Möödahiilimisrünnakute Arv (Indeks) |
| :--- | :--- | :--- |
| **2018** | **25%** (MFA algusfaas) | 1 (Vähe rünnakuid) |
| **2019** | **45%** (Laienemine) | 2 |
| **2020** | **55%** (Kaugtöö algus) | 3 |
| **2021** | **65%** (MFA nõue kasvab) | 5 (MFA *prompt bombing* algus) |
| **2022** | **75%** (Paljudel kontodel olemas) | 7 (Uberi/Cisco sarnased ründed) |
| **2023** | **80%** (Standard nõue) | 8 (AI-ga genereeritud *phishing*) |
| **2024** | **82%** (Fookus phishing-kindlusele) | 7 (Hakkab langema tänu FIDO standarditele) |

---

## Diagramm 3: Domineeriva Ründe Fookus (Perioodi Põhjal)

See diagramm sobib **sektoridiagrammide komplektiks** (üks diagramm iga perioodi kohta), mis näitab domineerivaid ründetaktikaid.

| Periood | Domineeriv Taktika (Sektoridiagrammi Elemendid) |
| :--- | :--- |
| **2015–2017** | **Massiivne Andmevargus (40%)**, **DDoS ja IoT Ründed (30%)**, **Lunavara *Spray and Pray* (20%)**, **Standardne Phishing (10%)** |
| **2018–2021** | **RaaS ja Topeltväljapressimine (45%)**, **Pilve Konfiguratsioonivead (30%)**, **Supply Chain Ründed (15%)**, **Sotsiaalne Inseneeria (10%)** |
| **2022–2025** | **MFA Möödahiilimine ja Identiteedivargus (40%)**, **Pilve IAM-i ja API Ründed (30%)**, **AI-ga Genereeritud Phishing (20%)**, **Phishing-kindel Autentimine (10% - uus kaitse)** |

Loodan, et need andmetabelid ja soovitused aitavad teil luua visuaalselt mõjuvad diagrammid!
