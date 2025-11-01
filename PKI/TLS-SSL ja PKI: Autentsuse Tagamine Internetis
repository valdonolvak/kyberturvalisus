Õppematerjal, mis keskendub **TLS/SSL-i toimimisele** ja **PKI (X.509 sertifikaatide ja CA-de) rollile** autentimisel.

---

## 🔐 TLS/SSL ja PKI: Autentsuse Tagamine Internetis

### 1. Sissejuhatus TLS/SSL-i (Transport Layer Security)

**TLS** (ja selle eelkäija, vananenud SSL) on krüptograafiline protokoll, mis loob turvalise sidekanali kahe osapoole (nt veebibrauseri ja serveri) vahel internetis. TLS tagab kolm peamist turvaomadust, mis on hädavajalikud **HTTPS**-i (HTTP üle TLS-i) jaoks:

* **Konfidentsiaalsus:** Andmed on krüpteeritud ja loetamatud kõrvalistele osapooltele.
* **Terviklikkus:** Tagatud on, et andmeid pole edastamise käigus muudetud.
* **Autentsus:** Mõlemad osapooled (tavaliselt klient autentib serveri) on tõendanud oma identiteeti.

---

### 2. Sertifitseerimisasutused (CA) ja PKI

**Avaliku Võtme Infrastruktuur (PKI)** on raamistik, mis reguleerib avalike võtmete loomist, haldamist, levitamist, kasutamist, talletamist ja tühistamist. PKI süda on **Sertifitseerimisasutus (CA)**.

#### CA Põhifunktsioonid:

1.  **Identiteedi Kinnitamine:** Kui serveri omanik soovib sertifikaati (nt domeenile `example.com`), kontrollib CA rangelt, kas taotleja on tõepoolest selle domeeni õiguspärane omanik.
2.  **Allkirjastamine:** Pärast identiteedi kinnitamist kasutab CA oma **salajast (privaatset) võtit**, et luua taotleja sertifikaadile **digitaalallkiri**.
3.  **Usaldusahela Loomine:** CA-d loovad hierarhilise ahela. Klientide seadmed (brauserid, operatsioonisüsteemid) usaldavad vaid **väikest hulka Juur-CA-sid**, mis on nende süsteemidesse eelinstalleeritud. Kui sertifikaat on allkirjastatud usaldatud CA-st lähtuva ahela kaudu, loetakse ka see sertifikaat usaldusväärseks.

---

### 3. Digitaalsertifikaadid: X.509 Standard

Digitaalsertifikaadid on digitaalsed dokumendid, mis seovad avaliku võtme identiteediga. Enamik tänapäevaseid sertifikaate järgib standardit **X.509**.

#### X.509 Sertifikaadi Kriitilised Väljad:

| Väli | Sisu | Olulisus |
| :--- | :--- | :--- |
| **Omanik (Subject)** | Sertifikaadi omanik, nt domeeninimi (`example.com`). | Brauser kontrollib, kas see vastab lehekülje URL-ile. |
| **Omaniku Avalik Võti** | Võti, mida brauser kasutab turvaliseks võtmevahetuseks ja serveri poolt allkirjastatud andmete dekrüpteerimiseks. | Krüptograafiline alus. |
| **Väljastaja (Issuer)** | Sertifikaadi väljastanud CA nimi. | Brauser kasutab seda nime CA usaldusahelas leidmiseks. |
| **Kehtivus:** | Sertifikaadi kehtivuse algus- ja lõppkuupäev. | Turvameede aegunud sertifikaatide vastu. |
| **CA Allkiri:** | CA privaatvõtmega krüpteeritud sertifikaadi räsi. | **Tõendab autentsust**; kinnitab, et CA on sertifikaadi sisu heaks kiitnud ja et seda pole rikutud. |

---

### 4. Autentsuse Kontroll TLS Käepigistuse Ajal

Autentsuse kontroll on TLS Käepigistuse **algusfaas** ja see toimub automaatselt kliendi (brauseri) poolt:

#### Samm 1: Sertifikaadi Saatmine
Server vastab Kliendi "Tere" teatele, saates oma **X.509 sertifikaadi** (mis sisaldab serveri avalikku võtit).

#### Samm 2: Brauseri Kontrollid (Autentsus ja Terviklikkus)

Brauser teeb automaatselt kolm kriitilist kontrolli enne jätkamist:

1.  **Ahela Usaldus (PKI kontroll):** Brauser proovib leida sertifikaadi väljastaja (`Issuer`) oma **usaldatud CA-de hulgast**. Kui tegemist on vahepealse sertifikaadiga, üritab brauser luua katkematu ahela tagasi usaldatud **Juur-CA-ni**.
2.  **Allkirja Krüptograafiline Kontroll:** Brauser kasutab CA avalikku võtit (mis on eelinstalleeritud usaldushoidlasse) serveri sertifikaadi **CA Allkirja dekrüpteerimiseks**.
    * Kui dekrüpteeritud allkiri sobib sertifikaadi sisu reaalajas arvutatud räsiga, on tõestatud, et sertifikaat on **ehtne** ja seda pole pärast CA allkirjastamist muudetud.
3.  **Domeeni Kehtivus:** Brauser kontrollib, kas sertifikaadi väljal **Subject** või **Subject Alternative Name (SAN)** olev domeeninimi vastab URL-ile, millega ta ühendust luua püüab.

#### Lõpptulemus

* **Edukalt:** Kui kõik kontrollid õnnestuvad, usaldab brauser serveri identiteeti. Järgneb turvaline sümmeetriline võtmevahetus, ja ühendus luuakse.
* **Viga:** Kui kontrollid nurjuvad (nt allkiri ei klapi, sertifikaat on aegunud või CA on ebausaldusväärne), peatab brauser ühenduse ja näitab kasutajale turvahoiatust.

---
Kokkuvõtlik võrdlus **TLS 1.2** ja **TLS 1.3** versioonide peamistest erinevustest.

---

## 5. 🚀 TLS Versioonide Võrdlus: TLS 1.2 vs. TLS 1.3

Kuigi TLS 1.2 oli pikka aega standard, toob **TLS 1.3** kaasa olulisi parandusi nii **kiiruses** kui ka **turvalisuses**. TLS 1.3 standardiseeriti 2018. aastal.

### A. Peamised Erinevused ja Eelised

| Funktsioon | TLS 1.2 | TLS 1.3 | Tähtsus IT-s |
| :--- | :--- | :--- | :--- |
| **Käepigistuse aeg** | Nõuab **kaks edasi-tagasi** liikumist (2-RTT). | Nõuab vaid **ühte edasi-tagasi** liikumist (1-RTT). | **Suurem kiirus.** Vähendab latentsust, parandab veebilehtede laadimisaega. |
| **Šifrikomplektid** | Toetab laia valikut šifreid, sh vananenud ja haavatavaid algoritmide kombinatsioone. | **Eemaldas kõik nõrgad ja vananenud šifrid** (nt SHA-1, 3DES, RC4). | **Suurem turvalisus.** Välistab tuntud ründepunktid (nt DROWN rünne). |
| **Võtmevahetus** | Toetab nii haavatavaid RSA võtmevahetusmeetodeid kui ka **PFS-i** (Perfect Forward Secrecy) toetavaid DH/ECDH meetodeid. | Nõuab **PFS-i kasutamist** (Diffie-Hellman või elliptiliste kõverate variant). | **Parem privaatsus.** Kui pikemaajaline privaatvõti lekib, ei saa ründaja varasemaid sessioone dekrüpteerida. |
| **Sertifikaatide Allkirjastamine**| Kasutab SHA-1 räsisid (aegunud standard). | Nõuab turvalisi räsisid (nt **SHA-256** ja SHA-384). | **Parem terviklikkus.** Tugevam kaitse ründajate vastu, kes võiksid püüda räsi kokku põrgata. |
| **0-RTT taasalustamine**| Ei toeta. | **Toetab** 0-RTT taasalustamist. | **Väga kiire korduvühendus.** Korduvühenduse saab luua *null* edasi-tagasi liikumise ajaga. |

### B. Peamine Turvalisuse Eelis (PFS ja Haavatavuste Eemaldamine)

TLS 1.3 peamine eelis ei ole ainult kiirus, vaid **radikaalne turvalisuse tõstmine** disaini tasemel:

1.  **Nõrkade Algoritmide Eemaldamine:** TLS 1.2 puhul oli levinud probleem, et isegi kui server toetas tugevaid šifreid, võisid kliendid või vahendajad sundida ühendust kasutama nõrka šifrit (nn *downgrade attack*). TLS 1.3 **eemaldas** need nõrgad algoritmid standardist täielikult, muutes sellised rünnakud võimatuks.
2.  **Kohustuslik PFS (Perfect Forward Secrecy):** TLS 1.3 muudab PFS-i kohustuslikuks. See tähendab, et iga loodud sessioonivõti on **unikaalne** ja **lühiajaline**. Isegi kui ründaja suudab hiljem kätte saada serveri **püsiva privaatvõtme** (näiteks RSA-võtme), ei saa ta dekrüpteerida varem salvestatud TLS-sessioone, kuna need loodi ajutise Diffie-Hellmani (DH) võtmega, mida enam ei eksisteeri.
