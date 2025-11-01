-----

# 📚 PKI (Avaliku Võtme Taristu) – Põhjalik Õppematerjal Terminoloogia Selgitustega

## 1\. tund: Sissejuhatus PKI-sse ja Sertifikaadi Anatoomia

### 🎯 Eesmärgid

  * Mõista PKI **põhiülesannet** usalduse loomisel.
  * Õppida tundma **asümmeetrilise krüptograafia** matemaatilist alust.
  * Analüüsida X.509 sertifikaadi **kohustuslikke komponente**.

-----

### 1\. PKI (Avaliku Võtme Taristu) Alused


#### Asümmeetriline Krüptograafia: Põhiolemus

**Asümmeetriline krüptograafia** (tuntud ka kui **avaliku võtme krüptograafia**) lahendab kaks peamist probleemi: **võtmete jagamine** ja **digitaalallkirjastamine**.

1.  **Võtmepaar:** Erinevalt sümmeetrilisest krüptograafiast (mis kasutab ühte võtit nii krüpteerimiseks kui dekrüpteerimiseks), luuakse siin matemaatiliselt seotud **võtmepaar**:
      * **Avalik võti (Public Key):** Mõeldud laialdaseks jagamiseks. Seda saab kasutada **andmete krüpteerimiseks** (saaja saab lugeda ainult privaatvõtmega) või **digitaalallkirja kontrollimiseks**.
      * **Privaatvõti (Private Key):** Tuleb hoida **absoluutses saladuses**. Seda saab kasutada ainult **andmete dekrüpteerimiseks** või **digitaalallkirjade loomiseks**.
2.  **Põhiomadus:** See, mis on krüpteeritud avaliku võtmega, saab dekrüpteerida *ainult* vastava privaatvõtmega – ja vastupidi. See suhe on **matemaatiliselt pöördumatu**.

#### PKI: Põhiolemus

Avaliku võtme taristu (Public Key Infrastructure, PKI) on raamistik, mis võimaldab turvalist autentimist (authentication), andmete terviklikkuse (integrity) ja konfidentsiaalsuse (confidentiality) tagamist läbi avaliku/privaatvõtme (public/private key) krüptograafia ning digitaalsertifikaatide (digital certificates) halduse. PKI võimaldab usaldusvõrgustikku, kus osapooled (subscribers), usaldusosapooled (relying parties) ja sertifikaadiasutused (Certificate Authorities, CA) suhtlevad ja usaldavad üksteise väljastatud sertifikaate.

PKI peamine eesmärk on lahendada **võtme autentsuse probleem**. Kuidas ma tean, et avalik võti, mille ma sain, kuulub tõepoolest isikule/serverile, keda ta väidab end olevat? PKI standardiseerib protsessi, luues kolme osapoole vahelise **usalduse ahela**: **Saaja** usaldab **CA-d**, et **CA** on kontrollinud **Avaliku Võtme Omaniku** identiteeti.

#### Põhikomponendid (Core components)

1. **Sertifikaadi väljastaja (Certificate Authority, CA)**

   * CA on usaldusväärne osapool, kes väljastab, uuendab ja tühistab digitaalseid sertifikaate.
   * Root CA (juur-CA) on kõige usaldusväärsem otsus: tema avalikku võtit (public key) kasutatakse usalduse alustalaks (trust anchor).
   * Vahe-CA (intermediate CA) vähendab juur-CA ekspositsiooni, sest juur-CA hoitakse tavaliselt väga ohutult (offline, HSM).

2. **Registreerimisamet (Registration Authority, RA)**

   * RA kontrollib taotleja (subject) identiteeti enne sertifikaadi väljastamist; RA ja CA võivad mõnikord olla samas organisatsioonis, aga rollid eristatakse.

3. **Sertifikaat (Certificate, X.509 certificate)**

   * Tavaliselt X.509 formaat: sisaldab sertifikaadi omaniku (subject) identiteeti, avalikku võtit, väljastaja (issuer) identiteeti, kehtivusaegu (validity), sertifikaadi laiendusi (extensions) ja sertifikaadi väljastaja digitaalallkirja (signature).

4. **Võtmepar (Key pair)**

   * Privaatvõti (private key): hoitakse saladuses; seda kasutatakse allkirjastamiseks (signing) ja dekodeerimiseks (decryption, kui kasutatakse RSA).
   * Avalik võti (public key): jagatakse teistega; seda kasutatakse allkirja kontrollimiseks (verification) ja krüpteeritud sõnumi saatmiseks.

5. **Tühistusinfo (Revocation information)**

   * CRL (Certificate Revocation List) — perioodiliselt avaldatav tühistusnimekiri.
   * OCSP (Online Certificate Status Protocol) — reaalajas päringute teenus sertifikaadi staatuse kontrollimiseks.

6. **Sertifikaadi kasutusreeglid, poliitikad ja CPS (Certificate Policy, CP; Certification Practice Statement, CPS)**

   * CP kirjeldab, milleks sertifikaat loodud on ning millised nõuded kehtivad.
   * CPS on dokument, mis kirjeldab CA käitumist ja protseduure (kuidas CA kontrollib identiteeti, hoiab võtmeid, tühistab sertifikaate jms).

7. **Usaldusankur (Trust anchor)**

   * Tavaliselt root CA sertifikaat, mis on eelinstalleeritud brauseritesse, operatsioonisüsteemidesse või organisatsiooni kergetuspunktidesse.

8. **Turvahaldus (Key management / HSM)**

   * Privaatvõtmete turvaline ladustamine HSM-ides (Hardware Security Module) ning võtmete elutsükli haldus — genereerimine, varundamine, taastamine, arhiveerimine ja hävitamine.

<img alight=center width="384" height="256" alt="Illustration of Publ" src="https://github.com/user-attachments/assets/a9be954b-6d84-4cab-8b9f-637b7d915695" />

  * **Identiteet:** Keda sertifikaat esindab (inimene, domeen, seade).
  * **CA (Sertifitseerimisasutus):** PKI ökosüsteemi **notar**. CA kontrollib taotleja identiteeti ja **allkirjastab** taotleja avaliku võtme, luues **sertifikaadi**. CA-d usaldavad kõik brauserid ja operatsioonisüsteemid vaikimisi.
  * **RA (Registreerimisasutus):** CA-d abistav üksus, mis viib läbi **esialgse identiteedikontrolli** (nt. juriidiliste dokumentide kontroll) ja saadab seejärel taotluse CA-le allkirjastamiseks.

| Kontseptsioon | Päriselu Näide 1 | Päriselu Näide 2 |
| :--- | :--- | :--- |
| **Asümmeetriline Krüptograafia** | **Turvaline Kiri:** Kujutage ette luku ja kahe võtmega seifi. Avalik võti on lukk, mille te saadate sõbrale. Igaüks saab saata krüpteeritud sõnumi seifi (lukuga krüpteerida), kuid avada saab selle **ainult teie** (oma privaatvõtmega dekrüpteerida). | **Ajakohased Sõnumirakendused (Signal, WhatsApp):** Sõnumid on **otsast lõpuni krüpteeritud (end-to-end encryption)**. Rakendus genereerib igale kasutajale võtmepaari. Teie telefon krüpteerib sõnumid vastuvõtja avaliku võtmega, mida saab dekrüpteerida ainult vastuvõtja privaatvõtmega. |
| **CA / RA roll** | **Digi-ID või ID-kaart:** Eestis toimib **PPA** (Politsei- ja Piirivalveamet) Registreerimisasutusena (RA), kes kontrollib teie identiteeti ja annab välja kaardi. **Sertifitseerimisteenuse pakkuja** (nt. SK ID Solutions) toimib CA-na, kes allkirjastab sertifikaadi ja loob sellega usalduse. | **AWS või Azure'i Sertifikaatide Haldus:** Suur pilveteenuse pakkuja võib luua oma sise-PKI. Nad kasutavad automatiseeritud teenuseid (mis toimivad RA-na), et kontrollida, kas server taotleja **tõepoolest kuulub** ettevõttele, ja seejärel lasevad oma CA-l sertifikaadi allkirjastada. |

-----

### 2\. Sertifikaadid (X.509 standard)

#### X.509 Sertifikaat: Põhiolemus
Digitaalsertifikaatide X.509 standard on Rahvusvahelise Telekommunikatsiooni Liidu (ITU) laialdaselt tunnustatud standard, mis määratleb avaliku võtme infrastruktuuri (PKI) sertifikaatide formaadi.

Need sertifikaadid on sisuliselt digitaalsed passi või isikutunnistused, mida kasutatakse veebis tegutsevate üksuste (nagu veebisaidid, kasutajad, seadmed või organisatsioonid) autentsuse ja identiteedi kinnitamiseks.

**X.509** on **digitaalne dokument** või **standardiseeritud formaat** (sarnane füüsilisele passile), mis **seob avaliku võtme identiteediga**. See standard on kriitiline, sest see võimaldab igal brauseril, operatsioonisüsteemil ja rakendusel sertifikaati **ühtemoodi töödelda ja valideerida**.


Digitaalsertifikaatide **X.509 standard** on **Rahvusvahelise Telekommunikatsiooni Liidu (ITU)** laialdaselt tunnustatud standard, mis määratleb **avaliku võtme infrastruktuuri (PKI)** sertifikaatide formaadi.

Need sertifikaadid on sisuliselt **digitaalsed passi või isikutunnistused**, mida kasutatakse veebis tegutsevate üksuste (nagu veebisaidid, kasutajad, seadmed või organisatsioonid) **autentsuse ja identiteedi kinnitamiseks**.

---

## 🔑 Põhielemendid ja tööpõhimõte

X.509 sertifikaat seob digitaalallkirja abil **avaliku võtme** kindla **identiteediga**.

* **Sertifikaadi Sisu:** Sertifikaat sisaldab olulist teavet:
    * **Subjekti info:** Identiteedi andmed (nt domeeninimi, organisatsioon, isikunimi).
    * **Avalik võti:** Krüptograafiline võti, mida kasutatakse krüpteerimiseks ja digitaalallkirjade kontrollimiseks.
    * **Väljaandja info:** Sertifikaadi välja andnud **sertifitseerimisasutuse (CA)** nimi.
    * **Kehtivusaeg:** Sertifikaadi algus- ja lõppkuupäevad.
    * **Digitaalallkiri:** CA allkiri, mis kinnitab sertifikaadi andmete ehtsust.

* **Tööpõhimõte:**
    1.  **Väljaandmine:** Sertifitseerimisasutus (CA) kontrollib identiteeti ja annab välja digitaalselt allkirjastatud X.509 sertifikaadi.
    2.  **Kontrollimine:** Kui brauser (klient) saab sertifikaadi, kontrollib ta CA digitaalallkirja, et veenduda sertifikaadi usaldusväärsuses ja andmete muutmatuses. See luuakse tavaliselt usaldusahela kaudu.
    3.  **Turvaline side:** Pärast edukat kontrolli saab sertifikaadis sisalduvat avalikku võtit kasutada **turvalise (krüpteeritud) side** loomiseks (nt TLS/SSL puhul).

---

## 🌐 X.509 Kasutusvaldkonnad

X.509 sertifikaadid on **PKI nurgakivi** ja neid kasutatakse laialdaselt:

* **Turvalised veebisaidid (HTTPS):** **SSL/TLS sertifikaadid** (mis on X.509 tüüpi) krüpteerivad andmeedastuse veebibrauserite ja serverite vahel.
* **Meiliturve (S/MIME):** Digitaalne allkirjastamine ja krüpteerimine meilisuhtluses.
* **Koodi allkirjastamine:** Tarkvara autentsuse ja terviklikkuse kinnitamine, tagades, et koodi ei ole pärast väljaandmist muudetud.
* **Kliendi autentimine:** Kasutajate identiteedi kontrollimine (nt e-panganduses).
* **Asjade internet (IoT):** Seadmete autentimine.

---

## 📜 Sertifikaadi Versioonid

Standard on aja jooksul arenenud, lisades uusi funktsioone:

* **Versioon 1 (v1):** Algne standard (1988).
* **Versioon 3 (v3):** Praegu kehtiv versioon, mis lisas **sertifikaadi laiendused** (**Extensions**), võimaldades paindlikumat kasutusotstarvete ja piirangute määratlemist (nt *Extended Key Usage*).

---
  * **CSR (Certificate Signing Request):** See on sisuliselt **taotlus kirjutada alla oma avalikule võtmele**. Taotleja loob oma privaatvõtme ja sellest tuletatud avaliku võtme. Ta saadab avaliku võtme koos identiteediandmetega (nt. domeeninimi, organisatsioon) CA-le, kuid hoiab privaatvõtme endale.
  * **Kehtivusaeg:** Määratleb ajaakna, mille jooksul sertifikaati peetakse kehtivaks. Pärast aegumiskuupäeva (või enne kehtivuse algust) sertifikaati ei usaldata.
  * **CA Allkiri:** CA krüpteerib sertifikaadi andmete räsi oma **privaatvõtmega**. Brauser kasutab CA **avalikku võtit** (mis on brauseris eelnevalt salvestatud) allkirja kontrollimiseks. Kui see kontroll ebaõnnestub, tähendab see, et kas sertifikaati on muudetud või see ei pärine usaldusväärsest CA-st.
  * **SAN (Subject Alternative Name):** Peamine tunnus tänapäeva TLS-sertifikaatidel. See võimaldab ühel sertifikaadil kaitsta mitut hostinime (nt. *[www.example.com](https://www.example.com)* ja *https://www.google.com/search?q=mail.example.com*), tagades paindlikkuse.

| Kontseptsioon | Päriselu Näide 1 | Päriselu Näide 2 |
| :--- | :--- | :--- |
| **X.509 Väljad** | **Laiendatud Valideerimisega (EV) Sertifikaat:** See on X.509 sertifikaat, mis sisaldab lisaks tavaväljadele ka eraldi organisatsiooni juriidilisi andmeid (auditipõhine). Brauserid kuvavad varem sellise saidi nime rohelisena, mis annab tarbijale maksimaalse usalduse. | **Koodi Allkirjastamise Sertifikaat:** X.509 sertifikaadi erivorm. Selle `Key Usage` väli on piiratud ainult **digiallkirjastamisega**. Seda kasutatakse tarkvara installifailide allkirjastamiseks, et tagada, et tarkvara pole pärast arendaja poolt allkirjastamist pahavaraga nakatatud. |
| **CA Allkiri ja Usaldus** | **Root CA Juured:** Brauserid (nt. Google Chrome) omavad eelinstalleeritud **Root CA sertifikaatide kogumit**. See on nagu "usalduse nimekiri". Kui sertifikaat on allkirjastatud mõne nimekirjas oleva Root CA poolt (või selle **Intermediate CA** poolt), usaldab brauser seda automaatselt. | **Vahesertifikaadid (Intermediate CA):** Tänapäeval ei allkirjasta Root CA ise lõppkasutaja sertifikaate. Seda teeb **Intermediate CA**, kes on allkirjastatud Root CA poolt. See loob **usalduse ahela** ja võimaldab Root CA privaatvõtit hoida turvaliselt **offline**-režiimis (võrgust lahus). |

-----

## 2\. tund: Sertifikaadi Haldus ja Avaliku Võtmega Autentimine

### 🎯 Eesmärgid

  * Mõista, millal ja miks on vajalik sertifikaadi **ennetähtaegne tühistamine**.
  * Õppida tundma **CRL** ja **OCSP** erinevusi.
  * Mõista **TLS-i käepigistuse** rolli serveri autentimisel ja turvalise seansivõtme loomisel.

-----

### 1\. Sertifikaadi Elutsükkel

#### Sertifikaadi Tühistamine: Põhiolemus

Tühistamine on PKI kriitiline turvakomponent. Kuigi sertifikaadil on aegumiskuupäev, võib selle **privaatvõti** sattuda varem kurjategijate kätte (nt. serveri häkkimise või pahavara tõttu). Sel juhul tuleb sertifikaat **koheselt tühistada**.

  * **CRL (Certificate Revocation List):** **Must nimekiri**. CA avaldab perioodiliselt faili, mis sisaldab kõigi tühistatud sertifikaatide seerianumbreid. Kontrollimiseks peab tarkvara kogu selle nimekirja alla laadima. See on aeglane ja CRL-failid muutuvad aja jooksul väga suureks.
  * **OCSP (Online Certificate Status Protocol):** **Reaalajas kontroll**. Selle asemel, et alla laadida terve nimekiri, saadab tarkvara CA-le (või selle vastutavale serverile) kiire küsimuse: *Kas see sertifikaat seerianumbriga X on kehtiv?* Vastus on kompaktne (Kehtiv, Tühistatud, Tundmatu). See on kiirem ja kaasaegsem meetod.
  * **OCSP Stapling:** Optimeering, kus veebiserver **hangib ise** CA-lt sertifikaadi kehtivuse tõendi ja **lisab selle** iga TLS-i käepigistuse juurde. See vähendab brauseri vajadust pöörduda eraldi CA serveri poole.

| Kontseptsioon | Päriselu Näide 1 | Päriselu Näide 2 |
| :--- | :--- | :--- |
| **Tühistamine** | **Kogemata Lekkinud Privaatvõti:** Kui süsteemiadministraator paneb serveri privaatvõtme kogemata avalikku koodihoidlasse (nt. GitHubi), on see kohe ohtlik. CA-d tuleb sellest teavitada ja sertifikaat **koheselt tühistada**, olenemata selle aegumiskuupäevast. | **Ebaseaduslik CA (DigiNotar):** 2011. aastal häkiti Hollandi CA-d DigiNotar ja häkkerid väljastasid võltsitud Google'i sertifikaate. Pärast seda, kui see avastati, **tühistasid** kõik brauserid ja operatsioonisüsteemid DigiNotari kui CA usalduse juurest, muutes kõik selle väljastatud sertifikaadid kasutuks. |
| **OCSP Kasutamine** | **Finantstehingud:** Pangad kasutavad OCSP-d igal kliendi autentimisel. See on eriti kriitiline tehingute puhul, kus sertifikaadi olek peab olema **garanteeritult reaalajas** kontrollitud, et välistada kahtlase privaatvõtmega sertifikaadi kasutamine. | **Pikaajaline Allkirjastamine (LT-TM):** Dokumendi allkirjastamisel lisatakse allkirja juurde OCSP vastus. See tõendab tulevikus, et **allkiri oli kehtiv hetkel**, mil see loodi (vastasel juhul ei saa me teada, kas see oli tühistatud enne allkirjastamist või mitte). |

-----

### 2\. Avaliku Võtmega Autentimine

#### Avaliku Võtmega Autentimine: Põhiolemus

Autentimine PKI-ga tõestab, et teil on juurdepääs **salajasele privaatvõtmele**, mis vastab avalikult jagatud sertifikaadile. See toimub tavaliselt matemaatilise **väljakutse-vastuse (challenge-response)** mehhanismi kaudu.

  * **TLS (Transport Layer Security) Käepigistus (Handshake):** See on algne suhtlus kahe osapoole (nt. brauser ja server) vahel. **Selle eesmärk on:**
    1.  **Serveri Autentimine:** Server saadab brauserile oma sertifikaadi. Brauser kontrollib sertifikaadi usaldust ja kehtivust.
    2.  **Seansivõtme Turvaline Lõpp-planeerimine:** Kasutades asümmeetrilist krüptograafiat, lepivad mõlemad osapooled kokku juhuslikus **sümmeetrilises seansivõtmes**.
  * **Seansivõti:** Pärast edukat käepigistust kasutatakse kogu edasise andmevahetuse krüpteerimiseks seda **sümmeetrilist võtit** (nt. AES-256). See on palju kiirem kui asümmeetriline krüpteerimine.
  * **Diffie-Hellman (DH):** Laialdaselt kasutatav algoritm seansivõtme turvaliseks vahetamiseks, mis pakub **Forward Secrecy (edaspidist saladust)**: Isegi kui serveri privaatvõti lekib tulevikus, ei saa ründaja dekrüpteerida varasemaid salvestatud seansse, kuna iga seansivõti loodi ajutiste (efemeraalsete) parameetrite abil.

| Kontseptsioon | Päriselu Näide 1 | Päriselu Näide 2 |
| :--- | :--- | :--- |
| **TLS Käepigistus** | **Turvalise Ühenduse Indikaator:** Kui teie brauser näitab **tabaluku ikooni** (ja aadress algab HTTPS-iga), tähendab see, et TLS-i käepigistus oli edukas. Brauser teab, et server on autenditud ja andmevahetus on krüpteeritud vastava seansivõtmega. | **VPN-i Ühenduse Loomine:** Kui loote ühenduse turvalise VPN-iga, kasutab VPN-klient avaliku võtme sertifikaate VPN-serveri autentsuse kontrollimiseks. Pärast serveri autentsuse kinnitamist luuakse turvaline tunnel, mis kasutab **sümmeetrilist võtit** andmete kiireks edastamiseks. |
| **SSH Võtmepõhine Autentimine** | **Serveri Haldus ilma Paroolita:** Administraator loob SSH võtmepaari. Ta paneb **avaliku võtme** kaugserveri faili `~/.ssh/authorized_keys`. Sisselogimisel saadab server talle **väljakutse** (krüpteeritud juhusliku andmehulga). Administraatori SSH klient dekrüpteerib selle andmehulga **privaatvõtmega** ja saadab dekrüpteeritud vastuse serverile, mis tõestab, et administratsioonil on privaatvõti. | **Kahepoolne Autentimine Kliendi Sertifikaadiga:** Ettevõte nõuab, et töötajate ligipääs tundlikule siseveebile toimuks parooli **ja** spetsiaalse kliendi sertifikaadi (USB-tokenil) abil. Server kontrollib esmalt parooli ja seejärel nõuab kliendi sertifikaadi esitamist, et kontrollida töötaja privaatvõtme olemasolu. |

-----

## 3\. tund: Digitaalallkirjad ja Võtmehoidlad

### 🎯 Eesmärgid

  * Mõista, kuidas **digitaalallkirjad** tagavad andmete terviklikkuse ja keeldumatuse.
  * Õppida tundma **räsifunktsioonide** rolli allkirjastamisel.
  * Eristada privaatvõtme **tarkvaralise** ja **riistvaralise** hoiustamise eeliseid ja puudusi.

-----

### 1\. Digitaalallkirjad ja Autentsus

#### Digitaalallkiri: Põhiolemus

Digitaalallkirjastamine on asümmeetrilise krüptograafia teine pool. Selle eesmärk ei ole krüpteerida andmeid, vaid tõestada andmete **päritolu** ja **muutmatust**.

1.  **Räsifunktsioon (Hash Function):** See on **matemaatiline funktsioon**, mis võtab suvalise pikkusega andmehulga (dokumendi) ja loob sellest fikseeritud pikkusega **räsi (hash)**. Räsi on dokumenti iseloomustav **unikaalne sõrmejälg**. **Põhiolemus:** Isegi ühe tähemärgi muutmine dokumendis muudab räsi täielikult (lumepalliefekt).
2.  **Allkirjastamine:** Allkirjastaja võtab dokumendi **räsi** ja krüpteerib selle **oma privaatvõtmega**. Krüpteeritud räsi ongi **digitaalallkiri**.
3.  **Kontroll:** Vastuvõtja: a) loob dokumendist ise räsi, ja b) dekrüpteerib allkirja allkirjastaja **avaliku võtmega** (saades algse räsi). Kui need kaks räsi on identsed, on täidetud kolm tingimust:
      * **Autentsus:** Tõendab allkirjastaja identiteeti (ainult allkirjastaja privaatvõti sai luua selle allkirja).
      * **Terviklikkus (Integrity):** Tõendab, et dokumenti pole pärast allkirjastamist muudetud.
      * **Keeldumatus (Non-Repudiation):** Allkirjastaja ei saa hiljem väita, et ta ei allkirjastanud.

| Kontseptsioon | Päriselu Näide 1 | Päriselu Näide 2 |
| :--- | :--- | :--- |
| **Räsifunktsioon (SHA-256)** | **Allalaaditud Tarkvara Kontroll:** Sageli annab tarkvara pakkuja oma veebilehel ka tarkvara installifaili **SHA-256 räsi**. Kasutaja saab pärast allalaadimist kontrollida faili räsi oma arvutis. Kui räsid ei kattu, on faili kas transpordi ajal muudetud või see on kahjustatud. | **Plokiahela Terviklikkus:** Krüptoraha (nt. Bitcoin) plokiahel kasutab räsifunktsiooni. Iga ploki andmed räsitakse ja see räsi salvestatakse järgmise ploki sisse. See loob **ahela**, mis muudab andmete tagasiulatuva muutmise (terviklikkuse rikkumise) matemaatiliselt võimatuks. |
| **Digitaalallkirja Kasutus** | **Eesti DigiDoc (ASiC-E):** Eesti digiallkirja standard kasutab digitaalallkirju, mis lisatakse dokumendile metaandmetena. See võimaldab dokumendil endal jääda loetavaks, samas kui allkiri garanteerib selle **juriidilise siduvuse** ja terviklikkuse. | **Finantsaruannete Kontroll:** Valitsusasutused või audiitorid võivad nõuda, et ettevõtted allkirjastaksid oma elektroonilised finantsaruanded spetsiaalse sertifikaadiga. See garanteerib, et andmeid pole esitamise ja vastuvõtmise vahelisel ajal muudetud. |

-----

### 2\. Võtmete Hoiustamine (Key Storage)

#### Privaatvõtme Hoiustamine: Põhiolemus

Privaatvõtme saladuses hoidmine on PKI kõige kriitilisem turvaprobleem. Kui privaatvõti satub väärasse kätte, võib ründaja teostada **identiteedivargust** (allkirjastada kellegi nimel, dekrüpteerida tema andmeid).

  * **Tarkvaraline Hoiustamine:** Võti on salvestatud **failina** (nt. `.p12`, `.pem` või `.key`) arvuti kõvakettal. Fail on tavaliselt krüpteeritud parooliga. See on mugav, kuid haavatav, kuna pahavara või ründajad võivad faili kopeerida või dekrüpteerida.
  * **Riistvaraline Hoiustamine (HSM/Kiipkaart):** Võti luuakse ja hoitakse spetsiaalses füüsiliselt turvatud riistvaraseadmes. **Põhiolemus:** **Privaatvõti ei lahku kunagi seadmest krüpteerimata kujul.** Seade teostab allkirjastamise ja dekrüpteerimise **seadme sees** ja väljastab ainult tulemused. See pakub parimat kaitset pahavara ja kaugrünnakute vastu.
      * **HSM (Hardware Security Module):** Spetsiaalne serveri- või andmekeskuse tasandi seade, mida kasutatakse CA juurvõtmete, panga süsteemivõtmete või suurte TLS-võtmete hoidmiseks.
      * **Nutikaart/Token:** Isiklik HSM, mida kasutatakse ID-kaartides ja USB-tokenites (nt. YubiKey).

| Kontseptsioon | Päriselu Näide 1 | Päriselu Näide 2 |
| :--- | :--- | :--- |
| **Nutikaart/Token** | **Krüptoraha Rahakott (Hardware Wallet):** Riistvaraline seade krüptovõtmete hoiustamiseks. Kui soovite saata krüptoraha, peab arvuti ütlema seadmele: "Allkirjasta see tehing." Seade nõuab **PIN-koodi** ja allkirjastab tehingu turvaliselt. See tagab, et isegi nakatunud arvuti ei saa teie võtit varastada. | **Turvaline Kaugjuurdepääs (MFA):** USB-tokenit kasutatakse kahetegurilise autentimise (MFA) osana. Token sisaldab privaatvõtit, mida kasutatakse serveri väljakutse krüptograafiliseks allkirjastamiseks, tõestades nii kasutaja isikut. |
| **HSM Kasutamine** | **Pilveteenuse Võtmehoidlad (AWS KMS, Azure Key Vault):** Suured pilveteenuse pakkujad pakuvad HSM-i teenuseid. Kasutajad saavad oma krüpteerimisvõtmeid hoida pilve HSM-is, mis pakub FIPS-standarditele vastavat (riiklikult heakskiidetud) füüsilist turvalisust. | **Finantsasutuste PIN-haldus:** Pangad peavad oma sisevõrkudes kaitsma krüpteeritud PIN-koode ja sümmeetrilisi võtmeid, mida kasutatakse pangaautomaatide ja kaarditehingute töötlemiseks. See toimub rangelt sertifitseeritud ja füüsiliselt turvatud HSM-ide sees. |

-----

## 4\. tund: Võtmete Halduse Parimad Praktikad ja PKI Haldus

### 🎯 Eesmärgid

  * Mõista **Root CA** ja **Intermediate CA** hierarhia strateegilist tähtsust.
  * Õppida tundma **CPS-i** (Certificate Practice Statement) sisu ja olulisust.
  * Mõista, millised on parimad praktikad võtme **genereerimiseks, varundamiseks ja hävitamiseks**.

-----

### 1\. PKI Halduspõhimõtted

#### CA Hierarhia ja Haldus: Põhiolemus

CA hierarhia on **kaitse sügavuse** (Defense in Depth) rakendamine PKI-s. Kõige tähtsam võti (Root CA) on ka kõige paremini kaitstud.

  * **Root CA (Juur-CA):** **Kõige kõrgem usaldus**. Selle sertifikaat on **iseallkirjastatud** (self-signed). Selle privaatvõti peab olema **offline** (võrgust lahus) ja hoitud ülimalt turvalises HSM-is. Root CA ainus ülesanne on allkirjastada **Intermediate CA** sertifikaadid. Kui Root CA võti lekib, on kogu PKI taristu kompromiteeritud.
  * **Intermediate CA (Vahe-CA):** **Igapäevane tööeest vastutaja**. See on Root CA poolt allkirjastatud ja hoitakse **online**-režiimis (võrguga ühendatud), et allkirjastada serveri ja kasutaja sertifikaate. Kui Intermediate CA võti lekib, saab selle tühistada Root CA poolt, säästes ülejäänud taristut.
  * **CPS (Certificate Practice Statement / Sertifikaadi Tegevuspoliitika):** PKI **juhendraamat**. See on juriidiline dokument, mis kirjeldab detailseid reegleid: kuidas CA võtmeid kaitseb, milliseid standardeid sertifikaatide väljastamisel järgitakse ja millised on tühistamise protseduurid. See annab usalduse tarbijatele (teie brauserile), et CA on usaldusväärne.

| Kontseptsioon | Päriselu Näide 1 | Päriselu Näide 2 |
| :--- | :--- | :--- |
| **CA Hierarhia** | **Veebibrauseri Usaldusahel:** Kui klõpsate veebisaidi tabalukule, näete sertifikaatide teed: **Lõpp-kasutaja Sertifikaat** (nt. *example.com*) $\rightarrow$ allkirjastatud **Intermediate CA** poolt $\rightarrow$ allkirjastatud **Root CA** poolt. See ahel peab olema täielik ja Root CA peab olema brauseri usaldusnimekirjas. | **Suure Ettevõtte Sise-PKI:** Ettevõte loob Root CA, mis hoiab usaldust ja on alati offline. Iga osakond (nt. IT, arendus, finants) saab oma Intermediate CA, et väljastada sertifikaate ainult oma spetsiifilistele serveritele ja seadmetele. |
| **CPS Jõustamine** | **WebTrust Auditid:** Avalikud CA-d peavad läbima regulaarselt **WebTrust** või ETSI standarditel põhinevaid turvaauditeid. Need auditid kontrollivad, kas CA tegelikult järgib oma CPS-i (nt. kas Root CA võti on tõesti offline-HSM-is), et säilitada brauserite (nt. Google, Mozilla) usaldus. | **Juhtsertifikaatide Piiramine:** CPS määrab reeglid, milliste domeenide jaoks CA saab sertifikaate väljastada. Näiteks ei tohiks sertifikaadi väljastaja väljastada sertifikaati Google'i domeenidele, välja arvatud Google'i enda taotlusel (teatud tehnilised piirangud). |

-----

### 2\. Võtmehaldus

#### Võtmete Elutsükkel: Põhiolemus

Võtmehaldus katab kõik protsessid alates võtme loomisest kuni selle hävitamiseni. **Turvalisus** peab olema tagatud igas etapis.

  * **Juhuslikkuse genereerimine:** Krüptograafilised võtmed peavad olema **täiesti juhuslikud** ja **ennustamatud**. Kasutatakse **Kõrge Entroopia Allikaid** (nt. riistvaraline müra või termiline müra), et tagada, et matemaatikud ei suudaks võtit ennustada. Nõrk juhuslikkus on krüptograafia suurim viga.
  * **Võtme Varundamine (Key Backup):** Kriitilised privaatvõtmed (eriti need, mida kasutatakse dekrüpteerimiseks) tuleb varundada turvaliselt. Kui inimene kaotab oma privaatvõtme, kaotab ta ligipääsu kõigile varem krüpteeritud andmetele.
  * **Võtme Hävitamine (Key Destruction):** Kui sertifikaat aegub või tühistatakse, tuleb selle privaatvõti **turvaliselt hävitada** (nt. andmekandja füüsiline hävitamine või spetsiaalne üle kirjutamine), et vältida selle kuritarvitamist.

| Kontseptsioon | Päriselu Näide 1 | Päriselu Näide 2 |
| :--- | :--- | :--- |
| **Varundamine** | **Key Escrow (Võtmete Hoiuleandmine):** Ettevõtte dekrüpteerimisvõtmed hoiustatakse turvaliselt kolmanda osapoole juures. Kui töötaja lahkub või kaotab võtme, saab ettevõte andmetele ligi (nt. vanad krüpteeritud e-kirjad), ilma et oleks oht krüpteerimisele tervikuna. | **Võtme Arhiveerimine:** Kui sertifikaat aegub, säilitatakse see koos privaatvõtmega krüpteeritud arhiivina. See on vajalik tulevikus vanade dokumentide **dekrüpteerimiseks** või varasemate allkirjade **kontrollimiseks**. |
| **Hävitamine** | **Füüsiline Kettakustutus:** Andmekeskustes, kus hoiti CA privaatvõtmeid, ei piisa kustutamise käsu andmisest. Riistvara (nt. HSM-id või kõvakettad), mis sisaldas privaatvõtmeid, **hävitatakse füüsiliselt** (purustatakse) enne, kui need saadetakse utiliseerimisele, et välistada võtmete taastamine. | **OpenSSL Parameetrite Valik:** Kaasaegne krüptograafia nõuab, et uued võtmed oleksid piisavalt pikad, et taluda arvutusjõu kasvu. Seetõttu nõutakse RSA puhul **vähemalt 2048 bitti** või Eestis eelistatud elliptiliste kõverate (ECC) puhul vähemalt **P-256** standardit. |

-----

## Praktiline Ülesanne IT Õpilastele: Mini-PKI Seadistamine

See ülesanne aitab õpilastel mõista PKI hierarhiat ja sertifikaatide loomise protsessi, kasutades tasuta ja laialt levinud tööriista **OpenSSL**.

### **Ülesanne: Omaette TLS-i Usaldusahela Loomine**

#### **Eesmärk**

Seadistada lihtsustatud kolmeastmeline PKI (Root CA, Intermediate CA, Serveri sertifikaat) ja luua töötav usaldusahel.

#### **Vajalikud vahendid**

  * Linuxi või macOS-i süsteem (või Windows Subsystem for Linux (WSL) Windowsis).
  * **OpenSSL** tarkvara (tavaliselt e-installeeritud).

#### **Samm-sammuline juhend**

### 1\. Ettevalmistus ja Root CA Loomine (Juur)

Root CA on usaldusahela tipp. See peab olema kõige turvalisem.

| Samm | Käsk (Linux/macOS) | Eesmärk |
| :--- | :--- | :--- |
| **1.1 Loo Root CA Privaatvõti** | `openssl genrsa -aes256 -out root-ca.key 2048` | Loob krüpteeritud RSA privaatvõtme (2048 bitti). See tuleb hoida absoluutselt saladuses. Sisestage tugev **parool** (`pass phrase`). |
| **1.2 Loo Root CA Sertifikaat** | `openssl req -x509 -new -nodes -key root-ca.key -sha256 -days 3650 -out root-ca.crt` | Loob iseallkirjastatud **Root CA sertifikaadi** (`.crt`). See on **usaldusjuur**. Vastake küsimustele (Country, Organisation Name jne.). CN (Common Name) peaks olema nt. `"My-Root-CA"`. Kehtib 10 aastat (3650 päeva). |

### 2\. Intermediate CA Loomine (Vahelüli)

Intermediate CA väljastab igapäevaseid serveri sertifikaate.

| Samm | Käsk (Linux/macOS) | Eesmärk |
| :--- | :--- | :--- |
| **2.1 Loo Intermediate CA Privaatvõti** | `openssl genrsa -aes256 -out intermediate-ca.key 2048` | Loob uue privaatvõtme Intermediate CA jaoks. |
| **2.2 Loo Intermediate CA Sertifikaadi Taotlus (CSR)** | `openssl req -new -key intermediate-ca.key -sha256 -out intermediate-ca.csr` | Loob CSR-i, mis sisaldab Intermediate CA avalikku võtit ja identiteediinfot. CN peaks olema nt. `"My-Intermediate-CA"`. |
| **2.3 Allkirjasta CSR Root CA-ga** | `openssl x509 -req -in intermediate-ca.csr -CA root-ca.crt -CAkey root-ca.key -CAcreateserial -out intermediate-ca.crt -days 1825 -sha256 -extensions v3_ca` | Kasuta Root CA privaatvõtit ja sertifikaati, et **allkirjastada** Intermediate CA CSR. Tulemus on **Intermediate CA sertifikaat**. Laiend `v3_ca` määrab, et see on sertifitseerimisasutus. Sisestage Root CA parool. |

### 3\. Veebiserveri Sertifikaadi Loomine (Lõppkasutaja)

Lõpuks loome veebiserveri jaoks sertifikaadi, mille allkirjastab Intermediate CA.

| Samm | Käsk (Linux/macOS) | Eesmärk |
| :--- | :--- | :--- |
| **3.1 Loo Serveri Privaatvõti** | `openssl genrsa -out server.key 2048` | Loo serveri privaatvõti. |
| **3.2 Loo Serveri Sertifikaadi Taotlus (CSR)** | `openssl req -new -key server.key -out server.csr` | Loo CSR serveri jaoks. **TÄHELEPANU:** Common Name (CN) peab olema domeeninimi, nt. `"localhost"` või `"myserver.local"`. |
| **3.3 Allkirjasta Serveri CSR Intermediate CA-ga** | `openssl x509 -req -in server.csr -CA intermediate-ca.crt -CAkey intermediate-ca.key -CAcreateserial -out server.crt -days 365 -sha256` | Kasuta Intermediate CA privaatvõtit ja sertifikaati, et allkirjastada serveri CSR. Tulemuseks on **serveri sertifikaat** (`.crt`). Sisestage Intermediate CA parool. |

### **Tulemuse Kontroll**

Kontrolli OpenSSL-i abil, kas usaldusahel on korrektselt loodud:

1.  **Kontrolli Intermediate CA usaldust Root CA vastu:**
    ```bash
    openssl verify -CAfile root-ca.crt intermediate-ca.crt
    ```
    *Vastus peaks olema: `OK`*
2.  **Kontrolli Serveri sertifikaadi usaldust Intermediate CA vastu:**
    ```bash
    openssl verify -CAfile intermediate-ca.crt server.crt
    ```
    *Vastus peaks olema: `OK`*

Kui mõlemad käsud annavad vastuseks `OK`, on usaldusahel matemaatiliselt kehtiv\!

Kas sooviksite, et ma koostaksin täiendavaid kontrollküsimusi selle materjali kinnistamiseks?
