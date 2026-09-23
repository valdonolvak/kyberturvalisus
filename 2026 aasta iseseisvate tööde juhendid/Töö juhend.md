## Tööjuhend. 

# Küberturvalisuse uurimistöö

## CVE-põhise haavatavuse uurimise juhend

---

# 1. Töö eesmärk

Selle töö eesmärk on uurida ühte konkreetset tarkvara või operatsioonisüsteemiga seotud turvahaavatavust ning näidata, et oskad:

* leida usaldusväärset infot;
* kasutada erinevaid küberturbe allikaid;
* selgitada tehnilist probleemi oma sõnadega;
* mõista CVSS-hinnangut;
* analüüsida võimalikku rünnakut;
* otsida tõendeid reaalse ärakasutamise kohta;
* hinnata mõju kasutajale ja organisatsioonile;
* leida tootja pakutud paranduse;
* pakkuda põhjendatud leevendusmeetmeid;
* teha allikatele tuginevaid järeldusi.

Töö eesmärk ei ole CVE kirjeldust Internetist ümber kirjutada.

Oluline on näidata, et **sa saad uuritud haavatavusest aru**.

---

# 2. Tööülesanne

Vali üks sulle määratud või lubatud CVE ning koosta selle kohta uurimistöö.

Uuritav haavatavus peab olema piisavalt hästi dokumenteeritud, et selle kohta oleks võimalik leida:

* CVE põhiandmed;
* tehniline kirjeldus;
* mõjutatud süsteemid;
* CVSS-hinnang;
* paranduse info;
* võimalikud ründeviisid;
* võimalusel info reaalse ärakasutamise kohta.

Töö peab vastama küsimusele:

> **Mis on selle haavatavuse puhul tehniliselt valesti, kuidas seda võiks ära kasutada, milline oleks selle mõju ning mida saab süsteemi omanik riski vähendamiseks teha?**

---

# 3. Milline peaks töö ülesehitus olema?

Töö koosneb järgmistest põhiosadest:

1. Sissejuhatus
2. CVE üldandmed
3. Haavatavuse tehniline kirjeldus
4. Olulised terminid
5. CVSS analüüs
6. Mõju CIA-triaadile
7. Ründe eeltingimused
8. Rünnakuahel
9. Exploit ja PoC
10. Reaalne ärakasutamine
11. CISA KEV analüüs
12. Zero-day analüüs
13. Ajakava
14. Mõjutatud versioonid
15. Parandus
16. Leevendusmeetmed
17. Mõju organisatsioonile
18. Võimalik rünnaku areng
19. MITRE ATT&CK seosed
20. CVSS, EPSS ja KEV võrdlus
21. Uurimistulemuste kokkuvõtte tabel
22. Järeldused
23. Kasutatud allikad

Kõiki peatükke ei pea kirjutama ühepikkuselt.

Tehniline kirjeldus ja ründeanalüüs võivad olla pikemad kui näiteks sissejuhatus.

---

# 4. Sissejuhatus

Sissejuhatuses anna lühidalt teada:

* millist CVE-d uurid;
* millise süsteemi või tootega see seotud on;
* miks on selle uurimine asjakohane;
* mida oma töös analüüsid.

Sissejuhatus ei pea olema pikk.

Soovituslik pikkus:

**umbes 0,5 lehekülge.**

### Küsi endalt

* Mis CVE-d uurin?
* Mis toodet või süsteemi see mõjutab?
* Mida kavatsen töö käigus välja selgitada?

---

# 5. CVE üldandmed

Leia ja esita vähemalt:

* CVE number;
* haavatavuse nimetus;
* tootja;
* mõjutatud toode või komponent;
* haavatavuse tüüp;
* avaldamise kuupäev;
* CVSS-hinnang;
* CWE, kui see on määratud.

Kasuta võimalusel esmaseid allikaid.

Näiteks:

* CVE.org;
* NVD;
* tootja turvateade;
* operatsioonisüsteemi turvabülletään.

### Ära tee ainult tabelit

Tabel on kasulik ülevaateks, kuid lisa ka lühike selgitus.

---

# 6. Haavatavuse tehniline kirjeldus

Siin tuleb selgitada:

> **Mis programmis või komponendis tegelikult valesti on?**

Ära piirdu lausega:

> „Tegemist on buffer overflow'ga.“

Selgita:

* mis komponenti probleem puudutab;
* milline programmeerimis- või töötlusviga tekib;
* millist tüüpi andmeid töödeldakse;
* miks tekib turvaprobleem;
* milline võib olla tulemus.

Kui kasutatakse keerulist terminit, selgita see lahti.

Näiteks:

**Integer overflow** – täisarvu ületäitumine, mille korral arvutuse tulemus ületab kasutatava andmetüübi lubatud väärtuste vahemiku.

---

# 7. Olulised terminid

Koosta töö jaoks väike terminoloogia osa.

Vali vähemalt **5–8 olulist terminit**.

Näiteks võivad olla:

* vulnerability;
* exploit;
* PoC;
* memory corruption;
* privilege escalation;
* remote code execution;
* integer overflow;
* patch;
* mitigation;
* zero-day.

Iga termini juures:

1. ingliskeelne termin;
2. eestikeelne vaste;
3. lühike selgitus;
4. vajadusel seos uuritud CVE-ga.

---

# 8. CVSS analüüs

Leia uuritud CVE CVSS-hinnang.

Esita:

* skoor;
* tõsidusklass;
* CVSS-versioon;
* CVSS-vektor.

Seejärel **analüüsi vektorit**.

Näiteks tuleb vajadusel selgitada:

* Attack Vector;
* Attack Complexity;
* Privileges Required;
* User Interaction;
* Scope;
* Confidentiality;
* Integrity;
* Availability.

### Oluline

Ära kirjuta ainult:

> CVSS = 7.8 High.

Selgita, **mida see sinu uuritud haavatavuse puhul tähendab**.

---

# 9. Mõju CIA-triaadile

Analüüsi eraldi:

### Confidentiality – konfidentsiaalsus

Kas ründaja võiks saada ligipääsu andmetele, millele tal ei peaks olema ligipääsu?

### Integrity – terviklus

Kas ründaja võiks muuta andmeid, konfiguratsiooni või süsteemi tööd?

### Availability – käideldavus

Kas rünnak võiks põhjustada:

* teenuse katkemise;
* programmi krahhi;
* süsteemi ebastabiilsuse;
* seadme töö häirumise?

Ära eelda, et kõik kolm on alati mõjutatud.

Põhjenda oma hinnangut.

---

# 10. Ründe eeltingimused

Uuri:

> **Mida peab ründaja enne tegema, et haavatavust kasutada?**

Näiteks võib olla vaja:

* lokaalset ligipääsu;
* kasutajakontot;
* madalaid õigusi;
* füüsilist ligipääsu;
* spetsiaalset sisendit;
* ohvri tegevust;
* võrguühendust.

Eralda:

**teadaolevad eeltingimused**

ja

**enda analüütilised järeldused**.

Kui täpne ründeviis ei ole avalikult dokumenteeritud, kirjuta seda ausalt.

---

# 11. Rünnakuahel

Koosta diagramm, mis näitab võimalikku rünnaku kulgu.

Näiteks:

```text
Ründaja
   ↓
Esialgne ligipääs
   ↓
Haavatava komponendi käivitamine
   ↓
Haavatavuse ärakasutamine
   ↓
Täiendavad õigused
   ↓
Andmetele või süsteemile ligipääs
   ↓
Lõplik mõju
```

Diagramm peab olema seotud sinu konkreetse CVE-ga.

Ära kasuta lihtsalt üldist küberrünnaku skeemi.

---

# 12. Exploit ja PoC

Uuri:

* kas avalik PoC on olemas;
* kas avalik exploit on olemas;
* millal see avaldati;
* kes selle avaldas;
* kas tegemist on töötava exploitiga või ainult demonstratsiooniga.

### Väga oluline erinevus

**PoC olemasolu ei tõesta automaatselt reaalset rünnakut.**

Näiteks:

> GitHubis on PoC.

See tähendab, et keegi on avaldanud tehnilise demonstratsiooni.

See ei tähenda automaatselt:

> Seda kasutatakse massiliselt rünnakutes.

---

# 13. Reaalne ärakasutamine

Otsi tõendeid selle kohta, kas haavatavust on reaalselt kasutatud.

Kontrolli näiteks:

* tootja turvateadet;
* CISA KEV-i;
* CERT-organisatsioonide teateid;
* turvafirmade uuringuid;
* usaldusväärseid küberturbe uudiseid.

Kirjuta selgelt:

* kas ärakasutamine on kinnitatud;
* kas tegemist on piiratud või laialdase ärakasutamisega;
* millal seda märgati;
* kelle hinnangul seda kasutati.

Kui tõendeid ei ole, ära kirjuta, et rünnak toimus.

Kirjuta näiteks:

> Avalikest allikatest ei leitud kinnitust reaalse ärakasutamise kohta.

---

# 14. CISA KEV analüüs

Kontrolli, kas CVE on:

**CISA Known Exploited Vulnerabilities (KEV)** kataloogis.

Kui on, esita:

* lisamise kuupäev;
* remediatsioonitähtaeg;
* CISA kirjeldus;
* tootja või süsteemiomaniku soovitatud tegevus.

Selgita ka:

> Miks muudab KEV-kirje selle haavatavuse uurimise seisukohalt oluliseks?

Kui CVE-d KEV-is ei ole, kirjuta seda samuti.

Ära järelda ainult KEV puudumise põhjal, et haavatavust ei ole kasutatud.

---

# 15. Zero-day analüüs

Uuri:

* millal haavatavus avastati;
* millal tootjat teavitati;
* millal parandus valmis;
* millal info avalikustati;
* kas ärakasutamine võis toimuda enne paranduse avaldamist.

Kui allikad ei võimalda zero-day staatust kindlaks teha, ütle seda.

Ära kasuta terminit **zero-day** lihtsalt seetõttu, et haavatavus oli tõsine.

---

# 16. Ajakava

Koosta kronoloogiline ülevaade.

Näiteks:

| Kuupäev | Sündmus                |
| ------- | ---------------------- |
| kuupäev | Haavatavuse avastamine |
| kuupäev | Tootjale teatamine     |
| kuupäev | CVE avaldamine         |
| kuupäev | Turvabülletään         |
| kuupäev | Paranduse avaldamine   |
| kuupäev | KEV-i lisamine         |
| kuupäev | Avalik exploit / PoC   |

Kõik kuupäevad peavad olema allikatega kontrollitavad.

---

# 17. Mõjutatud versioonid

Leia võimalikult täpselt:

* mõjutatud tarkvaraversioonid;
* mõjutatud seadmed;
* mõjutatud komponendid;
* parandatud versioonid;
* turvapaiga kuupäev.

Kui mõjutatud toodete nimekiri on väga pikk, võib selle esitada eraldi tabelina või lisana.

---

# 18. Parandus

Leia tootja ametlik lahendus.

Kirjelda:

* milline parandus avaldati;
* millise versiooni või patch level'iga;
* millal see avaldati;
* kuidas kasutaja saab kontrollida, kas parandus on paigaldatud.

Kui tegemist on Androidiga, võib näiteks olla oluline:

**Security patch level**

Kui tegemist on Windowsiga, võib olla oluline:

**KB-number**

Kui tegemist on Linuxiga:

**paketi parandatud versioon.**

---

# 19. Leevendusmeetmed

Kui parandust ei saa kohe rakendada, otsi ajutisi lahendusi.

Näiteks:

* ligipääsu piiramine;
* süsteemi isoleerimine;
* MDM-poliitika;
* teenuse ajutine väljalülitamine;
* haavatava seadme eemaldamine;
* õiguste vähendamine;
* seire tõhustamine.

Iga leevendusmeetme puhul selgita:

> Mida see vähendab ja mida see ei lahenda?

---

# 20. Mõju organisatsioonile

Võta uuritud haavatavus organisatsiooni konteksti.

Mõtle:

* milliseid seadmeid organisatsioon kasutab;
* kas haavatav süsteem võib olla organisatsioonis kasutusel;
* milliseid andmeid seal töödeldakse;
* millised kontod või teenused on seotud;
* kas süsteem pääseb ettevõtte võrku;
* milline võiks olla mõju tööprotsessidele.

Ära kirjuta lihtsalt:

> „Organisatsioon võib saada kahjustada.“

Kirjelda **konkreetset võimalikku mõju**.

---

# 21. Võimalik rünnaku areng

Koosta analüüs:

> Mis võiks juhtuda pärast haavatavuse edukat ärakasutamist?

Näiteks:

```text
Haavatav süsteem
       ↓
Haavatavuse ärakasutamine
       ↓
Täiendavad õigused
       ↓
Süsteemi uurimine
       ↓
Ligipääs andmetele
       ↓
Andmete muutmine või varastamine
       ↓
Mõju organisatsioonile
```

Märgi ära, millised sammud on allikatega tõendatud ja millised on sinu analüütiline stsenaarium.

---

# 22. MITRE ATT&CK seosed

Kui see on uuritud haavatavuse puhul põhjendatud, otsi seoseid MITRE ATT&CK raamistikust.

Võimalikud teemad võivad olla:

* Execution;
* Privilege Escalation;
* Credential Access;
* Discovery;
* Collection;
* Exfiltration.

Ära lisa tehnikat ainult sellepärast, et selle nimi sobib.

Iga seos peab olema põhjendatud.

---

# 23. CVSS, EPSS ja KEV võrdlus

Selgita kolme erinevat mõistet:

| Näitaja | Küsimus                                           |
| ------- | ------------------------------------------------- |
| CVSS    | Kui tõsine on haavatavus tehniliselt?             |
| EPSS    | Kui tõenäoliselt võidakse seda ära kasutada?      |
| KEV     | Kas teadaolevalt on seda reaalselt ära kasutatud? |

Kui EPSS on olemas, lisa töö koostamise kuupäeva seisuga väärtus.

Ära käsitle EPSS-i kui kindlat ennustust.

---

# 24. Uurimistulemuste kokkuvõtte tabel

Koosta töö lõpus kokkuvõtte tabel.

Näiteks:

| Näitaja               | Tulemus |
| --------------------- | ------- |
| CVE                   |         |
| Toode                 |         |
| Komponent             |         |
| Haavatavuse tüüp      |         |
| CWE                   |         |
| CVSS                  |         |
| Ründevektor           |         |
| Reaalne ärakasutamine |         |
| KEV                   |         |
| PoC                   |         |
| Mõjutatud versioonid  |         |
| Parandus              |         |
| Peamine leevendus     |         |

---

# 25. Järeldused

Järeldustes vasta:

1. Mis oli uuritud haavatavuse peamine probleem?
2. Milline on selle võimalik mõju?
3. Kas haavatavust on reaalselt ära kasutatud?
4. Kui tugevad on selle kohta olemasolevad tõendid?
5. Millised süsteemid on mõjutatud?
6. Kuidas haavatavust parandada?
7. Millised meetmed vähendavad riski?

Järeldus peab tulenema sinu uurimistööst.

---

# 26. Kasutatud allikad

Eelista järgmises järjekorras:

### 1. Tootja ametlik allikas

Näiteks:

* Microsoft;
* Apple;
* Google/Android;
* Qualcomm;
* Linuxi distributsioon.

### 2. CVE/NVD

* CVE.org;
* NVD.

### 3. CISA

Eriti KEV-kataloog.

### 4. Turvaorganisatsioonid

Näiteks:

* CERT;
* RIA/CERT-EE;
* Google Project Zero;
* Mandiant;
* ESET;
* Cisco Talos.

### 5. Usaldusväärne tehniline ajakirjandus

Näiteks:

* BleepingComputer;
* The Record.

### 6. GitHub ja muud tehnilised allikad

Neid võib kasutada PoC-i või tehnilise materjali leidmiseks, kuid allika usaldusväärsust tuleb eraldi hinnata.

---

# 27. Töö vormistus ja soovituslik maht

Soovituslik maht:

**8–15 lehekülge**

Töö võib olla Markdown-formaadis.

Soovituslik struktuur:

```text
# Pealkiri

## 1. Sissejuhatus

## 2. CVE üldandmed

## 3. Haavatavuse tehniline kirjeldus

...

## 23. Kasutatud allikad
```

Kasuta:

* pealkirju;
* tabeleid;
* jooniseid;
* rünnakuahela skeeme;
* allikaviiteid.

Väldi pikki kopeeritud tekstiplokke.

---

# 28. Hindamise põhimõtted

Tööd hinnates pööratakse tähelepanu järgmistele aspektidele.

| Valdkond              | Mida hinnatakse?                                |
| --------------------- | ----------------------------------------------- |
| Allikad               | Kas allikad on usaldusväärsed?                  |
| Tehniline mõistmine   | Kas probleem on oma sõnadega selgitatud?        |
| CVSS                  | Kas skoori ja vektorit mõistetakse?             |
| Ründeanalüüs          | Kas ründe eeltingimused on arusaadavad?         |
| Reaalne ärakasutamine | Kas väited on tõendatud?                        |
| Mõju                  | Kas CIA ja organisatsiooni mõju on analüüsitud? |
| Parandus              | Kas lahendus on leitud?                         |
| Järeldused            | Kas need tulenevad uurimusest?                  |
| Vormistus             | Kas töö on loogiline ja loetav?                 |

---

# 29. Enne esitamist kontrolli

Enne töö esitamist kontrolli:

* [ ] CVE number on õige.
* [ ] Mõjutatud toode on õige.
* [ ] Tehniline probleem on oma sõnadega selgitatud.
* [ ] Olulised terminid on lahti seletatud.
* [ ] CVSS skoor on kontrollitud.
* [ ] CVSS vektor on lahti seletatud.
* [ ] CIA mõju on analüüsitud.
* [ ] Ründe eeltingimused on kirjeldatud.
* [ ] Rünnakuahel on olemas.
* [ ] PoC ja exploit on eristatud.
* [ ] Reaalne ärakasutamine on eraldi uuritud.
* [ ] CISA KEV on kontrollitud.
* [ ] Zero-day staatust ei ole oletatud.
* [ ] Ajakava on koostatud.
* [ ] Mõjutatud versioonid on kontrollitud.
* [ ] Parandus on leitud.
* [ ] Leevendusmeetmed on kirjeldatud.
* [ ] Organisatsiooni mõju on analüüsitud.
* [ ] Vajadusel on lisatud MITRE ATT&CK seosed.
* [ ] CVSS, EPSS ja KEV on eristatud.
* [ ] Kõik olulised väited on allikatega kontrollitavad.
* [ ] Kasutatud allikad on töö lõpus välja toodud.

---

## Töö põhiküsimus

Kogu töö jooksul hoia meeles ühte põhiküsimust:

> **Mis on selle CVE puhul tehniliselt valesti, kuidas seda võiks ära kasutada, milline oleks selle mõju ning mida saab süsteemi omanik teha riski vähendamiseks?**

Kui sinu töö annab sellele küsimusele põhjendatud vastuse, on töö põhieesmärk täidetud.

---

## Oluline

Näidistöö on eraldi dokument.

Seda tööjuhendit tuleb kasutada **oma valitud CVE uurimiseks**. Näidistöö eesmärk on näidata, millise sisulise tasemeni võiks valmis töö jõuda.

---
