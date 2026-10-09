# Ründetehnikad ja -mudelid

*Õppematerjal IT-õppijatele · eeldab teadmisi krüptograafiast, haavatavustest, pahavarast ja E-ITS-ist*

## 1. Õpiväljundid

Pärast materjali läbitöötamist õppija:

- selgitab, miks ründemudeleid kasutatakse ning mille poolest need aitavad kaitsjat;
- kirjeldab Cyber Kill Chain’i, MITRE ATT&CK-i ja Diamond Model’i põhimõtteid ning oskab neid võrrelda;
- tunneb ära levinumad ründetehnikad (õngitsus, volituste ründed, külgsuunaline liikumine, andmete väljaviimine, veebiründed jt) ning seostab need kaitsemeetmetega;
- analüüsib rünnet kasutades ühiseid mudeleid;
- koostab tuvastusloogika ja kaitsemeetmete kaardi (*detection and mitigation mapping*) ühe ründescenaariumi jaoks.

## 2. Miks ründemudeleid vaja on?

Kaitsja ei suuda tegeleda „üksikute rünnetega”, sest neid on liiga palju ja need muutuvad pidevalt. Mudelid annavad:

- **ühise keele** – turvameeskond, juhtkond ja audiitor mõistavad rünnet samamoodi;
- **struktuuri** – aitab näha, milliseid ründe etappe mingi meede katab ja milliseid mitte;
- **prioriseerimise** – aitab otsustada, kuhu piiratud ressursse suunata;
- **tuvastuse planeerimise** – iga ründetehnika juures saab küsida: „Mida me näeksime logides?”
- **ründesimulatsiooni ja testimise aluse** – punane meeskond (*red team*) ning õpetaja saavad üles ehitada realistlikke harjutusi.

**Kaitsja mõttemuster:** ründaja peab ületama *kõik* etapid edukalt; kaitsja peab peatama *ühe*. Seetõttu on kihiline kaitse nii tähtis.

## 3. Ründaja eesmärgid ja tüübid

Enne tehnikaid tasub mõista, **kes ja miks** ründab. See mõjutab, milliseid tehnikaid oodata:

| Ründaja tüüp | Peamine motiiv | Tüüpiline sihtmärk | Oskused ja ressursid |
| --- | --- | --- | --- |
| Küberkurjategija | Raha | Kogu ärimaailm | Varieeruv; sageli organiseeritud |
| Riiklik ründaja (APT) | Spionaaž, mjõutus, sabotaaž | Valitsus, kriitiline taristu, kaitsetehnoloogia | Kõrge, pikaajaline, hea rahastus |
| Aktivist (*hacktivist*) | Ideoloogia | Poliitilised või ärilised sihtmärgid | Madal kuni keskmine; sageli DDoS ja avalikustamine |
| Siseringi ründaja | Raha, kuri tahe, kaebus | Oma organisatsioon | Ligipääs ja teadmised on juba olemas |
| Pealiskaudne ründaja (*script kiddie*) | Huvi, maine | Juhuslikud sihtmärgid | Madal; kasutab valmis tööriistu |

**APT** (*Advanced Persistent Threat*) tegutseb sihipäraselt ja pikalt: ta ei kiirusta, vaid peidab end ning laieneb aeglaselt. Küberkurjategija on tihti oportunistlik: tabab neid, kelle kaitse on kergesti läbiv.

## 4. Cyber Kill Chain

**Cyber Kill Chain** on Lockheed Martini töötatud mudel, mis kirjeldab rünnet seitsme järjestikuse etapina. Idee: kui katkestada üks lüli, siis ei õnnestu kogu rünne.

| # | Etapp | Mida ründaja teeb | Näide | Kaitse |
| --- | --- | --- | --- | --- |
| 1 | Luure (*Reconnaissance*) | Kogub infot sihtmärgi kohta | Töötajate nimed LinkedInist, avalikud IP-aadressid, DNS-kirjed | Avaliku info piiramine, seire |
| 2 | Relvastamine (*Weaponization*) | Valmistab ette ründevahendi | Pahatahtliku manusega dokumendi koostamine | Ohuluure |
| 3 | Kohaletoimetamine (*Delivery*) | Saadab ründevahendi sihtmärgile | Õngitsuskiri, pahatahtlik veebileht, USB | E-posti filtreerimine, veebifiltrid, teadlikkus |
| 4 | Ekspluateerimine (*Exploitation*) | Kasutab haavatavust või kasutaja tegevust | Kasutaja avab manuse, käivitub kood | Paigahaldus, makrode piiramine, EDR |
| 5 | Paigaldamine (*Installation*) | Kinnistub süsteemis | Ajastatud ülesanne, teenus, registri autostart | Rakenduste lubamisnimekirjad, EDR |
| 6 | Juhtimine (*Command & Control*) | Loob sidekanali ründajaga | HTTPS-side juhtserveriga | Võrguseire, DNS-filtreerimine, väljuva liikluse piiramine |
| 7 | Tegevus eesmärgi nimel (*Actions on Objectives*) | Saavutab eesmärgi | Andmete varastamine, krüpteerimine, sabotaaž | Segmenteerimine, DLP, varundus |

### 4.1 Tugevused ja piirangud

**Tugevused:** lihtne, intuitiivne, hea juhtkonnale selgitamiseks; rõhutab, et rünnet saab katkestada igas etapis.

**Piirangud:**

- eeldab lineaarset rünnet; päris ründed on sageli iteratiivsed ja eri etapid kordavad end;
- keskendub **välistele perimeetrile**, seega ei kata hästi rünnet pärast sissepääsu (külgsuunaline liikumine, õiguste tõstmine);
- ei sobi hästi siseringi rünnete ega pilvekeskkonna rünnete kirjeldamiseks;
- liiga üldine täpseks tuvastusplaneerimiseks.

Seetõttu täiendatakse seda tihti **MITRE ATT&CK-iga**, mis on palju detailsem.

## 5. MITRE ATT&CK

**MITRE ATT&CK** (*Adversarial Tactics, Techniques, and Common Knowledge*) on avalik teadmusbaas ründaja **käitumisest**, mis põhineb tegelikel vaatlustel. See on kõige laialdasemalt kasutatav raamistik tänapäevases küberkaitses.

### 5.1 Struktuur

ATT&CK koosneb kolmest tasemest:

- **Taktikad** – *miks* ründaja midagi teeb (ründaja lühiajaline eesmärk). Näiteks „Õiguste eskaleerimine”.
- **Tehnikad** – *kuidas* ründaja eesmärgi saavutab. Näiteks „Valid Accounts” (kehtivate kontode kasutamine).
- **Alamtehnikad** – tehnika täpsem variant. Näiteks „Valid Accounts: Domain Accounts”.

Iga tehnika juurde kuuluvad:

- kirjeldus ja kasutusnäited tegelikest juhtumitest;
- **ründegrupid ja pahavara**, mis seda kasutavad;
- **tuvastussoovitused** (andmeallikad, jälgitavad sündmused);
- **leevendusmeetmed** (*mitigations*).

ATT&CK-il on mitu maatriksit: **Enterprise** (Windows, Linux, macOS, pilv, võrk), **Mobile** ja **ICS** (tööstuskontrollsüsteemid).

### 5.2 Enterprise-maatriksi taktikad

Taktikad järjestatud ligikaudu ründe loogilises käigus:

1. **Reconnaissance** – luure
2. **Resource Development** – vahendite ja taristu ettevalmistamine
3. **Initial Access** – esmane ligipääs
4. **Execution** – koodi käivitamine
5. **Persistence** – kinnistumine
6. **Privilege Escalation** – õiguste tõstmine
7. **Defense Evasion** – kaitse kijaskäimine
8. **Credential Access** – volituste hankimine
9. **Discovery** – keskkonna uurimine
10. **Lateral Movement** – külgsuunaline liikumine
11. **Collection** – andmete kogumine
12. **Command and Control** – juhtimiskanal
13. **Exfiltration** – andmete väljaviimine
14. **Impact** – mõju (krüpteerimine, hävitamine, teenuse häirimine)

### 5.3 Näited tehnikatest

Siin on valik tehnikaid, mida õppijad peaksid tundma. Pane tähele nii **kuidas** need töötavad kui ka **mida kaitsja näeb**.

| Taktika | Tehnika | Lühikirjeldus | Tuvastus | Leevendus |
| --- | --- | --- | --- | --- |
| Initial Access | Phishing (T1566) | Sotsiaalselt manipuleeriv e-kiri manuse või lingiga | E-posti filtrid, kasutajate teavitused, ebatavalised protsessiahelad (Office → skript) | Filtreerimine, MFA, koolitus |
| Initial Access | Exploit Public-Facing Application (T1190) | Avaliku teenuse haavatavuse kasutamine | Veebiserveri ja WAF-i logid, ebatavalised päringud | Paigahaldus, WAF, ründepinna vähendamine |
| Initial Access / Persistence | Valid Accounts (T1078) | Varastatud või lekkinud volituste kasutamine | Ebatavalised sisselogimised (asukoht, kellaaeg, seade) | MFA, paroolipoliitika, volituste monitooring |
| Execution | Command and Scripting Interpreter (T1059) | PowerShelli, cmd, skriptide kasutamine | Skriptide logimine, kahtlased käsurea argumendid | Constrained Language Mode, lubamisnimekirjad |
| Persistence | Scheduled Task/Job (T1053) | Ajastatud ülesanne tagab pärast taaskäivitust jätkamise | Uute ülesannete loomine (Windows Event 4698) | Õiguste piiramine, auditeerimine |
| Privilege Escalation | Abuse Elevation Control Mechanism (T1548) | Õiguste tõstmine UAC või sarnaste mehhanismide kaudu | Ebatavalised protsessid kõrgendatud õigustega | Vähimate õiguste põhimõte |
| Defense Evasion | Impair Defenses (T1562) | Kaitsetarkvara väljalülitamine | Teenuse peatamise ja seadistuste muutmise sündmused | Manipuleerimiskaitse (*tamper protection*) |
| Credential Access | OS Credential Dumping (T1003) | Volituste (räsid, parooliandmed) kogumine mälust või andmebaasist | Ligipääs LSASS-i protsessile, ebatavalised tööriistad | Credential Guard, LSA-kaitse, administraatorikontode piiramine |
| Credential Access | Brute Force / Password Spraying (T1110) | Paroolide proovimine, sageli vähe katsega paljude kontode peal | Palju ebaõnnestunud sisselogimisi, kontode lukustused | MFA, lukustuspoliitika, tugevad paroolid |
| Discovery | Account / Network Discovery (T1087, T1046) | Kontode, võrkude ja teenuste uurimine | Massilised LDAP-päringud, skaneerimisliiklus | Segmenteerimine, võrguseire |
| Lateral Movement | Remote Services (T1021) | RDP, SMB, WinRM kasutamine teise seadmesse liikumiseks | Ebatavalised kaugsisselogimised (Event 4624 tüüp 3/10) | Segmenteerimine, MFA, halduse eraldamine |
| Collection / Exfiltration | Exfiltration Over Web Service (T1567) | Andmete saatmine pilveteenusesse | Suur väljuv andmemaht, ebatavalised sihtkohad | DLP, väljuva liikluse piiramine |
| Impact | Data Encrypted for Impact (T1486) | Andmete krüpteerimine (lunavara) | Massiline failimuutmine, varjukoopiate kustutamine | Varundus, segmenteerimine, EDR |

*Märkus:* tehnikate ID-d (nt T1566) on stabiilsed, kuid ATT&CK-i sisu uuendatakse regulaarselt. Täpsed andmed tasub alati kontrollida ametlikult veebilehelt (attack.mitre.org).

### 5.4 Kuidas ATT&CK-i praktikas kasutada?

- **Tuvastuskattekaart** – märgi, milliseid tehnikaid sinu SIEM/EDR tuvastab ja millistel on lünki (*detection coverage*). ATT&CK Navigator on selleks mugav tööriist.
- **Ohuluure kaardistamine** – vaata, milliseid tehnikaid kasutavad ründegrupid, kes sinu sektorit sihivad.
- **Intsidendianalüüs** – kaardista ründe sammud tehnikatele, et leida lünki ja soovitusi.
- **Punase ja sinise meeskonna harjutused** – *purple team* valib tehnika, simuleerib seda ja kontrollib, kas tuvastus töötab.
- **Riskihindamine ja aruandlus** – aitab selgitada juhtkonnale, millised ründeviisid on katmata.

## 6. Diamond Model

**Diamond Model of Intrusion Analysis** vaatleb **ühte sündmust** nelja elemendi kaudu:

- **Adversary** – ründaja (isik, grupp, motiiv);
- **Capability** – võimekus (tööriistad, pahavara, tehnikad);
- **Infrastructure** – taristu (domeenid, serverid, e-posti kontod, mida ründaja kasutab);
- **Victim** – ohver (organisatsioon, süsteem, isik, haavatavus).

Iga sündmuse puhul saab küsida: *Kes? Millega? Mille kaudu? Kelle vastu?* Mudelit kasutatakse eelkõige **ohuluure ja korrelatsiooni** jaoks: kui kaks sündmust jagavad sama taristut või võimekust, võivad need olla samast kampaaniast.

**Erinevalt Kill Chain’ist** ei kirjelda Diamond Model ründe ajalist käig, vaid sündmuse sisu. Seetõttu täiendavad mudelid teineteist: Kill Chain annab **järjestuse**, ATT&CK **tehnilise detaili**, Diamond Model **seosed ründajate ja taristu vahel**.

## 7. Mudelite võrdlus

| Omadus | Cyber Kill Chain | MITRE ATT&CK | Diamond Model |
| --- | --- | --- | --- |
| Fookus | Ründe etapid | Ründaja taktikad ja tehnikad | Sündmuse neli elementi |
| Detailsus | Madal | Kõrge | Keskmine |
| Hea | Selgitamiseks ja perimeetrikaitse planeerimiseks | Tuvastuse ja leevenduse planeerimiseks | Ohuluure ja ründekampaaniate seostamiseks |
| Nõrkus | Eeldab lineaarsust, perimeetri kesksus | Võib olla üleäratavalt detailne algajale | Eeldab rikkalikku ohuluuret |
| Kasutaja | Juhtkond, turvaarhitekt | SOC, CTI, red/blue team | Analüüsijad, CTI-meeskond |

Mõned täiendavad raamistikud, mida tasub tunda: **Unified Kill Chain** (ühendab Kill Chain’i ja ATT&CK-i, kirjeldab ka sissepääsu järgset etappi), **OWASP Top 10** (veebirakenduste riskid), **STRIDE** (ohumodelleerimise meetod arenduses), **Pyramid of Pain** (kui valus on ründajale erinevate tuvastus- ja kaitsemeetmete mojutus).

## 8. Peamised ründetehnikad lähemalt

### 8.1 Sotsiaalne manipulatsioon ja õngitsus

**Sotsiaalne manipulatsioon** (*social engineering*) ekspluateerib inimlikke tegureid: usaldust, kiirustamist, hirmu, autoriteeti ja uudishimu.

**Vormid:**

- **Phishing** – massiline pettuskiri;
- **Spear phishing** – isikupõhine, hoolikalt ette valmistatud kiri;
- **Whaling** – suunatud juhtidele;
- **Vishing** – telefonikõne kaudu;
- **Smishing** – SMS-i kaudu;
- **Pretexting** – võltsstsenaarium (nt „IT-tugi” palub parooli);
- **BEC (Business Email Compromise)** – juhi või tarnija nime all makse nõudmine;
- **Quishing** – pahatahtlik QR-kood;
- **MFA fatigue** – kasutajale saadetakse pidevalt MFA-taotlusi, kuni ta kinnitab.

**Näide (BEC):** raamatupidaja saab e-kirja, mis näib tulevat tegevjuhilt, ja palve teha kiire ülekanne uuele pangakontole. Kiri on kirjutatud kiirustavas toonis ja viitab konfidentsiaalsusele. Selline rünne ei sisalda pahavara, kuid võib tuua suure rahalise kahju.

**Kaitse:** MFA (eelistatult phishing-resistentne, nt FIDO2), SPF/DKIM/DMARC, e-posti filtreerimine, protsessid maksete kinnitamiseks teisel kanalil, õngitsussimulatsioonid, lihtne viis kahtlastest kirjadest teatamiseks.

### 8.2 Volituste ründed

Paljud ründed põhinevad volituste (paroolid, küpsised, pääsulubad) hankimisel, sest sisselogimine on vaiksem kui haavatavuse ekspluateerimine.

- **Brute force** – kõigi võimalike paroolide proovimine; aeglane ja märgatav.
- **Password spraying** – üks levinud parool proovitakse paljude kontode peal; väldib kontode lukustusi.
- **Credential stuffing** – mujalt lekkinud kasutajanime ja parooli kombinatsioonide proovimine.
- **Volituste kogumine** (*credential dumping*) – pärast süsteemi sissepääsu volituste väljavõtmine mälust või andmebaasist.
- **Pass-the-Hash / Pass-the-Ticket** – varastatud räsi või Kerberose piletiga autentimine ilma parooli teadmata.
- **Kerberoasting** – teenusekontode Kerberose piletite pealtvõtmine ja võrguväliselt parooli murdmine; ohtlik nõrkade teenusekontode paroolide korral.
- **Session hijacking / token theft** – aktiivse sessiooni või tokeni varastamine (nt infovarga abil), mis võib mitmefaktorilise autentimise kõrvale jätta.

**Kaitse (Windows/AD-keskkonnas):** tugevad ja pikad teenusekontode paroolid (või gMSA), Credential Guard, LAPS, Protected Users grupp, tiered administration, NTLM-i piiramine, MFA ja anomaalsete sisselogimiste seire.

### 8.3 Külgsuunaline liikumine ja õiguste eskaleerimine

Pärast esmast sissepääsu on ründaja tavaliselt tavakasutaja õigustega ühe seadme peal. Tema eesmärk on:

1. leida sisevõrk ja olulised süsteemid (**discovery**);
2. hankida kõrgemad õigused (**privilege escalation**);
3. liikuda teistele seadmetele (**lateral movement**), kuni jõutakse kriitiliste süsteemideni, nt domeenikontrollerini.

**Tüüpilised võimalused:** kaugteenused (RDP, SMB, WinRM, SSH), volituste taaskasutus, liiga laiad administraatoriõigused, halvasti seadistatud Active Directory (nt delegeeringud, õigused), jagatud kohalikud administraatori paroolid.

**Kaitse:** segmenteerimine, vähimate õiguste põhimõte, administraatorikontode eraldamine (tööjaama-, serveri- ja domeeniadministraator), kohalike administraatoriparoolide unikaalsus (LAPS), kaughalduse piiramine hallatavate masinate vahel, seire ebatavalisele sisevõrgu liiklusele.

### 8.4 Veebiründed

Veebirakendused on sageli otse internetist kättesaadavad ja seega atraktiivne sihtmärk. Levinud nõrkused (OWASP Top 10 teemad):

- **Süstid** (*injection*): SQL, käsuread, LDAP – kasutaja sisend satub käsuna üle. Kaitse: parameetriseeritud päringud, sisendi valideerimine.
- **Katkine ligipääsukontroll** (*broken access control*): kasutaja näeb või muudab andmeid, millele tal pole õigust. Kaitse: serveripoolne õiguste kontroll igal päringul.
- **Cross-Site Scripting (XSS):** pahatahtlik skript käivitub teise kasutaja brauseris. Kaitse: väljundi kodeerimine, Content Security Policy.
- **CSRF:** kasutaja brauser saadab tema teadmata päringu. Kaitse: CSRF-tokenid, SameSite-küpsised.
- **Turvaline autentimine ja sessioonid:** nõrk sessioonihaldus, paroolide ebaturvaline hoidmine. Kaitse: tugev hashimine (nt bcrypt/Argon2), turvalised küpsised.
- **Turvaseadistuse vead:** vaikeparoolid, avatud halduspaneelid, liigsed veateated.
- **Haavatavad komponendid:** vananenud teegid, pluginad (nt CMS-i pluginad).

Seda teemat on soovitatav täiendada praktilise laboriga, kus õppijad leiavad ja parandavad nõrkusi omaenda arendatud rakenduses (PHP/MySQL või muu).

### 8.5 Teenusetõkestamine (DoS/DDoS)

**DoS** (*Denial of Service*) üritab muuta teenuse kasutajatele kättesaamatuks. **DDoS** teeb seda paljudest allikatest (sageli botnetist).

**Vormid:**

- **Mahupõhine** (*volumetric*): võrgu ribalaiuse täitmine (nt UDP-üleujutus, amplifikatsioon DNS/NTP kaudu);
- **Protokollipõhine**: ressursside ammutamine protokolli nõrkuse kaudu (nt SYN flood);
- **Rakenduse tasandi:** koormus raskete päringutega (nt HTTP flood).

**Kaitse:** DDoS-kaitseteenused (CDN, scrubbing center), kiirusepiirangud (*rate limiting*), liigse liikluse filtreerimine lähteaadressil, ISP-ga koostöö, hägus- ja skaleerimise planeerimine. DDoS-i kasutatakse sageli ka **hämamiseks** või **väljapressimiseks**.

### 8.6 Tarneahela ja kolmandate osapoolte ründed

Ründaja ei sihi alati lõplikku ohvrit, vaid selle **tarnijat** või **kasutatavat tarkvara**.

- Pahatahtlik **tarkvarauuendus** (nt NotPetya, SolarWinds);
- Kompromiteeritud **teegid ja paketihaldurid** (nt pahatahtlikud npm-/PyPI-paketid);
- **Hallatud teenusepakkujate** (MSP) kontode ja tööriistade kompromiteerimine, mis annab ligipääsu paljudele klientidele;
- **Riistvara ja püsivara** manipuleerimine (harv, kuid keeruline avastada).

**Kaitse:** tarnijate riskihindamine, tarkvara tarneahela kontrollid (SBOM, allkirjade kontroll), klientide keskkonna eraldamine, ligipääsu piiramine ja monitooring ka tarnijate kontodele.

### 8.7 Andmete väljaviimine ja varjatud kanalid

Väljaviimine (*exfiltration*) toimub sageli tavalise liikluse sees, et vältida avastamist:

- HTTPS-liiklus pilveteenustesse (nt failijagamisplatvormid);
- DNS-päringud, milles on andmeid peidetud (*DNS tunneling*);
- andmete pakkimine ja jagamine väiksemateks osadeks;
- ajastamine öösel või nädalavahetusel.

**Tuvastus:** väljuva liikluse mahu ja sihtkohtade monitooring, DNS-anomaaliad, DLP-lahendused, ebatavalised kettaoperatsioonid (nt suurte arhiivide loomine).

## 9. Näide: ühe ründe läbikäimine mudelitega

Vaatleme **väikese ettevõtte lunavararünnet** (fiktiivne stsenaarium õpetööks):

| Aeg | Sündmus | Kill Chain | ATT&CK taktika/tehnika |
| --- | --- | --- | --- |
| Päev 1 | Ründaja kogub töötajate e-posti aadresse avalikest allikatest | Luure | Reconnaissance |
| Päev 3 | Raamatupidaja saab „arve” manusega; avab selle | Kohaletoimetamine, ekspluateerimine | Initial Access: Phishing (T1566) |
| Päev 3 | Manusest käivitub skript, mis laadib alla laaduri | Ekspluateerimine, paigaldamine | Execution: Scripting (T1059) |
| Päev 3 | Ajastatud ülesanne tagab kinnistumise | Paigaldamine | Persistence: Scheduled Task (T1053) |
| Päev 4 | Pahavara loob ühenduse välise serveriga | Juhtimine | Command and Control |
| Päev 5 | Ründaja varastab volitusi arvuti mälust | Tegevus eesmärgi nimel | Credential Access: Credential Dumping (T1003) |
| Päev 6 | Ründaja uurib domeeni ja serverite nimekirja | Tegevus eesmärgi nimel | Discovery |
| Päev 7 | Liikumine failiserverile ja varukoopiaserverile RDP kaudu | Tegevus eesmärgi nimel | Lateral Movement: Remote Services (T1021) |
| Päev 8 | Andmeid viiakse pilvekausta | Tegevus eesmärgi nimel | Exfiltration (T1567) |
| Päev 9 (öö) | Varukoopiad kustutatakse, kaitsetarkvara lülitatakse välja | Tegevus eesmärgi nimel | Defense Evasion (T1562) |
| Päev 9 (öö) | Failid krüpteeritakse | Tegevus eesmärgi nimel | Impact: Data Encrypted (T1486) |

**Analüüs – kus oleks rünne saanud katkeda?**

- **Päev 3:** e-posti filter, makrode blokeerimine, teadliku kasutaja teatamine → rünne peatub.
- **Päev 3–4:** EDR tuvastab ebatavalise protsessiahela ja ajastatud ülesande → rünne peatub.
- **Päev 5:** LSA-kaitse ja Credential Guard raskendavad volituste hankimist.
- **Päev 7:** segmenteerimine ja eraldi halduskontod takistavad liikumist varukoopiaserverile.
- **Päev 8:** DLP või väljuva liikluse seire märkab ebatavalist andmemahtu.
- **Päev 9:** muutumatu varukoopia tagab taastamise isegi siis, kui kõik muud kihid ebaõnnestusid.

**Õpetus:** rünnet oli võimalik peatada vähemalt kuues kohas. Rünnaku edu eeldab, et kõik kihid ebaõnnestuvad.

## 10. Tuvastusloogika: kuidas mudeleid kaitses kasutada

Tuvastusinsener mõtleb tehnikate kaudu:

1. **Vali tehnika** (nt T1003 Credential Dumping).
2. **Tuvasta andmeallikad**: millised logid peaksid seda näitama (nt protsessi loomine, ligipääs LSASS-ile, Sysmon).
3. **Kirjelda ebatavalist käitumist**: mis eristab rünnet tavaprotsessist?
4. **Kirjuta tuvastusreegel** (SIEM-is või EDR-is), nt Sigma-formaadis.
5. **Testi reeglit** kontrollitud simulatsiooniga (nt Atomic Red Team) turvalises keskkonnas.
6. **Vähenda valehäireid**: lisa erandid tavapärastele toimingutele.
7. **Dokumenteeri reageerimine**: mida analüüsija teeb, kui häire tängib.

**Pyramid of Pain:** ründaja jaoks on kõige „valusam” kaotada tehnikaid ja taktikaid (TTP), mitte üksikuid räsi või IP-aadresse. Seetõttu on **käitumispõhised tuvastusreeglid** vastupidavamad kui puhtalt IoC-del põhinevad.

## 11. Seos E-ITS-iga

E-ITS aitab ründemudeleid praktikas rakendada: iga ründetehnika vastab mõnele **ohule**, mille vastu on E-ITS-is **meetmed**. Soovitatav on õppijatega kaardistada:

- ründetehnika → ohu → E-ITS-i meede (nt õngitsus → teadlikkuse ja e-posti kaitse meetmed; volituste rünne → autentimise ja ligipääsu haldus; külgsuunaline liikumine → võrgu segmenteerimine);
- olemasolevad tuvastus- ja reageerimismeetmed ründeetappide lõikes;
- lüngad: millistel ründe etappidel puudub meil meede.

*Märkus õpetajale:* täpsed moodulid ja meetmed tasub valida E-ITS-i kehtivast versioonist.

## 12. Praktilised ülesanded

1. **Kill Chain’i analüüs.** Vali tegelik ründejuhtum (nt Colonial Pipeline, NotPetya) ja kirjelda seda Kill Chain’i seitsme etapi kaudu. Märgi iga etapi juures, millised meetmed oleksid ründe katkestanud.
2. **ATT&CK-i kaardistamine.** Võta õpetaja antud lühike intsidendikirjeldus ja kaardista iga sündmus ATT&CK-i taktikale ja tehnikale. Võrdle tulemusi kaaslasega.
3. **Tuvastuskattekaart.** Kasuta ATT&CK Navigatorit ja märgi, milliseid tehnikaid sinu organisatsiooni (või näidisorganisatsiooni) olemasolevad kaitsemeetmed katavad. Too välja kolm suurimat lünka.
4. **Diamond Model.** Kirjelda ühe õngitsuskampaania sündmus nelja elemendi kaudu ja mõtle, kuidas seda seostada teise sündmusega.
5. **Tuvastusreegli kavand.** Vali üks tehnika ja kirjelda täielikult tuvastusloogika (andmeallikas, ebatavaline käitumine, valehäired, reageerimine). Reeglit ei pea realiseerima.
6. **AD-keskkonna riskianalüüs.** Vaata õppelõukeskkonna Active Directory seadistust ja leia kolm kohta, mis võiksid külgsuunalist liikumist hõlbustada (nt jagatud kohalik administraatoriparool, liiga laiad õigused). Paku parandusmeetmed.
7. **Purple team harjutus (õpetaja juhtimisel).** Kontrollitud, isoleeritud keskkonnas simuleerib õpetaja ühte kahjutut tehnikat (nt uue ajastatud ülesande loomine) ja õppijad uurivad, millise jälje see logidesse jätab.

## 13. Kontrollküsimused

1. Miks ründemudeleid kaitses kasutatakse?
2. Nimeta Cyber Kill Chain’i seitse etappi ja too iga etapi juurde üks kaitsemeede.
3. Mille poolest erineb ATT&CK-i taktika tehnikast?
4. Millised on Kill Chain’i piirangud võrreldes ATT&CK-iga?
5. Mida kirjeldab Diamond Model ja kuidas see aitab ründekampaaniaid seostada?
6. Mille poolest erineb password spraying brute force’ist?
7. Miks on Kerberoasting ohtlik nõrkade teenusekontode paroolide puhul?
8. Kuidas aitab segmenteerimine külgsuunalise liikumise vastu?
9. Mida tähendab, et ründaja peab ületama kõik etapid, kaitsja aga peatama ühe?
10. Miks on käitumispõhised tuvastusreeglid vastupidavamad kui IoC-põhised?

## 14. Soovitatav lisalugemine

- MITRE ATT&CK (attack.mitre.org) ja ATT&CK Navigator
- Lockheed Martin, *Intelligence-Driven Computer Network Defense* (Cyber Kill Chain’i algtekst)
- Caltagirone, Pendergast, Betz, *The Diamond Model of Intrusion Analysis*
- OWASP Top 10 (owasp.org)
- David Bianco, *The Pyramid of Pain*
- Sigma reeglid (SigmaHQ) ja Atomic Red Team (Red Canary)
- RIA küberturvalisuse aastaraamat ja CERT-EE ülevaated
- ENISA Threat Landscape, Verizon DBIR

## 15. Sõnastik

- **APT** – Advanced Persistent Threat, pikaajaline ja sihipärane ründaja.
- **ATT&CK** – MITRE-i ründaja taktikate ja tehnikate teadmusbaas.
- **Credential dumping** – volituste väljavõtmine süsteemist.
- **DLP** – Data Loss Prevention, andmeleke ennetamine.
- **IoC** – Indicator of Compromise, kompromiteerimise tunnus.
- **Kill Chain** – ründe etappide mudel.
- **Lateral movement** – külgsuunaline liikumine võrgus.
- **Purple team** – punase (ründaja) ja sinise (kaitsja) meeskonna koostöö.
- **TTP** – Tactics, Techniques and Procedures.

## Lisa õpetajale: iseseisvate tööde teemad ja juhendid

*See osa on mõeldud õpetajale. Iga peatükki 12 ülesande kohta on 5 teemavarianti ning valmis ülesande kirjeldus, mille saab õppijatele kodutööks või iseseisvaks tööks välja jagada.*

### Kuidas kasutada

- Jaga teemad õppijate vahel nii, et naabrid ei saaks sama teemat; 5 teemat sobib 5-liikmelistele gruppidele või rotatsiooniks 10–15 õppija korral (2–3 õppijat teema kohta, kuid töö esitatakse eraldi).
- Soovitatav maht: **3–5 akadeemilist tundi** ülesande kohta, välja arvatud ülesanne 7, mis toimub klassis.
- Kõik ülesanded on **analüütilised ja kaitsele suunatud**. Õppijad ei kirjuta ega käivita pahavara. Praktilised katsed tehakse ainult õpetaja antud isoleeritud laborikeskkonnas.
- Üldine hindamisskaala igal ülesandel: **100 punkti** (kriteeriumid on iga ülesande juures). 90+ = suurepärane, 75–89 = hea, 60–74 = rahuldav, alla 60 = täiendada.
- Allikatena lase kasutada ametlikke materjale (MITRE ATT&CK, RIA/CERT-EE, ENISA, tootjate dokumentatsioon). Nõua viiteid ja ära luba kopeerida võõraid tekste.

---

### Ülesanne 1: Kill Chain’i analüüs

**Eesmärk:** õppija kaardistab tegeliku ründejuhtumi Cyber Kill Chain’i etappidele ja hindab kaitsemeetmete mõju.

**5 teemat (ründejuhtumid):**

| # | Teema | Fookus |
| --- | --- | --- |
| 1 | Colonial Pipeline (2021) | Sissepääs VPN-i kaudu, IT/OT-eraldatus |
| 2 | SolarWinds (2020) | Tarneahela rünne, pikaajaline kohalolek |
| 3 | Equifax (2017) | Paigatamata veebirakendus, andmeleke |
| 4 | Target (2013) | Tarnija kaudu sissepääs, külgsuunaline liikumine |
| 5 | Kaseya VSA (2021) | MSP-vahendi kompromiteerimine, lunavara massiliselt |

**Ülesande kirjeldus õppijale:**

Vali oma teema ning leia vähemalt **kolm usaldusväärset allikat** (ametlikud aruanded, uurijate analüüsid). Kirjelda rünnet Kill Chain’i seitsme etapi kaudu. Iga etapi juures too välja: (a) mis juhtus, (b) milline meede oleks selle etapi peatanud, (c) kas organisatsioonil see meede oli olemas. Lõpuks vasta küsimusele: **millised kaks etappi oleks olnud kõige odavam ja efektiivsem katkestada?**

**Esitatav:** 3–4 lk analüüs + üks tabel (etapp / sündmus / meede) + allikate loetelu.

**Hindamiskriteeriumid (100 p):** faktide täpsus ja allikad (25) · etappide korrektne kaardistamine (25) · kaitsemeetmete asjakohasus (25) · järeldused ja kriitiline mõtlemine (15) · vormistus (10).

---

### Ülesanne 2: ATT&CK-i kaardistamine

**Eesmärk:** õppija õpib lugema intsidendi kirjeldust ja seostama sündmusi ATT&CK-i taktikate ning tehnikatega.

**5 teemat (õpetaja koostab iga teema kohta 8–12 lausega fiktiivse intsidendi kirjelduse):**

| # | Stsenaarium | Fookus |
| --- | --- | --- |
| 1 | Väikese ettevõtte lunavararünne | Õngitsus → volitused → krüpteerimine |
| 2 | BEC-rünne raamatupidamises | Sotsiaalne manipulatsioon, postkasti reeglid |
| 3 | Siseringi andmevargus | Kogumine, väljaviimine pilveteenusesse |
| 4 | Veebiserveri kompromiteerimine (CMS-i plugin) | Avaliku rakenduse ekspluateerimine, veebishell, püsivus |
| 5 | Microsoft 365 konto ülevõtmine | Kehtivad kontod, sessiooniülevõtmine, edasisuunamisreeglid |

**Ülesande kirjeldus õppijale:**

Loe läbi õpetaja antud intsidendi kirjeldus. Leia igale sündmusele vastav **ATT&CK-i taktika, tehnika (ja vajadusel alamtehnika)** ning kirjuta tehnika ID. Seejärel lisa iga tehnika juurde: (a) milline logi või andmeallikas oleks seda näidanud, (b) üks leevendusmeede ATT&CK-i lehelt. Esita tulemus ATT&CK Navigatori kihina (JSON) või tabelina. Lisa lühike (5–8 lauset) kokkuvõte: **mis oli ründe kõige nähtavam etapp?**

**Esitatav:** tabel või Navigatori fail + kokkuvõte.

**Hindamiskriteeriumid (100 p):** tehnikate tuvastamise täpsus (35) · andmeallikate ja tuvastuse mõistlikkus (25) · leevenduste asjakohasus (20) · kokkuvõte (10) · vormistus (10).

---

### Ülesanne 3: Tuvastuskattekaart

**Eesmärk:** õppija hindab, milliseid ründetehnikaid organisatsiooni olemasolevad meetmed katavad ja kus on lüngad.

**5 teemat (näidisorganisatsioonid):**

| # | Organisatsioon | Fookus |
| --- | --- | --- |
| 1 | Windows Server + AD keskkonnaga ärifirma (50 töökohta) | Domeeni- ja serverikaitse |
| 2 | Kool või õppeasutus (õppijate ja personali võrk) | Segmenteerimine, kasutajate mitmekesisus |
| 3 | Veebihostinguga väikefirma (PHP/MySQL) | Veebiserver, andmebaas, varundus |
| 4 | Pilvepõhine startup (Microsoft 365 + SaaS) | Identiteet, e-post, pilvelogid |
| 5 | Kohalik omavalitsus | Piiratud ressurss, kriitilised teenused, E-ITS |

**Ülesande kirjeldus õppijale:**

Õpetaja annab valitud organisatsiooni lühikirjelduse (süsteemid, kaitsevahendid, logimine). Kasuta **ATT&CK Navigatorit** ja värvi tehnikad kolmes kategoorias: **tuvastame**, **tuvastame osaliselt**, **ei tuvasta**. Vali vähemalt **15 tehnikat**, mis on selle organisatsiooni jaoks kõige asjakohasemad (põhjenda valikut). Too välja **kolm suurimat lünka** ja iga lünga jaoks kaks kaitsemeetet, millest üks on odav ja kiire.

**Esitatav:** Navigatori kihi eksport (pilt või JSON) + 2 lk selgitus ja soovitused.

**Hindamiskriteeriumid (100 p):** tehnikate valiku põhjendus (20) · hinnangute realistlikkus (25) · lüngad ja nende riskid (25) · soovituste praktilisus ja prioriseerimine (20) · vormistus (10).

---

### Ülesanne 4: Diamond Model

**Eesmärk:** õppija kirjeldab sündmust Diamond Model’i nelja elemendi kaudu ja seostab sündmusi kampaaniateks.

**5 teemat (õpetaja annab iga teema kohta kaks lühikest sündmuse kirjeldust):**

| # | Kampaania tüüp | Fookus |
| --- | --- | --- |
| 1 | „Arve”-õngitsuskampaania | Jagatud taristu e-posti vahendajatega |
| 2 | Credential stuffing veebipoe vastu | Lekkinud volitused, automatiseeritud sisselogimised |
| 3 | Hacktivistlik DDoS-kampaania | Motiiv, ajastus, botnet |
| 4 | Pahatahtlik teegipakett arendajate vastu | Tarneahel, ohvriks arendajad |
| 5 | Sihitud spear phishing juhtidele | Adversary profileerimine, luure |

**Ülesande kirjeldus õppijale:**

Täida kummagi sündmuse jaoks Diamond Model’i diagramm (**Adversary, Capability, Infrastructure, Victim**) ning lisa meta-tunnused, mida mõni allikas lubab (aeg, faas, tulemus). Seejärel võrdle sündmusi: **millised elemendid kattuvad ja kas see viitab samale kampaaniale?** Selgita järeldus ning too välja, millist lisainfot oleks vaja kindluse suurendamiseks.

**Esitatav:** kaks diagrammi + 1–2 lk analüüs.

**Hindamiskriteeriumid (100 p):** elementide korrektne täitmine (30) · seoste analüüs (30) · järelduste põhjendatus ja ettevaatlikkus (20) · lisainfo vajaduse hindamine (10) · vormistus (10).

---

### Ülesanne 5: Tuvastusreegli kavand

**Eesmärk:** õppija mõtleb läbi ühe tehnika tuvastusloogika, seostab selle andmeallika ja hindab valehäireid.

**5 teemat (tehnikad):**

| # | Tehnika | Soovituslikud andmeallikad |
| --- | --- | --- |
| 1 | Password spraying (T1110.003) | Ebaõnnestunud sisselogimised (nt Event ID 4625), VPN- ja pilvelogid |
| 2 | Scheduled Task (T1053) | Ajastatud ülesande loomine (nt Event ID 4698) |
| 3 | OS Credential Dumping (T1003) | Protsessi ligipääs LSASS-ile (nt Sysmon Event ID 10) |
| 4 | Remote Services / RDP (T1021) | Sisselogimised (nt Event ID 4624, tüüp 10), võrguliiklus |
| 5 | Impair Defenses (T1562) | Kaitsetarkvara seadistuste ja teenuse muudatused |

*Event ID-d on näited – õppija peab need üle kontrollima Microsofti dokumentatsioonist ja oma keskkonnast.*

**Ülesande kirjeldus õppijale:**

Kirjuta ühe tehnika kohta **tuvastusloogika kirjeldus**, mis sisaldab: (1) tehnika lühikirjeldus oma sõnadega, (2) andmeallikad ja nõutavad logid, (3) tuvastuskriteerium (mis tingimustel häire tängib, nt lävi ja ajaaken), (4) võimalikud **valehäired** ja kuidas neid vähendada, (5) analüüsija reageerimissammud, (6) kuidas reeglit turvaliselt testida. Reeglit ei pea SIEM-is realiseerima, kuid soovi korral võib esitada pseudokoodi või Sigma-vormingus kavandi.

**Esitatav:** 2–3 lk dokument.

**Hindamiskriteeriumid (100 p):** tehnika ja andmeallika mõistmine (25) · tuvastuskriteeriumi loogika (25) · valehäirete analüüs (20) · reageerimine (15) · testimisplaan (10) · vormistus (5).

---

### Ülesanne 6: Active Directory riskianalüüs

**Eesmärk:** õppija leiab AD-keskkonnast seadistusi, mis hõlbustavad volituste rünnet ja külgsuunalist liikumist, ning pakub parandused.

**5 teemat:**

| # | Teema | Mida kontrollida |
| --- | --- | --- |
| 1 | Teenusekontod ja Kerberoasting | Teenusekontode paroolide tugevus ja aegumine, gMSA kasutus |
| 2 | Kohalikud administraatoriparoolid | Jagatud paroolid, LAPS-i olemasolu |
| 3 | Privilegeeritud rühmad | Domain Admins, Enterprise Admins liikmed, liigsed õigused |
| 4 | NTLM ja vanad protokollid | NTLM-i kasutus, SMBv1, LDAP-i allkirjastamine |
| 5 | GPO ja delegeeringud | GPO muutmisõigused, OU delegeerimine, ligipääsuõigused |

**Ülesande kirjeldus õppijale:**

Tee analüüs **õpetaja antud õppelabori** (või õpetaja koostatud dokumenteeritud andmestiku) pealt; ära uuri ühtegi tegelikku töökeskkonda ilma kirjaliku loata. Dokumenteeri leiud: (a) mida kontrollisid ja kuidas (käsk, konsool), (b) mida leidsid, (c) milline ründetehnika seda ekspluateeriks (ATT&CK ID), (d) riski hinnang (tõenäosus × mõju), (e) parandusmeede ja selle töömaht. Koosta lõpuks **prioriteetide nimekiri** (top 5 parandust).

**Esitatav:** riskianalüüsi aruanne (3–5 lk) + ekraanikuvad või käskude väljundid.

**Hindamiskriteeriumid (100 p):** leidude täpsus ja tõendid (25) · seos ründetehnikatega (20) · riskihinnang (20) · parandusmeetmete tehniline kvaliteet (25) · vormistus (10).

---

### Ülesanne 7: Purple team harjutus (klassis, õpetaja juhtimisel)

**Eesmärk:** õppija näeb, millise jälje kahjutu ründetegevus logidesse jätab, ja õpib tuvastust parandama.

**5 teemat (õpetaja demonstreerib isoleeritud virtuaalkeskkonnas ühe kahjutu tegevuse):**

| # | Demonstreeritav tegevus | Seotud tehnika |
| --- | --- | --- |
| 1 | Uue ajastatud ülesande loomine | T1053 (Persistence) |
| 2 | Uue kohaliku kasutaja loomine ja lisamine administraatorite rühma | T1136, T1098 |
| 3 | Turvalogi tühjendamine | T1070.001 (Defense Evasion) |
| 4 | Uue teenuse paigaldamine | T1543.003 (Persistence) |
| 5 | RDP-sisselogimine tavapärasest väliselt hostilt | T1021.001 (Lateral Movement) |

**Ülesande kirjeldus õppijale:**

Enne demonstratsiooni **ennusta**: milliseid logisissekandeid see tegevus tekitab? Pärast demonstratsiooni uuri süsteemi logisid, **leia tegelikud sissekanded** ja võrdle ennustusega. Kirjelda: (a) millised sündmused tekkisid (ID, allikas, olulised väljad), (b) kas tavaline seadistus tuvastaks tegevuse, (c) mis logimisseadistust tuleks muuta, (d) milline tuvastusreegel tuleks kirjutada ja milline oleks valehäire risk.

**Esitatav:** 2 lk labülühiaruanne ja logiväljavõte (puhastatud isikuandmetest).

**Hindamiskriteeriumid (100 p):** ennustuse kvaliteet (20) · logide tõenägamise ja tuvastamise täpsus (30) · logimisvõimaluste ja tuvastuse analüüs (25) · reegli ettepanek (15) · vormistus (10).

---

### Soovitused õpetajale

- **Eetika ja ohutus:** tuleta õppijatele meelde, et ründetehnikaid tohib katsetada ainult õpetaja loodud isoleeritud keskkonnas. Volitamata süsteemide uurimine on seadusega keelatud.
- **Plagiaadi vältimiseks:** erinev teema igale õppijale, suuline kaitsmine (5 minutit) ja päris juhtumite puhul nõue viidata allikatele.
- **Tagasiside:** anna hinde kõrval ka üks konkreetne parendusettepanek. Töid saab kasutada hiljem lõpuprojekti sisendina.
- **Seos lõpuprojektiga:** ülesanded 2, 3 ja 5 sobivad hästi lõpuprojekti osadeks (ründekaardistus, tuvastuse hindamine, tuvastuskavand).

## Lisa õpetajale: valmis stsenaariumid ülesannetele 2 ja 4

*Kõik ettevõtted, isikud ja sündmused on väljamõeldud. Domeenid on kujul .example ja IP-aadressid pärinevad dokumentatsiooniks reserveeritud vahemikest, seega need ei viita päris süsteemidele. Õppijatele jagamiseks kopeeri stsenaarium (ilma vastusevõtmeta). Vastusevõtmed on selle jaotise lõpus.*

### Ülesanne 2: intsidendikirjeldused ATT&CK-i kaardistamiseks

Iga sündmus on nummerdatud, et õppijad saaksid viidata numbrile ja õpetaja saaks kontrollida vastuseid.

#### Stsenaarium 1: Väikese ettevõtte lunavararünne

*Tuuleveski Mööbel OÜ: 45 töötajat, Windows Server ja Active Directory, failiserver ning domeeni liikmena töötav varukoopiaserver.*

1. Esmaspäeval kell 09:12 saab müügiassistent e-kirja teemaga „Arve 4471 – tasumata“, mille manuseks on ZIP-arhiiv.
2. Assistent avab arhiivis oleva dokumendi, mis palub lubada makrod, ja ta teeb seda.
3. Makro käivitab PowerShelli käsu, mis laadib internetist alla teise programmi.
4. Samal õhtul tekib arvutisse uus ajastatud ülesanne, mis käivitub iga süsteemi käivitumisel.
5. Teisipäeval alates kella 02:30-st loob arvuti iga viie minuti järel HTTPS-ühenduse tundmatu välise serveriga.
6. Kolmapäeval käivitatakse arvutis tööriist, mis pääseb ligi LSASS-protsessi mälule, ja pärast seda ilmub logisse teenusekonto uus kasutus.
7. Neljapäeval tehakse domeenis ebatavaliselt palju päringuid kasutajate, arvutite ja jagatud kaustade kohta.
8. Reedel kell 15:40 logitakse teenusekonto abil RDP kaudu sisse failiserverisse ja varukoopiaserverisse.
9. Reedel kell 17:10 pakitakse failiserveri kaustu arhiivi ja ligi 18 GB andmeid saadetakse välisesse pilvesalvestusse.
10. Reedel kell 23:45 peatatakse varukoopiaserveril varundusteenus, varukoopiad kustutatakse ja Windowsi varjukoopiad eemaldatakse.
11. Laupäeval kell 01:20 lülitatakse failiserveril kaitsetarkvara välja.
12. Laupäeval kell 01:35 krüpteeritakse failid ja ekraanile ilmub lunanõue.

#### Stsenaarium 2: BEC-rünne raamatupidamises

*Põhjarand Transport OÜ: 20 töötajat, Microsoft 365, mitmefaktoriline autentimine ei ole sisse lülitatud.*

1. Raamatupidaja Mari saab e-kirja, mis näib tulevat Microsoft 365 teenuselt: „Postkast on peaaegu täis, kinnita sisselogimine“, kirjas on link.
2. Link viib lehele, mis näeb välja nagu ettevõtte sisselogimisleht, ja Mari sisestab sinna kasutajanime ja parooli.
3. Tund hiljem logitakse Mari kontole sisse teisest riigist.
4. Sisselogija loob postkasti reegli, mis suunab kõik kirjad sõnadega „arve“ ja „makse“ edasi välisele aadressile.
5. Sama reegel märgib need kirjad loetuks ja liigutab need kausta, mida Mari ei vaata.
6. Järgmise kahe nädala jooksul loeb ründaja Mari postkasti ja õpib ära ettevõtte tarnijad, arvete vormid ja maksegraafikud.
7. Ründaja registreerib domeeni, mis erineb päris tarnija omast ühe tähe võrra.
8. Selle domeeni aadressilt saadab ründaja Marile kirja „meie pangakonto on muutunud“, vastates olemasolevale arveteemalisele kirjavahetusele.
9. Mari uuendab tarnija pangakonto andmed maksesüsteemis.
10. Kolme päeva pärast makstakse tarnija arve 18 400 eurot ründaja kontole ja pettus selgub, kui päris tarnija küsib arve tasumata jäämise kohta.

#### Stsenaarium 3: Siseringi andmevargus

*Kaldaprojekt Insenerid OÜ: ehitusprojekteerimisfirma, projektijuht Peeter on lahkumas konkurendi juurde.*

1. Peeter teatab, et lahkub kuu lõpus.
2. Kolme viimase nädala jooksul logib ta tööarvutisse sisse sagedamini kui varem, sh õhtuti ja nädalavahetustel.
3. Ta avab ja kopeerib klientide nimekirju ja hinnapakkumisi kaustadest, mis ei kuulu tema projektidele, kuid millele tal on lugemisõigus.
4. Failid pakitakse paroolikaitsega arhiivi.
5. Arhiiv kopeeritakse USB-mälupulgale, mis kajastub lõppseadme kaitse logis.
6. Sama arhiiv laaditakse üles isiklikku pilvesalvestusse ja veebiproksi logis on suur üleslaadimine.
7. Viimasel tööpäeval tühjendab Peeter oma tööarvuti sündmuste logi ja ajutised failid.
8. Tema konto desaktiveeritakse lahkumispäeval ja kolm nädalat hiljem teeb konkurent mitmele Kaldaprojekti kliendile sarnase pakkumise.

#### Stsenaarium 4: Veebiserveri kompromiteerimine

*Merepääsuke Reisid OÜ: reisibüroo veebileht töötab WordPressil koos broneerimispluginaga.*

1. Juunis avalikustatakse populaarses broneerimispluginas kriitiline haavatavus, mis lubab autentimata kasutajal serverisse faili laadida, ja parandus avaldatakse samal päeval.
2. Reisibüroo veebihaldur plugina uuendust ei paigalda.
3. Kolme päeva pärast ilmub veebiserveri logisse sadu päringuid plugina haavatavale aadressile, algul skaneerimisena ühelt IP-aadressilt ja seejärel ka teistelt.
4. Ründaja laadib serverisse väikese PHP-faili, mis lubab veebi kaudu käske käivitada.
5. Selle faili kaudu käivitatakse serveris käske ja laaditakse alla täiendavaid tööriistu.
6. Ründaja loeb veebirakenduse seadistusfaili ja leiab sealt andmebaasi kasutajanime ja parooli.
7. Andmebaasist tehakse tõmmis (klientide nimed, e-posti aadressid, broneeringud).
8. Tõmmis saadetakse veebiserverist HTTPS-ühendusega ründaja serverisse.
9. Ründaja lisab lehe päisesse peidetud JavaScripti, mis suunab osa külastajatest pettuslehele.
10. Nädal hiljem teatab klient, et pärast broneeringu tegemist sai ta petukirja.

#### Stsenaarium 5: Microsoft 365 konto ülevõtmine

*Linnamaja Arendus OÜ: 60 töötajat, Microsoft 365, mitmefaktoriline autentimine on kasutusel.*

1. Projektijuht Kristiina saab e-kirja „Jagatud dokument: projektipakkumine“ lingiga sisselogimislehele.
2. Leht on vahendusproksi, mis edastab sisselogimise päris Microsoftile, seega sisestab Kristiina parooli ja kinnitab mitmefaktorilise autentimise tavapäraselt.
3. Ründaja varastab sisselogimisel tekkinud sessiooni tunnuse (küpsise).
4. Üksteist minutit hiljem logitakse Kristiina kontole sama sessiooniga teisest riigist ja uut MFA-nõuet ei esitata.
5. Ründaja registreerib kontole uue autentimisseadme, et ligipääs säiliks ka pärast sessiooni lõppu.
6. Ründaja loob postkasti reegli, mis kustutab kirjad märksõnadega „turvahoiatus“ ja „ebatavaline sisselogimine“.
7. Ründaja otsib Kristiina postkastist kirju märksõnadega „arve“, „leping“ ja „parool“.
8. Kristiina kontolt saadetakse 40 kolleegile sama „jagatud dokumendi“ link.
9. Kaks kolleegi sisestavad oma andmed samale leheküljele.
10. IT-tugi märkab hoiatust ebatavalisest sisselogimisest ja lähtestab Kristiina parooli, kuid ründaja registreeritud seade jääb kontole alles ja ligipääs säilib.

---

### Ülesanne 4: Diamond Model'i stsenaariumid

Iga teema juures on kaks sündmust (A ja B). Õppijad täidavad mõlema jaoks Diamond Model'i neli elementi ja otsustavad, kas sündmused kuuluvad samasse kampaaniasse, ning hindavad oma kindlust (madal, keskmine, kõrge).

#### Teema 1: „Arve“-õngitsuskampaania

**Sündmus A (teisipäev, Tuuleveski Mööbel OÜ).** Raamatupidaja saab e-kirja teemaga „Tasumata arve nr 2381“ aadressilt info@arve-teavitus.example. Kiri on eesti keeles ja allkirjastatud „Lugupidamisega, Finantsosakond“ (ettevõtte nime pole). Manuseks on HTML-fail, mis suunab sisselogimislehele https://portaal.arve-teavitus.example/login. Lehe server asub IP-aadressil 198.51.100.17 ja kirja saatnud server on IP-aadressil 203.0.113.45. Kolm töötajat sisestas oma andmed.

**Sündmus B (neljapäev, Kalamaja Pood AS).** Juhtivtöötaja saab e-kirja teemaga „Maksemeeldetuletus: arve 5120“ aadressilt teavitus@arvekeskus-info.example. Kiri on eesti keeles ja allkirjastatud „Lugupidamisega, Finantsosakond“. Manuseks on HTML-fail, mis suunab lehele https://arvekeskus-info.example/login. Lehe server asub IP-aadressil 198.51.100.17 ja kirja saatnud server on IP-aadressil 203.0.113.45. Üks töötaja sisestas oma andmed.

#### Teema 2: Credential stuffing veebipoodide vastu

**Sündmus A (märts, Merelaine Pood).** Öösel kell 02:10–02:40 tehakse veebipoe sisselogimislehele 4800 katset 120 erinevalt IP-aadressilt (elamupiirkonna proksid). Kõigi päringute User-Agent on identne. Edukaid sisselogimisi on 1,3%. Pärast edukat sisselogimist kontrollib ründaja kliendikonto kinkekaardi saldot ja lahkub.

**Sündmus B (aprill, Tuuletark OÜ).** Hommikul kell 04:00–04:50 tehakse sisselogimislehele 6000 katset 95 erinevalt IP-aadressilt, ükski neist ei kattu sündmuse A omadega. Päringute User-Agent on teistsugune ja varieerub päringute vahel. Edukaid sisselogimisi on 0,9%. Pärast edukat sisselogimist muudab ründaja konto e-posti aadressi ja tellib kaupa pakiautomaati.

#### Teema 3: Hacktivistlik DDoS-kampaania

**Sündmus A (reede kell 18:00, linnavalitsuse veebileht).** Avalikus sotsiaalmeediakanalis ilmub teade sihtmärgi ja kampaania sildiga #KaitseMeieÕigusi. Täpselt kell 18:00 algab HTTP-üleujutus: ligi 40 000 päringut sekundis umbes 3000 IP-aadressilt, päringud lähevad aadressile /otsing?q=… ja otsingusõna on juhuslik. Kõigis päringutes on päis X-Kampaania: kaitse. Veebileht ei ole kaks tundi kättesaadav.

**Sündmus B (järgmise nädala reede kell 18:00, maakonnahaigla patsiendiportaal).** Samas kanalis ilmub uus teade sama sildiga. Täpselt kell 18:00 algab HTTP-üleujutus: ligi 35 000 päringut sekundis umbes 2800 IP-aadressilt, millest umbes pooled ei kattu sündmusega A. Päringud lähevad aadressile /otsing?q=… juhusliku otsingusõnaga ja päises on X-Kampaania: kaitse. Portaal on 90 minutit aeglane, kuid osaliselt kasutatav.

#### Teema 4: Pahatahtlik teegipakett arendajate vastu

**Sündmus A (teisipäev, Pikseltehas OÜ).** Arendaja paigaldab paketihaldurist paketi „datefmt-fast“, mille avaldaja konto on „devtools-ee“. Paigaldusskript saadab arenduskeskkonna keskkonnamuutujad (sh API-võtmed) HTTPS-ühendusega aadressile https://stats-collect.example/ingest. Pakett on avaldatud kolm päeva varem ja sellel on 12 allalaadimist.

**Sündmus B (kolmapäev, Koodikoda AS).** Arendaja paigaldab paketi „logger-fastjs“, mille avaldaja konto on samuti „devtools-ee“. Paigaldusskript saadab CI-keskkonna tokenid HTTPS-ühendusega aadressile https://stats-collect.example/ingest. Pakett on avaldatud kuus päeva varem ja sellel on 31 allalaadimist.

#### Teema 5: Sihitud spear phishing juhtidele

**Sündmus A (esmaspäev, Ehitusgrupp AS).** Finantsjuht saab e-kirja „Teie hankeleping: dokumendid vaatamiseks“ saatjanimega „Marko Tamm“. Kiri viitab avalikus hankeregistris avaldatud hankele, mille võitja on Ehitusgrupp. Link viib lehele https://hanke-dokumendid.example/portal/doc-view?id=4410, mis küsib Microsoft 365 sisselogimisandmeid. Lehe server asub IP-aadressil 192.0.2.88.

**Sündmus B (kolmapäev, Teetööd OÜ).** Tegevjuht saab e-kirja „Teie hankeleping: dokumendid vaatamiseks“ saatjanimega „Marko Tamm“. Kiri viitab teisele avalikus hankeregistris avaldatud hankele, mille võitja on Teetööd. Link viib lehele https://hanketeade.example/portal/doc-view?id=7305, mis küsib Microsoft 365 sisselogimisandmeid. Lehe server asub IP-aadressil 192.0.2.88.

---

### Vastusevõtmed (ainult õpetajale)

#### Ülesanne 2: ATT&CK-i kaardistus

Vastused on viitelahendused. Võta arvesse, et sama sündmust saab mõnikord õigustatult kaardistada kahel tehnikal, ning kontrolli ID-sid ATT&CK-i kehtivast versioonist.

**Stsenaarium 1 (lunavara):** 1: Initial Access, Phishing: Spearphishing Attachment (T1566.001) · 2: Execution, User Execution: Malicious File (T1204.002) · 3: Execution, PowerShell (T1059.001) ja Command and Control, Ingress Tool Transfer (T1105) · 4: Persistence, Scheduled Task (T1053.005) · 5: Command and Control, Application Layer Protocol: Web Protocols (T1071.001) · 6: Credential Access, OS Credential Dumping: LSASS Memory (T1003.001) · 7: Discovery, Account Discovery (T1087), Remote System Discovery (T1018), Network Share Discovery (T1135) · 8: Lateral Movement, Remote Services: RDP (T1021.001) ja Valid Accounts (T1078) · 9: Collection, Archive Collected Data (T1560) ja Exfiltration, Exfiltration to Cloud Storage (T1567.002) · 10: Impact, Inhibit System Recovery (T1490) · 11: Defense Evasion, Impair Defenses (T1562.001) · 12: Impact, Data Encrypted for Impact (T1486).

**Stsenaarium 2 (BEC):** 1–2: Initial Access, Phishing: Spearphishing Link (T1566.002) ja Phishing for Information (T1598.003) · 3: Initial Access, Valid Accounts: Cloud Accounts (T1078.004) · 4: Collection, Email Forwarding Rule (T1114.003) · 5: Defense Evasion, Email Hiding Rules (T1564.008) · 6: Collection, Remote Email Collection (T1114.002) · 7: Resource Development, Acquire Infrastructure: Domains (T1583.001) · 8: Defense Evasion, Impersonation (T1656; lisatud uuemas ATT&CK-i versioonis) · 9–10: Impact, Financial Theft (T1657; samuti uuemas versioonis). Kui õppija kasutab vanemat ATT&CK-i versiooni, aktsepteeri ka üldist selgitust ilma ID-ta.

**Stsenaarium 3 (siseringi andmevargus):** 2: Valid Accounts (T1078), eristub tavakäitumisest, kuid ei ole tehniline rünne · 3: Collection, Data from Network Shared Drive (T1039) · 4: Collection, Archive via Utility (T1560.001) · 5: Exfiltration, Exfiltration Over USB (T1052.001) · 6: Exfiltration, Exfiltration to Cloud Storage (T1567.002) · 7: Defense Evasion, Clear Windows Event Logs (T1070.001) · 1 ja 8 on kontekst, mitte tehnika. Hea arutelukoht: kuidas eristada tavaline töökäitumine kahtlasest ilma süüdistamata.

**Stsenaarium 4 (veebiserver):** 1–2: ei ole tehnika, vaid tingimused (haavatavus ja paigata jätmine) · 3: Reconnaissance, Vulnerability Scanning (T1595.002) ja Initial Access, Exploit Public-Facing Application (T1190) · 4: Persistence, Server Software Component: Web Shell (T1505.003) · 5: Execution, Unix Shell (T1059.004) ja Ingress Tool Transfer (T1105) · 6: Credential Access, Credentials in Files (T1552.001) · 7: Collection, Data from Local System (T1005) või Data from Information Repositories (T1213) · 8: Exfiltration, Exfiltration Over C2 Channel (T1041) · 9: Initial Access (külastajate suhtes), Content Injection (T1659) või Impact, External Defacement (T1491.002) · 10: tagajärg.

**Stsenaarium 5 (M365 konto ülevõtmine):** 1: Initial Access, Phishing: Spearphishing Link (T1566.002) · 2: Credential Access, Adversary-in-the-Middle (T1557) · 3: Credential Access, Steal Web Session Cookie (T1539) · 4: Defense Evasion, Use Alternate Authentication Material: Web Session Cookie (T1550.004) ja Valid Accounts: Cloud Accounts (T1078.004) · 5: Persistence, Account Manipulation: Device Registration (T1098.005) · 6: Defense Evasion, Email Hiding Rules (T1564.008) · 7: Collection, Remote Email Collection (T1114.002) · 8: Lateral Movement, Internal Spearphishing (T1534) · 9: Initial Access, Spearphishing Link (T1566.002) · 10: õppetund: parooli lähtestamine ei tühista kehtivat sessiooni ega registreeritud seadet, seega tuleb kindlasti tühistada sessioonid ja eemaldada võõras autentimisseade.

#### Ülesanne 4: Diamond Model'i viitelahendused

**Teema 1: „Arve“-õngitsus.**

| Element | Sündmus A | Sündmus B | Kattuvus |
| --- | --- | --- | --- |
| Adversary | Tundmatu | Tundmatu | Võimalik sama |
| Capability | HTML-manuse õngitsus, sisselogimisleht, sama allkirjakuju | Sama | Tugev |
| Infrastructure | Domeen arve-teavitus.example, saatev server 203.0.113.45, leht 198.51.100.17 | Domeen arvekeskus-info.example, sama saatev server ja sama lehe server | Tugev: kaks jagatud IP-aadressi |
| Victim | Väike ettevõte, raamatupidaja | Väike ettevõte, juhtivtöötaja | Sama profiil |

**Järeldus:** kõrge kindlus, et tegu on samast kampaaniast, sest jagatud on nii taristu kui ka töövõtted.

**Teema 2: Credential stuffing.**

| Element | Sündmus A | Sündmus B | Kattuvus |
| --- | --- | --- | --- |
| Adversary | Tundmatu | Tundmatu | Puudub tõend |
| Capability | Credential stuffing, kinkekaardi kontroll | Credential stuffing, andmete ülevõtmine ja kauba tellimine | Sama töövõte, erinev eesmärk |
| Infrastructure | 120 IP-d, identne User-Agent | 95 IP-d, erinev User-Agent | Ei kattu |
| Victim | Veebipood | Veebipood | Sama liik |

**Järeldus:** madal kindlus. Töövõte on levinud ja kasutatud paroolid pärinevad tõenäoliselt samadest avalikest lekkeandmetest, kuid ühist ründajat ei saa väita. See on õppetund liigse omistamise vastu.

**Teema 3: Hacktivistlik DDoS.**

| Element | Sündmus A | Sündmus B | Kattuvus |
| --- | --- | --- | --- |
| Adversary | Kampaania silt #KaitseMeieÕigusi | Sama silt | Tugev |
| Capability | HTTP-üleujutus, juhuslik otsingusõna, päis X-Kampaania | Sama | Tugev |
| Infrastructure | Umbes 3000 IP-d, avalik kanal | Umbes 2800 IP-d, pooled kattuvad, sama kanal | Osaline |
| Victim | Kohalik omavalitsus | Tervishoiuasutus | Avaliku sektori sihtmärgid |

**Järeldus:** keskmine kuni kõrge kindlus. Arutelukoht: kuna kampaaniat kuulutatakse avalikult, võivad seda kasutada ka teised kasutajad, seega tuleb hinnata, kas tegutseb üks rühm.

**Teema 4: Pahatahtlik teegipakett.**

| Element | Sündmus A | Sündmus B | Kattuvus |
| --- | --- | --- | --- |
| Adversary | Avaldaja konto devtools-ee | Sama konto | Tugev |
| Capability | Paigaldusskript, mis väljastab keskkonnamuutujad | Sama põhimõte | Tugev |
| Infrastructure | stats-collect.example | Sama aadress | Täielik |
| Victim | Arendaja, väike tarkvarafirma | Arendaja, CI-keskkond | Sarnane |

**Järeldus:** kõrge kindlus. Hea arutelukoht: kuidas aitaksid tarneahela kontrollid (paketi allkirjad, SBOM, hiljuti avaldatud pakettide piirang) rünnet vältida.

**Teema 5: Spear phishing juhtidele.**

| Element | Sündmus A | Sündmus B | Kattuvus |
| --- | --- | --- | --- |
| Adversary | „Marko Tamm“ kui saatjanimi | Sama saatjanimi | Tugev (kuid nime on lihtne võltsida) |
| Capability | Hankeregistri teabe põhjal kohandatud õngitsus, sama kirja teema ja sama aadressistruktuur | Sama | Tugev |
| Infrastructure | hanke-dokumendid.example, server 192.0.2.88 | hanketeade.example, sama server 192.0.2.88 | Tugev: sama IP |
| Victim | Ehitusfirma juht | Ehitusfirma juht | Sama sektor ja roll |

**Järeldus:** kõrge kindlus. Ründaja valib ohvrid avaliku teabe põhjal, seega tasub arutada, milliseid andmeid avalik hankeregister paljastab ja kuidas ettevõtted saaksid sihitud õngitsust ära tunda.

## Lisa õpetajale: valmis organisatsioonikirjeldused ülesandele 3

*Kõik organisatsioonid on väljamõeldud. Õppijatele jagamiseks kopeeri organisatsiooni kirjeldus (ilma vastusevõtmeta). Vastusevõtmed on selle jaotise lõpus.*

### Hindamisskaala õppijatele

Õppijad värvivad ATT&CK Navigatoris iga valitud tehnika kolme kategooria järgi:

- **Tuvastame:** on olemas andmeallikas ja keegi (või midagi) saab selle põhjal hoiatuse ning reageerib.
- **Osaliselt:** logid on olemas või ennetav meede töötab, kuid hoiatused puuduvad, logid ei ole keskselt kogutud või kaitse katab ainult osa juhtudest.
- **Ei tuvasta:** puudub nii andmeallikas kui ka ennetav meede.

Nõua iga hinnangu juurde üks lause põhjendust, mis viitab kirjelduse kindlale faktile.

---

### Organisatsioon 1: Rannamõisa Kinnisvara OÜ

*Kinnisvarabüroo, 50 töötajat, kolm kontorit, Active Directory keskkond.*

**Süsteemid**

- kaks domeenikontrollerit (Windows Server 2019), failiserver ja varukoopiaserver, mõlemad domeeni liikmed;
- 50 Windowsi töökohta;
- e-post Microsoft 365-s;
- kaugtöö VPN-iga tulemüüri kaudu.

**Kaitsemeetmed**

- Microsoft Defender viirusetõrje töökohtadel ja serverites, EDR puudub;
- e-posti vaikimisi filtreerimine Microsoft 365-s, kasutajatel pole õngitsusest teatamise nuppu;
- VPN-i sisselogimiseks piisab kasutajanimest ja paroolist, mitmefaktoriline autentimine on ainult administraatoritel;
- varukoopiad tehakse öösel võrguketta (NAS) peale, millele pääseb ligi domeeni kontodega; üks koopia viiakse kord kuus asukohast välja.

**Logimine ja seire**

- Windowsi sündmuste logid jäävad iga serveri enda kettale, keskset logide kogumist ei ole;
- domeenikontrolleritel on sisselogimise auditeerimine sees, kuid keegi neid regulaarselt ei vaata;
- tulemüüri logid säilivad seadmes seitse päeva;
- PowerShelli skriptide logimine on välja lülitatud.

**Teadaolevad omadused**

- IT-halduseks on üks täiskohaga administraator ja väline partner;
- administraator kasutab kõigeks ühte domeeniadministraatori kontot;
- kohalike administraatorite paroolid on töökohtadel ühesugused (LAPS puudub);
- RDP on lubatud kõigist sisevõrgu seadmetest kõikidesse serveritesse;
- rakenduste lubamisnimekirju (allowlisting) ei kasutata.

---

### Organisatsioon 2: Kaldaranna Kutsekool

*Kutseõppeasutus, ligi 600 õpilast ja 70 töötajat, kaks IT-töötajat.*

**Süsteemid**

- Google Workspace for Education (e-post, dokumendid, õpikeskkond);
- kohalik Active Directory arvutiklasside jaoks (nelja klassi 80 arvutit);
- õppeinfosüsteem pilveteenusena;
- õpilaste Wi-Fi ja töötajate võrk on eraldi VLAN-idel.

**Kaitsemeetmed**

- Defender viirusetõrje töötajate arvutitel, klasside arvutid taastuvad igal taaskäivitamisel algolekusse;
- DNS-filter õpilaste Wi-Fi-l blokeerib teadaolevalt pahatahtlikke ja sobimatuid domeene, töötajate võrgus filtrit ei ole;
- tulemüür blokeerib õpilaste Wi-Fi-st liikluse töötajate võrku;
- mitmefaktoriline autentimine on töötajatele Google Workspace'is vabatahtlik ja kasutusel ligikaudu 30%-l.

**Logimine ja seire**

- tsentraalset logide kogumist ei ole;
- tulemüüri blokeerimislogid säilivad 30 päeva ja IT vaatab neid juhuslikult;
- Google Workspace'i logid on saadaval administraatori konsoolis, hoiatusi ei ole seadistatud.

**Teadaolevad omadused**

- klassiarvutitel kasutavad õpetajad ühist kontot õpetaja1–õpetaja8;
- USB-mälupulkade kasutamine on lubatud kõigil arvutitel ja piiranguid ei ole;
- töötajate võrgus puudub segmenteerimine kontori, raamatupidamise ja õppejõudude vahel;
- õppeinfosüsteemi sisselogimine on paroolipõhine.

---

### Organisatsioon 3: Pilvekene Veeb OÜ

*Veebihostingufirma, 8 töötajat, 12 Linuxi serverit, kliendid on väikefirmad.*

**Süsteemid**

- 12 Linuxi serverit, igaühel veebiserver, PHP ja MySQL samas masinas;
- kliendid kasutavad enamasti WordPressi ja muid CMS-e koos pluginatega;
- hostingupaneel (haldusliides) on internetist ligipääsetav.

**Kaitsemeetmed**

- veebirakenduste tulemüür (WAF) serverite ees blokeerib teadaolevad rünnakumustrid, kuid kliendi CMS-i plugina haavatavusi ei paigata virtuaalselt;
- SSH-sisselogimine on lubatud ainult võtmega ja Fail2ban blokeerib korduvad ebaõnnestunud katsed;
- operatsioonisüsteemi turvauuendused paigaldatakse automaatselt, kuid CMS-i ja pluginate uuendamine on kliendi vastutus;
- hostingupaneeli administraatorikontodel on ainult parool, mitmefaktoriline autentimine puudub;
- varukoopiad tehakse öösel teisele serverile sama andmekeskuse sees.

**Logimine ja seire**

- veebiserveri ja SSH logid jäävad 30 päevaks igale serverile eraldi, korrelatsiooni ei tehta;
- käimasolevat teenuse kättesaadavust jälgib seiresüsteem ja saadab hoiatuse administraatoritele, turvaseiret ei ole;
- failide terviklikkuse kontrolli (FIM) ja pahavaraskannerit ei ole;
- käskude auditeerimist (auditd) ei kasutata.

**Teadaolevad omadused**

- kliendi veebikataloogid ja andmebaasiühenduse seadistusfailid (nt wp-config.php) on veebiserveri protsessile loetavad;
- samas serveris asuvad mitme kliendi saidid, kuid iga kliendil on eraldi kasutaja;
- ühe töötaja puhkuse ajal ei ole hostingupaneeli logisid keegi jälginud.

---

### Organisatsioon 4: Tähekiir Labs OÜ

*Tarkvara startup, 25 töötajat, täielik kaugtöö, kogu töö toimub pilveteenustes.*

**Süsteemid**

- Microsoft 365 (e-post, failid, kalender);
- SaaS-teenused CRM-i ja projektihalduse jaoks, kõik ühtse sisselogimisega (SSO) Microsoft 365 kaudu;
- koodihoidla pilves koos pilveinfrastruktuuriga (arenduskeskkond ja tootmiskeskkond);
- töötajate sülearvutid on haldussüsteemi (MDM) all, ketas on krüpteeritud.

**Kaitsemeetmed**

- kõigil töötajatel on kohustuslik mitmefaktoriline autentimine autentimisrakenduse märguandega (push, ilma numbri kinnitamiseta);
- sülearvutitel on EDR-lahendus;
- Microsoft 365-s on õngitsusvastane poliitika ja ebatavalise asukoha sisselogimise hoiatus (impossible travel), mis läheb ühele IT-juhile e-postiga;
- koodihoidlas on saladuste skannimine (secrets scanning) sees;
- tingimuspõhine ligipääs lubab sisselogimist igast riigist ja igast seadmest, sh haldamata seadmetest.

**Logimine ja seire**

- Microsoft 365 auditilogi on sisse lülitatud ja säilib 90 päeva, SIEM-i ei ole;
- postkastireeglite loomise ja uute autentimisseadmete registreerimise kohta hoiatusi ei ole seadistatud, kuigi sündmused jõuavad auditilogisse;
- pilveinfrastruktuuri haldustegevused logitakse, kuid neid ei jälgita regulaarselt;
- SaaS-teenuste kasutust (nt suured eksportimised) ei jälgita.

**Teadaolevad omadused**

- arendajatel on oma sülearvutis kohalikud administraatoriõigused;
- andmelekke tõkestamise (DLP) lahendust ei ole;
- turvaküsimustega tegeleb ühe inimese lisaülesandena IT-juht.

---

### Organisatsioon 5: Kaldavälja Vallavalitsus

*Kohalik omavalitsus, 120 töötajat, väga piiratud IT-eelarve.*

**Süsteemid**

- Active Directory ja töökohad, enamik Windows 10/11;
- dokumendihaldussüsteem ja registrirakendus, mis töötavad Windows Server 2012 R2 serveril, mille tootjatugi on lõppenud ja mida ei saa tarkvara tõttu uuendada;
- avalik e-teenuste portaal, mille majutab ja haldab kolmas osapool;
- e-post Microsoft 365-s.

**Kaitsemeetmed**

- allkirjapõhine viirusetõrje töökohtadel, EDR puudub;
- tulemüüri haldab väline IT-partner, kes saab vallavalitsuse võrku VPN-iga üheainsa jagatud kontoga;
- mitmefaktoriline autentimine on ainult juhtkonnal Microsoft 365-s;
- varukoopiad tehakse kord nädalas magnetlindile ja viiakse välja asukohta, taastamist on testitud kord aastas;
- töötajate turvateadlikkuse koolitus toimub kord aastas;
- E-ITS-i rakendamine on algusjärgus (baastaseme hindamine käimas).

**Logimine ja seire**

- tulemüüri logid saadetakse IT-partnerile, kes neid vajadusel uurib, vallavalitsus ise hoiatusi ei saa;
- AD ja serverite logid jäävad kohapeale ja neid ei koguta;
- avaliku portaali logid on teenusepakkuja käes, vallavalitsusel neile ligipääsu ei ole.

**Teadaolevad omadused**

- serverit 2012 R2 ei ole võrgus eraldatud, see asub samas võrgus kui töökohad;
- RDP on lubatud töökohtadelt serverisse;
- haavatavuste skaneerimist ei tehta;
- portaali turvavastutuse jaotus vallavalitsuse ja teenusepakkuja vahel ei ole lepingus selgelt kirjas.

---

### Vastusevõtmed (ainult õpetajale)

Vastused on viitelahendused. Aktsepteeri ka teistsugust hinnangut, kui õppija põhjendab seda kirjelduse konkreetse faktiga. Kui hinnang erineb võtmest, küsi õppijalt, millise logi või meetme ta eeldab.

#### Organisatsioon 1: Rannamõisa Kinnisvara OÜ

| Tehnika | Hinnang | Põhjendus |
| --- | --- | --- |
| Phishing (T1566) | Osaliselt | Vaikimisi filtrid peavad osa kirju kinni, kuid õngitsusest teatamise võimalust ei ole |
| Password Spraying (T1110.003) | Osaliselt | Sisselogimisrikked on kontrollerite logis, kuid neid ei vaadata ja VPN-il pole MFA-d |
| Valid Accounts (T1078) | Ei tuvasta | Keskseid logisid ega tavakäitumise alust ei ole |
| PowerShell (T1059.001) | Ei tuvasta | Skriptide logimine on välja lülitatud |
| Scheduled Task (T1053.005) | Ei tuvasta | Ajastatud ülesannete loomist ei koguta keskselt |
| LSASS Memory (T1003.001) | Osaliselt | Defender blokeerib tuntud tööriistu, kuid kaitset (Credential Guard) ja hoiatust ei ole |
| RDP (T1021.001) | Osaliselt | Sisselogimised on serverite logides, kuid neid ei seostata ja RDP on laialt lubatud |
| Impair Defenses (T1562.001) | Osaliselt | Defender märkab muudatusi, kuid hoiatusi erandite lisamise kohta ei ole |
| Inhibit System Recovery (T1490) | Ei tuvasta | Varjukoopiate kustutamist ei jälgita |
| Data Encrypted for Impact (T1486) | Osaliselt | Defender tunneb osa lunavara käitumist, kuid varukoopiad on domeeni kontodega ligipääsetavad |
| Exfiltration to Cloud Storage (T1567.002) | Ei tuvasta | Tulemüüri logid säilivad ainult seitse päeva ja mahu hoiatust ei ole |

**Kolm suurimat lünka:** (1) keskne logimine ja hoiatused, (2) administraatorikontode eraldamine ja LAPS, (3) varukoopiate eraldamine domeeni kontodest.

#### Organisatsioon 2: Kaldaranna Kutsekool

| Tehnika | Hinnang | Põhjendus |
| --- | --- | --- |
| Phishing (T1566) | Osaliselt | Google Workspace filtreerib, kuid MFA on töötajatel vabatahtlik |
| Brute Force (T1110) | Osaliselt | Logid on olemas, hoiatusi ei ole seadistatud ja õppeinfosüsteem on paroolipõhine |
| Valid Accounts (T1078) | Ei tuvasta | Ühiskontod klassides takistavad tegevuse seostamist isikuga |
| Replication Through Removable Media (T1091) | Ei tuvasta | USB-d on lubatud ja piiranguid ega logimist ei ole |
| Network Service Discovery (T1046) | Osaliselt | Õpilaste Wi-Fi on eraldatud, blokeeringud logitakse, kuid logisid vaadatakse juhuslikult |
| Application Layer Protocol (T1071) | Osaliselt | DNS-filter katab ainult õpilaste Wi-Fi |
| Remote Services (T1021) | Osaliselt | Töötajate võrgus puudub segmenteerimine |
| Data Encrypted for Impact (T1486) | Osaliselt | Klasside arvutid taastuvad algolekusse, kuid töötajate arvutid sõltuvad Defenderist |
| Exfiltration to Cloud Storage (T1567.002) | Ei tuvasta | Väljuva liikluse seiret ei ole |

**Kolm suurimat lünka:** (1) kohustuslik MFA kõigile, (2) isikupõhised kontod ja töötajate võrgu segmenteerimine, (3) logide kogumine ja põhihoiatused.

#### Organisatsioon 3: Pilvekene Veeb OÜ

| Tehnika | Hinnang | Põhjendus |
| --- | --- | --- |
| Exploit Public-Facing Application (T1190) | Osaliselt | WAF blokeerib tuntud mustreid, kuid kliendi CMS-i haavatavusi ei paigata |
| Web Shell (T1505.003) | Ei tuvasta | FIM-i ja pahavaraskannerit ei ole |
| Brute Force: SSH (T1110) | Tuvastame | Fail2ban ja SSH logid katavad nii ennetuse kui ka tuvastuse |
| Valid Accounts: paneeli kontod (T1078) | Osaliselt | Sisselogimislogid on olemas, kuid MFA ja hoiatused puuduvad |
| Unix Shell (T1059.004) | Ei tuvasta | Käskude auditeerimist ei ole |
| Credentials in Files (T1552.001) | Ei tuvasta | Seadistusfailid on veebiprotsessile loetavad ja ligipääsu ei jälgita |
| Exfiltration Over C2 Channel (T1041) | Ei tuvasta | Väljuva liikluse seiret ei ole |
| Data Encrypted for Impact (T1486) | Osaliselt | Öised varukoopiad aitavad taastada, kuid asuvad samas andmekeskuses ja ründe tuvastust ei ole |
| Network Denial of Service (T1498) | Osaliselt | Kättesaadavuse seire annab märku, kuid DDoS-i leevendust ei ole kirjeldatud |

**Kolm suurimat lünka:** (1) failide terviklikkuse kontroll ja pahavaraskanner veebikataloogidel, (2) MFA hostingupaneelile, (3) auditd ja logide kogumine ühte kohta.

#### Organisatsioon 4: Tähekiir Labs OÜ

| Tehnika | Hinnang | Põhjendus |
| --- | --- | --- |
| Spearphishing Link (T1566.002) | Osaliselt | Õngitsusvastane poliitika ja EDR aitavad, kuid kasutajatel puudub teatamise võimalus |
| Steal Web Session Cookie (T1539) | Osaliselt | Hoiatus ebatavalisest asukohast on olemas, kuid haldamata seadmed on lubatud ja tokeneid ei seota seadmega |
| Cloud Accounts (T1078.004) | Tuvastame | Auditilogi ja riskantsete sisselogimiste hoiatus jõuavad IT-juhini |
| MFA Request Generation (T1621) | Osaliselt | Märguanne ilma numbri kinnitamiseta on väsimusrünnakule haavatav |
| Email Forwarding Rule (T1114.003) | Ei tuvasta | Hoiatust ei ole, kuigi auditilogi sündmuse salvestab |
| Device Registration (T1098.005) | Osaliselt | Auditilogis on sündmus, kuid hoiatust ei ole |
| PowerShell (T1059.001) | Tuvastame | EDR katab sülearvutid |
| Unsecured Credentials in code (T1552) | Tuvastame | Koodihoidla saladuste skannimine on sees |
| Data from Cloud Storage (T1530) | Ei tuvasta | DLP-d ja SaaS-teenuste kasutuse jälgimist ei ole |
| Data Encrypted for Impact (T1486) | Osaliselt | Andmed on pilves ja EDR katab sülearvutid, kuid SaaS-i andmete taastamise plaani ei kirjeldata |

**Kolm suurimat lünka:** (1) postkastireeglite ja autentimisseadmete registreerimise hoiatused, (2) numbri kinnitamine või õngitsuskindel MFA ja nõue vastavatele seadmetele, (3) SaaS-i jälgimine ja DLP.

#### Organisatsioon 5: Kaldavälja Vallavalitsus

| Tehnika | Hinnang | Põhjendus |
| --- | --- | --- |
| Exploit Public-Facing Application (T1190) | Ei tuvasta | Portaali logid on kolmanda osapoole käes ja skaneerimist ei tehta |
| Valid Accounts (T1078) | Ei tuvasta | Jagatud VPN-konto takistab tegevuse seostamist isikuga |
| Phishing (T1566) | Osaliselt | Vaikimisi filtrid ja kord aastas koolitus, kuid MFA on ainult juhtkonnal |
| Exploitation of Remote Services (T1210) | Ei tuvasta | Tugi lõppenud server samas võrgus töökohtadega ja haavatavusi ei skaneerita |
| RDP (T1021.001) | Ei tuvasta | Logid jäävad kohapeale ja RDP on töökohtadest serverisse lubatud |
| LSASS Memory (T1003.001) | Ei tuvasta | EDR puudub |
| Brute Force (T1110) | Osaliselt | Microsoft 365 MFA katab ainult juhtkonda |
| Impair Defenses (T1562) | Ei tuvasta | Allkirjapõhisel tõrjel puudub keskne vaade |
| Data Encrypted for Impact (T1486) | Osaliselt | Nädalased lindivarukoopiad väljaspool asukohta aitavad taastada, kuid taastamisaeg on pikk |
| Exfiltration (T1041) | Osaliselt | Tulemüüri logid jõuavad IT-partnerini, kuid hoiatusi vallavalitsus ei saa |

**Kolm suurimat lünka:** (1) tugi lõppenud serveri eraldamine või asendamine, (2) isikupõhised kontod ja MFA IT-partneri ligipääsule, (3) logide kogumine ja hoiatused, mis jõuavad ka vallavalitsuseni.

**Soovitus õpetajale:** organisatsioon 5 sobib hästi E-ITS-iga sidumiseks: lase õppijatel iga lünga juurde leida vastav E-ITS-i meede.
