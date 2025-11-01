Õppematerjal **krüptograafia ajaloost** koos illustreerivate näidetega.

---

## 📜 Krüptograafia ajalugu: Sõnumite varjamine ja kaitsmine

Krüptograafia (*kreeka keeles: **kryptos** – varjatud, **graphein** – kirjutamine*) on sama vana kui kirjutamine ise. See on teadus, mis tegeleb **info turvalise edastamisega**, muutes sõnumid arusaamatuks kõigile peale ettenähtud vastuvõtja.

---

## 1. Klassikaline krüptograafia (Eelkrüptograafia kuni u 19. sajand)

See ajastu hõlmab peamiselt **šifreid**, mis jagunevad kaheks põhitüübiks: **asendusšifrid** ja **ümberpaigutusšifrid**.

### A. Asendusšifrid (Substitution Ciphers)

Asendusšifrid asendavad algteksti tähe ühe või mitme teise tähe või sümboliga.

#### Näide 1: Caesari šiffer (u 1. sajand eKr)
* **Kirjeldus:** Tõenäoliselt tuntuim varajane krüptosüsteem, mida kasutas Rooma keiser **Julius Caesar**. See on **nihkešiffer**, kus iga algteksti täht asendatakse tähega, mis asub fikseeritud arvu kohti tähestikus hiljem.
* **Näide:** Kui nihe (võti) on **3**:
    * A $\rightarrow$ D
    * B $\rightarrow$ E
    * **Algtekst:** SALA
    * **Šifritekst:** VDO D

#### Näide 2: Polüalfabeetilised šifrid (16. sajand)
* **Kirjeldus:** See oli suur edasiminek. Ühe fiksaatori (nagu Caesari šifri puhul) kasutamise asemel kasutatakse krüpteerimisel järjestikku mitut asendustähestikku (tavaliselt võtmesõna alusel).
* **Vigenère'i šiffer:** Tuntuim näide. See raskendas oluliselt **sagedusanalüüsi** (tähtede esinemissageduse uurimist), mis oli Caesari šifri lihtne murdmisviis.
* **Tähtsus:** Vigenère'i šifrit peeti üle 300 aasta "murdmatuks".

### B. Ümberpaigutusšifrid (Transposition Ciphers)

Ümberpaigutusšifrid jätavad algteksti tähed samaks, kuid **muudavad nende järjekorda**.

#### Näide: Skytale (Sparta, u 7. sajand eKr)
* **Kirjeldus:** Üks vanimaid füüsilisi krüpteerimisvahendeid. Sõnum kirjutati spiraalselt nahkrihmale, mis oli keritud ümber spetsiaalse silindri (Skytale).
* **Võti:** Silindri **läbimõõt** (paksus).
* **Toimimine:** Kui nahkrihm lahti keriti, olid tähed segamini ja arusaamatu. Vastuvõtja vajas täpselt sama läbimõõduga silindrit, et sõnum uuesti loetavaks muuta. 

---

## 2. Mehaaniline ja elektro-mehaaniline krüptograafia (20. sajand)

Elektri ja mehaaniliste seadmete areng tõi kaasa palju keerukamad šifreerimismasinad.

### Näide: Enigma masin (II maailmasõda)
* **Kirjeldus:** Saksa armee poolt II maailmasõjas kasutatud rootormasin. Enigma kasutas kolme või enamat vahetatavat **rootorit** (ratast), mis pöörlesid iga tähe sisestamisel, luues iga kord uue ja kordumatu asenduse.
* **Tähtsus:** See oli äärmiselt keeruline polüalfabeetiline asendusšiffer. Enigma murdmine Bletchley Parkis (Suurbritannias **Alan Turingi** juhtimisel) aitas oluliselt lühendada Teist maailmasõda.
* **Murdmine:** Murdmisel ei piisanud ainult matemaatilisest analüüsist; vaja läks ka Saksamaa tegevusprotseduuride (nt igapäevaste teadete kindel formaat) tundmist ja esimese programmeeritava elektromehaanilise arvuti, **"Bombe"**, arendamist.

---

## 3. Kaasaegne krüptograafia (Alates 1970ndatest)

Arvutiajastu nõudis palju kiiremaid ja turvalisemaid krüptosüsteeme. Murranguline oli **avaliku võtme krüptograafia** sünd.

### A. Sümmeetriline krüptograafia

Kasutab **sama võtit** nii krüpteerimiseks kui ka dekrüpteerimiseks.

#### Näide: DES ja AES
* **DES (Data Encryption Standard):** Standardiseeriti 1977. aastal ja oli pikka aega ülemaailmne standard. Tänapäeval on see oma **56-bitise võtmepikkuse** tõttu aeglane ja ebaturvaline.
* **AES (Advanced Encryption Standard):** Tänapäeval levinuim sümmeetriline krüptoalgoritm (standardiseeriti 2001). Kasutab 128-, 192- või 256-bitiseid võtmeid ja on ülimalt turvaline ja kiire.

### B. Asümmeetriline krüptograafia ehk Avaliku võtme krüptograafia (Public-Key Cryptography)

Selle leiutamine 1970. aastatel (Diffie, Hellman, Merkle) muutis krüptograafiat radikaalselt, lahendades **võtmejaotuse probleemi**.

* **Põhimõte:** Kasutatakse kahte matemaatiliselt seotud võtit:
    1.  **Avalik võti (Public Key):** Jagatakse kõigiga ja seda kasutatakse **krüpteerimiseks**.
    2.  **Privaatvõti (Private Key):** Hoitakse saladuses ja seda kasutatakse **dekrüpteerimiseks**.

#### Näide: RSA algoritm
* **Kirjeldus:** Nimetatud selle loojate (Rivest, Shamir, Adleman) järgi. See on tuntuim ja laialdasemalt kasutatav avaliku võtme krüptosüsteem.
* **Alus:** Selle turvalisus põhineb ülisuurte algarvude **faktoreerimise** (teguriteks lahutamise) matemaatilisel keerukusel.
* **Kasutus:** Kasutatakse digitaalsertifikaatides (X.509), turvalises meilisuhtluses ja paljudes krüpteerimisprotokollides (nt TLS/SSL).

---

## 4. Tuleviku väljakutsed

Krüptograafia areng ei ole lõppenud. Tänapäeva peamine väljakutse on **kvantkrüptograafia** ja **kvantkindlad algoritmid** (*Post-Quantum Cryptography*). Kvanttöötluse areng ähvardab murda praegused asümmeetrilised süsteemid (nt RSA ja elliptiliste kõverate krüptograafia), mistõttu tegelevad teadlased uute, kvantarvutite rünnakutele vastupidavate algoritmide väljatöötamisega.

Kas sooviksite lähemalt uurida näiteks **RSA algoritmi matemaatilist alust** või **kvantkrüptograafia** teemat?
