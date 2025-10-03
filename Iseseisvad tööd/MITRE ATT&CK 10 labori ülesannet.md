**MITRE ATT&CK** raamistiku kasutamine laboriülesannetes annab sügava ja standardiseeritud arusaama ründaja mõtteviisist. See võimaldab harjutada mitte ainult tööriistade kasutamist, vaid ka **ründaja TTP-de (Tactics, Techniques, and Procedures)** tuvastamist ja leevendamist.

Siin on 10 laboriülesannet, mis on seotud teemadega (Phishing, DDoS, Lunavara) ja teiste oluliste valdkondadega, kus iga ülesanne on mõeldud 3-tunniseks põhjalikuks lahendamiseks.

---

## 10 Laboriülesannet, mis põhinevad MITRE ATT&CK Raamistikul

Iga ülesanne nõuab juurdepääsu turvalisele keskkonnale (nt. Kali Linux, Windows Server VM-id, võrguseire tööriistad) ja keskendub konkreetsetele ATT&CK taktikatele ja tehnikatele.

| Nr. | Teema | ATT&CK Taktika (Tactic) | ATT&CK Tehnika (Technique) | Eesmärk (3 tundi) |
| :---: | :--- | :--- | :--- | :--- |
| **1** | **Phishing'u ettevalmistus** | Reconnaissance (TA0043) | Active Scanning (T1595) & Phishing (T1598) | **Koosta sihipärane õngitsusmeil** ja selleks vajalikud eeluuringu andmed (OSINT) konkreetse töötaja kohta. |
| **2** | **Algne Sissetung** | Initial Access (TA0001) | Valid Accounts (T1078) & Exploit Public-Facing Application (T1190) | Kasutades lihtsaid paroolinimekirju, **kompromiteeri** avaliku teenuse (nt. vananenud FTP/Telnet) abil **üks konto**. |
| **3** | **Parooli räsikute püüdmine** | Credential Access (TA0006) | OS Credential Dumping (T1003) | Kasutades tööriistu (nt. Mimikatz, seadustatud *PowerShell* skriptid), **ekstraheeri parooli räsikud** Windowsi harjutusmasina mälust (LSASS). |
| **4** | **Lunavara leviku jäljendamine** | Lateral Movement (TA0008) | Remote Services (T1021) | Pärast esmast ligipääsu **liigu edasi** (nt. *PsExec* või *WMI* abil) ühest harjutusmasinast teise ja demonstreeri kaugkäsu käivitamist. |
| **5** | **Domeeni Dominatsioon** | Defense Evasion (TA0005) & Persistence (TA0003) | Account Manipulation (T1098) | Pääse Domeeni Kontrolleri masinale ligi ja **loo uus varukasutaja** (Backdoor Account), mis on peidetud administraatoriõigustega. |
| **6** | **DDoS ettevalmistus** | Resource Development (TA0042) | Develop Capabilities (T1587) | Uuri ja dokumenteeri, kuidas luuakse Botneti tarbeks **võimendusserver** (nt. Memcached või DNS serveri vale konfiguratsioon) ning leevenda seda. |
| **7** | **Andmete Ekstraheerimine** | Collection (TA0009) & Exfiltration (TA0010) | Automated Exfiltration (T1020) | Paki kokku tundlikud "dokumendid" (nt. $1 \text{GB}$ fail) ja **simuleeri nende saatmist** võrgu kaudu välisele serverile (nt. *Netcat* või HTTP tunnel). |
| **8** | **Kaitsemeetmete saboteerimine** | Defense Evasion (TA0005) | Disabling Security Software (T1562) | Uuri, kuidas ründajad kasutavad **seaduslikke Windowsi käske** (nt. *netsh advfirewall*) tulemüüri ajutiseks keelamiseks või seire logimise seiskamiseks. |
| **9** | **Lunavara käideldavuse rünne** | Impact (TA0040) | Data Encrypted for Impact (T1486) | **Simuleeri krüpteerimiskoodi** (nt. Pythoni lihtsa skripti abil) *harjutusmasina* andmetele, seejärel **taasta andmed** väliselt varukskopeeritud meediast. |
| **10** | **Võrgu seire vältimine** | Defense Evasion (TA0005) | Indicator Removal on Host (T1070) | Kasutades *Metasploiti* või sarnast tööriista, demonstreeri, kuidas **puhastada logid** (*clear event logs*) ja **eemaldada ajutised failid** harjutusmasinal, et vältida avastamist. |

---

## 2 Näidet Laboriülesannete Lahendamisest

Järgnevad näited pakuvad samm-sammulised juhendid kahe ülaltoodud laboriülesande lahendamiseks.

### Näide 1: Laboriülesanne 3: Parooli Räsikute Püüdmine

#### Eesmärk
* **Taktika:** Credential Access (TA0006)
* **Tehnika:** OS Credential Dumping (T1003)
* **Sisu:** Pärast esmast juurdepääsu Windowsi masinale, demonstreerida, kuidas **ekstraheerida parooli räsikud** *Local Security Authority Subsystem Service (LSASS)* mälust.
* **Aeg:** 3 tundi

#### Vajalik Keskkond
* Windowsi harjutusmasin (nt. Windows 10/Server 2016 VM)
* Ligipääs administraatoriõigustega (simuleerides esmast sissetungi)
* Kali Linux masin (tööriistade majutamiseks)

#### Lahenduskäik (3 tundi)

| Aeg | Tegevus | ATT&CK Seos | Tööriistad | Tulemus |
| :---: | :--- | :--- | :--- | :--- |
| **0:00 - 0:30** | **Ettevalmistus ja Kontroll** | Initial Access (T1190) | RDP/PsExec | Veenduda, et on administraatoriõigused (nt. *whoami /priv*). |
| **0:30 - 1:30** | **Räsikute Dumpimine** | OS Credential Dumping (T1003.001) | **Mimikatz** (või *pypykatz*) | Laadida Mimikatz harjutusmasinale (vältides AV-d) ja käivitada käsud: *privilege::debug* ja *sekurlsa::logonpasswords*. |
| **1:30 - 2:30** | **Räsikute Kräkkeri Ettevalmistus** | Lateral Movement (T1552) | **Hashcat** (Kali Linuxis) | Kopeerida leitud räsikud (nt. NTLM) Kali Linuxi masinasse. Valmistada ette *sõnastikufail* ja *mask* kräkkeri jaoks. |
| **2:30 - 3:00** | **Räsikute Kräkkimine ja Dokumenteerimine** | Credential Access (T1110) | Hashcat | Käivitada Hashcat ja **murda vähemalt 1 nõrk parool**. Dokumenteerida leitud räsikud, paroolid ja leevendamise ettepanekud (nt. MFA, LAPS). |

---

### Näide 2: Laboriülesanne 9: Lunavara Käideldavuse Rünne ja Taastumine

#### Eesmärk
* **Taktika:** Impact (TA0040)
* **Tehnika:** Data Encrypted for Impact (T1486)
* **Sisu:** Simuleerida (mitte tegelikult pahavaraga!) andmete krüpteerimist mõjutamata süsteemi tööd. Pärast rünnakut **demonstreerida taastumist** varukoopiast.
* **Aeg:** 3 tundi

#### Vajalik Keskkond
* Windowsi harjutusmasin, mis sisaldab tundlikke faile (*.docx, .xlsx*).
* Välise kettana paigaldatud varukoopia (*simuleeritud* taastemeedia).

#### Lahenduskäik (3 tundi)

| Aeg | Tegevus | ATT&CK Seos | Tööriistad | Tulemus |
| :---: | :--- | :--- | :--- | :--- |
| **0:00 - 0:30** | **Ettevalmistus ja Sihtmärgi Valik** | Collection (T1560) | Windowsi failisüsteem | Luua kaust (nt. C:\Tundlikud_andmed) ja täita see $10 \text{MB}$ "kriitiliste" failidega. |
| **0:30 - 1:30** | **Krüpteerimise Simuleerimine** | Data Encrypted for Impact (T1486) | **Python/PowerShell Skript** (Simulatsioon) | Kirjutada või leida skript, mis **asendab** failide sisu "FAIL KRÜPTEERITUD" tekstiga ja lisab faili lõppu *.locked* laiendi. Käivitada skript kaustas. |
| **1:30 - 2:30** | **Intsidendi Reageerimine ja Eraldamine** | Containment | Windowsi Tulemüür/Võrgukonfiguratsioon | Simuleerida **nakatunud masina isoleerimist** (nt. keelata kõik võrguliidesed/tulemüüri blokeeringud) ning **kontrollida** krüpteerimise levikut teistele ketastele. |
| **2:30 - 3:00** | **Andmete Taastamine** | Recovery (TA0046) | Varukoopia Ketas | Kustutada krüpteeritud failid ja **kopeerida originaalfailid** tagasi *simuleeritud väliselt* varukoopiakettalt. Dokumenteerida taasteaeg ja õnnestumise protsent. |

---
