# Peatükid 6–10 

# 6. Buffer Overflow ehk puhvri ületäide

**Puhvri ületäide (buffer overflow)** on programmeerimisviga, mille korral programm kirjutab andmeid mälupiirkonda rohkem, kui selle piirkonna jaoks on ette nähtud. Selle mõistmiseks tuleb kõigepealt aru saada, mis on **puhver (buffer)**. Puhver on mälus reserveeritud ala, kuhu programm paigutab ajutiselt andmeid. Näiteks võib programm reserveerida ruumi 20 märgi jaoks. Kui programm lubab kasutajal sisestada 100 märki ega kontrolli sisendi pikkust, võib osa andmetest jõuda puhvrist väljapoole. Mälu väljaspool puhvrit võib aga sisaldada mõnda muud andmestikku, muutujat, aadressi või programmi tööks vajalikku juhtinfot. Seetõttu ei ole puhvri ületäide lihtsalt "liiga pikk tekst". See võib muuta programmi sisemist olekut. Teatud juhtudel võib tagajärjeks olla programmi kokkujooksmine, andmete rikkumine või pahatahtliku koodi käivitamine. Eriti ohtlikud on puhvri ületäited operatsioonisüsteemi kernelis, draiverites ja võrguteenustes, sest need komponendid töötavad sageli kõrgete õigustega. Puhvri ületäide on klassikaline mäluturbe probleem, mille vastu kasutatakse tänapäeval mitmeid kaitsemehhanisme.

## Kuidas buffer overflow tehniliselt tekib?

Lihtsustatud näites reserveerib programm mälus ruumi:

```text
Mälu

+-------------------+
| Puhver            |
| 20 baiti          |
+-------------------+
| Muu andmestik     |
+-------------------+
| Juhtinfo          |
+-------------------+
```

Kui programm kirjutab puhvri sisse ainult 20 baiti, on kõik korras:

```text
Sisend
  │
  ▼
20 baiti
  │
  ▼
+-------------------+
| XXXXX             |
+-------------------+
```

Probleem tekib siis, kui sisend on näiteks 50 baiti:

```text
50 baiti sisendit
       │
       ▼
+-------------------+
| XXXXX             | 20
+-------------------+
| XXXXX             | ← puhvrist väljas
+-------------------+
| XXXXX             | ← puhvrist väljas
+-------------------+
```

Kui programm ei kontrolli sisendi suurust, võib ta kirjutada üle järgmise mäluala.

Programmeerimiskeeltes nagu C ja C++ on sellised probleemid eriti olulised, sest programmeerija peab sageli ise hoolitsema selle eest, et mälule ligipääs jääks lubatud piiridesse.

Tänapäevased operatsioonisüsteemid kasutavad selliste rünnete raskendamiseks näiteks **ASLR-i (Address Space Layout Randomization)**, **DEP/NX-i (Data Execution Prevention / No-eXecute)**, kontrollitud kopeerimisfunktsioone, kompilaatori turvakontrolle ja mälu märgistamist.

## Mida ründaja pärast buffer overflow kasutamist teha võib?

Kõige lihtsam tagajärg on programmi kokkujooksmine. Kui ründaja suudab aga mõjutada programmi juhtimisvoogu, võib teatud tingimustel olla võimalik panna programm käivitama soovimatut koodi.

**Koodikäivitamine (code execution)** tähendab, et ründaja suudab mõjutada süsteemi nii, et tema valitud toimingud viiakse läbi ohvri protsessi kontekstis.

Kui haavatav programm töötab administraatori- või süsteemiõigustes, võib ka koodi käivitamine toimuda kõrgete õigustega.

Seetõttu võib ründeahel olla:

```text
Vigane sisendi kontroll
        │
        ▼
Buffer overflow
        │
        ▼
Mälu rikkumine
        │
        ▼
Kontroll programmi töövoo üle
        │
        ▼
Koodi käivitamine
        │
        ▼
Võimalik õiguste suurendamine
```

### Windows

**CVE-2024-38115 – Windows IP Routing Management Snapin**

Windowsi IP Routing Management Snapin'is tuvastati heap-based buffer overflow ehk kuhjamälus toimuv puhvri ületäide. Haavatavus võis viia koodi kaugkäivitamiseni. Ründeahel eeldas kasutaja interaktsiooni, näiteks spetsiaalselt koostatud sisu avamist. Microsoft parandas vea turvauuendusega. ([CVE][1])

**CVE-2024-20696 – Windows libarchive**

Windowsi libarchive-komponendis oli heap-based buffer overflow. Haavatavuse ärakasutamine võis põhjustada koodi käivitamise. Libarchive tegeleb arhiivifailide töötlemisega, mistõttu võib pahatahtlikult koostatud arhiiv olla sellise vea sisendiks. Mõjutatud Windowsi versioon tuli uuendada Microsofti parandatud versioonile. ([CVE][2])

### Linux

**CVE-2021-33655 – Linux framebuffer**

Linuxi kerneli framebuffer-draiver ei kontrollinud teatud suurusepiire korrektselt. Selle tulemusel tekkis out-of-bounds write, mis on otseselt seotud puhvri piiridest väljapoole kirjutamisega. Mõju võis olla süsteemi kokkujooksmine või teatud tingimustel koodi käivitamine. Ubuntu väljastas sellele parandused. ([Ubuntu][3])

**CVE-2022-0185 – Linux kernel**

Linuxi kerneli failisüsteemikoodis oli integer underflow, mis võis viia out-of-bounds write olukorrani. See tähendab, et arvutus andis ootamatu tulemuse ja programm sai kirjutada mällu valest kohast. Ubuntu kirjeldas võimaliku mõjuna süsteemi kokkujooksmist või koodi käivitamist. ([Ubuntu][4])

### macOS

**CVE-2026-28959 – APFS**

macOS Sonoma APFS-komponendis parandati buffer overflow. APFS on Apple'i failisüsteem. Vigane mälukäsitlus võis põhjustada süsteemi ootamatu lõpetamise. Apple lahendas probleemi parema bounds checking'u abil. ([Apple Support][5])

**CVE-2026-84510 – Disk Images**

macOS-i kettapiltide töötlemisel parandati heap buffer overflow. Kettapilt võib sisaldada struktureeritud andmeid, mida operatsioonisüsteem peab töötlemisel parsima. Vigane suurusekontroll võib sellises olukorras viia mälupiiride ületamiseni. Apple kirjeldab selle konkreetse vea puhul ootamatut süsteemi lõpetamist. ([Apple Support][6])

### Android

**CVE-2025-26785**

Androidi komponendis tuvastati heap buffer overflow, mille tulemuseks võis olla out-of-bounds write ja potentsiaalselt koodi kaugkäivitamine. Androidi andmetel ei olnud täiendavaid täitmisõigusi ega kasutaja tegevust vaja. Selline näide näitab, miks Androidi native-kood ehk C/C++-põhine süsteemikood on oluline ründepind.

**CVE-2015-3834 – libstagefright**

Androidi vanemas Stagefrighti meediakomponendis oli buffer overflow. Vigase meedia töötlemine võis põhjustada mälurikkumise ja potentsiaalselt koodi käivitamise mediaserveri protsessi õigustes. See on klassikaline näide sellest, miks failide ja meedia automaatne töötlemine võib olla turvarisk. ([Android Open Source Project][7])

### iOS

**CVE-2025-24209**

Apple'i WebKiti komponendis parandati buffer overflow. Pahatahtlikult koostatud veebisisu töötlemine võis põhjustada protsessi ootamatu lõpetamise. Parandus rakendati iOS 18.4, iPadOS 18.4 ja vastavatesse Apple'i süsteemidesse. ([NVD][8])

**CVE-2025-24237**

Apple'i süsteemikomponendis parandati samuti buffer overflow, mille tagajärjeks võis olla ootamatu süsteemi lõpetamine. Apple lahendas probleemi bounds checking'u parandamisega. ([NVD][9])

## Kuidas riski vähendada?

Arenduses tuleb kontrollida alati sisendi suurust ja mälu kasutamise piire. Eelistada tuleb mäluturvalisemaid programmeerimiskeeli või kasutada C/C++ puhul turvalisemaid teeke ja kompilaatori kaitsemehhanisme. Operatsioonisüsteemi administraator peab paigaldama turvauuendused, sest buffer overflow probleemid parandatakse sageli süsteemikomponentide uuendamisega. Kasutada tuleb ASLR-i, DEP/NX-i ja muid platvormi pakutavaid kaitsemehhanisme. Rakenduste töötamisel piiratud kasutajaõigustes vähendatakse võimaliku ärakasutamise mõju. Failide ja võrgusisendi töötlemisel tuleb võimaluse korral kasutada sandbox'i ehk piiratud täitmiskeskkonda.

---

# 7. Out-of-Bounds Read/Write ehk lubatud mälupiiride ületamine

**Out-of-bounds ehk OOB** tähendab olukorda, kus programm loeb või kirjutab mällu väljaspool sellele programmile eraldatud lubatud piirkonda. See mõiste on laiem kui klassikaline buffer overflow. Buffer overflow kirjeldab sageli olukorda, kus andmeid kirjutatakse puhvrist välja, samal ajal kui out-of-bounds võib olla nii lugemine kui ka kirjutamine.

**Out-of-bounds read** tähendab, et programm loeb andmeid valest mälupiirkonnast. Selle tulemusena võib lekkida andmeid, mida programm ei tohiks näha.

**Out-of-bounds write** tähendab, et programm kirjutab väljapoole lubatud mälupiirkonda. See võib rikkuda teisi andmeid või muuta programmi töövoogu.

Probleem tekib sageli valest indeksist, suuruse arvutamise veast, integer overflow'ist või puudulikust sisendi valideerimisest.

### Kuidas OOB-viga tehniliselt tekib?

Näiteks on massiiv kümne elemendiga:

```text
Indeks:
0 1 2 3 4 5 6 7 8 9
│ │ │ │ │ │ │ │ │ │
+-------------------+
|     lubatud ala   |
+-------------------+
```

Kui programm proovib lugeda indeksit 10:

```text
0 1 2 3 4 5 6 7 8 9 | 10
                      │
                      ▼
                 OOB ACCESS
```

Programmeerija võis arvutada indeksi valesti või usaldada kasutaja antud suurust.

OOB write on veel ohtlikum, sest vale kirjutamine võib muuta teise objekti või programmi juhtimisandmeid.

```text
Lubatud objekt
+-------------+
|   andmed    |
+-------------+
      │
      X
      ▼
+-------------+
| teine objekt|
+-------------+
```

### Mida ründaja pärast OOB-viga teha võib?

OOB read võib anda ründajale mälust infot, mida tal ei tohiks olla. Sellist infot võib mõnikord kasutada teiste rünnete ettevalmistamiseks.

OOB write võib põhjustada mälu rikkumist, programmi kokkujooksmist või teatud tingimustel koodi käivitamist.

Näiteks võib mäluleke anda infot, mis aitab ründajal ületada ASLR-i kaitset.

### Windows

**CVE-2025-55681 – Desktop Window Manager**

Windowsi Desktop Window Manageris tuvastati out-of-bounds read. See võimaldas autoriseeritud ründajal lokaalselt suurendada õigusi. Probleem seisnes selles, et komponent luges mälust andmeid väljaspool lubatud piire. ([CVE][10])

**CVE-2025-33065 – Windows Storage Management Provider**

Windows Storage Management Provideris oli out-of-bounds read, mille tagajärjeks võis olla tundliku info avalikustamine. Selline haavatavus ei pea andma kohe koodi käivitamist: juba süsteemi mälust info lekkimine võib aidata järgmise ründe ettevalmistamisel. ([CVE][11])

### Linux

**CVE-2022-48502 – Linux NTFS**

Linuxi NTFS-failisüsteemi komponendis ei kontrollitud teatud puhvri indekseid korrektselt. Selle tulemuseks oli out-of-bounds read, mis võis paljastada kerneli mälu sisu. ([Ubuntu][12])

**CVE-2024-23849 – Linux RDS**

Linuxi RDS-protokolli implementatsioonis oli out-of-bounds read. Ubuntu kirjeldas võimalikuks mõjuks süsteemi kokkujooksmise. ([Ubuntu][13])

### macOS

**CVE-2026-64784 – WebKit**

Apple'i Safari/WebKiti komponendis parandati out-of-bounds access. Pahatahtlikult koostatud veebisisu võis põhjustada Safari protsessi ootamatu lõpetamise. Apple lahendas probleemi parema bounds checking'u abil. ([Apple Support][14])

**CVE-2026-43683 – CoreDrag**

macOS-i CoreDrag-komponendis parandati out-of-bounds read. Apple kirjeldas võimaliku mõjuna protsessi mälu avalikustamist või protsessi lõpetamist. ([Apple Support][6])

### Android

**CVE-2025-26785**

Androidi komponendis esinenud heap buffer overflow viis out-of-bounds write'ini. Tegemist oli kriitilise mäluturbe probleemiga, mille võimalik mõju oli koodi kaugkäivitamine.

Androidi puhul on oluline, et sellised probleemid võivad esineda nii AOSP komponendis kui ka tootja suletud lähtekoodiga komponentides.

**CVE-2015-3834**

Stagefrighti buffer overflow näitab samuti, kuidas mälupiiride rikkumine võib tekkida meediaandmete töötlemisel. Kuna meediafaili töötlemiseks tuleb faili struktuuri lugeda, võivad valed pikkused ja indeksid põhjustada OOB-juurdepääsu või sellega seotud puhvri ületäite. ([Android Open Source Project][7])

### iOS

**CVE-2026-65359**

iOS-i kerneli puhul kirjeldas Apple out-of-bounds read'i, mille tõttu võis kohalik kasutaja saada ligipääsu kerneli mälule. ([Apple Support][15])

**CVE-2026-84510**

iOS-i exFAT-komponendis parandati heap buffer overflow, mis on OOB-write tüüpi mäluturbe probleem. Vigase köite ühendamine võis põhjustada süsteemi ootamatu lõpetamise. ([Apple Support][15])

## Kuidas riski vähendada?

Kõige olulisem on korrektne bounds checking ehk piiride kontroll. Massiivi, puhvri, faili või võrgusõnumi pikkust ei tohi usaldada ilma kontrollimata. Programmeerimisel tuleb kasutada turvalisemaid andmestruktuure ja mäluturvalisi API-sid. Operatsioonisüsteemi tasemel aitavad kaasa ASLR, MTE, HWASan, KASAN ja muud mäluturbe mehhanismid. Android kasutab näiteks HWASan-i ja MTE-d mäluturbe probleemide tuvastamiseks ja leevendamiseks. ([Android Open Source Project][16])

---

# 8. Use-After-Free ehk vabastatud mälu kasutamine

**Use-after-free (UAF)** on mäluturbe viga, mille korral programm kasutab mälupiirkonda pärast seda, kui see on juba vabastatud.

Mälu vabastamine tähendab, et programm ütleb operatsioonisüsteemile või mäluhaldurile, et konkreetset mälupiirkonda enam ei kasutata.

Probleem tekib siis, kui mõni teine osa programmist säilitab sellele piirkonnale viitava **kursori (pointer)** ja proovib seda hiljem kasutada.

Pointer on muutujas hoitav väärtus, mis viitab mingile mäluaadressile.

Lihtsustatud näide:

```text
Objekt luuakse
     │
     ▼
Mälu 0x1000
     │
     ▼
Objekt kustutatakse
     │
     ▼
Mälu vabastatakse
     │
     ▼
Pointer osutab ikka 0x1000
     │
     ▼
Programm kasutab pointerit
     │
     ▼
USE-AFTER-FREE
```

Vabastatud mälu võidakse vahepeal anda teisele objektile. Seetõttu võib programm lugeda või muuta täiesti teise objekti andmeid.

### Kuidas UAF tehniliselt tekib?

Tavaliselt on põhjuseks keeruline objektide elutsükkel.

```text
Thread A
   │
   ├── loob objekti
   │
   └── säilitab pointeri
          │
          ▼
Thread B
   │
   └── vabastab objekti
          │
          ▼
Thread A kasutab vana pointerit
          │
          ▼
     Use-after-free
```

Sageli on põhjuseks **race condition**, kus kaks samaaegselt töötavat tegevust jõuavad objekti juurde erineval ajal.

### Mida ründaja teha võib?

UAF võib põhjustada programmi kokkujooksmise.

Ohtlikum on olukord, kus ründaja suudab mõjutada seda, milline uus objekt vabastatud mälu piirkonda paigutatakse. Siis võib vana pointer hakata viitama ründaja jaoks sobivalt mõjutatud andmetele.

Seda tüüpi haavatavused on olnud olulised näiteks brauserite, kerneli ja draiverite puhul.

### Windows

**CVE-2025-54101 – Windows SMBv3 Client**

Windows SMBv3 Clientis tuvastati use-after-free, mis võis võimaldada võrgu kaudu koodi käivitamist. SMB on Windowsi failijagamise protokoll. Sellise vea puhul on oluline, et võrgu kaudu saadetud andmed mõjutavad keerukat protokollitöötlust. ([CVE][17])

**CVE-2025-27476 – Windows Digital Media**

Windows Digital Media komponendis oli use-after-free, mis võis viia kohaliku õiguste suurendamiseni. Ründaja pidi esmalt omama võimalust süsteemis toiminguid teha. ([CVE][18])

### Linux

**CVE-2022-2602 – io_uring**

Linuxi kerneli io_uringi alamsüsteemis tekkis race condition, mis viis use-after-free olukorrani. Kohalik ründaja võis selle abil põhjustada teenusetõkestuse või teatud tingimustel koodi käivitamise. ([Ubuntu][19])

**CVE-2021-3609 – CAN BCM**

Linuxi CAN BCM võrguprotokolli rakenduses tuvastati race condition, mis põhjustas use-after-free haavatavusi. Ubuntu kirjeldas võimaliku mõjuna arbitraarse koodi käivitamist. ([Ubuntu][20])

### macOS

**CVE-2026-28969 – IOKit**

macOS-i IOKit-komponendis parandati use-after-free. Apple kirjeldas võimalikuks mõjuks süsteemi ootamatu lõpetamise. IOKit on oluline riistvara ja kerneli ning kasutajaruumi vahelise suhtluse raamistik. ([Apple Support][6])

**CVE-2026-84506 – udf**

Apple'i udf-komponendis parandati use-after-free probleem, mille võimalik mõju oli koodi käivitamine kerneliõigustes. Selline mõju näitab, miks kernelitaseme UAF on eriti tõsine. ([Apple Support][6])

### Android

**CVE-2025-22403 – Bluetooth**

Androidi Bluetoothi komponendis oli use-after-free, mis võis põhjustada koodi kaugkäivitamise ilma täiendavate täitmisõigusteta või kasutaja tegevuseta. ([NVD][21])

**CVE-2025-22409 – Bluetooth**

Teises Androidi Bluetoothi komponendi UAF-haavatavuses võis probleem viia lokaalse õiguste suurendamiseni. Ka siin ei olnud kasutaja tegevust vaja. ([NVD][22])

### iOS

**CVE-2026-64718 – WebKit Canvas**

WebKiti Canvas-komponendis parandati use-after-free. Apple'i turvabülletäänides kirjeldatakse selliste vigade puhul tavaliselt võimalikku protsessi lõpetamist või mälu rikkumist. ([Apple Support][23])

**CVE-2025-43536 – WebKit**

iOS-i WebKiti use-after-free probleem võis pahatahtlikult koostatud veebisisu töötlemisel põhjustada protsessi ootamatu lõpetamise. Apple lahendas selle parema mäluhaldusega. ([Apple Support][24])

## Kuidas riski vähendada?

Arendaja peab tagama, et objekti kasutamine lõpeks enne selle mälu vabastamist. Keerukates mitmelõimelistes programmides tuleb kasutada korrektset sünkroniseerimist. Kasulikud on memory-safe keeled, automaatne mäluhaldus ja sanitiseerijad. Android toetab näiteks HWASan-i, mis suudab UAF-vigu tuvastada. ([Android Open Source Project][16])

Administraator peab eelkõige tagama operatsioonisüsteemi, brauseri, draiverite ja rakenduste ajakohasuse. Kui UAF paikneb kernelis või brauseris, ei ole tavakasutajal tavaliselt võimalik probleemi konfiguratsiooniga täielikult parandada.

---

# 9. Null Pointer Dereference ehk nullkursori dereferentseerimine

**Null pointer dereference** tähendab olukorda, kus programm kasutab pointerit, mille väärtus on `NULL` või `nullptr`, justkui viitaks see päris mälupiirkonnale.

Nullpointer tähendab sisuliselt:

```text
Pointer
   │
   ▼
NULL
   │
   X
   └── ei viita kasutatavale objektile
```

Kui programm proovib selle kaudu mälule ligi pääseda, võib operatsioonisüsteem protsessi lõpetada.

Kõige tavalisem tagajärg on programmi kokkujooksmine. Turvaprobleemiks muutub nullpointer dereference siis, kui vigane olukord on ründaja poolt kontrollitav ning selle abil saab mõjutada süsteemi turvamehhanismi.

Kernelis võib selline viga olla eriti tõsine, sest kerneli protsessis on kõrgemad õigused ja süsteemi stabiilsus sõltub selle korrektsest töötamisest.

### Kuidas probleem tekib?

Programmeerija võib kirjutada loogika:

```text
otsi objekt
   │
   ▼
kas objekt leiti?
   │
   ├── jah → kasuta objekti
   │
   └── ei  → NULL
             │
             ▼
        programm kasutab objekti
             │
             ▼
       NULL POINTER DEREFERENCE
```

Õige programm peab kontrollima, kas pointer on kehtiv.

### Mida ründaja teha võib?

Kõige tüüpilisem tagajärg on **DoS (Denial of Service)** ehk teenusetõkestus. Kui serveriprotsess kukub kokku, ei saa kasutajad teenust kasutada.

Mõnel juhul võib nullpointer dereference olla kombineeritav teiste mäluprobleemidega. Seetõttu ei tohi eeldada, et kõik nullpointeri vead on ainult "programm jookseb kokku".

### Windows

**CVE-2025-33057 – Windows LSA**

Windows Local Security Authority komponendis oli null pointer dereference, mille kaudu võis autoriseeritud ründaja põhjustada teenusetõkestuse võrgu kaudu. ([CVE][25])

**CVE-2025-29838 – Windows ExecutionContext Driver**

Windowsi draiveris tuvastati nullpointer dereference, mis võis lubada lokaalselt õiguste suurendamist. See näide on oluline, sest näitab, et nullpointeri probleem ei pea piirduma DoS-iga. ([CVE][26])

### Linux

**CVE-2022-42722**

Linuxi Wi-Fi draiveris tuvastati teatud olukordades nullpointer dereference. Ubuntu kirjeldas võimaliku mõjuna süsteemi kokkujooksmist. ([Ubuntu][19])

**CVE-2024-22099**

Linuxi Bluetooth RFCOMM-draiveris oli race condition, mis võis viia nullpointer dereference'ini ja põhjustada süsteemi DoS-i. ([Ubuntu][27])

### macOS

**CVE-2026-65412 – CoreText**

Apple'i CoreText-komponendis parandati nullpointer dereference, mis võis veebisisu töötlemisel põhjustada teenusetõkestuse. Probleem lahendati parema sisendi valideerimisega. ([Apple Support][6])

**CVE-2026-65360 – Kernel**

macOS-i kernelis on Apple avaldanud mitmeid mälukäsitlusega seotud probleeme; kerneli nullpointeri või sarnase pointeri väärkasutuse tagajärjeks võib olla süsteemi lõpetamine. Selliste probleemide puhul tuleb kasutada konkreetse macOS-i turvavärskenduse parandust.

### Android

Androidi süsteemikomponentides esineb samuti pointeri- ja mäluhaldusega seotud vigu. Androidi enda dokumentatsioon rõhutab, et ASan/HWASan suudavad tuvastada nii puhvri ületäiteid kui ka muid mäluturbe probleeme. ([Android Open Source Project][28])

Androidi tootjate turvabülletäänides on dokumenteeritud nullpointer dereference'i probleeme näiteks draiverites ja riistvarakomponentides. Need võivad põhjustada rakenduse või seadme protsessi lõpetamise ning mõnel juhul olla kombineeritavad teiste haavatavustega. ([NCSC Advisories][29])

### iOS

Apple'i iOS-i turvauuendused sisaldavad samuti mälupointeritega seotud parandusi. Näiteks võivad süsteemikomponendid pärast vigase sisendi töötlemist jõuda olekusse, kus oodatud objekt puudub.

iOS-is on mõju sageli piiratud sandbox'i ja muude kaitsemehhanismidega, kuid kernelis või privilegeeritud teenuses toimuv pointeriviga võib olla palju olulisem.

## Kuidas riski vähendada?

Programmeerija peab kontrollima, kas objekt või pointer on enne kasutamist olemas. Vigade käsitlemine peab olema osa programmi tavapärasest tööloogikast. Kernelis ja draiverites tuleb eriti hoolikalt kontrollida vigase või ootamatu sisendi juhtumeid. Sanitiseerijad, staatiline analüüs ja fuzzing aitavad selliseid probleeme avastada.

Administraator peab hoidma kernelid, draiverid ja operatsioonisüsteemid ajakohasena. Nullpointeri probleemi ei saa tavaliselt lahendada lihtsalt parooli muutmise või tulemüüri reegliga, sest viga paikneb tarkvarakoodis endas.

---

# 10. Command Injection ehk käsu süstimine

**Command injection ehk käsu süstimine** tähendab olukorda, kus programm kasutab kasutaja või muu välise allika antud andmeid operatsioonisüsteemi käsu osana viisil, mis võimaldab sisendil muuta käsu tegelikku tähendust.

Probleemi mõistmiseks tuleb aru saada mõistest **shell**. Shell on programm, mis võtab vastu käske ja käivitab teisi programme. Linuxis on näiteks Bash ja Zsh; Windowsis kasutatakse muu hulgas Command Prompt'i ja PowerShelli.

Kui programm ehitab käsu kokku selliselt:

```text
käsk = "programm " + kasutaja_sisend
```

siis võib kontrollimata sisend muuta kogu käsu tähendust.

Turvalisem lahendus on kasutada funktsiooni, mis ei anna kasutaja sisendile võimalust muutuda uueks käsuks.

Command injection erineb tavalisest code injection'ist selle poolest, et siin on otseseks sihtmärgiks käsutöötlus või operatsioonisüsteemi käsu täitmise mehhanism.

### Kuidas command injection tehniliselt tekib?

```text
Kasutaja sisend
      │
      ▼
Rakendus
      │
      ▼
"ehita shell-käsk"
      │
      X
      │
      ▼
Sisend muudab käsu struktuuri
      │
      ▼
Shell
      │
      ▼
Soovimatu käsk
```

Näiteks võib rakendus võtta kasutajalt faili nime ja anda selle shellile.

Kui rakendus ei erista andmeid ja käske, võib kasutaja sisend muutuda käsu osaks.

Sellepärast on väga oluline põhimõte:

> **Andmeid ei tohi käsitleda käskudena.**

### Mida ründaja pärast command injection'i teha võib?

Mõju sõltub sellest, millise kasutaja õigustes käsk töötab.

Kui rakendus töötab tavakasutajana, võib ründaja saada selle kasutaja õigused. Kui rakendus töötab administraatori või root-kasutajana, võib mõju olla palju suurem.

Käsusüst võib võimaldada näiteks failide lugemist, programmide käivitamist, süsteemiseadete muutmist või järgmise ründeetapi käivitamist.

```text
Command injection
       │
       ▼
Koodi/käsu käivitamine
       │
       ▼
Rakenduse kasutaja õigused
       │
       ▼
Privilege escalation
       │
       ▼
Süsteemi kompromiteerimine
```

### Windows

**CVE-2025-54100 – Windows PowerShell**

Windows PowerShellis tuvastati command injection ehk CWE-77 probleem. Microsofti kirjelduses võimaldas vigane käsu neutraliseerimine lokaalselt koodi käivitada. CVE rakendus Windows PowerShellile ning paranduseks tuli paigaldada Microsofti turvauuendus. ([NVD][30])

**CVE-2026-50488 – Windows Clipboard User Service**

Windows Clipboard User Service'is tuvastati command injection, mille kaudu võis autoriseeritud ründaja lokaalselt õigusi suurendada. Mõjutatud olid Windows 11 ja Windows Server 2025 teatud versioonid. CVE-kirje järgi ei olnud 2026. aasta juulis CISA aktiivse ärakasutamise märget. ([OpenCVEmber][31])

### Linux

Linuxis tuleb command injection'i puhul eristada operatsioonisüsteemi enda kerneli haavatavust ja rakendust, mis kasutab shelli.

Linuxi süsteemides on selliseid vigu ajalooliselt esinenud näiteks haldustööriistades, veebirakendustes ja skriptides, kus kasutaja sisend ühendatakse shell-käsuga.

Administraatori seisukohalt on oluline, et **shelli kasutamine ei ole automaatselt turvaviga**. Probleem tekib siis, kui välise sisendi ja käsu vahel puudub korrektne eraldus.

### macOS

**CVE-2019-8513**

macOS Mojave süsteemis parandati command injection'i probleem, mille tõttu võis lokaalne kasutaja käivitada suvalisi shell-käske. Haavatavus parandati macOS Mojave 10.14.4 versioonis. ([NVD][32])

**CVE-2020-9862**

Apple'i Web Inspectoris oli command injection'i probleem. Apple'i kirjelduses võis URL-i kopeerimine Web Inspectorist viia command injection'ini; probleem lahendati parema escaping'u abil. See mõjutas muu hulgas iOS-i, macOS-i ja Safari versioone. ([NVD][33])

### Android

**CVE-2021-0363**

Androidi `mobile_log_d` komponendis tuvastati command injection, mille põhjuseks oli puudulik bounds checking. Probleem võis viia lokaalse õiguste suurendamiseni süsteemiõigustes. Mõjutatud olid Android 10 ja 11. ([NVD][34])

**CVE-2021-0364**

Samuti `mobile_log_d` komponendis esinenud command injection oli seotud puuduliku sisendi valideerimisega. Mõju võis olla lokaalne õiguste suurendamine. ([NVD][35])

Need näited on head selleks, et näha, kuidas kaks erinevat programmeerimisprobleemi — ebapiisav bounds checking ja input validation — võivad viia sama põhiklassi, command injection'ini.

### iOS

**CVE-2020-9862**

Apple'i Web Inspectoris esinenud command injection mõjutas iOS-i kuni iOS 13.6-ni. Probleem tekkis URL-i käsitlemisel ning lahendus oli sisendi parem escaping. ([NVD][33])

**CVE-2024-27818**

Apple'i iOS-i ja macOS-i puhul registreeritud CVE-2024-27818 on klassifitseeritud CWE-77 command injection'iks. Apple parandas selle iOS 17.5 ja iOS 16.7.8 versioonides. CVE kirje järgi ei olnud CISA-l selle kohta aktiivse ärakasutamise märget. ([NVD][36])

Siin tuleb tähele panna, et CVE kirjeldus ei tähenda tingimata, et iga command injection annab kohe süsteemi täieliku kontrolli. Mõju sõltub sellest, millises komponendis probleem paikneb, milliste õigustega see töötab ja millist kasutaja tegevust ärakasutamine eeldab.

## Kuidas riski vähendada?

Kõige parem lahendus on vältida shelli kasutamist olukordades, kus seda pole tegelikult vaja. Kui programm peab siiski käivitama välise programmi, tuleb kasutada turvalist API-d, kus käsu programm ja argumendid on eraldi määratud.

Kasutaja sisend tuleb valideerida lubatud väärtuste järgi. Näiteks kui kasutaja peab valima failinime, ei ole mõistlik lubada suvalist käsusüntaksit. Rakendus peaks kasutama lubatud väärtuste loendit ehk **allowlist'i**.

Kui shelli kasutamine on vältimatu, tuleb kasutada korrektset escaping'u ja argumentide eraldamist. Samuti peab rakendus töötama võimalikult väikeste õigustega.

PowerShelli, Bash'i või Zsh'i kasutamine administraatorina tähendab, et command injection'i mõju võib olla väga suur. Seetõttu on oluline, et süsteemiteenused ei töötaks põhjendamatult root-, SYSTEM- või administraatoriõigustes.

---

# 6–10 peatükkide omavaheline seos

Need viis haavatavuse klassi on väga hea näide sellest, miks ainult haavatavuse nime teadmisest ei piisa.

Üks võimalik ründeahel on:

```text
Pahatahtlik sisend
       │
       ▼
Buffer Overflow
       │
       ▼
Out-of-Bounds Write
       │
       ▼
Mälu rikkumine
       │
       ▼
Use-After-Free
       │
       ▼
Koodi käivitamine
       │
       ▼
Privilege Escalation
```

Teine ründeahel võib olla:

```text
Kontrollimata sisend
       │
       ▼
Command Injection
       │
       ▼
Käsu käivitamine
       │
       ▼
Tavalise kasutaja õigused
       │
       ▼
Null Pointer / kerneliviga / muu haavatavus
       │
       ▼
Privilege Escalation
```

Samuti võib mäluturbe viga anda ründajale ainult informatsiooni:

```text
Out-of-Bounds Read
       │
       ▼
Mälust lekib aadress või muu info
       │
       ▼
Ründaja saab rohkem teadmisi süsteemi kohta
       │
       ▼
Järgmise haavatavuse ärakasutamine
```

Seega ei ole iga haavatavuse otsene tagajärg süsteemi täielik kompromiteerimine. Mõnikord on haavatavuse väärtus ründajale selles, et see eemaldab ühe kaitsekihi või annab järgmise ründe jaoks vajaliku info.

## Mäluturbe probleemide ühine loogika

Buffer overflow, out-of-bounds read/write, use-after-free ja null pointer dereference kuuluvad kõik laiemasse **memory safety ehk mäluturbe** valdkonda.

```text
                    MÄLUTURVE
                       │
        ┌──────────────┼──────────────┐
        │              │              │
        ▼              ▼              ▼
Buffer Overflow    Out-of-Bounds   Use-After-Free
        │              │              │
        │              │              │
        └──────────────┼──────────────┘
                       │
                       ▼
              Mälu vale kasutamine
                       │
              ┌────────┴────────┐
              ▼                 ▼
             DoS          Koodi käivitamine
                                │
                                ▼
                       Õiguste suurendamine
```

Tänapäeva operatsioonisüsteemid kasutavad nende probleemide mõju vähendamiseks mitut kaitsekihti. Android dokumenteerib näiteks ASan-i, HWASan-i ja MTE kasutamist mäluturbe vigade tuvastamiseks või leevendamiseks. ([Android Open Source Project][28])

Oluline on aga mõista, et **kaitsemehhanism ei kõrvalda programmeerimisviga**. See muudab selle ärakasutamise raskemaks või aitab vea avastada.

Samuti ei ole "mäluturbe viga" alati sama mis "buffer overflow". Näiteks use-after-free võib toimuda täiesti ilma puhvri piiride ületamiseta.

Seetõttu tuleb õppida eristama:

* **buffer overflow** – kirjutatakse puhvrist välja;
* **out-of-bounds read** – loetakse lubatud alast väljaspool;
* **out-of-bounds write** – kirjutatakse lubatud alast väljapoole;
* **use-after-free** – kasutatakse juba vabastatud mälu;
* **null pointer dereference** – kasutatakse kehtetu/null-pointeri kaudu mälu;
* **command injection** – välise sisendi abil muudetakse käsu tähendust.

Need erinevad mehhanismid võivad aga lõppeda sama tulemusega: **koodi käivitamise, andmelekke, teenusetõkestuse või õiguste suurendamisega**.

### Kontrollitud allikate põhipunktid

Selles osas kasutatud näidete puhul kontrollisin muu hulgas Microsofti CVE-kirjeid, Ubuntu Security Notices'i, Apple'i ametlikke turvabülletääne ning Androidi ametlikke turvaallikaid. Näiteks Ubuntu kirjeldab Dirty Pipe'i, OOB-, UAF- ja nullpointeri probleeme konkreetsete Linuxi kerneli komponentide juures; Microsofti 2025. aasta CVE-kirjed sisaldavad Windowsi UAF-, OOB- ja nullpointeri juhtumeid; Apple'i 2025–2026 turvavärskendustes on arvukalt buffer overflow, OOB ja UAF parandusi. ([Ubuntu][37])

Androidi puhul on eriti oluline, et ametlikud bülletäänid eristavad näiteks lokaalse ja kaugõiguste suurendamise ning koodi kaugkäivitamise probleeme ning määravad seadme kaitsmiseks konkreetse **security patch level'i**. 2026. aasta septembri bülletäänis on näiteks CVE-2026-28664 Android Runtime'i lokaalse õiguste suurendamise ja CVE-2026-28666 Framework'i kaugõiguste suurendamise näited. ([Android Open Source Project][38])

Apple'i 2026. aasta turvavärskendused näitavad omakorda hästi, et üks operatsioonisüsteem võib sisaldada väga erinevaid haavatavuse klasse: WebKitis, kernelis, failisüsteemis, draiverites, meediatöötluses ja süsteemiteenustes. ([Apple Support][6])

**Järgmine osa on 11–15:**
**Code Injection → Path Traversal → Insecure Deserialization → Improper Input Validation → Cryptographic Failures.** Seal muutuvad eriti oluliseks programmide ja andmete piir, failisüsteemi turvalisus, serialiseeritud objektid ning krüptograafia tegelik roll süsteemi turvalisuses.

[1]: https://www.cve.org/CVERecord?id=CVE-2024-38115&utm_source=chatgpt.com "CVE Record: CVE-2024-38115"
[2]: https://www.cve.org/CVERecord?id=CVE-2024-20696&utm_source=chatgpt.com "CVE Record: CVE-2024-20696"
[3]: https://ubuntu.com/security/notices/USN-5683-1?utm_source=chatgpt.com "USN-5683-1: Linux kernel (IBM) vulnerabilities | Ubuntu security notices | Ubuntu"
[4]: https://ubuntu.com/security/notices/USN-5362-1?utm_source=chatgpt.com "USN-5362-1: Linux kernel (Intel IOTG) vulnerabilities | Ubuntu security notices | Ubuntu"
[5]: https://support.apple.com/en-us/127117?utm_source=chatgpt.com "About the security content of macOS Sonoma 14.8.7 - Apple Support"
[6]: https://support.apple.com/en-us/149043?utm_source=chatgpt.com "About the security content of macOS Sequoia 15.8 - Apple Support"
[7]: https://source.android.com/docs/security/bulletin/2015-08-01?hl=en&utm_source=chatgpt.com "Nexus Security Bulletin—August 2015  |  Android Open Source Project"
[8]: https://nvd.nist.gov/vuln/detail/CVE-2025-24209?utm_source=chatgpt.com "NVD - CVE-2025-24209"
[9]: https://nvd.nist.gov/vuln/detail/CVE-2025-24237?utm_source=chatgpt.com "NVD - CVE-2025-24237"
[10]: https://www.cve.org/CVERecord?id=CVE-2025-55681&utm_source=chatgpt.com "CVE Record: CVE-2025-55681"
[11]: https://www.cve.org/CVERecord?id=CVE-2025-33065&utm_source=chatgpt.com "CVE Record: CVE-2025-33065"
[12]: https://ubuntu.com/security/notices/USN-6285-1?utm_source=chatgpt.com "USN-6285-1: Linux kernel (OEM) vulnerabilities | Ubuntu security notices | Ubuntu"
[13]: https://ubuntu.com/security/notices/USN-6767-2?utm_source=chatgpt.com "USN-6767-2: Linux kernel (BlueField) vulnerabilities | Ubuntu security notices | Ubuntu"
[14]: https://support.apple.com/en-us/148286?utm_source=chatgpt.com "About the security content of Safari 26.6.1 - Apple Support"
[15]: https://support.apple.com/en-us/149041?utm_source=chatgpt.com "About the security content of iOS 26.7 and iPadOS 26.7 - Apple Support"
[16]: https://source.android.com/docs/security/test/hwasan?utm_source=chatgpt.com "Hardware-assisted AddressSanitizer  |  Android Open Source Project"
[17]: https://www.cve.org/CVERecord?id=CVE-2025-54101&utm_source=chatgpt.com "CVE Record: CVE-2025-54101"
[18]: https://www.cve.org/CVERecord?id=CVE-2025-27476&utm_source=chatgpt.com "CVE Record: CVE-2025-27476"
[19]: https://ubuntu.com/security/notices/USN-5693-1?utm_source=chatgpt.com "USN-5693-1: Linux kernel (OEM) vulnerabilities | Ubuntu security notices | Ubuntu"
[20]: https://ubuntu.com/security/notices/USN-5505-1?utm_source=chatgpt.com "USN-5505-1: Linux kernel vulnerabilities | Ubuntu security notices | Ubuntu"
[21]: https://nvd.nist.gov/view/vuln/detail?vulnId=CVE-2025-22403&utm_source=chatgpt.com "NVD - CVE-2025-22403"
[22]: https://nvd.nist.gov/vuln/detail/CVE-2025-22409?utm_source=chatgpt.com "NVD - CVE-2025-22409"
[23]: https://support.apple.com/en-us/128073?utm_source=chatgpt.com "About the security content of Safari 26.6 - Apple Support"
[24]: https://support.apple.com/en-ae/125884?utm_source=chatgpt.com "About the security content of iOS 26.2 and iPadOS 26.2 - Apple Support (AE)"
[25]: https://www.cve.org/CVERecord?id=CVE-2025-33057&utm_source=chatgpt.com "CVE Record: CVE-2025-33057"
[26]: https://www.cve.org/CVERecord?id=CVE-2025-29838&utm_source=chatgpt.com "CVE Record: CVE-2025-29838"
[27]: https://ubuntu.com/security/notices/USN-6976-1?utm_source=chatgpt.com "USN-6976-1: Linux kernel vulnerabilities | Ubuntu security notices | Ubuntu"
[28]: https://source.android.com/docs/security/test/asan?hl=en&utm_source=chatgpt.com "AddressSanitizer  |  Android Open Source Project"
[29]: https://advisories.ncsc.nl/2025/ncsc-2025-0143.html?utm_source=chatgpt.com "NCSC NL | Beveiligingsadviezen"
[30]: https://nvd.nist.gov/vuln/detail/CVE-2025-54100?utm_source=chatgpt.com "NVD - CVE-2025-54100"
[31]: https://opencve.alliance.unm.edu/cve/CVE-2026-50488?utm_source=chatgpt.com "CVE-2026-50488 - Vulnerability Details - OpenCVE"
[32]: https://nvd.nist.gov/vuln/detail/CVE-2019-8513?utm_source=chatgpt.com "NVD - CVE-2019-8513"
[33]: https://nvd.nist.gov/vuln/detail/cve-2020-9862?utm_source=chatgpt.com "NVD - cve-2020-9862"
[34]: https://nvd.nist.gov/vuln/detail/CVE-2021-0363?utm_source=chatgpt.com "NVD - CVE-2021-0363"
[35]: https://nvd.nist.gov/vuln/detail/CVE-2021-0364?utm_source=chatgpt.com "NVD - CVE-2021-0364"
[36]: https://nvd.nist.gov/vuln/detail/cve-2024-27818?utm_source=chatgpt.com "NVD-CVE-2024-27818"
[37]: https://ubuntu.com/security/CVE-2022-0847?utm_source=chatgpt.com "CVE-2022-0847 | Ubuntu"
[38]: https://source.android.com/docs/security/bulletin/2026/2026-09-01?utm_source=chatgpt.com "Android Security Bulletin—September 2026  |  Android Open Source Project"
