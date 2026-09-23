Peatükid **11–15**. 

# 11. Code Injection ehk koodi süstimine

**Code injection ehk koodi süstimine** tähendab olukorda, kus ründaja suudab panna programmi käivitama andmeid koodina. Tavapärases programmis peaks kasutaja sisend olema lihtsalt andmed. Näiteks kui kasutaja kirjutab otsingukasti sõna `server`, peaks programm käsitlema seda sõnana, mitte uue programmina. Code injection'i korral tekib aga olukord, kus programm ei erista piisavalt andmeid ja täidetavat koodi. Selle tulemusena võib ründaja mõjutada programmi loogikat või panna selle käivitama soovimatuid käske. Koodi süstimine võib esineda väga erineval kujul. Näiteks võib tegemist olla skripti süstimise, JavaScripti süstimise, SQL-i süstimise, interpreteeritava keele koodi süstimise või operatsioonisüsteemi käsu süstimisega. Operatsioonisüsteemi tasandil on eriti oluline olukord, kus süsteemikomponent käivitab välisest allikast saadud koodi. Mõiste **interpreeter (interpreter)** tähendab programmi, mis loeb ja täidab teises keeles kirjutatud käske või skripti. Näiteks JavaScripti interpreeter käivitab JavaScripti ning PowerShelli interpreeter PowerShelli käske. Kui ründaja suudab kontrollida interpreetrile antavat sisendit, võib andmetest saada käivitatav kood. Seetõttu on code injection üks olulisemaid probleeme rakenduste ja operatsioonisüsteemi komponentide piiril.

## Kuidas code injection tehniliselt tekib?

Turvalises programmis peaks andmete ja koodi vahel olema selge piir:

```text
Kasutaja sisend
       │
       ▼
      ANDMED
       │
       ▼
Programm töötleb andmeid
```

Vigases programmis võib tekkida olukord:

```text
Kasutaja sisend
       │
       ▼
Programm ühendab sisendi koodiga
       │
       ▼
Interpreeter
       │
       ▼
ANDMED muutuvad KOODIKS
       │
       ▼
Soovimatu tegevus
```

Näiteks võib programm võtta kasutaja antud väärtuse ja lisada selle skripti sisse. Kui sisendit ei valideerita ega kodeerita, võib ründaja mõjutada skripti struktuuri.

Siin tuleb eristada **input validation'i ehk sisendi valideerimist** ja **output encoding'ut ehk väljundi kodeerimist**. Valideerimine kontrollib, kas sisend vastab lubatud vormile. Kodeerimine muudab andmed selliseks, et neid ei tõlgendataks kogemata koodina.

Code injection võib tekkida ka siis, kui rakendus kasutab dünaamiliselt `eval`-tüüpi funktsiooni. Selline funktsioon võtab stringi ja interpreteerib selle programmina. Kui string pärineb kasutajalt, võib tulemuseks olla väga tõsine turvaprobleem.

## Mida ründaja pärast code injection'i kasutada võib?

Kui süstimine toimub veebibrauseri kontekstis, võib ründaja mõjutada kasutaja brauseris töötavat koodi.

Kui süstimine toimub serveris, võib ründaja saada serveriprotsessi õigused.

Kui haavatav komponent töötab administraatori või süsteemiõigustes, võib koodi käivitamine viia kogu süsteemi kompromiteerimiseni.

```text
Code Injection
      │
      ▼
Koodi käivitamine
      │
      ▼
Rakenduse/protsessi õigused
      │
      ├── andmete lugemine
      ├── andmete muutmine
      ├── failide loomine
      └── järgmise haavatavuse kasutamine
```

Oluline on mõista, et code injection ei tähenda alati automaatselt administraatoriõiguste saamist. Mõju sõltub sellest, millise kasutaja või teenuse õigustes süstitud kood töötab.

### Windows

**CVE-2021-40444 – MSHTML**

Windowsi MSHTML-komponendi RCE-haavatavus võimaldas spetsiaalselt koostatud Microsoft Office'i dokumendi kaudu käivitada pahatahtlikku koodi. Microsoft dokumenteeris, et 2021. aasta augustis täheldati selle kasutamist reaalsetes rünnetes. Ründeahel kasutas pahatahtlikku ActiveX-komponenti ning nõudis teatud juhtudel kasutaja dokumendi avamist. Microsoft soovitas turvapaiga paigaldamist ning kirjeldas ka Office'i attack-surface-reduction kaitsemeetmeid.

**CVE-2020-0601 – Windows CryptoAPI**

Windows CryptoAPI ehk `crypt32.dll` sertifikaadi valideerimise viga ei olnud klassikaline shell injection, kuid selle kaudu sai ründaja mõjutada seda, kuidas Windows hindas ECC-sertifikaate. Microsoft kirjeldas, et pahatahtlik fail võis näida olevat usaldusväärselt allkirjastatud. See näide on oluline, sest näitab, et "koodi süstimine" ei pea alati olema sõna-sõnalt käsu lisamine; usaldusmudeli manipuleerimine võib samuti viia pahatahtliku koodi käivitamiseni.

### Linux

Linuxis võib code injection esineda näiteks süsteemiteenuses, skriptis, haldustööriistas või interpreteeritavas konfiguratsioonis.

**CVE-2021-41773** Apache HTTP Serveris on hea näide, kus path traversal võis koos CGI kasutamisega viia kaugkoodi käivitamiseni. Tegemist ei olnud puhta "eval"-tüüpi süstimisega, kuid kasutaja kontrollitud päringu ja serveripoolse kooditäitmise vahel tekkis turvapiiri rikkumine.

**CVE-2021-42013** oli Apache HTTP Serveri eelmise paranduse ebapiisav lahendus. Path traversal võis teatud konfiguratsioonis koos CGI-ga võimaldada RCE-d.

### macOS

**CVE-2020-9852** macOS-i kernelis oli use-after-free, mille abil võis rakendus teatud tingimustel käivitada koodi kerneliõigustes. See ei ole klassikaline interpreteri injection, kuid on näide sellest, kuidas sisendi kaudu saavutatud kontroll võib lõppeda koodi käivitamisega kõrgemas turvakontekstis.

**CVE-2021-30713** oli macOS-i permissions-probleem, mille puhul pahatahtlik rakendus võis Privacy Preferences piirangutest mööduda. Apple parandas selle parema valideerimisega ning CISA on selle haavatavuse lisanud Known Exploited Vulnerabilities kataloogi.

### Android

Androidis on code injection'iga seotud probleemid sageli seotud süsteemikomponentide, skriptide või native-koodi töötlemisega.

**CVE-2021-0397** Androidi SDP-komponendis oli double-free ehk sama mäluala topeltvabastamise viga. NVD kirjeldab, et seda võis kasutada süsteemi kompromiteerimiseks ja kaugkoodi käivitamiseks ilma täiendavate õiguste või kasutaja tegevuseta.

**CVE-2020-0423** Androidi Binderi komponendis võimaldas spetsiaalselt koostatud faili abil lokaalsel ründajal teatud tingimustel käivitada koodi privilegeeritud protsessi kontekstis. Android klassifitseeris selle EoP-na ehk õiguste suurendamisena.

### iOS

**CVE-2020-27930** FontParseris oli memory corruption probleem. Pahatahtlikult koostatud fondi töötlemine võis põhjustada suvalise koodi käivitamise. Apple parandas probleemi parema sisendi valideerimisega.

**CVE-2021-30737** ASN.1 dekooderis oli mälurikkumise probleem, mille kaudu võis pahatahtlikult koostatud sertifikaat põhjustada suvalise koodi käivitamise. Viga parandati Apple'i süsteemiuuendustes.

## Kuidas riski vähendada?

Kõige tähtsam on hoida andmed ja kood rangelt eraldatud. Kasutaja sisendit ei tohi automaatselt käsitleda skripti või programmina. Vältida tuleb dünaamilist koodikäivitust, kui selleks pole väga konkreetset vajadust. Sisend tuleb valideerida ning kasutada lubatud väärtuste loendeid. Süsteemikomponendid peavad töötama vähimate õigustega. Rakendused, mis töötlevad väliseid faile või võrguandmeid, peaksid kasutama sandbox'i. Samuti tuleb operatsioonisüsteemid ja rakendused regulaarselt paigata.

---

# 12. Path Traversal ehk teekonna läbimine

**Path traversal ehk path traversal attack** tähendab olukorda, kus kasutaja kontrollitud failitee abil püütakse pääseda väljapoole seda kataloogi, millele rakendus pidi ligipääsu andma.

Seda nimetatakse ka **directory traversal** või **path traversal** ründeks.

Operatsioonisüsteemis moodustavad failid ja kataloogid hierarhilise struktuuri. Näiteks:

```text
/var/www/
├── index.html
├── images/
│   └── logo.png
└── documents/
    └── manual.pdf
```

Kui rakendus peaks lubama kasutajal lugeda ainult `documents` kataloogi faile, ei tohiks kasutaja saada lugeda `/etc/passwd`, rakenduse konfiguratsioonifaili või mõnda muud kaitstud ressurssi.

Path traversal tekib siis, kui rakendus usaldab kasutaja antud failiteed liiga palju.

Üks oluline erimärk on `..`, mis tähendab failisüsteemis vanemkataloogi. Kui programm liidab kasutaja sisendi otse failiteega, võib kasutaja sisend muuta lõpptulemust nii, et programm liigub lubatud kataloogist välja.

### Kuidas path traversal tehniliselt tekib?

Turvaline loogika:

```text
Lubatud kataloog
      │
      ▼
/var/www/documents/
      │
      ▼
manual.pdf
```

Vigane loogika:

```text
Kasutaja sisend
      │
      ▼
failitee ühendamine
      │
      ▼
/var/www/documents/ + kasutaja sisend
      │
      ▼
normaliseerimata tee
      │
      ▼
lubatud kataloogist väljapoole
```

Tõsisem probleem tekib siis, kui rakendus ei **normaliseeri (normalize)** failiteed enne kontrollimist.

Normaliseerimine tähendab tee viimist standardkujule, kus näiteks suhtelised komponendid on lahendatud.

Samuti ei piisa alati ainult stringi kontrollimisest. Sümboolsed lingid ehk **symbolic links** võivad suunata ühe failitee hoopis teise asukohta.

## Mida ründaja teha võib?

Kõige tavalisem tagajärg on tundlike failide lugemine.

Serveris võivad huvipakkuvad olla näiteks konfiguratsioonifailid, API-võtmed, sertifikaadid või kasutajakontode andmed.

Mõnel juhul võib path traversal lubada ka failide kirjutamist.

```text
Path Traversal
      │
      ├── failide lugemine
      │
      ├── konfiguratsiooni lugemine
      │
      ├── failide muutmine
      │
      └── koos teise veaga → koodi käivitamine
```

### Windows

Windowsis tuleb arvestada nii `\` kui `/` tüüpi teekondadega, draivitähtedega, UNC-teedega ja sümboolsete linkidega.

**CVE-2021-40444** sisaldas Windowsi ründeahelas ka pahatahtliku dokumendi ja välise sisu töötlemist. Microsoft dokumenteeris selle aktiivse ründe ning CISA lisas CVE KEV kataloogi.

**CVE-2020-0601** on path traversal'ist erinev, kuid hea näide sellest, kuidas Windowsi komponent peab töötlema välisest allikast pärinevaid andmeid ilma usalduspiire valesti rakendamata.

### Linux

**CVE-2021-41773 – Apache HTTP Server**

Apache HTTP Server 2.4.49 path normalization'i probleem võimaldas päringuga liikuda väljapoole dokumentide juurkataloogi. Apache teatas, et probleem oli reaalselt kasutuses ning teatud konfiguratsioonis võis CGI kasutamise korral tekkida RCE.

**CVE-2021-42013**

Apache 2.4.50 parandus ei kõrvaldanud probleemi täielikult. Uus CVE võimaldas samuti path traversal'i ning teatud konfiguratsiooni korral RCE-d. CVE mõjutab Apache 2.4.49 ja 2.4.50.

Need kaks näidet on väga head selleks, et mõista, miks "parandus" ei pruugi alati kogu turvaprobleemi kõrvaldada.

### macOS

macOS kasutab UNIX-i failisüsteemi ning seetõttu kehtivad talle samad üldised path traversal'i põhimõtted.

**CVE-2022-26707** macOS Monterey 12.4-s oli keskkonnamuutujate töötlemise probleem, mille tõttu võis kasutaja näha tundlikku infot. Apple parandas selle parema valideerimisega.

**CVE-2022-22639** mõjutas macOS-i, iOS-i ja iPadOS-i ning oli seotud rakenduse õiguste ja süsteemiressursside kaitsega. NVD andmetes on see Apple'i probleem registreeritud kuni macOS 12.3 ja iOS/iPadOS 15.4 versioonideni.

### Android

Androidis on failiteed rakenduste sandbox'i tõttu tavaliselt rangemalt eraldatud. Rakendus ei peaks saama lihtsalt lugeda teise rakenduse privaatseid faile.

**CVE-2021-0397** näitab aga, kuidas madalama taseme mälurike võib süsteemi kompromiteerimise kaudu ületada tavapäraseid failikaitseid.

**CVE-2020-0423** Android Binderis on samuti näide, kus privilegeeritud komponendini jõudmine võib anda ründajale võimaluse pääseda ligi ressurssidele, mis tavarakendusele ei ole kättesaadavad.

### iOS

**CVE-2020-10010** iOS-i Logging-komponendis oli path handling'u probleem, mille kaudu võis lokaalne ründaja õigusi suurendada. Apple kirjeldas lahendust parema valideerimisega.

**CVE-2022-22639** mõjutas ka iOS-i ning Apple'i paranduste hulka kuulus iOS 15.4. Probleem oli seotud privilege boundary'ga ning näitab, et failitee ja süsteemiressursi käsitlemine võib mõjutada ka autoriseerimist.

## Kuidas riski vähendada?

Rakendus peab määrama kindla lubatud juurkataloogi. Kasutaja antud tee tuleb normaliseerida ja kontrollida pärast normaliseerimist, mitte enne. Tuleb kontrollida ka sümboolseid linke ning absoluutseid failiteid. Kasutada tuleks spetsiaalseid failisüsteemi API-sid, mis aitavad piirata juurdepääsu lubatud kataloogiga. Serveri protsessil peavad olema ainult vajalikud failisüsteemi õigused. Konfiguratsioonifailid ja saladused tuleb paigutada kataloogidesse, mida veebiteenus ei saa lugeda. Samuti tuleb vältida olukorda, kus rakendus lubab kasutajal valida täiesti suvalise failitee.

---

# 13. Insecure Deserialization ehk ebaturvaline deserialiseerimine

**Serialiseerimine (serialization)** tähendab objekti või andmestruktuuri muutmist vormingusse, mida saab salvestada või teisele süsteemile edastada.

Näiteks võib programmeerimisobjekt:

```text
Kasutaja
nimi = Valdo
roll = admin
```

muutuda andmevorminguks:

```text
{ "nimi": "Valdo", "roll": "admin" }
```

Selle vastupidist protsessi nimetatakse **deserialiseerimiseks (deserialization)**.

Probleem tekib siis, kui programm võtab väljast saadud serialiseeritud andmed ja muudab need automaatselt objektiks, eeldades, et andmed on usaldusväärsed.

Mõnes programmeerimiskeeles võib deserialiseerimise ajal käivituda objektide loomise või taastamisega seotud kood.

Seetõttu võib pahatahtlikult koostatud serialiseeritud objekt muutuda turvariskiks.

Oluline on eristada lihtsat JSON-i lugemist ja ohtlikku objektideserialiseerimist. JSON ei ole iseenesest ebaturvaline. Probleem tekib siis, kui rakendus usaldab väliseid andmeid või kasutab mehhanismi, mille kaudu andmete taastamine võib käivitada soovimatut loogikat.

### Kuidas probleem tehniliselt tekib?

```text
Ründaja
   │
   ▼
Pahatahtlik serialiseeritud objekt
   │
   ▼
Server
   │
   ▼
Deserialiseerimine
   │
   ▼
Objekt luuakse
   │
   ▼
Eriline meetod / loogika käivitub
   │
   ▼
Soovimatu tegevus
```

Probleem võib tekkida näiteks Java objektide, PHP objektide, .NET objektide või muude programmeerimiskeelte objektide taastamisel.

### Mida ründaja teha võib?

Mõne deserialiseerimisvea puhul võib mõju olla ainult rakenduse kokkujooksmine.

Tõsisematel juhtudel võib ründaja mõjutada serveris käivitatavat loogikat või saavutada RCE.

```text
Ebaturvaline deserialiseerimine
             │
             ▼
Objekti loomise kontrolli kaotus
             │
             ▼
Rakenduse loogika mõjutamine
             │
             ▼
Koodi käivitamine
             │
             ▼
Serveri kompromiteerimine
```

### Windows

Windowsi puhul võib ebaturvaline deserialiseerimine esineda eelkõige .NET-i, haldusteenuste või süsteemiga kaasas olevate rakenduste kaudu.

**CVE-2024-21448** ja sarnased .NET-i turvavead näitavad, miks väliste andmete käsitlemine peab olema rangelt kontrollitud.

Lisaks võib deserialiseerimisprobleem esineda Microsofti serverirakenduses, mis töötab Windowsi peal. Sellisel juhul tuleb vaadelda nii operatsioonisüsteemi kui ka rakenduse komponenti.

### Linux

Linux ise ei määra üht kindlat serialiseerimisvormingut. Probleem sõltub kasutatavast programmeerimiskeelest ja rakendusest.

Näiteks võib Linuxi serveris töötav Java-rakendus kasutada Java serialization mehhanismi. Kui server võtab väljast vastu Java serialiseeritud objekti ja deserialiseerib selle ilma usaldusväärsuse kontrollita, võib tekkida RCE.

Samuti võivad Python, PHP ja Ruby rakendused kasutada oma objektide taastamise mehhanisme.

### macOS

macOS-is võivad serialiseerimisega seotud probleemid tekkida süsteemiteenustes, mis kasutavad keerukate objektide vahetamiseks IPC-d.

**CVE-2021-30713** ei ole klassikaline deserialization-viga, vaid permissions-probleem, kuid see näitab hästi, et Apple'i süsteemiteenused peavad kontrollima ka objektide ja päringute päritolu.

**CVE-2020-9852** macOS-i kernelis oli use-after-free. Selliseid mäluprobleeme võib mõnes ründeahelas kombineerida keerukate süsteemiobjektide töötlemisega.

### Android

Android kasutab laialdaselt IPC-d ehk **Inter-Process Communication**, mille abil protsessid saavad omavahel suhelda.

**CVE-2020-0423** Binderis näitab, kui oluline on privilegeeritud IPC-andmete korrektne töötlemine.

**CVE-2021-0397** Androidi SDP-komponendis näitab teist tüüpi objektitöötluse probleemi, kus vigane objektide/mälu haldamine viis süsteemi kompromiteerimise võimaluseni.

Androidi puhul on oluline mõista, et kõik IPC-vead ei ole deserialization-vead, kuid keerukate IPC-struktuuride valideerimine täidab sisuliselt sama turvapõhimõtet: väljast saadud andmeid ei tohi automaatselt usaldada.

### iOS

iOS kasutab süsteemikomponentide vaheliseks suhtluseks näiteks XPC-d.

**CVE-2026-84617** oli XPC autoriseerimisprobleem. See ei ole klassikaline insecure deserialization, kuid on hea näide sellest, kuidas privilegeeritud süsteemiteenus peab kontrollima talle saadetud objektide ja päringute autoriseerimist.

**CVE-2021-1782** puhul oli iOS-i kerneli race condition, mis võis viia õiguste suurendamiseni. See näitab, et keerukate süsteemiobjektide töötlemisel võivad erinevad haavatavusklassid omavahel kombineeruda.

## Kuidas riski vähendada?

Kõige parem lahendus on mitte deserialiseerida väliseid andmeid otse keerukateks objektideks, kui selleks pole vajadust. Eelistada tuleks lihtsaid andmevorminguid, mille semantika on selgelt määratud. Kõik väljast tulevad andmed tuleb valideerida. Deserialiseerimise ajal ei tohiks lubada suvaliste klasside või objektide loomist. Kasutada tuleb turvalisi parser'eid ja ajakohaseid teeke. Kui rakendus peab töötlema keerukaid objekte, peaks deserialiseerimine toimuma võimalikult piiratud õigustega protsessis.

---

# 14. Improper Input Validation ehk ebapiisav sisendi valideerimine

**Sisendi valideerimine (input validation)** tähendab välise sisendi kontrollimist enne selle kasutamist.

Väline sisend võib tulla kasutajalt, veebipäringust, failist, USB-seadmest, teisest arvutist, API-st või mõnest süsteemiteenusest.

Programmeerija ei tohi eeldada, et sisend on alati korrektne.

Näiteks kui programm ootab vanuseks arvu vahemikus 0–120, ei tohiks ta lubada väärtusi nagu:

```text
-500
999999999999
tekst
ootamatu objekt
```

Sisendi valideerimine ei tähenda ainult andmetüübi kontrollimist. Kontrollida tuleb ka pikkust, väärtuste lubatud vahemikku, struktuuri, kodeeringut ja konteksti.

Oluline on kasutada põhimõtet **fail closed ehk vea korral pigem keelata kui lubada**.

Kui valideerimine ebaõnnestub, ei tohiks programm oletada, et sisend on turvaline.

### Kuidas probleem tehniliselt tekib?

```text
Väline sisend
     │
     ▼
Kas kontrollitakse?
     │
     ├── JAH → lubatud formaat → töötlemine
     │
     └── EI
          │
          ▼
     ootamatu sisend
          │
          ▼
   programm satub vale olekusse
          │
          ▼
     turvaprobleem
```

Improper input validation on väga sageli teiste haavatavuste **põhjus**, mitte lõpptulemus.

Näiteks:

```text
Ebapiisav valideerimine
        │
        ├── buffer overflow
        ├── command injection
        ├── path traversal
        ├── code injection
        ├── SQL injection
        └── memory corruption
```

### Mida ründaja teha võib?

Ründaja proovib anda sisendi, mida programmeerija ei ole ette näinud.

Näiteks võib ta proovida:

* liiga pikka sisendit;
* negatiivset arvu;
* ootamatut faililaiendit;
* spetsiaalseid Unicode'i sümboleid;
* katkist faili;
* liiga suurt väärtust;
* ootamatut andmestruktuuri.

Kui programm satub selle tõttu ootamatusse olekusse, võib tekkida mõni teine haavatavus.

### Windows

**CVE-2020-0601** on hea näide sellest, kuidas Windowsi CryptoAPI sisendi — ECC-sertifikaadi — valideerimine oli puudulik. Windows võis käsitleda võltsitud sertifikaati usaldusväärsena.

**CVE-2021-40444** MSHTML-is näitab samuti, kuidas spetsiaalselt koostatud Office'i dokument ja ActiveX-i sisend võisid viia koodi käivitamiseni. Microsoft jälgis selle CVE puhul reaalseid ründeid.

### Linux

**CVE-2021-41773** Apache HTTP Serveris tekkis path normalization'i ja sisendi töötlemise probleemina. Server ei käsitlenud päringute failiteed piisavalt turvaliselt.

**CVE-2022-0185** Linuxi kernelis oli suuruse arvutamise ja sisendi töötlemise probleem, mis võis viia out-of-bounds write'ini. See näitab, et isegi arvulised väärtused võivad olla turvarisk, kui nende suurust või piire ei kontrollita.

### macOS

**CVE-2022-26707** macOS Monterey keskkonnamuutujate töötlemise probleem lahendati parema valideerimisega.

**CVE-2020-9797** macOS-i kernelis oli integer overflow, mille lahenduseks Apple kasutas paremat input validation'i. Integer overflow tähendab olukorda, kus arvutus ületab andmetüübi lubatud väärtuse ja tulemuseks tekib ootamatu number.

### Android

**CVE-2021-0397** näitas, kuidas vigane andmetöötlus võib viia memory corruption'ini ja kaugkoodi käivitamiseni.

Androidi ametlikes bülletäänides on palju juhtumeid, kus probleem on seotud spetsiaalselt koostatud sisendiga. Näiteks 2026. aasta septembri bülletäänis on mitmeid kriitilisi System-komponendi RCE-vigu, mille puhul ei ole kasutaja tegevust vaja. See tähendab, et süsteem peab suutma käsitleda ka pahatahtlikku sisendit turvaliselt.

### iOS

**CVE-2020-27930** FontParseris oli memory corruption probleem, mille Apple lahendas improved input validation abil.

**CVE-2020-10002** ImageIO komponendis oli out-of-bounds read, mis võis pahatahtlikult koostatud pildi töötlemisel viia koodi käivitamiseni. Apple kirjeldas parandust samuti parema sisendi valideerimisena.

Need näited näitavad hästi, et isegi pilt või font on tegelikult **sisendandmed**, mida operatsioonisüsteem peab käsitlema nagu potentsiaalselt ebaturvalist infot.

## Kuidas riski vähendada?

Kõik välised andmed tuleb käsitleda ebausaldusväärsena. Kontrollida tuleb andmetüüpi, pikkust, vormingut, suurust ja lubatud väärtusi. Eelistada tuleks allowlist'i ehk lubatud väärtuste loendit, mitte blacklist'i ehk keelatud väärtuste loendit. Sisendi valideerimine peab toimuma serveris või privilegeeritud komponendis, mitte ainult kasutajaliideses. Failide puhul tuleb kontrollida ka faili tegelikku struktuuri, mitte ainult faililaiendit. Parser'id peavad olema ajakohased. Lisaks tuleb kasutada fuzzing'ut ehk automaatset testimist suure hulga ootamatute sisenditega, et leida programmeerimisvigu enne tootmiskeskkonda jõudmist.

---

# 15. Cryptographic Failures ehk krüptograafilised vead

**Krüptograafia (cryptography)** on meetodite kogum, millega kaitstakse infot matemaatiliste algoritmide abil. Krüptograafiat kasutatakse muu hulgas andmete konfidentsiaalsuse, tervikluse ja autentimise tagamiseks.

Krüptograafiline süsteem võib olla matemaatiliselt tugev, kuid rakendus võib seda kasutada valesti.

Seetõttu ei piisa sellest, et programm kasutab "AES-i", "RSA-d" või "ECC-d". Oluline on, kuidas algoritmi kasutatakse, milliseid võtmeid kasutatakse, kuidas võtmeid säilitatakse ja kuidas kontrollitakse sertifikaate.

Krüptograafiline viga võib tähendada näiteks liiga nõrka algoritmi, liiga lühikest võtit, võtme lekkimist, ebaturvalist juhuslike arvude genereerimist või sertifikaadi vale valideerimist.

Samuti võib probleem olla selles, et andmed küll krüpteeritakse, kuid ründaja saab mõjutada seda, millist võtit või algoritmi kasutatakse.

### Kuidas krüptograafiline probleem tehniliselt tekib?

Turvaline ühendus:

```text
Klient
  │
  │  autentimine
  ▼
Server
  │
  │  võtme kokkulepe
  ▼
Krüpteeritud kanal
  │
  ▼
Kaitstud andmed
```

Vigane süsteem:

```text
Klient
  │
  ▼
Vale sertifikaadi kontroll
  │
  ▼
Ründaja saab teeselda serverit
  │
  ▼
Andmed liiguvad vale osapoole kaudu
```

Krüptograafia kolm olulist omadust on:

**Konfidentsiaalsus** – kõrvaline inimene ei peaks saama andmeid lugeda.

**Terviklus (integrity)** – andmeid ei tohiks saada märkamatult muuta.

**Autentsus (authenticity)** – süsteem peab suutma kontrollida, kellega ta suhtleb.

### Mida ründaja teha võib?

Kui krüptograafiline kaitse on vigane, võib ründaja lugeda või muuta andmeid.

Näiteks võib ründaja proovida saada kontrolli HTTPS/TLS ühenduse üle, kasutada võltsitud sertifikaati või ära kasutada nõrka võtmehaldust.

```text
Krüptograafiline viga
        │
        ├── andmete lugemine
        ├── andmete muutmine
        ├── identiteedi võltsimine
        └── autentimise nõrgenemine
```

### Windows

**CVE-2020-0601 – Windows CryptoAPI**

Windows CryptoAPI ECC sertifikaatide valideerimise viga on väga hea näide krüptograafilisest veast. Ründaja võis kasutada võltsitud sertifikaati nii, et Windows pidas seda usaldusväärselt allkirjastatud sertifikaadiks. Microsoft dokumenteeris, et sellist haavatavust võis kasutada pahatahtliku faili usaldusväärsena esitamiseks. Microsoft Defender tuvastas ka vastavat ärakasutust sisaldanud pahavara.

**CVE-2022-34713 – Windows Secure Boot**

Secure Boot'i eesmärk on tagada, et arvuti käivitamisel kasutataks usaldusväärset tarkvara. Secure Bootiga seotud krüptograafilise usaldusahela probleem võib olla väga tõsine, sest see toimub enne operatsioonisüsteemi tavapärase turvamudeli käivitumist.

### Linux

Linuxi süsteemides kasutatakse krüptograafiat näiteks OpenSSL-i, GnuTLS-i, OpenSSH ja kernelitaseme crypto API kaudu.

**CVE-2022-0778 – OpenSSL**

OpenSSL-i BN_mod_sqrt funktsioonis oli probleem, mis võis põhjustada lõpmatu tsükli. Kuigi tegemist ei olnud otseselt "katkise krüpteerimisega", näitab see, et krüptograafiline teek võib sisaldada ka algoritmi rakendamise vigu, mis mõjutavad teenuse kättesaadavust.

**CVE-2016-0777 – OpenSSH**

OpenSSH roaming-funktsionaalsusega seotud viga võis põhjustada privaatvõtme materjali lekkimist. See on hea näide sellest, et isegi kui kasutatav krüptoalgoritm ise on tugev, võib võtme haldamine või rakenduse loogika olla nõrk.

### macOS

**CVE-2021-30737**

Apple'i ASN.1 dekooderis olnud mälurikkumine võis sertifikaadi töötlemise kaudu viia koodi käivitamiseni. Sertifikaadid on krüptograafilise usaldusahela oluline osa, mistõttu sertifikaatide parser on turvakriitiline komponent.

**CVE-2021-30713**

macOS-i permissions-probleem ei olnud krüptograafiline viga, kuid see näitab, miks krüptograafiast üksi ei piisa. Kui rakendusele antakse vale autoriseerimisotsus, ei päästa tugev krüpteerimine süsteemi kõigi rünnete eest.

### Android

Androidi puhul on oluline näiteks **Conscrypt**, mis on Androidi TLS/krüptograafia komponent.

Androidi 2025. aasta veebruari turvabülletään sisaldab Conscryptiga seotud CVE-d **CVE-2024-49723**, mis kuulus Google Play süsteemiuuenduste kaudu parandatavate probleemide hulka. Androidi bülletäänid näitavad, et krüptograafiakomponendid on Androidi turvamudeli eraldi oluline osa.

Androidi 2026. aasta bülletäänides on samuti süsteemi- ja võrguühendustega seotud turvaparandusi. Seetõttu peab Androidi turvalisuse hindamisel arvestama nii AOSP turvapaiga kui ka Google Play süsteemiuuendustega.

### iOS

Apple'i süsteemides kasutatakse krüptograafiat näiteks Keychaini, TLS-i, sertifikaatide, failikaitse ja süsteemi allkirjastamise juures.

**CVE-2021-30737** ASN.1 dekooderis on näide sertifikaatide töötlemisega seotud turvaveast. Apple kirjeldas, et pahatahtlikult koostatud sertifikaat võis põhjustada suvalise koodi käivitamise.

**CVE-2020-0601** ei mõjuta iOS-i, kuid on oluline võrdlusnäide Windowsist: sama üldine turvapõhimõte kehtib kõigil platvormidel — sertifikaadi kehtivust ei tohi otsustada ainult selle järgi, et sertifikaat näeb vormiliselt korrektne välja.

## Kuidas riski vähendada?

Tuleb kasutada tänapäevaseid ja standardiseeritud krüptoalgoritme ning vältida ise krüptograafiliste algoritmide väljamõtlemist. Võtmeid tuleb hoida kaitstult ja nende eluiga tuleb hallata. TLS-ühendustes tuleb kontrollida sertifikaadi kehtivust, usaldusahelat ja hostinime. Krüptograafilisi teeke tuleb regulaarselt uuendada, sest ka turvalise algoritmi rakendus võib sisaldada programmeerimisvigu. Paroolide puhul tuleb kasutada sobivaid aeglaseid paroolihashimise algoritme, mitte lihtsalt SHA-256 või muud üldotstarbelist hash'i. Krüptograafia peab olema osa laiemast turvamudelist, mitte ainus kaitsekiht.

---

# Peatükkide 11–15 seos

Need viis peatükki näitavad eriti hästi, kuidas üks viga võib viia järgmise probleemini.

```text
             VÄLINE SISEND
                  │
                  ▼
       Ebapiisav valideerimine
                  │
       ┌──────────┼──────────┐
       ▼          ▼          ▼
 Code Injection  Path      Parseri viga
                Traversal      │
       │          │            ▼
       │          │      Memory Corruption
       │          │            │
       └──────────┼────────────┘
                  ▼
             Koodi käivitamine
                  │
                  ▼
          Kõrgemad õigused
                  │
                  ▼
         Süsteemi kompromiteerimine
```

Krüptograafiline viga võib samal ajal toimida teistsuguses harus:

```text
Krüptograafiline viga
          │
          ▼
Usaldusväärsuse kontroll ebaõnnestub
          │
          ▼
Ründaja suudab esineda usaldusväärse osapoolena
          │
          ▼
Pahatahtlik fail / ühendus / andmed
          │
          ▼
Järgmine haavatavus
```

Seetõttu tuleb küberturvalisuses vaadata süsteemi tervikuna.

Näiteks ei piisa sellest, kui server kasutab HTTPS-i. Kui HTTPS-ühenduse sertifikaati kontrollitakse valesti, võib krüptograafiline kaitse olla praktikas nõrgenenud.

Samuti ei piisa sellest, et rakendus kontrollib kasutaja sisselogimist. Kui kasutaja saab path traversal'i kaudu lugeda konfiguratsioonifaili, võib sealt lekkida parool või API-võti.

Veel üks oluline näide on deserialiseerimine. Kui rakendus võtab väljast tuleva objekti ja taastab selle ilma kontrollita, võib sisend muuta rakenduse tööloogikat isegi siis, kui autentimine ise on täiesti korrektne.

Seetõttu võib ühe süsteemi ründeahel olla näiteks:

```text
1. Nõrk autentimine
        │
        ▼
2. Kasutaja konto kompromiteerimine
        │
        ▼
3. Path Traversal
        │
        ▼
4. Konfiguratsioonifaili lugemine
        │
        ▼
5. Saladuse / võtme lekkimine
        │
        ▼
6. Code Injection
        │
        ▼
7. Koodi käivitamine
        │
        ▼
8. Privilege Escalation
        │
        ▼
9. Süsteemi kompromiteerimine
```

See on üks olulisemaid mõtteid kogu õppematerjalis:

> **Ründaja ei pea leidma ühte haavatavust, mis annab kohe kogu süsteemi üle kontrolli. Piisab mitmest väiksemast nõrkusest, mida saab omavahel ühendada.**

---

# Mõisted, mida tuleb nende peatükkide puhul osata eristada

**Code injection** – andmete sattumine olukorda, kus neid tõlgendatakse koodina.

**Command injection** – kasutaja kontrollitud sisendi kaudu operatsioonisüsteemi käsu tähenduse muutmine.

**Path traversal** – lubatud failitee piiridest väljapoole liikumine.

**Serialization** – objekti või andmestruktuuri muutmine edastatavaks või salvestatavaks vormiks.

**Deserialization** – sellise andmevormingu taastamine objektiks või andmestruktuuriks.

**Input validation** – välise sisendi kontrollimine enne selle kasutamist.

**Cryptography** – matemaatiliste meetodite kasutamine andmete ja identiteedi kaitsmiseks.

**Encryption** – andmete muutmine kujule, mida ilma sobiva võtmeta ei peaks olema võimalik mõistlikult lugeda.

**Hash** – ühesuunaline matemaatiline teisendus, mida kasutatakse näiteks paroolide kontrollimiseks ja andmete tervikluse hindamiseks.

**Certificate** – digitaalne tõend, mida kasutatakse muu hulgas identiteedi ja avaliku võtme seostamiseks.

**TLS** – protokollide kogum, mida kasutatakse näiteks HTTPS-i turvalise ühenduse loomiseks.

**Parser** – programmiosa, mis loeb struktureeritud sisendit ja muudab selle programmi jaoks arusaadavaks andmestruktuuriks.

**Interpreter** – programm, mis loeb ja täidab programmeerimis- või skriptikeeles esitatud käske.

**API** – programmeerimisliides, mille kaudu üks programm saab kasutada teise komponendi funktsioone.

**IPC** – protsessidevaheline suhtlus ehk Inter-Process Communication.

**Sandbox** – piiratud täitmiskeskkond, milles programmile antakse tavaliselt ainult vajalikud õigused ja ligipääsud.

---

# Miks on sisendi valideerimine nii oluline?

Paljud selles õppematerjalis käsitletavad haavatavused saavad alguse samast probleemist:

```text
           VÄLINE MAAILM
                │
                ▼
        kasutaja / fail /
        võrk / rakendus
                │
                ▼
          SISENDANDMED
                │
                ▼
       ┌────────────────┐
       │   VALIDEERIMINE │
       └────────────────┘
          │           │
       lubatud      vigane
          │           │
          ▼           ▼
       töötlemine   keelamine
```

Kui valideerimist ei tehta, võib sama sisend liikuda mitmesse erinevasse ohtlikku kohta:

```text
                  SISEND
                     │
       ┌─────────────┼─────────────┐
       ▼             ▼             ▼
      mälu         failitee      interpreeter
       │             │             │
       ▼             ▼             ▼
   OOB/UAF       Traversal      Injection
```

Seetõttu on sisendi valideerimine üks keskseid turvalise tarkvara arendamise põhimõtteid.

Samas tuleb mõista, et ainult sisendi valideerimisest ei piisa. Turvaline süsteem peab kasutama mitut kaitsekihti.

Näiteks:

```text
Valideerimine
      +
Vähimad õigused
      +
Sandbox
      +
MFA
      +
Krüptograafia
      +
Paigahaldus
      +
Logimine
      +
Monitooring
      +
Varukoopiad
```

Sellist mitmekihilist kaitset nimetatakse **defense in depth ehk süvakaitseks**.

Kui üks kaitsekiht ebaõnnestub, peab järgmine kiht piirama ründaja võimalusi.

Peamised kontrollitud allikad nende peatükkide jaoks on Microsofti turvainfo ja ohuteabe materjalid, NIST/NVD ning Apple'i ametlikud turvaväljalasked. Näiteks Microsoft dokumenteerib CVE-2021-40444 puhul nii aktiivsed ründekatsed kui ka ründeahela; Windows CryptoAPI CVE-2020-0601 puhul kirjeldab Microsoft sertifikaatide võltsimise mehhanismi. ([Microsoft][1])

Path traversal'i puhul on Apache enda kirjeldus CVE-2021-41773 kohta eriti selge: 2.4.49 path-normalization'i viga võimaldas liikuda dokumentide juurest väljapoole ning Apache märkis probleemi reaalselt ärakasutatuks; CVE-2021-42013 oli selle paranduse ebapiisavuse jätk. ([Openwall][2])

Apple'i näidete puhul on ametlikud iOS-i turvadokumendid eriti kasulikud, sest need kirjeldavad konkreetse komponendi, mõju ja paranduse. Näiteks CVE-2020-27930 puhul oli FontParseri memory corruption seotud pahatahtliku fondi töötlemisega ning CVE-2020-10002 puhul ImageIO out-of-bounds read pahatahtliku pildi töötlemisega. ([Apple Support][3]) Apple on eraldi dokumenteerinud ka CVE-2021-1782 aktiivse ärakasutamise teate ning macOS-i CVE-2021-30713 puhul on CISA lisanud haavatavuse KEV kataloogi. ([Apple Support][4])

Androidi ametlikud bülletäänid näitavad, et süsteemi haavatavused jagunevad muu hulgas RCE, EoP, ID ja DoS klassidesse ning et Androidi turvapaiga tase on oluline osa sellest, kas seade on konkreetse probleemi vastu parandatud. 2026. aasta septembri bülletään sisaldab mitmeid kriitilisi RCE ja EoP probleeme System-komponendis. ([Android Open Source Project][5])

**Järgmine osa on 16–20**, kus läheme edasi võrguteenuste ja protokollide, kerneli ja draiverite, faili- ja meediaparserite, tarkvara tarneahela ning info avalikustumise juurde. Seejärel tuleb eraldi **5 veebirakenduste haavatavuse peatükki** ja kõige lõpus põhjalik **„Kokkuvõte: kuidas haavatavused omavahel ründeahelaks muutuvad“** koos 20 haavatavuse võrdlustabeli ja tervikliku kaitsemudeliga.

[1]: https://www.microsoft.com/en-us/security/blog/2021/09/15/analyzing-attacks-that-exploit-the-mshtml-cve-2021-40444-vulnerability/?utm_source=chatgpt.com "Analyzing attacks that exploit the CVE-2021-40444 MSHTML vulnerability | Microsoft Security Blog"
[2]: https://www.openwall.com/lists/oss-security/2021/10/05/2?utm_source=chatgpt.com "oss-security - CVE-2021-41773: Path traversal and file disclosure vulnerability in Apache HTTP Server 2.4.49"
[3]: https://support.apple.com/en-ie/103121?utm_source=chatgpt.com "About the security content of iOS 14.2 and iPadOS 14.2 - Apple Support (IE)"
[4]: https://support.apple.com/en-ae/103123?utm_source=chatgpt.com "About the security content of iOS 14.4 and iPadOS 14.4 - Apple Support (AE)"
[5]: https://source.android.com/docs/security/bulletin/2026/2026-09-01?utm_source=chatgpt.com "Android Security Bulletin—September 2026  |  Android Open Source Project"
