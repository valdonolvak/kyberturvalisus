Uurimiseks välja **10 reaalset küberturvalisuse intsidenti**, mis jagunevad valitud ründeliikide (Phishing, DDoS, Lunavara) vahel.

Iga rühm saab valida nimekirjast ühe juhtumi oma detailseks analüüsiks.

---

## 10 Juhtumit Iseseisva Töö Uurimiseks

### A. Phishing (Õngitsemise) Juhtumid 🎣

Need juhtumid näitavad, kuidas inimfaktor on endiselt küberturvalisuse nõrgim lüli.

| Nr. | Ettevõte/Organisatsioon | Aasta | Ründe Detail | Uurimisfookus |
| :---: | :--- | :--- | :--- | :--- |
| **1** | **Twitter (2020 Bitcoin Scam)** | 2020 | Ründajad kasutasid **Spear Phishing'ut** Twitteri töötajate vastu, et saada ligipääs sisevahenditele ja **kaaperdada** kõrgetasemelisi kontosid (nt. Barack Obama, Elon Musk). | Sotsiaalne inseneeria sisevõrgus; MFA (mitmefaktoriline autentimine) puudumine. |
| **2** | **Google & Facebook (BEC)** | 2013-2015 | Leedu pettur saatis ettevõtetele pikema perioodi jooksul **võltsarveid (Business Email Compromise - BEC)**, esinedes Aasia elektroonikatootjana, ning sai petta neilt kümneid miljoneid dollareid. | Ärikirjavahetuse kompromiteerimine; finantsprotokollide nõrkused. |
| **3** | **Democratic National Committee (DNC)** | 2016 | Poliitiline rünnak, kus **Spear Phishing'uga** sihiti DNC töötajaid, et varastada ligipääsud ja hiljem lekkida sisekirjavahetus. | **Poliitiline küberrünne**; sotsiaalse inseneeria roll riigi julgeolekus. |

---

### B. DDoS (Teenusetõkestamise) Juhtumid 🚫

Need juhtumid demonstreerivad käideldavuse (availability) mõju ärilisele tegevusele ja infrastruktuurile.

| Nr. | Ettevõte/Organisatsioon | Aasta | Ründe Detail | Uurimisfookus |
| :---: | :--- | :--- | :--- | :--- |
| **4** | **GitHub (Memcached rünne)** | 2018 | GitHub'i tabas ajaloo suurim teadaolev DDoS rünnak (1.35 Tbps), mis kasutas uut ja võimsat **Memcached teenuse võimendust**. | Uued võimendustehnikad; teenusepakkujate haavatavused (Memcached oli avalikult ligipääsetav). |
| **5** | **Amazon Web Services (AWS Shield)** | 2020 | AWS teatas, et leevendas 2.3 Tbps tippkiirusega rünnakut. Rünne oli suunatud AWS'i kliendi vastu. | **Mahurünnete** kasvutrend; kuidas **pilveteenused** (cloud services) leevendavad DDoS-i. |
| **6** | **Eesti küberrünnakud** | 2007 | Rida ulatuslikke DDoS ründeid Eesti avaliku sektori, meedia ja pankade vastu, mis panid proovile riigi küberkaitse. | Ründed **kriitilise infrastruktuuri** vastu; riiklik küberkaitse ja vastupidavus. |

---

### C. Lunavara (Ransomware) Juhtumid 💰

Need juhtumid rõhutavad topelt-väljapressimist ja kriitilise infrastruktuuri riske.

| Nr. | Ettevõte/Organisatsioon | Aasta | Ründe Detail | Uurimisfookus |
| :---: | :--- | :--- | :--- | :--- |
| **7** | **Colonial Pipeline (DarkSide)** | 2021 | USA suurim kütusetorujuhtme operaator, kelle tegevus peatati DarkSide lunavara tõttu. Nad maksid lunaraha, et tagada kütuse varustus. | Kriitilise infrastruktuuri mõju; **vanade kontode** ja MFA puudumine (esmane sisenemispunkt). |
| **8** | **Kaseya VSA rünnak (REvil)** | 2021 | VSA haldustarkvara haavatavuse kaudu rünnati tuhandeid ettevõtteid üle maailma. **Tarneahela rünnaku** (Supply Chain Attack) näide. | **Tarneahela riskid**; haldustarkvara haavatavuste ärakasutamine. |
| **9** | **WannaCry (Ülemaailmne epideemia)** | 2017 | Krüpteeris sadu tuhandeid arvuteid kogu maailmas, kasutades **EternalBlue** haavatavust (Windows SMB). Mõjutas tugevalt tervishoidu (NHS UK). | Kiire levik; aegunud ja paikamata süsteemide oht. |
| **10** | **Maersk (NotPetya)** | 2017 | Petya-sarnane pahavara, mis levis globaalselt, põhjustades Maerski (maailma suurim laevandusettevõte) süsteemide täieliku seiskumise ja miljardite dollarite kahju. | Lunavara, mis oli tegelikult **hävitav pahavara (wiper)**; kiire globaalne levik ja tohutu majanduslik kahju. |

---

Valige rühmaga üks nendest juhtumitest ja asuge seda juhendi (Intsidendi uurimisrühm) alusel analüüsima! 🔎
