
# Haavatavused

# Peatükid 1-5

## Sissejuhatus

Arvuti, serveri, telefoni või muu digitaalse seadme turvalisus ei sõltu ainult sellest, kas seadmesse on paigaldatud viirusetõrje või tulemüür. Turvalisuse oluline osa on ka kasutatav tarkvara ise ning see, kuidas tarkvara töötleb kasutaja, võrgu, failide ja teiste programmide antud sisendit. Tarkvaras võivad olla programmeerimis- ja arhitektuurivead, mis võimaldavad teha midagi sellist, mida tarkvara autor ei ole ette näinud. Sellist turvanõrkust nimetatakse **haavatavuseks (vulnerability)**.

Haavatavus ei ole veel iseenesest rünnak. Näiteks võib operatsioonisüsteemis olla avastatud viga, kuid kuni keegi seda viga ründamiseks ei kasuta, ei ole tegemist veel konkreetse rünnakuga. Haavatavuse ärakasutamiseks kasutatavat tehnikat või programmi nimetatakse **ärakasutuseks (exploit)**. Kui ründaja kasutab seda ärakasutust reaalses keskkonnas, toimub **haavatavuse ärakasutamine (exploitation)**.

Haavatavus võib võimaldada näiteks kasutajaõigustest möödumist, tundlike andmete lugemist, programmi kokkujooksmist, pahatahtliku koodi käivitamist või isegi kogu arvuti üle kontrolli saavutamist. Kõik haavatavused ei ole sama mõjuga. Mõni viga võimaldab ainult teenuse töö katkestada, teine võib aga anda ründajale süsteemi administraatoriõigused.

Oluline on mõista ka mõistet **CVE (Common Vulnerabilities and Exposures)**. CVE on standardiseeritud identifikaator, millega tähistatakse konkreetset avalikult kirjeldatud haavatavust. Näiteks **CVE-2022-0847** tähistab Linuxi kernelis avastatud Dirty Pipe'i nime all tuntud haavatavust. CVE-number ise ei ole ohu- ega raskusaste. Haavatavuse tehnilist raskust saab kirjeldada näiteks CVSS-skooriga, kuid CVE ja CVSS on kaks erinevat asja.

<details>
  <summary> <kbd><b>Dirty Pipe (ametlikult tähistatud kui CVE-2022-0847)</b> on 2022. aasta alguses avastatud kriitiline turvaviga Linuxi tuumas (kernelis)</kbd></summary>
<i>
**Dirty Pipe** (ametlikult tähistatud kui **CVE-2022-0847**) on 2022. aasta alguses avastatud kriitiline turvaviga Linuxi tuumas (kernelis). Tegemist on kohaliku õiguste eskaleerimise (Local Privilege Escalation – LPE) haavatavusega, mis võimaldas tavakasutajal, kellel puuduvad administraatori õigused, kirjutada andmeid suvalistesse failidesse – isegi nendesse, mis on süsteemis märgitud kui kirjutuskaitstud (read-only).

Seda viga võrreldakse sageli ajaloolise "Dirty COW" (CVE-2016-5195) haavatavusega, kuid Dirty Pipe oli koodi tasemel lihtsamini ja stabiilsemalt ära kasutatav, muutes selle ohtlikumaks.

### Kuidas haavatavus tehniliselt töötas?

Süsteemi tuum haldab failide lugemist ja kirjutamist mälulehtede (page cache) abil, et vältida pidevat kettapoole pöördumist. Viga tekkis sellest, kuidas kernel käsitles andmete liigutamist mälulehtede ja Linuxi torude (pipes) vahel.

1. **Torude loomine ja seadistamine:** Ründaja loob süsteemis toru (sarnane käsureal kasutatavale `|` operaatorile). Torusse andmeid kirjutades ja sealt lugedes saab ründaja manipuleerida toru mälupuhvri lippudega.
2. **Lipu viga:** Kerneli koodis oli loogikaviga. Kui toru teatud viisil tühjaks loeti, jäi mälu struktuuri aktiivseks lipp nimega `PIPE_BUF_FLAG_CAN_MERGE`. See lipp ütleb kernelile, et uute andmete saabumisel tohib need otse eelmiste andmete külge "liita", selle asemel et uut mälulehte eraldada.
3. **Faili mälulehe ühendamine (Splice):** Kasutades süsteemikutsungit `splice()`, ühendas ründaja soovitud kirjutuskaitstud faili (näiteks süsteemi paroolifaili) otse sellesse torusse.
4. **Ülekirjutamine:** Kuna torus oli eelmisest sammust jäänud aktiivseks `CAN_MERGE` lipp, siis nüüd torusse uusi andmeid kirjutades eeldas kernel, et tegemist on liidetava puhvriga, ja kirjutas ründaja uued andmed otse kirjutuskaitstud faili mälulehele (page cache). Tulemusena sünkroniseeriti ründaja sisestatud muudatused ka füüsilisele kettale.

### Reaalsete kasutusjuhtude ja rünnakute näited

Haavatavuse ilmsikstulekul loodi kiiresti mitmeid töötavaid (Exploit/PoC) skripte, mida kasutati nii pahatahtlikes kampaaniates kui ka turvatestijate (Red Teaming) ja hobikasutajate poolt.

* **Administraatori (root) konto ülevõtmine failide muutmise teel:**
Kõige levinum näide ründest oli `/etc/passwd` faili muutmine. Ründaja asendas failis algse root-kasutaja rea uuega, millel puudus paroolinõue (kustutades "x" tähise). See võimaldas ründajal sisestada käsu `su root` ja saada koheselt administraatori õigused ilma parooli teadmata. See on klassikaline stsenaarium, mida käsitletakse ka küberturvalisuse õppematerjalides ja CTF (Capture The Flag) võistlustel.
* **Androidi seadmete "ruutimine" (Rooting):**
Kuna Androidi operatsioonisüsteem baseerub Linuxi kernelil, olid paljud uuemad nutitelefonid Dirty Pipe'i suhtes haavatavad. Tehnoloogiaentusiastid ja turvauurijad kasutasid seda viga ära näiteks **Google Pixel 6** ja **Samsung Galaxy S22** telefonides root-õiguste saamiseks. Haavatavuse kaudu kirjutati üle süsteemi alglaadimise või taustaprotsesside faile, et käivitada seadmes piiramatute õigustega kasutajakeskkond ja minna mööda tootja paigaldatud lukustustest.
* **SUID-programmide ja SSH-võtmete ülekirjutamine:**
Teine ründemeetod oli kirjutada pahatahtlikku koodi (shellcode) süsteemsetesse SUID-õigustega programmidesse (nagu `/usr/bin/su` või `/usr/bin/sudo`). Kui ründaja kirjutas programmi sisse oma koodi, siis järgmine kord, kui administraator või süsteem ise seda programmi käivitas, avati ründajale taustal "tagauks" (reverse shell) kõrgeimate õigustega. Samuti kasutati tehnikat, kus ründaja kirjutas üle lokaalselt loetavaid SSH avalikke võtmeid (näiteks kellegi teise `authorized_keys` faili), et endale ligipääs tagada.

Viga oli olemas Linuxi kerneli versioonides alates 5.8 ja see parandati veebruaris 2022 kerneli versioonides 5.16.11, 5.15.25 ja 5.10.102.
</i>
</details>


Operatsioonisüsteemi puhul võib haavatavus paikneda väga erineval tasemel. Viga võib olla näiteks **kernelis (kernel)**, mis on operatsioonisüsteemi keskne osa ja vahendab riistvara ning programmide vahelist suhtlust. Viga võib olla **draiveris (driver)**, mis võimaldab operatsioonisüsteemil kasutada konkreetset riistvara. Samuti võib probleem paikneda võrguteenuses, failide töötlemise komponendis, brauseris, autentimisteenuses või mõnes süsteemiga kaasas olevas teegis.

Haavatavused tekivad sageli seetõttu, et tarkvara peab töötlema väga palju erinevat sisendit. Sisend võib tulla kasutajalt, veebilehelt, failist, USB-seadmest, võrgupaketist või teisest programmist. Kui programm eeldab ekslikult, et sisend on alati korrektne, võib spetsiaalselt koostatud sisend põhjustada ootamatu olukorra.

Selles õppematerjalis vaadeldakse haavatavusi tehnilisest vaatenurgast. Eesmärk ei ole õppida ründama päris süsteeme, vaid mõista, **miks haavatavused tekivad, mida need võimaldavad ning kuidas süsteemiadministraator ja arendaja saavad riski vähendada**.

---

# 1. Unpatched and Outdated Software ehk paikamata ja vananenud tarkvara

Paikamata tarkvara tähendab olukorda, kus kasutusel olevast programmist või operatsioonisüsteemist on olemas turvaparandus, kuid seda parandust ei ole süsteemi paigaldatud. Vananenud tarkvara tähendab omakorda, et kasutatakse versiooni, mille tootja võib olla juba välja vahetanud või mille turvatoe periood on lõppenud. Mõlemad olukorrad suurendavad võimalust, et süsteemis olevat teadaolevat haavatavust saab ära kasutada.

Turvapaik ehk **security patch** on tarkvarauuendus, millega tootja parandab näiteks konkreetse programmeerimisvea või turvanõrkuse. Paik ei tähenda tingimata kogu programmi uut versiooni. Mõnikord parandatakse ainult üks konkreetne funktsioon või komponent.

Probleem seisneb selles, et pärast haavatavuse avalikustamist ei ole ründajal enam vaja viga ise avastada. CVE kirjeldus, tootja turvabülletään ja turvauuringud võivad anda piisavalt infot selle kohta, milline komponent on vigane. Kui süsteemiadministraator parandust ei paigalda, võib haavatav süsteem jääda ründajale avatuks.

Eriti ohtlik on olukord, kus haavatavus mõjutab internetist kättesaadavat teenust. Kui teenus kuulab näiteks TCP-porti ja võtab vastu väliseid ühendusi, võib ründaja proovida haavatavust kasutada ilma füüsilise ligipääsuta.

Paikamata süsteemi risk suureneb veelgi siis, kui haavatavus on juba avalikult dokumenteeritud ja selle ärakasutamine on reaalselt toimunud. Sellisel juhul ei ole tegemist ainult teoreetilise probleemiga.

### Kuidas paikamata tarkvara haavatavus tehniliselt tekib?

Tüüpiline protsess on järgmine:

```text
Tarkvaras on programmeerimisviga
              │
              ▼
Turvauurija või tootja avastab vea
              │
              ▼
Haavatavusele määratakse CVE
              │
              ▼
Tootja valmistab turvapaiga
              │
              ▼
Administraator ei paigalda paika
              │
              ▼
Haavatav süsteem jääb kasutusse
              │
              ▼
Ründaja kasutab teadaolevat viga
```

Oluline on mõista, et paigaldamata turvaparandus ei loo uut haavatavust. Haavatavus oli tarkvaras juba olemas. Paiga puudumine tähendab, et teadaolev probleem jääb süsteemis lahendamata.

Näiteks võib operatsioonisüsteemi võrguteenuses olla viga, mille kaudu saab spetsiaalselt koostatud võrgupakett põhjustada mälu rikkumise. Tootja parandab vea. Kui serveri administraator parandust ei paigalda, töötab server endiselt vana koodiga.

### Mida ründaja pärast paikamata haavatavuse kasutamist teha võib?

Mõju sõltub konkreetsest haavatavusest. Mõni viga võimaldab ainult teenuse katkestamist. Teine võib võimaldada **RCE-d (Remote Code Execution)** ehk koodi kaugkäivitamist.

Kui koodi saab käivitada serveri protsessi õigustes, sõltub järgmine mõju sellest, milliste õigustega protsess töötab. Kui teenus töötab piiratud kasutajana, on mõju väiksem kui juhul, kui teenusel on süsteemi- või administraatoriõigused.

Ründaja võib kasutada üht haavatavust ka esimese sammuna pikemas ründeahelas:

```text
Paikamata teenus
       │
       ▼
Esialgne kompromiteerimine
       │
       ▼
Kohalik ligipääs
       │
       ▼
Õiguste suurendamine
       │
       ▼
Püsivus / andmete kogumine
       │
       ▼
Süsteemi või võrgu kahjustamine
```

### Windows

**CVE-2021-1732 – Win32k**

Microsoft Windowsi Win32k komponendis olnud haavatavus võimaldas lokaalset õiguste suurendamist. Ründajal pidi juba olema võimalus süsteemis koodi käivitada. Seejärel võis haavatavust kasutada selleks, et liikuda madalama õigustasemega protsessist kõrgema õigustasemega keskkonda. Haavatavus kuulus nende hulka, mille reaalset ärakasutamist on CISA KEV kataloogis dokumenteeritud.

**CVE-2020-1472 – Netlogon / Zerologon**

Windows Serveri Netlogoni protokollis olnud viga võimaldas domeenikeskkonnas väga tõsise õiguste suurendamise ründe. Netlogon on Windowsi domeenikeskkonna oluline autentimise ja turvasuhte komponent. Haavatavus oli eriti ohtlik domeenikontrolleritele ning CISA on dokumenteerinud selle reaalse ärakasutamise. Lahendus on paigaldada Microsofti turvaparandused ja tagada domeenikontrollerite regulaarne uuendamine.

### Linux

**CVE-2022-0847 – Dirty Pipe**

Linuxi kernelis olnud Dirty Pipe'i nime all tuntud viga võimaldas teatud olukordades kirjutada andmeid failidega seotud kernelipuhvritesse viisil, mis ei olnud tavapäraste failisüsteemi õigustega lubatud. Seda sai kasutada kohaliku õiguste suurendamise saavutamiseks. CISA KEV kataloog sisaldab selle haavatavuse kohta reaalse ärakasutamise infot.

**CVE-2022-34918**

Linuxi kernelis tuvastatud haavatavus näitab samuti, miks kernelitaseme vead on eriti olulised. Kernel töötab kõrgemate õigustega kui tavalised kasutajaprotsessid. Kui ründaja suudab kernelis oleva vea abil saavutada kontrolli kerneli koodi või andmete üle, võib tagajärjeks olla kogu süsteemi kompromiteerimine.

### macOS

macOS-i puhul ei tähenda süsteemi uuendamine ainult operatsioonisüsteemi põhiversiooni muutmist. Apple'i turvauuendused parandavad ka WebKiti, süsteemiteekide, graafikakomponentide ja muude süsteemiga kaasas olevate komponentide vigu.

Näiteks Apple'i turvabülletäänides avaldatakse CVE-d, mille parandamine toimub macOS-i turvauuenduste kaudu. Seetõttu ei tohiks macOS-i puhul lähtuda ainult sellest, kas kasutusel on "uus macOS", vaid kontrollida konkreetset turvapaiga taset.

### Android

Androidi puhul on oluline mõista turvapaiga taset (**security patch level**). Androidi seadmel võib olla sama operatsioonisüsteemi põhiversioon, kuid erinev turvapaiga tase.

2026. aasta septembri Androidi turvabülletään kirjeldab näiteks **CVE-2026-28664**, mis mõjutab Android Runtime'i ja võib viia kohaliku õiguste suurendamiseni. Bülletääni järgi ei vaja ärakasutamine täiendavaid täitmisõigusi ega kasutaja tegevust. ([Android Open Source Project][1])

Samuti kirjeldatakse **CVE-2026-28666**, mis mõjutab Android Framework'i ja võib viia kaugõiguste suurendamiseni. Android märgib selle kriitiliseks ning kirjeldab, et ärakasutamine ei vaja täiendavaid täitmisõigusi ega kasutaja tegevust. ([Android Open Source Project][1])

### iOS

Apple'i seadmetes avaldatakse turvaparandused iOS-i versiooniuuenduste kaudu. Näiteks iOS 26.7 turvabülletään kirjeldab **CVE-2026-43715**, mis oli WebKiti use-after-free tüüpi viga ja võis põhjustada mälu rikkumist. ([Apple Support][2])

Samas uuenduses parandati ka **CVE-2026-84617**, mis oli XPC komponendi autoriseerimisprobleem ning võis võimaldada rakendusel pääseda ligi tundlikele kasutajaandmetele. ([Apple Support][2])

### Kuidas riski vähendada?

Kõige olulisem meede on regulaarne paigahaldus ehk **patch management**. Organisatsioon peaks teadma, millised operatsioonisüsteemid ja tarkvaraversioonid tema seadmetes töötavad. Turvauuendusi tuleb hinnata nende mõju ja kiireloomulisuse järgi. Internetist kättesaadavad süsteemid vajavad eriti kiiret uuendamist. Vananenud ja tootja toe kaotanud tarkvara tuleks asendada või eraldada.

Oluline on ka automaatsete uuenduste kasutamine seal, kus see on organisatsiooni töökorraldusega kooskõlas. Serverikeskkonnas tuleb uuendused enne laialdast kasutuselevõttu kontrollida, et vältida teenuse katkemist.

Paigahaldus ei tähenda ainult Windows Update'i käivitamist. See hõlmab ka Linuxi pakette, macOS-i komponente, Androidi turvapaiga taset, iOS-i versiooni, rakendusi, draivereid ja kolmandate osapoolte komponente.

---

# 2. Insecure Configuration ehk ebaturvaline konfiguratsioon

**Ebaturvaline konfiguratsioon (insecure configuration)** tähendab olukorda, kus tarkvara ise võib olla tehniliselt korrektne, kuid süsteem on seadistatud viisil, mis suurendab turvariski.

Näiteks võib serveris olla lubatud teenus, mida tegelikult ei vajata. Samuti võib kasutusel olla vaikimisi administraatorikonto, liiga lai tulemüürireegel, nõrk autentimismeetod või liiga avatud failijagamine.

Konfiguratsioon on sisuliselt reeglite kogum, mille järgi süsteem otsustab, kuidas ta töötab. Operatsioonisüsteemil, veebiserveril, andmebaasil ja võrguseadmel on kõigil konfiguratsioon.

Turvaline tarkvara võib muutuda ebaturvaliseks, kui administraator muudab konfiguratsiooni valesti.

Näiteks võib SSH-teenus olla ise turvaline, kuid kui server lubab parooliga administraatorina otse sisse logida ja server on internetist kättesaadav, suureneb ründerisk.

### Kuidas konfiguratsioonihaavatavus tekib?

```text
Turvaline komponent
        │
        ▼
Administraator muudab konfiguratsiooni
        │
        ├── liiga lai ligipääs
        ├── ebavajalik teenus
        ├── nõrk autentimine
        └── vaikeseaded
                │
                ▼
        Suurenenud ründepind
                │
                ▼
             Rünnak
```

**Ründepind (attack surface)** tähendab kõiki kohti, mille kaudu süsteemiga saab suhelda. Mida rohkem teenuseid, porte, kasutajakontosid ja väliseid liideseid on kasutusel, seda suurem võib olla ründepind.

### Mida ründaja teha võib?

Ründaja võib kõigepealt otsida süsteemi konfiguratsioonist nõrku kohti. Näiteks võib ta avastada internetist avatud haldusliidese või teenuse, millele ei peaks väljast ligi pääsema.

Kui konfiguratsioon lubab liiga palju, võib ründaja pääseda ligi andmetele, muuta süsteemi seadeid või kasutada teenust järgmise ründeetapina.

Konfiguratsiooniprobleem võib seega olla otsene turvaviga või aidata mõnel teisel haavatavusel muutuda palju ohtlikumaks.

### Windows

Windows Serveris on tüüpilised konfiguratsiooniriskid näiteks liiga laiad SMB-jagamiste õigused, ebaturvalised RDP-seaded, liiga laiad GPO-d ja administraatorikontode väärkasutus.

**CVE-2020-1472** näitab, kuidas protokollitaseme probleem ja domeenikeskkonna konfiguratsioon võivad koos väga tõsise tagajärjeni viia.

**CVE-2021-34527** ehk PrintNightmare puudutas Windows Print Spooleri teenust. CISA KEV andmetel on seda haavatavust reaalselt ära kasutatud. Kui spooler töötab süsteemis, kus seda pole vaja, võib teenuse piiramine vähendada ründepinda.

### Linux

Linuxi serveris võib ebaturvaline konfiguratsioon tähendada näiteks SSH parooliautentimise lubamist, liigseid sudo-õigusi, liiga avatud failisüsteemi õigusi või internetile avatud haldusliideseid.

**CVE-2022-0847** puhul ei olnud põhiküsimus lihtsalt konfiguratsioon, vaid kerneliviga. Kuid selle näite abil saab näha, miks õiguste ja teenuste vähendamine on oluline: mida vähem võimalusi ründajal enne kerneli haavatavuse kasutamist on, seda väiksem on kogu ründepind.

### macOS

macOS-is mõjutavad turvalisust muu hulgas rakenduste õigused, tulemüür, failijagamine, kaugjuurdepääs ja süsteemi privaatsusseaded.

Apple'i turvamudel kasutab muu hulgas rakenduste **sandbox'i**, mille eesmärk on piirata rakenduse juurdepääsu süsteemi ressurssidele. Kui süsteemi või rakenduse konfiguratsioon lubab rohkem, kui tegelikult vaja, väheneb selle kaitseväärtus.

### Android

Android kasutab rakenduste eraldamiseks õigusi ja sandbox'i. Rakendusele antud liigsed õigused suurendavad kahju juhul, kui rakendus ise kompromiteeritakse.

Näiteks võib pahatahtlik või kompromiteeritud rakendus saada ligipääsu failidele, asukohale või muudele ressurssidele, kui kasutaja on vastavad õigused andnud.

### iOS

iOS kasutab tugevat rakenduste eraldamist ning autoriseerimismehhanisme. Apple'i iOS 26.7 turvabülletäänis kirjeldatud **CVE-2026-84617** näitab, et isegi tugeva sandbox'i korral võib autoriseerimisviga põhjustada olukorra, kus rakendus pääseb ligi tundlikele andmetele. ([Apple Support][2])

### Kuidas riski vähendada?

Konfiguratsioon peab lähtuma põhimõttest **least privilege ehk vähimate õiguste põhimõte**. Teenus peaks töötama ainult siis, kui seda vajatakse. Port peaks olema avatud ainult siis, kui selleks on konkreetne vajadus. Kasutajakontole tuleks anda ainult vajalikud õigused.

Organisatsioon peaks kasutama turvalisi baas-konfiguratsioone ehk **security baselines**. Windowsi puhul saab selleks kasutada näiteks Microsofti turvasoovitusi ja GPO-sid. Linuxis saab kasutada standardiseeritud hardening-juhiseid.

Oluline on konfiguratsiooni muutuste jälgimine. Turvaline süsteem võib muutuda ebaturvaliseks ka pärast kuude või aastate pikkust korrektset töötamist, kui keegi muudab ühe kriitilise seadistuse.

---

# 3. Weak Authentication and Session Management ehk nõrk autentimine ja seansihaldus

**Autentimine (authentication)** tähendab kasutaja või süsteemi identiteedi kontrollimist. Küsimus on: **"Kes sa oled?"**

**Autoriseerimine (authorization)** vastab järgmisele küsimusele: **"Mida sul on lubatud teha?"**

Need kaks mõistet tuleb kindlasti lahus hoida. Kasutaja võib olla korrektselt autentitud, kuid tal ei pruugi olla õigust konkreetsele failile või toimingule.

Autentimiseks kasutatakse näiteks parooli, turvavõtit, sertifikaati, biomeetriat või mitmeastmelist autentimist.

**Seanss (session)** on ajavahemik, mille jooksul süsteem mäletab, et kasutaja on edukalt autentitud. Veebirakenduses võib seanssi tähistada näiteks küpsises või seansitunnuses.

Kui autentimine või seansihaldus on vigane, võib ründaja saada kasutaja identiteedi või seansi üle kontrolli.

### Kuidas probleem tehniliselt tekib?

```text
Kasutaja
   │
   ▼
Kasutajanimi + autentimisandmed
   │
   ▼
Autentimissüsteem
   │
   ▼
Seansitunnus / token
   │
   ▼
Rakendus
   │
   X
   └── halb kontroll
          │
          ▼
  Ründaja kasutab identiteeti
```

**Token** on digitaalne tunnus, mida süsteem kasutab selleks, et tõendada kasutaja või protsessi õigust. Kui token satub vale inimese kätte, võib ründaja esineda ohvrina.

Probleem võib tekkida näiteks nõrga parooli, puuduliku mitmefaktorilise autentimise, liiga pika seansi, ennustatava seansitunnuse või vale väljalogimise tõttu.

<details>
  <summary><kbd><b>Tokeni (sessioonivõtme) natuke pikem seletus</b></kbd></summary>
<kbd>
  <i>
  See lõik kirjeldab, mis juhtub **pärast** seda, kui ründaja on tokeni kätte saanud. Süsteemi vaates on kehtival tokenil ja päris kasutajal võrdusmärk – süsteem ei oska enam vahet teha, kas nupu vajutas õige inimene või tokeni varastanud häkker.

Siin on detailsem lahtiseletus selle kohta, mida ründaja igas kirjeldatud olukorras teha saab:

**1. Kasutaja kontoga sisselogimine (Konto ülevõtmine ja identiteedivargus)**

* **Paroolidest ja MFA-st möödaminek:** Kui ründaja varastab aktiivse tokeni (näiteks seansitunnuse ehk *session cookie*), ei pea ta üldse teadma ohvri parooli. Ta ei vaja isegi nutitelefoni, kuhu saadetakse mitmefaktorilise autentimise (MFA) kood. Token on juba tõestus, et "see kasutaja on edukalt sisse loginud", ning ründaja pääseb otse kontole.
* **Tegevused ohvri nimel:** Ründaja saab lugeda ohvri e-kirju, sooritada tema raha eest oste, varastada isiklikke andmeid või saata ohvri nimel kolleegidele õngitsuskirju, mis tunduvad usaldusväärsed.

**2. Administraatorikonto kompromiteerimine (Süsteemi täielik ülevõtmine)**

* **Maksimaalne häving:** Administraatoril on ligipääs kogu süsteemile. See tähendab, et ründaja võib varastada kogu ettevõtte kliendiandmebaasi, kustutada elutähtsaid faile või paigaldada lunavara (*ransomware*).
* **Tagaukse (*backdoor*) loomine:** Et ligipääsu säilitada ka siis, kui varastatud token aegub või algne turvaauk parandatakse, loob ründaja süsteemi uusi, varjatud administraatorikontosid, mille kaudu ta saab edaspidi igal ajal tagasi tulla.

**3. Tavalise kasutaja konto ja õiguste suurendamine (*Privilege Escalation*)**
Isegi täiesti piiratud õigustega konto (näiteks tavaline foorumi külastaja või ettevõtte madalaima astme töötaja) on ründajale väga väärtuslik. See on "jalg ukse vahel", mis annab ligipääsu süsteemi sisevaatele.

* **Vertikaalne õiguste suurendamine:** Ründaja otsib süsteemi koodist programmeerimisvigu. Näiteks proovib ta saata serverile ootamatuid käske, mis sunniksid süsteemi talle administraatori õigusi andma, ehk ta liigub "alt üles".
* **Horisontaalne õiguste suurendamine:** Ründaja üritab ligi pääseda *teiste samaväärsete* kasutajate andmetele. Näiteks proovib ta muuta veebilehe aadressiribal kasutaja ID numbrit (nt `user_id=123` muudab `user_id=124`) lootuses, et süsteem unustab kontrollida, kas tal on õigus seda teist kontot näha.
* **Külgsuunaline liikumine (*Lateral Movement*):** Olles sisevõrgus autentitud kasutaja, saab ründaja hakata kaardistama teisi servereid ja teenuseid, kuhu otse internetist ligi ei pääse, lootes leida nõrkusi sisesüsteemides.
</i></kbd>
</details>

### Mida ründaja teha võib?

Kui ründaja saab kasutaja autentimisandmed, võib ta sisse logida kasutaja kontoga.

Kui kompromiteeritud konto on administraatorikonto, võib mõju olla väga suur.

Kui kompromiteeritud on tavalise kasutaja konto, võib ründaja proovida kasutada teisi haavatavusi õiguste suurendamiseks.

### Windows

Windowsi keskkonnas on autentimine tihedalt seotud Active Directory, Kerberose ja NTLM-iga.

**CVE-2020-1472 Zerologon** näitas, kui tõsised tagajärjed võivad olla domeeni autentimisega seotud turvaprobleemidel. CISA KEV sisaldab selle kohta reaalse ärakasutamise märget.

Windowsi keskkonnas tuleb lisaks tarkvaravigadele kaitsta ka kasutajakontosid, administraatorikontosid, Kerberose infrastruktuuri ja autentimisprotokolle.

### Linux

Linuxi autentimine toimub sageli PAM-i (**Pluggable Authentication Modules**) kaudu. PAM on mehhanism, mille abil erinevad Linuxi teenused saavad kasutada ühtset autentimisraamistikku.

SSH kaudu sisselogimisel tuleb kaitsta nii kasutajakontosid kui ka autentimisteenust.

Näiteks võivad vananenud OpenSSH komponendid sisaldada turvavigu. Seetõttu peab autentimise turvalisuse vaatlemisel kontrollima nii konfiguratsiooni kui ka kasutatava tarkvara versiooni.

### macOS

macOS kasutab kasutajakontode ja süsteemiteenuste jaoks Apple'i enda autentimis- ja autoriseerimismehhanisme. Samuti kasutatakse Apple'i seadmetes tugevat kasutajaidentiteedi kaitset näiteks võtmehoidja (**Keychain**) abil.

Kui autentimissüsteemi viga võimaldab rakendusel saada ligipääsu teise komponendi kaitstud andmetele, võib see muutuda autoriseerimisprobleemiks.

### Android

Androidi puhul on autentimine seotud nii seadme lukustuse kui ka kasutajakonto ja rakenduste identiteediga.

Androidi septembri 2026 bülletäänis on näiteks mitmeid Framework'i ja System'i haavatavusi, mis on klassifitseeritud õiguste suurendamiseks. See näitab, et autentimise ja õiguste piiril olevad süsteemikomponendid on turvalisuse seisukohalt kriitilised. ([Android Open Source Project][1])

### iOS

iOS-i turvamudel kasutab tugevalt rakenduste eraldamist ja süsteemi autoriseerimismehhanisme.

**CVE-2026-84617** XPC-komponendis oli autoriseerimisprobleem, mille tõttu võis rakendus pääseda ligi tundlikele kasutajaandmetele. See ei ole klassikaline "vale parooliga sisselogimise" probleem, vaid näide sellest, et autentimine ja autoriseerimine koos moodustavad süsteemi identiteedikaitse. ([Apple Support][2])

### Kuidas riski vähendada?

Kasutada tuleb pikki ja unikaalseid paroole ning võimaluse korral **MFA-d (Multi-Factor Authentication)**. Administraatorikontosid ei tohiks kasutada tavapäraseks veebisirvimiseks ega e-posti lugemiseks.

Seansid peavad aeguma ning väljalogimisel tuleb seansitunnus muuta kehtetuks. Seansitunnuseid ei tohi ehitada üles viisil, mis võimaldab neid lihtsalt ära arvata.

Administraator peab jälgima ebatavalisi sisselogimisi, korduvaid autentimisvigu ja olukordi, kus sama kontot kasutatakse ootamatutest asukohtadest või seadmetest.

---

# 4. Broken Access Control ehk vigane ligipääsukontroll

**Ligipääsukontroll (access control)** määrab, milline kasutaja, protsess või rakendus võib konkreetse ressursiga midagi teha.

Ressurss võib olla fail, kaust, andmebaas, API, süsteemiseade või teine kasutaja konto.

Ligipääsukontrolli ülesanne on takistada olukorda, kus kasutaja saab teha midagi, milleks tal puudub õigus.

Oluline on eristada autentimist ja autoriseerimist. Kasutaja võib olla täiesti korrektselt sisse logitud, kuid rakendus peab pärast sisselogimist veel kontrollima, kas tal on õigus konkreetset toimingut teha.

Kui seda kontrolli ei tehta, tekib **Broken Access Control**.

OWASP Top 10:2025 paigutab Broken Access Control kategooriasse A01 ning kirjeldab seda kui olukorda, kus kasutaja saab tegutseda väljaspool talle mõeldud õigusi. ([OWASP Top 10][3])

### Kuidas probleem tekib?

```text
Kasutaja autentitakse
        │
        ▼
"Sina oled kasutaja X"
        │
        ▼
Kasutaja küsib ressurssi Y
        │
        ▼
Rakendus peaks kontrollima õigust
        │
        X
        │
        ▼
Ressurss väljastatakse
```

Näiteks võib kasutaja muuta URL-is või API päringus objekti identifikaatorit. Kui server kontrollib ainult seda, kas kasutaja on sisse logitud, kuid ei kontrolli, kas tal on õigus konkreetsele objektile ligi pääseda, võib tekkida volitamata ligipääs.

Seda tüüpi olukorda nimetatakse sageli **IDOR-iks (Insecure Direct Object Reference)**.

### Mida ründaja teha võib?

Ründaja võib lugeda teise kasutaja andmeid, muuta dokumente, kustutada ressursse või kasutada administraatori funktsioone.

Kõige ohtlikumad on juhtumid, kus kasutaja saab muuta oma rolli või pääseb ligi kõrgema õigustasemega funktsioonidele.

### Windows

Windowsi failisüsteem kasutab ACL-e (**Access Control Lists**). ACL määrab, millisel kasutajal või grupil on failiga lugemise, kirjutamise või täitmise õigus.

Kui ACL on liiga lai, võib tavaline kasutaja saada ligi andmetele, mida ta ei peaks nägema.

**CVE-2021-1732** on näide sellest, kuidas Windowsi komponentide haavatavused võivad viia õiguste suurendamiseni. Sellisel juhul ei ole probleem ainult faililoendis, vaid süsteemitaseme autoriseerimise piiris.

### Linux

Linuxi klassikaline õiguste mudel kasutab omanikku, gruppi ja teisi kasutajaid. Lisaks saab kasutada ACL-e.

**CVE-2022-0847 Dirty Pipe** on näide, kus tavapärane õiguste eraldamine võis teatud tingimustel olla kernelivea tõttu ületatav.

### macOS

macOS kasutab failide ja süsteemiressursside kaitsmiseks UNIX-i õigusi, ACL-e ning täiendavaid Apple'i turvamehhanisme.

Apple'i **sandbox** piirab rakenduse tegevusruumi. Kui autoriseerimisviga võimaldab rakendusel sandbox'i piire ületada või pääseda ligi kaitstud andmetele, muutub ligipääsukontrolli probleem väga oluliseks.

### Android

Androidi rakenduste puhul kasutatakse rakenduste UID-sid, permission'eid ja sandbox'i.

Androidi septembri 2026 bülletään sisaldab mitmeid EoP ehk **Elevation of Privilege** probleeme. Näiteks CVE-2026-28666 mõjutab Framework'i ja võib viia kaugõiguste suurendamiseni. ([Android Open Source Project][1])

### iOS

Apple'i iOS 26.7 **CVE-2026-84617** on väga hea näide autoriseerimisprobleemist. Apple kirjeldab seda XPC autoriseerimisprobleemina, mille tõttu võis rakendus saada ligipääsu tundlikele kasutajaandmetele. ([Apple Support][2])

### Kuidas riski vähendada?

Iga ressursi puhul tuleb kontrollida, **kes** seda küsib ja **mida** ta teha soovib. Ainult sisselogimisest ei piisa.

Server peab tegema autoriseerimiskontrolli ise. Kasutaja saadetud rolli, objekti ID-d või muid õigusi ei tohi pimesi usaldada.

Tuleb rakendada vähimate õiguste põhimõtet, kasutada gruppe ja rolle ning kontrollida administraatorifunktsioone eraldi.

Ligipääsukontrolli testimisel tuleb proovida mitte ainult lubatud tegevusi, vaid ka olukordi, kus tavaline kasutaja üritab kasutada teise kasutaja või administraatori ressursse.

---

# 5. Privilege Escalation ehk õiguste suurendamine

**Privilege escalation ehk õiguste suurendamine** tähendab olukorda, kus kasutaja, protsess või rakendus saavutab rohkem õigusi, kui talle algselt anti.

See on üks olulisemaid operatsioonisüsteemide turvateemasid, sest paljud ründed ei alga administraatoriõigustega. Ründaja võib alguses saada näiteks tavalise kasutaja õigused või kontrolli ühe piiratud rakenduse üle. Seejärel otsib ta võimalust liikuda kõrgema õigustasemeni. Kui tavaline kasutaja suudab saada administraatori- või süsteemiõigused, on süsteemi kaitsemudel oluliselt nõrgenenud. Windowsis võib kõrge õigustase tähendada näiteks SYSTEM-konteksti, Linuxis root-kasutajat ning mobiilsetes operatsioonisüsteemides süsteemikomponendi või privilegeeritud teenuse õigusi. Õiguste suurendamine võib olla **vertikaalne (vertical privilege escalation)**, kui liigutakse tavakasutajast administraatoriks. See võib olla ka **horisontaalne (horizontal privilege escalation)**, kui üks kasutaja saab teise sama taseme kasutaja õigused või andmed. Õiguste suurendamise rünnakud võivad tekkida kernelivigadest, draiveritest, teenustest, autentimisest, failisüsteemist või vigasest autoriseerimisest. Seetõttu ei ole privilege escalation üks konkreetne programmeerimisviga, vaid terve rühm turvaprobleeme.

### Kuidas privilege escalation tehniliselt tekib?

Operatsioonisüsteem peab pidevalt otsustama, millised toimingud on mingile protsessile lubatud.

**Protsess (process)** on töötav programm. Igal protsessil on oma identiteet ja õigused. Kui protsess töötab tavakasutaja õigustes, ei tohiks ta tavaliselt muuta kerneli, süsteemifailide või teiste kasutajate kaitstud andmeid.

Probleem tekib siis, kui madalama õigusega protsess suudab kasutada komponenti, mis töötab kõrgemate õigustega.

```text
Tavaline kasutaja
       │
       ▼
Kasutaja protsess
       │
       ▼
Haavatav teenus / draiver / kernel
       │
       ▼
Õiguste kontrollist möödumine
       │
       ▼
Kõrgema õigusega protsess
       │
       ▼
SYSTEM / root / privilegeeritud kontekst
```

Näiteks võib tavakasutaja käivitada programmi, mis suhtleb kerneliga. Kui kernelis on viga, võib spetsiaalselt koostatud sisend muuta kerneli sisemist olekut viisil, mida programm ei tohiks saada teha.

Teine võimalus on vigane teenus. Kui teenus töötab administraatoriõigustes, kuid võtab kasutajalt vastu kontrollimata sisendit, võib ründaja proovida teenuse kaudu panna süsteemi tegema toiminguid tema eest.

### Mida ründaja pärast õiguste suurendamist teha võib?

Pärast kõrgemate õiguste saamist võib ründaja pääseda ligi failidele, millele tal varem õigust ei olnud. Samuti võib ta muuta süsteemi konfiguratsiooni, luua uusi kasutajaid, muuta turvaseadeid või paigaldada pahatahtlikku tarkvara.

Windowsis võib SYSTEM-õigus anda väga laialdase kontrolli operatsioonisüsteemi üle. Linuxis annab root tavaliselt väga ulatusliku kontrolli failisüsteemi ja süsteemiteenuste üle.

Õiguste suurendamine on sageli ründeahela keskmine etapp:

```text
Esialgne ligipääs
       │
       ▼
Tavaline kasutajakonto
       │
       ▼
Privilege escalation
       │
       ▼
Administraatoriõigus
       │
       ├── andmete lugemine
       ├── süsteemi muutmine
       ├── püsivuse loomine
       └── teiste süsteemide ründamine
```

### Windows

**CVE-2021-1732 – Win32k**

Microsoft Windowsi Win32k komponendi haavatavus võimaldas lokaalset õiguste suurendamist. Ründajal oli vaja esmalt saada võimalus süsteemis koodi käivitada. Seejärel sai haavatavust kasutada kõrgemate õiguste saavutamiseks. CISA KEV kataloogis on selle haavatavuse puhul dokumenteeritud reaalne ärakasutamine.

**CVE-2020-1472 – Zerologon**

Netlogoni protokolli viga võimaldas ründajal domeenikeskkonnas tõsise õiguste suurendamise. Probleem oli eriti oluline domeenikontrollerite puhul. CISA on selle haavatavuse märkinud aktiivselt ärakasutatute hulka.

### Linux

**CVE-2022-0847 – Dirty Pipe**

Dirty Pipe on klassikaline Linuxi lokaalse õiguste suurendamise näide. Viga paiknes Linuxi kernelis ning võimaldas teatud tingimustel muuta andmeid viisil, mida tavapärased failisüsteemi õigused ei oleks lubanud.

CISA KEV kataloog sisaldab selle kohta reaalse ärakasutamise infot.

### Android

**CVE-2026-28664**

Android Runtime'i haavatavus võib Androidi septembri 2026 turvabülletääni järgi põhjustada lokaalset õiguste suurendamist. Google kirjeldab, et täiendavaid täitmisõigusi ei ole vaja ja kasutaja tegevus ei ole vajalik. Mõjutatud on Androidi AOSP versioonid 14, 15, 16, 16 QPR2 ja 17. ([Android Open Source Project][1])

**CVE-2026-28666**

Android Framework'i haavatavus võib põhjustada kaugõiguste suurendamist. Google klassifitseerib selle kriitiliseks ning märgib, et täiendavaid täitmisõigusi ega kasutaja tegevust ei ole vaja. ([Android Open Source Project][1])

### iOS

**CVE-2026-84617**

iOS 26.7 XPC autoriseerimisprobleem võis võimaldada rakendusel saada ligipääsu tundlikele kasutajaandmetele. Apple lahendas probleemi parema olekuhaldusega. ([Apple Support][2])

**CVE-2026-43715**

WebKiti use-after-free probleem võis põhjustada mälu rikkumist. Apple lahendas vea parema mäluhaldusega. Kuigi see CVE ei ole iseenesest klassikaline privilege-escalation-haavatavus, võib selline mälurikkumine olla üheks lüliks keerulisemas ründeahelas, kus esialgne koodi käivitamine kombineeritakse hiljem mõne teise õiguste suurendamise probleemiga. ([Apple Support][2])

### Kuidas riski vähendada?

Kõige olulisem on vältida olukorda, kus tavakasutaja töötab pidevalt administraatoriõigustes. Rakendused ja teenused peaksid töötama võimalikult väikeste õigustega.

Operatsioonisüsteemi ja kerneli turvapaigad tuleb kiiresti paigaldada. Eriti tähtsad on lokaalsed õiguste suurendamise vead, sest need võivad muuta väikese algse kompromiteerimise täielikuks süsteemi kompromiteerimiseks.

Samuti tuleb kaitsta administraatorikontosid, piirata sudo- ja GPO-õigusi, kontrollida teenuste kasutajakonteksti ning jälgida ootamatuid õiguste muutusi.

---

# Mõistete seos esimese viie peatüki põhjal

Nende viie haavatavuse puhul on oluline märgata, et need ei ole alati üksteisest eraldatud.

```text
Paikamata tarkvara
       │
       ▼
Teadaolev haavatavus
       │
       ▼
Ründaja saab süsteemi
       │
       ▼
Vigane ligipääsukontroll
       │
       ▼
Kõrgemate õiguste saavutamine
       │
       ▼
Süsteemi kompromiteerimine
```

Samuti võib ründeahel alata hoopis konfiguratsiooniveast:

```text
Ebaturvaline konfiguratsioon
          │
          ▼
Liiga avatud teenus
          │
          ▼
Nõrk autentimine
          │
          ▼
Kasutajakonto kompromiteerimine
          │
          ▼
Privilege escalation
          │
          ▼
Administraatoriõigused
```

Seetõttu ei ole küberturvalisuses piisav küsida ainult:

> "Kas meil on haavatavusi?"

Tuleb küsida ka:

* millised teenused on väljast nähtavad;
* millised kontod võivad süsteemi kasutada;
* millised õigused kasutajatel ja teenustel on;
* milline tarkvara on paigatud;
* milliseid autentimisviise kasutatakse;
* millised süsteemid on omavahel ühendatud;
* mida saab ründaja teha juhul, kui üks kaitsekiht ebaõnnestub.

OWASP Top 10:2025 käsitleb veebirakenduste puhul samasugust põhimõtet: turvariskid võivad tekkida ligipääsukontrollist, konfiguratsioonist, tarkvara tarneahelast, krüptograafiast ja süstimisvigadest. ([OWASP Top 10][3])

## Oluline põhimõte

**Haavatavus ei võrdu automaatselt kompromiteerimisega.**

Näiteks:

```text
Haavatavus
    │
    ├── paik paigaldatud → risk väheneb
    │
    ├── süsteem isoleeritud → ärakasutamise võimalus väheneb
    │
    ├── kaitsemehhanism töötab → mõju võib väheneda
    │
    └── ärakasutamine õnnestub
              │
              ▼
        kompromiteerimine
```

Seetõttu tuleb haavatavuste käsitlemisel vaadelda vähemalt nelja eri asja:

1. **Kas haavatavus eksisteerib?**
2. **Kas selle jaoks on olemas ärakasutus?**
3. **Kas seda ärakasutust kasutatakse reaalselt?**
4. **Milline on selle konkreetse süsteemi tegelik mõju?**

See eristus on oluline kogu järgneva õppematerjali mõistmiseks.



[1]: https://source.android.com/docs/security/bulletin/2026/2026-09-01?utm_source=chatgpt.com "Android Security Bulletin—September 2026  |  Android Open Source Project"
[2]: https://support.apple.com/en-us/149041?utm_source=chatgpt.com "About the security content of iOS 26.7 and iPadOS 26.7 - Apple Support"
[3]: https://top10.owasp.org/2025/0x00_2025-Introduction/?utm_source=chatgpt.com "Introduction - OWASP Top 10:2025"
