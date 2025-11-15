

---

# ⭐ Põhjalik samm-sammuline NÄIDISÜLESANNE

**Teema:** *Active Directory (AD) ja DHCP teenuste riskianalüüs*
**Ülesanne 1 – Lähteteksti pikendatud ja detailne variant**

---

# **1. Riskide kogumine (Risk identification)**

Riskianalüüsi esimene etapp on **riskide süstemaatiline kogumine**. Kuna AD (Active Directory) ja DHCP (Dynamic Host Configuration Protocol) on ettevõtte võrgu **kriitilised teenused**, siis riskide otsimine jagatakse kategooriatesse:

### **Kategooriad selgitustega**

#### **1) Tehnilised riskid**

Viitavad riistvarale või tarkvarale, nt:

* serveri ketta rike
* virtuaalmasina kahjustumine
* RAM-i rike
* võrgukaardi rike
* tarkvaraline crash (nt LSASS.exe crash → DC ei tööta)

#### **2) Konfiguratsiooniriskid**

Keskkonna või teenuse seadistusega seotud riskid:

* DHCP-s on vale IP vahemik või gateway
* DNS-is on vigased A/AAAA kirjed
* AD replikatsioon pole õigesti seadistatud
* vigased GPO seaded

#### **3) Turvariskid**

Ohud, mis tulenevad rünnakutest või nõrkadest õigustest:

* nõrgad paroolid
* RDP avalikult internetis
* AD konto mitte lukustamise poliitika
* DNS poisoning
* pahatahtlik sisekasutaja

#### **4) Inimfaktor (Human factor)**

Kasutajate või administraatorite eksimused:

* AD objektide vale kustutamine
* DHCP scope kogemata kinni pandud
* vigane GPO muudatus
* logide ignoreerimine

#### **5) Teenuse kättesaadavus (Service availability)**

Teenuse terviklikkus ja töökindlus:

* üksainus domeenikontroller
* DHCP ja DNS mõlemad ainult ühel serveril
* puudub varundus
* hooldustööd ilma fallbackita

**Õpetaja märkus:**
*Siin on kasulik lasta õpilastel joonistada AD+DHCP teenuste skeem, et nad näeksid teenuste omavahelisi seoseid (AD → DNS → DHCP → kliendid).*

---

# **2. Riskide kirjeldamine (Risk description)**

Iga risk tuleb analüüsida läbi kolme põhisammu:

1. **Tõenäosus (1–5)**
2. **Mõju (1–5)**
3. **Riskitase = Tõenäosus × Mõju**

Allpool on näited koos täiendatud tehniliste selgitustega.

---

## **RISK 1: AD domeenikontrolleri (DC1) ketta riknemine**

* **Tõenäosus:** 3
* **Mõju:** 5
* **Riskitase:** 15

### **Analüüs (detailne)**

* DC1 on ettevõtte **primaarne domeenikontroller (Primary Domain Controller Emulator – PDCe)**.
* Samuti töötab sellel **DNS** (Directory-Integrated DNS) ja **DHCP**.
* Kui **üksik ketas (single disk scenario)** rikki läheb, võib:

  * AD autentimine peatuda
  * DNS ei vasta → kliendid ei leia domeeni teenuseid
  * DHCP ei jaga enam IP-aadresse
  * GPO-d ei rakendu
* Kui DC1 on **vanas Proxmoxi klastris**, võib rike põhjustada mitmetunnise teenuse katkemise.

### **Leevendus (mitmeastmeline)**

* **RAID1 või RAID10** kasutamine domeenikontrolleri VM-i hostis.
* **Teise domeenikontrolleri (DC2)** olemasolu, mis hoiab AD replikatsioone (NTDS replication).
* **Regulaarsed AD varukoopiad** (System State Backup).
* Kontroll, et **SYSVOL replikatsioon (DFSR)** töötab korrektselt.
* Proxmoxi tasemel kasutada **HA** (kui olemas).

**Märkus:** Õpilastele näidata käsku:
`repadmin /replsummary`

---

## **RISK 2: DHCP server ei jaga IP-sid (teenus maas või vale konfiguratsioon)**

* **Tõenäosus:** 3
* **Mõju:** 4
* **Riskitase:** 12

### **Analüüs (detailne)**

* Kui kliendid ei saa IP-d, siis:

  * nad ei saa **DNS serverit**,
  * ei leia **domeeni kontrollereid**,
  * ei pääse ligi **failiserveritele, printeritele ja siseveebidele**.
* Ühised probleemid:

  * vale **DHCP scope**
  * vale **gateway**
  * DHCP teenus peatunud (nt Windows Update restart)
  * DHCP pole volitatud (DHCP Authorization AD-s)

### **Leevendus**

* DHCP **Failover mode** DC1 ↔ DC2 vahel (Load Balance 50/50 või Hot Standby).
* Regulaarne DHCP konfi testimine:
  `ipconfig /renew`
* Scope Reservation’i ja Pool’i korrektne kasutamine.
* **Zabbix monitooring** DHCP teenusele, failover tšekid.

---

## **RISK 3: DNS konfiguratsiooni vigane kirje**

* **Tõenäosus:** 4
* **Mõju:** 4
* **Riskitase:** 16

### **Analüüs (detailne)**

AD töötab täielikult DNS-ist. Domeenikontrollerid registreerivad SRV kirjeid:

* `_ldap._tcp.dc._msdcs.<domain>`
* `_kerberos._tcp.<domain>`

Kui DNS-is on:

* vale domeeni nimi
* kustutatud SRV kirje
* vale forwarder
* topelt A kirje masinale
  siis:
* GPO-d ei rakendu
* kasutajad ei logi domeeni
* serverid ei leia üksteist

### **Leevendus**

* DNS testid tööks:
  `dcdiag /test:dns`
* DNS muudatuste logimine (auditpol, Event Viewer → DNS logs).
* Range **muudatuste halduse protsess** (Change Management).

---

# **3. Riskiregistri vorm (Risk register)**

Riskiandmed koostatakse tabelisse:

| ID | Risk                  | Tõen. | Mõju | Riskitase | Leevendused                  | Omanik |
| -- | --------------------- | ----- | ---- | --------- | ---------------------------- | ------ |
| R1 | DC1 ketta rike        | 3     | 5    | 15        | RAID1, DC2 olemasolu, backup | Admin  |
| R2 | DHCP ei jaga aadresse | 3     | 4    | 12        | Failover, testid             | Admin  |
| R3 | DNS vale kirje        | 4     | 4    | 16        | Muudatuste haldus, testid    | Admin  |

**Õpetaja märkus:**
*Siin võib lasta õpilastel teha Exceli tabeli + lisada värvikoodid (punane = kõrge risk).*

---

# **4. Leevenduskava koostamine**

Järgmine samm on **reaktsiooniplaan** riskide realiseerumisel.

Allpool toodud **näide DC1 rikke käsitlemiseks** on täiendatud tehniliste detailidega.

---

## **Leevenduskava – DC1 rike (detailne)**

### **1. Kontrolli Proxmoxi VM-i olekut**

* kas masin on *running / stopped / suspended*
* kas Proxmoxi logides on *I/O error*

### **2. Kui masin ei käivitu → kontrolli RAID seisu**

* Proxmox → *Disks → ZFS / LVM RAID*
* käsud:

  * `zpool status` (ZFS)
  * `cat /proc/mdstat` (mdadm RAID)

### **3. DHCP koormuse ületoomine DC2 peale**

* kui kasutusel on Load Balance, siis DC2 teenindab kliente automaatselt
* kui kasutusel Hot-Standby, siis aktiveerida partner pool

### **4. Kontrolli AD replikatsiooni DC2-s**

* `repadmin /replsummary`
* `repadmin /showrepl`
* Event Viewer → *Directory Service Logs*

### **5. Vajadusel taastada AD backupist**

* System State Backup → taastada NTDS.dit
* kontrollida SYSVOL-i (DFSR)
* vajadusel käivitada **authoritative restore**

### **6. Taaskäivita teenused**

* `net stop netlogon` → `net start netlogon`
* `ipconfig /registerdns`
* DNS teenuse restart

**Õpilastele praktikaülesanne:**
*Mängige läbi stsenaarium: DHCP/DC1 võetakse maha → kuidas võrgu seis muutub?*

---

# **5. Kontrollküsimustik (õppija testimiseks)**

1. **Mis on kõige suurema mõjuga AD risk?**
   – DNS rikked, sest AD sõltub täielikult DNS-ist.

2. **Milline risk puudutab kasutajate autentimist?**
   – Domeenikontrolleri kättesaamatus, DNS probleemid.

3. **Kuidas testida DNS korrektsust AD keskkonnas?**
   – `dcdiag /test:dns`

4. **Mis juhtub, kui DHCP ei tööta?**
   – Kliendid ei saa IP-aadresse → ei pääse domeeni → ei saa võrku.

5. **Miks on vaja kahte domeenikontrollerit?**
   – Redundants, kõrge kättesaadavus, replikatsioon, teenuste järjepidevus.

---

Kui soovid, võin:

✅ lisada veel **10 täiendavat AD+DHCP riski**
✅ koostada **täieliku 4–6 lehe pikkuse riskianalüüsi dokumendi PDF-is**
✅ teha **slaidid õpetamiseks**
✅ koostada **kontrolltöö** selle teema põhjal

Ütle lihtsalt, mida vajad!
