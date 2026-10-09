# Pahavara ja lunavara

*Õppematerjal IT-õppijatele · eeldab teadmisi krüptograafiast, haavatavustest ja E-ITS-ist*

## 1. Õpiväljundid

Pärast materjali läbitöötamist õppija:

- selgitab pahavara mõistet ning eristab peamisi pahavaraliike;
- kirjeldab pahavara elutsüklit ja levikuteid;
- selgitab lunavara toimimist, sealhulgas krüptograafia rolli ründes;
- analüüsib kolme tegelikku juhtumit (WannaCry, NotPetya, Colonial Pipeline);
- tunneb ära pahavara tunnused ning oskab kirjeldada tuvastamise võimalusi;
- kavandab kaitsemeetmed, varundusstrateegia ja taastamisplaani;
- seostab teemat E-ITS-i meetmetega ja kirjeldab intsidendi käsitlemise põhisamme.

## 2. Sissejuhatus

**Pahavara** (ingl *malware*, *malicious software*) on tarkvara, mis on loodud kahjustama süsteemi, varastama andmeid, häirima teenust või andma ründajale volitamata ligipääsu. Pahavara ei ole iseenesest „viirus”: viirus on ainult üks pahavara liik.

Miks teema on oluline IT-spetsialistile:

- pahavara on endiselt üks levinumaid viise, kuidas organisatsioone tabatakse;
- lunavara on viimastel aastatel olnud üks kahjulikumaid ründeliike, sest see mõjutab otse ärijätkuvust;
- sama ründe võib põhjustada nii tehniline viga (paigatamata haavatavus) kui inimlik viga (õngitsuskirja avamine), seega kaitse peab olema kihiline.

Oluline põhimõte: **ründaja eesmärk on enamasti raha**. Selle kõrval esineb spionaaž, sabotaaž ja aktivism, kuid suurem osa juhtumeid on rahamotiiviga.

## 3. Pahavara liigid

| Liik | Põhiomadus | Näide |
| --- | --- | --- |
| Viirus | Lisab end teistesse failidesse, vajab kasutaja tegevust | Vanemad failiviirused |
| Uss (*worm*) | Levib iseseisvalt võrgu kaudu, ei vaja kasutaja tegevust | WannaCry, Conficker |
| Trooja hobune | Näib kasulik või kahjutu programm, kuid sisaldab varjatud funktsiooni | Emotet (algselt pangatrooja) |
| Lunavara | Krüpteerib või lukustab andmed ja nõuab tasu | LockBit, Conti, Ryuk |
| Nuhkvara (*spyware*) | Jälgib kasutajat ja kogub andmeid | Reklaamiklikkijad, jälgimistarkvara |
| Infovaras (*infostealer*) | Varastab paroole, küpsiseid, sessioone | RedLine, Raccoon |
| Klahvilogija (*keylogger*) | Salvestab sisestatud klahvivajutused | Mitmesugused |
| Juurkomplekt (*rootkit*) | Peidab end operatsioonisüsteemi tasemel | Stuxnet sisaldas rootkit-elemente |
| Botnet | Nakatatud seadmete võrk, mida juhitakse kaugelt | Mirai (IoT-seadmed) |
| Krüptokaevur | Kasutab ohvri arvutiressurssi krüptoraha kaevandamiseks | Cryptojacking |
| Laadur (*loader/dropper*) | Toob sihtsüsteemi järgmise etapi pahavara | Emotet, Qakbot |
| Failivaba (*fileless*) pahavara | Töötab mälus ja kasutab süsteemi enda tööriistu | Ründed PowerShelli ja WMI vahendusel |

### 3.1 Mõned eristused

- **Viirus vs uss.** Viirus vajab peremeesfaili ja kasutaja tegevust; uss levib ise, tavaliselt võrguteenuste haavatavuste kaudu. Seetõttu võivad ussid nakatada tuhandeid seadmeid minutitega.
- **Laadur vs lõppkoormus.** Kaasaegsed ründed on mitmeastmelised: esmane pahavara (laadur) on väike ja tuvastamatu, ning toob alla põhikoormuse (*payload*), näiteks lunavara.
- **Kaubanduslik pahavara.** Paljud ründajad ei kirjuta pahavara ise, vaid ostavad ligipääsu või tööriistu. See on põhjus, miks nõrgema oskusega ründajad suudavad tekitada suurt kahju.

## 4. Pahavara elutsükkel ja levikuteed

### 4.1 Elutsükli etapid

1. **Levitamine** – pahavara jõuab sihtmärgini.
2. **Käivitumine** – kasutaja või haavatavus käivitab koodi.
3. **Kinnistumine** (*persistence*) – pahavara tagab, et jääb süsteemi ka pärast taaskäivitust (autostart, ajastatud ülesanded, teenused).
4. **Eskaleerimine** – õiguste tõstmine, nt administraatori- või domeeniõigused.
5. **Peitmine** – tuvastuse vältimine (pakkimine, krüpteerimine, kaitsetarkvara väljalülitamine).
6. **Juhtimine** – side ründaja juhtserveriga (*command and control*, C2).
7. **Tegevus eesmärgi nimel** – andmete varastamine, krüpteerimine, laiali levimine.
8. **Jälgede kustutamine** – logide ja tööriistade eemaldamine.

### 4.2 Levinumad levikuteed

- **Õngitsuskirjad** (*phishing*) pahatahtliku manuse või lingiga. Manuseks on sageli kontoridokument, arhiiv või ISO-fail.
- **Paigatamata haavatavused** avalikel teenustel (VPN, RDP, veebirakendused, e-posti serverid).
- **Nõrgad või lekkinud paroolid**, eriti kaugligipääsul (RDP, VPN) ilma mitmefaktorilise autentimiseta.
- **Pahatahtlikud veebilehed ja reklaamid** (*drive-by download*, *malvertising*).
- **Tarneahela rünne** – pahavara jõuab tarkvara uuenduse või kolmanda osapoole komponendi kaudu.
- **Irdkandjad** (USB), eriti füüsilise ligipääsuga rünnetes.
- **Piraattarkvara ja võltsrakendused**, sh pahatahtlikud brauserilaiendused.
- **Eelnevalt nakatatud seadmed** – ründaja ostab ligipääsu, mille on loonud teine pahavara (nt Emotet → lunavara).

## 5. Lunavara

### 5.1 Mis see on?

**Lunavara** (*ransomware*) on pahavara, mis takistab ohvril oma andmetele või süsteemidele ligipääsu ja nõuab selle taastamise eest tasu, tavaliselt krüptoraha kujul.

### 5.2 Rünnete tüübid

- **Lukustav lunavara** (*locker*) – blokeerib seadme või ekraani.
- **Krüpteeriv lunavara** (*crypto-ransomware*) – krüpteerib failid; kõige levinum.
- **Kahekordne väljapressimine** (*double extortion*) – andmed varastatakse enne krüpteerimist ning ähvardatakse need avaldada.
- **Kolmekordne väljapressimine** – lisaks survestatakse ohvri kliente, partnereid või kasutatakse DDoS-i.
- **Ransomware-as-a-Service (RaaS)** – lunavara arendajad rendivad tööriista „partneritele”, kes viivad ründed läbi ja jagavad tulu.

### 5.3 Krüptograafia roll lunavaras

Siin seostub teema varem õpitud krüptograafiaga. Korralik lunavara kasutab **hübriidkrüptograafiat**:

1. Iga faili (või failigrupi) krüpteerimiseks genereeritakse **sümmeetriline võti** (nt AES), sest see on kiire.
2. Need sümmeetrilised võtmed krüpteeritakse **asümmeetrilise avaliku võtmega** (nt RSA), mis on ründaja poolt süsteemi kaasa pandud.
3. Vastava **privaatvõtme** hoiab ainult ründaja, seega ilma temata ei saa sümmeetrilisi võtmeid taastada.

See selgitab, miks krüpteeritud andmeid tavaliselt **ei saa murda**: probleem ei ole algoritmis, vaid võtme puudumises. Nõrkused ilmnevad pigem **teostuses** (vigased juhuarvud, korduvalt kasutatud võtmed, vigane protsess), mille tõttu on mõne lunavara jaoks tehtud tasuta dekrüptorid. Seda ei tohi aga kaitse alusena arvestada.

### 5.4 Tüüpiline lunavararünde käik

Tänapäevane „käsitsi juhitud” lunavararünne (*human-operated ransomware*) kestab tihti päevi või nädalaid:

1. **Esmane ligipääs** – õngitsus, haavatav VPN, varastatud paroolid või ostetud ligipääs.
2. **Keskkonna uurimine** – domeeni struktuuri, serverite ja varukoopiate leidmine.
3. **Õiguste eskaleerimine** ja volituste kogumine (nt domeeniadministraatori konto).
4. **Külgsuunaline liikumine** (*lateral movement*) teistesse serveritesse.
5. **Varukoopiate ja kaitsetarkvara kahjustamine** – varukoopiate kustutamine, varjukoopiate eemaldamine, EDR-i väljalülitamine.
6. **Andmete väljaviimine** (*exfiltration*) topeltväljapressimiseks.
7. **Krüpteerimine** – tavaliselt öösel, nädalavahetusel või pühade ajal, ja korraga paljudel süsteemidel (nt GPO kaudu).
8. **Lunanõue** – lunakiri ja kontakt (sageli Tor-võrgus).

**Oluline järeldus:** lunavara käivitumine on ründe **viimane** samm. Eelnev tegevus jätab jälgi, mida on võimalik tuvastada (ebatavalised sisselogimised, uued administraatorikontod, suured andmeedastused, kaitsetarkvara väljalülitamine). Tuvastamine enne krüpteerimist on suurim võimalus kahju ära hoida.

### 5.5 Kas maksta lunaraha?

Soovitus on **mitte maksta**, kuid otsus on organisatsiooni juhtkonna oma ning sõltub õiguslikest ja ärilistest asjaoludest. Põhjused maksmise vastu:

- tasumine ei garanteeri andmete taastamist (dekrüptor võib olla aeglane või osaliselt vigane);
- see rahastab kuritegelikke gruppe ja soodustab uusi rünnakuid;
- ründaja võib andmed siiski avaldada;
- organisatsioon märgitakse tihti „maksjana” ning ründed korduvad.

Selle asemel on mõistlik investeerida **varundusse, taastamisplaani ja tuvastamisse**. Rünnet tuleb alati teavitada ametiasutustele (Eestis CERT-EE/RIA, vajadusel politsei ning isikuandmete rikkumise korral Andmekaitse Inspektsioon).

## 6. Kolm näidet

### 6.1 Näide 1: WannaCry (2017)

**Mis juhtus.** 2017. aasta mais levis lunavaraussi WannaCry kiiresti üle maailma ning nakatas sadu tuhandeid arvuteid sadades riikides. Rängalt kannatasid muu hulgas Suurbritannia tervishoiuasutused (NHS), mille tõttu tuli haiglatel osa teenuseid katkestada.

**Kuidas töötas.**

- Pahavara kasutas Windowsi SMB-protokolli haavatavust (**EternalBlue**), mida Microsoft oli paigaga parandanud ligikaudu kaks kuud varem.
- Tegemist oli **ussiga**: nakatunud seade otsis võrgust ja internetist uusi haavatavaid seadmeid ning nakatas need ilma kasutaja tegevuseta.
- Seejärel krüpteeris pahavara failid ja nõudis lunaraha Bitcoinis.

**Õppetunnid.**

- **Paigahaldus** on põhiline kaitse. Paigata seadmed ei langenud ohvriks.
- **SMB (TCP 445) ei tohi olla avalikult kättesaadav** ning sisevõrgus tuleb teenuseid segmenteerida.
- Vananenud, toetuseta operatsioonisüsteemid (nt Windows XP) on suur risk.
- Lõppeesmärk polnud ilmselt tulus: lunaraha maksmise protsess oli kehv, mis näitab, et ründe mõju võib ületada ründaja eesmärgid.
- Ründe peatas osaliselt „lüliti” (*kill switch*), mille leidis turvauurija – see näitab, et analüüs ja teabejagamine aitavad.

### 6.2 Näide 2: NotPetya (2017)

**Mis juhtus.** 2017. aasta juunis algas Ukrainas rünne, mis levis rahvusvahelistesse ettevõtetesse ning põhjustas miljardite dollarite suuruse kahju. Kannatanute hulgas olid suured logistika-, tootmis- ja farmaatsiaettevõtted.

**Kuidas töötas.**

- Algne levik toimus **tarneahela kaudu**: pahatahtlik uuendus jõudis Ukrainas laialt kasutatud raamatupidamistarkvara uuendusmehhanismi kaudu klientideni.
- Levimiseks kasutati nii EternalBluet kui ka **volituste kogumise ja külgsuunalise liikumise** tehnikaid (nt Windowsi paroolide taaskasutamine ja kaughalduse vahendid). See tähendab, et ka paigatud seadmeid sai tabada, kui võrk oli „lame” ja volitused jagatud.
- Pahavara **nägi välja nagu lunavara**, kuid tegelikult oli see **hävitav** (*wiper*): andmete taastamine ei olnud võimalik ka lunaraha maksmise korral, sest krüpteerimisvõtit ei olnud võimalik ohvriga seostada.

**Õppetunnid.**

- Pahavara mõju sõltub **sihtmärgi võrgu ülesehitusest**. Segmenteerimine ja volituste eraldamine piiravad levikut.
- **Tarneahela risk**: usaldusväärne tarkvarauuendus võib olla ründekanal.
- Mitte kõik „lunavara” ei ole lunavara – mõnikord on eesmärk sabotaaž.
- **Varukoopiad peavad olema võrgust eraldatud**; kui domeeni kontrolleril on kõik andmed hävinud, võib kogu domeeni taastamine olla keeruline.
- Ründed tänapäevases sõjalises ja poliitilises kontekstis võivad tabada ka kolmandaid osapooli.

### 6.3 Näide 3: Colonial Pipeline (2021)

**Mis juhtus.** 2021. aasta mais tabas USA suurimat kütusetorustiku operaatorit Colonial Pipeline lunavararünne (DarkSide grupp, RaaS-mudel). Ettevõte peatas ettevaatusabinõuna torustiku töö, mis tekitas USA idarannikul kütusepuuduse ja paanikaostud. Ettevõte maksis lunaraha ligikaudu 4,4 miljonit USA dollarit, millest ametivõimud suutsid hiljem osa tagasi saada.

**Kuidas töötas.**

- Avalike teadete põhjal pääses ründaja sisse **ühe VPN-konto kaudu, millel puudus mitmefaktoriline autentimine**; parool oli ilmselt mujalt lekkinud.
- Rünnak kasutas **topeltväljapressimist**: enne krüpteerimist viidi andmeid välja.
- Lunavara mõjutas ettevõtte **IT-süsteeme** (arveldus jms), mitte otseselt torustiku juhtimissüsteeme. Ettevõte katkestas tegevuse siiski, kuna ei saanud teenuse eest arveldada ega olla kindel, et ründajad ei ole jõudnud OT-keskkonda.

**Õppetunnid.**

- **MFA on kriitiline**, eriti kaugligipääsul.
- **IT/OT-eraldatus** ja teadmine, millised süsteemid on kriitilised.
- **Kasutamata kontod** tuleb eemaldada ning paroolide taaskasutust vältida.
- Lunavara võib peatada kriitilise taristu ka siis, kui otsest tehnilist mõju tootmisele pole – oluline on **ärijätkuvuse** planeerimine.
- Rünne näitab, kuidas üks nõrk lüli (konto ilma MFA-ta) võib tuua kaasa ühiskondliku mõju.

### 6.4 Näidete võrdlus

|  | WannaCry | NotPetya | Colonial Pipeline |
| --- | --- | --- | --- |
| Aasta | 2017 | 2017 | 2021 |
| Tüüp | Lunavara-uss | Hävitav pahavara (wiper) | Käsitsi juhitud lunavara (RaaS) |
| Sisenemine | SMB haavatavus (EternalBlue) | Tarneahela rünne | VPN-konto ilma MFA-ta |
| Levik | Automaatne, kontrollimatu | Automaatne, volituste abil | Käsitsi, sihitud |
| Peamine õppetund | Paigahaldus | Segmenteerimine, tarneahel | MFA, ärijätkuvus |

## 7. Tuvastamine

### 7.1 Tuvastusmeetodid

- **Signatuuripõhine** – tuntud pahavara tunnused (räsid, mustrid). Kiire ja täpne, kuid ei tuvasta uut pahavara.
- **Heuristiline** ja **käitumispõhine** – hindab tegevust (nt massiline failide ümbernimetamine, ebatavaline protsessipuu).
- **Sandbox-analüüs** – kahtlase faili käivitamine isoleeritud keskkonnas.
- **EDR/XDR** – lõpp-punktide jälgimine ja automaatne reageerimine.
- **Võrguseire** – ebatavaline liiklus, side tuntud C2-aadressidega, DNS-anomaaliad.
- **Logianalüüs ja SIEM** – korrelatsioon eri allikate vahel.
- **Ohuluure** (*threat intelligence*) – kompromiteerimistunnused (IoC), nt räsid, IP-aadressid ja domeenid.

### 7.2 Tüüpilised hoiatusmärgid

- ootamatu failide ümbernimetamine või laiendite muutumine suures mahus;
- arvuti aeglustumine, tundmatud protsessid, kõrge protsessori- või kettakasutus;
- kaitsetarkvara ootamatu väljalülitumine;
- uued administraatorikontod või teenused;
- ebatavalised sisselogimised ööajal või erinevatest asukohtadest;
- suur väljuv andmemaht;
- varjukoopiate ja varukoopiate kustutamise katsed;
- pidev ühendus tundmatu välise serveriga.

## 8. Kaitse

Kaitse peab olema **kihiline** (*defense in depth*): ükski meede üksi ei ole piisav.

### 8.1 Ennetavad meetmed

- **Paigahaldus** – kriitilised paigad kiiresti, eriti avalikele teenustele. Haavatavuste prioriseerimisel arvesta, kas haavatavust aktiivselt ära kasutatakse.
- **Mitmefaktoriline autentimine** kõikidel kaugligipääsudel, administraatorikontodel ja e-postil.
- **Vähimate õiguste põhimõte** – kasutajad ei vaja administraatoriõigusi; administraatoritel eraldi konto igapäevaseks tööks ja haldamiseks.
- **Segmenteerimine** – eralda kasutajavõrk, serverid, haldusvõrk ja varundus.
- **Rakenduste lubamisnimekirjad** (*application allowlisting*) – nt AppLocker, WDAC.
- **Makrode ja skriptide piiramine** – blokeeri makrod internetist pärit dokumentides, piira PowerShelli käivitamist (Constrained Language Mode, logimine).
- **E-posti ja veebi filtreerimine** – pahatahtlike manuste ja linkide blokeerimine.
- **Tulemüürid ja avaliku ründepinna vähendamine** – sulge mittevajalikud teenused (RDP, SMB).
- **Turvateadlikkuse koolitus** ja õngitsussimulatsioonid.
- **Tarkvaravarade haldus** – tea, mis süsteemid on olemas (CMDB, varade register), sest haldamata varasid ei saa kaitsta.
- **Windowsi keskkonnas**: Active Directory kõvendamine, tiered administration, LAPS (kohaliku administraatori paroolide haldus), Protected Users grupp, NTLM piiramine.

### 8.2 Tuvastus ja reageerimine

- EDR kõigil serveritel ja töökohtadel;
- keskne logimine ja SIEM, tuvastusreeglid tüüpilistele tegevustele (nt uute administraatorite lisamine, massiline failimuutmine);
- 24/7 seire või SOC-teenus, kui organisatsiooni võimekus seda lubab;
- regulaarsed intsidendi harjutused (*tabletop exercise*).

### 8.3 Varundus ja taastamine

Varundus on lunavarakaitse **viimane ja kõige olulisem kiht**. Lähtepunktiks on **3-2-1-1-0 reegel**:

- **3** koopiat andmetest;
- **2** erinevat andmekandjat;
- **1** koopia asukohas väljaspool;
- **1** koopia **muutumatu** (*immutable*) või võrgust eraldatud (*air-gapped*);
- **0** vigu – taastamist tuleb regulaarselt **testida**.

Olulised küsimused: Kui kiiresti peame taastama (**RTO**)? Kui palju andmeid võime kaotada (**RPO**)? Kas varukoopiaserver on kasutajate ja domeeni tavaliste volitustega kättesaadav? (Ei tohiks olla.)

## 9. Intsidendi käsitlemine lunavara korral

Kui rünne on avastatud:

1. **Isoleeri** nakatunud seadmed võrgust (ära lülita neid välja, kui mälu analüüs võib olla vajalik – otsusta intsidendimeeskonnaga).
2. **Aktiveeri intsidendiplaan** ja teavita vastutavaid isikuid, juhtkonda ja vajadusel õigusnõustajat.
3. **Teavita** CERT-EE-d (RIA) ning vajadusel politseid ja Andmekaitse Inspektsiooni (isikuandmete rikkumise korral 72 tunni jooksul).
4. **Säilita tõendid** – logid, mälu- ja kettatõmmised, lunakiri.
5. **Tuvasta ulatus ja sisenemispunkt** – ilma selleta võib rünne korduda.
6. **Likvideeri** ründaja ligipääs: lähtesta volitused (sh domeeni teenusekontod, krbtgt), kõrvalda püsivusmehhanismid.
7. **Taasta** süsteemid puhtalt: ehita uuesti või taasta tõestatult puhtast varukoopiast, mitte ei kasuta nakatunud süsteeme.
8. **Õppetunnid** – analüüsi, mis ebaõnnestus, ning uuenda plaani ja meetmeid.

## 10. Seos E-ITS-iga

E-ITS-i baasmeetmed toetavad lunavarakaitset mitmes valdkonnas. Õppijal on soovitatav leida E-ITS-i moodulitest vastavad meetmed ja siduda need käesoleva materjaliga:

- **Pahavarakaitse** – kaitse pahavara vastu lõpp-punktides ja serverites;
- **Varundus ja andmete taastamine** – varundusstrateegia ja taastamise testimine;
- **Paigahaldus ja muudatuste haldus**;
- **Ligipääsuhaldus** ja autentimine;
- **Võrgu turvalisus** – segmenteerimine ja tulemüürid;
- **Logimine ja sündmuste seire**;
- **Turvaintsidentide käsitlemine**;
- **Töötajate teadlikkus**.

*Märkus õpetajale:* täpsed moodulite tähised ja meetmed tasub kontrollida E-ITS-i kehtivast versioonist, et viited oleksid ajakohased.

## 11. Praktilised ülesanded

1. **Juhtumianalüüs.** Vali üks kolmest näitest ja koosta ründe ajajoon (sisenemine → levik → mõju). Märgi iga etapi juures, milline kaitsemeede oleks ründe peatanud.
2. **Riskihindamine.** Hinda näidisorganisatsiooni lunavararisk: ressursid, ründepind, olemasolevad meetmed, mõju ja tõenäosus.
3. **Varundusplaan.** Koosta 3-2-1-1-0 põhimõttel varundusplaan, määra RTO ja RPO ning kirjelda taastamise testimise kord.
4. **Tuvastusreeglid.** Kirjelda kolm SIEM-i tuvastusloogikat, mis annaksid lunavararünde varases etapis märku (nt massiline sisselogimisrike, uus domeeniadministraator, kaitsetarkvara väljalülitamine).
5. **Tabletop-harjutus.** Läbi rollimängu: reedel kell 16.30 teatab töötaja, et failid on krüpteeritud ja ekraanil on lunanõue. Kes teeb mida esimese tunni jooksul? Kellele teatatakse?
6. **Turvaline laboratoorium.** Analüüsi *ohutus, isoleeritud keskkonnas* (virtuaalmasin ilma võrguühenduseta) õpetaja antud kahjutu näidisfaili tegevust ning kirjelda, milliseid jälgi see jätab (protsessid, registrivõtmed, võrguühendused).

## 12. Kontrollküsimused

1. Mille poolest erineb uss viirusest?
2. Miks on lunavara puhul oluline hübriidkrüptograafia ja miks ei ole krüpteeritud andmeid tavaliselt võimalik ilma võtmeta taastada?
3. Mis on topeltväljapressimine ja miks on see muutnud varundusel põhinevat kaitset ebapiisavaks?
4. Millised kaks tegurit tegid WannaCry ründe nii edukaks?
5. Mille poolest erines NotPetya tavapärasest lunavarast?
6. Mis oli Colonial Pipeline'i ründe sisenemispunkt ja milline lihtne meede oleks seda takistanud?
7. Mida tähendab 3-2-1-1-0 reegel?
8. Nimeta viis hoiatusmärki, mis võivad viidata käimasolevale lunavararünnakule.
9. Miks ei tohi varukoopiaserver olla domeeni tavaliste administraatoriandmetega ligipääsetav?
10. Millised on lunavara intsidendi käsitlemise esimesed sammud?

## 13. Soovitatav lisalugemine

- RIA küberturvalisuse aastaraamat ja CERT-EE ülevaated
- ENISA Threat Landscape (aastaaruanne)
- Verizon Data Breach Investigations Report (DBIR)
- CISA Stop Ransomware juhend (#StopRansomware)
- No More Ransom (nomoreransom.org) – tasuta dekrüptorid ja nõuanded
- MITRE ATT&CK – pahavara ja ründegruppide kirjeldused
- Andy Greenberg, *Sandworm* (NotPetya ja selle taust)

## 14. Sõnastik

- **C2 (Command and Control)** – ründaja juhtserver, millega pahavara suhtleb.
- **EDR** – Endpoint Detection and Response, lõpp-punktide tuvastus- ja reageerimislahendus.
- **Exfiltration** – andmete volitamata väljaviimine.
- **IoC** – Indicator of Compromise, kompromiteerimise tunnus.
- **Lateral movement** – külgsuunaline liikumine võrgus.
- **Persistence** – kinnistumine; pahavara püsimine süsteemis.
- **RaaS** – Ransomware-as-a-Service.
- **RPO / RTO** – lubatud andmekao ja taastamisaja piirmäär.
- **Wiper** – andmeid hävitav pahavara.
