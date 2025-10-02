# Küberturvalisuse ohud ja haavatavused: Teooria ja Praktika (8 tundi)

---

## 1. Auditoorne Töö: Põhimõisted ja Ohutüübid (2 tundi)

### 1.1. Turvariski kontseptsioon: Oht, Haavatavus, Risk

Küberturvalisuse alusmõistete eristamine on kriitilise tähtsusega, et osata riske õigesti hinnata ja juhtida.

| Mõiste | Definitsioon | Eesmärk Küberturvalisuses |
| :--- | :--- | :--- |
| **Oht (*Threat*)** | Potentsiaalne tegur või sündmus, mis võib süsteemi või vara kahjustada. **Oht on väljaspool süsteemi.** | Rünnete ennustamine, ründajate tüüpide (nt sisetöötaja, riiklik rühmitus) tundmine. |
| **Haavatavus (*Vulnerability*)** | Süsteemi, tarkvara, protsessi või disaini **nõrkus**, mida oht saab ära kasutada. **Haavatavus on süsteemis sees.** | Nõrkuste kaardistamine ja eemaldamine (paikamine, konfiguratsiooni muutmine). |
| **Risk (*Risk*)** | Ohu realiseerumise tõenäosus korrutatud selle **mõjuga** organisatsioonile (finants-, maine- või õiguslik kahju). | **Riski juhtimine:** Riski vähendamine, elimineerimine, aktsepteerimine või ülekandmine (kindlustuse kaudu). |

**Valem:** $$Risk = T\tilde{o}en\tilde{a}osus (P) \times M\tilde{o}ju (I)$$

#### Elulised Näited: Oht, Haavatavus ja Risk

1.  **Juhtum: Äpardunud uuendus**
    * **Haavatavus:** Organisatsiooni sisevõrgus on seadistatud serverid, millel on vaikimisi sisselülitatud kaugjuurdepääsu port (nt RDP), ilma igasuguse piiranguta.
    * **Oht:** Skripti käivitav ründaja otsib **interneti pealt** lahtisi RDP-porte.
    * **Risk:** Nõrka parooli kasutades saab ründaja serverisse sisse, paigaldab lunavara ja rikub andmete **käideldavuse** (CIA-kolmiku A).

2.  **Juhtum: Vananenud tarkvara**
    * **Haavatavus:** Organisatsioon kasutab vana veebiserveri tarkvara (nt Apache), millel on teadaolevaid turvaauke. Paikade haldus (*patch management*) puudub.
    * **Oht:** Riiklik ründegrupp kasutab avalikult kättesaadavat **eksploiti** (*exploit*) teadaoleva haavatavuse ärakasutamiseks.
    * **Risk:** Ründaja saab serveris administraatori õigused ja varastab (rikub **konfidentsiaalsust**) 100 000 kliendi isikuandmed.

---

### 1.2. Levinud Ohutüübid

#### A. Õelvara (*Malware*): Eriti Lunavara

**Seletus:** *Malicious Software* on loodud süsteemi kahjustama, andmeid varastama või selle käitumist moonutama. **Lunavara (*Ransomware*)** on kõige levinum ja mõjukam *malware* tüüp, mis krüpteerib ohvri failid ja nõuab nende dekrüpteerimise eest lunaraha.

| Eluline Juhtum | Mõju ja Allikas | Ründaja Tehnika |
| :--- | :--- | :--- |
| **Colonial Pipeline, 2021** | Halvas USA suurima kütusetorustiku operaatori IT-süsteemid. Ettevõte maksis **$4.4 miljonit** BTC-s. Rünne näitas, kuidas *ransomware* ohustab **kriitilist taristut**. | Ründajad (DarkSide) kasutasid **kompromiteeritud VPN-i parooli** (*Initial Access*), et võrku pääseda ja seejärel levitada lunavara. *(Allikas: CISA, FBI avaldused)* |
| **Meditsiiniasutuste rünnak, 2023** | Saksa tervishoiusüsteemi rünnak, kus lunavara krüpteeris sadade patsientide andmed, sundides haiglaid kasutama paberdokumente ja tühistama protseduure. | Lunavara sisenes **phishing'u** teel. Andmete varastamisele järgnes krüpteerimine (nn *double extortion*). *(Allikas: Saksa meedia, turvafirmade raportid)* |

#### B. Andmepüük (*Phishing*)

**Seletus:** Sotsiaalinseneri tehnika, kus ründaja maskeerib end **usaldusväärseks allikaks** (nt pank, IT-osakond, tegevjuht), et ohver vabatahtlikult **avaldaks tundlikku infot** (parool, krediitkaardiandmed) või käivitaks pahavara.

| Eluline Juhtum | Mõju ja Allikas | Ründaja Tehnika |
| :--- | :--- | :--- |
| **Twitter/X, 2020** | Ründajad kasutasid sotsiaalset inseneeriat, et pääseda ligi Twitteri sisemistele tööriistadele, millega nad said kontrolli all 130 kõrgetasemelise konto (sh Elon Musk, Bill Gates) üle ja postitasid **Bitcoini petuskeemi**. | Ründajad kasutasid telefoni teel sotsiaalset inseneeriat, et **veenda töötajat andma ligipääs** sisesüsteemidele. *(Allikas: USA Justiitsministeerium, Twitteri avaldused)* |
| **Business Email Compromise (BEC)** | **FACC AG, 2016**. Lennunduskomponentide tootja kaotas **€50 miljonit** pärast seda, kui ründajad esitasid end **tegevjuhina** ja andsid raamatupidamisosakonnale korralduse teha kiire konfidentsiaalne ülekanne. | Ründaja kasutas **CEO-pettust** (*whaling*), luues täiusliku võltsitud e-kirja ja kasutades tehingu kiireloomulisust (*urgency*) inimfaktori ärakasutamiseks. *(Allikas: Ettevõtte pressiteated)* |

#### C. Hajutatud Teenusetõkestuse Rünnakud (*DDoS*)

**Seletus:** Rünnak, mille eesmärk on rikkuda sihtmärgi (veebisait, teenus) **käideldavust** (*Availability*) saates üle võrgu **liigselt päringuid** kompromiteeritud seadmete võrgustikust (*Botnet*).

| Eluline Juhtum | Mõju ja Allikas | Ründaja Tehnika |
| :--- | :--- | :--- |
| **Google, 2023** | **Rekordiline HTTP/2 Rapid Reset rünnak**, kus ründajad suutsid luua erakordselt suure hulga päringuid, pannes proovile isegi Google'i ulatusliku kaitsetaristu. | Kasutati uut haavatavust **HTTP/2 protokolli** rakendamisel, mis võimaldas ründajal tühistada tuhandeid vooge kiiresti, luues samal ajal uusi – see koormas servereid äärmiselt ebaefektiivsel viisil. *(Allikas: Google Cloud Security Blog, 2023)* |
| **Eesti pankade rünnak, 2022** | Pärast geopoliitilisi sündmusi rünnati Eesti **pangandus- ja valitsusasutusi**, muutes veebipanganduse ja e-teenused lühiajaliselt kättesaamatuks. | Ründaja (Killnet) kasutas **maht-põhist rünnakut** (koormates ribalaiust) ja rakenduskihi rünnakuid, mis sihtisid pangalingi teenuseid. *(Allikas: Riigi Infosüsteemi Amet (RIA) raportid)* |

#### D. *Zero-day* Haavatavused

**Seletus:** Turvaauk tarkvaras, mille jaoks ei ole olemas avalikku **turvapaika (*patch*)**. Ründajad (tavaliselt riiklikud või kõrgelt organiseeritud) avastavad ja kasutavad neid auke **enne** tarkvara arendajat.

| Eluline Juhtum | Mõju ja Allikas | Ründaja Tehnika |
| :--- | :--- | :--- |
| **Microsoft Exchange Server, 2021** | Tundmatud ründajad kasutasid nelja nullpäeva haavatavust Exchange serverites (nt **ProxyLogon**), et saada ligipääs e-posti kontodele ja paigaldada *web shell'e* (püsivus). Mõjutas kümneid tuhandeid organisatsioone. | **Kriitilise serveri** haavatavuse ärakasutamine võimaldas **autentimata** ründajal saata serverile kaugkäske (*Remote Code Execution*). *(Allikas: Microsoft, CISA)* |
| **Apple iOS (Pegasus), 2021** | Nuhkvara Pegasus kasutas nullpäeva haavatavust, et paigalduda **kasutaja teadmata** (*zero-click*) ajakirjanike, poliitikute ja aktivistide telefonidesse, varastades sõnumeid, pilte ja aktiveerides mikrofone. | Haavatavus (*ForcedEntry*) asus Apple'i iMessage'i süsteemis, võimaldades ründajal saata pahatahtliku pildi, mis käivitas tarkvara ilma kasutajapoolse tegevuseta. *(Allikas: Citizen Lab, Apple'i turvateated)* |

---

## 2. Iseseisev Töö: Rünnete Süvendatud Analüüs (6 tundi)

Iseseisva töö eesmärk on arendada oskust mõista, kuidas rünnakud toimuvad ja kuidas neid **professionaalselt** kaardistada.

### A. Detailne uurimine: Phishing, DDoS, Lunavara (3 tundi)

Valige kolm ülaltoodud juhtumit (üks igast kategooriast) ja koostage lühike ülevaade järgmiste punktide kohta:

1.  **Rünnaku Mehhanism:** Kirjeldage samm-sammult, **kuidas tehniliselt** rünnak õnnestus (nt lunavara puhul: Phishing e-kiri -> manuse käivitamine -> side *Command & Control* serveriga -> krüpteerimine).
2.  **Ründaja Motivaator:** Mis oli ründaja peamine eesmärk (nt rahaline kasu, poliitiline protest, spionaaž)?
3.  **Leevendusmeetmed:** Tuvastage vähemalt 3 konkreetset tehnilist ja 1 protsessilist meedet, mis oleks saanud rünnaku **ära hoida** või selle **mõju vähendada** (nt MFA, varundus, võrgu segmenteerimine).

### B. MITRE ATT&CK Raamistiku Tutvustus (3 tundi)

**Seletus:** **MITRE ATT&CK** on küberrünnete teadmiste baas, mis kaardistab ja kirjeldab ründajate **taktikaid** (miks nad midagi teevad) ja **tehnikaid** (kuidas nad seda teevad). See on hädavajalik turvaintsidentide analüüsimisel ja kaitsestrateegiate loomisel.

**Ülesanne:**

1.  **Tutvumine Maatriksiga:** Minge MITRE ATT&CK veebisaidile ja tutvuge **Enterprise** (ettevõtluse) maatriksiga.
2.  **Valitud Rünnaku Kaardistamine:** Valige oma uuritud lunavara juhtum (nt Colonial Pipeline) ja proovige kaardistada rünnaku käik 4-5 **taktikaga** (veeruga).
    * *Näide:*
        * **Taktika:** *Initial Access* (Esmane ligipääs)
        * **Tehnika:** **T1078.001** (*Valid Accounts: Default Accounts or Shared Accounts*) – Kasutati lekkivat VPN-i parooli.
        * **Taktika:** *Execution* (Täitmine)
        * **Tehnika:** **T1059** (*Command and Scripting Interpreter*) – Lunavara käivitamiseks kasutati PowerShell'i.
3.  **Aruandlus:** Dokumenteerige kaardistus ja selgitage, kuidas MITRE ATT&CK aitab **kaitsemeeskondadel** (Blue Team) paremini kaitset planeerida.

**Tulemus:** Pärast seda moodulit peaksid õpilased suutma veenvalt selgitada, mis eristab ohtu haavatavusest, ja oskama kaardistada reaalse rünnaku samme, kasutades tööstusstandardit.
