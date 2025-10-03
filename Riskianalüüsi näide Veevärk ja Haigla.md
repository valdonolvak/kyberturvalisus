Suurepärane! Täiendan riskianalüüse, lisades mõlemasse kuus (6) spetsiifilist riski, poliitika soovitust ja kaitsemeedet. Need näited on koostatud arvestades tänapäevaseid küberohte.

---

## Näidis 1: Riskianalüüs – Kohalik Vee- ja Kanalisatsioonivarustaja (ETO/OT)

### Taust

* **Organisatsioon:** Kriitilise infrastruktuuriga seotud vee- ja kanalisatsioonivarustaja.
* **Põhieesmärk:** Tagada vee puhastusprotsesside Terviklikkus ja süsteemide Käideldavus.
* **Spetsiifiline fookus:** **IT/OT Konvergents** (IT-võrgu ja operatiivtehnoloogia kokkusulamine) ja vananenud seadmed.

| Nr. | Risk | Tõenäosus | Mõju (CIA) | Ründe Stsenaarium | Konkreetsed Poliitikad ja Kaitsemeetmed |
| :---: | :---: | :---: | :---: | :--- | :--- |
| **1** | **SCADA kompromiteerimine** | Kõrge | **Käideldavus (Kriitiline)**, Terviklikkus | Ründaja saab ligipääsu SCADA serverile IT-võrgu kaudu ja muudab puhastusprotsesside parameetreid. | **Võrgu Segmentimise Poliitika:** Range eraldamine **OT-võrgu** ja **IT-võrgu** vahel **andmete dioodi** (Data Diode) abil. OT-võrku pääseb ainult sisevõrgust füüsiliselt. |
| **2** | **Vananenud süsteemid ja tehniline võlg** | Väga Kõrge | Terviklikkus, Käideldavus | PLC-d ja kontrollerid kasutavad tarkvara, millel pole paikasid ja mis sisaldab avalikult teadaolevaid haavatavusi. | **Elutsükli haldamise poliitika:** Iga operatiivtehnoloogia (OT) seadmele peab olema määratud kindel **paigaldamise ja väljavahetamise** aeg. **Virtuaalpaikamine** (Virtual Patching) vananenud süsteemidele. |
| **3** | **Ebaselge võrgu inventuur** | Keskmine | Konfidentsiaalsus, Terviklikkus | Täpset inventuuri pole (IP-aadressid, teenused, pordid) ning võrgus on tundmatud seadmed (nt. vana WiFi ruuter). | **Vara Haldamise Poliitika:** Kohustuslik reaalajas seadmete **automaatne avastamine ja inventuur** (CMDB) nii IT- kui OT-võrgus. Iga uue seadme puhul range autoriseerimine. |
| **4** | **Lunavara levik IT-st OT-sse** | Kõrge | Käideldavus (Kriitiline) | Administraator nakatab oma sülearvuti IT-võrgus ja see levib (nt. ühise jagatud draivi kaudu) OT-võrku. | **Eemaldatava Meedia Poliitika:** USB-mälupulkade, CD-de ja teiste eemaldatavate seadmete kasutamine OT-võrgus on **rangelt keelatud** või lubatud ainult spetsiaalses, skaneerivas kandejaamas (kiosk). |
| **5** | **Kolmandate osapoolte kaugjuurdepääs** | Keskmine | Konfidentsiaalsus, Terviklikkus | Seadmete hooldust pakkuvad välised müüjad kasutavad seadmete hooldamiseks **mitmekordseid VPN-e** ilma MFAta. | **Kaugjuurdepääsu Poliitika:** Lubada kaugjuurdepääs ainult **spetsiaalse turvatud hüppepunkti** (Jump Server) kaudu, mis nõuab alati **MFA-d** ja **seansi logimist**. |
| **6** | **Töötaja teadlikkuse puudumine** | Kõrge | Konfidentsiaalsus | Töötajad on teadmatud sotsiaalse inseneeria meetoditest ja avavad e-kirju, mis annavad ründajale algse sissetungi. | **Koolituspoliitika:** Regulaarsed (vähemalt kvartali) **phishing-simulatsioonid** ja spetsiifiline **OT turvalisuse** ja ohutusprotokollide koolitus. |

---

## Näidis 2: Riskianalüüs – Haigla (Tervishoid)

### Taust

* **Organisatsioon:** Haigla, kus patsiendiandmete konfidentsiaalsus ja meditsiiniseadmete käideldavus on elutähtsad.
* **Põhieesmärk:** Hoida patsientide andmed konfidentsiaalsed (PHI) ja tagada elutähtsate teenuste katkestamatu töö.
* **Spetsiifiline fookus:** **IoMT (Internet of Medical Things)** seadmed ja kõrge konfidentsiaalsuse nõue.

| Nr. | Risk | Tõenäosus | Mõju (CIA) | Ründe Stsenaarium | Konkreetsed Poliitikad ja Kaitsemeetmed |
| :---: | :---: | :---: | :---: | :--- | :--- |
| **1** | **Patsiendiandmete (EHR) vargus** | Väga Kõrge | **Konfidentsiaalsus (Kriitiline)**, Mõju mainele | Ründaja saab ligipääsu EHR andmebaasile ja varastab tundlikke isikuandmeid müügiks Pimedal Veebil. | **Juurdepääsu poliitika:** **Vähima privileegi põhimõte** (Least Privilege) ja **MFA** kohustus **kõigile** süsteemidele, mis sisaldavad patsientide andmeid. |
| **2** | **Unsecured IoMT seadmed** | Kõrge | Terviklikkus, Käideldavus | Vana infusioonipump või diagnostikaseade (nt. MRI) on võrku ühendatud vaikeparoolidega ja paikamata tarkvaraga. | **Meditsiiniseadmete poliitika:** Seadmete **täpne segmentimine** võrgus. Meditsiiniseadmete võrk on **eraldi tulemüüri** taga ja keelatakse igasugune sissetulev ühendus väljastpoolt. |
| **3** | **Lunavara katastroof** | Väga Kõrge | **Käideldavus (Kriitiline)**, Terviklikkus | Kõik IT- ja administraatori süsteemid on krüpteeritud, mis sunnib arste kasutama paberkandjat, viivitades kriitilisi diagnoose. | **Katastroofi Taaste Poliitika:** Kohustuslikud **lunavara taasteharjutused** igas kvartalis. Kriitiliste süsteemide (nt. laboriandmete) taastamine peab olema võimalik **vähem kui 4 tunni** jooksul. |
| **4** | **Sisemine oht (Insider Threat)** | Keskmine | Konfidentsiaalsus, Terviklikkus | Rahulolematu töötaja laeb suure hulga patsientide andmeid USB-mälupulgale või lahkumise eel edastab neid. | **Kasutajate Monitoorimise Poliitika:** Ebahariliku andmeliikluse (nt. 500 MB tundliku info allalaadimine) automaatne seire ja logimine. Töötaja lahkumisel **kohene ligipääsu keelamine**. |
| **5** | **Andmete jagamine ja Pilve riskid** | Keskmine | Konfidentsiaalsus, Terviklikkus | Terviseandmete (PHI) jagamine välispartnerite (laborid, eriarstid) või pilveteenustega (nt. meditsiini pildindus) ilma korraliku krüpteerimiseta. | **Andmete Krüpteerimise Poliitika:** Kõik tundlikud andmed peavad olema **krüpteeritud puhkeolekus** (serveris) ja **edastamise ajal** (TLS/VPN). Audit pilveteenuse pakkujate lepingutele (BAA). |
| **6** | **Rünne elutähtsate kommunikatsioonide vastu** | Madal | Käideldavus | DDoS rünnak või pahavara blokeerib haigla kõnekeskuse või kiirabi kommunikatsioonisüsteemi. | **Käideldavuse Poliitika:** Sidekanali (nt. VoIP süsteem) dubleerimine. **DDoS kaitse teenuse** kasutamine (nt. pilvepõhine CDN) ning **füüsilise (analooog) varuplaani** olemasolu erakorraliseks sides. |
