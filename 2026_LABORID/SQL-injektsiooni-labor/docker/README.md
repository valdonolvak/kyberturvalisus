# Docker-compose variant — SQL-süstimise labor

See kaust käivitab sama haavatava veebilehe **konteinerites** (ei pea serverit
käsitsi seadistama). Sobib hästi su Proxmoxi Debiani web-labori masinasse, kuhu on
paigaldatud Docker.

> ⚠️ **AINULT isoleeritud laborikeskkonnas.** Konteinerid on tahtlikult haavatavad.

## Eeldused

Debiani/Ubuntu masinas:

```bash
sudo apt update
sudo apt install -y docker.io docker-compose-v2
sudo systemctl enable --now docker
```

## Käivitamine

Mine kausta `docker/` ja käivita:

```bash
docker compose up --build -d
```

- Veebileht: `http://<hosti-ip>:8080/index.php`
  (`<hosti-ip>` = masina IP, kus Docker jookseb; Kali sihib seda)
- Andmebaas luuakse automaatselt failist `../andmebaas.sql`.

Peatamine ja täielik puhastus (kustutab ka andmemahu):

```bash
docker compose down -v
```

## Mis on konteinerites

| Teenus | Image | Roll |
|--------|-------|------|
| `web` | `php:8.1-apache` (ehitatud `web/Dockerfile`-ist) | Haavatav veebileht, port 8080 |
| `db` | `mariadb:10.6` | Andmebaas `pood` |

Tahtlikult nõrk seadistus on juba sisse ehitatud:
- PHP `display_errors = On` (`web/haavatav.ini`) — veapõhine süstimine
- MariaDB `secure_file_priv=` ja `local_infile=1` (`db/labor.cnf`) — failioperatsioonid
- veebikasutajal `FILE`-õigus (`db/02-oigused.sql`)
- jagatud köide `upload` db ja web vahel — nii jõuab `INTO OUTFILE` fail veebi

## Laborid Dockeris

Laborid **1–6 ja 8** töötavad täpselt nagu failis `LABORITOOD.md`, ainult kasuta
URL-is porti `:8080`, nt:

```
http://<hosti-ip>:8080/toode.php?id=1 OR 1=1
```

**Labor 7 (webshell):**
- `LOAD_FILE('/etc/passwd')` loeb **db-konteineri** faili `/etc/passwd` (töötab).
- `INTO OUTFILE '/var/www/html/upload/shell.php'` kirjutab jagatud köitesse, mida
  web-konteiner serveerib aadressil `http://<hosti-ip>:8080/upload/shell.php?cmd=id`.

Kui OUTFILE annab õiguste vea, kontrolli, et köide `upload` on kirjutatav
(esmakäivitusel kandub `chmod 777` Dockerfile-ist köitesse).

## VM vs Docker — kumba kasutada?

- **Ühe-VM variant** (`paigaldus.sh` + `haavatav_server.sh`): kõige lähemal pärisele,
  Labor 7 webshell töötab ühe masina failisüsteemis kõige selgemalt.
- **Docker variant**: kiire üles-alla, ei määri ära püsimasinat, sobib korduvaks
  klassiruumis kasutamiseks.
