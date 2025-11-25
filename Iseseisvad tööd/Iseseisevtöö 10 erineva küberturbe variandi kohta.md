Siin on ümbertöötatud ülesanded, mis on suunatud **IT-süsteemide administraatorite, küberkaitse või tarkvaraarenduse tudengitele**. Iga punkti juurde on lisatud "Tehniline ründevektor", mis selgitab, kuidas rünne "kapoti all" toimib.

---

### Iseseisvad tööd IT-eriala tudengitele

#### 1. Tarkvara uuendamine: Exploit'i anatoomia (WannaCry & EternalBlue)
**Tehniline ründevektor:** WannaCry kasutas EternalBlue (CVE-2017-0144) exploit'i. See rünne sihtis Windowsi SMBv1 (Server Message Block) protokolli, saates spetsiaalselt muundatud pakette, mis põhjustasid mälus *buffer overflow*. See võimaldas ründajal käivitada oma koodi (Remote Code Execution - RCE) SYSTEM õigustes ilma autentimiseta.
**Ülesanne:** Uuri CVE-2017-0144 dokumentatsiooni.
1. Selgita tehniliselt, miks SMBv1 protokolli disainiviga võimaldas koodi käivitamist (vihje: *heap memory corruption*).
2. Miks ei aidanud selle ründe vastu tavaline paroolipoliitika?
3. Kirjelda Nmap skripti (`smb-vuln-ms17-010`), mida ründajad kasutavad haavatavate masinate leidmiseks võrgus.

#### 2. EDR vs. Traditsiooniline AV: Obfuscation ja Signature Bypass
**Tehniline ründevektor:** Ründajad kasutavad "pakkijaid" (packers) ja koodi segamist (*obfuscation*), et muuta pahavara binaarfaili *hash*-i (MD5/SHA256). Traditsiooniline AV võrdleb faili *hash*-i andmebaasiga – kui *hash* on uus, siis viirust ei tuvastata. EDR jälgib aga süsteemikutseid (*Syscalls*) ja API *hook*'e. Näiteks: `powershell.exe` käivitamine, mis teeb võrgupäringu ja püüab süstida koodi `lsass.exe` protsessi (Credential Dumping).
**Ülesanne:**
1. Võrdle staatilist analüüsi ja dünaamilist analüüsi (Sandbox).
2. Kirjelda stsenaariumi, kus ründaja kasutab "Fileless Malware" (nt PowerShell script mälus), mis ei salvesta kettale ühtegi `.exe` faili. Kuidas EDR selle tuvastab, aga tavaline AV mitte?

#### 3. Tulemüür ja liikumine (Lateral Movement)
**Tehniline ründevektor:** Kui ründaja on saanud *shell*'i ühes masinas, kasutab ta seda "hüppelauana" (*Pivot*). Ta skaneerib sisevõrku (ARP scan, Ping sweep), et leida teisi masinaid (nt port 445, 3389). Ilma sisemiste tulemüürideta on liiklus VLAN-ide või subnetide vahel piiramatu.
**Ülesanne:**
1. Koosta `iptables` või Cisco ACL (Access Control List) reeglistik, mis lubab "Kontori VLAN"-ist "Serveri VLAN"-i ainult RDP ühenduse (port 3389) kindlalt haldus-IP-lt ja blokeerib kõik muu (sh ICMP).
2. Selgita, mis on "Micro-segmentation" ja kuidas see erineb tavalisest VLAN-põhisest segmenteerimisest ründaja horisontaalse liikumise takistamisel.

#### 4. Credential Stuffing: Automatiseerimine ja Proxyd
**Tehniline ründevektor:** Ründajad ei sisesta paroole käsitsi. Nad kasutavad tööriistu nagu **Sentry MBA**, **Sniper** või **Hydra**. Nad laevad sisse "Combo Listi" (kasutaja:parool paarid) ja kasutavad tuhandeid tasuta või botneti proxy-sid (*Rotating Proxies*), et vältida veebiserveri IP-põhist blokeeringut (*Rate Limiting*). Rünne näeb serveri logides välja nagu legitiimne liiklus erinevatest riikidest.
**Ülesanne:** (Vaata näidislahendust allpool).

#### 5. MFA: Man-in-the-Middle (MitM) ja Session Hijacking
**Tehniline ründevektor:** Lihtne SMS-põhine või TOTP (Google Authenticator) MFA on haavatav reaalajas toimuva **Reverse Proxy Phishing** ründe (nt tööriistaga **Evilginx2**) vastu. Ründaja loob võltsitud sisselogimislehe, mis vahendab liiklust ohvri ja päris teenuse vahel. Kui ohver sisestab MFA koodi, saadab ründaja server selle edasi päris teenusele, saab vastu **Session Cookie** ja salvestab selle. Ründaja ei vaja parooli, vaid "Session Tokenit".
**Ülesanne:**
1. Selgita tehniliselt, mis on "Session Cookie" ja "Auth Token" erinevus.
2. Miks FIDO2/U2F (füüsiline võti) on ainus kindel kaitse Evilginx2 tüüpi ründe vastu? (Vihje: *Domain binding*).

#### 6. Varundus: Lunavara krüpteerimismehhanismid
**Tehniline ründevektor:** Kaasaegne lunavara (nt Conti, Ryuk) kasutab hübriidkrüpteerimist. Failid krüpteeritakse sümmeetrilise võtmega (nt AES-256), mis on kiire. Seejärel krüpteeritakse AES-võti ründaja avaliku võtmega (RSA-2048 või 4096), mis on pahavaras kaasas. Ilma ründaja privaatvõtmeta on AES-võtit (ja seega faile) võimatu taastada. Lunavara otsib võrgust üles kõik SMB share'id (`net view`, `Get-SmbShare`) ja krüpteerib ka need.
**Ülesanne:**
1. Kirjelda protsessi, kuidas "Immutable Backup" (muutmatu varukoopia) tehnoloogia (nt WORM - Write Once, Read Many) takistab lunavaral varukoopiaid krüpteerimast või kustutamast, isegi kui ründajal on Admin õigused.

#### 7. Turvateadlikkus: E-posti päiste analüüs (Email Spoofing)
**Tehniline ründevektor:** Ründajad võltsivad saatja aadressi (Spoofing). Nad kasutavad SMTP servereid, mis ei kontrolli saatja õigusi. Kaitseks on protokollid SPF, DKIM ja DMARC.
**Ülesanne:** Sulle antakse analüüsida kahtlase e-kirja päis (*header*).
1. Selgita, mida tähendab `Received-SPF: Fail` või `Softfail`.
2. Mida teeb DKIM allkiri ja kuidas see tõestab, et kirja sisu pole teel muudetud?
3. Kuidas Punycode (IDN homograph attack) võimaldab ründajal registreerida domeeni, mis näeb brauseris välja nagu `apple.com`, kuid on tegelikult midagi muud?

#### 8. Ligipääsude haldus: Privilege Escalation
**Tehniline ründevektor:** Ründaja on saavutanud ligipääsu tavakasutaja kontole. Ta kasutab tööriistu nagu **Mimikatz**, et ekstraheerida mälust (LSASS protsessist) teiste kasutajate räsi või piletid (*Kerberos Tickets*). Kui masinasse on sisse loginud domeeniadmin, saab ründaja teha "Pass-the-Hash" või "Pass-the-Ticket" ründe ja võtta üle terve domeeni kontrolleri (DC).
**Ülesanne:**
1. Selgita "Least Privilege" printsiipi Windowsi Active Directory kontekstis. Miks ei tohi Domeeni Admin kunagi logida sisse tavakasutaja tööjaama?
2. Mis on "Cached Credentials" ja miks on nende arv (`CachedLogonsCount`) registris turvarisk?

#### 9. Seadmete turvalisus: Cold Boot ja DMA ründed
**Tehniline ründevektor:** Kui arvuti on unerežiimis (Sleep), on krüpteerimisvõtmed RAM-mälus. **Cold Boot** rünne seisneb mälu füüsilises külmutamises ja lugemises teises masinas. **DMA (Direct Memory Access)** rünne toimub läbi kiirete portide (Thunderbolt, FireWire), kus väline seade loeb otse arvuti mälu, minnes mööda protsessorist ja OS-i kaitsest.
**Ülesanne:**
1. Uuri, kuidas TPM (Trusted Platform Module) kiip kaitseb BitLockerit.
2. Mida tähendab "Pre-boot authentication" ja miks see on ainus kaitse Cold Boot ründe vastu, kui arvuti on varastatud "Sleep" režiimis?

#### 10. Võrgu turvalisus: ARP Spoofing ja Sniffing
**Tehniline ründevektor:** Jagatud võrgus (nt Guest Wi-Fi) saab ründaja teha **ARP Poisoning** ründe, kuulutades end Gateway'ks. Kogu ohvri liiklus läbib ründaja masinat (*Man-in-the-Middle*). Ründaja kasutab **Wiresharki** või **tcpdump**-i, et salvestada pakette ja otsida krüpteerimata andmeid (HTTP, Telnet, FTP) või teha SSL Strippingut.
**Ülesanne:**
1. Selgita, kuidas *Port Security* (MAC-aadressi piirang switchi pordil) ja *Dynamic ARP Inspection* (DAI) takistavad ARP Spoofing rünnet.

#### 11. Poliitika: Data Loss Prevention (DLP) tehnoloogiad
**Tehniline ründevektor:** Andmeleke ei toimu alati häkkimise teel. Sageli laadivad töötajad andmeid üles (Google Drive, WeTransfer) või kopeerivad USB-le. Rünne on siin pigem "Insider Threat".
**Ülesanne:**
1. Kuidas töötab DLP süsteem tehniliselt? (Vihje: *Regex* mustrite otsimine failidest ja võrguliiklusest, nt krediitkaardi numbrid, isikukoodid).
2. Kirjelda, kuidas HTTPS liiklus (TLS) takistab võrgupõhist DLP-d (Network DLP) ja miks ettevõtted kasutavad selleks *SSL Inspection* (MITM) lahendusi tulemüüris.

#### 12. Intsidendi käsitlemine: Logide analüüs ja SIEM
**Tehniline ründevektor:** Ründajad püüavad oma jälgi peita, kustutades logisid (`wevtutil cl System`). SIEM (Security Information and Event Management) süsteem korjab logid reaalajas tsentraalsesse serverisse.
**Ülesanne:**
1. Sulle antakse veebiserveri (Apache/Nginx) logi väljavõte. Tuvasta sealt SQL Injection ründekatse. (Otsi mustreid nagu `' OR 1=1`, `UNION SELECT`).
2. Miks on oluline logide "TimeStamp" sünkroniseerimine (NTP) kohtuekspertiisi (Forensics) jaoks?

---

### Näidislahendus IT-tudengile

#### Ülesanne nr 4: Credential Stuffing (Süvendatud)

**Situatsioon:** Ründaja kasutab lekkinud paroolide andmebaasi, et tungida hotelli süsteemi.
**Ülesanne:**
1. Analüüsi tehniliselt ründe läbiviimist. Milliseid komponente ründaja vajab?
2. Kirjelda meetmeid, mida süsteemiadministraator saab serveri poolel (WAF - Web Application Firewall tasemel) rakendada, et eristada botneti rünnet tavalisest liiklusest.

#### Lahendus:

**1. Ründe tehniline läbiviimine:**

See ei ole "Brute Force" (kõikide kombinatsioonide proovimine), vaid "Credential Stuffing" (tuntud paaride proovimine).

* **Sisendandmed (Combo List):** Ründaja laeb alla tekstifaili formaadis `email:password` (nt `anti@public.log:ABC12345`). Need pärinevad SQL-injectiooni teel varastatud andmebaasidest, kus räsid on lahti murtud või olid plaintextis.
* **Tööriist (Attack Tool):** Kasutatakse konfigureeritavat tööriista nagu **OpenBullet** või **Sentry MBA**. Tööriistale kirjutatakse "Config" fail, mis õpetab, kuidas hotelli veebilehele POST päringut saata ja kuidas lugeda vastust (nt kui HTML sisaldab sõna "Welcome", siis on *Success*, kui "Incorrect password", siis *Fail*).
* **Anonüümsus ja maht (Proxy Rotation):** Ründaja suunab liikluse läbi tuhandete kodukasutajate nakatunud ruuterite või IoT seadmete (Residential Proxies). Hotelli tulemüür näeb, et päringud tulevad erinevatelt IP-delt üle maailma, mitte ühelt ründaja IP-lt.
* **User-Agent Spoofing:** Iga päringuga muudetakse HTTP päises `User-Agent` stringi (nt Chrome, Firefox, Safari, iPhone), et jäljendada erinevaid seadmeid.

**2. Tõrjumine WAF ja Backend tasemel:**

Kuna paroolid on tehniliselt õiged, on rünnet raske tuvastada. IT-administraator peab rakendama järgmist:

* **Rate Limiting (Kiiruse piiramine):** Seadistada WAF (nt Cloudflare, AWS WAF) piirama sisselogimiskatseid ühelt IP-lt (nt max 5 katset minutis). *Märkus: See ei aita hästi hajutatud proxy-võrgustiku vastu.*
* **Device Fingerprinting (Seadme sõrmejälje võtmine):** Kasutada JavaScripti brauseri omaduste (ekraani resolutsioon, pluginad, canvas rendering) analüüsiks. Botid ei suuda sageli JavaScripti täielikult käivitada või on nende "sõrmejälg" liiga ühtne.
* **IP Reputatsiooni kontroll:** Blokeerida liiklus tuntud andmekeskustest (AWS, DigitalOcean) või Tor väljundsõlmedest sisselogimislehele, kuna tavakasutajad ei logi sisse serveriparkidest.
* **CAPTCHA (reCAPTCHA v3):** Rakendada "nähtamatut" CAPTCHA-t, mis analüüsib hiire liikumist ja klõpsude dünaamikat. Botid liigutavad "hiirt" lineaarselt ja ebainimlikult kiiresti.
* **Credential Screening:** Integratsioon teenusega (nt *Have I Been Pwned* API), mis kontrollib sisselogimisel, kas kasutaja parool on teadaolevas lekkes. Kui on, sunnib süsteem kasutajat parooli vahetama, isegi kui see on õige.
