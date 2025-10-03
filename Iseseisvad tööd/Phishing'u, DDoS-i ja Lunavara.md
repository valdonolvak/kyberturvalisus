 **Phishing'u, DDoS-i ja Lunavara** ründeliikidei toimemehhanismid koos selgitavate näidetega.

---

## Loengumaterjal: Küberturvalisuse Kolm Suurt Ohtu

### Sissejuhatus: Miks just need kolm?

**Phishing, DDoS** ja **Lunavara** esindavad kaasaegse küberkuritegevuse kolme põhisuunda: **Sotsiaalne Inseneeria**, **Teenuse Kättesaadavuse Tõkestamine** ja **Raha Väljapressimine**. Need ründed mõjutavad otse **CIA kolmnurga** (Konfidentsiaalsus, Terviklikkus, Käideldavus) põhimõtteid ja põhjustavad suurimat majanduslikku kahju.

---

## 1. Phishing ehk Õngitsemine: Sotsiaalne Inseneeria 🎣

Phishing on rünne, mis kasutab ära **inimlikku usaldust** ja **emotsioone**, mitte ainult tehnilisi nõrkusi.

### Toimemehhanism: Sotsiaalne Uuring (Reconnaissance) ja Petmine

Phishing-rünnak on tihti mitmeastmeline protsess:

1.  **Sotsiaalne Uuring (Reconnaissance):** Enne rünnakut viib ründaja läbi põhjaliku uuringu sihtmärgi kohta.
    * **Andmete Kogumine:** Kasutatakse avalikke allikaid (**OSINT – Open Source Intelligence**): LinkedIn, Facebook, Instagram, ettevõtte veebileht, pressiteated.
    * **Eesmärk:** Leida töötajate nimed, ametikohad, e-posti aadressid, ettevõtte sisekultuuri eripärad (nt. kas kasutatakse Google'i või Microsofti teenuseid, millal on puhkused jne). See teeb petuskeemi uskumatult isikupäraseks (**Spear Phishing**).

2.  **Petukirja Koostamine:** Ründaja koostab e-kirja, mis on **emotsionaalselt laetud** (kiireloomulisus, hirm, autoriteedi austamine).
    * **Tehniline Maskeering:** Võltsitakse saatja aadress (spoofing), luuakse ametlikke veebilehti matkivaid petulehti (lookalike domains).
    * **Prioriteet:** Kasutatakse autoriteeti (nt. IT-osakonna juht, CEO) või äkilist kriisi (nt. pangakonto blokeerimine, parooli aegumine).

3.  **Ohvri Käitumine ja Saagi Koristamine:**
    * Ohver klõpsab lingil või avab manuse.
    * Link viib **võltsitud sisselogimislehele**, mis korjab sisse sisestatud kasutajanime ja parooli.
    * Või laeb manuse avamine alla **pahavara** (nt. lunavara, *keylogger*).

#### Selgitav Näide: Phishing Juhtivtöötaja vastu

**Etapp 1: Sotsiaalne Uuring (Sihtmärk: Ettevõtte raamatupidaja, Tiina)**

* Ründaja otsib LinkedInist Tiina ametikohta, näeb, et ta suhtleb sageli **CEO Jaaguga**.
* Ründaja leiab ettevõtte veebilehelt, et nad kasutavad **Microsoft 365** (Office'i keskkond).
* Ründaja teab, et Tiina kasutab e-posti aadressi *tiina.mets@ettevote.ee*.

**Etapp 2: Petukirja saatmine (CEO Jaagu nimel)**

* Ründaja saadab Tiinale e-kirja aadressilt *jaak.karu@ettevot*.net (väike trükiviga domeeninimes, mida Tiina kiiruga ei märka).
* **Pealkiri:** "KIIRMUST: Uus kvartaliaruande kood ja Office 365 paroolimuutus!"
* **Sisu:** "Tiina, mul on vaja see kvartaliaruanne KOHE üle vaadata. Pidin just uue turbeprotokolli tõttu parooli muutma. Palun sisesta uuesti oma andmed siin, et tagada ligipääs uuele arveldusfailile, mis asub **SharePointis** (link **võltsitud Microsofti** sisselogimislehele). Kiirusta, ma ootan."

**Etapp 3: Rünnaku Õnnestumine**

* Tiina näeb Jaagu nime, kiirustab ega kontrolli linki.
* Sisestab oma Microsoft 365 parooli võltslehele.
* **Tulemus:** Ründaja saab Tiina parooli ja pääseb ligi ettevõtte tundlikule finantsinfole.

---

## 2. DDoS (Distributed Denial of Service) 🚫

DDoS-rünnaku eesmärk on tarbida sihtmärgi ressursid (võrguriba laius, protsessori võimsus, mälu) nii ära, et **päriskasutajad** ei pääse teenusele ligi.

### Toimemehhanism: Botnet'i Võimsuse Rakendamine

DDoS erineb tavalisest DoS-rünnakust selle poolest, et liiklus pärineb **paljudest allikatest korraga** (Distributed).

1.  **Botnet'i loomine:** Ründaja nakatab pahavaraga (nt. Mirai) tuhandeid või miljoneid seadmeid (arvutid, ruuterid, nutikaamerad) ja loob neist **Botnet'i** – kontrollitud zombiseadmete võrgustiku.
2.  **Rünnaku Käivitamine:** Ründaja saadab Botnet'ile käskluse suunata kogu oma liiklus kindla sihtmärgi IP-aadressile.
3.  **Liikluse Tüüp (Näide: SYN Flood):** Enamasti on tegemist **SYN Flood** protokollirünnakuga.
    * Tavaline ühendus (TCP Handshake) nõuab 3 sammu: (1) Klient saadab **SYN** (Soovin ühenduda) → (2) Server saadab **SYN-ACK** (Sain teate ja nõustun) → (3) Klient saadab **ACK** (Okei, alustan andmete saatmist).
    * **SYN Flood:** Botnet saadab serverile massiliselt (1) **SYN** pakette, kuid **ei vasta kunagi** sammuga (3) **ACK**.
    * **Tulemus:** Serveri mälus pühitakse ootele tuhandeid poolikuid ühendusi, kulutades ressursse ja takistades seeläbi uute (päris) ühenduste loomist.
4.  **Sihtmärgi Ülekoormamine:** Server ei suuda enam töödelda seaduslikku liiklust, veebileht hangub ja on **kättesaamatu (Denial of Service)**.

#### Selgitav Näide: DDoS Rünnak E-poe vastu

* **Olukord:** Suur e-pood on just alanud müügikampaania ("Kõik -50%").
* **Rünne:** Konkureeriv rühm või väljapressija käivitab 100 000 nakatunud seadmest koosneva Botnet'i, mis saadab e-poe serverile 10 gigabitti rämpsliiklust sekundis (10 Gbps).
* **Tagajärg:** Kuigi e-pood suudaks normaalselt töödelda 1 Gbps liiklust, on sissetulev maht 10 korda suurem. Serveri võrguühendus on üle koormatud, veebileht "vajub kokku" ja pärisostjad ei saa tellimusi esitada.
* **Mõju:** Tunni aja jooksul kaotab ettevõte sadu tuhandeid eurosid müügitulu ja mainekahju.

---

## 3. Lunavara (Ransomware): Andmete Väljapressimine 💰

Lunavara on pahavara, mis lukustab süsteemi või krüpteerib andmed, nõudes nende taastamise eest raha.

### Toimemehhanism: Sissetung, Krüpteerimine ja Väljapressimine

Lunavara on arenenud lihtsast failikrüpteerijast keerukaks ärimudeliks.

1.  **Esmane Sissetung:** Kõige levinum sisenemispunkt on **Phishing** või **paikamata haavatavused** (nt. vananenud VPN serverid, millel puudub paik (patch)).
2.  **Eeluurimine ja Liikumine:** Pärast sissetungi ei krüpteerita kohe. Ründajad liiguvad võrgus horisontaalselt (Lateral Movement), otsides **kõige väärtuslikumaid andmeid** ja **varukoopiaid**.
    * **Eesmärk:** Leida domeenikontrollerid ja hävitada võrguga ühendatud varukoopiad.
3.  **Topelt-Väljapressimine (Double Extortion):** Ründajad teevad krüpteerimisprotsessi käigus tundlikest andmetest **koopia**.
    * **1. väljapressimine:** Nõutakse lunaraha krüpteeringu eemaldamise eest.
    * **2. väljapressimine:** Kui lunaraha ei maksta, ähvardatakse varastatud andmed avalikustada (müüa mustal turul), mis on tugev **konfidentsiaalsuse** riive.
4.  **Krüpteerimine ja Nõue:** Ründaja käivitab lunavara, mis krüpteerib failid tugeva algoritmi (nt. AES-256) abil. Ekraanile kuvatakse **lunarahanõude teade**, mis sisaldab juhiseid makse tegemiseks krüptovaluutas.

#### Selgitav Näide: Lunavara Rünnak Tervishoiuettevõtte vastu

* **Sissetung:** Tervishoiukliiniku töötaja avab e-kirja, mis sisaldab CV-d, kuid tegelikult on see **lokkimishaagis (dropper)**, mis laseb süsteemi sisse *Conti* lunavara.
* **Eeluurimine:** Ründaja tegutseb võrgus vaikselt nädalaid, saades ligipääsu patsiendikaartide serveritele ja raamatupidamise andmebaasile.
* **Krüpteerimine:** Süsteemid krüpteeritakse pühapäeva õhtul, et tagada maksimaalne mõju esmaspäevaks.
* **Tagajärg:** Kliinik ei saa ligi patsiendikaartidele (Terviklikkus ja Käideldavus rikutud). Ründaja teatab, et on varastanud 50 000 patsiendi andmed.
* **Valik:** Kliinik on sunnitud valima: kas maksta lunaraha ja loota dekrüpteerijale (ning et andmeid ei avalikustata) või riskida täieliku andmekao ja ulatusliku **GDPR trahviga** (Konfidentsiaalsuse rikkumine).

---
