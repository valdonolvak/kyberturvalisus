Juhised on koostatud eeldades, et õpilased kasutavad **Linuxi/macOS-i keskkonda** ja **OpenSSL** tööriista, mis on krüptograafiliste funktsioonide jaoks standardne.

-----

## 🛠️ Praktilised Krüptograafia Ülesanded OpenSSL-iga

### 1\. Sümmeetriline Krüptograafia (AES): Faili turvaline šifreerimine

Selles ülesandes kasutatakse **sümmeetrilist krüptograafiat (AES)** tundliku faili krüpteerimiseks. Õpilane loob šifreerimisel kasutatava võtme.

#### Samm-sammuline juhend:

1.  **Loo salajane sisendfail:**
    Loo lihtne tekstifail, mis sisaldab tundlikku teavet.

    ```bash
    echo "See on väga salajane teave, krüpteeri see!" > salajane.txt
    ```

2.  **Loo Sümmeetriline Võti (Parool):**
    OpenSSL kasutab sümmeetriliseks krüpteerimiseks parooli, mis toimib võtmena (läbi võtme tuletamise funktsiooni, nt PBKDF2). Krüpteerimiseks vali **tugev parool** (nt *MySuPerS41aN3k3y\!*) või genereeri juhuslik võti. Meie kasutame parooli.

3.  **Krüpteeri fail AES-256 CBC režiimis:**
    Kasuta faili `salajane.txt` krüpteerimiseks AES-256 algoritmi ja CBC režiimi. OpenSSL küsib parooli (võtme).

    ```bash
    # Käsk krüpteerimiseks (sisestage parool)
    openssl enc -aes-256-cbc -salt -in salajane.txt -out krüpteeritud.aes
    ```

    > **Märkus:** `-salt` tagab krüpteerimise turvalisuse, lisades unikaalse juhusliku väärtuse (*salt*).

4.  **Kontrolli krüpteeritud faili:**
    Proovi faili sisu vaadata – see peaks olema loetamatu.

    ```bash
    cat krüpteeritud.aes
    ```

5.  **Dekrüpteeri fail:**
    Dekrüpteeri fail tagasi, kasutades täpselt **sama parooli** (võtit).

    ```bash
    # Käsk dekrüpteerimiseks (sisestage sama parool)
    openssl enc -d -aes-256-cbc -in krüpteeritud.aes -out dekrüpteeritud.txt
    ```

6.  **Kontrolli dekrüpteeritud faili:**
    Veendu, et sisu on identne algse failiga.

    ```bash
    cat dekrüpteeritud.txt
    ```

-----

### 2\. Asümmeetriline Krüptograafia (RSA): Turvaline teade ja Võtmepaarid

Selles ülesandes luuakse **avaliku/privaatse võtme paar (RSA)** ja kasutatakse neid teate krüpteerimiseks, mille saab dekrüpteerida vaid salajase võtme omanik.

#### Samm-sammuline juhend:

1.  **Loo Privaatvõti:**
    Genereeri turvaline 2048-bitine RSA privaatvõti ja salvesta see faili `minu_privaatne.pem`.

    ```bash
    # Loo privaatvõti (sisestage parool selle kaitsmiseks)
    openssl genrsa -out minu_privaatne.pem 2048
    ```

2.  **Loo Avalik Võti:**
    Tuleta privaatvõtmest vastav avalik võti. See on võti, mille peaksid saatma sõbrale.

    ```bash
    # Tuleta privaatvõtmest avalik võti
    openssl rsa -pubout -in minu_privaatne.pem -out minu_avalik.pem
    ```

3.  **Loo Sõnum Krüpteerimiseks:**
    See on sõnum, mille keegi teine (kasutades sinu avalikku võtit) sulle saadab.

    ```bash
    echo "Tere, see on sinu jaoks krüpteeritud sõnum!" > saatja_sõnum.txt
    ```

4.  **Krüpteeri sõnum (Saatja pool):**
    Krüpteeri sõnum, kasutades **vastuvõtja avalikku võtit** (`minu_avalik.pem`).

    ```bash
    # Krüpteeri sõnum avaliku võtmega
    openssl rsautl -encrypt -pubin -inkey minu_avalik.pem -in saatja_sõnum.txt -out krüpteeritud_teade.dat
    ```

5.  **Dekrüpteeri sõnum (Vastuvõtja pool):**
    Dekrüpteeri teade, kasutades **enda salajast võtit** (`minu_privaatne.pem`).

    ```bash
    # Dekrüpteeri sõnum privaatvõtmega (sisesta parool, kui on)
    openssl rsautl -decrypt -inkey minu_privaatne.pem -in krüpteeritud_teade.dat -out dekrüpteeritud_teade.txt
    ```

6.  **Kontrolli dekrüpteeritud sõnumit:**

    ```bash
    cat dekrüpteeritud_teade.txt
    ```

-----

### 3\. Asümmeetriline Krüptograafia (RSA): Digitaalallkiri

Selles ülesandes kasutatakse **asümmeetrilist krüptograafiat** dokumendi **ehtsuse** ja **terviklikkuse** tõendamiseks digitaalallkirja abil.

#### Samm-sammuline juhend:

1.  **Loo allkirjastatav dokument:**

    ```bash
    echo "Lepingu tekst: Kinnitan, et need andmed on õiged." > leping.txt
    ```

2.  **Loo Privaatvõti** (Kui pole seda Ülesandest 2 juba olemas, tee uuesti):

    ```bash
    openssl genrsa -out allkirjastaja_privaatne.pem 2048
    ```

3.  **Loo Dokumendi Räsi (Hash):**
    Loo dokumendist unikaalne "sõrmejälg" (räsi) SHA256 abil.

    ```bash
    # Loo räsi
    openssl dgst -sha256 -binary leping.txt > leping.räsi
    ```

4.  **Loo Digitaalallkiri:**
    Krüpteeri räsi, kasutades **allkirjastaja privaatvõtit**. See krüpteeritud räsi ongi **digitaalallkiri**.

    ```bash
    # Allkirjasta räsi privaatvõtmega
    openssl pkeyutl -sign -in leping.räsi -inkey allkirjastaja_privaatne.pem -out leping.allkiri
    ```

5.  **Kontrolli Digitaalallkirja (Vastuvõtja pool):**
    Vastuvõtja (sina) peab:
    a) Tuletama privaatvõtmest vastava avaliku võtme (kui seda pole veel antud).

    ```bash
    openssl rsa -pubout -in allkirjastaja_privaatne.pem -out allkirjastaja_avalik.pem
    ```

    b) Dekrüpteerima (kontrollima) allkirja, kasutades **allkirjastaja avalikku võtit**.

    ```bash
    # Dekrüpteeri (kontrolli) allkiri avaliku võtmega
    openssl pkeyutl -verify -in leping.räsi -sigfile leping.allkiri -pubin -inkey allkirjastaja_avalik.pem
    ```

      * Kui väljund on `Verification successful`, tähendab see, et allkiri on kehtiv ja dokumenti pole pärast allkirjastamist muudetud.
      * **Proovi testi:** Muuda faili `leping.txt` sisu (nt lisa tühik) ja proovi allkirja uuesti kontrollida. Kontroll peaks nurjuma.

**Kas sooviksid nüüd lahendusi nendele ülesannetele kontrollida või keskenduda mõnele muule krüptograafia teemale?**
