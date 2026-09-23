Jah. Veebirakenduste puhul soovitan teha eraldi **10 peatükiga osa**, mis ei kordaks lihtsalt operatsioonisüsteemide 20 haavatavust, vaid keskendub sellele, kuidas haavatavused tekivad **HTTP, veebirakenduse, API, andmebaasi, autentimise, sessioonide ja serveripoolse loogika tasandil**.

Allolev ülesehitus lähtub muu hulgas **OWASP Top 10:2025** kategooriatest ning OWASP/PortSwiggeri veebiturbe õppematerjalidest. OWASP 2025 käsitleb näiteks ligipääsukontrolli, turvakonfiguratsiooni, tarneahelat, krüptograafiat, injektsioone ja autentimist; PortSwiggeri õppematerjalides on eraldi käsitletud näiteks SQL injection'it, XSS-i, CSRF-i, XXE-d, SSRF-i, path traversal'it, deserialiseerimist ja failide üleslaadimist. ([OWASP Top 10][1])

# Veebirakenduste haavatavused

Veebirakendus on programm, millele kasutaja pääseb tavaliselt ligi veebibrauseri kaudu. Brauser saadab serverile HTTP- või HTTPS-päringuid ning server töötleb need ja tagastab vastuse. Tänapäevane veebirakendus võib koosneda kasutajaliidesest, veebiserverist, rakendusserverist, andmebaasist, API-dest, autentimisteenusest ja paljudest kolmandate osapoolte komponentidest. Seetõttu võib haavatavus tekkida väga erinevates kohtades.

Veebiturbe puhul on oluline mõista erinevust **autentimise (authentication)** ja **autoriseerimise (authorization)** vahel. Autentimine vastab küsimusele „Kes sa oled?“, autoriseerimine aga küsimusele „Mida sul on lubatud teha?“. Samuti tuleb eristada kliendipoolset ja serveripoolset töötlemist. Brauseris olev JavaScript ei ole turvapiir, sest kasutaja saab brauseri saadetud päringuid muuta.

OWASP Top 10:2025 on veebirakenduste turbe teadlikkuse ja riskide käsitlemise referents, kuid selle kümmet kategooriat ei pea käsitlema kümne kõige sagedamini esineva konkreetse tehnilise haavatavusena. OWASP ise kirjeldab neid kui rakendusturbe oluliste riskide kategooriaid. ([OWASP Top 10][1])

---

# 1. Broken Access Control ehk vigane ligipääsukontroll

Ligipääsukontroll (access control) määrab, milliseid tegevusi kasutaja võib veebirakenduses teha. Näiteks võib tavaline kasutaja vaadata enda profiili, kuid administraator võib muuta kõigi kasutajate andmeid. Kui rakendus ei kontrolli iga tundliku tegevuse juures kasutaja õigusi, võib tekkida Broken Access Control. See tähendab, et kasutaja suudab teha midagi, mida tema roll või konto talle ette nähtud ei ole. Probleem võib tekkida nii veebilehtedel kui ka API-des. Väga tüüpiline viga on eeldada, et kasutaja ei muuda URL-i või HTTP-päringut. Tegelikult saab kasutaja muuta URL-i, päringuparameetreid, küpsiseid, JSON-andmeid ja HTTP-meetodit. Seetõttu peab turvakontroll toimuma serveris. OWASP 2025 käsitleb Broken Access Control'i kategoorias muu hulgas tundliku info avalikustamist, CSRF-i, SSRF-i ja õiguste ületamist. ([GitHub][2])

## Kuidas Broken Access Control tehniliselt tekib?

Oletame, et kasutaja vaatab oma tellimust:

```text
GET /orders/1001
```

Server leiab tellimuse ID 1001 ja tagastab selle.

Probleem tekib juhul, kui server kontrollib ainult seda, kas kasutaja on sisse logitud, kuid ei kontrolli, kas tellimus 1001 kuulub just sellele kasutajale.

```text
Kasutaja
   |
   | GET /orders/1001
   v
Veebiserver
   |
   | kasutaja on autentitud?
   | JAH
   v
Andmebaas
   |
   v
Tellimus 1001
   |
   v
Kasutaja saab andmed
```

Turvalises rakenduses peab kontroll olema:

```text
Kasutaja
   |
   v
Autentimine
   |
   v
Autoriseerimine
   |
   +---- Kas kasutajal on õigus objektile?
              |
        +-----+-----+
        |           |
       JAH          EI
        |           |
        v           v
     Andmed       403
```

### Tüüpilised näited

**IDOR (Insecure Direct Object Reference)** tähendab olukorda, kus kasutaja saab objekti identifikaatorit muutes ligi teise kasutaja objektile.

Näiteks:

```text
/minu-arve/125
```

muudetakse:

```text
/minu-arve/126
```

Kui server tagastab arve 126 ilma omandiõigust kontrollimata, on tegemist ligipääsukontrolli veaga.

Teine probleem on **force browsing**, mille korral kasutaja proovib otse avada administraatorile mõeldud URL-i.

```text
/user/dashboard
       |
       v
/admin/dashboard
```

Kui server kaitseb administraatori linki ainult kasutajaliidese tasandil, ei ole kaitse piisav.

## Mida ründaja pärast haavatavuse kasutamist teha võib?

Ründaja võib saada ligi teiste kasutajate andmetele. Ta võib muuta teise kasutaja profiili, tellimust või seadistusi. Kui haavatavus võimaldab administraatori funktsioonide kasutamist, võib ründaja saada süsteemis palju suuremad õigused.

API-de puhul võib probleem olla eriti suur, sest sama API võib võimaldada andmete lugemist, muutmist ja kustutamist.

Ligipääsukontrolli viga võib seetõttu mõjutada korraga konfidentsiaalsust, terviklust ja käideldavust.

## Kuidas riski vähendada?

Iga kaitset vajav tegevus tuleb autoriseerida serveris. Kasutada tuleb vaikimisi keelavat mudelit ehk **deny by default**. Kasutaja ID või roll ei tohi olla ainus turvakontroll. API peab kontrollima õigusi iga tundliku GET-, POST-, PUT- ja DELETE-operatsiooni puhul. Testida tuleb ka olukorda, kus kasutaja muudab URL-i või JSON-päringu väärtusi. Administraatori funktsioonid tuleb kaitsta eraldi rollipõhise ligipääsukontrolliga. OWASP soovitab rakendada vähimate õiguste põhimõtet. ([GitHub][2])

---

# 2. Security Misconfiguration ehk ebaturvaline turvakonfiguratsioon

Turvakonfiguratsiooni viga tähendab, et veebiserver, rakendus, andmebaas, pilveteenus või muu komponent on seadistatud viisil, mis loob tarbetu turvariski. See võib olla vaikimisi parool, lubatud debug-režiim, liiga avatud CORS, ebavajalikud teenused või detailsete veateadete kuvamine. OWASP 2025 kirjeldab Security Misconfiguration'i eraldi riskikategooriana ja märgib, et konfiguratsiooniprobleemid on väga laialt levinud. ([OWASP Top 10][3])

## Kuidas see tehniliselt tekib?

Veebirakendus võib arenduskeskkonnas olla seadistatud näiteks:

```text
DEBUG = TRUE
```

Arenduses võib see olla mugav, kuid tootmiskeskkonnas võib kasutajale kuvada näiteks:

```text
Database connection failed
Server: DB01
Database: production
User: webapp
Path: /var/www/application/
Stack trace: ...
```

Selline info võib aidata ründajal süsteemi tundma õppida.

```text
Kasutaja
   |
   v
Veebirakendus
   |
   +---- DEBUG
   +---- vaikimisi konto
   +---- avatud haldusliides
   +---- ebavajalikud pordid
   +---- liigsed õigused
             |
             v
       Suurem ründepind
```

## Mida ründaja teha võib?

Ründaja võib esmalt koguda süsteemi kohta infot. Seejärel võib ta kasutada avalikustatud haldusliidest, vaikimisi kontot või muud valesti seadistatud komponenti.

Näiteks võib avalikult kättesaadav diagnostikafail sisaldada andmebaasiühenduse infot. Kui sama konto on kasutatav ka tootmiskeskkonnas, võib probleem areneda otseseks kompromiteerimiseks.

## Kuidas riski vähendada?

Tootmiskeskkonnas tuleb debug-režiim välja lülitada. Vaikimisi kontod ja paroolid tuleb muuta või eemaldada. Mittevajalikud teenused ja moodulid tuleb eemaldada. Haldusliidesed tuleb piirata ainult haldusvõrgule. HTTP-turvapäised tuleb õigesti seadistada. Server peab kuvama kasutajale üldise veateate, kuid kirjutama detailid turvalisse serverilogisse. Konfiguratsiooni tuleks hallata standardiseeritud hardening-protsessi abil. OWASP soovitab minimaalse platvormi ja korduvat turvalist konfiguratsiooniprotsessi. ([OWASP Top 10][3])

---

# 3. Injection ehk injektsioon

Injektsioon tekib siis, kui kasutaja kontrollitav sisend jõuab käsu või päringu osaks viisil, mida rakendus ei ole õigesti eraldanud. See võib puudutada SQL-i, operatsioonisüsteemi käske, NoSQL-i, LDAP-i, XML-i või muid tõlgendatavaid keeli. Veebirakendus ei tohiks käsitleda kasutaja sisendit automaatselt usaldusväärse käsuna. OWASP 2025 paigutab Injection'i eraldi A05 kategooriasse ning selle alla kuuluvad mitmesugused injektsioonirünnakud. ([OWASP Top 10][1])

## Kuidas injektsioon tekib?

Oletame, et rakendus ehitab SQL-päringu otse kasutaja sisendist.

```text
Kasutaja sisend
       |
       v
Rakendus
       |
       v
SQL-päring
       |
       v
Andmebaas
```

Turvaline rakendus peab andmed ja käsu eraldama:

```text
SQL-käsk
   +
parameeter
   |
   v
Andmebaas
```

Kõige tuntum näide on **SQL Injection**.

Lisaks võib esineda:

* SQL Injection
* NoSQL Injection
* OS Command Injection
* LDAP Injection
* XPath Injection

## Mida ründaja teha võib?

SQL Injection võib anda võimaluse lugeda või muuta andmebaasiandmeid. Mõnes keskkonnas võib mõju ulatuda andmebaasist rakendusserverini.

OS Command Injection on eriti ohtlik, sest veebirakendus võib sattuda olukorda, kus kasutaja sisend muutub serveris käsuks.

## Kuidas riski vähendada?

SQL-i puhul tuleb kasutada parametriseeritud päringuid ehk prepared statements. Kasutaja sisendit tuleb valideerida. OS-käskude käivitamist tuleks võimalusel vältida. Kui käskude käivitamine on vältimatu, tuleb kasutada turvalisi API-sid ja lubatud väärtuste nimekirja. Rakenduse andmebaasikontol peab olema ainult vajalikud õigused. Lisaks tuleb kasutada väljundkodeerimist ja sisendi valideerimist vastavalt konkreetsele kontekstile.

PortSwiggeri Web Security Academy käsitleb eraldi SQL Injection'i, OS Command Injection'it, NoSQL Injection'it ja mitmeid teisi injektsioonivorme. ([PortSwigger][4])

---

# 4. Cross-Site Scripting ehk XSS ehk ristse skriptimine

Cross-Site Scripting ehk **XSS** tekib siis, kui veebirakendus lisab kasutaja kontrollitava sisu veebilehele nii, et brauser käsitleb seda aktiivse sisuna. Selle tulemusena võib pahatahtlik JavaScript töötada ohvri brauseris veebisaidi kontekstis. XSS ei tähenda tingimata serveri kompromiteerimist. Rünnak toimub sageli kasutaja brauseris.

XSS-il on kolm peamist vormi: **reflected XSS**, **stored XSS** ja **DOM-based XSS**. PortSwigger käsitleb neid eraldi ning pakub nende õppimiseks praktilisi laboratooriume. ([PortSwigger][5])

## Kuidas XSS tekib?

```text
Ründaja sisend
      |
      v
Veebirakendus
      |
      v
HTML vastus
      |
      v
Ohvri brauser
      |
      v
JavaScript käivitub
```

Stored XSS-i puhul salvestab server kasutaja sisendi näiteks kommentaari andmebaasi.

```text
Ründaja
   |
   v
Kommentaar
   |
   v
Andmebaas
   |
   v
Ohvri brauser
```

## Mida ründaja teha võib?

XSS võib muuta veebilehe sisu, suunata kasutaja teisele lehele või teha kasutaja brauseris päringuid selle veebisaidi nimel. Samuti võib XSS mõjutada sessiooni ja kasutaja tegevusi, sõltuvalt rakenduse küpsiste ja muude kaitsete seadistusest.

XSS on eriti ohtlik administraatoriliidestes. Kui administraator avab pahatahtlikku sisu sisaldava lehe, võib rünnak toimuda tema õigustega veebirakenduse kontekstis.

## Kuidas riski vähendada?

Kõige olulisem kaitse on kontekstipõhine väljundkodeerimine. HTML-i, JavaScripti, CSS-i ja URL-i kontekstides kasutatakse erinevaid kodeerimisreegleid. Kasutada tuleb turvalisi DOM API-sid. `innerHTML` kasutamist kasutaja kontrollitava sisendiga tuleb vältida või rangelt kontrollida. Content Security Policy ehk CSP võib vähendada XSS-i mõju. Küpsised tuleb võimalusel seadistada `HttpOnly`, `Secure` ja sobiva `SameSite` atribuudiga. OWASP ja PortSwigger käsitlevad XSS-i eraldi põhjaliku veebiturbe teemana. ([PortSwigger][5])

---

# 5. SQL Injection ehk SQL-injektsioon

SQL Injection on injektsiooni erijuht, kus kasutaja kontrollitav sisend mõjutab SQL-päringut. SQL (Structured Query Language) on keel, millega rakendus suhtleb relatsioonilise andmebaasiga. Kui rakendus ühendab kasutaja sisendi ja SQL-käsu üheks stringiks, võib kasutaja mõjutada päringu tähendust.

## Kuidas SQL Injection tehniliselt tekib?

Ebaturvaline loogika:

```text
kasutaja sisend
      |
      v
stringi ühendamine
      |
      v
SQL-päring
      |
      v
andmebaas
```

Turvaline loogika:

```text
SQL-lause
   +
parameeter
   |
   v
Prepared Statement
   |
   v
Andmebaas
```

Prepared statement tähendab, et SQL-i struktuur ja kasutaja antud väärtus käsitletakse eraldi.

## Mida ründaja teha võib?

Sõltuvalt rakenduse õigustest võib SQL Injection võimaldada:

* andmete lugemist;
* andmete muutmist;
* andmete kustutamist;
* tabelite struktuuri mõjutamist;
* autentimisloogika mõjutamist;
* mõnel juhul liikumist edasi rakendusserveri kompromiteerimiseni.

Kõik need tagajärjed ei ole iga SQL Injection'i puhul võimalikud. Mõju sõltub andmebaasimootorist, rakenduse õigustest ja haavatavuse täpsest olemusest.

## Kuidas riski vähendada?

Kasutada tuleb parametriseeritud päringuid. Andmebaasikontole tuleb anda ainult vajalikud õigused. Rakendus ei peaks töötama andmebaasi administraatori kontoga. Sisendit tuleb valideerida, kuid ainult sisendi valideerimine ei asenda parametriseeritud päringuid. Veateated ei tohiks kasutajale avaldada SQL-i struktuuri. Andmebaasi tegevusi tuleb logida ja jälgida. PortSwigger käsitleb SQL Injection'it ühe põhilise veebirakenduse turvateemana. ([PortSwigger][6])

---

# 6. Cross-Site Request Forgery ehk CSRF ehk ristpäringu võltsimine

CSRF tähendab olukorda, kus ohvri brauser saadetakse tegema veebirakenduses tegevust, mida kasutaja ise ei kavatsenud teha. Rünnak kasutab ära asjaolu, et brauser saadab teatud autentimisandmed, näiteks küpsised, automaatselt kaasa.

CSRF erineb XSS-ist. XSS tähendab pahatahtliku skripti käivitamist ohvri brauseris, CSRF aga kasutab ohvri olemasolevat autentitud sessiooni soovimatu tegevuse tegemiseks.

## Kuidas CSRF tekib?

```text
Ohver
  |
  | autentitud sessioon
  v
Panga / rakenduse server
  ^
  |
  | võltsitud päring
  |
Ründaja veebileht
```

Näiteks:

```text
Ohver logib sisse
       |
       v
Sessiooniküpsis
       |
       v
Ohver avab teise veebilehe
       |
       v
Brauser saadab soovimatu päringu
       |
       v
Server peab päringut ohvri päringuks
```

## Mida ründaja teha võib?

Mõju sõltub sellest, milliseid tegevusi saab autentitud kasutaja teha. Näiteks võib probleem puudutada konto seadistuste muutmist, e-posti aadressi muutmist või muid olekut muutvaid tegevusi.

CSRF on eriti oluline rakendustes, kus autentimine põhineb automaatselt kaasa saadetaval küpsisel.

## Kuidas riski vähendada?

Kasutada tuleb CSRF-tokensüsteemi, mille väärtust ründaja ei saa lihtsalt ennustada. Küpsistele tuleb seadistada sobiv `SameSite` väärtus. Oluliste tegevuste puhul võib kasutada täiendavat kinnitust või uuesti autentimist. GET-päringuid ei tohiks kasutada olekut muutvate tegevuste jaoks. Lisaks tuleb kontrollida päritolu (`Origin`) või vajadusel `Referer` päist. PortSwigger käsitleb CSRF-i ja selle kaitseid eraldi õppematerjalides. ([PortSwigger][5])

---

# 7. Path Traversal ehk teekonna läbimine

Path Traversal tekib siis, kui kasutaja saab mõjutada serveris kasutatavat failiteed ning rakendus ei kontrolli, kas saadud tee jääb lubatud kataloogi piiridesse. Probleemi seostatakse sageli sümbolitega `../`, kuid turvalisuse seisukohalt ei piisa ainult nende märkide blokeerimisest.

## Kuidas Path Traversal tekib?

Oletame, et rakendus kasutab:

```text
/download?file=manual.pdf
```

Server võib moodustada:

```text
/var/www/files/manual.pdf
```

Kui `file` väärtust ei kontrollita, võib kasutaja proovida liikuda lubatud kataloogist väljapoole.

```text
/var/www/files/
       |
       +-- manual.pdf
       |
       +-- image.png

        ↓

lubatud kataloogist välja
        |
        v
muud serveri failid
```

## Mida ründaja teha võib?

Ründaja võib saada ligi failidele, mida veebirakendus ei peaks talle näitama. Need võivad sisaldada konfiguratsiooni, lähtekoodi, logisid või muid tundlikke andmeid.

Kui rakendus lubab ka kirjutamist, võib probleem muutuda veel tõsisemaks.

## Kuidas riski vähendada?

Failinimede asemel võib kasutada sisemisi identifikaatoreid. Server peaks kontrollima normaliseeritud absoluutset failiteed ja veenduma, et see jääb lubatud kataloogi. Kasutajale ei tohiks anda otsest kontrolli serveri failisüsteemi tee üle. Rakendusprotsess peab töötama vähimate õigustega. PortSwigger sisaldab Path Traversal'i eraldi veebirakenduse haavatavusena. ([PortSwigger][4])

---

# 8. SSRF ehk Server-Side Request Forgery

SSRF tähendab, et ründaja suudab panna veebiserveri tegema võrgupäringu sihtkohta, mida ründaja ise otse kätte ei saa. See on eriti oluline pilvekeskkondades ja sisevõrkudes.

## Kuidas SSRF tekib?

Oletame, et rakendus võimaldab URL-i kaudu pilti laadida:

```text
/load-image?url=https://example.com/image.jpg
```

Server teeb ise päringu:

```text
Kasutaja
   |
   v
Veebirakendus
   |
   | HTTP request
   v
Internet / sisevõrk
```

Kui server ei piira sihtkohta, võib tekkida olukord, kus server hakkab suhtlema sisemiste teenustega.

```text
Internet
   |
   v
Veebiserver
   |
   +---- avalik veeb
   |
   +---- sisevõrk
   |
   +---- haldusliides
   |
   +---- pilve metadata teenus
```

OWASP Top 10:2025 paigutab SSRF-i Broken Access Control'i alla, erinevalt 2021. aasta eraldi SSRF kategooriast. ([OWASP Top 10][1])

## Mida ründaja teha võib?

SSRF võib võimaldada sisevõrgu teenuste avastamist. Mõnel juhul võib ründaja saada ligi serveri enda jaoks kättesaadavatele haldusliidestele või pilve metadata teenustele.

Oluline on mõista, et SSRF ei tähenda automaatselt sisevõrgu täielikku kompromiteerimist. Mõju sõltub sellest, kuhu server pääseb ja milliseid autentimis- või võrgukontrolle sihtteenus kasutab.

## Kuidas riski vähendada?

URL-i sihtkohad tuleb piirata lubatud domeenide või sihtkohtade nimekirjaga. Rakendus ei tohiks lubada päringuid suvalistele privaatvõrgu aadressidele. DNS-i ja IP-aadresside kontroll peab olema läbimõeldud, sest ainult tekstilise URL-i kontrollimisest ei piisa. Pilvekeskkondades tuleb metadata teenused eraldi kaitsta. Võrgusegmentatsioon vähendab SSRF-i mõju. PortSwigger käsitleb SSRF-i eraldi veebiturbe teemana. ([PortSwigger][4])

---

# 9. XXE ehk XML External Entity Injection

XXE tekib XML-i töötlemisel, kui parser lubab XML External Entity funktsioone ning rakendus ei piira, milliseid väliseid ressursse XML võib viidata. XML on andmevorming, mida kasutatakse muu hulgas rakenduste ja süsteemide vaheliseks andmevahetuseks.

## Kuidas XXE tekib?

```text
Kasutaja XML
     |
     v
XML parser
     |
     | välise ressursi lubamine
     v
Fail / URL / sisevõrk
     |
     v
Tundlik info
```

Probleem on selles, et XML-dokument võib kirjeldada väliseid entiteete.

Turvaline XML-parser peaks välised entiteedid vajadusel täielikult keelama.

## Mida ründaja teha võib?

XXE võib sõltuvalt parseri konfiguratsioonist põhjustada serveri kohalike failide lugemist. Samuti võib see olla seotud SSRF-iga, kui XML-parser suudab teha väliseid võrgupäringuid.

Seetõttu võib üks XML-parseri viga ühendada mitu turvariski.

## Kuidas riski vähendada?

Välised XML-entiteedid tuleb keelata, kui rakendus neid ei vaja. XML-parser tuleb seadistada turvalises režiimis. Kasutada tuleb ajakohast parseriteeki. XML-sisendi suurusele ja keerukusele tuleb seada piirangud. Vajadusel tuleks kasutada alternatiivset andmevormingut, näiteks JSON-i. OWASP 2025 Security Misconfiguration kategoorias on XXE-ga seotud CWE-611 eraldi välja toodud. ([OWASP Top 10][3])

---

# 10. Insecure Deserialization ehk ebaturvaline deserialiseerimine

Serialiseerimine (serialization) tähendab objekti või andmestruktuuri muutmist vormingusse, mida saab salvestada või edastada. Deserialiseerimine (deserialization) tähendab selle andmevormingu muutmist tagasi objektiks või andmestruktuuriks.

Probleem tekib siis, kui rakendus usaldab deserialiseeritud andmeid liiga palju. Mõnes programmeerimiskeeles võib deserialiseerimine põhjustada isegi meetodite või objektide automaatset loomist ja töötlemist.

## Kuidas see tehniliselt tekib?

```text
Objekt
  |
  | serialization
  v
Andmevorm
  |
  | kasutaja kontrollib
  v
Muudetud andmed
  |
  | deserialization
  v
Objekt
  |
  v
Rakenduse loogika
```

Kui deserialiseerija usaldab täielikult kasutaja antud objekti tüüpi või omadusi, võib ründaja mõjutada rakenduse käitumist.

## Mida ründaja teha võib?

Sõltuvalt programmeerimiskeelest ja kasutatavast teegist võib probleem põhjustada:

* autentimise või autoriseerimise möödumist;
* andmete muutmist;
* serveri protsessi mõjutamist;
* koodi käivitamist;
* teenuse töö katkemist.

Mõju sõltub väga tugevalt sellest, millist serialiseerimisformaati kasutatakse ja millised klassid või objektid on rakendusele kättesaadavad.

## Kuidas riski vähendada?

Vältida tuleks kasutaja kontrollitavate andmete otsest deserialiseerimist usaldusväärseteks objektideks. Võimalusel kasutada lihtsaid andmevorme, näiteks JSON-i, koos range skeemivalideerimisega. Lubatud objektitüübid tuleb piirata. Digitaalallkiri võib teatud arhitektuurides aidata kontrollida andmete päritolu ja terviklust, kuid seda ei tohi pidada ainsaks kaitseks. Deserialiseerimise teegid tuleb hoida ajakohasena. Rakendus peab töötama vähimate vajalike õigustega.

PortSwiggeri Web Security Academy käsitleb ebaturvalist deserialiseerimist eraldi veebirakenduste haavatavusena ning seostab selle võimalike serveripoolsete tagajärgedega. ([PortSwigger][4])

---

# Kokkuvõte: kuidas veebirakenduste haavatavused omavahel seostuvad?

Veebirakenduse turvalisust ei saa hinnata ainult ühe haavatavuse järgi. Üks viga võib olla ründeahela alguspunkt ning teine viga võimaldab ründajal oma mõju suurendada.

Näiteks võib ründeahel olla:

```text
                    INTERNET
                       |
                       v
              +----------------+
              | Veebirakendus  |
              +----------------+
                       |
             +---------+---------+
             |                   |
             v                   v
       SQL Injection             XSS
             |                   |
             v                   v
        Andmebaas             Brauser
             |                   |
             +---------+---------+
                       |
                       v
              Sessiooni / andmete
                  kompromiss
                       |
                       v
             Broken Access Control
                       |
                       v
                Privilege /
                funktsioonide
                   suurenemine
                       |
                       v
                 Andmete vargus
```

Oluline on eristada:

**Haavatavus (vulnerability)** – tarkvara või süsteemi turvanõrkus.

**Exploit** – viis või vahend, millega konkreetset haavatavust ära kasutatakse.

**Rünnak (attack)** – tegevus, mille käigus ründaja püüab süsteemi mõjutada.

**Kompromiteerimine (compromise)** – olukord, kus ründaja on saavutanud soovimatu ligipääsu või kontrolli.

Veebirakenduse kaitsmisel tuleb seetõttu rakendada kaitsekihte:

```text
              KASUTAJA
                  |
                  v
          HTTPS / TLS
                  |
                  v
        WAF / võrgu kaitse
                  |
                  v
        Autentimine (AuthN)
                  |
                  v
        Autoriseerimine (AuthZ)
                  |
                  v
       Sisendi valideerimine
                  |
                  v
      Turvaline rakendusloogika
                  |
                  v
       Turvaline andmebaas
                  |
                  v
        Logimine + monitooring
                  |
                  v
         Varundus + taastamine
```

Kõige olulisem põhimõte on, et **kliendi kontrolli all olevat infot ei tohi automaatselt usaldada**. URL, vorm, JSON, küpsis, HTTP-päis ja JavaScripti kaudu saadetud väärtus võivad kõik olla kasutaja muudetavad.

Veebirakenduse turvalisus koosneb seega mitmest kihist:

| Haavatavus                | Põhiprobleem                          | Võimalik mõju                              |
| ------------------------- | ------------------------------------- | ------------------------------------------ |
| Broken Access Control     | kasutaja saab teha liiga palju        | andmete muutmine/vargus                    |
| Security Misconfiguration | süsteem on valesti seadistatud        | ründepinna suurenemine                     |
| Injection                 | sisend muutub käsuks                  | andmebaasi/serveri kompromiss              |
| XSS                       | sisend muutub aktiivseks veebisisuks  | kasutaja sessiooni ja tegevuste mõjutamine |
| SQL Injection             | sisend mõjutab SQL-i                  | andmebaasi kompromiss                      |
| CSRF                      | brauser teeb soovimatu tegevuse       | kasutaja konto muutmine                    |
| Path Traversal            | failitee kontroll puudub              | failide lugemine/muutmine                  |
| SSRF                      | server teeb ründaja määratud päringu  | sisevõrgu/teenuste ligipääs                |
| XXE                       | XML parser töötleb väliseid ressursse | failileke/SSRF                             |
| Insecure Deserialization  | ebaturvalised objektid taastatakse    | rakenduse kompromiss                       |

OWASP Top 10:2025 sisaldab neist teemadest mitut suurema kategooria sees: näiteks Broken Access Control, Security Misconfiguration, Software Supply Chain Failures, Cryptographic Failures, Injection ja Authentication Failures. OWASP rõhutab ka, et kategooriad võivad osaliselt kattuda ning eesmärk on võimaluse korral kirjeldada probleemi algpõhjust, mitte ainult selle tagajärge. ([OWASP Top 10][1])

Seetõttu on veebiturbe õppimisel kasulik vaadata iga haavatavust kolme küsimuse kaudu:

1. **Mida rakendus usaldab, mida ta ei tohiks usaldada?**
2. **Millist turvakontrolli saab ründaja mõjutada või mööda minna?**
3. **Mida saab ründaja pärast selle kontrolli ületamist teha?**

Kui nendele kolmele küsimusele osatakse vastata, on võimalik mõista ka keerukamaid ründeahelaid, kus mitu näiliselt väikest viga koos põhjustavad suure turvaintsidendi.

See 10 peatükki moodustavad nüüd veebirakenduste osa, mis sobib sisuliselt jätkuks sinu **20 operatsioonisüsteemi haavatavuse peatükile**. OWASP 2025 järgi on veebiturbe käsitlus laiem kui ainult need kümme tehnilist näidet, kuid need teemad annavad õppimiseks hea silla konkreetsete veebirakenduse vigade ja suuremate OWASP riskikategooriate vahel. ([OWASP Top 10][1])

[1]: https://top10.owasp.org/2025/0x00_2025-Introduction/?utm_source=chatgpt.com "Introduction - OWASP Top 10:2025"
[2]: https://github.com/OWASP/Top10/blob/master/2025/docs/en/A01_2025-Broken_Access_Control.md?utm_source=chatgpt.com "Top10/2025/docs/en/A01_2025-Broken_Access_Control.md at master · OWASP/Top10 · GitHub"
[3]: https://top10.owasp.org/2025/A02_2025-Security_Misconfiguration/?utm_source=chatgpt.com "A02 Security Misconfiguration - OWASP Top 10:2025"
[4]: https://portswigger.net/web-security/all-labs?utm_source=chatgpt.com "All labs | Web Security Academy"
[5]: https://portswigger.net/web-security/all-materials?utm_source=chatgpt.com "All learning materials | Web Security Academy"
[6]: https://portswigger.net/web-security?utm_source=chatgpt.com "Web Security Academy: Free Online Training from PortSwigger"
