# Peamised küberohud: teooria, ründevektorid ja praktika

Nov 4, 2026 · @Valdo

## Sissejuhatus

See materjal tutvustab 12 levinumat küberohtu nii, et esimese kursuse IT-õpilane saab aru, **mis** oht on, **kuidas** rünne käib, **miks** see õnnestub ja **kuidas** end kaitsta. Iga ohu juures on lahtiseletatud terminid, päriselu näited, ründevektori joonis ja kaitsemeetmed. Materjali lõpus on praktilised harjutused ja iseseisev töö.

### Õpiväljundid

Pärast materjali läbimist õpilane:

1. eristab mõisteid oht, haavatavus ja risk ning seostab neid CIA-kolmikuga;
2. kirjeldab 12 levinud küberohu toimimist ja tunneb ära nende ründevektorid;
3. toob iga ohu kohta vähemalt ühe päriselu näite;
4. valib igale ohule sobivad tehnilised ja protsessilised kaitsemeetmed;
5. kaardistab reaalse rünnaku etapid Cyber Kill Chaini ja MITRE ATT&CK raamistiku abil.

### Soovituslik ajakava

| Osa | Sisu | Maht |
| --- | --- | --- |
| Teooria (auditoorne) | Põhimõisted, rünnaku etapid, ohud 1–12 | 6 tundi |
| Praktika (labor) | Harjutused P1–P6 | 6 tundi |
| Iseseisev töö | Juhtumianalüüs ja MITRE ATT&CK kaardistus | 6 tundi |

### Ohud selles materjalis

| Nr | Oht | Peamiselt rikub |
| --- | --- | --- |
| 1 | Andmepüük ja sotsiaalne manipuleerimine | Konfidentsiaalsust |
| 2 | Pahavara | Kõiki kolme |
| 3 | Lunavara | Käideldavust (ja konfidentsiaalsust) |
| 4 | Paroolirünnakud ja kontode ülevõtmine | Konfidentsiaalsust |
| 5 | Hajutatud teenusetõkestus (DDoS) | Käideldavust |
| 6 | Vahendajarünne (MitM) ja võlts-Wi-Fi | Konfidentsiaalsust, terviklust |
| 7 | Veebirakenduste ründed (SQL-süst, XSS) | Konfidentsiaalsust, terviklust |
| 8 | Nullpäeva ja paikamata haavatavused | Kõiki kolme |
| 9 | Valekonfiguratsioon ja avatud teenused | Kõiki kolme |
| 10 | Tarneahela ründed | Terviklust |
| 11 | Siseoht | Konfidentsiaalsust |
| 12 | Tehisintellekt ja süvavõltsingud | Konfidentsiaalsust, terviklust |

> **NB! Seadus ja eetika.** Kõik praktilised harjutused tehakse ainult kooli laborikeskkonnas või oma virtuaalmasinates. Võõrale süsteemile loata ligipääs, skaneerimine või rünnak on Eestis kuritegu (Karistusseadustik § 217 jt). Hea turvaspetsialist küsib alati enne luba.

## 1. Põhimõisted ja terminoloogia

Enne ohtude juurde minekut peab selge olema, mida me kaitseme ja millega. Need mõisted korduvad igas järgmises peatükis.

### 1.1 CIA-kolmik: mida me kaitseme

Infoturbe eesmärk on hoida informatsiooni kolme omadust. Iga rünnak rikub vähemalt ühte neist.

| Omadus | Tähendus | Näide rikkumisest |
| --- | --- | --- |
| **Konfidentsiaalsus** (*Confidentiality*) | Infot näevad ainult need, kellel on selleks õigus. | Ründaja varastab klientide isikuandmed. |
| **Terviklus** (*Integrity*) | Info on õige ja seda ei ole loata muudetud. | Ründaja muudab arve pangakonto numbrit. |
| **Käideldavus** (*Availability*) | Info ja teenus on vajadusel kättesaadavad. | Lunavara krüpteerib serveri, e-pood ei tööta. |

### 1.2 Oht, haavatavus ja risk

Need kolm mõistet aetakse sageli segi, kuid riskide hindamiseks tuleb neid eristada.

| Mõiste | Definitsioon | Asukoht |
| --- | --- | --- |
| **Oht** (*Threat*) | Potentsiaalne tegur või sündmus, mis võib süsteemi või vara kahjustada (ründaja, pahavara, tulekahju). | Väljaspool süsteemi |
| **Haavatavus** (*Vulnerability*) | Süsteemi, tarkvara, protsessi või disaini nõrkus, mida oht saab ära kasutada. | Süsteemi sees |
| **Risk** (*Risk*) | Ohu realiseerumise tõenäosus korrutatud selle mõjuga. | Hinnang |

```latex
Risk = T\tilde{o}en\ddot{a}osus\ (P) \times M\tilde{o}ju\ (I)
```

**Lihtne võrdlus:** maja uks on lukustamata (haavatavus), linnas liigub varas (oht). Risk on see, kui tõenäoline on, et varas just sinu ukse leiab, ja kui palju ta sealt viia saab.

**Näide IT-st:** serveril on RDP-port internetti avatud ja administraatoril nõrk parool (haavatavus). Ründajad skaneerivad internetti avatud RDP-portide leidmiseks (oht). Kui ründaja pääseb sisse ja paigaldab lunavara, kaob teenuse käideldavus (riski realiseerumine).

Riskiga saab teha nelja asja: **vähendada** (paigaldada uuendus, lisada MFA), **vältida** (lülitada teenus välja), **üle kanda** (kindlustus, teenusepakkuja) või **aktsepteerida** (teadlik otsus jätta nii).

### 1.3 Sõnastik

| Termin | Inglise keeles | Selgitus |
| --- | --- | --- |
| Ründaja | Threat actor | Isik või rühm, kes rünnakut teeb: kurjategija, häktivist, riiklik rühmitus, sisetöötaja. |
| Ründevektor | Attack vector | Tee või meetod, mille kaudu ründaja süsteemi pääseb (e-kiri, avatud port, USB-pulk, veebivorm). |
| Ründepind | Attack surface | Kõigi võimalike ründevektorite summa: kõik teenused, kasutajad, seadmed, mida saab rünnata. |
| Eksploit | Exploit | Kood või tehnika, mis kasutab ära konkreetset haavatavust. |
| Kasulik koormus | Payload | Osa, mis teeb pärast sissepääsu „päris töö": krüpteerib, varastab, avab tagaukse. |
| Pahavara | Malware | Pahatahtlik tarkvara (*malicious software*). |
| Turvapaik | Patch | Tarkvarauuendus, mis parandab haavatavuse. |
| CVE | Common Vulnerabilities and Exposures | Avalikult teadaoleva haavatavuse unikaalne tunnus, nt CVE-2021-44228. |
| CVSS | Common Vulnerability Scoring System | Haavatavuse tõsiduse hinne skaalal 0–10. |
| Nullpäev | Zero-day | Haavatavus, mille kohta tootja veel ei tea või paika pole. |
| Botivõrk | Botnet | Ründaja kontrolli all olevate nakatunud seadmete võrk. |
| Juhtimisserver | Command & Control (C2) | Server, mille kaudu ründaja nakatunud seadmeid juhib. |
| Õiguste tõstmine | Privilege escalation | Tavakasutaja õigustest administraatori õigusteni jõudmine. |
| Külgliikumine | Lateral movement | Ühest kompromiteeritud masinast teistesse sama võrgu masinatesse liikumine. |
| Püsivus | Persistence | Võte, millega ründaja tagab, et tema ligipääs säilib ka pärast taaskäivitust. |
| Andmete väljaviimine | Exfiltration | Varastatud andmete viimine organisatsioonist välja. |
| Mitmikautentimine | MFA | Sisselogimine kahe või enama tõendiga (parool + telefon/turvavõti). |
| Vähimate õiguste põhimõte | Least privilege | Igale kasutajale ja teenusele antakse ainult tööks vajalikud õigused. |
| Kihiline kaitse | Defense in depth | Mitu kaitsekihti üksteise järel, et ühe kihi läbimine ei annaks kõike. |
| Kompromiteerimise tunnus | Indicator of Compromise (IoC) | Märk, mis viitab rünnakule: kahtlane IP, failiräsi, domeen. |
| Sotsiaalne manipuleerimine | Social engineering | Inimese mõjutamine nii, et ta teeks ründaja kasuks midagi ebaturvalist. |
| Sinine / punane meeskond | Blue / Red Team | Kaitsjad / kontrollitud ründajad, kes testivad kaitset. |

## 2. Kuidas rünne üldiselt toimub

Enamik sihitud rünnakuid läbib sarnased etapid. Lockheed Martin kirjeldas need 2011. aastal mudelina **Cyber Kill Chain** (rünnaku ahel). Kaitsja jaoks on mudeli põhimõte lihtne: **mida varasemas etapis ahel katkestada, seda väiksem on kahju.**

&#91;embedded content: Cyber Kill Chain · 7 etappi\]

| Etapp | Mida ründaja teeb | Näide | Kuidas katkestada |
| --- | --- | --- | --- |
| 1. Luure (*Reconnaissance*) | Kogub infot sihtmärgi kohta. | Otsib LinkedInist töötajate nimesid, skaneerib avatud porte. | Avaliku info piiramine, skaneerimiste jälgimine. |
| 2. Relvastamine (*Weaponization*) | Valmistab ründevahendi. | Paneb makroga Wordi faili pahavara. | (Toimub ründaja juures.) |
| 3. Kohaletoimetamine (*Delivery*) | Toimetab ründevahendi ohvrini. | Saadab andmepüügikirja manusega. | E-posti filter, kasutajate koolitus. |
| 4. Ärakasutamine (*Exploitation*) | Käivitab koodi, kasutab haavatavust. | Kasutaja lubab makrod, kood käivitub. | Paigaldatud uuendused, makrode keelamine. |
| 5. Paigaldamine (*Installation*) | Loob püsiva ligipääsu. | Paigaldab tagaukse ja ajastatud ülesande. | EDR-tarkvara, rakenduste lubatud nimekiri. |
| 6. Juhtimine (*Command & Control*) | Loob ühenduse juhtimisserveriga. | Nakatunud arvuti küsib HTTPS-i kaudu käske. | Tulemüüri väljuva liikluse filter, DNS-filter. |
| 7. Eesmärgid (*Actions on Objectives*) | Teeb seda, milleks tuli. | Varastab andmeid, krüpteerib faile. | Segmenteerimine, varukoopiad, DLP. |

Täpsem ja tänapäeval levinum raamistik on **MITRE ATT&CK**, mis jaotab ründaja tegevuse 14 taktikaks (*miks*) ja sadadeks tehnikateks (*kuidas*). Seda kasutatakse iseseisvas töös.

## Oht 1: Andmepüük ja sotsiaalne manipuleerimine

**Andmepüük (*phishing*) on rünnak inimese vastu, mitte arvuti vastu.** Ründaja esineb usaldusväärse osapoolena (pank, IT-tugi, kuller, ülemus), et ohver annaks ise ära parooli, kaardiandmed või käivitaks pahavara. See on kõige levinum esmane sissepääsutee: suur osa lunavara- ja andmelekkejuhtumeid algab just andmepüügikirjast.

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Phishing** | Masspüük: sama võltsitud kiri saadetakse tuhandetele. |
| **Spear phishing** (sihitud andmepüük) | Kiri on koostatud konkreetsele inimesele, kasutades tema nime, ametit, kolleegide nimesid. |
| **Whaling** | Sihitud andmepüük juhtide („suurte kalade") vastu. |
| **Smishing** | Andmepüük SMS-i teel (nt „Teie pakk on kinni, makske 1,99 €"). |
| **Vishing** | Andmepüük telefonikõne teel (*voice phishing*). |
| **BEC** (*Business Email Compromise*) | Ärikirja pettus: ründaja esineb juhi või partnerina ja palub teha makse. |
| **Pretexting** | Usutava loo („ettekäände") loomine, nt „Helistan IT-osakonnast, teie konto on lukus". |
| **Typosquatting** | Sarnase domeeni registreerimine: *swedbank-ee.com*, *rnicrosoft.com* (rn näeb välja nagu m). |

### Kuidas rünne käib

&#91;embedded content: Andmepüügi ründevektor · 6 sammu\]

1. **Luure.** Ründaja kogub töötajate nimed ja e-posti aadressid kodulehelt, LinkedInist või varasematest andmeleketest.
2. **Kirja koostamine.** Kiri jäljendab tuntud brändi kujundust ja tekitab emotsiooni: hirm („konto suletakse"), kiirustamine („24 tunni jooksul"), uudishimu („palgatõusu tabel").
3. **Klõps.** Ohver avab lingi või manuse. Link viib võltslehele, mis näeb välja nagu päris sisselogimisleht.
4. **Andmete sisestamine.** Ohver sisestab kasutajanime ja parooli (sageli ka MFA koodi, mida ründaja reaalajas edasi kasutab).
5. **Ligipääs.** Ründaja logib ohvri nimel sisse, loeb e-kirju, loob edasisuunamise reegli või saadab uusi andmepüügikirju juba „päris" aadressilt.
6. **Mõju.** Andmeleke, rahaline kahju (BEC), lunavara paigaldamine.

### Päriselu näited

- **Twitter, juuli 2020.** Ründajad helistasid väikesele arvule töötajatele (telefoni teel sihitud andmepüük) ja said ligipääsu sisetööriistadele. Rünnaku sihtmärgiks oli 130 kontot, sh Barack Obama, Bill Gatesi ja Elon Muski omad, millelt postitati Bitcoini-pettus. ([Dark Reading](https://www.darkreading.com/cyberattacks-data-breaches/3-arrested-for-massive-twitter-breach), [Al Jazeera](https://www.aljazeera.com/amp/news/2020/7/31/three-men-charged-in-connection-with-celebrity-twitter-hack))
- **FACC AG, 2015/2016.** Austria lennukiosade tootja töötaja sai „tegevjuhilt" e-kirja salajase ettevõtteostu kohta ja kandis üle umbes 50 miljonit eurot (BEC / *fake president*). Ettevõte kandis kahjumisse 41,9 miljonit eurot ja vallandas tegevjuhi. ([SecurityWeek](https://www.securityweek.com/austrian-firm-fires-ceo-after-56-million-cyber-scam), [Der Standard](https://derstandard.at/2000037606475/Chef-des-Luftfahrtzulieferers-FACC-muss-gehen))
- **Eesti igapäev.** Levinud on võltsitud kullerfirma SMS-id ja „Smart-ID kinnitamise" kõned, kus petis palub kinnitada PIN-koodiga toimingu, mille ta ise algatas.

### Kuidas ära tunda

- Saatja aadress ei klapi nimega (*IT-tugi <support@it-desk-help.xyz>*).
- Kiri tekitab ajasurvet või hirmu.
- Lingi peale hiirt hoides (*hover*) on näha teine domeen.
- Palutakse parooli, PIN-koodi või makset, mida tavaliselt nii ei küsita.
- Ootamatu manus (.zip, .html, .docm, .iso).

### Kaitse

| Tüüp | Meede |
| --- | --- |
| Tehniline | E-posti filtreerimine ja liivakast manustele; SPF, DKIM ja DMARC domeeni kaitseks. |
| Tehniline | Andmepüügikindel MFA (FIDO2-turvavõti, pääsuvõti), mis ei tööta võltslehel. |
| Tehniline | Välise saatja hoiatus e-kirja päises. |
| Protsessiline | Maksete kinnitamine teise kanali kaudu (helista teadaolevale numbrile). |
| Inimene | Regulaarne koolitus ja andmepüügi simulatsioonid; lihtne viis kahtlasest kirjast teatada. |

## Oht 2: Pahavara

**Pahavara (*malware*, *malicious software*) on tarkvara, mis on loodud süsteemi kahjustama, andmeid varastama või ründajale kontrolli andma.** Pahavara on rünnaku „tööriist": andmepüük või haavatavus on sageli see, mis pahavara arvutisse toob.

### Pahavara liigid

| Liik | Kuidas levib / töötab | Mida teeb |
| --- | --- | --- |
| **Viirus** (*virus*) | Kinnitub teise faili või programmi külge, levib selle käivitamisel. | Rikub faile, levib edasi. |
| **Uss** (*worm*) | Levib ise võrgu kaudu, kasutades haavatavust. Inimese abi pole vaja. | Levib väga kiiresti kogu võrku. |
| **Troojalane** (*trojan*) | Maskeerub kasulikuks programmiks (piraattarkvara, „PDF-lugeja"). | Avab tagaukse, laadib alla muud pahavara. |
| **Nuhkvara** (*spyware*) | Töötab varjatult. | Jälgib kasutajat, kogub andmeid. |
| **Klahvinuhk** (*keylogger*) | Salvestab klahvivajutusi. | Varastab paroole ja kaardiandmeid. |
| **Infovaras** (*infostealer*) | Otsib brauserist salvestatud paroole ja küpsiseid. | Ründaja saab sessiooniküpsisega MFA-st mööda. |
| **Juurkomplekt** (*rootkit*) | Peidab end operatsioonisüsteemi sügavusse. | Varjab teisi pahavarasid, raske avastada. |
| **Reklaamvara** (*adware*) | Tuleb kaasa tasuta programmidega. | Näitab reklaame, suunab ümber. |
| **Botiklient** (*bot*) | Ühendab seadme botivõrku. | Saadab rämpsposti, osaleb DDoS-is. |
| **Lunavara** (*ransomware*) | Vt Oht 3. | Krüpteerib failid, nõuab lunaraha. |

### Kuidas rünne käib

&#91;embedded content: Pahavara nakatumise tee · 6 sammu\]

1. **Kohaletoimetamine.** Pahavara jõuab arvutisse e-kirja manuse, võltsitud allalaadimise, nakatunud USB-pulga või haavatava teenuse kaudu.
2. **Käivitamine.** Kasutaja avab faili (nt lubab Wordis makrod) või uss kasutab haavatavust ise.
3. **Allalaadija.** Esimene väike programm (*dropper/loader*) laadib internetist alla päris pahavara.
4. **Püsivus.** Pahavara lisab end automaatkäivitusse (registri Run-võti, ajastatud ülesanne, teenus).
5. **Juhtimine.** Nakatunud arvuti võtab ühendust juhtimisserveriga (C2) ja ootab käske.
6. **Tegevus.** Andmete vargus, levik teistesse masinatesse, lunavara käivitamine.

### Päriselu näited

- **WannaCry, mai 2017** (uss + lunavara). Kasutas Windowsi SMBv1 haavatavust EternalBlue (MS17-010), mille paiga Microsoft oli välja andnud juba märtsis. Nakatas sadu tuhandeid arvuteid enam kui 150 riigis, sh Ühendkuningriigi haiglates. ([SecurityWeek](https://www.securityweek.com/one-year-after-wannacry-outbreak-eternalblue-exploit-still-threat/amp/), [MS-ISAC](https://cisecurity.org/ms-isac/cyber-alert-wannacry-ransomware), [Security Magazine / CISA](https://www.securitymagazine.com/articles/92173-cisa-issues-alert-on-north-korean-cybersecurity-threat))
- **Emotet, kuni 2021** (troojalane/allalaadija). Levis automaatsete andmepüügikirjade manustega ja müüs ligipääsu teistele kurjategijatele, kes paigaldasid lunavara. Europol ja Eurojust koordineerisid 27. jaanuaril 2021 operatsiooni, mille käigus võeti selle taristu üle. ([Eurojust](https://www.eurojust.europa.eu/worlds-most-dangerous-malware-emotet-disrupted-through-global-action), [BankInfoSecurity](https://www.bankinfosecurity.net/law-enforcement-operation-disrupts-notorious-emotet-botnet-a-15866))
- **Infovarastajad tänapäeval.** Piraatmängude ja „tasuta" tarkvara alla peidetud infovarastajad koguvad paroole ja sessiooniküpsiseid, mida müüakse edasi. Just nii jõudis ründajateni ka Uberi rünnakus kasutatud parool (vt Oht 4).

### Kuidas ära tunda

- Arvuti on järsku aeglane, ventilaator töötab pidevalt.
- Tundmatud protsessid tegumihalduris, uued automaatkäivituse kirjed.
- Viirusetõrje on keelatud ja ei lase end sisse lülitada.
- Kontodelt saadetakse sõnumeid, mida sa ei saatnud.

### Kaitse

- Ajakohane viirusetõrje / EDR (Windowsis Microsoft Defender).
- Operatsioonisüsteemi ja tarkvara uuendused (WannaCry oleks peatunud õigeaegse paigaga).
- Tavakasutaja konto igapäevatööks, administraatori õigused ainult vajadusel.
- Makrode blokeerimine internetist tulnud failides.
- Tarkvara ainult ametlikest allikatest; rakenduste lubatud nimekiri (AppLocker, WDAC).
- Võrgu segmenteerimine, et uss ei leviks kogu võrku.

## Oht 3: Lunavara

**Lunavara (*ransomware*) krüpteerib ohvri failid ja nõuab nende avamise eest lunaraha, tavaliselt krüptorahas.** See on praegu organisatsioonidele kõige kahjulikum küberoht, sest see peatab kogu töö: arveldus, tootmine, haigla infosüsteemid.

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Krüpteerimine** | Andmete muutmine loetamatuks; avamiseks on vaja võtit, mis on ainult ründajal. |
| **Topeltväljapressimine** (*double extortion*) | Ründaja enne krüpteerimist varastab andmed ja ähvardab need avaldada, kui ei maksta. |
| **RaaS** (*Ransomware-as-a-Service*) | Lunavara „rendimudel": arendajad annavad tööriista partneritele ja saavad osa lunarahast. |
| **Esmase ligipääsu vahendaja** (*Initial Access Broker*) | Kurjategija, kes murrab võrku sisse ja müüb ligipääsu lunavaragrupile. |
| **Lekkesait** | Ründajate veebileht, kus avaldatakse maksmata jätnud ohvrite andmeid. |

### Kuidas rünne käib

&#91;embedded content: Lunavara rünnaku käik · 6 etappi\]

1. **Esmane ligipääs.** Andmepüük, varastatud VPN- või RDP-parool, paikamata VPN-seade.
2. **Luure võrgus.** Ründaja kaardistab võrgu, otsib domeenikontrollerit ja varundusservereid.
3. **Õiguste tõstmine.** Ründaja hangib domeeniadministraatori õigused (nt paroolid mälust tööriistaga Mimikatz).
4. **Külgliikumine ja varukoopiate hävitamine.** Kustutatakse varjukoopiad (*Volume Shadow Copies*), rünnatakse varundusserverit.
5. **Andmete väljaviimine.** Suured andmehulgad laaditakse pilve (topeltväljapressimine).
6. **Krüpteerimine.** Lunavara käivitatakse korraga kõigis masinates, sageli öösel või nädalavahetusel. Ekraanile ilmub lunarahanõue.

Oluline tähelepanek: ründaja võib olla võrgus **päevi või nädalaid** enne krüpteerimist. Kui ta selles faasis avastada, saab kahju ära hoida.

### Päriselu näited

- **Colonial Pipeline, mai 2021.** USA suurima kütusetorustiku operaator. DarkSide'iga seotud ründajad pääsesid võrku kasutusest väljas, kuid sisse lülitatud VPN-kontoga, millel oli ainult parool ja MFA puudus. Torustik suleti ja ettevõte maksis umbes 4,4 miljonit dollarit lunaraha; USA justiitsministeerium sai sellest hiljem tagasi umbes 2,3 miljonit. ([Oil & Gas Journal](https://www.ogj.com/general-interest/article/14205879/senators-query-colonial-pipeline-ceo-about-cyberattack-shutdown), [Bloomberg Law](https://news.bloomberglaw.com/privacy-and-data-security/ceo-of-hacked-pipeline-company-tells-senate-hes-deeply-sorry))
- **Kaseya VSA, juuli 2021.** REvili partnerid kasutasid IT-haldustarkvara Kaseya VSA haavatavust ja levitasid lunavara haldusteenuse pakkujate kaudu. Kaseya hinnangul mõjutas see 800–1500 ettevõtet; ründajad nõudsid 70 miljonit dollarit (seos tarneahela ohuga, vt Oht 10). ([The Record](https://therecord.media/kaseya-more-than-1500-downstream-businesses-impacted-by-ransomware-attack), [TechTarget](https://www.techtarget.com/cybersecurity/news/252503605/Kaseya-1500-organizations-affected-by-REvil-attacks))

### Kuidas ära tunda

- Failide laiendid muutuvad (*.docx.locked*), failid ei avane.
- Igas kaustas on uus fail nagu *README\_RESTORE.txt*.
- Enne krüpteerimist: tundmatud administraatorkontod, öised sisselogimised, suur väljuv andmeliiklus.

### Kaitse

- **Varukoopiad reegliga 3-2-1:** 3 koopiat, 2 erineval andmekandjal, 1 väljaspool võrku (*offline*) või muutmatuna (*immutable*). Taastamist tuleb regulaarselt proovida.
- MFA kõigile kaugligipääsudele (VPN, RDP, e-post).
- Kiire paikamine, eriti internetis nähtavatel seadmetel.
- Võrgu segmenteerimine ja administraatorikontode eraldamine.
- EDR ja logide jälgimine (SIEM), et ründaja enne krüpteerimist avastada.
- Intsidendi lahendamise plaan: kes mida teeb, kellele teatada (Eestis CERT-EE / RIA).

Lunaraha maksmist ei soovitata: see ei taga andmete tagasisaamist, rahastab kuritegevust ja teeb ohvrist korduva sihtmärgi.

## Oht 4: Paroolirünnakud ja kontode ülevõtmine

**Paroolirünnakus püüab ründaja ära arvata või ära kasutada kasutaja parooli, et logida sisse tema nimel.** Ründaja ei pea siis midagi „häkkima": ta lihtsalt logib sisse nagu tavaline kasutaja, mistõttu on rünnakut raske märgata.

### Rünnaku liigid

| Liik | Kuidas töötab | Näide |
| --- | --- | --- |
| **Toore jõu rünne** (*brute force*) | Proovitakse kõiki võimalikke kombinatsioone. | aaaa, aaab, aaac … |
| **Sõnastikurünne** (*dictionary attack*) | Proovitakse levinud paroole ja sõnu loendist. | Parool123, Suvi2026! |
| **Paroolipihustus** (*password spraying*) | Üks-kaks levinud parooli proovitakse paljude kontode peal, et vältida konto lukustumist. | „Talv2026!" 500 töötaja kontol. |
| **Mandaaditäide** (*credential stuffing*) | Ühest lekkest pärit kasutajanime-parooli paare proovitakse teistes teenustes. | Foorumi lekke parool töötab ka e-postis. |
| **Räsi murdmine** (*hash cracking*) | Varastatud paroolide räsid murtakse võrguühenduseta, võimsa graafikakaardiga. | Tööriistad hashcat, John the Ripper. |
| **MFA väsitamine** (*MFA fatigue*) | Ründajal on parool; ta saadab kümneid MFA-kinnituspäringuid, kuni kasutaja tüdineb ja vajutab „Kinnita". | Uber, 2022. |

**Räsi** (*hash*) on ühesuunaline „sõrmejälg": süsteem ei salvesta parooli ennast, vaid selle räsi. Kui sisestad parooli, arvutatakse räsi ja võrreldakse. Nõrga algoritmiga (MD5) ja ilma soolata (*salt*) salvestatud räsisid on lihtne murda.

### Kuidas rünne käib

&#91;embedded content: Mandaaditäite ründevektor · 6 sammu\]

1. Mõnes teenuses (foorum, e-pood) toimub andmeleke; kasutajanimed ja paroolid jõuavad müüki.
2. Ründaja ostab või laeb alla miljoneid kasutajanime-parooli paare (*combolist*).
3. Automaattööriist proovib neid paare teistes teenustes (pank, e-post, pilv), kasutades botivõrku, et IP-aadressid vahetuksid.
4. Kasutajad, kes kasutavad sama parooli mitmes kohas, kaotavad konto.
5. Ründaja vahetab parooli, lisab oma MFA-seadme või kasutab kontot edasisteks rünnakuteks.

### Päriselu näited

- **23andMe, 2023.** Ründajad kasutasid teistest leketest pärit kasutajanime-parooli paare (mandaaditäide) umbes 14 000 konto ülevõtmiseks. Sugulaste otsingu funktsiooni kaudu said nad ligi umbes 6,9 miljoni kasutaja andmetele. Pärast intsidenti muutis ettevõte kaheastmelise autentimise kohustuslikuks. ([HIPAA Journal](https://www.hipaajournal.com/6-9-million-23andme-users-affected-by-data-breach/), [Fortune](https://fortune.com/2023/12/04/23andme-customer-health-data-hacking/))
- **Uber, september 2022.** Ründaja ostis tõenäoliselt pimeveebist töövõtja parooli, mis oli varastatud pahavaraga. Seejärel saatis ta töövõtjale korduvalt MFA-päringuid ja kirjutas WhatsAppis IT-toena, et teavitused lõpevad, kui ühe kinnitad. Töövõtja kinnitas ja ründaja pääses sisevõrku (MFA väsitamine). ([InfoQ](https://www.infoq.com/news/2022/09/Uber-breach-mfa-fatigue/), [KPMG](https://assets.kpmg.com/content/dam/kpmg/in/pdf/2022/09/27-september-2022-lessons-to-learn-from-the-uber-security-breach.pdf))
- **Colonial Pipeline, 2021.** Kasutatud VPN-konto parool leiti hiljem pimeveebi paroolilekete hulgast, mis viitab parooli korduvkasutusele; MFA puudus (vt Oht 3). ([Governing / Bloomberg](https://www.governing.com/security/bad-password-gave-colonial-pipeline-hackers-access?_amp=true))

### Kaitse

- **Pikk ja unikaalne parool igale teenusele** (nt 4–5 juhuslikku sõna: *kohver-sinine-jõgi-laud*). Pikkus on olulisem kui erimärgid.
- **Paroolihaldur** (Bitwarden, KeePassXC), et ei peaks paroole meeles pidama.
- **MFA** kõikjal; parim on andmepüügikindel (FIDO2, pääsuvõti). MFA-päringule, mida sa ise ei algatanud, vajuta alati „Keeldu".
- Kontrolli lekkeid lehel [haveibeenpwned.com](https://haveibeenpwned.com).
- Administraatorile: konto lukustamine pärast valekatseid, sisselogimiste jälgimine, paroolide salvestamine tugeva algoritmiga (bcrypt, Argon2) koos soolaga.

## Oht 5: Hajutatud teenusetõkestus (DDoS)

**Teenusetõkestusrünne (*DoS*, *Denial of Service*) püüab teenuse kasutajatele kättesaamatuks teha, koormates seda üle. Hajutatud rünnaku (*DDoS*, *Distributed DoS*) puhul tuleb liiklus korraga tuhandetest seadmetest.** Andmeid tavaliselt ei varastata; rikutakse käideldavust.

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Botivõrk** (*botnet*) | Tuhanded nakatunud arvutid, ruuterid, kaamerad, mida ründaja juhib C2-serveri kaudu. |
| **Mahupõhine rünne** (*volumetric*) | Ummistab võrguühenduse ribalaiuse (mõõdetakse Gbit/s või Tbit/s). |
| **Protokollirünne** | Kurnab serveri või tulemüüri ressursse, nt SYN flood: palju poolikuid TCP-ühendusi. |
| **Rakenduskihi rünne** (*Layer 7*) | Palju „päris" HTTP-päringuid kulukale lehele (otsing, sisselogimine). Mõõdetakse päringutes sekundis. |
| **Võimendusrünne** (*amplification*) | Ründaja saadab väikese päringu võltsitud saatja-IP-ga (ohvri IP) avatud DNS- või NTP-serverile, mis saadab ohvrile kümneid kordi suurema vastuse. |
| **Puhastuskeskus** (*scrubbing center*) | Teenusepakkuja, kes filtreerib ründeliikluse enne, kui see sinu serverini jõuab. |

### Kuidas rünne käib

&#91;embedded content: DDoS botivõrgu kaudu · 6 sammu\]

1. Ründaja nakatab või rendib botivõrgu (sageli halvasti kaitstud IoT-seadmed vaikeparoolidega).
2. Ründaja annab C2-serveri kaudu käsu: sihtmärk, rünnaku tüüp, kestus.
3. Kõik botid saadavad korraga päringuid sihtmärgi pihta.
4. Ühendus, tulemüür või server ei jõua päringuid töödelda.
5. Tavakasutajad ei pääse teenusele ligi; ettevõte kaotab tulu ja usaldust.

### Päriselu näited

- **Eesti, aprill–mai 2007.** Pronkssõduri teisaldamise järel kestsid küberrünnakud 22 päeva. Ummistusrünnete sihtmärgiks olid riigiasutuste, pankade, meedia ja erakondade veebilehed. ([ERR](https://www.err.ee/1608688192/narva-tanki-teisaldamine-toi-kaasa-suurima-kuberrunnete-laine-parast-pronksiood), [ERR News](https://news.err.ee/1608688201/estonia-subjected-to-extensive-cyber-attacks-following-tank-removal))
- **Eesti, august 2022.** Pärast Narva tanki teisaldamist ründas venemeelne rühmitus Killnet Eesti riigi- ja erasektori e-teenuseid. Riigi IT-juhi Luukas Kristjan Ilvese sõnul olid need kõige laiaulatuslikumad rünnakud alates 2007. aastast, kuid ründed ebaõnnestusid ja teenused püsisid töös. ([ERR](https://www.err.ee/1608688192/narva-tanki-teisaldamine-toi-kaasa-suurima-kuberrunnete-laine-parast-pronksiood), [Infosecurity Magazine](https://www.infosecurity-magazine.com/news-features/estonias-battle-against-a-deluge))
- **Dyn / Mirai, oktoober 2016.** Mirai botivõrk koosnes häkitud kaameratest, salvestitest ja ruuteritest. Rünnak DNS-teenusepakkuja Dyni vastu tegi mitmeks tunniks paljudele kasutajatele kättesaamatuks Twitteri, Netflixi, Redditi, Spotify jt. ([KrebsOnSecurity](https://krebsonsecurity.com/page/117/?y=0), [The Open University](https://www.open.edu/openlearn/digital-computing/learning-major-cyber-security-incidents/content-section-4.1))
- **Google, 2023.** HTTP/2 „Rapid Reset" haavatavus (CVE-2023-44487) võimaldas tekitada üle 398 miljoni päringu sekundis; varasem rekord oli 46 miljonit. ([Google Cloud](https://cloud.google.com/blog/products/identity-security/google-cloud-mitigated-largest-ddos-attack-peaking-above-398-million-rps/))

### Kaitse

- DDoS-kaitseteenus või sisuedastusvõrk (Cloudflare, Akamai, internetiteenuse pakkuja kaitse).
- Päringute piiramine (*rate limiting*) ja veebirakenduse tulemüür (WAF).
- Avatud DNS-, NTP- ja muude teenuste sulgemine, et sinu server ei oleks võimendaja.
- IoT-seadmetel vaikeparoolide vahetamine (et sinu seade ei oleks boti osa).
- Tegevusplaan: kellega ühendust võtta, kuidas liiklus ümber suunata.

## Oht 6: Vahendajarünne (MitM) ja võlts-Wi-Fi

**Vahendajarünnakus (*Man-in-the-Middle*, MitM, ka *Adversary-in-the-Middle*) asetab ründaja end kahe osapoole vahele: kasutaja arvab, et suhtleb otse teenusega, kuid kogu liiklus käib läbi ründaja.** Ründaja saab liiklust pealt kuulata (rikub konfidentsiaalsust) või muuta (rikub terviklust).

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Kurja kaksiku pääsupunkt** (*evil twin*) | Võltsitud Wi-Fi võrk, millel on sama nimi mis päris võrgul (nt „Kohvik\_WiFi"). |
| **ARP-võltsimine** (*ARP spoofing*) | Kohtvõrgus väidab ründaja arvuti, et tema MAC-aadress kuulub ruuterile; liiklus suunatakse temale. |
| **DNS-võltsimine** (*DNS spoofing*) | Ohvrile antakse domeeninimele vale IP-aadress, mis viib ründaja serverisse. |
| **SSL stripping** | Ründaja „alandab" HTTPS-ühenduse HTTP-ks, et liiklus oleks krüpteerimata. |
| **Sertifikaat** | Digitaalne dokument, mis tõendab, et veebileht kuulub sellele, kellele ta väidab. Brauser hoiatab, kui sertifikaat ei sobi. |
| **HSTS** | Veebilehe seadistus, mis sunnib brauserit alati HTTPS-i kasutama. |
| **Sessiooniküpsis** | Brauseris olev märk, mis hoiab sind sisselogituna. Selle varastamine annab ligipääsu ilma paroolita. |

### Kuidas rünne käib

&#91;embedded content: Vahendajarünne võlts-Wi-Fi kaudu · 6 sammu\]

1. Ründaja istub kohvikus või lennujaamas ja käivitab sülearvutis või väikeseadmes Wi-Fi pääsupunkti nimega „Lennujaam\_Free\_WiFi".
2. Ohvri telefon või arvuti ühendub, sest võrk on avatud ja signaal tugev.
3. Kogu ohvri liiklus läbib ründaja seadet.
4. Krüpteerimata liiklusest (HTTP, vanad protokollid) näeb ründaja paroole ja sisu; võltsitud DNS-iga suunab ta ohvri võltsitud sisselogimislehele.
5. Ründaja kasutab paroole või sessiooniküpsiseid ohvri kontodele sisse logimiseks.

Tänapäevased andmepüügikomplektid (nt Evilginx) teevad sama veebis: võltsleht on „vahendaja" ohvri ja päris teenuse vahel ning varastab ka MFA järel tekkiva sessiooniküpsise.

### Päriselu näited

- **DigiNotar, 2011.** Ründaja murdis Hollandi sertifitseerimiskeskusesse ja väljastas sadu võltsitud sertifikaate, sh domeenile *google.com*. Uurimisfirma Fox-IT tuvastas umbes 300 000 IP-aadressi, mis kasutasid võltssertifikaati; 99% neist asusid Iraanis. DigiNotar kuulutati samal aastal pankrotti. ([Computerworld](https://www.computerworld.com/article/2510951/hackers-spied-on-300-000-iranians-using-fake-google-certificate.html), [IEEE Spectrum](https://spectrum.ieee.org/diginotar-certificate-authority-breach-crashes-egovernment-in-the-netherlands))
- **DarkHotel, avalikustati 2014.** Kaspersky kirjeldas rühmitust, mis sihtis luksushotellides peatuvaid ärijuhte. Kui ohver logis hotelli Wi-Fisse, pakuti talle võltsitud „uuendust" (Adobe Flash, Google Toolbar), mis paigaldas tagaukse. ([Help Net Security](https://www.helpnetsecurity.com/?p=37938), [NBC News](https://www.nbcnews.com/tech/security/dark-hotel-hacks-target-business-travelers-report-n245266))

### Kaitse

- Kasuta ainult HTTPS-i; ära kunagi ignoreeri brauseri sertifikaadihoiatust.
- Avalikus Wi-Fis kasuta VPN-i või mobiilset andmesidet.
- Lülita välja automaatne ühendumine avatud võrkudega.
- Võrgu administraatorile: WPA3 või WPA2-Enterprise, switchidel *Dynamic ARP Inspection* ja *DHCP Snooping*, veebilehtedel HSTS.
- Andmepüügikindel MFA (FIDO2), mis seob sisselogimise õige domeeniga.

## Oht 7: Veebirakenduste ründed (SQL-süst ja XSS)

**Veebirakenduse ründes kasutab ründaja ära vigu rakenduse koodis: rakendus usaldab kasutaja sisendit ja käsitleb seda koodina.** Kõige tuntumad näited on SQL-süst ja saidiülene skriptimine. Veebirakenduste levinumad riskid koondab nimekiri **OWASP Top 10**.

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Sisend** (*input*) | Kõik, mida kasutaja saab rakendusse saata: vormiväljad, URL-i parameetrid, küpsised, päised. |
| **SQL-süst** (*SQL injection*, SQLi) | Ründaja lisab sisendisse SQL-koodi, mis muudab andmebaasi päringu tähendust. |
| **Saidiülene skriptimine** (*Cross-Site Scripting*, XSS) | Ründaja paneb lehele JavaScripti, mis käivitub teiste külastajate brauseris. |
| **Salvestatud XSS** (*stored*) | Pahatahtlik skript salvestatakse andmebaasi (nt kommentaari) ja näidatakse kõigile. |
| **Peegeldatud XSS** (*reflected*) | Skript on lingis; käivitub, kui ohver lingile klõpsab. |
| **Parametriseeritud päring** (*prepared statement*) | Päring, kus kood ja andmed on eraldi; peamine kaitse SQL-süsti vastu. |
| **Väljundi kodeerimine** (*output encoding*) | Erimärkide (`<`, `>`, `"`) muutmine ohutuks enne lehele kuvamist; peamine kaitse XSS-i vastu. |
| **WAF** (*Web Application Firewall*) | Tulemüür, mis filtreerib veebipäringutes tuntud ründemustreid. |

### Kuidas SQL-süst töötab

Oletame, et sisselogimislehe PHP-kood ehitab päringu nii:

```php
$sql = "SELECT * FROM kasutajad WHERE nimi = '" . $_POST['nimi'] . "' AND parool = '" . $_POST['parool'] . "'";
```

Kui ründaja kirjutab kasutajanime väljale ` ' OR '1'='1' --  `, muutub päring selliseks:

```sql
SELECT * FROM kasutajad WHERE nimi = '' OR '1'='1' -- ' AND parool = '...'
```

Tingimus `'1'='1'` on alati tõene ja `--` muudab ülejäänu kommentaariks. Andmebaas tagastab kõik kasutajad ning rakendus laseb ründaja sisse ilma paroolita. Sama võttega saab lugeda teisi tabeleid, näiteks kõigi klientide andmeid.

**Õige lahendus** on parametriseeritud päring, kus kasutaja sisend ei saa kunagi koodiks muutuda:

```php
$stmt = $pdo->prepare("SELECT * FROM kasutajad WHERE nimi = ?");
$stmt->execute([$_POST['nimi']]);
```

### Kuidas XSS töötab

Foorumis saab postitada kommentaare ja rakendus kuvab need muutmata kujul. Ründaja postitab kommentaari, milles on `<script>` silt. Iga külastaja brauser, kes kommentaari näeb, käivitab skripti, mis saadab tema sessiooniküpsise ründaja serverisse. Ründaja kasutab küpsist ja on ohvri nimel sisse logitud.

&#91;embedded content: SQL-süst ja XSS · kaks ründeteed\]

### Päriselu näited

- **TalkTalk, oktoober 2015.** Ühendkuningriigi telekomifirma veebilehe SQL-süsti kaudu varastati 156 959 kliendi andmed, sh 15 656 kliendi pangakonto andmed. Haavatav oli ülevõetud vana andmebaas, mille paik oli ammu olemas. Andmekaitseasutus ICO määras trahvi 400 000 naela; kahtlustatavana peeti kinni 15-aastane poiss. ([Silicon Republic](https://www.siliconrepublic.com/enterprise/talktalk-cyberattack-fine-ico), [Engadget](https://www.engadget.com/2016/10/05/talktalk-400-000-ico-hack-fine/))
- **Samy uss MySpace'is, oktoober 2005.** Salvestatud XSS kopeeris end iga profiili külastaja lehele ja lisas autori tema sõbraks. Vähem kui 20 tunniga oli koodi käivitanud üle miljoni kasutaja. ([Wikipedia](<https://en.wikipedia.org/wiki/Samy_(computer_worm)>), [The Register](https://www.theregister.com/2015/08/17/a_decade_on_and_one_mans_security_red_flag_is_lost_in_the_din/))
- **British Airways, 2018.** Magecarti rühmitus muutis ettevõtte veebilehe JavaScripti nii, et makseandmed suunati ründajate serverisse. Mõjutatud oli umbes 429 612 inimest, neist umbes 244 000 maksekaardi andmed; ICO määras trahvi 20 miljonit naela. ([CNBC](https://www.cnbc.com/2020/10/16/british-airways-fined-20-million-for-data-breach-by-ico.html), [OCCRP](https://www.occrp.org/en/news/british-airways-to-pay-record-fine-for-poor-data-protection))

### Kaitse

- **Ära kunagi usalda kasutaja sisendit:** kontrolli tüüpi, pikkust ja lubatud märke.
- SQL-päringud ainult parametriseeritult (PDO, ORM).
- Väljundi kodeerimine (PHP-s `htmlspecialchars()`), *Content Security Policy* päis.
- Küpsistele lipud `HttpOnly`, `Secure` ja `SameSite`.
- Andmebaasi kasutajal ainult vajalikud õigused (mitte *root*).
- Turvatestimine ja WAF; arendajate koolitus OWASP Top 10 põhjal.

## Oht 8: Nullpäeva ja paikamata haavatavused

**Nullpäeva haavatavus (*zero-day*) on turvaauk, mille kohta tarkvara tootja veel ei tea või mille jaoks paika pole: kaitsjal on olnud „null päeva" selle parandamiseks.** Palju sagedasem probleem on aga **paikamata haavatavus** (*n-day*): paik on olemas, kuid seda pole paigaldatud. Enamik edukaid rünnakuid kasutab juba ammu teada olevaid auke.

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Haavatavus** | Viga koodis või disainis, mis lubab teha midagi, mida ei peaks saama. |
| **Nullpäev** (*0-day*) | Haavatavus, mida kasutatakse enne, kui paik on olemas. |
| **N-päev** (*n-day*) | Teada haavatavus, mille paik on olemas, kuid mida pole igal pool paigaldatud. |
| **CVE** | Haavatavuse tunnus, nt CVE-2021-44228 (Log4Shell). Andmebaas: [nvd.nist.gov](https://nvd.nist.gov). |
| **CVSS** | Tõsiduse hinne 0–10; 9,0–10 on kriitiline. |
| **RCE** (*Remote Code Execution*) | Kaugkoodi käivitamine: ründaja saab üle võrgu serveris oma koodi käivitada. Kõige ohtlikum haavatavuse tüüp. |
| **Paikade haldus** (*patch management*) | Protsess uuenduste leidmiseks, testimiseks ja paigaldamiseks. |
| **CISA KEV** | USA küberturbeagentuuri nimekiri haavatavustest, mida päriselt rünnakutes kasutatakse. Hea prioriteetide allikas. |

### Kuidas rünne käib

&#91;embedded content: Haavatavuse elutsükkel · 6 etappi\]

1. **Avastamine.** Keegi leiab vea. Kui see on ründaja, jääb viga salajaseks (nullpäev).
2. **Ärakasutamine.** Ründaja kasutab viga sihitud rünnakutes, tootja ei tea veel midagi.
3. **Avalikustamine.** Viga avastatakse, tootja annab välja paiga ja CVE avaldatakse.
4. **Võidujooks.** Mõne päeva jooksul ilmuvad avalikud eksploidid; ründajad skaneerivad kogu internetti haavatavate serverite leidmiseks.
5. **Massrünnak.** Kõik, kes pole paika paigaldanud, on ohus. See periood võib kesta aastaid.

### Päriselu näited

- **Log4Shell, detsember 2021** (CVE-2021-44228). Java logimisteegi Log4j kaugkoodi käivitamise haavatavus, mille Apache hindas kriitiliseks. CISA ja partnerriikide hinnangul oli see eriti ohtlik, sest Javat kasutatakse väga laialt ja haavatavust oli lihtne ära kasutada. ([CISA](https://cisa.gov/news-events/alerts/2021/12/22/mitigating-log4shell-and-other-log4j-related-vulnerabilities), [CISA juhend](https://cisa.gov/uscert/apache-log4j-vulnerability-guidance))
- **Microsoft Exchange ProxyLogon, märts 2021.** Neli nullpäeva haavatavust võimaldasid ründajatel (Microsofti järgi HAFNIUM) pääseda Exchange'i serveritesse ja paigaldada veebikestad (*web shell*). Microsoft andis 2. märtsil välja erakorralised paigad, kuid paik ei eemaldanud juba paigaldatud veebikesti. ([Truesec](https://www.truesec.com/hub/blog/tracking-microsoft-exchange-zero-day-proxylogon-and-hafnium), [Sophos](https://sophos.com/hafnium))
- **Equifax, 2017.** Apache Struts'i haavatavuse paik ilmus 2017. aasta märtsis, kuid ei olnud ühes internetis nähtavas serveris paigaldatud. Ründajad tegutsesid võrgus maist juulini; lekkisid umbes 147 miljoni inimese andmed. ([GovInfoSecurity](https://www.govinfosecurity.com/equifax-agrees-to-425-million-breach-settlement-a-18484), [PortSwigger](https://portswigger.net/daily-swig/equifax-finalizes-data-breach-settlement-with-us-regulators))
- **Eesti ID-kaart (ROCA), 2017.** Tšehhi teadlased avastasid Infineoni kiibi turvavea, mis mõjutas Eestis pärast 16. oktoobrit 2014 välja antud umbes 750 000 kaarti. Teadaolevalt riski ei realiseerunud, kuid riik peatas 3. novembril umbes 760 000 kaardi sertifikaadid ja kasutajad uuendasid need kaugteel või PPA-s. ([ERR](https://www.err.ee/632424/ria-avalikustab-lahipaevil-id-kaardi-turvariski-korvaldamise-ajakava), [ERR](https://www.err.ee/693519/uuendamata-jai-lopuks-ligi-300-000-id-kaarti))
- **Pegasus / FORCEDENTRY, 2021.** iMessage'i nullpäeva haavatavus (CVE-2021-30860) võimaldas paigaldada NSO Groupi nuhkvara telefonidesse ilma kasutaja tegevuseta (*zero-click*). Citizen Labi andmetel oli see kasutusel vähemalt 2021. aasta veebruarist. ([Citizen Lab](https://citizenlab.ca/wp-content/uploads/2025/12/Report143-forcedentry.pdf), [BleepingComputer](https://www.bleepingcomputer.com/news/apple/new-zero-click-iphone-exploit-used-to-deploy-nso-spyware))

### Kaitse

- **Varade nimekiri:** sa ei saa paigata seda, millest sa ei tea (CMDB, tarkvara inventuur).
- Paikade haldus kindlate tähtaegadega; kriitilised ja internetis nähtavad süsteemid esimesena (nt 48 tunni jooksul).
- Automaatsed uuendused tööjaamades (WSUS, Intune).
- Haavatavuste skaneerimine (OpenVAS, Nessus).
- Nullpäeva vastu aitavad kihilised kaitsed: vähimad õigused, segmenteerimine, EDR, logide jälgimine.
- Toote elutsükli lõpp (*end-of-life*): tarkvara, millele uuendusi enam ei tule, tuleb välja vahetada.

## Oht 9: Valekonfiguratsioon ja avatud teenused

**Valekonfiguratsioon (*misconfiguration*) tähendab, et süsteem on ise korras, kuid seadistatud ebaturvaliselt: vaikeparoolid, liigsed õigused, internetti avatud haldusliidesed, avalikud pilvekaustad.** Ründajal pole vaja ühtegi haavatavust ära kasutada, sest uks on juba lahti. See on süsteemiadministraatori vastutusala kõige otsesemalt.

### Levinud vead

| Viga | Miks ohtlik |
| --- | --- |
| **Vaikeparoolid** (*admin/admin*) | Vaikeparoolide nimekirjad on avalikud; Mirai botivõrk kasutas ainult neid. |
| **Avatud RDP/SSH internetti** (port 3389/22) | Ründajad skaneerivad pidevalt kogu internetti ja proovivad paroole. |
| **Avatud andmebaas** (MySQL 3306, MongoDB 27017, Elasticsearch 9200) | Andmebaas ilma paroolita internetis = kõik andmed avalikud. |
| **Avalik pilvesalvestus** (S3 bucket, Azure Blob) | Faile saab lugeda igaüks, kes teab või arvab ära aadressi. |
| **Liigsed õigused** | Kõik kasutajad on kohalikud administraatorid; teenusekontol on domeeniadmini õigused. |
| **Vanad protokollid sees** | SMBv1, TLS 1.0, Telnet, krüpteerimata FTP. |
| **Logimine välja lülitatud** | Rünnakut ei saa avastada ega hiljem uurida. |
| **Vigade detailne kuvamine** | Veebileht näitab veateates andmebaasi struktuuri ja failiteid. |

**Shodan** ([shodan.io](https://www.shodan.io)) on otsingumootor, mis indekseerib internetti ühendatud seadmeid ja nende avatud porte. Ründajad kasutavad seda sihtmärkide leidmiseks, kaitsjad oma ründepinna kontrollimiseks.

### Kuidas rünne käib

&#91;embedded content: Avatud RDP kaudu rünne · 6 sammu\]

1. Ründaja skaneerib internetti (masscan, Shodan) ja leiab tuhandeid avatud RDP-porte.
2. Automaatne tööriist proovib igal serveril levinud kasutajanimesid ja paroole.
3. Nõrga parooliga server annab järele; ründaja logib sisse administraatorina.
4. Kuna võrk pole segmenteeritud, liigub ründaja edasi teistesse serveritesse.
5. Ründaja varastab andmed või käivitab lunavara.

### Päriselu näited

- **Capital One, 2019.** AWS-i pilves valesti seadistatud veebirakenduse tulemüür (ModSecurity) lubas ründajal suunata päringuid sisemistele ressurssidele ja hankida ligipääsuvõtmed (SSRF). Tulemüüril olid ka liiga laiad õigused: see võis lugeda kõiki S3-kaustu. Lekkisid umbes 100 miljoni USA ja 6 miljoni Kanada kliendi andmed. ([KrebsOnSecurity](https://krebsonsecurity.com/2019/08/what-we-can-learn-from-the-capital-one-hack/), [Infosec Institute](https://infosecinstitute.com/resources/news/lessons-learned-the-capital-one-breach/))
- **MongoDB „apokalüpsis", 2017.** Ründajad otsisid internetist paroolita MongoDB andmebaase, kustutasid nende sisu ja jätsid lunarahanõude. Jaanuaris tabati kümneid tuhandeid andmebaase ja septembris lisandus veel umbes 26 000. Kõigil juhtudel oli põhjus administraatori konto ilma paroolita. ([IT Pro](https://www.itpro.com/security/27885/26000-unsecured-mongodb-servers-hit-by-ransomware), [IBM Security Intelligence](https://securityintelligence.com/news/resurgent-malware-attack-targets-mongodb))

### Kaitse

- **Turvaline alusseadistus** (*hardening*): CIS Benchmarks, Microsofti turvalisuse alused, E-ITS meetmed.
- Haldusliidesed ainult VPN-i või hüppeserveri (*jump host*) kaudu, mitte otse internetti.
- Vaikeparoolid kohe vahetada; teenusekontod vähimate õigustega.
- Vanad protokollid välja (SMBv1, TLS 1.0/1.1).
- Regulaarne väline skaneerimine oma IP-de pihta (nmap, Shodan Monitor).
- Konfiguratsioon koodina ja muudatuste ülevaatus, et vead ei korduks.

## Oht 10: Tarneahela ründed

**Tarneahela rünnakus (*supply chain attack*) ei rünnata sihtmärki otse, vaid tema tarnijat: tarkvaratootjat, IT-teenuse pakkujat või avatud lähtekoodiga teeki.** Kui tarnija on kompromiteeritud, jõuab pahavara klientideni „usaldusväärse" uuenduse või teenuse kaudu. Üks edukas rünne tabab korraga tuhandeid organisatsioone.

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Tarneahel** | Kõik välised osapooled ja komponendid, millest sinu süsteem sõltub: tarkvara, teegid, riistvara, teenusepakkujad. |
| **Sõltuvus** (*dependency*) | Teek või pakett, mida sinu rakendus kasutab (npm, pip, Composer). |
| **Pahatahtlik uuendus** | Ametliku allkirjaga uuendus, millesse ründaja on lisanud tagaukse. |
| **Typosquatting paketiregistris** | Sarnase nimega pahatahtlik pakett, nt *reqeusts* päris *requests* asemel. |
| **MSP** (*Managed Service Provider*) | IT-teenuse pakkuja, kellel on ligipääs paljude klientide süsteemidele. |
| **SBOM** (*Software Bill of Materials*) | Tarkvara „koostisosade nimekiri": millised komponendid ja versioonid on sees. |

### Kuidas rünne käib

&#91;embedded content: Tarneahela rünne SolarWindsi näitel · 6 sammu\]

1. Ründaja tungib tarkvaratootja arenduskeskkonda (ehitusserver, koodihoidla).
2. Ründaja lisab lähtekoodi või ehitusprotsessi tagaukse.
3. Tootja allkirjastab ja avaldab uuenduse nagu tavaliselt; keegi ei märka muudatust.
4. Kliendid paigaldavad uuenduse, sest usaldavad tootjat (ja automaatuuendused on sees).
5. Tagauks võtab ühendust ründaja serveriga; ründaja valib huvitavad ohvrid ja liigub nende võrgus edasi.

### Päriselu näited

- **SolarWinds Orion, 2020.** Ründajad lisasid võrguhaldustarkvara ametlikesse uuendustesse (märts–juuni 2020) tagaukse SUNBURST. SolarWindsi hinnangul paigaldas selle kuni 18 000 klienti; CISA andis välja erakorralise direktiivi Orioni toodete lahtiühendamiseks. ([Dark Reading](https://www.darkreading.com/cyberattacks-data-breaches/18-000-organizations-possibly-compromised-in-massive-supply-chain-cyberattack), [Security Affairs](https://securityaffairs.com/112294/hacking/solarwinds-sec-filing.html?amp=1))
- **NotPetya, juuni 2017.** Ukraina raamatupidamistarkvara M.E.Doc uuendusserver häkiti ja selle kaudu levis hävitav pahavara, mis maskeeris end lunavaraks. Ülemaailmset kahju hinnatakse üle 10 miljardi dollari; kannatasid ka Maersk, Merck ja FedEx. ([CCDCOE Cyber Law Toolkit](<https://cyberlaw.ccdcoe.org/wiki/NotPetya_(2017)>), [Wired (ISSP)](https://www.issp.com/wired-the-untold-story-of-notpetya))
- **XZ Utils, märts 2024** (CVE-2024-3094, CVSS 10). Kasutajanime „Jia Tan" all tegutsenud ründaja oli aastaid projekti kaasautor ja lisas versioonidesse 5.6.0 ja 5.6.1 SSH-serverit mõjutava tagaukse. Selle avastas arendaja Andres Freund, kes märkas SSH-sisselogimise umbes poolesekundilist viivitust ja suurenenud protsessorikoormust. ([Wikipedia](https://en.wikipedia.org/wiki/XZ_Utils_backdoor), [iTnews](https://www.itnews.com.au/news/curious-engineer-catches-backdoor-in-linux-compression-package-606599))
- **3CX, 2023.** Ettevõtte VoIP-rakenduse ametlik paigaldaja sisaldas pahavara. Mandianti uurimise järgi oli 3CX ise nakatunud, kui töötaja laadis alla pahavaraga nakatatud X\_Traderi tarkvara: esimene teadaolev juhtum, kus üks tarneahela rünnak tõi kaasa teise. ([CyberScoop](https://cyberscoop.com/3cx-supply-chain-north-korea/), [TechCrunch](https://techcrunch.com/2023/04/20/3cx-supply-chain-xtrader-mandiant))

### Kaitse

- Tarnijate valik ja hindamine: turvanõuded lepingus, sertifikaadid (ISO 27001).
- Vähimad õigused ka tarnija tarkvarale ja MSP ligipääsudele; ligipääsud eraldi kontodega ja logitud.
- Võrgu väljuva liikluse kontroll: haldustarkvara ei peaks saama vabalt internetti pöörduda.
- Arendajale: sõltuvuste lukustamine (*lock*-failid), sõltuvuste skaneerimine (Dependabot, `npm audit`), SBOM.
- Uuenduste testimine enne laialdast paigaldamist (etapiline juurutus).

## Oht 11: Siseoht

**Siseoht (*insider threat*) tuleb inimeselt, kellel on organisatsioonis juba seaduslik ligipääs: töötaja, endine töötaja, praktikant, lepingupartner.** Tulemüür ei aita, sest see inimene on juba „sees". Siseoht ei ole alati pahatahtlik: suur osa juhtumeid on hooletus või eksimus.

### Siseohu liigid

| Liik | Kirjeldus | Näide |
| --- | --- | --- |
| **Pahatahtlik sisetöötaja** | Kahjustab tahtlikult: kättemaks, raha, ideoloogia, spionaaž. | Lahkuv töötaja kopeerib kliendibaasi konkurendile. |
| **Hooletu sisetöötaja** | Ei taha kahju teha, kuid rikub reegleid. | Saadab tundliku faili isiklikule e-postile, jätab sülearvuti lukustamata. |
| **Kompromiteeritud sisetöötaja** | Tema konto on ründaja kontrolli all (andmepüük). | Ründaja logib sisse raamatupidaja kontoga. |
| **Värvatud sisetöötaja** | Väline ründaja maksab töötajale ligipääsu eest. | Lunavaragrupp pakub töötajale osa lunarahast. |

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Vähimate õiguste põhimõte** | Igaüks näeb ainult seda, mida tööks vaja. |
| **Kohustuste lahusus** (*separation of duties*) | Kriitilist toimingut ei saa teha üks inimene üksi (nt makse koostab üks, kinnitab teine). |
| **DLP** (*Data Loss Prevention*) | Tarkvara, mis takistab tundlike andmete viimist USB-le, e-postiga või pilve. |
| **UEBA** | Kasutajakäitumise analüüs: märkab, kui keegi käitub tavapärasest erinevalt. |
| **Väljumisprotsess** (*offboarding*) | Lahkuva töötaja kõigi ligipääsude sulgemine. |

### Kuidas rünne käib

&#91;embedded content: Pahatahtliku sisetöötaja tegevus · 6 sammu\]

Pahatahtliku sisetöötaja tüüpiline käik:

1. **Motiiv tekib:** rahulolematus, rahahäda, lahkumine konkurendi juurde, väljastpoolt tulev pakkumine.
2. **Ettevalmistus:** töötaja uurib, kuhu tal ligipääs on, sageli ka sinna, kuhu tal tööülesannete järgi asja pole.
3. **Tegevus:** andmete kopeerimine USB-le, isiklikku pilve või e-postile; süsteemide sabotaaž.
4. **Varjamine:** logide kustutamine, tegevus väljaspool tööaega.
5. **Mõju:** ärisaladuste leke, isikuandmete leke, maine- ja õiguslik kahju.

### Päriselu näited

- **Tesla, 2023.** Kaks endist töötajat andsid Saksa ajalehele Handelsblatt umbes 100 GB sisedokumente, sh 75 735 praeguse ja endise töötaja isikuandmed. Tesla kaebas nad kohtusse ja nende seadmed konfiskeeriti. ([Infosecurity Magazine](https://www.infosecurity-magazine.com/news/tesla-insiders-responsible-for/), [InsideEVs](https://insideevs.com/news/682896/tesla-sues-two-former-employees-data-breach-affected-75000/))
- **Edward Snowden, 2013.** NSA lepingupartneri Booz Allen Hamiltoni kaudu süsteemiadministraatorina töötanud Snowden kopeeris salastatud dokumente USB-pulkadele ja andis need ajakirjanikele. Juhtum näitab, kui ohtlikud on laiad administraatoriõigused ilma järelevalveta. ([EBSCO Research Starters](https://ebsco.com/research-starters/biography/edward-snowden))
- **Herman Simm, 2008.** Kaitseministeeriumi julgeolekuosakonna endine juhataja andis aastatel 1995–2008 Venemaa välisluurele SVR edasi tuhandeid salastatud Eesti ja NATO dokumente. Harju maakohus mõistis ta 25. veebruaril 2009 süüdi riigireetmises ja karistuseks määrati 12 aastat ja 6 kuud vangistust. ([ERR](https://www.err.ee/351936/herman-simm-andis-vene-luurele-vahemalt-3294-saladokumenti), [ERR](https://www.err.ee/837696/prokuratuur-on-riigireetur-herman-simmi-vanglast-vabastamisele-vastu))
- **Hooletuse näide.** Töötaja saadab palgafaili vale adressaadile, sest e-posti programm pakkus automaatselt vale nime. Tahtlust ei olnud, kuid isikuandmete leke on sama.

### Kaitse

- Vähimad õigused ja regulaarne õiguste ülevaatus (kas kõik õigused on veel vajalikud?).
- Kohustuste lahusus kriitilistes protsessides (maksed, administraatoritoimingud).
- Logimine ja jälgimine, eriti administraatorite tegevuse puhul.
- DLP ja USB-seadmete piiramine.
- Korrektne väljumisprotsess: kontod suletakse lahkumise päeval.
- Organisatsioonikultuur: töötajad julgevad muredest ja vigadest rääkida.

## Oht 12: Tehisintellekt ja süvavõltsingud

**Tehisintellekt (TI) ei ole uus ründetüüp, vaid võimendi: see teeb vanad ründed (andmepüük, pettus, pahavara) kiiremaks, odavamaks ja usutavamaks.** Kõige märgatavam muutus on süvavõltsingud: võltsitud hääl ja video, mis jäljendavad päris inimest.

### Terminid

| Termin | Selgitus |
| --- | --- |
| **Süvavõltsing** (*deepfake*) | TI-ga loodud võltsitud video, pilt või hääl, mis näib päris inimesena. |
| **Hääle kloonimine** | Mõne sekundi helisalvestusest saab TI luua hääle, mis ütleb mida iganes. |
| **TI-põhine andmepüük** | Keelemudel kirjutab vigadeta, isikupärastatud kirju mis tahes keeles, ka eesti keeles. |
| **Viipesüst** (*prompt injection*) | Rünnak TI-rakenduse vastu: varjatud tekst dokumendis või veebilehel annab TI-assistendile ründaja käske. |
| **Varjatud TI** (*shadow AI*) | Töötajad kasutavad heakskiitmata TI-teenuseid ja kopeerivad sinna tundlikke andmeid. |

### Kuidas rünne käib

&#91;embedded content: Süvavõltsitud videokõne pettus · 6 sammu\]

1. **Materjali kogumine.** Ründaja kogub juhi videoid ja kõnesid avalikest allikatest (esinemised, YouTube, sotsiaalmeedia).
2. **Võltsingu loomine.** TI-tööriist loob hääle ja näo, mis räägivad reaalajas.
3. **Kontakt.** Raamatupidaja saab kirja „tegevjuhilt" konfidentsiaalse tehingu kohta ja kutse videokõnele.
4. **Veenmine.** Videokõnes on „tegevjuht" ja „kolleegid", kõik võltsitud. Nad kinnitavad tehingut.
5. **Makse.** Ohver teeb ülekande, sest nägi ja kuulis oma juhti.

### Päriselu näited

- **Arup, Hongkong, jaanuar 2024.** Inseneribüroo Hongkongi kontori töötaja sai „finantsjuhilt" kirja konfidentsiaalse tehingu kohta. Videokõnes olid finantsjuht ja kolleegid kõik süvavõltsingud. Töötaja tegi 15 ülekannet viiele pangakontole kokku 200 miljonit Hongkongi dollarit (umbes 25 miljonit USA dollarit). ([Dezeen](https://www.dezeen.com/2024/05/17/arup-victim-deepfake-video-scam/), [South China Morning Post](https://scmp.com/news/hong-kong/law-and-crime/article/3263151/uk-multinational-arup-confirmed-victim-hk200-million-deepfake-scam-used-digital-version-cfo-dupe))
- **Ühendkuningriigi energiafirma, 2019.** Tegevjuht sai kõne, kus Saksa emaettevõtte juhi TI-ga kloonitud hääl palus tunni jooksul kanda Ungari tarnijale 220 000 eurot. Raha kanti üle; kindlustusandja Euler Hermes kirjeldas seda kui esimest teadaolevat häälevõltsinguga pettust. ([Sophos](https://www.sophos.com/es-es/blog/scammers-deepfake-ceos-voice-to-talk-underling-into-243000-transfer), [Infosecurity Magazine](https://www.infosecurity-magazine.com/infosec/deepfake-audio-is-the-next-social/))
- **„Lapselapse" pettus.** Petised helistavad eakatele lähedase kloonitud häälega ja paluvad kiiresti raha, sest „juhtus õnnetus".

### Kuidas ära tunda

- Ootamatu ja kiireloomuline palve raha või andmete järele, ka siis, kui hääl või nägu tundub tuttav.
- Videos ebaloomulikud liigutused, huulte ja heli ebasünkroonsus, kummaline valgus näo servades.
- Helistaja keeldub tagasihelistamisest või teisest kanalist.

### Kaitse

- **Kinnita teise kanali kaudu:** helista tagasi teadaolevale numbrile, küsi kolleegilt üle.
- Kokkulepitud kontrollsõna või -küsimus pere- ja töökeskkonnas.
- Maksete kahe inimese kinnitus ja ülempiirid, mida ei saa ühe kõnega muuta.
- TI-kasutuse reeglid organisatsioonis: millistesse teenustesse tohib milliseid andmeid panna.
- Koolitus: töötajad peavad teadma, et video ja hääl ei ole enam tõend.

## Praktika

Praktika koosneb kuuest laboriharjutusest (P1–P6, kokku 6 tundi) ja kahest iseseisvast tööst (I1–I2, kokku 6 tundi). Kõik harjutused tehakse **ainult kooli laborivõrgus või oma virtuaalmasinates**.

**Vajalik keskkond:** VirtualBox või VMware; Kali Linuxi virtuaalmasin; Windows 10/11 virtuaalmasin; DVWA (Damn Vulnerable Web Application) Dockeri konteineris või Metasploitable 2 virtuaalmasin. Virtuaalmasinad on ühendatud ainult sisevõrku (*Host-only* / *Internal network*).

| Harjutus | Teema | Seotud ohud | Aeg |
| --- | --- | --- | --- |
| P1 | Andmepüügikirja analüüs | 1, 12 | 1 h |
| P2 | Paroolide tugevus ja räside murdmine | 4 | 1 h |
| P3 | Võrgu skaneerimine ja avatud teenused | 9, 8 | 1 h |
| P4 | Liikluse pealtkuulamine Wiresharkiga | 6 | 1 h |
| P5 | SQL-süst ja XSS DVWA-s | 7 | 1,5 h |
| P6 | Haavatavuste otsimine CVE andmebaasist | 8, 10 | 0,5 h |
| I1 | Rünnaku juhtumianalüüs | 1, 3, 5 | 3 h |
| I2 | MITRE ATT&CK kaardistus | 3 | 3 h |

### P1. Andmepüügikirja analüüs (1 h)

**Eesmärk:** õppida andmepüügikirja tehniliselt ära tundma.

1. Õpetaja jagab 3–4 näidiskirja (.eml failid), millest osa on päris, osa andmepüük.
2. Ava kiri tekstiredaktoris ja leia päistest `From`, `Reply-To`, `Return-Path` ja `Received` read. Kas saatja domeen klapib?
3. Leia päisest `Authentication-Results`: mis on SPF, DKIM ja DMARC tulemus (*pass/fail*)?
4. Kopeeri kirjas olevad lingid (ära klõpsa!) ja kontrolli neid [VirusTotalis](https://www.virustotal.com) või [urlscan.io](https://urlscan.io)-s.
5. Täida iga kirja kohta tabel: tunnused, mis viitavad andmepüügile, ja lõplik hinnang.

**Esitamiseks:** tabel ja 3 lauset selle kohta, milline tunnus oli kõige veenvam.

### P2. Paroolide tugevus ja räside murdmine (1 h)

**Eesmärk:** mõista, miks lühikesed ja levinud paroolid on ohtlikud.

1. Kali Linuxis loo fail `rasid.txt` õpetaja antud MD5-räsidega (või loo ise: `echo -n "Parool123" | md5sum`).
2. Murra räsid sõnastikuga: `john --format=raw-md5 --wordlist=/usr/share/wordlists/rockyou.txt rasid.txt`.
3. Pane kirja, millised paroolid murti ja kui kiiresti.
4. Loo samasugune räsi 4 juhuslikust sõnast koosnevale paroolile ja proovi uuesti. Mis juhtus?
5. Kontrolli oma e-posti aadressi lehel [haveibeenpwned.com](https://haveibeenpwned.com) ja seadista paroolihaldur (Bitwarden või KeePassXC).

**Arutelu:** miks peab süsteem paroole salvestama bcrypti või Argon2-ga, mitte MD5-ga?

### P3. Võrgu skaneerimine ja avatud teenused (1 h)

**Eesmärk:** näha ründepinda ründaja silmadega.

1. Leia laborivõrgus Metasploitable'i masina IP: `nmap -sn 192.168.56.0/24`.
2. Skaneeri avatud pordid ja teenuste versioonid: `nmap -sV 192.168.56.101`.
3. Koosta tabel: port, teenus, versioon, miks see võib ohtlik olla (nt Telnet 23 krüpteerimata, FTP anonüümne ligipääs).
4. Skaneeri ka oma Windowsi virtuaalmasinat enne ja pärast Windowsi tulemüüri sisselülitamist. Mis muutus?
5. Paku iga ohtliku teenuse jaoks kaitsemeede (sulgeda, piirata, uuendada).

### P4. Liikluse pealtkuulamine Wiresharkiga (1 h)

**Eesmärk:** mõista, miks krüpteerimata ühendus on MitM-rünnakus ohtlik.

1. Käivita Wireshark Kali masinas ja hakka sisevõrgu liidest salvestama.
2. Logi Metasploitable'i masinasse sisse Telneti või FTP kaudu (kasutaja *msfadmin*).
3. Filtreeri liiklus (`telnet` või `ftp`) ja kasuta *Follow TCP Stream*. Kas näed kasutajanime ja parooli?
4. Korda sama SSH-ga. Mida näed nüüd?
5. Kirjuta lühike selgitus: mida näeks ründaja avalikus Wi-Fis HTTP ja HTTPS puhul?

### P5. SQL-süst ja XSS DVWA-s (1,5 h)

**Eesmärk:** näha oma silmaga, kuidas sisendi usaldamine viib rünnakuni, ja kuidas seda parandada.

1. Käivita DVWA (`docker run -d -p 8080:80 vulnerables/web-dvwa`), logi sisse ja sea turvatase **Low**.
2. **SQL Injection:** sisesta User ID väljale `1` ja seejärel `1' OR '1'='1`. Mitu kasutajat tagastati? Selgita, miks.
3. **XSS (Reflected):** sisesta nimeväljale `<script>alert('XSS')</script>`. Mis juhtus?
4. **XSS (Stored):** lisa külalisraamatusse sama skript ja ava leht uuesti.
5. Sea turvatase **Impossible** ja ava *View Source*. Leia koodist, mis rünnaku peatas (parametriseeritud päring, `htmlspecialchars`).

**Esitamiseks:** ekraanitõmmised ja selgitus, milline koodirida haavatavuse parandab.

### P6. Haavatavuste otsimine CVE andmebaasist (0,5 h)

1. Vali P3-s leitud teenuse versioon (nt vsftpd 2.3.4 või Apache 2.2.8).
2. Otsi selle versiooni haavatavusi lehel [nvd.nist.gov](https://nvd.nist.gov) ja [CISA KEV](https://www.cisa.gov/known-exploited-vulnerabilities-catalog) nimekirjast.
3. Pane kirja: CVE number, CVSS hinne, haavatavuse tüüp (nt RCE), kas paik on olemas.
4. Otsusta, kui kiiresti tuleks see päriselus paigata ja miks.

### I1. Iseseisev töö: rünnaku juhtumianalüüs (3 h)

Vali kolm juhtumit selle materjali näidetest: üks andmepüügi, üks DDoS-i ja üks lunavara kohta. Kirjuta iga juhtumi kohta 1–2 lehekülge:

1. **Rünnaku mehhanism:** kirjelda samm-sammult, kuidas rünnak tehniliselt õnnestus (nt andmepüügikiri → manuse käivitamine → ühendus C2-serveriga → krüpteerimine). Lisa oma joonis ründevektorist.
2. **Ründaja motiiv:** rahaline kasu, poliitiline protest, spionaaž või muu?
3. **Rikutud CIA omadus:** mis rikuti ja kuidas see organisatsiooni mõjutas?
4. **Leevendusmeetmed:** vähemalt 3 tehnilist ja 1 protsessiline meede, mis oleks rünnaku ära hoidnud või mõju vähendanud. Põhjenda, millises Kill Chaini etapis iga meede ahela katkestab.

Kasuta vähemalt kahte usaldusväärset allikat iga juhtumi kohta (CISA, RIA, tootja ametlik raport, tunnustatud meedia).

### I2. Iseseisev töö: MITRE ATT&CK kaardistus (3 h)

**MITRE ATT&CK** ([attack.mitre.org](https://attack.mitre.org)) on teadmistebaas, mis kirjeldab ründajate **taktikaid** (*miks*, eesmärk) ja **tehnikaid** (*kuidas*, meetod). Kaitsjad kasutavad seda, et mõista ründaja käitumist ja planeerida kaitset.

1. Ava *Enterprise* maatriks ja tutvu 14 taktikaga (veerud vasakult paremale: *Reconnaissance* … *Impact*).
2. Vali I1-s uuritud lunavara juhtum ja kaardista selle käik vähemalt 5 taktikaga.
3. Iga taktika juurde leia sobiv tehnika koos ID-ga ja selgita ühe lausega.

Näide (Colonial Pipeline):

| Taktika | Tehnika | Selgitus |
| --- | --- | --- |
| Initial Access | T1078 Valid Accounts | Kasutati lekkinud VPN-konto parooli. |
| Execution | T1059 Command and Scripting Interpreter | Käskude käivitamine (nt PowerShell). |
| Exfiltration | T1567 Exfiltration Over Web Service | Andmed laaditi enne krüpteerimist välja. |
| Impact | T1486 Data Encrypted for Impact | Failid krüpteeriti lunavaraga. |

4. Lisa iga tehnika juurde üks kaitsemeede (ATT&CK lehel *Mitigations*).
5. Kirjuta lõpuks lühike arutlus (½ lehekülge): kuidas aitab ATT&CK sinisel meeskonnal kaitset planeerida?

### Hindamine

| Kriteerium | Osakaal |
| --- | --- |
| Praktikaharjutuste P1–P6 aruanded | 40% |
| Juhtumianalüüs I1 | 30% |
| MITRE ATT&CK kaardistus I2 | 20% |
| Enesekontrolli test | 10% |

## Kokkuvõte: oht ja peamine kaitse

| Oht | Peamine ründevektor | Kõige tõhusam kaitse |
| --- | --- | --- |
| 1. Andmepüük | E-kiri, SMS, kõne | Andmepüügikindel MFA, koolitus, maksete kinnitus teise kanali kaudu |
| 2. Pahavara | Manus, allalaadimine, USB | EDR, uuendused, tavakasutaja õigused |
| 3. Lunavara | Andmepüük, VPN/RDP parool | Offline-varukoopiad, MFA, segmenteerimine |
| 4. Paroolirünnakud | Lekkinud paroolid | Unikaalsed paroolid, paroolihaldur, MFA |
| 5. DDoS | Botivõrk | DDoS-kaitseteenus, päringute piiramine |
| 6. MitM | Avalik Wi-Fi, kohtvõrk | HTTPS, VPN, HSTS |
| 7. Veebirakenduse ründed | Vormiväljad, URL | Parametriseeritud päringud, väljundi kodeerimine |
| 8. Haavatavused | Paikamata tarkvara | Paikade haldus, varade nimekiri |
| 9. Valekonfiguratsioon | Avatud pordid, vaikeparoolid | Alusseadistus (*hardening*), väline skaneerimine |
| 10. Tarneahel | Tarnija uuendus | Tarnijate hindamine, vähimad õigused, SBOM |
| 11. Siseoht | Seaduslik ligipääs | Vähimad õigused, logimine, väljumisprotsess |
| 12. TI ja süvavõltsingud | Kõne, videokõne | Kinnitus teise kanali kaudu, kontrollsõna |

Läbiv mõte: **üks kaitse ei piisa kunagi** (kihiline kaitse). Kõige sagedamini korduvad meetmed on MFA, uuendused, varukoopiad, vähimad õigused ja teadlik kasutaja.

## Enesekontrolli küsimused

1. Mis vahe on ohul ja haavatavusel? Too üks näide mõlemast samast olukorrast.
2. Milline CIA-kolmiku omadus rikutakse DDoS-rünnakus ja milline andmelekkes?
3. Selgita oma sõnadega, mis on ründevektor ja ründepind.
4. Miks on sihitud andmepüük ohtlikum kui masspüük?
5. Mis vahe on viirusel, ussil ja troojalasel?
6. Mis on topeltväljapressimine ja miks ainult varukoopiad sellest ei päästa?
7. Miks aitab MFA mandaaditäite vastu, kuid tavaline SMS-kood ei pruugi aidata vahendajarünnaku vastu?
8. Kirjuta SQL-päring, mis on haavatav SQL-süstile, ja selle turvaline versioon.
9. Miks on paikamata haavatavused (n-day) suurem probleem kui nullpäevad?
10. Too kolm näidet valekonfiguratsioonist, mida süsteemiadministraator saab kohe parandada.
11. Kuidas jõudis SolarWindsi rünnak 18 000 organisatsioonini, ilma et neid otse rünnati?
12. Kuidas kaitseksid ettevõtet süvavõltsitud videokõne pettuse eest?
13. Nimeta Cyber Kill Chaini seitse etappi ja too iga etapi kohta üks kaitsemeede.

## Allikad ja lisalugemine

- [Riigi Infosüsteemi Amet (RIA) ja CERT-EE](https://www.ria.ee): Eesti küberturvalisuse aastaraamatud ja ohuhinnangud
- [E-ITS – Eesti infoturbestandard](https://eits.ria.ee)
- [MITRE ATT&CK](https://attack.mitre.org): ründajate taktikate ja tehnikate teadmistebaas
- [Lockheed Martin Cyber Kill Chain](https://www.lockheedmartin.com/en-us/capabilities/cyber/cyber-kill-chain.html)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/): veebirakenduste levinumad riskid
- [CISA Known Exploited Vulnerabilities](https://www.cisa.gov/known-exploited-vulnerabilities-catalog): päriselt rünnakutes kasutatud haavatavused
- [NVD – haavatavuste andmebaas](https://nvd.nist.gov)
- [CCDCOE Cyber Law Toolkit](https://cyberlaw.ccdcoe.org): Tallinnas asuva NATO küberkaitsekoostöö keskuse juhtumikirjeldused

Iga päriselu näite allikad on lingitud otse näite juures. Arvud (kahjusummad, mõjutatud kasutajate arv) pärinevad ametiasutuste, ettevõtete ja meedia avaldatud andmetest ning on kontrollitud 4. novembril 2026. Hilisemad uurimised võivad neid täpsustada.
