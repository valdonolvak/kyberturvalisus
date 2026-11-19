# SQL-süstimise labor (SQL injection)

Haavatav PHP/MySQL veebileht koos ründe- ja kaitseülesannetega.
Teema: **veebirakenduste turvalisus — SQL-süstimine**.

> ⚠️ **HOIATUS.** Kõik failid on **tahtlikult haavatavad** ja mõeldud **ainult
> isoleeritud laborikeskkonnas** (Proxmox SDN VNet, ligipääsuta väljastpoolt)
> õppe-eesmärgil. Ründamist tohib harjutada ainult selles laboris — võõraste
> süsteemide ründamine on seadusega karistatav.

## Laborikeskkond

| Masin | Roll |
|-------|------|
| Ubuntu / Debian server | Ohver — haavatav PHP + MariaDB veebileht |
| Kali Linux | Ründaja — brauser, `curl`, `sqlmap`, `hashcat`/`john` |
| Windows Server 2019 | Taustakeskkond (AD/DNS/DHCP) |

## Ülesseadmine — kaks võimalust

**1) Otse Ubuntu/Debiani serveris:**
```bash
sudo bash paigaldus.sh          # LAMP + veebileht + andmebaas
sudo bash haavatav_server.sh    # teeb serveri tahtlikult nõrgaks
```

**2) Dockeris** (vt `docker/README.md`):
```bash
cd docker && docker compose up --build -d
# http://<hosti-ip>:8080/index.php
```

## Failid

| Fail | Sisu |
|------|------|
| `PAIGALDUS.md` | Paigaldusjuhend + 8 rünnakunäidet + kaitsemeetmed |
| `LABORITOOD.md` | Versioonijuhend + 8 sammsammulist laboriülesannet |
| `andmebaas.sql` | Andmebaasi + testandmete loomine |
| `config.php` | Andmebaasiühendus (localhost või Docker env) |
| `index.php` | Avaleht (turvaline) |
| `toode.php` | **HAAVATAV** — URL-põhine SQLi (`?id=`) |
| `login.php` | **HAAVATAV** — autentimisest möödahiilimine |
| `otsing.php` | **HAAVATAV** — stacked queries (andmete muutmine) |
| `toode_turvaline.php` | **KAITSTUD** näide (prepared statement) |
| `paigaldus.sh` | LAMP-i ja lehe seadistus (VM) |
| `haavatav_server.sh` | Serveri tahtlik nõrgestamine (VM) |
| `docker/` | docker-compose variant |

## Laborite ülevaade (vt `LABORITOOD.md`)

1. Luure ja haavatavuse tuvastamine
2. UNION-põhine andmeleke (paroolide vargus)
3. Andmebaasi kaardistamine (`information_schema`)
4. Autentimisest möödahiilimine + paroolide murdmine
5. Andmete manipuleerimine (stacked queries)
6. Pime süstimine + `sqlmap`
7. *(edasijõudnutele)* failide lugemine/webshell (SQLi → RCE)
8. Kaitse ja uuesti testimine
