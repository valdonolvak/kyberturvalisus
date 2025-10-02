## Küberhügieeni Põhimõtete Süvendatud Analüüs Detailsete Näidetega

### 1. Tarkvara uuendamine (Patch Management)

**Sisu selgitus:** Turvapaikade (*patches*) kiire ja süstemaatiline paigaldamine on organisatsiooni vältimatu kohustus. See protsess peab olema automatiseeritud ja prioriseerima need paigad, mis on seotud kriitiliste haavatavustega, mida aktiivselt rünnatakse (tuntud kui *exploited in the wild*).

**Miks see on ülioluline:** Isegi paari kuu viivitus kriitilise paiga paigaldamisel võib olla katastroofiline, kuna küberkurjategijad loovad koheselt rünnakutarkvara (exploit kits) uute haavatavuste ärakasutamiseks.

**Päriseluline näide: WannaCry Rünnaku Tehniline Detail**

* **Rünnaku tüüp:** Ülemaailmne iseseisvalt leviv **lunavara** (*ransomware*).
* **Haavatavus:** Rünnak kasutas ära **Microsoft Windowsi SMBv1 (Server Message Block) võrguprotokollis** avastatud kriitilist turvaauku, mille koodnimi oli **EternalBlue**. SMBv1-l oli puudus, mis võimaldas ründajal saata spetsiaalselt valmistatud paketi ja seejärel täita haavatavas masinas **suvalist koodi** (ehk **lunavara**).
* **Ebaõnnestunud põhimõte:** Paljud organisatsioonid jätsid paigaldamata **MS17-010 turvapaiga**, mille Microsoft oli avaldanud kaks kuud varem, märtsis 2017.
* **Sündmuste ahel ja tagajärg:** Kuna paika polnud, pääses lunavara arvutisse ja kasutas EternalBlue’d, et **automaatselt levida teistele uuendamata arvutitele** samas võrgus. See iseseisev levik tõi kaasa tervete haiglate ja tehaste süsteemide krüpteerimise.

---

### 2. Viirusetõrje ja pahavaratõrje (Antivirus and Anti-Malware)

**Sisu selgitus:** Organisatsiooni tõrjelahendus peab olema **EDR (*Endpoint Detection and Response*)** tasemel. See suudab jälgida protsesside käitumist ja tuvastada anomaaliaid (nt programmi ootamatu katse krüpteerida faile või ligi pääseda paroolide salvestusalale), isegi kui pahavara kood on täiesti uus.

**Miks see on ülioluline:** Uuemad ründed kasutavad **failivaba (fileless) pahavara** ja **polümorfset pahavara** (mis muudab pidevalt oma koodi), et traditsioonilisest allkirjapõhisest viirusetõrjest mööda hiilida.

**Päriseluline näide: EDR-i Tuvastusmehhanism**

* **Rünnaku tüüp:** **Nuhkvara** (*Spyware*) sissetung.
* **Haavatavus:** Töötaja laadis veebist alla legaalse välimusega, kuid **pakendatud (wrapped) pahavaraga** tarkvara, millel polnud varem pahavara allkirja.
* **Kaitsemehhanism:** Organisatsioon kasutas kaasaegset EDR-lahendust.
* **Sündmuste ahel ja tagajärg:** Kui tarkvara paigaldati ja käivitati, hakkas EDR koheselt jälgima selle käitumist. Kuigi traditsiooniline kaitse ei peataks, tuvastas EDR-i **käitumuslik analüüs**, et see programm püüdis käivitada **PowerShelli** (*Windowsi administraatori tööriist*) kahtlase käsujadaga, mis sarnanes **mälu *dumping*** operatsiooniga (administraatori paroolide mäluvõtmine). EDR **blokeeris PowerShelli käivituse** ja **tõkestas** nuhkvara enne, kui see jõudis paroolid serverisse saata.

---

### 3. Tulemüüride kasutamine (Firewall Usage)

**Sisu selgitus:** Tulemüüride peamine roll on **võrgu segmenteerimine**. See tähendab, et võrk jaotatakse isoleeritud osadeks (**VLAN-id**), et piirata rünnete levikut (nn *lateral movement*). Tulemüür peaks olema konfigureeritud **Zero Trust põhimõttel**, kus liiklust lubatakse ainult *spetsiifiliste* IP-aadresside ja portide vahel.

**Miks see on ülioluline:** Kui ründaja kompromiteerib ühe arvuti (nt läbi õngitsuse), saab ta ilma tulemüüri segmentimiseta vabalt skaneerida ja rünnata teisi võrgus olevaid servereid ja tööjaamu.
Absoluutselt. Konkreetse näite juurde ettevõtte nime lisamine on keeruline, kuna enamik ohvreid, kes tulevad toime küberintsidentidega, ei avalikusta kõiki turvadetaile. Küll aga saan ma pakkuda teile **tõepärase ja detailse näite** just sellisest olukorrast, mis on sarnane paljudele reaalselt toimunud intsidentidele, ning lisada täpse teabe tulemuse ja finantsmõju kohta.


**Miks see on ülioluline:** Kui ründaja kompromiteerib ühe võrguosa (nt läbi õngitsuse), **ei saa ta automaatselt levida teistesse kriitilistesse süsteemidesse**. See piirab ründe ulatust (*containment*) ja annab turvameeskonnale aega reageerida.

**Päriseluline detailne näide:**

* **Ettevõtte valdkond:** Globaalne pakiveo- ja logistikaettevõte **"GlobalTrans"**.
* **Rünnaku tüüp:** Töötaja arvutisse sattunud **lunavara (nt Ryuk-tüüpi)**.
* **Haavatavus:** Üks kontoritöötaja, kes vastutas finantsaruandluse eest, klõpsas rämpspostiga saabunud pahavaralisel manusel. See käivitas lunavara, mis püüdis koheselt levida sisevõrgus.
* **Kaitsemehhanism:** GlobalTrans oli rakendanud ranget **võrgu segmenteerimise poliitikat**:
    * **VLAN 10 (Kontorivõrk):** Finants-, müügi- ja turundustöötajate arvutid (suur risk).
    * **VLAN 50 (Operatsioonivõrk):** Kriitilised lao- ja tarneahela serverid (MIS) ning pakkide sorteerimissüsteemid (väga kõrge kriitilisus).
    * Nende kahe VLAN-i vahele oli paigaldatud **sisemine tulemüür** reegliga **"deny-all-except"** (keela kõik, välja arvatud rangelt vajalikud sideprotokollid).
* **Sündmuste ahel ja tulemus:**
    1.  Lunavara **krüpteeris kiiresti** kõik failid **VLAN 10** arvutites ja võrguketastes (nt finantsaruanded).
    2.  Pahavara proovis seejärel skaneerida sisevõrku ja rünnata **logistikaservereid (VLAN 50)**, et seiskada kriitiline äritegevus.
    3.  **Tulemüür analüüsis** neid ründepakette ja tuvastas, et tegemist oli **volitamata SMB (Server Message Block)** liiklusega (mis oli keelatud VLAN 10 ja VLAN 50 vahel), ja **blokeeris selle liikluse koheselt**.
    4.  **Tulemus:** **Logistikaserverid jäid puutumata** ja pakkide sorteerimine/vedu sai **tööd jätkata**. Kuigi kontoritöötajate süsteemid olid rivist väljas ja finantsaruanded tuli taastada varukoopiatest (aeg: 2 päeva), **välditi täielikku seiskumist**, mille maksumus oleks olnud üle 5 miljoni euro päevas. Tänu segmenteerimisele jäi **kahju piirduma administratiivse poolega**, mitte ei levinud operatsioonidesse.
---

## Ryuk-tüüpi Lunavara Olemus

**Ryuk** on tuntud oma äärmise agressiivsuse ja võime poolest põhjustada ettevõtetele tohutut kahju. See on **suunatud lunavara** (*targeted ransomware*), mis tähendab, et ründajad ei levita seda pimesi, vaid kasutavad seda pärast hoolikat eeltööd ja võrku sisenemist. Ryuki peamine sihtmärk on alati olnud **kõrge väärtusega organisatsioonid** (nt suured transpordiettevõtted, haiglad, meedia- ja tehnoloogiaettevõtted).

### Ryuki Rünnaku Etapid ja Eripärad:

1.  **Eelnev luure ja sisenemine:** Erinevalt WannaCry'st ei pääse Ryuk süsteemi ühe klõpsuga. Ründajad (tavaliselt suured organiseeritud kuritegelikud rühmitused) kasutavad esmalt muid vahendeid (nt nuhkvara nagu **Emotet** või **TrickBot**), et pääseda võrku ja viia läbi **sisevõrgu luure**. Nad püüavad leida administraatori paroole, kaardistavad võrgustruktuuri ja tuvastavad kõik kriitilised serverid.
2.  **Sihtmärkide tuvastamine:** Ründajate eesmärk on leida süsteemid, mille seiskamine tekitab kõige suuremat rahalist kahju – näiteks **domeenikontrollerid, failiserverid, varundussüsteemid ja operatsioone haldavad serverid** (nagu logistikaettevõtte puhul).
3.  **Hävitav käivitamine:** Ryuk käivitatakse alles siis, kui ründajad on veendunud, et nad on saavutanud **maksimaalse leviku** ja avastanud **kõige väärtuslikumad sihtmärgid**. Ryuk on eriti efektiivne, kuna see on võimeline **otsima ja krüpteerima kõiki võrgukettaid**, sh paljusid ühendatud varundussüsteeme.
4.  **Tulemus:** Krüpteerimise tulemusel on süsteemid täielikult rivist väljas ja ettevõte peab seisma silmitsi kas **suure lunaraha** maksmisega (Ryuki nõudmised ulatuvad sageli sadadesse tuhandetesse või miljonitesse eurodesse) või pikaajalise ja kuluka taastumisega.

### Miks on võrgu segmenteerimine Ryuki vastu elutähtis?

Logistikaettevõtte näites aitas tulemüür Ryuki vastu täpselt seetõttu, et:

* **See blokeerib lateraalse liikumise:** Kui Ryuk käivitati kontorivõrgus (**VLAN 10**), püüdis see sisevõrgu luure käigus tuvastatud logistikaserveritele (**VLAN 50**) juurde pääseda. Kuna nende kahe võrgu vahel oli **sisemine tulemüür**, oli **kriitiline suhtlus keelatud**. Tulemüür ei lubanud Ryukil kasutada levinud võrguprotokolle (nt SMB), mida ta levikuks vajanuks.
* **See tekitab tõkke:** Ryuki eesmärk on levida kiiresti ja krüpteerida kõik, mis on võrgus nähtav. Võrgu segmenteerimine tekitab ründajale **ootamatu tõkke**, mis ei lase lunavaral oma hävitavat potentsiaali realiseerida – see jääb piiratud alale.
* **See vähendab RTO-d:** Isegi kui finantssüsteemid vajasid taastamist, said **logistika operatsioonid koheselt tööd jätkata**. See tähendas, et ettevõte ei kaotanud päevi kriitilises äritegevuses, mis on Ryuki rünnaku puhul alati põhiline eesmärk.

Ryuk ei ole lihtsalt pahavara; see on **kogu ettevõtte tarneahelat ja kriitilisi süsteeme halvav ärimudel**. Seetõttu ongi **segmenteerimine** ja **sisevõrgu kontroll** selle vastu kõige olulisem füüsilise tulemüüri järel.
---

### 4. Paroolide haldus (Password Management)

**Sisu selgitus:** Tugeva ja unikaalse parooli pikkus peaks olema **vähemalt 12–16 märki** ja need ei tohiks sisaldada ühtegi personaalset infot. **Paroolihaldurite** juurutamine on kriitiline, sest need mitte ainult ei loo keerulisi paroole, vaid ka kaitsevad *phishing*-rünnakute eest, kuna paroolihaldur täidab parooli automaatselt ainult õige (kontrollitud) veebisaidi aadressi puhul.

**Miks see on ülioluline:** Paroolide korduvkasutus muudab **Credential Stuffing** rünnakud (varastatud paroolide proovimine kõikides teenustes) äärmiselt ohtlikuks.

**Päriseluline näide: Credential Stuffing ja Andmeleke**

* **Rünnaku tüüp:** **Credential Stuffing** (identimisteabe ärakasutamine).
* **Haavatavus:** Ettevõtte töötaja oli kasutanud oma **töö-e-posti parooli** identselt ka ühel vähem turvalisel e-kaubanduse veebilehel.
* **Sündmuste ahel ja tagajärg:** See e-kaubanduse veebileht sattus andmelekkesse ja ründajad said kätte tuhandeid e-posti aadresse/paroolipaare. Ründajad kasutasid seda nimekirja, et **automaatselt proovida sisse logida organisatsiooni VPN-i, pilveteenustesse ja e-posti kontodesse**. Kuna töötaja parool sobis, **sai ründaja ligipääsu töötaja töömeilile** ja kasutas seda edasiste õngitsusrünnakute levitamiseks organisatsiooni sees.

---

### 5. Mitmeastmeline autentimine (MFA)

**Sisu selgitus:** MFA tagab, et ründajal on vaja juurdepääsuks kahte erinevat tõestusvahendit. Tugevaimad MFA-vormid on **U2F (Universal 2nd Factor) turvavõtmed** (nt YubiKey) või **rakenduspõhised koodid** (nt Google Authenticator, Microsoft Authenticator), mitte SMS-koodid, mida on lihtsam pealt kuulata (*SIM-swapping*).

**Miks see on ülioluline:** MFA on kõige tõhusam kaitse, kuna isegi **õngitsusega varastatud parool** (mis on krüpteerimata tekst) on ilma teise faktorita kasutu.

**Päriseluline näide: MFA Peatab Suunatud Õngitsusrünnaku**

* **Rünnaku tüüp:** **Spetsiifiline õngitsus (*Spear Phishing*)** finantsjuhile.
* **Haavatavus:** Ründajad saatsid finantsjuhile äärmiselt usaldusväärse võlts-sisselogimislehe, mis kogus kokku tema **töökonto parooli**.
* **Kaitsemehhanism:** Organisatsioon oli rakendanud **MFA**-d kõigil sisselogimistel, kasutades selleks **kinnitust telefoniäpis**.
* **Sündmuste ahel ja tagajärg:** Ründaja püüdis varastatud parooliga sisse logida ettevõtte pilvandmete repositooriumi (nt SharePoint/OneDrive). Süsteem saatis finantsjuhi telefonile **MFA kinnituspäringu**. Kuigi parool oli õige, **keelas** finantsjuht oma telefonis tundmatu sisselogimiskatse. Ründaja **jäeti süsteemist välja**, takistades juurdepääsu tundlikele finantsdokumentidele.

---

### 6. Andmete varundamine (Data Backup)

**Sisu selgitus:** Varundamine on kriitiline **taastemehhanism** ja see peab olema **testitud**. Eriti oluline on *immutable* (muutmatu) varundus, mis takistab pahavaral varukoopiate krüpteerimist või kustutamist. Varundus peab olema loogiliselt või füüsiliselt isoleeritud (*offline* või *air-gapped*) primaarsest võrgust.

**Miks see on ülioluline:** See on ainus garantii, et lunavara rünnak, tulekahju või riistvara rike ei too kaasa püsivat andmekadu ja äritegevuse seiskumist.

**Päriseluline näide: Immutable Varunduse Edu**

* **Rünnaku tüüp:** Ränk ja laiaulatuslik **lunavararünnak** (*ransomware*).
* **Haavatavus:** Pahavara pääses süsteemi ja hakkas krüpteerima kõiki võrgukettaid, sealhulgas **kõiki võrgus kättesaadavaid varukoopiaid**.
* **Kaitsemehhanism:** Kriitilistest andmetest oli loodud koopia **Immutable Storage'is** (muutumatu varundus). See tähendab, et varundusserver oli seadistatud nii, et lunavara ei saanud varukoopiaid muuta ega kustutada, isegi administraatori õigustega.
* **Sündmuste ahel ja tagajärg:** Lunavara krüpteeris kõik tootmise ja kliendihalduse süsteemid. Tänu *immutable* varundusele oli ettevõttel olemas terve, puutumatu koopia kõigist andmetest. Ettevõte **keeldus lunaraha maksmisest** ja **taastas kogu oma IT-keskkonna puutumatutest varukoopiatest 48 tunni jooksul**.

---

### 7. Turvateadlikkuse tõstmine (Security Awareness Training)

**Sisu selgitus:** Teadlikkuse koolitus peab olema pidev, pakkudes ajakohast teavet uute ohtude (nt **AI-ga loodud hääle- või pildipettused**) kohta. Koolitus peaks sisaldama ka **praktilisi simulatsioone** (nt õngitsustestid) ja keskenduma sellele, et töötajad oskaksid **kahtlustada, kontrollida ja teatada** kahtlastest sündmustest.

**Miks see on ülioluline:** Inimfaktor on küberkuritegevuse kõige levinum sihtmärk. Kõige kallimad tehnilised lahendused on kasutud, kui töötaja annab ründajale oma parooli.

**Päriseluline näide: Häälepettus ja Kiire Teavitamine**

* **Rünnaku tüüp:** **Häälega sotsiaalne manipulatsioon** (CEO pettus).
* **Haavatavus:** Finantsosakonna töötaja sai kõne, mis kõlas **täpselt nagu tegevjuhi hääl** (võimalik, et tehisintellektiga loodud koopia), nõudes kiiret konfidentsiaalset makset.
* **Kaitsemehhanism:** Töötaja oli just läbinud teadlikkuse koolituse, mis rõhutas, et **kriitilised tehingud tuleb alati kontrollida läbi teise kanali** (nt e-post või otsekõne).
* **Sündmuste ahel ja tagajärg:** Töötaja tundis kõne tooni ja kiiruse järgi kahtlust. Ta saatis tegevjuhile e-posti teel kontrolliva küsimuse ja tegevjuht kinnitas, et ta pole mingit makset palunud. **Töötaja ennetav tegevus päästis organisatsiooni 50 000 euro suurusest pettusest**.

---

### 8. Ligipääsude haldamine (Access Control)

**Sisu selgitus:** Ligipääsude haldamine põhineb **vähima privileegi (*Least Privilege*)** põhimõttel. Iga töötaja, rakendus ja teenus saab ainult hädavajalikud õigused oma töö tegemiseks. See nõuab regulaarset **õiguste auditeerimist** ja **rollipõhiseid juurdepääsu reegleid (RBAC)**.

**Miks see on ülioluline:** See **piirab ründaja tegevusulatust**. Kui ründaja kompromiteerib ühe madala privileegiga konto, ei saa ta selle kaudu ligi kõrgelt turvatud andmetele ega serveritele.

**Päriseluline näide: Vähima Privileegi Vältimine Andmelekkest**

* **Rünnaku tüüp:** **Sisemine oht** (endine töötaja).
* **Haavatavus:** Tarkvaraarendaja lahkus ettevõttest ja tema ligipääs **koodihoidlale** (nt GitHub) deaktiveeriti. Kuid tema ligipääs vana projektiga seotud **kliendiandmebaasile** oli unustatud eemaldada.
* **Kaitsemehhanism:** Nõuetekohane ligipääsude haldus ja vähima privileegi reegel.
* **Sündmuste ahel ja tagajärg:** Kuna endisel töötajal oli jätkuvalt ligipääs vanale andmebaasile, **kopeeris ta sealt kliendiandmeid**, et kasutada neid uues töökohas. Kui organisatsioon oleks rakendanud range **RBAC-poliitika**, mis eemaldanuks ligipääsud automaatselt pärast rolli lõppemist, oleks andmete kopeerimine olnud võimatu.

---

### 9. Seadmete turvalisus (Device Security)

**Sisu selgitus:** Seadmete turvalisus nõuab **kogu kõvaketta krüpteerimist** kõikidel mobiilsetel seadmetel (sülearvutid, telefonid). Lisaks peab organisatsioon kasutama **MDM (Mobile Device Management) lahendust**, mis võimaldab seadme varguse või kaotuse korral selle **kaugkustutamist (remote wipe)**.

**Miks see on ülioluline:** Füüsiline vargus on otsene andmeleke. Krüpteerimine on ainus usaldusväärne viis tagada, et varas ei saa andmeid seadmelt lugeda.

**Päriseluline näide: Krüpteerimise Päästev Mõju**

* **Rünnaku tüüp:** **Füüsiline vargus** (sülearvuti vargus).
* **Haavatavus:** Tervishoiuasutuse administraatori sülearvuti varastati konverentsilt. Arvuti sisaldas krüpteerimata tundlikke **patsientide andmeid** (GDPR).
* **Kaitsemehhanism:** (Vastupidine näide) Seade **ei olnud krüpteeritud**. Varas sai kõvaketta eemaldada ja andmetele ligi. Kui aga oleks olnud aktiivne **BitLocker** (Windowsi krüpteerimislahendus), oleks varas saanud ainult krüpteeritud ja loetamatu info.
* **Tagajärg:** Krüpteerimise puudumine tõi kaasa **andmelekkest teavitamise kohustuse** (Andmekaitse Inspektsioonile) ja potentsiaalselt hiiglasliku trahvi isikuandmete kaitse rikkumise eest. Krüpteerimine oleks selle ära hoidnud.

---

### 10. Võrgu turvalisus (Network Security)

**Sisu selgitus:** Võrgu turvalisus eeldab lisaks segmentimisele ka **IDS/IPS (Intrusion Detection/Prevention System)** lahenduste kasutamist, mis jälgivad võrguliiklust anomaaliate ja rünnaku mustrite suhtes. Kõik ühendused väljapoole peavad olema kaitstud **krüpteeritud VPN-ga** ja läbima range kontrolli.

**Miks see on ülioluline:** See tagab kaitse nii seestpoolt tulevatele (nakatunud arvuti levib) kui ka väljastpoolt tulevatele ohtudele (skaneerimine, DDoS).

**Päriseluline näide: IDS Peatab Brute-Force Rünnaku**

* **Rünnaku tüüp:** **Brute-Force sisselogimisrünnak** (jõuline paroolide katsetamine).
* **Haavatavus:** Ründajad üritasid sadade tuhandete paroolikombinatsioonidega sisse murda veebiserveri administraatori kontole.
* **Kaitsemehhanism:** Võrku oli paigaldatud **IPS (Intrusion Prevention System)**.
* **Sündmuste ahel ja tagajärg:** IPS tuvastas lühikese aja jooksul **ühe IP-aadressi poolt tehtud ebaloomulikult suure sisselogimiskatsete arvu**. IPS klassifitseeris selle tegevuse **Brute-Force ründeks** ja **blokeeris automaatselt ründaja IP-aadressilt tuleva liikluse** juba pärast kümmet ebaõnnestunud katset, enne kui nad oleksid jõudnud õige parooli ära arvata.

---

### 11. Turvapoliitika ja protseduurid (Security Policy and Procedures)

**Sisu selgitus:** Turvapoliitika peab olema **ametlikult kinnitatud** organisatsiooni juhtkonna poolt. See peab olema **juriidiliselt siduv** ja määrama karistused selle rikkumise eest. Turvaprotseduurid (nt varunduse kontrollimise kord, krüpteerimise kohustus) peavad olema **detailne juhend**, mida iga töötaja peab järgima.

**Miks see on ülioluline:** Poliitika ja protseduurid on **vastavuse (compliance)** ja **vastutuse** aluseks. Need vähendavad subjektiivsust turvalisuse tagamisel ja tagavad, et kõik töötajad järgivad samu standardeid.

**Päriseluline näide: Andmete Klassifitseerimise Poliitika Eiramine**

* **Rünnaku tüüp:** Inimlik viga ja turvapoliitika puudumine.
* **Haavatavus:** Organisatsioonis puudus selge **Andmete Klassifitseerimise Poliitika**. Üks turundustöötaja salvestas **konfidentsiaalsed tootearendusplaanid** (mis oleks pidanud olema krüpteeritud) **avalikult ligipääsetavasse pilvekausta**, arvates, et see on "lihtsalt tööfail".
* **Sündmuste ahel ja tagajärg:** Kuigi otsest häkkimist ei toimunud, avastas keegi internetist kausta ja **lekkinud info sattus konkurentide kätte**. Kui oleks olnud selge poliitika, mis oleks määranud need plaanid "Ärisaladuse" klassi alla, oleks töötaja olnud **kohustatud** need krüpteerima ja salvestama ainult sisemisse serverisse, vältides leket.

---

### 12. Küberintsidentide käsitlemine (Incident Response)

**Sisu selgitus:** IRP on **operatiivdokument**, mis peab olema **läbi harjutatud ja ajakohane**. See määratleb sündmuste ahela kriitilised sammud: **Tuvastamine, Ohjeldamine (Containment), Likvideerimine (Eradication), Taastumine (Recovery)** ja **Õppetundide analüüs**. IRP peab sisaldama ka **kommunikatsiooniplaani** meedia, õiguskaitseorganite ja klientide teavitamiseks.

**Miks see on ülioluline:** See vähendab kaost ja teeb kriisist kontrollitud sündmuse. Valmisolek lühendab taastumisaega ja minimeerib nii rahalist kahju kui ka reputatsioonikahju.

**Päriseluline näide: IRP Peatab Andmeleke ja Kontrollib Mainekahju**

* **Rünnaku tüüp:** **Andmeleke** (nt SQL-i süstimise kaudu).
* **Haavatavus:** Ründaja pääses ligi veebiserverile ja hakkas sealt andmebaasist infot kopeerima.
* **Kaitsemehhanism:** Organisatsioonis oli olemas ja läbi harjutatud IRP.
* **Sündmuste ahel ja tagajärg:**
    1.  **Tuvastamine:** Turvameeskond tuvastas anomaalse andmeliikluse IRP protseduuri abil.
    2.  **Ohjeldamine (Containment):** IRP nägi ette, et kahtlane server tuleb **koheselt võrgust isoleerida**. See peatati **15 minuti jooksul**, takistades ründajal kogu andmebaasi kopeerimise.
    3.  **Taastumine:** Andmed taastati varukoopiast.
    4.  **Kommunikatsioon:** IRP nägi ette, et **ametiasutusi (CERT-EE, Andmekaitse Inspektsioon) teavitatakse 72 tunni jooksul** ja avalikkusele suunatakse rahulik, faktipõhine teadaanne. **Organiseeritud tegevus ja kiire teavitamine** vähendasid trahvide ja reputatsioonikahju ohtu.
