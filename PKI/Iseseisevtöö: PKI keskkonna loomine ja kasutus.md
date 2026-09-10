Iseseisevtöö simuleerib realistlikku tootmiskeskkonda, kus Sertifitseerimisasutus (CA) ja veebiserver (teenusepakkuja) asuvad alati erinevates füüsilistes või virtuaalsetes masinates.

Järgnevas juhendis eeldame, et sul on kaks eraldi Linuxi/macOS-i masinat (VM-i), mida nimetame:

1.  **CA Server** (IP näiteks: `192.168.1.10`)
2.  **VEBISERVER** (IP näiteks: `192.168.1.20`)

1. Keskkond ja ligipääs
Aadress (sisevõrgus): https://193.40.178.155:8006/
Aadress (väljast): https://193.40.178.155:8006/
Realm: hkhk.edu.ee | Kasutaja: eesnime esitäht + perekonnanimi
Info: Töö ajal on lubatud kasutada internetis leiduvaid materjale ja juhendeid.
2. Virtuaalmasinad ja võrguseadistus (VMBR)
Võrguaadress moodustub valemiga 10.0.XXX.0/24, kus XXX on sinu virtuaalmasina võrguseadme vnet number.

Näide: Kui vnet = 50, siis võrguaadress on 10.0.50.0/24.

Masina nimi Proxmoxis:
 - kyberturve-its25-[kasutajanimi]-winserver
 - kyberturve-its25-[kasutajanimi]-win11enterprise
 - kyberturve-its25-[kasutajanimi]-debian-1
 - kyberturve-its25-[kasutajanimi]-debian-2

Märkus: NIMI tähistab Proxmoxi masinate nimedes õpilase kasutajanime.

Debian serveri kasutajanimi: kasutaja ja parool:Passw0rd
Windows serveri Administraatori parool: Passw0rd
Windows Klientmasina parool: Passw0rd


Kasutame domeeninime **`minudomeen.local`**.

-----

## 🚀 Praktiline Ülesanne: PKI Loomine Kahes Serveris ja HTTPS Seadistamine

### I. ⚙️ CA Serveri Ettevalmistus ja Juur-CA Loomine (Server 1)

See masin on rangelt CA rollis ja **ei tohiks** sisaldada veebiserveri privaatvõtmeid.

#### Samm-sammuline juhend:

1.  **Loo CA Privaatvõti:**

    ```bash
    # CA SERVERIS
    openssl genrsa -aes256 -out ca.key 4096 
    # Sisesta parool!
    ```

2.  **Loo CA Juursertifikaat (Self-Signed):**

    ```bash
    # CA SERVERIS
    openssl req -new -x509 -sha256 -days 3650 -key ca.key -out ca.crt
    # Vasta küsimustele. CN: My Test Root CA
    ```

3.  **Ettevalmistus edasiseks tööks:**
    Loo CA serverisse kaustad ja failid, mida OpenSSL vajab uute sertifikaatide jälgimiseks ja seerianumbrite haldamiseks.

    ```bash
    # CA SERVERIS
    mkdir demoCA
    mkdir demoCA/newcerts
    touch demoCA/index.txt
    echo 1000 > demoCA/serial 
    # '1000' on esimene seerianumber, mida CA hakkab väljastama
    ```

-----

### II. 🖥️ Veebiserveri Sertifikaadi Taotlus (Server 2)

Veebiserver loob oma võtmepaari ja saadab avaliku taotluse CA-le.

#### Samm-sammuline juhend:

1.  **Loo Serveri Privaatvõti:**
    ```bash
    # VEBISERVERIS
    openssl genrsa -out server.key 2048
    ```
2.  **Loo Sertifikaadi Taotlus (CSR):**
    CN (Common Name) peab vastama domeeninimele.
    ```bash
    # VEBISERVERIS
    openssl req -new -key server.key -out server.csr
    # CN (Common Name): minudomeen.local
    ```
3.  **Esita CSR CA-le:**
    **Kopeeri** fail `server.csr` VEBISERVERIST turvaliselt CA SERVERISSE. Seda faili võib transportida avaliku kanali kaudu, kuna see sisaldab ainult avalikku võtit ja detaile.
    ```bash
    # VEBISERVERIS
    scp server.csr user@192.168.1.10:/path/to/ca/
    ```

-----

### III. 🤝 CA Server Allkirjastab ja Väljastab Sertifikaadi (Server 1)

CA server võtab taotluse vastu, kontrollib detaile ja annab välja lõpliku sertifikaadi.

#### Samm-sammuline juhend:

1.  **Allkirjasta CSR CA-ga:**
    Kasuta `ca.key` ja `ca.crt` serveri taotluse (`server.csr`) allkirjastamiseks.

    ```bash
    # CA SERVERIS (asukoht, kus on ca.key ja ca.crt)
    openssl ca -in server.csr -out server.crt -days 365
    # See käsk kasutab vaikimisi demoCA kausta! 
    # Sisesta CA privaatvõtme parool.
    ```

    > **Tulemus:** Loodi fail **`server.crt`** (CA poolt allkirjastatud serveri sertifikaat).

2.  **Edasta sertifikaat VEBISERVERISSE:**
    **Kopeeri** allkirjastatud sertifikaat (`server.crt`) ja CA juursertifikaat (`ca.crt`) tagasi VEBISERVERISSE turvaliselt (nt kasutades `scp`).

    ```bash
    # CA SERVERIS
    scp server.crt user@192.168.1.20:/path/to/server/
    scp ca.crt user@192.168.1.20:/path/to/server/
    ```

-----

### IV. 💻 HTTPS Seadistamine ja Testimine (Server 2)

VEBISERVER paigaldab oma privaatvõtme, äsja saadud sertifikaadi ja CA sertifikaadi.

#### Samm-sammuline juhend:

1.  **Kopeeri Sertifikaadifailid Apache Kaustadesse:**

    ```bash
    # VEBISERVERIS (asukohas, kuhu server.key ja server.crt kopeeriti)
    sudo cp server.crt /etc/ssl/certs/
    sudo cp server.key /etc/ssl/private/
    sudo cp ca.crt /etc/ssl/certs/
    ```

2.  **Loo Veebiserveri Konfiguratsioon (Virtual Host):**
    Luba Apache SSL moodul ja loo konfiguratsioonifail (nt `minudomeen.conf`).

    ```bash
    # VEBISERVERIS
    sudo a2enmod ssl
    sudo nano /etc/apache2/sites-available/minudomeen.conf
    ```

    Lisa faili HTTPS-i seadistus, viidates õigetele failidele:

    ```apacheconf
    <IfModule mod_ssl.c>
    <VirtualHost *:443>
        ServerName minudomeen.local
        DocumentRoot /var/www/html

        SSLEngine on
        SSLCertificateFile /etc/ssl/certs/server.crt
        SSLCertificateKeyFile /etc/ssl/private/server.key

        # See on sertifikaadiahela tugi. Viiab meie enda CA juurde.
        SSLCertificateChainFile /etc/ssl/certs/ca.crt
    </VirtualHost>
    </IfModule>
    ```

3.  **Luba konfiguratsioon ja taaskäivita Apache:**

    ```bash
    # VEBISERVERIS
    sudo a2ensite minudomeen.conf
    sudo systemctl restart apache2
    ```

4.  **Kliendi Poolne Testimine (Lokaalarvutis):**
    Selleks, et brauser ei annaks viga, pead **kliendi arvutisse** installeerima **`ca.crt`** (CA avaliku sertifikaadi) usaldusväärsete juursertifikaatide hulka. Samuti uuenda oma kliendi `hosts` faili, et suunata `minudomeen.local` VEBISERVERI IP-aadressile (`192.168.1.20`).

    ```bash
    # KLIENDI ARVUTIS (/etc/hosts)
    192.168.1.20 minudomeen.local
    ```

    Nüüd ava brauseris: `https://minudomeen.local`. Kui kõik on õigesti seadistatud ja CA on usaldatud, peaks HTTPS leht avanema ilma hoiatusteta.

**See ülesanne demonstreerib võtmete ja sertifikaatide elutsüklit kahes eraldi turvatsoonis, mis on PKI arhitektuuris standardne praktika.**


Kuidas Linuxi käsurealt **OpenSSL-i abil sertifikaati kontrollida** – see on igapäevane oskus IT-administraatoritele.

-----

## 🔍 V. Sertifikaadi Kontroll Linuxi Käsurealt

Sertifikaadi kontrollimine on vajalik selleks, et veenduda, et see on allkirjastatud õige CA poolt ja sisaldab vajalikke andmeid.

### 5.1 CA ja Allkirja Kontroll (Server 2)

Kasutame OpenSSL-i, et vaadata, kes sertifikaadi allkirjastas (Issuer) ja milline on selle sisu.

| Kontrollitav fail | Asukoht | Märkus |
| :--- | :--- | :--- |
| **`server.crt`** | VEBISERVER | Lõplik serveri sertifikaat. |
| **`ca.crt`** | VEBISERVER | CA juursertifikaat. |

#### Samm-sammuline juhend:

1.  **Vaata serveri sertifikaadi sisu ja väljastajat (Issuer):**
    Selle käsu abil saad näha sertifikaadi omanikku (Subject), selle kehtivusaega ja kõige olulisemat – **väljastajat (Issuer)**, mis peab olema sinu loodud CA nimi.

    ```bash
    # VEBISERVERIS
    openssl x509 -in server.crt -text -noout
    ```

      * **Oodatav väljund:** Otsi rida **`Issuer:`** ja veendu, et see vastab sinu CA nimele, nt:
        `Issuer: C = EE, ST = Harjumaa, O = My Company, CN = My Test Root CA`
      * **Märkus:** Rida **`Subject:`** peaks vastama sinu domeeninimele (`minudomeen.local`).

2.  **Vaata CA juursertifikaadi sisu (CA allkiri):**
    Vaata nüüd CA juursertifikaadi sisu. Kuna see on ise allkirjastatud sertifikaat, peaks selle väljastaja (`Issuer`) olema sama mis sertifikaadi omanik (`Subject`). See on *self-signed* allkirja tunnus.

    ```bash
    # VEBISERVERIS
    openssl x509 -in ca.crt -text -noout
    ```

      * **Oodatav väljund:** Veendu, et **`Issuer:`** on sama mis **`Subject:`**, nt:
        `Issuer: C = EE, ST = Harjumaa, O = My Company, CN = My Test Root CA`
        `Subject: C = EE, ST = Harjumaa, O = My Company, CN = My Test Root CA`

3.  **Kontrolli Sertifikaadiahelat (Kas allkiri on usaldusväärne):**
    See käsk proovib krüptograafiliselt kontrollida, kas `server.crt` on allkirjastatud `ca.crt` poolt. See on **CA allkirja tegelik kontroll**.

    ```bash
    # VEBISERVERIS
    openssl verify -CAfile ca.crt server.crt
    ```

      * **Oodatav väljund:** Kui allkirjad sobivad, annab see tulemuse: **`server.crt: OK`**.
      * **Veateade näiteks:** Kui sa prooviksid seda käsku ilma `-CAfile ca.crt` parameetrita, ütleks OpenSSL, et ta ei usalda seda sertifikaati (kuna tal pole juur-CA-d, mida ta usaldaks).

### Kokkuvõte 🧐

  * **`openssl x509 -in ... -text -noout`** – Annab **tekstikujulise tõlgenduse** sertifikaadist, näidates, **kes on väljastaja (CA)**.
  * **`openssl verify -CAfile ...`** – **Krüptograafiliselt kontrollib** sertifikaadi (sh selle allkirja) kehtivust antud CA vastu.
