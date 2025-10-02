Forensiline analüüs, täpsemalt **digitaalne forensika** (inglise keeles *Digital Forensics*), on küberintsidentide käsitlemise (Incident Response) kontekstis kriitiline etapp.

See on teaduslik meetod **digitaalsete tõendite kogumiseks, säilitamiseks, analüüsimiseks ja esitamiseks** viisil, mis on õiguslikult vastuvõetav. Selle eesmärk on kindlaks teha, mis täpselt intsidendi käigus toimus, kes selle taga oli ja millist kahju tekitati.

## Forensilise Analüüsi Eesmärk Küberintsidendi Puhul

Digitaalse forensika roll küberintsidentide käsitlemisel jaguneb mitmeks põhiülesandeks:

1.  **Algpõhjuse (Root Cause) tuvastamine:** Leidmaks, kuidas ründaja süsteemi sisenes (nt. turvaauk, andmepüügi e-kiri, nõrk parool).
2.  **Rünnaku ulatuse hindamine:** Määramaks, milliseid süsteeme, andmeid ja kasutajaid mõjutati.
3.  **Ründaja tegevuse kaardistamine:** Uurimaks, mida ründaja süsteemis tegi (nt. andmete kopeerimine, pahavara installeerimine, tagaukse loomine).
4.  **Tõendite kogumine õiguslikuks menetluseks:** Kui plaanitakse kriminaal- või tsiviilasja algatamist, on forensiline analüüs hädavajalik, et tagada tõendite usaldusväärsus.
5.  **Reageerimise toetamine:** Analüüsi tulemused suunavad reageerimismeeskonda, andes neile teada, mida täpselt parandada ja milliseid süsteeme prioritiseerida.

---

## Forensilise Analüüsi Põhimeetodid

Forensiline analüüs erineb tavalisest IT-tõrkeotsingust, kuna selle käigus peab säilima **tõendite ahel** (*Chain of Custody*), et tõestada, et tõendeid pole manipuleeritud.

### 1. Tõendite Kogumine (Collection)

See on kõige tundlikum etapp, kus kehtib põhimõte: **"kõige kaduvama esimesena"**.

* **Pahukese pildistamine:** Enne süsteemi väljalülitamist kogutakse andmed, mis kaovad arvuti väljalülitamisel.
    * **Mälupildid (*Memory Dump*):** Tööjaama või serveri RAM-i sisu kopeerimine, mis sisaldab käimasolevaid protsesse, võrguühendusi, pahavara koodi ja potentsiaalselt krüpteerimisvõtmeid.
    * **Hetkeseisu logid:** Käimasolevate võrguühenduste, aktiivsete kasutajate ja failisüsteemide kiirlogide salvestamine.
* **Andmekandja pilt (*Forensic Image*):** Pärast eelnevate andmete kogumist luuakse kõvakettast või muust andmekandjast **bitthaaval koopia** (nn. *image*). See on identne koopia, mille analüüs ei mõjuta originaalketast.

### 2. Säilitamine (Preservation)

Kogutud tõendid peavad olema **muutumatud**.

* **Räsiväärtuste kasutamine:** Iga kogutud tõendite faili kohta arvutatakse **räsiväärtus (hash)** (nt. SHA-256). See digitaalne "sõrmejälg" tõestab, et andmeid pole hiljem muudetud.
* **Tõendite ahel (*Chain of Custody*):** Dokumenteeritakse täpselt, millal, kust, kes ja kuidas tõendid kogus ning kus neid hoitakse. See on kriitiline, kui asi jõuab kohtusse.

### 3. Analüüs (Examination & Analysis)

Spetsialistid kasutavad tööriistu, et leida kogutud andmetest rünnakuga seotud anomaaliaid.

* **Ajajoonte loomine:** Luuakse detailne ajajoon, mis näitab iga toimingu (faili loomine, sisselogimine, programmi käivitamine) täpset aega ja seotust rünnakuga.
* **Pahavara analüüs (*Malware Analysis*):** Tuvastatud pahavara eraldatakse ja analüüsitakse, et mõista selle funktsionaalsust ja eesmärki (nt. andmete vargus, süsteemi kahjustamine).
* **Logianalüüs:** Süsteemilogidest, tulemüüri logidest ja muudest andmeallikatest otsitakse sissetungi märke ja ebatavalist käitumist.

### 4. Aruandlus (Reporting)

Lõpuks koostatakse detailne, arusaadav ja objektiivne aruanne, mis toetab reageerimise meeskonda ja vajadusel õiguslikke asutusi.

* Aruanne peab vastama küsimustele: **Mis juhtus? Kuidas? Millal? Miks? Kes vastutab (tehniliselt)?** Aruanne on aluseks intsidendi käsitlemise 4. faasile (**Järeltegevused**).
