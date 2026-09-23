# ÕPIÜLESANNE

## 2026. aasta operatsioonisüsteemide olulised turvahaavatavused

# Iseseisev uurimistöö

## 2026. aasta operatsioonisüsteemide turvahaavatavused

### Õppeaine

Küberturvalisus

### Sihtrühm

IT-õpilased

### Töö liik

Iseseisev uurimistöö

### Teema

Operatsioonisüsteemide haavatavused, CVE, CVSS, ekspluateerimine, mõju ja kaitsemeetmed

---

# 1. Töö eesmärk

Töö eesmärk on uurida 2026. aastal avalikustatud olulisi turvahaavatavusi erinevates operatsioonisüsteemides ning mõista, kuidas tehniline programmeerimis- või disainiviga võib muutuda reaalseks küberturbeohuks.

Töö käigus tuleb analüüsida:

* milles haavatavus seisneb;
* millist komponenti see mõjutab;
* millised operatsioonisüsteemi versioonid on mõjutatud;
* milline on ründevektor;
* milliseid õigusi ründaja vajab;
* kas kasutaja tegevus on vajalik;
* milline võiks olla ründeahel;
* milline on mõju konfidentsiaalsusele, terviklusele ja käideldavusele;
* milline on CVSS hinnang;
* kas haavatavust on reaalselt ära kasutatud;
* kas tegemist on zero-day probleemiga;
* milline parandus on avaldatud;
* kuidas saab süsteemi administraator riski vähendada.

---

# 2. Uuritavad operatsioonisüsteemid

Töö kasutab viit operatsioonisüsteemide rühma.

| Operatsioonisüsteem     | Haavatavuste arv |
| ----------------------- | ---------------: |
| Windows 10 / Windows 11 |               10 |
| Linux                   |                7 |
| macOS                   |                5 |
| Android                 |                5 |
| iOS                     |                5 |
| **Kokku**               |           **32** |

Õpetaja võib jagada haavatavused õpilaste vahel nii, et iga õpilane uurib näiteks 2–4 CVE-d.

---

# 3. WINDOWS 10 / WINDOWS 11

## Uuritavad haavatavused

### 1. CVE-2026-41096

**Windows DNS – heap-based buffer overflow**

* Windows 11
* heap-based buffer overflow
* Remote Code Execution
* CVSS 3.1: 9.8 Critical
* võrgust rünnatav
* kasutaja tegevust ei ole vaja

NVD andmetel võimaldab viga Windows DNS-is volitamata ründajal võrgu kaudu koodi käivitada. Mõjutatud on muu hulgas Windows 11 23H2, 24H2, 25H2 ja 26H1. ([NVD][1])

### 2. CVE-2026-50478

**Windows Kernel – use-after-free**

* Windows 10
* Windows 11
* Windows Kernel
* privilege escalation
* CVSS 3.1: 7.8 High
* lokaalne rünnak

Viga võimaldab autentitud lokaalsel kasutajal privileege eskaleerida. Probleemiks on use-after-free. ([NVD][2])

### 3. CVE-2026-62707

**Windows Modern Device Management**

* Windows 10
* Windows 11
* MDM
* use-after-free
* privilege escalation
* CVSS 3.1: 7.8 High

Haavatavus asub Windowsi Modern Device Management komponendis. ([NVD][3])

### 4. CVE-2026-50302

**Windows Cryptographic Services**

* Windows 10
* Windows 11
* certificate validation
* security feature bypass
* CVSS 3.1: 6.5 NVD järgi

Probleem seisneb sertifikaadi ebakorrektses valideerimises ning võimaldab turvafunktsioonist mööda minna võrgu kaudu. ([NVD][4])

### 5. CVE-2026-50681

**Windows Cryptographic Services**

* Windows 10
* Windows 11
* information disclosure
* CVSS 3.1: 5.5

Lokaalne autentitud kasutaja võib saada ligipääsu tundlikule informatsioonile. ([NVD][5])

### 6. CVE-2026-57084

**Windows File Explorer**

* Windows 10
* Windows 11
* uninitialized resource
* information disclosure
* CVSS 3.1: 5.5

Probleem võimaldab lokaalselt tundliku informatsiooni avalikustamist. ([NVD][6])

### 7. CVE-2026-50454

**Windows User Interface Core**

* Windows 11
* relative path traversal
* privilege escalation
* CVSS 3.1: 7.8 High

([NVD][7])

### 8. CVE-2026-50457

**Windows Runtime**

* Windows 10
* Windows 11
* use-after-free
* race condition
* privilege escalation
* CVSS 3.1: 7.8 High

([NVD][8])

### 9. CVE-2026-20922

**Windows NTFS**

* Windows 10
* Windows 11
* heap-based buffer overflow
* local code execution
* CVSS 3.1: 7.8 High

([NVD][9])

### 10. CVE-2026-58532

**Windows Kernel**

* Windows 10/11
* integer overflow
* privilege escalation
* CVSS 3.1: 7.8 High

([NVD][10])

---

# 4. LINUX

Uuritavad Linuxi haavatavused:

| CVE                | Nimetus / komponent | Probleem                    |
| ------------------ | ------------------- | --------------------------- |
| **CVE-2026-31431** | Copy Fail           | Kernel privilege escalation |
| **CVE-2026-43284** | Dirty Frag          | Kernel privilege escalation |
| **CVE-2026-46331** | Traffic Control     | Privilege escalation        |
| **CVE-2026-53362** | ipv6_frag_escape    | Container escape            |
| **CVE-2026-43499** | GhostLock           | Locking subsystem           |
| **CVE-2026-46243** | CIFSwitch           | CIFS privilege escalation   |
| **CVE-2026-74469** | DiagSpill           | SCTP integer overflow       |

Red Hat on avaldanud nende kohta eraldi 2026. aasta turvabülletäänid. ([Red Hat Customer Portal][11])

### CVE-2026-31431 – Copy Fail

Kohalik kasutaja võib Linuxi kerneli krüptograafilise liidese vea abil saavutada root-õigused. Red Hat liigitas probleemi „Important“ tasemele ning avaldas parandused. ([Red Hat Customer Portal][12])

### CVE-2026-43284 – Dirty Frag

Viga paikneb Linuxi kerneli XFRM/IPsec ja RxRPC alamkomponentides ning võib võimaldada madalaõigustega lokaalsel kasutajal saada root-õigused. ([Red Hat Customer Portal][13])

### CVE-2026-46331 – Traffic Control

Linuxi kerneli Traffic Control komponendi probleem võimaldab privilege escalation'i. ([Red Hat Customer Portal][11])

### CVE-2026-53362 – ipv6_frag_escape

IPv6 fragmentatsiooni probleem on seotud container escape'i riskiga. Red Hat avaldas selle kohta eraldi turvabülletääni. ([Red Hat Customer Portal][11])

### CVE-2026-43499 – GhostLock

Locking subsystem'i probleem, mida Red Hat kirjeldab privilege escalation'ina. ([Red Hat Customer Portal][11])

### CVE-2026-46243 – CIFSwitch

CIFS-i alamkomponendi viga võib lubada madalate õigustega lokaalsel kasutajal käivitada käske root-õigustes. ([Red Hat Customer Portal][14])

### CVE-2026-74469 – DiagSpill

SCTP diagnostikaliideses tekkiv integer overflow võib põhjustada ligikaudu 8 MB suuruse kirjutamise väljapoole eraldatud puhvrit. Erinevalt teistest samas Red Hati bülletäänis kirjeldatud vigadest ei nõua see unprivileged user namespace'i. ([Red Hat Customer Portal][15])

---

# 5. macOS

Õpilaste uurimiseks:

| CVE                | Komponent       | Probleem                     |
| ------------------ | --------------- | ---------------------------- |
| **CVE-2026-84568** | autofs          | Path traversal → root        |
| **CVE-2026-84570** | autofs          | Gatekeeper bypass            |
| **CVE-2026-84607** | AVEVideoEncoder | Sandbox → kernel privileges  |
| **CVE-2026-65369** | Kernel          | Gatekeeper bypass            |
| **CVE-2026-84506** | udf             | Use-after-free → kernel code |

Apple'i 14. septembril 2026 avaldatud macOS Sequoia 15.8 turvadokumentatsioon kirjeldab neid ja paljusid teisi 2026. aasta parandusi. ([Apple Support][16])

Näiteks:

**CVE-2026-84568** – pahatahtliku võrgudirektoriserveri kontroll võib viia arbitrary code execution'ini root-õigustes. Apple kirjeldab põhjuseks path traversal'i. ([Apple Support][16])

**CVE-2026-65369** – pahatahtlik rakendus võib mööduda Gatekeeperi kontrollidest. ([Apple Support][16])

**CVE-2026-84607** – sandboxed rakendus võib saada võimaluse käivitada suvalist koodi kernel-privileegidega. Probleemiks oli race condition. ([Apple Support][16])

---

# 6. ANDROID

Androidi 2026. aasta septembri turvabülletään on eriti sobiv allikas, sest Google nimetab seal otse kriitilised probleemid ja nende tüübid. 2026-09-05 või uuem turvapaigatase lahendab selle bülletääni vastavad probleemid. ([Android Open Source Project][17])

## Uuritavad 5 CVE-d

| CVE                | Komponent             | Tüüp | Google'i hinnang |
| ------------------ | --------------------- | ---- | ---------------- |
| **CVE-2026-28604** | System / adbd         | RCE  | Critical         |
| **CVE-2026-28618** | System / Media Codecs | RCE  | Critical         |
| **CVE-2026-28639** | System                | RCE  | Critical         |
| **CVE-2026-28662** | System / Wi-Fi        | RCE  | Critical         |
| **CVE-2026-28666** | Framework             | EoP  | Critical         |

Google märgib, et System-komponendi kõige tõsisemad vead võivad viia remote code execution'ini ilma täiendavate privileegideta ning kasutaja tegevust ei ole vaja. ([Android Open Source Project][17])

Õpilane peab iga CVE puhul välja selgitama, milline konkreetne alamkomponent on seotud, sest Androidi tootjad võivad kasutada erinevaid seadme- ja tarkvarakomponente.

---

# 7. iOS

Apple'i 14. septembri 2026 iOS 26.7 turvadokumentatsioon sisaldab väga suurt hulka parandatud haavatavusi. ([Apple Support][18])

Õpilaste uurimiseks:

| CVE                | Komponent            | Probleem                    |
| ------------------ | -------------------- | --------------------------- |
| **CVE-2026-86882** | Accelerate Framework | Out-of-bounds write         |
| **CVE-2026-84523** | APFS                 | Kernel memory write         |
| **CVE-2026-84607** | AVEVideoEncoder      | Sandbox → kernel privileges |
| **CVE-2026-65414** | Bluetooth            | Out-of-bounds write         |
| **CVE-2026-65399** | copyfile             | Gatekeeper bypass           |

Apple'i järgi:

* **CVE-2026-86882** – spetsiaalselt loodud pildi töötlemine võib põhjustada protsessi lõpetamise; probleem oli out-of-bounds write. ([Apple Support][18])
* **CVE-2026-84523** – rakendus võib põhjustada süsteemi lõpetamise või kirjutada kerneli mällu. ([Apple Support][18])
* **CVE-2026-84607** – sandboxed rakendus võib jõuda kernel-privileegidega koodi käivitamiseni; põhjuseks oli race condition. ([Apple Support][18])
* **CVE-2026-65414** – Bluetoothi probleem võib võimaldada remote attacker'il põhjustada rakenduse lõpetamise või arbitrary code execution'i. ([Apple Support][18])
* **CVE-2026-65399** – pahatahtlik arhiiv võis Gatekeeperist mööda minna. ([Apple Support][18])

---

# 8. ÜLESANNE ÕPILASELE

Iga õpilane saab õpetajalt ühe või mitu CVE-d.

Iga CVE kohta tuleb koostada järgmine analüüs.

## 8.1. Põhiandmed

Kirjelda:

1. CVE number.
2. Avalikustamise kuupäev.
3. Operatsioonisüsteem.
4. Mõjutatud versioonid.
5. Haavatav komponent.
6. Haavatavuse tüüp.
7. CVSS skoor.
8. CVSS vektor.
9. Tootja hinnang.
10. NVD hinnang, kui see erineb tootja hinnangust.

---

# 9. Tehniline analüüs

Selgita oma sõnadega:

**Mis täpselt on katki?**

Näiteks ei piisa vastusest:

> „Tegemist on buffer overflow'ga.“

Tuleb selgitada:

> Millises komponendis buffer overflow tekib, milliseid andmeid ründaja kontrollib, milline kontroll on puudu ning mida võib ründaja saavutada.

---

# 10. Ründevektor

Koosta skeem:

```text
Ründaja
   ↓
Ründevektor
   ↓
Haavatav komponent
   ↓
Haavatavuse käivitamine
   ↓
Ekspluateerimine
   ↓
Privileegide saamine
   ↓
Süsteemi kompromiteerimine
```

---

# 11. CIA-analüüs

Analüüsi:

### Confidentiality

Kas ründaja võib saada ligipääsu andmetele?

### Integrity

Kas ründaja võib muuta süsteemi või andmeid?

### Availability

Kas ründaja võib süsteemi või teenuse muuta kättesaamatuks?

---

# 12. Ründaja vajalikud tingimused

Koosta tabel:

| Küsimus                                | Vastus |
| -------------------------------------- | ------ |
| Kas rünnak toimub üle võrgu?           |        |
| Kas vajalik on lokaalne ligipääs?      |        |
| Kas kasutajakonto on vajalik?          |        |
| Kas administraatoriõigus on vajalik?   |        |
| Kas kasutaja peab midagi tegema?       |        |
| Kas füüsiline ligipääs on vajalik?     |        |
| Kas exploit võib töötada automaatselt? |        |

---

# 13. Reaalne mõju

Uuri:

* kas CVE-d on reaalselt ära kasutatud;
* kas tegemist oli zero-day'ga;
* kas CISA KEV sisaldab seda;
* kas on teada avalik PoC;
* kas exploit on avalikult kättesaadav;
* kas seda on seostatud konkreetse ründekampaaniaga;
* millist tüüpi ründajad seda kasutasid.

Kui tõendeid ei leita, tuleb kirjutada:

> „Ma ei leidnud usaldusväärsetest avalikest allikatest tõendeid selle kohta, et CVE-d oleks reaalses rünnakus kasutatud.“

---

# 14. Parandus

Uuri:

* milline patch avaldati;
* millal patch avaldati;
* milline versioon probleemi parandab;
* kas süsteemi tuleb taaskäivitada;
* kas tootja pakkus workaround'i;
* kas workaround vähendab funktsionaalsust.

---

# 15. Kaitsemeetmed

Koosta vähemalt viis kaitsemeedet.

Näiteks:

1. Patch management.
2. Least privilege.
3. Network segmentation.
4. EDR.
5. Application control.
6. Logging.
7. IDS/IPS.
8. Kernel hardening.
9. Kasutajaõiguste piiramine.
10. Vanade süsteemiversioonide eemaldamine.

Kõik kaitsemeetmed peavad olema seotud konkreetse CVE-ga.

---

# 16. Organisatsiooni mõju

Oletame, et organisatsioonis on:

* 500 Windowsi arvutit;
* 50 Linuxi serverit;
* 100 Androidi telefoni;
* 50 iPhone'i;
* 20 macOS-i arvutit.

Analüüsi:

1. Kui palju seadmeid võiks potentsiaalselt olla mõjutatud?
2. Milliseid andmeid võiks ründaja saada?
3. Kas kompromiteeritud arvutist võiks rünnata teisi süsteeme?
4. Kas võimalik oleks ransomware-rünnak?
5. Kas võimalik oleks privileegide eskalatsioon?
6. Milliseid teenuseid võiks mõjutada?
7. Kui kiiresti peaks administraator reageerima?

---

# 17. Allikad

Kasutada tuleb vähemalt:

**3 tootja/ametlikku allikat**

ja

**2 sõltumatut allikat.**

Soovitatavad allikad:

* Microsoft Security Response Center
* NVD
* CVE.org
* CISA
* Red Hat Security
* Linux Kernel
* Apple Security
* Android Security Bulletins

---

# 18. Töö vormistus

Soovituslik pikkus:

**8–15 lehekülge.**

Töö struktuur:

1. Tiitelleht
2. Sissejuhatus
3. CVE üldandmed
4. Tehniline kirjeldus
5. Ründeahel
6. CVSS
7. CIA-analüüs
8. Reaalne mõju
9. Parandus
10. Kaitsemeetmed
11. Organisatsiooni mõju
12. Kokkuvõte
13. Allikad

---

# 19. Hindamine

| Hindamise osa                                   | Punktid |
| ----------------------------------------------- | ------: |
| CVE ja mõjutatud süsteemi korrektne tuvastamine |      10 |
| Tehnilise probleemi selgitamine                 |      20 |
| Ründeahela analüüs                              |      15 |
| CIA mõjuanalüüs                                 |      15 |
| Reaalse ekspluateerimise uurimine               |      10 |
| CVSS analüüs                                    |      10 |
| Kaitsemeetmed                                   |      10 |
| Allikad ja viitamine                            |       5 |
| Vormistus                                       |       5 |
| **Kokku**                                       | **100** |

---

# 20. Lõpuküsimus

Töö lõpus vasta küsimusele:

> **Kas CVSS skoorist piisab haavatavuse tegeliku riski hindamiseks?**

Põhjenda vastust vähemalt kolme erineva operatsioonisüsteemi näitel.

Selgita ka mõistete erinevust:

**CVE → CVSS → PoC → exploit → zero-day → KEV → tegelik risk**

---

# 21. NÄIDISLAHENDUSED ÕPILASELE

Need näited on meelega valitud **väljaspool õpilaste põhinimekirja**, et õpilane ei saaks lihtsalt näidislahendust kopeerida.

---

## A. Windows – näidislahendus

### CVE-2026-20922 – Windows NTFS

**Haavatavus:** heap-based buffer overflow.

NVD kirjeldab, et Windows NTFS-is olev heap-based buffer overflow võimaldab autentitud lokaalsel ründajal koodi käivitada. CVSS 3.1 skoor on 7.8 High. Mõjutatud on muu hulgas Windows 10 21H2/22H2 ning Windows 11 23H2, 24H2 ja 25H2. ([NVD][9])

### Tehniline probleem

Heap on dünaamilise mälu piirkond, mida programmid kasutavad töö käigus loodavate objektide hoidmiseks.

Buffer overflow tekib olukorras, kus programmi kirjutatav andmehulk ületab talle eraldatud puhvri piiri.

Lihtsustatult:

```text
eraldatud puhver

[ A ][ A ][ A ][ A ]

ründaja saadab:

[ A ][ A ][ A ][ A ][ X ][ X ][ X ]
                       ↑
                 väljaspool puhvrit
```

Kui ründaja suudab mõjutada väljaspool puhvrit paiknevat mälu, võib memory corruption teatud tingimustel muutuda koodi käivitamiseks.

### Ründeahel

```text
Lokaalne kasutajakonto
        ↓
NTFS-i haavatav funktsioon
        ↓
spetsiaalselt koostatud sisend
        ↓
heap buffer overflow
        ↓
memory corruption
        ↓
koodi käivitamine
```

### Mõju

| Mõju              | Hinnang |
| ----------------- | ------- |
| Konfidentsiaalsus | Kõrge   |
| Terviklus         | Kõrge   |
| Käideldavus       | Kõrge   |

NVD-s avaldatud Microsofti CVSS-vektor on:

`AV:L/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:H`

([NVD][9])

### Kaitse

Kõige olulisem kaitse on Windowsi turvapaiga paigaldamine. Lisaks:

* kasutajaõiguste piiramine;
* privilegeeritud kontode vähendamine;
* EDR;
* süsteemi regulaarne patch management;
* NTFS-i kasutava süsteemi monitooring.

### Õppetund

Selle näite oluline õppetund on:

> **Mälukasutuse viga võib muutuda otseseks koodi käivitamise probleemiks.**

---

# 22. Linux – näidislahendus

## CVE-2026-23243 – Linux Kernel RDMA

See CVE **ei ole õpilaste põhinimekirjas**.

Red Hati kirjelduse järgi paikneb probleem Linuxi kerneli RDMA `umad` komponendis. Ründaja võib manipuleerida sisendiga nii, et tekib integer underflow ning sellele järgnev out-of-bounds memory write. Selle tulemuseks võib olla kernel crash, infoleke või andmete tervikluse probleem. ([Red Hat Customer Portal][19])

### Ründeahel

```text
Lokaalne kasutaja
      ↓
RDMA umad interface
      ↓
manipuleeritud sisend
      ↓
integer underflow
      ↓
vale buffer size
      ↓
out-of-bounds write
      ↓
kernel memory corruption
```

### Tehniline probleem

Oluline õppetund on siin see, et:

**integer overflow/underflow võib olla memory corruption'i algpõhjus.**

Kui suuruse arvutamisel tekib vale tulemus:

```text
tegelik suurus = 4096
arvutatud suurus = 0
```

võib programm eraldada liiga väikese puhvri, kuid hiljem sinna rohkem andmeid kirjutada.

Red Hat soovitab ründe vähendamiseks takistada `ib_umad` mooduli laadimist juhul, kui seda funktsionaalsust ei vajata. ([Red Hat Customer Portal][19])

### Õppetund

> **Mälukaitse probleem ei pea algama otsesest buffer overflow'st – selle võib põhjustada vigane suuruse arvutamine.**

---

# 23. macOS – näidislahendus

## CVE-2026-84568 – macOS autofs

See CVE ei ole õpilaste viie põhihaavatavuse hulgas.

Apple kirjeldab probleemi nii, et ründaja, kes kontrollib võrgudirektoriserverit, võib saavutada arbitrary code execution'i root-privileegidega. Probleem parandati path validation'i parandamisega. ([Apple Support][16])

### Probleemi olemus

Siin on keskne mõiste:

**Path Traversal**

Näiteks:

```text
/var/data/file.txt
```

võib ründaja proovida manipuleerida kujule:

```text
/var/data/../../etc/passwd
```

Kui rakendus ei kontrolli rada korrektselt, võib see viia väljapoole lubatud kataloogi.

### Ründeahel

```text
Ründaja kontrollib võrgudirektorit
             ↓
autofs töötleb rada
             ↓
path traversal
             ↓
lubatud asukohast väljumine
             ↓
pahatahtliku faili/koodi kasutamine
             ↓
root
```

Apple'i paranduse kirjeldus ütleb otseselt, et probleem lahendati parema path validation'iga. ([Apple Support][16])

### Õppetund

> **Path traversal ei ole ainult veebirakenduste probleem. Sama kontseptsioon võib esineda operatsioonisüsteemi enda komponentides.**

---

# 24. Android – näidislahendus

## CVE-2026-55273 – Android Framework

See CVE ei ole õpilaste põhinimekirjas.

Google'i 2026. aasta septembri Android Security Bulletin loetleb **CVE-2026-55273** Android Frameworki EoP-haavatavusena ja määrab selle kriitiliseks. Mõjutatud AOSP versioonid on 16, 16 QPR2 ja 17. ([Android Open Source Project][17])

### Probleemi olemus

EoP tähendab:

**Elevation of Privilege**

Ründaja alustab madalamate õigustega ning kasutab haavatavust selleks, et saada kõrgema taseme õigused.

Lihtsustatud mudel:

```text
Tavaline rakendus
      ↓
Frameworki haavatavus
      ↓
privileegide eskalatsioon
      ↓
kõrgemate õigustega komponent
      ↓
ligipääs andmetele / funktsioonidele
```

Google klassifitseeris selle kriitiliseks Frameworki EoP-probleemiks. ([Android Open Source Project][17])

### Õppetund

> **RCE ei ole ainus tõsine ründetehnika. Privilege escalation võib olla ründeahela järgmine oluline samm pärast esialgset kompromiteerimist.**

---

# 25. iOS – näidislahendus

## CVE-2026-65395 – iOS ImageIO

See CVE ei ole õpilaste põhinimekirjas.

Apple kirjeldab iOS 26.7 turvadokumentatsioonis ImageIO probleemi, mille korral pahatahtlikult koostatud pildi töötlemine võib põhjustada memory corruption'i. Probleem parandati out-of-bounds write'i parandamisega. ([Apple Support][18])

### Probleemi olemus

ImageIO töötleb pildifaile.

Kui pahatahtlik pildifail sisaldab spetsiaalselt koostatud andmeid ja parser ei kontrolli sisendi piire õigesti:

```text
Pildifail
   ↓
ImageIO parser
   ↓
vigane suuruse/piiri kontroll
   ↓
out-of-bounds write
   ↓
memory corruption
```

Apple'i dokumentatsioon ütleb, et CVE-2026-65395 puhul oli probleem seotud out-of-bounds write'iga ning tulemuseks võis olla memory corruption. ([Apple Support][18])

### Miks see on oluline?

Pildifail tundub kasutajale lihtsalt:

```text
foto.jpg
```

aga tegelikult on selle töötlemiseks vaja keerukat parserit.

Seetõttu võivad ründepinnaks olla näiteks:

* veebilehed;
* sõnumirakendused;
* e-post;
* fotod;
* failimanused;
* eelvaated.

### Õppetund

> **Kasutaja ei pea alati käivitama programmi. Mõnikord võib haavatavuse käivitamiseks piisata spetsiaalselt koostatud faili töötlemisest.**

---

# 26. Õpilase töö soovituslik kokkuvõttevorm

Iga õpilane võiks oma CVE kohta lõpetada ühe sellise tabeliga:

| Näitaja                  | Tulemus         |
| ------------------------ | --------------- |
| CVE                      | CVE-XXXXXXXX    |
| OS                       |                 |
| Komponent                |                 |
| Haavatavuse tüüp         |                 |
| CVSS                     |                 |
| Ründevektor              |                 |
| Vajalik konto            |                 |
| Vajalik kasutaja tegevus |                 |
| RCE                      | Jah/Ei          |
| Privilege escalation     | Jah/Ei          |
| Information disclosure   | Jah/Ei          |
| DoS                      | Jah/Ei          |
| Reaalne exploit          | Jah/Ei/Teadmata |
| PoC                      | Jah/Ei          |
| Patch                    |                 |
| Peamine kaitsemeede      |                 |

---

## 27. Kõige olulisem osa õpilase töös

Õpilane ei tohi piirduda sellise vastusega:

> „CVE-XXXX on kõrge riskiga haavatavus, millele on olemas patch.“

Töö peab näitama **tehnilist arusaamist**:

```text
MIS ON KATKI?
      ↓
MIKS SEE KATKI ON?
      ↓
KUIDAS RÜNDAJA SEDA KASUTAB?
      ↓
MIDA RÜNDAJA SAAVUTAB?
      ↓
MILLISEID ANDMEID VÕIB MÕJUTADA?
      ↓
KUIDAS TOOTJA PROBLEEMI PARANDAS?
      ↓
KUIDAS ADMINISTRAATOR SEDA ENNETAB?
```

See on minu hinnangul IT-õpilase jaoks oluliselt väärtuslikum kui lihtsalt CVE numbri ja CVSS skoori leidmine.

### Peamised ametlikud allikad

* [Microsoft Security Response Center](https://msrc.microsoft.com/update-guide/?utm_source=chatgpt.com)
* [NIST National Vulnerability Database](https://nvd.nist.gov/?utm_source=chatgpt.com)
* [CVE.org](https://www.cve.org/?utm_source=chatgpt.com)
* [Red Hat Security Bulletins](https://access.redhat.com/security/vulnerabilities?utm_source=chatgpt.com)
* [Apple Security Updates](https://support.apple.com/en-us/100100?utm_source=chatgpt.com)
* [Android Security Bulletins](https://source.android.com/docs/security/bulletin?utm_source=chatgpt.com)

Androidi septembri 2026 bülletään kinnitab, et **2026-09-05 või uuem security patch level** lahendab selles bülletäänis loetletud probleemid. ([Android Open Source Project][17]) Apple'i iOS 26.7 ja macOS Sequoia 15.8 turvadokumendid on vastavalt **14. septembrist 2026** ning sisaldavad väga ulatuslikku 2026. aasta paranduste loendit. ([Apple Support][18])

[1]: https://nvd.nist.gov/vuln/detail/CVE-2026-41096?utm_source=chatgpt.com "NVD-CVE-2026-41096"
[2]: https://nvd.nist.gov/vuln/detail/CVE-2026-50478?utm_source=chatgpt.com "NVD - CVE-2026-50478"
[3]: https://nvd.nist.gov/vuln/detail/CVE-2026-62707?utm_source=chatgpt.com "NVD - CVE-2026-62707"
[4]: https://nvd.nist.gov/vuln/detail/CVE-2026-50302?utm_source=chatgpt.com "NVD - CVE-2026-50302"
[5]: https://nvd.nist.gov/vuln/detail/CVE-2026-50681?utm_source=chatgpt.com "NVD - CVE-2026-50681"
[6]: https://nvd.nist.gov/vuln/detail/CVE-2026-57084?utm_source=chatgpt.com "NVD - CVE-2026-57084"
[7]: https://nvd.nist.gov/vuln/detail/CVE-2026-50454?utm_source=chatgpt.com "NVD - CVE-2026-50454"
[8]: https://nvd.nist.gov/vuln/detail/CVE-2026-50457?utm_source=chatgpt.com "NVD - CVE-2026-50457"
[9]: https://nvd.nist.gov/vuln/detail/cve-2026-20922?utm_source=chatgpt.com "NVD-CVE-2026-20922"
[10]: https://nvd.nist.gov/vuln/detail/cve-2026-58532?utm_source=chatgpt.com "NVD-CVE-2026-58532"
[11]: https://access.redhat.com/security/vulnerabilities?utm_source=chatgpt.com "Security Bulletins | Red Hat Customer Portal"
[12]: https://access.redhat.com/security/vulnerabilities/RHSB-2026-002?utm_source=chatgpt.com "RHSB-2026-002 Cryptographic Subsystem Privilege Escalation - Linux Kernel (CVE-2026-31431) - Copy Fail | Red Hat Customer Portal"
[13]: https://access.redhat.com/security/vulnerabilities/RHSB-2026-003?utm_source=chatgpt.com "RHSB-2026-003 Networking subsystem Privilege Escalation - Linux Kernel (CVE-2026-43284, CVE-2026-43500, CVE-2026-46300) - \"Dirty Frag\" | Red Hat Customer Portal"
[14]: https://access.redhat.com/security/vulnerabilities/RHSB-2026-005?utm_source=chatgpt.com "RHSB-2026-005 CIFS Upcall Privilege Escalation - Linux Kernel (CVE-2026-46243) - \"CIFSwitch\" | Red Hat Customer Portal"
[15]: https://access.redhat.com/security/vulnerabilities/RHSB-2026-011?utm_source=chatgpt.com "RHSB-2026-011 Network Stack Privilege Escalations - Linux Kernel (CVE-2026-80844, CVE-2026-81000, CVE-2026-68121, CVE-2026-74469) | Red Hat Customer Portal"
[16]: https://support.apple.com/en-us/149043?utm_source=chatgpt.com "About the security content of macOS Sequoia 15.8 - Apple Support"
[17]: https://source.android.com/docs/security/bulletin/2026/2026-09-01?utm_source=chatgpt.com "Android Security Bulletin—September 2026  |  Android Open Source Project"
[18]: https://support.apple.com/en-us/149041 "support.apple.com"
[19]: https://access.redhat.com/security/cve/cve-2026-23243?utm_source=chatgpt.com "CVE-2026-23243 - Red Hat Customer Portal"
