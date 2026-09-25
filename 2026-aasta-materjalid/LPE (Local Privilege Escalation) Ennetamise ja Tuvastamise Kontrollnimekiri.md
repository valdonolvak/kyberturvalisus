# LPE (Local Privilege Escalation) Ennetamise ja Tuvastamise Kontrollnimekiri

See nimekiri on koostatud E-ITS (Eesti Infoturbestandard) parimate praktikate (sh DER.1, OPS.1, SYS.1) alusel, et kaitsta serveritaristut ja tööjaamu kohalike õiguste eskaleerimise rünnakute (nt Dirty Pipe, Win32k) eest.

## 1. Ennetavad meetmed (Prevention)

### Süsteemide paigamine ja elutsükkel (OPS.1.1.4)
- [ ] **Kerneli uuendused:** Automatiseeritud paikamisprotsess Linuxi ja Windowsi tuumadele (sh kriitiliste turvavigade *out-of-band* uuendused).
- [ ] **Virtuaalkeskkondade kaitse:** Hüperviisorite (nt Proxmox) regulaarne uuendamine ja haldusliideste isoleerimine eraldiseisvasse haldusvõrku.
- [ ] **Tarkvara inventuur:** Masinatesse on paigaldatud vaid minimaalselt vajalik tarkvara ja teenused (ründepinna vähendamine).

### Pääsuõiguste haldus ja karastamine (ORP.4 / SYS.1.2.2)
- [ ] **Vähimate õiguste printsiip (PoLP):** Tavalistel domeenikasutajatel puuduvad lokaalsed administraatori õigused.
- [ ] **Active Directory ja GPO:** GPO-de (Group Policy Object) abil on piiratud tarkvara paigaldus ja käivitamine standardkasutajate poolt. OU-d (Organizational Units) on loogiliselt ja turvaliselt eraldatud.
- [ ] **Linux SUID/SGID audit:** Süsteemides on kaardistatud ja vajadusel eemaldatud SUID/SGID bitid programmidele, mis seda reaalselt ei vaja.
- [ ] **Teenusekontod:** Veebiserverid ja rakendused (nt IIS, Apache) jooksevad piiratud õigustega teenusekontode (Service Accounts) all.

## 2. Tuvastavad meetmed (Detection)

### Tsentraalne logihaldus (DER.1 / DER.2)
- [ ] **Logide koondamine:** Kõikide serverite ja seadmete logid (Windows Event Logs, Linux Syslog) edastatakse reaalajas tsentraalsesse logiserverisse, vältimaks nende lokaalset kustutamist kompromiteeritud masinas.
- [ ] **Logide säilitamine:** Rakendatud on range logide säilitamise poliitika ja dokumentatsioon, mis vastab asutuse sisekorrale ja E-ITS nõuetele.
- [ ] **Ligipääsukontroll logidele:** Logiserverile on ligipääs ainult auditeerimiseks volitatud isikutel.

### Monitooring ja alarmeerimine
- [ ] **Kriitiliste failide jälgimine (FIM):** Automaatne teavitus süsteemifailide (nt `/etc/passwd`, `/etc/shadow`, Windowsi SAM andmebaas) ootamatust muutmisest.
- [ ] **Ebatüüpilised protsessid:** Teavitus, kui madala õigusega teenusekonto (nt `www-data`) käivitab ootamatult interaktiivse kesta (shell) või administraatori utiliidi.
- [ ] **Õiguste eskaleerumise sündmused:** Monitooring on seadistatud püüdmaks Windowsis edukat *SYSTEM* kontole lülitumist või Linuxis ootamatuid `su` / `sudo` kasutusi volitamata lokaalsete kasutajate poolt.

---

