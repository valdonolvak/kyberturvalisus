**Sisaldab:**

* ajalooline taust (kust krüptograafia alguse sai),
* sümmeetriline krüptograafia (väga lahti seletatud),
* asümmeetriline krüptograafia (väga lahti seletatud),
* digiallkiri ja sertifikaadid,
* illustratiivsed skeemid (tekstipõhised diagrammid),
* 3 praktilist näidet,
* päriselulised paralleelid (et õpilane intuitiivselt mõistaks).

---

# 🛡️ ÕPPETÜKK: SÜMMEETRILINE JA ASÜMMEETRILINE KRÜPTOGRAAFIA

---

## 1. Krüptograafia ajalugu (kust see alguse sai?)

Krüptograafia on eksisteerinud **tuhandeid aastaid**.
Selle eesmärk on alati olnud *hoida infot salajas*.

### 🏛️ Antiikaeg — Caesar ja Caesar cipher

Julius Caesar kasutas **lihtsat tähede nihutamist** sõjaväe sõnumite peitmiseks:

> Iga täht nihutati 3 kohta edasi.

Näide:
`A → D`
`B → E`
`C → F`

**Sõnum "SALAJANE" → "VDODMDQH"**

See oli sümmeetriline krüpteerimine — *mõlemad pooled peavad teadma nihke arvu.*

---

### ⚙️ 20. sajand — Enigma (II maailmasõda)

Saksamaa kasutas **Enigma** krüptomasinat.
Masin krüpteeris sõnumid keerulise mehaanilise elektriskeemiga.

```
Tekst → Enigma (võti) → Mõistetamatu signaal
```

Britid (Alan Turing jt) murdsid selle.
See lühendas sõja pikkust ~2 aasta võrra.

> Krüptograafia ei ole ainult matemaatika — see on strateegiline jõud.

---

### 🖥️ Digiajastu — matemaatiline krüptograafia

1970. aastatel sündis **asümmeetriline krüptograafia** (RSA algoritm).
      See muutis maailma, sest:

* enam ei pea salajast võtit eelnevalt kokku leppima
* digiallkirjad muutusid võimalikuks

Täna kasutame krüptograafiat **iga päev**:

* HTTPS (pangad, e-poed)
* ID-kaart / Mobiil-ID / Smart-ID
* VPN-id
* failide krüptimine (BitLocker, VeraCrypt)

---

## 2. Sümmeetriline krüptograafia

**(Symmetric encryption — mõlemad pooled kasutavad sama võtit)**

### Põhiline idee

> *Üks võti krüpteerib ja sama võti dekrüpteerib.*

```
┌─────────┐        võti        ┌──────────────┐
│ saatja  │ ───────────────→   │   saaja      │
└─────────┘                    └──────────────┘
 Krüpteerimine          Sama võti        Dekrüpteerimine
```

### Igapäevaanalogia (lihtne selgitus õpilasele)

* Võti = **kapp** koos võtmega
* Kui võtme koopia läheb valesse kohta → kapis olev info on lekkinud

Kui saatja saadab sõnumi:

```
"Raha ülekanne 500€"
```

Ja kasutab AES algoritmi sümmeetrilise võtmega:

```
Võti: X9F7E2A15B...
Sõnum → AES → 9A B1 C2 11 F4 98 ...
```

► Saaja **peab omama sama võtit**.

---

### Miks see on hea?

✅ **Väga kiire** – sobib suurtele failidele, voogandmetele, ketaste krüptimiseks
✅ Võtmed on lühikesed (128bit, 256bit)

### Miks see on probleem?

❌ **Kuidas võtit jagada turvaliselt?**
Kui sõnumit pealt kuulatakse ja võti varastatakse, on kõik sõnumid loetavad.

---

### Peamised algoritmid (mis on päriselus kasutusel)

| Algoritm                               | Kus kasutatakse                   | Märkus                     |
| -------------------------------------- | --------------------------------- | -------------------------- |
| **AES** (Advanced Encryption Standard) | VPN, HTTPS sessioon, BitLocker    | standard                   |
| **ChaCha20**                           | Google, TLS-handshake alternatiiv | väga kiire mobiilis        |
| **DES**                                | *ajalooline*                      | ebaturvaline (56-bit võti) |

---

### **Näide 1 — AES ketta krüpteerimine (BitLocker / VeraCrypt)**

```
Ketas → [AES koos salajase võtmega] → kõik failid salas
Ilma võtmata pole võimalik andmeid taastada
```

Kui USB-pulk kaob → andmed on turvalised.

---

---

## 3. Asümmeetriline krüptograafia

**(Asymmetric encryption — kasutatakse kahte erinevat võtit)**

### Põhiline idee

> *Avaliku võtmega krüpteeritakse, salajase võtmega dekrüpteeritakse.*

```
Avalik võti (public key)  → jagatakse kõigile
Salajane võti (private key) → hoitakse enda teada
```

📌 *Avalik võti krüpteerib.*
📌 *Salajane võti dekrüpteerib.*

Skeem:

```
┌─────────────┐               ┌──────────────┐
│ saatja      │  ciphertext   │ saaja        │
│ kasutab     │────────────→  │ kasutab      │
│ AVALIKKU võtit              │ SALAJAST võtit
└─────────────┘               └──────────────┘
```

---

### Igapäevaanalogia

* Avalik võti = **postkast** (kõik võivad kirja sisse panna)
* Salajane võti = **võti postkasti avamiseks**

Keegi ei saa kirja välja võtta ilma võtmeta.

---

### Kus seda kasutatakse?

| Valdkond      | Kuidas kasutatakse                         |
| ------------- | ------------------------------------------ |
| HTTPS         | brauser kontrollib serveri sertifikaati    |
| Digiallkirjad | dokument allkirjastatakse salajase võtmega |
| Smart-ID      | privaatvõti elab turvalises serveris       |

---

### Peamised algoritmid

| Algoritm                              | Tase       | Info                             |
| ------------------------------------- | ---------- | -------------------------------- |
| **RSA**                               | klassika   | põhineb suurte arvude jagamisel  |
| **ECC** (elliptic curve cryptography) | kaasaegne  | sama turvalisus väiksema võtmega |
| **EdDSA**                             | väga kiire | kasutab ECC suure jõudlusega     |

---

### **Näide 2 — E-kirja turvaline saatmine (RSA + PGP)**

Jaan soovib saada krüpteeritud e-kirja.

1. Jaan jagab oma avalikku võtit.
2. Mari krüpteerib sõnumi Jaani avaliku võtmega.
3. Ainult Jaan saab seda lugeda, sest tal on salajane võti.

```
Mari → [krüpteerib AVALIKUGA] → krüpteeritud sõnum → Jaan
Jaan → [dekrüpteerib SALAJASEGA] → loeb sõnumi
```

---

---

## 4. Digiallkiri

*(asümmeetrilise krüptograafia erikasutus)*

Digiallkiri võimaldab tõestada, et:

1. dokument tuleb õigest inimesest (**autentsus**)
2. dokument pole muudetud (**terviklikkus**)
3. inimene ei saa hiljem väita, et ta *ei allkirjastanud* (**mitte-äraütlemine**)

### Digiallkirja protsess

```
Dokumendist luuakse räsi (hash)
↓
Räsi krüpteeritakse SALAJASE võtmega
↓
Saaja kontrollib seda AVALIKU võtmega
```

---

### **Näide 3 — Eesti ID-kaardi / Smart-ID digiallkiri**

Kui allkirjastad lepingu:

```
Dokumendist arvutatakse räsi (hash)
↓
Räsi krüpteeritakse sinu salajase võtmega (ID-kaardi sees)
↓
Saaja kasutab sinu avalikku võtit ja kontrollib
```

Eestis on see **juriidiliselt samaväärne** käsikirjaga.

---

---

## 5. Sümmeetrilise ja asümmeetrilise kombineerimine (HTTPS)

Kõige tähtsam osa:

> **HTTPS EI kasuta ainult asümmeetrilist krüptograafiat.**

HTTPS töötab nii:

1. Brauser loob ühenduse serveriga
   → kasutatakse **asümmeetrilist krüptograafiat** võtmevahetuseks

2. Edasine liiklus toimub
   → **sümmeetrilise krüptograafiaga** (AES)

```
Etapp 1: RSA / ECC (aeglane, turvaline)
Etapp 2: AES (väga kiire)
```

Skeem:

```
BRAUSER ------- RSA/ECC --------> SERVER  (vahetatakse AES võti)
BRAUSER ------- AES ------------> SERVER  (kiire andmevahetus)
```

---

## 6. Võrdlustabel

| Omadus      | Sümmeetriline                     | Asümmeetriline                 |
| ----------- | --------------------------------- | ------------------------------ |
| Võtmete arv | 1                                 | 2 (avalik + salajane)          |
| Kiirus      | ⚡ Väga kiire                      | 🐢 Aeglane                     |
| Kasutusjuht | andmevoog, failid                 | võtmevahetus, digiallkiri      |
| Risk        | võtme vargus = kõik andmed avatud | salajase võtme kaotus = ka oht |

---

## Kokkuvõte (mida peab meeles hoidma)

* **sümmeetriline** = *üks võti*, kiire
* **asümmeetriline** = *avalik + salajane võti*, digiallkiri
* **HTTPS kombineerib mõlemat**

Krüptograafia on tänapäeva küberturbe alus.
Ilma selleta poleks meil:

* e-panka
* riiklikku e-identiteeti
* turvalist internetti

---

### ✅ Kui soovid, saan järgmisena luua:

* PowerPoint slaidid selle põhjal
* PDF konspekti joonistega
* praktilised harjutused (Python AES, RSA)

❓ Kas soovid **slaide** või **praktiilisi ülesandeid (koodinäidetega)**?
