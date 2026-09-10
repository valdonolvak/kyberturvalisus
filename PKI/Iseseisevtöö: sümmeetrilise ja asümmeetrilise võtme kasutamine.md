Juhised on koostatud eeldades, et õpilased kasutavad **Linuxi/macOS-i keskkonda** ja **OpenSSL** tööriista, mis on krüptograafiliste funktsioonide jaoks standardne.

**Keskkond ja ligipääs**
Aadress (sisevõrgus): https://193.40.178.155:8006/
Aadress (väljast): https://193.40.178.155:8006/
Realm: hkhk.edu.ee | Kasutaja: eesnime esitäht + perekonnanimi
Info: Töö ajal on lubatud kasutada internetis leiduvaid materjale ja juhendeid.

Virtuaalmasinad ja võrguseadistus (VMBR) Võrguaadress moodustub valemiga 10.0.XXX.0/24, kus XXX on sinu virtuaalmasina võrguseadme vnet number.

Näide: Kui vnet = 50, siis võrguaadress on 10.0.50.0/24.

| Masina nimi Proxmoxis                    | Masina nimi süsteemis | IP-aadress | Roll                           |
| ---------------------------------------- | --------------------- | ---------- | ------------------------------ |
| `kyberturve-its25-juhan-winserver`       | WinServer             | DHCP       | Windows Server                 |
| `kyberturve-its25-juhan-win11enterprise` | Win11Enterprise       | DHCP       | Klient (Windows 11 Enterprise) |
| `kyberturve-its25-juhan-debian-1`        | Debian1               | DHCP       | Linuxi server 1                |
| `kyberturve-its25-juhan-debian-2`        | Debian2               | DHCP       | Linuxi server 2                |


**Märkus: NIMI tähistab Proxmoxi masinate nimedes õpilase kasutajanime.**

Debian serveri kasutajanimi: kasutaja ja parool:Passw0rd
Windows serveri Administraatori parool: Passw0rd
Windows Klientmasina parool: Passw0rd


-----
## 🛠️ 3 Praktilist ülesannet OpenSSL-iga (Uuendatud Juhised)

### Ülesanne 1: Sümmeetriline Krüptograafia (AES)

**Eesmärk:** Krüpteerida fail turvaliselt, kasutades AES-i ja tugevdatud võtme tuletamise funktsiooni.

#### Samm-sammuline juhend:

1.  **Loo salajane sisendfail:**
    ```bash
    echo "See fail sisaldab krüpteerimist vajavat salajast infot." > salajane.txt
    ```
2.  **Krüpteeri fail AES-256-ga (Kasutades -pbkdf2):**
    Kasuta turvalisemat PBKDF2 võtme tuletamise funktsiooni. OpenSSL küsib parooli.
    ```bash
    openssl enc -aes-256-cbc -salt -pbkdf2 -in salajane.txt -out krüpteeritud.aes
    ```
3.  **Kontrolli krüpteeritud sisu:**
    ```bash
    cat krüpteeritud.aes
    ```
4.  **Dekrüpteeri fail:**
    Kasuta dekrüpteerimiseks täpselt sama parooli ja `-pbkdf2` parameetrit.
    ```bash
    openssl enc -d -aes-256-cbc -in krüpteeritud.aes -pbkdf2 -out dekrüpteeritud.txt
    ```
5.  **Kontrolli dekrüpteeritud sisu:**
    ```bash
    cat dekrüpteeritud.txt
    ```

-----

### Ülesanne 2: Asümmeetriline Krüptograafia (RSA)

**Eesmärk:** Loo avaliku/privaatse võtme paar ja kasuta neid sõnumi krüpteerimiseks ja dekrüpteerimiseks (simuleerides turvalist saatmist).

#### Samm-sammuline juhend:

1.  **Loo Privaatvõti:**
    ```bash
    openssl genrsa -out minu_privaatne.pem 2048
    ```
2.  **Loo Avalik Võti:**
    ```bash
    openssl rsa -pubout -in minu_privaatne.pem -out minu_avalik.pem
    ```
3.  **Loo Sõnum Krüpteerimiseks:**
    ```bash
    echo "Tere, see on konfidentsiaalne teade!" > saatja_sõnum.txt
    ```
4.  **Krüpteeri sõnum (Saatja pool) – Kasutades pkeyutl:**
    Krüpteeri **avaliku võtmega**.
    ```bash
    openssl pkeyutl -encrypt -pubin -inkey minu_avalik.pem -in saatja_sõnum.txt -out krüpteeritud_teade.dat
    ```
5.  **Dekrüpteeri sõnum (Vastuvõtja pool) – Kasutades pkeyutl:**
    Dekrüpteeri **salajase (privaatse) võtmega**.
    ```bash
    openssl pkeyutl -decrypt -inkey minu_privaatne.pem -in krüpteeritud_teade.dat -out dekrüpteeritud_teade.txt
    ```
6.  **Kontrolli tulemust:**
    ```bash
    cat dekrüpteeritud_teade.txt
    ```

-----

### Ülesanne 3: Digitaalallkiri ja Terviklikkuse Kontroll

**Eesmärk:** Kasuta asümmeetrilist krüptograafiat digitaalallkirja loomiseks ja veendumiseks, et faili pole muudetud.

#### Samm-sammuline juhend (Allkirjastamine):

1.  **Loo allkirjastatav dokument:**
    ```bash
    echo "Lepingu tekst: Kinnitan, et need andmed on õiged." > leping.txt
    ```
2.  **Loo Privaatvõti** (Kui pole veel loodud):
    ```bash
    openssl genrsa -out allkirjastaja_privaatne.pem 2048
    ```
3.  **Loo Räsi (Hash):**
    ```bash
    openssl dgst -sha256 -binary leping.txt > leping.räsi
    ```
4.  **Loo Digitaalallkiri (Kasutades pkeyutl):**
    Krüpteeri räsi **privaatvõtmega**.
    ```bash
    openssl pkeyutl -sign -in leping.räsi -inkey allkirjastaja_privaatne.pem -out leping.allkiri
    ```
5.  **Loo Avalik Võti** (Vajalik kontrollimiseks):
    ```bash
    openssl rsa -pubout -in allkirjastaja_privaatne.pem -out allkirjastaja_avalik.pem
    ```

#### Terviklikkuse Kontroll (Testi juhend):

6.  **Kontrolli algset, kehtivat allkirja:**
    Veendu, et allkiri on kehtiv enne muudatuste tegemist. Kasuta avalikku võtit ja algset räsi.

    ```bash
    openssl pkeyutl -verify -in leping.räsi -sigfile leping.allkiri -pubin -inkey allkirjastaja_avalik.pem
    ```

      * **Oodatav:** `Verification successful`

7.  **Muuda dokumenti (Riku terviklikkust):**
    Tee faili sisse tahtlik muudatus – see simuleerib ründaja tegevust.

    ```bash
    # Lisa faili lõppu tühik
    echo " " >> leping.txt 
    ```

8.  **Loo UUS räsi muudetud dokumendist:**

    ```bash
    openssl dgst -sha256 -binary leping.txt > muudetud.räsi
    ```

9.  **Kontrolli VANA allkirja UUE räsi vastu:**
    Proovi kasutada algset allkirja (`leping.allkiri`) muudetud sisu räsiga (`muudetud.räsi`).

    ```bash
    openssl pkeyutl -verify -in muudetud.räsi -sigfile leping.allkiri -pubin -inkey allkirjastaja_avalik.pem
    ```

      * **Oodatav Tulemus (Tõestus):** `Verification failure`
      * **Järeldus:** See tõestab, et faili **terviklikkus** on rikutud. Digitaalallkiri täitis oma ülesannet.

-----

**Kas sooviksid nüüd põhjalikku selgitust selle kohta, kuidas täpselt digitaalallkiri (räsi + asümmeetriline krüptimine) töötab?**
