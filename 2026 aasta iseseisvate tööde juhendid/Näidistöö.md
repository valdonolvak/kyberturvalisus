# Näidistöö

# Küberturvalisuse uurimistöö – näidislahendus

## CVE-2026-21385 – Qualcommi komponentide mälukorruptsiooni haavatavus. Näidise CVE-2026-21385 Androidi 2026. aasta märtsi turvabülletään viitab selle piiratud ja sihitud ärakasutamisele, CISA lisas haavatavuse KEV-kataloogi 3. märtsil 2026 ning CVSS 3.1 baasväärtus on 7,8. ([Android Open Source Project][1])


> **Näidistöö eesmärk:** näidata, kuidas tööjuhendi küsimustele vastata ja kuidas ühe CVE uurimisest kujundada terviklik uurimistöö.

---

# 1. Mida sellest näitest õppima peaks?

Selle näite puhul ei ole kõige tähtsam CVE-2026-21385 ise.

Kõige tähtsam on õppida **uurimise meetodit**.

Hea uurimistöö liigub järgmise loogika järgi:

```text
CVE
 ↓
Mis on valesti?
 ↓
Milline tehniline viga tekib?
 ↓
Millistel tingimustel saab seda kasutada?
 ↓
Mida ründaja saavutada võib?
 ↓
Kas seda on päriselt kasutatud?
 ↓
Milline on mõju?
 ↓
Kuidas seda parandada?
 ↓
Kuidas riski vähendada?
```

Selles näites kasutatakse ühe haavatavuse puhul nii ametlikke allikaid kui ka täiendavaid turvaallikaid.

Näiteks kinnitab Androidi 2026. aasta märtsi turvabülletään, et CVE-2026-21385 kohta on viiteid piiratud ja sihitud ärakasutamisele. CISA lisas selle 3. märtsil 2026 oma KEV-kataloogi. ([Android Open Source Project][1])

Oluline õppetund on see, et töö ei piirdu CVE andmebaasist ühe rea kopeerimisega.

---

# 2. Sissejuhatus

Käesolevas uurimistöös analüüsitakse haavatavust **CVE-2026-21385**, mis on seotud Qualcommi komponentidega ja Androidi seadmetes kasutatava tehnoloogiaga.

Uurimise eesmärk on selgitada, milles haavatavus seisneb, milline tehniline probleem selle põhjustab, millistel tingimustel võib seda olla võimalik ära kasutada ning milline võib olla selle mõju süsteemile ja kasutaja andmetele.

Lisaks analüüsitakse CVSS-hinnangut, CIA-triaadi mõju, reaalse ärakasutamise kohta avaldatud infot, CISA KEV staatust, parandamist ning organisatsiooni jaoks sobivaid leevendusmeetmeid.

---

# 3. CVE üldandmed

## CVE number

**CVE-2026-21385**

CVE tähistab süsteemi **Common Vulnerabilities and Exposures**.

CVE number on konkreetse avalikult dokumenteeritud haavatavuse identifikaator.

## Haavatavuse kirjeldus

CVE kirjeldusena on toodud:

> Memory corruption while using alignments for memory allocation.

Sisuliselt on probleem seotud mälu eraldamisel kasutatava arvutusega, kus võib tekkida integer overflow või wraparound.

CVE on seotud Qualcommiga ning haavatavus avaldati 2. märtsil 2026. ([nki.gov.hu][2])

## CWE

Haavatavus on seotud:

**CWE-190 – Integer Overflow or Wraparound**

## CVSS

CVSS 3.1:

**7.8 – High**

Vektor:

```text
CVSS:3.1/AV:L/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:H
```

Need andmed on kooskõlas avaldatud CVE andmetega. ([opencve.jolan-nrt-services.gleeze.com][3])

---

# 4. Haavatavuse tehniline kirjeldus

Haavatavuse keskmes on olukord, kus programm teeb arvutuse, mille tulemus võib ületada kasutatava täisarvu andmetüübi lubatud väärtuste vahemiku.

Seda nimetatakse **integer overflow'ks** ehk täisarvu ületäitumiseks.

Näiteks võib lihtsustatud kujul olla andmetüübi maksimaalne väärtus 255.

```text
250 + 10 = 260
```

Kui kasutatav andmetüüp ei saa väärtust 260 salvestada, võib tulemuseks olla väärtuse ümbermähkimine.

Selline olukord muutub turvaprobleemiks siis, kui vigast tulemust kasutatakse näiteks:

* mälu suuruse arvutamisel;
* puhvri suuruse määramisel;
* andmete kopeerimisel;
* mälu joondamisel.

CVE-2026-21385 kirjelduses on probleem seotud **memory allocation** ehk mälu eraldamisega ja joondamisega. CVE kirjelduse järgi võib selle tulemus olla mälukorruptsioon. ([opencve.jolan-nrt-services.gleeze.com][3])

See tähendab, et programm võib eraldada või käsitleda mälu teisiti kui tegelikult vaja.

Oluline on siinkohal mõista:

> Integer overflow ise ei tähenda automaatselt, et ründaja saab süsteemis koodi käivitada.

Turvarisk sõltub sellest, kuidas vigast tulemust hiljem kasutatakse ja millised kaitsemehhanismid süsteemis töötavad.

---

# 5. Olulised terminid

## Integer overflow

Täisarvu ületäitumine. Arvutuse tulemus ei mahu kasutatava andmetüübi lubatud vahemikku.

## Wraparound

Olukord, kus väärtus pärast maksimaalse võimaliku väärtuse ületamist liigub tagasi lubatud väärtuste algusesse või teise väärtusvahemikku.

## Memory corruption

Mälu korruptsioon ehk olukord, kus programmi kasutatav mälusisu muutub viisil, mida programmi korrektne töö ei eelda.

## Memory allocation

Mälu eraldamine programmile.

## Alignment

Mäluandmete paigutamise või aadresside joondamise reegel, mille järgi andmed paiknevad kindlate piiride suhtes.

## Vulnerability

Turvahaavatavus ehk tarkvara, süsteemi või konfiguratsiooni omadus, mida saab kasutada süsteemi turvalisuse rikkumiseks.

## Exploit

Meetod või programm, mille abil haavatavust ära kasutatakse.

## PoC

**Proof of Concept** – demonstratsioon, millega näidatakse, et konkreetset haavatavust saab teatud tingimustel ära kasutada.

## KEV

**Known Exploited Vulnerabilities** – CISA kataloog teadaolevalt reaalselt ärakasutatud haavatavustest.

---

# 6. CVSS analüüs

CVE-2026-21385 CVSS 3.1 skoor on:

**7.8 – High**

Vektor:

```text
CVSS:3.1/AV:L/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:H
```

## AV:L – Local

Ründevektor on lokaalne.

See tähendab, et CVSS-i hinnangu järgi eeldab ärakasutamine lokaalset ligipääsu.

See on oluline erinevus näiteks Internetist otse rünnatava serveri suhtes.

## AC:L – Low

Rünnaku keerukus on madal.

## PR:L – Low

Ründajal on vaja madala taseme õigusi.

## UI:N – None

Kasutaja täiendavat tegevust ei ole CVSS-i hinnangu järgi vaja.

## S:U – Unchanged

Haavatavuse mõju jääb sama turbeala piiresse.

## C:H – High

Konfidentsiaalsuse mõju on hinnatud kõrgeks.

## I:H – High

Tervikluse mõju on hinnatud kõrgeks.

## A:H – High

Käideldavuse mõju on hinnatud kõrgeks.

CVSS-i põhjal on tegemist tehniliselt kõrge tõsidusega haavatavusega. ([nki.gov.hu][2])

---

# 7. Mõju CIA-triaadile

## Konfidentsiaalsus

Kui haavatavuse edukas ärakasutamine võimaldab ründajal saada täiendava kontrollitaseme, võib tekkida võimalus pääseda ligi andmetele, millele ründajal ei peaks olema ligipääsu.

Android-seadmes võivad sellised andmed olla näiteks:

* rakenduste andmed;
* tööalased dokumendid;
* e-post;
* kontaktid;
* autentimisega seotud andmed.

Täpne mõju sõltub sellest, millise seadme ja tarkvaraga on tegemist ning millise rünnakuahelaga haavatavust kasutatakse.

## Terviklus

Kui ründaja saab süsteemis kõrgemaid õigusi või kontrollib haavatavat komponenti, võib ta potentsiaalselt mõjutada süsteemi või andmete terviklust.

## Käideldavus

Mälukorruptsioon võib põhjustada näiteks:

* rakenduse krahhi;
* süsteemi ebastabiilsust;
* ootamatut käitumist.

CVSS hindab selle haavatavuse võimalikku mõju kõigis kolmes CIA valdkonnas kõrgeks. ([opencve.jolan-nrt-services.gleeze.com][3])

---

# 8. Ründe eeltingimused

CVSS-i järgi on ründevektor **Local** ning vajalikud privileegid on **Low**.

See tähendab, et tegemist ei ole lihtsalt olukorraga, kus suvaline Interneti kasutaja saadab serverile ühe päringu ja haavatavus käivitub.

Ründajal peab olema võimalus jõuda haavatava komponendini lokaalselt.

Üldistatud kujul võib eeltingimus olla:

```text
Ründajal on vajalik lokaalne ligipääs
        ↓
Ründaja saab mõjutada haavatavat komponenti
        ↓
Haavatav arvutus käivitatakse
        ↓
Tekib integer overflow / wraparound
        ↓
Tekib mälukorruptsioon
```

Täpset rünnakumehhanismi ei tohiks aga oletada, kui tootja või turvauuring seda ei dokumenteeri.

---

# 9. Rünnakuahel

Selle CVE põhjal võib koostada **analüütilise** rünnakuahela:

```text
Haavatav Qualcommi komponent
          ↓
Ründaja saavutab vajaliku lokaalse ligipääsu
          ↓
Haavatavat funktsiooni mõjutav sisend
          ↓
Integer overflow / wraparound
          ↓
Vale mäluarvutus
          ↓
Memory corruption
          ↓
Võimalik kontrolli suurenemine
          ↓
Täiendav mõju süsteemile
```

Seda diagrammi tuleb lugeda ettevaatlikult.

Kõik diagrammi etapid ei ole ühe konkreetse avaliku rünnaku kirjeldus.

Diagramm näitab tehnilist loogikat, mille põhjal võib haavatavuse mõju mõista.

---

# 10. Exploit ja PoC

Uurimise käigus tuleb eristada:

### Haavatavus

Programmis või komponendis olev turvaviga.

### PoC

Demonstratsioon, mis näitab haavatavuse ärakasutamise võimalikkust.

### Exploit

Praktiline ärakasutamise tehnika või tööriist.

Selle CVE puhul leidub Internetis viiteid exploit-materjalidele ja GitHubi projektidele, kuid nende olemasolu tuleb hinnata eraldi.

Näiteks võib GitHubis olev projekt olla:

* täielik exploit;
* osaline PoC;
* uurimiskood;
* scanner;
* exploit'i analüüs.

Seetõttu ei tohi kirjutada:

> „GitHubis on kood, järelikult on olemas töötav exploit.“

Õigem on hinnata konkreetse projekti sisu.

---

# 11. Reaalne ärakasutamine

Selles näites on reaalse ärakasutamise uurimine eriti oluline.

Androidi 2026. aasta märtsi turvabülletään märgib CVE-2026-21385 kohta, et on viiteid **piiratud ja sihitud ärakasutamisele**. ([Android Open Source Project][1])

See tähendab, et tegemist ei ole ainult teoreetilise haavatavusega.

Samas ei tohiks sellest teha järeldust, et:

> „Kõik Android-seadmed on selle tõttu rünnaku all.“

Androidi allikas räägib piiratud ja sihitud ärakasutamisest.

CISA lisas CVE-2026-21385 3. märtsil 2026 KEV-kataloogi, kirjeldades seda Qualcommi mitme kiibistiku mälukorruptsiooni haavatavusena. CISA teatas samal kuupäeval, et kataloogi lisamine põhines tõenditel aktiivse ärakasutamise kohta. ([content.govdelivery.com][4])

Seega on siin kaks olulist tõendit:

1. Androidi tootjaökosüsteemi ametlik turvabülletään;
2. CISA KEV-kirje.

---

# 12. CISA KEV analüüs

CISA lisas:

**CVE-2026-21385**

KEV-kataloogi:

**3. märtsil 2026.**

Remediatsioonitähtajaks määrati:

**24. märts 2026.** ([opencve.jolan-nrt-services.gleeze.com][3])

CISA KEV-i olemasolu on oluline, sest see näitab, et haavatavuse puhul on olemas teave reaalse ärakasutamise kohta.

CISA soovitab organisatsioonidel KEV-kataloogis olevaid haavatavusi oma haavatavuste halduses prioriseerida. CISA märgib, et kataloog on elav nimekiri teadaolevalt ärakasutatud haavatavustest. ([content.govdelivery.com][4])

Organisatsiooni jaoks tähendab see, et CVE olemasolu KEV-is annab olulise põhjuse kontrollida, kas vastavad seadmed on organisatsioonis kasutusel.

---

# 13. Zero-day analüüs

Zero-day staatust tuleb käsitleda eraldi.

Selle töö puhul on teada, et haavatavuse kohta oli enne avalikustamist olemas ärakasutamisega seotud info ning Androidi märtsi turvabülletään sisaldab viidet piiratud ja sihitud ärakasutamisele. ([Android Open Source Project][1])

Samas ei ole ainult selle info põhjal mõistlik kirjeldada kogu ajavahemikku automaatselt zero-day perioodina.

Zero-day staatuse täpsemaks hindamiseks peaks uurima:

* millal haavatavus avastati;
* millal tootjat teavitati;
* millal parandus valmis;
* millal CVE avaldati;
* millal ärakasutamist täheldati.

Seetõttu on teaduslikult korrektsem kirjutada:

> Avalikud allikad kinnitavad, et haavatavuse kohta oli olemas info piiratud ja sihitud ärakasutamise kohta. Zero-day perioodi täpset pikkust ei saa ainult avalike põhiallikate põhjal kindlalt määrata.

---

# 14. Ajakava

| Kuupäev    | Sündmus                                    |
| ---------- | ------------------------------------------ |
| 17.12.2025 | CVE on reserveeritud                       |
| 02.03.2026 | CVE avaldatakse                            |
| 02.03.2026 | Androidi märtsi turvabülletään avaldatakse |
| 03.03.2026 | CISA lisab CVE KEV-kataloogi               |
| 24.03.2026 | KEV remediatsioonitähtaeg                  |
| 04.03.2026 | CVE andmeid täiendatakse                   |

CVE andmebaasi ajaloo järgi reserveeriti CVE 17. detsembril 2025 ning avaldati 2. märtsil 2026. ([opencve.jolan-nrt-services.gleeze.com][3])

Androidi märtsi turvabülletään näitab, et vastav turvapaiga tase on **2026-03-05**. ([Android Open Source Project][1])

---

# 15. Mõjutatud versioonid

CVE ei puuduta lihtsalt „Androidi“ tervikuna.

Probleem on seotud Qualcommi komponentidega ning mõjutatud toodete loend on ulatuslik.

Avaldatud haavatavusandmetes on näiteks Qualcommi platvorme ja firmware-komponente. Mõjutatud toodete täpne nimekiri tuleb kontrollida Qualcommi turvabülletäänist ja vastavatest seadmetootja teadetest. ([Enginsight Vulnerability Database][5])

See on oluline õppetund:

> Kui CVE on seotud kindla riistvara või firmware-komponendiga, ei saa ainult operatsioonisüsteemi nime järgi otsustada, kas konkreetne seade on haavatav.

Android-seadme puhul tuleb kontrollida vähemalt:

* seadme mudelit;
* kiibistikku;
* tootja turvapaiga taset;
* tootja turvateadet.

---

# 16. Parandus

Androidi märtsi 2026 turvabülletään teatab, et **2026-03-05 või hilisem turvapaiga tase** lahendab selles bülletäänis kirjeldatud probleemid. ([Android Open Source Project][1])

Seadme kasutaja peaks kontrollima:

**Settings → System → Software update**

ning vaatama seadme turvapaiga kuupäeva.

Organisatsioonis tuleks lisaks kontrollida:

1. millised Android-seadmed on kasutusel;
2. milliseid Qualcommi komponente need kasutavad;
3. milline on seadmete turvapaiga tase;
4. kas tootja on vastava paranduse seadmele kättesaadavaks teinud;
5. kas vanematele seadmetele on üldse turvauuendus saadaval.

---

# 17. Leevendusmeetmed

Kui parandust ei saa kohe paigaldada, võib organisatsioon vähendada riski näiteks:

* MDM-i kasutamisega;
* seadme ligipääsu piiramisega;
* vananenud seadmete eemaldamisega;
* tundlike rakenduste ligipääsu piiramisega;
* seadmete regulaarse patch-level kontrolliga;
* kompromiteerumise tunnuste jälgimisega.

Näiteks võib MDM-poliitika nõuda:

```text
Android security patch level
        ↓
Kontroll
        ↓
Piisavalt uus?
   ↙          ↘
 Jah          Ei
 ↓             ↓
Lubatud      Piiratud ligipääs
```

Oluline on:

> Leevendusmeede ei muuda haavatavat koodi parandatuks.

See vähendab riski seni, kuni tegelik parandus on paigaldatud.

---

# 18. Mõju organisatsioonile

Organisatsioonis võib Android-seade olla osa infosüsteemist.

Telefonis võivad olla:

* töömeil;
* Microsoft 365 või Google Workspace;
* MFA-rakendus;
* VPN;
* tööalased dokumendid;
* kontaktid;
* ettevõtte rakendused.

Seetõttu võib mobiiliseadme kompromiteerumine mõjutada ka organisatsiooni.

Näiteks:

```text
Haavatav telefon
       ↓
Ründaja saab seadmes kõrgema kontrolli
       ↓
Ligipääs tööalastele andmetele
       ↓
Võimalik kontode või andmete kompromiteerumine
       ↓
Mõju organisatsiooni infosüsteemidele
```

See on aga stsenaarium, mitte väide, et kõik need sammud toimuvad CVE-2026-21385 puhul automaatselt.

---

# 19. Võimalik rünnaku areng

Analüütilise näitena võib rünnak areneda järgmiselt:

```text
1. Ründaja valib sihtmärgi
        ↓
2. Leiab haavatava seadme
        ↓
3. Saavutab vajaliku lokaalse ligipääsu
        ↓
4. Käivitab haavatavat komponenti mõjutava tegevuse
        ↓
5. Tekib integer overflow
        ↓
6. Tekib memory corruption
        ↓
7. Ründaja püüab saavutada suuremat kontrolli
        ↓
8. Võimalik ligipääs tundlikumatele ressurssidele
        ↓
9. Võimalik mõju andmetele või süsteemile
```

Selles skeemis tuleb eristada:

**tehniliselt dokumenteeritud asjaolud**

ja

**uurija koostatud võimalikku stsenaariumi**.

See vahe on küberturvalisuse uurimistöös väga oluline.

---

# 20. MITRE ATT&CK seosed

Selle CVE puhul võib rünnaku hilisemate etappide kirjeldamisel olla asjakohane näiteks **Privilege Escalation**.

Põhjus on selles, et CVSS-i hinnang sisaldab:

```text
PR:L
```

ning haavatavuse võimalik mõju võib ulatuda kõrgemate õiguste saavutamiseni.

Siiski ei ole õige lihtsalt kirjutada:

> „CVE-2026-21385 = MITRE T1055.“

ATT&CK tehnika tuleb valida alles siis, kui konkreetse rünnaku tehniline tegevus on teada.

Seetõttu võib näidistöös olla täiesti korrektne järeldus:

> Avaliku info põhjal ei ole piisavalt detailset konkreetse rünnaku kirjeldust, et siduda kõik võimalikud ründeetapid kindlate MITRE ATT&CK tehnikatega.

See näitab uurija kriitilist mõtlemist.

---

# 21. CVSS, EPSS ja KEV võrdlus

## CVSS

CVSS 3.1:

**7.8 – High**

See kirjeldab haavatavuse tehnilist tõsidust.

## EPSS

EPSS hindab, kui tõenäoline on haavatavuse ärakasutamine teatud ajahorisondil.

EPSS ei tähenda:

> „Tõenäosus, et minu arvutit rünnatakse.“

See on statistiline prognoos haavatavuse ärakasutamise kohta ning selle väärtus muutub ajas.

## KEV

CVE-2026-21385 on CISA KEV-kataloogis.

See on oluline erinevus:

```text
CVSS
→ tehniline tõsidus

EPSS
→ ärakasutamise tõenäosuse prognoos

KEV
→ teadaolev reaalne ärakasutamine
```

Seetõttu ei ole need kolm näitajat omavahel asendatavad.

---

# 22. Uurimistulemuste kokkuvõtte tabel

| Näitaja                        | Tulemus                                                        |
| ------------------------------ | -------------------------------------------------------------- |
| CVE                            | CVE-2026-21385                                                 |
| Tootja                         | Qualcomm                                                       |
| Seotud ökosüsteem              | Android / Qualcommi komponendid                                |
| Haavatavuse tüüp               | Integer Overflow / Wraparound                                  |
| CWE                            | CWE-190                                                        |
| CVE kirjeldus                  | Memory corruption while using alignments for memory allocation |
| CVSS                           | 7.8 High                                                       |
| Attack Vector                  | Local                                                          |
| Attack Complexity              | Low                                                            |
| Privileges Required            | Low                                                            |
| User Interaction               | None                                                           |
| Confidentiality                | High                                                           |
| Integrity                      | High                                                           |
| Availability                   | High                                                           |
| Reaalne ärakasutamine          | Androidi allikas viitab piiratud ja sihitud ärakasutamisele    |
| CISA KEV                       | Jah                                                            |
| KEV lisamise kuupäev           | 03.03.2026                                                     |
| KEV tähtaeg                    | 24.03.2026                                                     |
| Androidi paranduse patch level | 2026-03-05 või hilisem                                         |
| Peamine kaitsemeede            | Turvauuenduse paigaldamine                                     |

Põhiandmed on kontrollitavad Androidi, CISA ja CVE andmetest. ([Android Open Source Project][1])

---

# 23. Järeldused

CVE-2026-21385 on Qualcommi komponentidega seotud kõrge tõsidusega turvahaavatavus, mille keskmes on integer overflow / wraparound ning sellest tulenev võimalik mälukorruptsioon. CVSS 3.1 skoor on 7.8 ning vektori järgi on ründevektor lokaalne, vajalikud privileegid madalad ning kasutaja täiendav tegevus ei ole vajalik. ([nki.gov.hu][2])

Uurimise seisukohalt on oluline, et Androidi 2026. aasta märtsi turvabülletään sisaldab viidet piiratud ja sihitud ärakasutamisele. Lisaks lisas CISA haavatavuse KEV-kataloogi, viidates aktiivse ärakasutamise tõenditele. ([Android Open Source Project][1])

See tähendab, et haavatavust ei saa käsitleda ainult teoreetilise programmeerimisveana.

Organisatsiooni jaoks on oluline kontrollida, kas kasutuses olevad Android-seadmed sisaldavad mõjutatud Qualcommi komponente ning kas nende turvapaiga tase on piisavalt uus.

Peamine kaitsemeede on tootja pakutud turvaparanduse rakendamine. Kui parandust ei ole võimalik kohe paigaldada, tuleb kasutada täiendavaid leevendusmeetmeid, näiteks MDM-i, seadmete ligipääsu piiramist ja vananenud seadmete kasutusest eemaldamist.

---

# 24. Kasutatud allikad

### 1. Android Open Source Project

**Android Security Bulletin – March 2026**

Kasutatud:

* CVE kirjelduse;
* ärakasutamise info;
* Androidi patch level'i;
* paranduse info kontrollimiseks.

([Android Open Source Project][1])

### 2. CISA

**Known Exploited Vulnerabilities / CISA advisory**

Kasutatud:

* KEV staatuse;
* lisamise kuupäeva;
* aktiivse ärakasutamise info;
* remediatsiooni tähtaja kontrollimiseks.

([content.govdelivery.com][4])

### 3. CVE / NVD andmed

Kasutatud:

* CVE identifikaatori;
* CVSS skoori;
* CVSS vektori;
* CWE seose;
* mõjutatud toodete info kontrollimiseks.

([opencve.jolan-nrt-services.gleeze.com][3])

### 4. Qualcomm

Qualcommi 2026. aasta märtsi turvabülletään on oluline esmase allikana mõjutatud Qualcommi komponentide ja paranduste kohta.

---

# 25. Mida sellest näitest enda töö jaoks üle võtta?

Kõige olulisem ei ole võtta sellest näitest üle valmis lauseid.

Üle tasub võtta **uurimise ülesehitus**.

Iga enda valitud CVE puhul võiksid endalt küsida:

```text
1. Mis CVE-ga on tegemist?
        ↓
2. Mis komponendis probleem asub?
        ↓
3. Mis on tehniliselt valesti?
        ↓
4. Millist tüüpi haavatavusega on tegemist?
        ↓
5. Milline on CVSS?
        ↓
6. Mida CVSS-vektor tähendab?
        ↓
7. Milline on mõju CIA-triaadile?
        ↓
8. Mida ründaja vajab?
        ↓
9. Milline võiks olla rünnakuahel?
        ↓
10. Kas PoC või exploit on olemas?
        ↓
11. Kas haavatavust on päriselt kasutatud?
        ↓
12. Kas see on CISA KEV-is?
        ↓
13. Kas tegemist võis olla zero-day'ga?
        ↓
14. Millal sündmused toimusid?
        ↓
15. Millised versioonid on mõjutatud?
        ↓
16. Kuidas haavatavust parandada?
        ↓
17. Millised leevendusmeetmed on võimalikud?
        ↓
18. Mis võiks juhtuda organisatsioonis?
        ↓
19. Millised ATT&CK seosed on põhjendatud?
        ↓
20. Milline on minu põhjendatud järeldus?
```

Hea uurimistöö ei ole see, kus on kõige rohkem teksti.

Hea uurimistöö on see, kus **iga oluline väide on põhjendatud, allikaga kontrollitav ja uurija enda sõnadega arusaadav**.

Kõige olulisem on osata eristada:

> **mida allikas ütleb, mida sellest saab järeldada ja mida me tegelikult ei tea.**

Just see eristus teeb lihtsast CVE kirjeldusest küberturvalisuse uurimistöö.

[1]: https://source.android.com/docs/security/bulletin/2026/2026-03-01?hl=ja&utm_source=chatgpt.com "Android のセキュリティに関する公開情報 - 2026 年 3 月  |  Android Open Source Project"
[2]: https://nki.gov.hu/figyelmeztetesek/serulekenysegek/cve-2026-21385/?utm_source=chatgpt.com "CVE-2026-21385 – Nemzeti Kiberbiztonsági Intézet"
[3]: https://opencve.jolan-nrt-services.gleeze.com/cve/CVE-2026-21385?utm_source=chatgpt.com "CVE-2026-21385 - Vulnerability Details - OpenCVE"
[4]: https://content.govdelivery.com/accounts/USDHSCISA/bulletins/40c7dc3?utm_source=chatgpt.com "CISA Adds Two Known Exploited Vulnerabilities to Catalog"
[5]: https://cve.enginsight.com/2026/21385/index.html?utm_source=chatgpt.com "CVE-2026-21385 :: Enginsight Vulnerability Database"
