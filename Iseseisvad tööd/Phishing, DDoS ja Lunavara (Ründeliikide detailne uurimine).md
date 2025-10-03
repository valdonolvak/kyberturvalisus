
## 1. Loengumaterjal: Phishing, DDoS ja Lunavara (Ründeliikide detailne uurimine)

See materjal on mõeldud toetama praktilist tegevust ja süvendama arusaama kolmest levinumast ja mõjukamast küberturvalisuse ohust.

### Sissejuhatus: Tänased kõige ohtlikumad ründed

Kuigi ründeliike on palju, põhjustavad **Phishing**, **DDoS** ja **Lunavara (Ransomware)** täna ettevõtetele ja üksikisikutele enim majanduslikku ja käideldavuse kahju.

| Ründeliik | Eesmärk | Põhiline Mõju (CIA) |
| :---: | :--- | :--- |
| **Phishing (Õngitsemine)** | Konfidentsiaalse info (paroolid, krediitkaardid) varastamine, pahavara levitamine. | **Konfidentsiaalsus** |
| **DDoS (Teenusetõkestamine)** | Teenuse/veebilehe kättesaamatuks muutmine. | **Käideldavus** |
| **Lunavara (Ransomware)** | Andmete krüpteerimine ja nende eest lunaraha nõudmine. | **Terviklikkus** ja **Käideldavus** |

---

### A. Phishing ehk Õngitsemine 🎣

**Definitsioon:** Sotsiaalse inseneeria meetod, kus ründaja maskeerib end usaldusväärseks isikuks või asutuseks, et ohver avaldaks konfidentsiaalset teavet või klõpsaks pahatahtlikul lingil.

#### Peamised tüübid:
1.  **Mass-Phishing:** Saadetakse suurele hulgale inimestele, näiteks "Teie Netflixi konto aegus!"
2.  **Spear Phishing (Täppisõngitsemine):** Suunatud kindlale isikule või organisatsioonile. Sisu on isikupärastatud.
3.  **Whaling (Vaalapüük):** Suunatud tippjuhtidele (CEO, CFO).

#### Kuidas toimib?
Phishing kasutab ära inimlikke nõrkusi: **kiirustamine, hirm, ahnus** või **autoriteedi austamine**. Rünnakud on tihti ajastatud (nt. maksutagastused, viirusepuhangud) ja jäljendavad täiuslikult ametlikke lehti.

---

### B. DDoS (Distributed Denial of Service) 🚫

**Definitsioon:** Teenuse kättesaamatuks muutmine, uputades sihtmärgi võrgu või serveri üleliigse liiklusega mitmetest (sageli kompromiteeritud) allikatest (botnet).

#### Peamised tüübid:
1.  **Mahuründed (Volume-Based):** Eesmärk on võrguühenduse (ribalaiuse) täielik ülekoormamine (nt. UDP, ICMP paketid).
2.  **Protokolli ründed:** Kasutavad ära võrguprotokollide nõrkusi (nt. SYN Flood, Smurf), koormates üle võrguseadmete ressursid.
3.  **Rakenduse ründed (Layer 7):** Suunatud veebirakendustele (HTTP GET/POST päringute massiline saatmine), simuleerides tavakasutaja käitumist ja koormates üle serveri protsessori/andmebaasi.

#### Mõju:
Teenus on kättesaamatu, mis põhjustab rahalist kahju (e-kaubandus) või mainekahju.

---

### C. Lunavara (Ransomware) 💰

**Definitsioon:** Pahavara, mis krüpteerib ohvri andmed ja nõuab nende dekrüpteerimise eest lunaraha (tavaliselt krüptovaluutas).

#### Toimemehhanism:
1.  **Sissetung:** Ründaja saab ligipääsu (sageli Phishing'u kaudu või paikamata haavatavuste ärakasutamisega).
2.  **Avastamine:** Liigutakse võrgus edasi, otsides olulisi faile ja varukoopiaid.
3.  **Krüpteerimine:** Kasutatakse tugevat krüpteerimisalgoritmi (nt. AES-256) andmete lukustamiseks.
4.  **Lunaraha nõue:** Kuvatakse teade (ransom note) maksejuhistega.
5.  **Doxxing/Topelt väljapressimine:** Kui lunaraha maksmisest keeldutakse, ähvardatakse andmed avalikustada (Confidentiality Impact).

#### Kuidas kaitsta?
**Võti on varukoopiad!** Eraldatud (offline, *air-gapped*) varukoopiad muudavad lunaraha maksmise mõttetuks.

---

### Elulised Näited 🌍

#### Eluline Näide 1: Spear Phishing Pääseb Ettevõttesse (Wawa)

* **Ründeliik:** Spear Phishing / Sotsiaalne inseneeria.
* **Juhtum:** Ameerika Ühendriikide jaemüügikett **Wawa** langes rünnaku ohvriks 2019. aastal. Ründajad ei saatnud tavalist mass-phishingut. Selle asemel saatsid nad IT-osakonna töötajale spetsiifilise e-kirja, mis tundus olevat siseinfo uuendus **palgaarvestuse süsteemi** kohta.
* **Miks see töötas?** E-kiri oli hästi koostatud ja suunatud spetsiifilisele rollile. Töötaja klõpsas lingil, mis laadis alla **keylogger'i** (klahvivajutuste salvestaja). See andis ründajatele ligipääsu sisevõrku ja paigaldas lunavara ja makseterminalide andmete varastamise tarkvara.
* **Õppetund:** Isegi spetsialistid võivad langeda hästi sihitud sotsiaalse inseneeria ohvriks. **Kriitilise info** ja **paroolide** kaitseks peab olema **mitmefaktoriline autentimine (MFA/2FA)**.

#### Eluline Näide 2: Suurim DDoS Rünnak Ühiskondliku Teenuse Vastu (Eesti küberrünnakud 2007)

* **Ründeliik:** Mahu- ja protokollipõhised DDoS ründed.
* **Juhtum:** 2007. aastal Eestit tabanud **küberrünnakud**, mis olid suuresti suunatud avaliku sektori, meedia ja pankade veebiteenuste vastu.
* **Miks see töötas?** Rünnakud kasutasid **Botnet’e** (miljonitest nakatunud arvutitest koosnevad võrgud) ning keskendusid erinevatele kihtidele (rakenduse kiht, võrgukiht), et üle koormata nii serverid kui ka võrguühenduse ribalaius. Eestil puudus sel ajal piisav **DDoS-i kaitse infrastruktuur**.
* **Õppetund:** DDoS rünnakud pole vaid "pätitegu", vaid võivad ohustada riigi käideldavust. Tuleb kasutada **geograafiliselt jaotatud puhverservereid (Content Delivery Networks/CDN)** ja DDoS-i leevendamise teenuseid (nt. Cloudflare, Akamai).

---

## 2. Iseseisev Rühmatöö Juhend: Kriisistsenaariumi Analüüs

### Iseseisva töö sisu: A. Detailne uurimine: Phishing, DDoS, Lunavara (3 tundi)

**Eesmärk:** Rakendada loengus ja praktikas omandatud teadmisi, analüüsides konkreetseid küberturvalisuse intsidente.

#### Ülesanne: **Intsidendi uurimisrühm (Incident Response Team)**

Teie rühm on palgatud väikeettevõtte juurde (nt. kohalik e-pood), keda tabas hiljuti üks järgmistest rünnakutest.

#### Tööetapid (3 tundi):

1.  **Stsenaariumi valik (30 min):** Rühm valib ühe kolmest allolevast stsenaariumist (Phishing, DDoS või Lunavara).
2.  **Uurimine ja taust (1 tund):** Kasutades interneti allikaid, otsige valitud ründeliigi kohta:
    * Vähemalt **kaks (2)** hiljutist (viimase 5 aasta) **päriselu juhtumit**.
    * Milliseid **tööriistu/tarkvara** ründajad kasutavad (nt. Lunavara - Conti, Phishing - Gophish, DDoS - Mirai botnet).
3.  **Riskide hindamine (1 tund):**
    * Koostage nimekiri **vähemalt viiest (5)** konkreetsest **kaitsemeetmest** valitud ründe ennetamiseks (sh. tehnilised ja protseduurilised).
    * Kirjeldage samm-sammult, mida tuleks teha **esimese 30 minuti** jooksul pärast rünnaku avastamist (Intsidendi Reageerimise plaan).
4.  **Esitlus ja kokkuvõte (30 min):** Valmistage ette lühike 5-minutiline suuline ülevaade oma leidudest ja soovitustest.

#### Esitatav Tulemus (PowerPoint või dokument):

1.  Ründeliigi lühikirjeldus.
2.  **Juhtumianalüüsid:** Kaks elulist näidet (mis juhtus, miks, tagajärjed).
3.  **Viis kaitsemeedet** (Ennetus).
4.  **Intsidendi reageerimise kava** (Avastamine ja reageerimine).

---

### Näide Iseseisvast Töö Tulemusest: Lunavara Stsenaarium

#### Valitud Stsenaarium: Lunavara (Ransomware) 💰

#### 1. Ründeliigi lühikirjeldus:
Lunavara krüpteerib andmed ja nõuab lunaraha. Täna levinud **topelt-väljapressimine**, kus lisaks krüpteerimisele varastatakse ka andmed ja ähvardatakse need avalikustada.

#### 2. Juhtumianalüüsid:
1.  **Colonial Pipeline (2021):** USA suurim kütusetorujuhtme operaator, keda tabas **DarkSide** lunavara. Rünnak peatas torujuhtme tegevuse, põhjustades USA idarannikul kütusepuuduse. Nad maksid $4.4 miljonit. Rünnak sai alguse arvatavasti **vanast VPN kontost**, millel puudus MFA.
2.  **Kaseya VSA rünnak (2021):** VSA tarkvaraplatvormi kaudu rünnati tuhandeid ettevõtteid üle maailma, kasutades **REvil** lunavara. See näitas **tarneahela rünnaku** (Supply Chain Attack) ohtu.

#### 3. Viis Kaitsemeedet (Ennetus):

1.  **Eraldatud ja testitud varukoopiad (3-2-1 reegel):** Hoidke vähemalt üks varukoopia, mis ei ole võrgu kaudu ligipääsetav (**Air-gapped**).
2.  **Töötajate koolitamine:** Regulaarsed *phishing*-simulatsioonid, et vältida esmast sissetungi.
3.  **Tarkvara ajakohastamine:** Kiire reageerimine tarkvara paikadele (patching), eriti serveritel, VPN-idel ja operatsioonisüsteemidel.
4.  **Kõikidel kontodel (ka VPN) peab olema mitmefaktoriline autentimine (MFA).**
5.  **Võrgu segmenteerimine:** Oluline on hoida tundlikud serverid ja andmed eraldi võrgusegmentides (VLAN-ides), et ründajad ei saaks kergesti edasi liikuda.

#### 4. Intsidendi Reageerimise Kava (Esimesed 30 minutit):

1.  **Eraldada (Isolate):** Kohe eemaldada nakatunud seadmed võrgust (tõmmata kaabel välja!), et vältida lunavara levikut.
2.  **Teavitada:** Teavitada kohe intsidendi reageerimise juhti/meeskonda ja tippjuhtkonda.
3.  **Mitte maksta lunaraha:** Rahvusvaheline parim praktika on mitte maksta (see julgustab ründajaid).
4.  **Dokumenteerida:** Salvestada krüpteerimisteade ja logid, et uurijad saaksid määrata rünnaku sisenemispunkti.
