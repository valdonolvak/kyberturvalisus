# HKHK veebiturbe labor – PHP/MySQL praktikum

## Samm-sammuline juhend koos päris logiväljunditega

See labor täiendab OWASP Juice Shopi ja WebGoati laboreid ühe olulise asjaga:
siin on **sinu enda rakenduse lähtekood**, nii et sa saad vea päriselt
**parandada**, konteineri uuesti ehitada ja **testi korrata**. Just seda tsüklit
Juice Shop ja WebGoat ei võimalda.

Rakendus on väljamõeldud kasutajatoe süsteem „HKHK Tugi". Selles on **kuus
tahtlikku turvaviga**:

| # | Haavatavus | Fail | OWASP Top 10 |
|---|-----------|------|--------------|
| 1 | SQL Injection | `login.php` | A03 Injection |
| 2 | Broken Access Control (IDOR) | `ticket.php` | A01 |
| 3 | Reflected XSS | `search.php` | A03 |
| 4 | CSRF | `profile.php` | A01 / varasem A08 |
| 5 | Path Traversal | `download.php` | A01 |
| 6 | Unrestricted File Upload | `upload.php` | A04 / A05 |

> ⚠️ **Hoiatus.** Rakendus on *tahtlikult katki*. Käivita see **ainult
> isoleeritud laborivõrgus**. Ära ava seda kunagi Internetti ega kooli
> tootmisvõrku.

---

# 1. Eeldused

Vajad serverit (WEB-SRV, Ubuntu Server 24.04), kus on Docker Engine ja
Docker Compose plugin paigaldatud (vt põhijuhendi LABOR 2). Kontrolli:

```bash
sudo docker --version
sudo docker compose version
```

Testimiseks kasutad lisaks:

* **Windowsi klienti** (brauser + Developer Tools);
* **Kali Linuxit** (`curl`, `nmap`, Burp Suite).

Kõik näited kasutavad serveri aadressi `192.168.50.10`. Kui sinu serveril on
teine aadress, muuda seda failis `.env`.

---

# 2. Rakenduse käivitamine

Paki kaust `hkhk-veebilabor` serverisse lahti ja mine sinna sisse:

```bash
cd hkhk-veebilabor
```

Ava fail `.env` ja pane sinna serveri laborivõrgu IP:

```bash
nano .env
```

```text
LAB_IP=192.168.50.10
```

Ehita ja käivita konteinerid:

```bash
sudo docker compose up -d --build
```

Kontrolli, et mõlemad konteinerid töötavad:

```bash
sudo docker compose ps
```

Oodatav väljund:

```text
NAME       IMAGE               STATUS                   PORTS
hkhk-db    mariadb:11          Up (healthy)
hkhk-web   hkhk-veebilabor-web Up                       192.168.50.10:8000->80/tcp
```

Ava brauseris:

```text
http://192.168.50.10:8000/
```

Laborikontod (kõik paroolid on väljamõeldud):

| Kasutaja | Parool | Roll |
|----------|--------|------|
| `mari` | `mari123` | tavakasutaja |
| `juri` | `juri123` | tavakasutaja |
| `admin` | `Adm1n!Labor#2026` | administraator |

## Logide jälgimine

Hoia **teises terminalis** kogu labori vältel avatuna rakenduse logi. Siin
näed, mida server iga päringuga teeb:

```bash
sudo docker compose logs -f web
```

Andmebaasi tegelikke SQL-päringuid näed nii:

```bash
sudo docker compose exec db tail -f /var/lib/mysql/general.log
```

> Kõik allpool toodud logiread on **päris väljund** selle rakenduse
> käivitamisest. Sinu logis on ajatemplid ja protsessi-ID-d teised, aga sisu
> peab olema sama.

---

# 3. Õppetsükkel

Iga labor järgib sama mustrit:

```text
seadista → kontrolli → vaata kuidas töötab → tee kontrollitud test →
tõenda → paranda → testi uuesti → vasta küsimustele
```

Parandamine käib nii: parandatud failid on kaustas `lahendused/`. Kui juhend
ütleb „paranda", kopeeri vastav fail `src/`-i ja ehita konteiner uuesti:

```bash
cp lahendused/login.php src/login.php
sudo docker compose restart web
```

Seejärel **korda sama testi** ja vaata, et vastus on nüüd turvaline.

---

# LABOR A – SQL Injection (`login.php`)

## A.1. Vaata, kuidas süsteem töötab

Logi sisse tavakasutajana `mari` / `mari123`. Vaata logis, millise
SQL-päringu server koostas:

```text
[HKHK] user=- LOGIN SQL: SELECT id, username, role FROM users WHERE username = 'mari' AND password = MD5('mari123')
[HKHK] user=mari LOGIN OK uid=2
```

Näed kohe probleemi: kasutajanimi `mari` on pandud **otse** SQL-lause sisse
jutumärkide vahele. Server ei erista kasutaja sisendit SQL-koodist.

## A.2. Kontrollitud test – katkesta päring

Logi välja. Proovi kasutajanimeks panna üks ülakoma:

* Kasutajanimi: `mari'`
* Parool: `x`

Kali terminalist (sama asja saab teha ka brauseris):

```bash
curl -s -i --data-urlencode "username=mari'" --data-urlencode "password=x" \
  http://192.168.50.10:8000/login.php
```

Server annab vea (sest `display_errors` on sees – see on omaette
turvaviga, misconfiguration):

```text
Fatal error:  Uncaught PDOException: SQLSTATE[42000]: Syntax error or access
violation: 1064 You have an error in your SQL syntax; ... near 'x')' at line 1
in /var/www/html/login.php:13
```

See **tõendab**, et sinu ülakoma jõudis muutmata kujul SQL-mootorini ja
lõhkus päringu struktuuri. Logis:

```text
[HKHK] user=- LOGIN SQL: SELECT id, username, role FROM users WHERE username = 'mari'' AND password = MD5('x')
PHP Fatal error:  Uncaught PDOException: SQLSTATE[42000]: ... near 'x')' at line 1
```

## A.3. Kontrollitud test – möödu autentimisest

Nüüd kasuta ülakoma ja kommentaari, et parooli kontroll „ära lõigata".
Kasutajanimeks:

```text
admin'-- -
```

Parooliks ükskõik mis. Serverile saadetud päring:

```bash
curl -s -i -c admin.jar \
  --data-urlencode "username=admin'-- -" \
  --data-urlencode "password=suvaline" \
  http://192.168.50.10:8000/login.php
```

Server koostab sellise lause (näed logist):

```text
[HKHK] user=- LOGIN SQL: SELECT id, username, role FROM users WHERE username = 'admin'-- -' AND password = MD5('suvaline')
[HKHK] user=admin LOGIN OK uid=1
```

Osa `-- -` muudab parooli kontrolli SQL-kommentaariks. Server logib sind
sisse **administraatorina, parooli teadmata**. Vastuses näed ümbersuunamist:

```text
HTTP/1.1 302 Found
Location: tickets.php
```

Küpsisega `admin.jar` pääsed nüüd admin-vaatesse:

```bash
curl -s -b admin.jar http://192.168.50.10:8000/admin.php
```

**Tõendus:** salvesta ekraanipilt admin-lehest + see logirida `LOGIN OK uid=1`.

## A.4. Paranda

```bash
cp lahendused/login.php src/login.php
sudo docker compose restart web
```

Parandus kasutab **parameetriseeritud päringut** – kasutaja sisend on nüüd
alati andmed, mitte kood:

```php
$st = $pdo->prepare('SELECT id, username, role FROM users WHERE username = ? AND password = MD5(?)');
$st->execute([$u, $p]);
```

## A.5. Testi uuesti

Proovi sama rünnakut `admin'-- -`:

```bash
curl -s -i --data-urlencode "username=admin'-- -" --data-urlencode "password=suvaline" \
  http://192.168.50.10:8000/login.php
```

Nüüd:

```text
HTTP/1.1 200 OK
...
<p class="err">Vale kasutajanimi või parool.</p>
```

Andmebaasi logis näed, et päring on nüüd **ette valmistatud** ja ründeköis
läheb sisse ühe tüki andmetena (pane tähele `\'` – ülakoma on turvaliselt
varjestatud):

```text
51 Prepare   SELECT id, username, role FROM users WHERE username = ? AND password = MD5(?)
51 Execute   SELECT id, username, role FROM users WHERE username = 'mari' AND password = MD5('mari123')
53 Execute   SELECT id, username, role FROM users WHERE username = 'admin\'-- -' AND password = MD5('suvaline')
```

Rakenduse logis:

```text
[HKHK] user=- LOGIN prepared statement, username=admin'-- -
[HKHK] user=- LOGIN FAIL username=admin'-- -
```

## A.6. Küsimused

1. Miks ei peatanud ülakoma varjestamine (escaping) üksinda probleemi, vaid
   lahenduseks on parameetriseeritud päring?
2. Mida teeb `-- -` SQL-päringus?
3. Miks on `display_errors=On` tootmisserveris omaette turvarisk?

---

# LABOR B – Broken Access Control / IDOR (`ticket.php`)

## B.1. Vaata, kuidas süsteem töötab

Logi sisse kui `mari`. Ava „Minu pöördumised" ja vajuta ühele pöördumisele.
URL on kujul:

```text
http://192.168.50.10:8000/ticket.php?id=3
```

Number `id` on **otsene viide objektile** (Insecure Direct Object Reference).

## B.2. Kontrollitud test

Mari pöördumised on ID 3 ja 5. Proovi käsitsi avada ID 4, mis **ei kuulu
Marile** (see on admini konfidentsiaalne pöördumine):

```bash
# mari.jar = Mari sisselogitud sessioon
curl -s -b mari.jar "http://192.168.50.10:8000/ticket.php?id=4"
```

Server tagastab Marile võõra pöördumise sisu:

```text
HTTP/1.1 200 OK
  Pealkiri: KONFIDENTSIAALNE: VPN
  Omanik: admin
  Sisu: Ajutine VPN-i võti serveriruumi jaoks: LABOR-VPN-7731 (väljamõeldud).
```

Logis näed, et server **ei kontrollinud omanikku**, kontrollis ainult, et
keegi on sisse loginud (Mari uid on 2, aga pöördumine 4 kuulub uid=1 adminile):

```text
[HKHK] user=mari TICKET VIEW id=4 uid=2
```

**Tõendus:** ekraanipilt võõra pöördumise sisust + logirida.

## B.3. Paranda ja testi uuesti

```bash
cp lahendused/ticket.php src/ticket.php
sudo docker compose restart web
```

Parandus lisab omaniku kontrolli **SQL-päringusse endasse**:

```php
WHERE t.id = ? AND t.user_id = ?
```

Korda testi:

```bash
curl -s -i -b mari.jar "http://192.168.50.10:8000/ticket.php?id=4"
```

Nüüd:

```text
HTTP/1.1 404 Not Found
```
```text
[HKHK] user=mari TICKET DENIED id=4 uid=2
```

> Pane tähele: tagastatakse **404**, mitte 403. Nii ei saa ründaja teada,
> kas sellise ID-ga pöördumine üldse olemas on.

## B.4. Küsimused

1. Mille poolest erineb autentimine autoriseerimisest selles laboris?
2. Miks ei piisa admin-nupu peitmisest kasutajaliideses?
3. Miks tagastab parandus 404, mitte 403?

---

# LABOR C – Reflected XSS (`search.php`)

## C.1. Vaata, kuidas süsteem töötab

Logi sisse. Ava „Otsing" ja otsi sõna `test`. Otsingusõna kuvatakse tagasi
lehel: *„Otsingu tulemused päringule: test"*.

Oluline: SQL-päring ise on siin **turvaline** (parameetriseeritud). Probleem
on **väljundis** – otsingusõna pannakse HTML-i kodeerimata.

## C.2. Kontrollitud test

Otsi järgmist teksti (brauseris otsingukasti, või URL-iga):

```text
<script>alert(document.cookie)</script>
```

```bash
curl -s -b mari.jar \
  "http://192.168.50.10:8000/search.php?q=%3Cscript%3Ealert(document.cookie)%3C/script%3E"
```

Serveri vastuses on sinu skript **muutmata HTML-ina**:

```text
<p>Otsingu tulemused päringule: <b><script>alert(document.cookie)</script></b></p>
```

Brauseris avades käivitub `alert`, mis näitab sessiooniküpsist. See
**tõendab**, et ründaja sisestatud JavaScript jookseb ohvri brauseris.

Logis näed, et SQL-pool oli korras (kasutati `LIKE ?`), aga server peegeldas
sisendi tagasi:

```text
[HKHK] user=mari SEARCH q=<script>alert(document.cookie)</script>
```

## C.3. Paranda ja testi uuesti

```bash
cp lahendused/search.php src/search.php
sudo docker compose restart web
```

Parandus kodeerib väljundi (`htmlspecialchars` funktsioon `e()`) ja lisab
Content-Security-Policy päise. Korda testi:

```bash
curl -s -b mari.jar \
  "http://192.168.50.10:8000/search.php?q=%3Cscript%3Ealert(document.cookie)%3C/script%3E"
```

Nüüd on `<` muudetud ohutuks tekstiks:

```text
<p>Otsingu tulemused päringule: <b>&lt;script&gt;alert(document.cookie)&lt;/script&gt;</b></p>
```

Brauser kuvab teksti, aga **ei käivita** seda skriptina.

## C.4. Küsimused

1. Miks ei olnud see SQL Injection, kuigi sisend läks andmebaasi?
2. Mis on kontekstipõhine väljundi kodeerimine?
3. Kuidas aitab `HttpOnly` küpsis XSS-i mõju vähendada?

---

# LABOR D – CSRF (`profile.php`)

## D.1. Vaata, kuidas süsteem töötab

Logi sisse kui `mari`. Ava „Profiil" ja muuda e-posti aadressi. Toiming
muudab serveris olekut (andmebaasi kirjet).

Selles versioonis on **kolm viga**: toiming aktsepteerib GET-päringut,
puudub CSRF-token ja küpsisel puudub `SameSite`.

## D.2. Kontrollitud test

Kaustas `rundaja/` on fail `kinkekaart.html` – pahatahtlik leht, mis saadab
Mari brauseri kaudu vaikselt päringu. Simuleeri seda päringut (`-e` lisab
võõra Referer-päise, nagu tuleks päring teiselt saidilt):

```bash
curl -s -i -b mari.jar \
  -e "http://192.168.50.30:8001/kinkekaart.html" \
  "http://192.168.50.10:8000/profile.php?email=kaaperdatud@kurjam.labor"
```

Server muudab e-posti aadressi ära:

```text
HTTP/1.1 200 OK
  E-post: kaaperdatud@kurjam.labor
  E-posti aadress muudetud.
```

Logis on näha, et toiming tehti **GET-meetodiga teiselt saidilt**:

```text
[HKHK] user=mari PROFILE EMAIL CHANGE method=GET new=kaaperdatud@kurjam.labor referer=http://192.168.50.30:8001/kinkekaart.html
```

Päris laboris paned `kinkekaart.html` Kali masinasse
(`cd rundaja && python3 -m http.server 8001`), avad selle Windowsi brauseris,
kus Mari on sisse logitud, ja vaatad, kuidas e-post muutub ohvri enda
brauseri kaudu.

## D.3. Paranda ja testi uuesti

```bash
cp lahendused/config.php  src/config.php
cp lahendused/profile.php src/profile.php
sudo docker compose restart web
```

Parandus: ainult POST, CSRF-token igas vormis, `SameSite=Strict` küpsis.

**Test 1 – GET lükatakse tagasi:**

```bash
curl -s -i -b mari.jar "http://192.168.50.10:8000/profile.php?email=x@x.labor"
```
```text
HTTP/1.1 405 Method Not Allowed
[HKHK] user=mari PROFILE EMAIL CHANGE REJECTED method=GET
```

**Test 2 – POST ilma tokenita lükatakse tagasi:**

```bash
curl -s -i -b mari.jar -d "email=x@x.labor" "http://192.168.50.10:8000/profile.php"
```
```text
HTTP/1.1 403 Forbidden
[HKHK] user=mari PROFILE CSRF TOKEN INVALID referer=...
```

**Test 3 – päris vorm kehtiva tokeniga töötab:**

```text
[HKHK] user=mari PROFILE EMAIL CHANGE method=POST new=mari.uus@hkhk.labor
```

Pane ka tähele, et sisselogimisel on küpsisel nüüd kaitse:

```text
Set-Cookie: PHPSESSID=...; path=/; HttpOnly; SameSite=Strict
```

## D.4. Küsimused

1. Miks on olekut muutva toimingu lubamine GET-meetodiga ohtlik?
2. Kuidas CSRF-token takistab võõralt saidilt tulnud päringut?
3. Mida teeb `SameSite=Strict` küpsis?

---

# LABOR E – Path Traversal (`download.php`)

## E.1. Vaata, kuidas süsteem töötab

Logi sisse. Ava „Juhendid". Faile laaditakse alla URL-iga:

```text
http://192.168.50.10:8000/download.php?file=wifi-juhend.txt
```

Server liidab failinime otse kataloogiteele: `files/ + wifi-juhend.txt`.

## E.2. Kontrollitud test

Proovi „välja ronida" lubatud kaustast `../` abil ja lugeda süsteemifaili:

```bash
curl -s -i -b mari.jar \
  "http://192.168.50.10:8000/download.php?file=../../../../etc/passwd"
```

Server tagastab serveri kasutajate faili sisu:

```text
HTTP/1.1 200 OK
Content-Type: text/plain; charset=utf-8

root:x:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
...
```

Sama meetodiga saab lugeda ka rakenduse enda konfiguratsiooni koos
andmebaasi parooliga:

```bash
curl -s -b mari.jar "http://192.168.50.10:8000/download.php?file=../config.php"
```

Logis on näha, kuhu tee tegelikult osutab:

```text
[HKHK] user=mari DOWNLOAD path=/var/www/html/files/../../../../etc/passwd
```

**Tõendus:** ekraanipilt `/etc/passwd` sisust brauseris + logirida.

## E.3. Paranda ja testi uuesti

```bash
cp lahendused/download.php src/download.php
sudo docker compose restart web
```

Parandus: lubatud failide **nimekiri** + `realpath`-kontroll, et tee jääks
lubatud kataloogi. Korda testi:

```bash
curl -s -i -b mari.jar "http://192.168.50.10:8000/download.php?file=../../../../etc/passwd"
```
```text
HTTP/1.1 404 Not Found
Faili ei leitud.
```
```text
[HKHK] user=mari DOWNLOAD DENIED file=../../../../etc/passwd
```

Lubatud fail töötab endiselt:

```text
[HKHK] user=mari DOWNLOAD path=/var/www/html/files/wifi-juhend.txt
```

## E.4. Küsimused

1. Mida tähendab `../` failiteel?
2. Miks on lubatud failide nimekiri turvalisem kui „keelatud märkide"
   filtreerimine?
3. Mida teeb `realpath()` ja miks see aitab?

---

# LABOR F – Faili üleslaadimine (`upload.php`)

## F.1. Vaata, kuidas süsteem töötab

Logi sisse. Ava „Üleslaadimine". Server kontrollib ainult **kliendi
saadetud** Content-Type päist ja salvestab faili veebijuure alla
kasutaja antud nimega.

## F.2. Kontrollitud test

Kaustas `rundaja/` on fail `test-upload.php` – kahjutu PHP-skript, mis
prindib serveri info. See **tõendab koodi käivitamist**, kui ta serveris
jookseb.

Esiteks näita, et aus üleslaadimine blokeeritakse:

```bash
curl -s -b mari.jar -F "f=@rundaja/test-upload.php" \
  http://192.168.50.10:8000/upload.php
```
```text
<p class='err'>Lubatud on ainult pildifailid (PNG, JPEG, GIF).</p>
```

Nüüd **võltsi** Content-Type päis väärtuseks `image/png` (Burp Suite'is
muudad seda päringut, `curl`-iga paned tüübi juurde):

```bash
curl -s -b mari.jar -F "f=@rundaja/test-upload.php;type=image/png" \
  http://192.168.50.10:8000/upload.php
```
```text
<p class='ok'>Fail salvestatud: <a href='uploads/test-upload.php'>uploads/test-upload.php</a></p>
```

Server uskus kliendi valet. Ava üleslaaditud fail – **PHP käivitub serveris**:

```bash
curl -s http://192.168.50.10:8000/uploads/test-upload.php
```
```text
HKHK LABOR: see PHP-kood kaivitati serveris!
Kasutaja: www-data
Server: Linux ... x86_64
```

Logis:

```text
[HKHK] user=mari UPLOAD name=test-upload.php type=image/png size=290
```

**Tõendus:** ekraanipilt serveris käivitunud skripti väljundist. See on
kõige raskem tagajärg – ründaja saab serveris oma koodi käivitada.

## F.3. Paranda ja testi uuesti

```bash
cp lahendused/config.php src/config.php
cp lahendused/upload.php src/upload.php
cp lahendused/view.php   src/view.php
sudo docker compose restart web
```

Parandus: tegelik tüüp tuvastatakse faili **sisu** järgi (`finfo`), server
genereerib failinime ise, failid salvestatakse **väljaspoole veebijuurt** ja
neid serveeritakse läbi `view.php`.

Korda võlts-PNG rünnakut:

```bash
curl -s -b mari.jar -F "f=@rundaja/test-upload.php;type=image/png" \
  http://192.168.50.10:8000/upload.php
```
```text
<p class='err'>Lubatud on ainult pildifailid (PNG, JPEG, GIF).</p>
```
```text
[HKHK] user=mari UPLOAD name=test-upload.php client_type=image/png size=290
[HKHK] user=mari UPLOAD REJECTED real_type=text/x-php
```

Vana rada ei toimi enam (fail pole veebijuures):

```text
GET /uploads/test-upload.php  →  HTTP/1.1 404 Not Found
```

Päris pilt läheb läbi ja salvestatakse turvalise nimega:

```text
[HKHK] user=mari UPLOAD SAVED 44cbfebbcdd63ad0cc7524ac5dad6c01.png real_type=image/png
```

## F.4. Küsimused

1. Miks ei saa usaldada kliendi saadetud Content-Type väärtust?
2. Miks on ohtlik salvestada üleslaaditud fail kausta, kust PHP saab seda
   käivitada?
3. Miks genereerib turvaline rakendus failinime ise?

---

# 4. Labori lähtestamine ja peatamine

Peata rakendus (andmed jäävad alles):

```bash
sudo docker compose stop
```

Täielik lähtestamine – kustutab andmebaasi ja ehitab puhtalt uuesti (kasulik,
kui tahad labori järgmisele rühmale algseisu viia):

```bash
sudo docker compose down -v
sudo docker compose up -d --build
```

Üks mugav töövoog õppijale: enne iga labori algust tee kõik haavatavad failid
tagasi algseisu `git`-iga või kopeerides varukoopiast, et saaks „enne ja
pärast" selgelt näidata.

---

# 5. Mida õpetaja hoiab enda käes

Kaust `lahendused/` on **õpetaja lahendusvõti**. Soovitus: anna õppijale ainult
haavatav `src/` ja see juhend ilma `lahendused/` kaustata; parandatud failid
jaga alles pärast seda, kui õppija on rünnaku ise tõendanud ja parandusettepaneku
kirjutanud.

---

# 6. Vastavus hindamismudelile

See labor teeb teostatavaks need kaks kriteeriumi, mis Juice Shopi/WebGoatiga
jäid õhku:

| Kriteerium | Kuidas siin täidetakse |
|-----------|------------------------|
| Parandus (1 p) | Õppija kopeerib/kirjutab parameetriseeritud päringu, `htmlspecialchars`, CSRF-tokeni jne ja ehitab konteineri uuesti |
| Paranduse kontroll (1 p) | Õppija kordab täpselt sama `curl`/Burp testi ja näitab, et vastus muutus (nt 200 → 404, või skript → kodeeritud tekst) |

Tõenduseks sobib iga labori puhul kolm asja: ründe HTTP-päring ja -vastus,
vastav logirida, ja sama paar pärast parandust.
