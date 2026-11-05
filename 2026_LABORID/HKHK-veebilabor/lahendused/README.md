# `lahendused/` – parandatud failid (õpetaja lahendusvõti)

Selles kaustas on HKHK Tugi rakenduse **parandatud versioonid** neist failidest,
mis kaustas `src/` on tahtlikult haavatavad. Iga fail parandab ühe labori
(A–F) haavatavuse. Õppija kopeerib faili `src/` kausta, taaskäivitab
veebikonteineri ja kordab sama rünnakut, et näha, et see enam ei toimi.

> 🔒 **See on lahendusvõti.** Ära anna seda kausta õppijatele enne, kui nad on
> rünnaku ise tõendanud ja oma parandusettepaneku kirjutanud. Kui põhirepo on
> avalik (*public*), hoia see kaust eraldi **privaatses** repos.

---

## Failid lühidalt

| Fail | Labor | Haavatavus | Mis on parandatud |
|------|-------|------------|-------------------|
| [`login.php`](login.php) | A | SQL Injection | Parameetriseeritud päring; uus sessiooni-ID pärast sisselogimist |
| [`ticket.php`](ticket.php) | B | IDOR | Omaniku kontroll SQL-päringus; võõra pöördumise korral 404 |
| [`search.php`](search.php) | C | Reflected XSS | Kogu väljund kodeeritakse funktsiooniga `e()` |
| [`profile.php`](profile.php) | D | CSRF | Muutmine ainult POST-iga ja CSRF-tokeniga; GET annab 405 |
| [`config.php`](config.php) | D, F (ja kõik teised) | Mitu | Turvaline sessiooniküpsis, CSP ja muud turvapäised, vead ei kuvata kasutajale, CSRF-funktsioonid |
| [`download.php`](download.php) | E | Path Traversal | Lubatud failide nimekiri ja `realpath()` kontroll |
| [`upload.php`](upload.php) | F | Faili üleslaadimine | Tüüp sisu järgi, serveri antud nimi, salvestamine väljaspool veebijuurt, suuruse piirang |
| [`view.php`](view.php) | F | – (uus fail) | Näitab üleslaaditud pilte turvaliselt, ilma neid käivitamata |

---

## Kuidas kasutada

```bash
# 1. Vaata enne kopeerimist, mis muutus (õppimise mõte!)
diff src/login.php lahendused/login.php

# 2. Kopeeri parandatud fail
cp lahendused/login.php src/login.php

# 3. Taaskäivita veebikonteiner
docker compose restart web
```

Kaust `src/` on konteinerisse otse ühendatud (*bind mount*), seega konteinerit
pole vaja uuesti ehitada.

Iga labori jaoks kopeeritavad failid:

| Labor | Kopeeri |
|-------|---------|
| A – SQL Injection | `login.php` |
| B – IDOR | `ticket.php` |
| C – XSS | `search.php` |
| D – CSRF | `config.php`, `profile.php` |
| E – Path Traversal | `download.php` |
| F – Faili üleslaadimine | `config.php`, `upload.php`, `view.php` |

Algseisu taastamiseks: `git checkout -- src/` või `docker compose down -v`
koos haavatavate failide taastamisega.

---

## Failide kaupa

### `login.php` – labor A, SQL Injection

| Rida | Muudatus | Miks |
|------|----------|------|
| 11–12 | `$pdo->prepare('… WHERE username = ? AND password = MD5(?)')` + `execute([$u, $p])` | SQL-mall ja kasutaja andmed saadetakse eraldi. Sisend jääb alati andmeteks ega saa päringu struktuuri muuta. |
| 17 | `session_regenerate_id(true)` | Pärast sisselogimist antakse uus sessiooni-ID. See kaitseb sessiooni fikseerimise (*session fixation*) eest: ründaja ei saa ette anda sessiooni-ID-d, mis hiljem sisselogituks muutub. |

Paroolid on andmebaasis endiselt MD5-räsina, sest andmebaasi struktuur on
kõigil versioonidel sama. Päris rakenduses kasuta `password_hash()` ja
`password_verify()`.

### `ticket.php` – labor B, IDOR

| Rida | Muudatus | Miks |
|------|----------|------|
| 6 | `(int)$_GET['id']` | ID teisendatakse täisarvuks. |
| 9–15 | Tavakasutajale `WHERE t.id = ? AND t.user_id = ?`, kus teine väärtus tuleb **sessioonist** | Kasutaja näeb ainult oma pöördumisi. `user_id` ei tule päringust, nii et kasutaja ei saa seda muuta. Admin näeb kõiki pöördumisi. |
| 18–24 | Kui rida ei leitud, tagastatakse **404** | Ründaja ei saa teada, kas sellise ID-ga pöördumine on olemas (403 annaks selle teada). |
| 31–34 | Kõik väljundid läbi `e()` | Kaitse ka salvestatud XSS-i eest pöördumise sisus. |

### `search.php` – labor C, Reflected XSS

| Rida | Muudatus | Miks |
|------|----------|------|
| 20, 26 | `<?= e($q) ?>` | Otsingusõna kodeeritakse HTML-i jaoks: `<` → `&lt;`, `"` → `&quot;` jne. Brauser näitab teksti, aga ei käivita seda. Kehtib ka vormivälja `value=""` atribuudis. |
| 29 | `e($r['id'])`, `e($r['title'])` | Ka andmebaasist tulev tekst kodeeritakse. |
| 10–11 | `WHERE user_id = ? AND title LIKE ?` | Otsing toimub ainult oma pöördumiste seast (päring oli ka haavatavas versioonis parameetriseeritud). |

**Content-Security-Policy päis** ei ole selles failis, vaid failis
`config.php`. Kui labor C tehakse enne laborit D, tuleb CSP alles koos
`config.php` failiga (vt „Tähelepanekud“).

### `profile.php` – labor D, CSRF

| Rida | Muudatus | Miks |
|------|----------|------|
| 10 | Muutmine ainult siis, kui `REQUEST_METHOD === 'POST'`, ja andmed loetakse `$_POST`-ist | Lingi või `<img>` sildiga (GET) ei saa enam midagi muuta. |
| 12–15 | `csrf_check()`, ebaõnnestumisel **403** | Server kontrollib, et päringus on sessiooni salajane token. Võõras leht ei tea seda tokenit. |
| 18 | `filter_var($email, FILTER_VALIDATE_EMAIL)` | Kontrollitakse, et tegu on e-posti aadressiga. |
| 27–31 | GET-iga muutmise katse annab **405** ja logikirje | Rünnakukatse on logist näha. |
| 44 | `<?= csrf_field() ?>` | Vormi lisatakse peidetud väli tokeniga. |

`SameSite=Strict` küpsis on failis `config.php`, seepärast kopeeritakse laboris
D mõlemad failid.

### `config.php` – ühine seadistus

Seda faili kasutavad **kõik** leheküljed (`require 'config.php'`), seega mõjutab
see kogu rakendust.

| Rida | Muudatus | Miks |
|------|----------|------|
| 5–7 | `display_errors=0`, `log_errors=1` | Veateated ei näita kasutajale enam SQL-lauseid ega failiteid (labor A, *security misconfiguration*). Vead lähevad ainult logisse. |
| 12–18 | Sessiooniküpsis `httponly` ja `samesite=Strict` | `HttpOnly`: JavaScript ei saa küpsist lugeda (XSS-i mõju väheneb). `SameSite=Strict`: brauser ei saada küpsist teiselt saidilt algatatud päringuga (CSRF). |
| 22 | `Content-Security-Policy` | Brauser käivitab ainult oma serveri skripte, mitte lehe sisse süstitud koodi. Teine kaitsekiht XSS-i vastu. |
| 23 | `X-Content-Type-Options: nosniff` | Brauser ei „arva“ faili tüüpi sisu järgi, vaid usaldab serveri `Content-Type` päist. |
| 24 | `X-Frame-Options: DENY` | Lehte ei saa panna teise lehe sisse `iframe`-is (*clickjacking*). |
| 38 | `PDO::ATTR_EMULATE_PREPARES => false` | Päris *prepared statement*'id: andmebaas saab malli ja andmed tõesti eraldi. |
| 46 | `applog()` eemaldab reavahetused | Kasutaja ei saa lisada logisse võltsitud ridu (*log injection*). |
| 58–61 | `e()` | Abifunktsioon HTML-kodeerimiseks (`htmlspecialchars`, `ENT_QUOTES`, UTF-8). |
| 64–81 | `csrf_token()`, `csrf_field()`, `csrf_check()` | Juhuslik 32-baidine token sessioonis; võrdlus `hash_equals()` abil, mis ei leki ajastuse kaudu infot. |

Rida 17 (`'secure' => true`) on välja kommenteeritud, sest labor töötab HTTP-ga.
HTTPS-iga rakenduses peab see olema sees.

### `download.php` – labor E, Path Traversal

| Rida | Muudatus | Miks |
|------|----------|------|
| 6 | `$baseDir = realpath(__DIR__ . '/files')` | Lubatud kausta täpne tee. |
| 9 | Lubatud failid = kausta `files/` sisu (ilma peidetud failideta) | Nimekiri koostatakse serveris. |
| 25–30 | `in_array($file, $allowed, true)`, muidu **404** | Nimi peab täpselt vastama mõnele lubatud failile. `../` või `/` sisaldav nimi ei vasta kunagi. |
| 33–39 | `realpath()` + `str_starts_with($path, $baseDir . '/')` | Teine kaitsekiht (*defense in depth*): ka siis, kui nimekirja kontroll kunagi muutub, peab faili lõplik tee asuma lubatud kaustas. Kaitseb ka sümbolinkide eest. |

### `upload.php` – labor F, faili üleslaadimine

| Rida | Muudatus | Miks |
|------|----------|------|
| 7 | `UPLOAD_DIR = '/var/www/private-uploads'` | Failid salvestatakse **väljaspool veebijuurt** `/var/www/html`. Apache ei saa neid URL-iga avada ega käivitada. |
| 8, 21 | Suuruse piirang 2 MB | Kaitse ketta täitmise eest. |
| 16 | `finfo(FILEINFO_MIME_TYPE)->file(...)` | Tüüp tuvastatakse faili **sisu** järgi (*magic bytes*). Kliendi saadetud `Content-Type` päist ei usaldata. |
| 17, 23 | Lubatud ainult `image/png`, `image/jpeg`, `image/gif` | PHP-skript (`text/x-php`) lükatakse tagasi. |
| 28 | `bin2hex(random_bytes(16)) . '.' . $map[$realType]` | Nime annab server; laiend tuleb tegelikust tüübist. Kasutaja ei saa valida laiendit `.php` ega kirjutada üle teisi faile. |
| 31 | Link `view.php?f=…` | Faili näidatakse ainult läbi `view.php`. |

### `view.php` – labor F, uus fail

| Rida | Muudatus | Miks |
|------|----------|------|
| 4 | `require_login()` | Pilte näevad ainult sisselogitud kasutajad. |
| 9–12 | Nimi peab vastama mustrile `32 hex-märki + .png/.jpg/.gif` | Ainult serveri antud nimed; `../` ja muud nimed on võimatud. |
| 20–21 | `Content-Type` laiendi järgi | Fail saadetakse alati pildina. Koos päisega `nosniff` ei tõlgenda brauser seda HTML-i või skriptina. |
| 23 | `readfile()` | Faili sisu **loetakse ja saadetakse**, mitte ei käivitata. |

---

## Sõltuvused `config.php`-st

Kõik parandatud failid kasutavad `config.php` funktsioone:

| Funktsioon | Kasutavad |
|------------|-----------|
| `e()`, `page_header()`, `page_footer()`, `applog()`, `require_login()` | kõik failid |
| `csrf_field()`, `csrf_check()` | `profile.php` |

Kui kopeerid ainult ühe faili (nt laboris C ainult `search.php`), kasutab see
`src/` kaustas olevat **haavatavat** `config.php` faili. See töötab ainult siis,
kui seal on samuti olemas funktsioonid `e()`, `applog()`, `page_header()` jne.

---

## Tähelepanekud õpetajale

1. **`config.php` mõjutab mitut laborit.** Laboris D kopeeritud `config.php`
   lülitab sisse ka CSP ja `HttpOnly` küpsise ning peidab veateated. Kui
   laborid tehakse järjekorras A → F, ei sega see midagi, sest C on enne D-d.
   Kui keegi teeb labori C pärast D-d, ei ilmu `alert(document.cookie)`
   enam isegi haavatava `search.php` korral, sest CSP blokeerib skripti ja
   `HttpOnly` peidab küpsise. Sel juhul taasta enne labori C algust haavatav
   `src/config.php`.
2. **CSP ja labor C.** Juhend ütleb, et labori C parandus lisab CSP päise, kuid
   see päis on failis `config.php`, mida laboris C ei kopeerita. Kas lisa
   laboris C juhendisse märkus, et CSP tuleb koos laboriga D, või lisa
   `search.php` algusesse sama `header("Content-Security-Policy: …")` rida.
3. **`/var/www/private-uploads` peab olemas olema.** Kaust peab olema loodud
   `web/Dockerfile`'is ja kuuluma kasutajale `www-data`, nt:
   ```dockerfile
   RUN mkdir -p /var/www/private-uploads && chown www-data:www-data /var/www/private-uploads
   ```
   Kui kaust puudub, ebaõnnestub `move_uploaded_file()`, aga `upload.php` ei
   kontrolli tagastusväärtust ja teatab ikkagi „Fail salvestatud“. Turvalisem on
   kontrollida:
   ```php
   if (!move_uploaded_file($f['tmp_name'], UPLOAD_DIR . '/' . $name)) { … }
   ```
4. **PHP versioon.** `download.php` kasutab funktsiooni `str_starts_with()`,
   mis on olemas alates PHP 8.0-st. Veendu, et `web/Dockerfile` kasutab PHP 8
   tõmmist.
