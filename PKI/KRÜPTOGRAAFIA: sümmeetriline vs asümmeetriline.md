---

# 🛡️ KRÜPTOGRAAFIA: sümmeetriline vs asümmeetriline

*(Õppematerjal IT õpilastele)*

---

## 1. Mis on krüptograafia?

**Krüptograafia** on meetod andmete kaitsmiseks nii, et need oleksid loetavad ainult autoriseeritud kasutajale.

🎯 Krüptograafia neli peamist eesmärki:

| Mõiste            | Selgitus                                                   |
| ----------------- | ---------------------------------------------------------- |
| Konfidentsiaalsus | Ainult õiged inimesed saavad infot lugeda                  |
| Terviklikkus      | Andmeid ei ole muudetud                                    |
| Autentsus         | Saatja isik on tuvastatud                                  |
| Mitte-äraütlemine | Saatja ei saa hiljem eitada, et ta saatis või allkirjastas |

Krüpteerimine = andmete muutmine loetamatuks ✅
Dekrüpteerimine = andmete taastamine algseks ❓

---

## 2. SÜMMEETRILINE KRÜPTOGRAAFIA (symmetric encryption)

### Põhimõte

👉 Kasutab **ühist salajast võtit** (shared key).

```
Saatja + salajane võti → [KRÜPTEERIMINE] → krüpteeritud andmed
Saaja + sama salajane võti → [DEKRÜPTEERIMINE] → algne tekst
```

---

### Plussid ja miinused

|                                                      | Sümmeetriline krüptograafia |
| ---------------------------------------------------- | --------------------------- |
| ✅ Väga kiire (sobib suurtele failidele)              |                             |
| ✅ Võti lühike (128-bit, 256-bit)                     |                             |
| ❌ Peamine probleem: kuidas võtit turvaliselt jagada? |                             |

Kujuta ette: **õpilane saadab failid õpetajale krüpteeritult**, aga peab talle *kuidagi võtit edastama*.
Kui see võti lekib → kõik failid on kadunud.

---

### Kus seda päriselt kasutatakse?

| Rakendus                                     | Põhjus                                        |
| -------------------------------------------- | --------------------------------------------- |
| VPN ühendused                                | Andmevoog suur, vaja kiirust                  |
| Failide krüpteerimine (BitLocker, VeraCrypt) | Krüpteeritakse tervet ketast                  |
| HTTPS krüpteerimine (pärast handshake’i)     | Sessiooni andmevahetus toimub sümmeetriliselt |

---

### ⚙️ Populaarsed algoritmid:

| Algoritm                               | Turvalisus     | Märkused                                    |
| -------------------------------------- | -------------- | ------------------------------------------- |
| **AES** (Advanced Encryption Standard) | väga turvaline | Tänapäevane standard (militaartase AES-256) |
| DES                                    | ebaturvaline   | liiga lühike võti (56-bit), murdetav        |
| ChaCha20                               | turvaline      | kasutusel mobiilis ja VPN-ides              |

---

### ➤ **Näide 1: AES krüpteerimine (lihtsustatud loogika)**

**Sisendtekst:**
`"Mul on eksamitöö tulemused"`

**AES-256 võtme näide (random):**
`A97F5DCCE1BF7636AAE2F...`

➡️ Krüpteerimise tulemus (ciphertext):
`7F5B9C89AA9E1823FA4AD81C...`

➡️ Sama võti dekrüpteerib tagasi:
`"Mul on eksamitöö tulemused"`

**Kui keegi võtme teada saab → saab kõike lugeda.**
Sellepärast võtit hoitakse *väga salajas*.

---

---

## 3. ASÜMMEETRILINE KRÜPTOGRAAFIA (asymmetric encryption)

### Põhimõte

👉 Kasutab **kahte võtit**:

| Võti                            | Kes omab          | Milleks                   |
| ------------------------------- | ----------------- | ------------------------- |
| **Avalik võti (public key)**    | jagatakse kõigile | andmete krüpteerimiseks   |
| **Salajane võti (private key)** | jääb omanikule    | andmete dekrüpteerimiseks |

```
Avalik võti → krüpteerib
Salajane võti → dekrüpteerib
```

---

### Plussid ja miinused

|                                        | Asümmeetriline krüptograafia |
| -------------------------------------- | ---------------------------- |
| ✅ Võtit ei pea salaja jagama           |                              |
| ✅ Saab teha digiallkirju (sym ei saa)  |                              |
| ❌ Aeglane (ei sobi suurtele failidele) |                              |

---

### Kus seda kasutatakse?

| Rakendus                                    | Kasutusviis                             |
| ------------------------------------------- | --------------------------------------- |
| Digiallkiri (ID-kaart, Smart-ID, Mobiil-ID) | sõnum allkirjastatakse salajase võtmega |
| HTTPS (TLS handshake)                       | brauser vahetab serveriga võtmeid       |
| SSH sisse logimine                          | autentimine võtme abil                  |

---

### ⚙️ Populaarsed algoritmid:

| Algoritm                              | Kasutus                                |
| ------------------------------------- | -------------------------------------- |
| **RSA**                               | laialt levinud, turvaline              |
| **ECC (Elliptic Curve Cryptography)** | uuem, sama turvalisus väiksema võtmega |
| **EdDSA**                             | eriti kiire digiallkirjade tegemisel   |

---

### ➤ **Näide 2: Turvaline e-kirja saatmine (RSA)**

Oletame: Mari saadab Jaanile konfidentsiaalse faili.

1. Jaan avaldab oma **avaliku võtme**.
2. Mari krüpteerib faili Jaani **avaliku võtmega**.
3. Jaan dekrüpteerib faili **salajase võtmega**.

```
Maril: avalik võti (Jaanilt) + fail → [KRÜPTEERIMINE] → ciphertext
Jaanil: ciphertext + salajane võti → [DEKRÜPTEERIMINE] → algne fail
```

Kui keegi vahepealt failile ligi saab → **ei saa lugeda**, sest puudub salajane võti.

---

---

## 4. DIGIALLKIRI

*(asümmeetrilise krüptograafia erikasutus)*

Digiallkiri **ei krüpteeri dokumenti** — see tõendab:

| Omadus            | Mis see tähendab?                               |
| ----------------- | ----------------------------------------------- |
| Autentsus         | allkirjastaja on tuvastatav                     |
| Terviklikkus      | dokumenti ei ole muudetud                       |
| Mitte-äraütlemine | isik ei saa öelda: "Ma ei allkirjastanud seda." |

---

### Kuidas digiallkiri töötab?

1. Dokumendist luuakse **räsi** (hash) — nt SHA-256:

```
"leping.pdf" → SHA-256 →  8A7F…FA3C (fikseeritud pikkusega räsi)
```

2. Räsi krüpteeritakse **salajase võtmega** → see on digiallkiri.

3. Kontrollija kasutab **avalikku võtit**, et veenduda, et:

   * räsi kehtib,
   * dokumenti pole muudetud,
   * allkiri kuulub omanikule.

---

### ➤ **Näide 3: Eesti digiallkiri (ID-kaart / Smart-ID)**

```
[Dokumendist luuakse SHA-256 räsi]
↓
[Räsi krüpteeritakse salajase võtmega → digiallkiri .bdoc failis]
↓
[Saaja kontrollib avaliku võtmega → ID-kaardi sertifikaadiga]
```

Eestis on digiallkiri **juriidiliselt võrdne** käsikirjaga.

---

---

## 5. MIKS HTTPS KASUTAB KAHET KRÜPTOGRAAFIA TÜÜPI?

HTTPS loob ühenduse nii:

| Etapp            | Kasutab                                  |
| ---------------- | ---------------------------------------- |
| 1. TLS handshake | asümmeetriline krüptograafia (RSA / ECC) |
| 2. Andmevahetus  | sümmeetriline krüptograafia (AES)        |

🔎 põhjus:
Asümmeetriline on turvalisem, sümmeetriline kiiresti töötav.

---

### Hübriidkrüpteerimise skeem (HTTPS)

```
BRAUSER ---- RSA/ECC ----> SERVER (vahetatakse võti)
BRAUSER ---- AES ---------> SERVER (andmevahetus)
```

---

## 6. Tabel: sümmeetriline vs asümmeetriline

| Omadus       | Sümmeetriline      | Asümmeetriline                 |
| ------------ | ------------------ | ------------------------------ |
| Võtmete arv  | 1 võti             | 2 võtit (avalik + salajane)    |
| Kiirus       | väga kiire         | aeglane                        |
| Sobib        | suurtele failidele | võtmevahetuseks, digiallkirjad |
| Peamine risk | võtme jagamine     | salajase võtme kaotus          |

---

# ✅ Kokkuvõte (esitamiseks slaididel)

* Sümmeetriline → **üks võti, kiire**, probleem on võti.
* Asümmeetriline → **avalik + salajane võti**, võimaldab **digiallkirju**.
* HTTPS kasutab *mõlemat*.

---

