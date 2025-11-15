**Tüüpiline IT-turberiskide hindamise raamistik**. Siin kasutatakse **E-ITS**-i ja tavapärase **riskimaatriksi** põhimõtteid, et anda selged kategooriad + seletused.

---

# 🎯 **1. Mõju (Impact) kategooriad – selgitustega**

Mõju hinnatakse tavaliselt skaalal **1–5**, kus 1 = tühine ja 5 = katastroofiline.
Allpool on tüüpiline definitsioon (sobib AD/DC riskide hindamiseks):

### **5 – Katastroofiline mõju**

* Teenused lakkavad töötamast kogu organisatsioonis.
* Tekib muu kriitiline seisak (nt autentimine, sisselogimine, failiserverid, VPN).
* Väga suur mainekahju.
* Vajalik on kiire taastamine (RTO < 4h).
* Võimalik, et vajalik on erakorraline kriisijuhtimine.

### **4 – Suur mõju**

* Mitmed ärikriitilised teenused katkestatud (nt AD/DNS häired).
* Osadel kasutajatel töö täielikult häiritud.
* Taastamine võtab aega mitu tundi kuni päeva.
* Mõõdukas mainekahju.

### **3 – Mõõdukas mõju**

* Olulised, kuid mitte ärikriitilised teenused on häiritud.
* Mõned kasutajad ei saa tööd jätkata.
* Taastamine kuni 1 päev.
* Vajalik tehniline sekkumine, kuid mitte kriisi tasemel.

### **2 – Väike mõju**

* Mõned funktsioonid aeglustuvad või muutuvad ebastabiilseks.
* Enamus kasutajaid saavad siiski tööd jätkata.
* Taastamine tavapärases hooldusaknas.

### **1 – Tühine mõju**

* Vormistuslikud vead, mis ei mõjuta teenuse toimimist.
* Kasutajad tõrkeid ei märka.
* Parandus võimalik jooksvalt.

---

# 🎯 **2. Tõenäosuse (Likelihood) kategooriad – selgitustega**

Tavapärane skaala samuti **1–5**, kus 1 = väga ebatõenäoline ja 5 = väga tõenäoline.

### **5 – Väga tõenäoline**

* Juhtub peaaegu kindlasti 1× aasta jooksul.
* Sarnane intsident on juba korduvalt esinenud.
* Tehniline platvorm on vananenud või defektne.

### **4 – Tõenäoline**

* Võib juhtuda kord 1–2 aasta jooksul.
* Tehnilised probleemid on teada.
* On varasemad hoiatavad sümptomid / logid.

### **3 – Võimalik**

* Võib juhtuda 1× 2–5 aasta jooksul.
* Tavaline tehniline risk (nt üksikketta rikke oht RAID0 puhul).
* Ei ole palju varasemaid intsidente, aga risk on realistlik.

### **2 – Vähe tõenäoline**

* Juhtub vaid harva (kord 5–10 aasta jooksul).
* On mingid kaitsemeetmed olemas.
* Platvorm on stabiilne.

### **1 – Väga ebatõenäoline**

* Teoreetiline oht.
* Hea disain + tugevad kontrollimeetmed muudavad juhtumise pea võimatuks.

---

# 🎯 **3. Riskitaseme (Risk Level) arvutamine**

**Riskitase = Tõenäosus × Mõju**

Tavaline skaala IT-riskianalüüsis:

| Mõju \ Tõenäosus      | 1 | 2  | 3  | 4  | 5  |
| --------------------- | - | -- | -- | -- | -- |
| **1–Tühine**          | 1 | 2  | 3  | 4  | 5  |
| **2–Väike**           | 2 | 4  | 6  | 8  | 10 |
| **3–Mõõdukas**        | 3 | 6  | 9  | 12 | 15 |
| **4–Suur**            | 4 | 8  | 12 | 16 | 20 |
| **5–Katastroofiline** | 5 | 10 | 15 | 20 | 25 |

Sinu näide:

* **Tõenäosus: 3 (Võimalik)**
* **Mõju: 5 (Katastroofiline)**
  👉 **Riskitase = 3 × 5 = 15**

---

# 🎯 **4. Riskitase → Kriitilisuse skaala (Risk Severity)**

Kasutan 5-astmelist tõlgendust, mida kasutavad paljud IT-organisatsioonid ja sobib E-ITS raamistikuga:

### **20–25: KRIITILINE**

* Teenus võib täielikult seiskuda.
* Kohene tegevus kohustuslik.
* Vajalik riskide juhtimise komitee / kriisijuhtimine.

### **15–19: VÄGA KÕRGE**

* Äritegevus on tõsises ohus.
* Vajalik kiire leevendus (nädala jooksul).
* Tegevused ja investeeringud tuleb planeerida prioriteediga 1.

### **10–14: KESKMINE / KÕRGE**

* Nõuab tähelepanu.
* Tuleb planeerida leevendused (1–3 kuu jooksul).
* Regulaarne järelevalve.

### **5–9: MADAL**

* Võib delegeerida IT-administraatoritele.
* Leevendused soovi korral.
* Jälgida kord aastas.

### **1–4: VÄGA MADAL**

* Dokumenteerida ja jätta alles.
* Ei vaja sekkumist.

Sinu riskiskoor **15** => **Kategooria: Väga kõrge risk**.

---

# 🎯 **5. Näitlik riskikirjeldus (kooskõlas kategooriatega)**

**RISK 1: AD domeenikontrolleri (DC1) ketta riknemine**

* **Tõenäosus: 3 – Võimalik**
* **Mõju: 5 – Katastroofiline**
* **Riskitase: 15 (Väga kõrge)**
* **Analüüs:** DC1 on primaarne domeenikontroller, DHCP ja DNS server. Ketta rike põhjustab autentimise seiskumise, nimeteenuse katkestuse ja potentsiaalselt täieliku AD teenuse seisaku.
* **Leevendusmeetmed:**

  * RAID1/RAID10 massiivid
  * Teise domeenikontrolleri (DC2) olemasolu
  * Regulaarne AD + SYSVOL backup
  * Kontroll, et AD replikatsioon toimib (DFSR, USN rollback kaitse, replikatsiooni tervis)

---
