# HKHK veebiturbe labor – HAAVATAV PHP/MySQL rakendus

⚠️ **AINULT ISOLEERITUD LABORIVÕRGUS.** See rakendus on tahtlikult katki.
Ära ava seda kunagi Internetti ega tootmisvõrku.

## Kiire käivitus

```bash
nano .env                              # pane serveri laborivõrgu IP
sudo docker compose up -d --build
```

Ava: `http://<serveri-ip>:8000/` — konto `mari` / `mari123`.

## Mis kaustas on

```
docker-compose.yml   konteinerite seadistus (web + db)
.env                 serveri laborivõrgu IP
web/Dockerfile       PHP 8.3 + Apache tõmmis
db/init.sql          laboriandmebaas (väljamõeldud andmed)
src/                 HAAVATAV rakendus (anna õppijale)
lahendused/          PARANDATUD failid (õpetaja lahendusvõti)
rundaja/             ründeabifailid (CSRF-leht, upload-test)
JUHEND.md            samm-sammuline juhend koos päris logidega
```

## Kuus viga

SQL Injection · Broken Access Control (IDOR) · Reflected XSS · CSRF ·
Path Traversal · Unrestricted File Upload.

Täpne töökäik iga vea juures on failis `JUHEND.md`.

## Logid

```bash
sudo docker compose logs -f web                               # rakendus
sudo docker compose exec db tail -f /var/lib/mysql/general.log # SQL
```
