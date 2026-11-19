#!/usr/bin/env bash
# =====================================================================
#  paigaldus.sh — LAMP-i ja haavatava veebilehe seadistamine
#  Sihtmasin: Ubuntu Server 22.04/24.04 või Debian 12 (isoleeritud labor)
#
#  Käivita juurkasutajana (root) või sudo-ga:
#      sudo bash paigaldus.sh
# =====================================================================
set -e

VEEBIKAUST="/var/www/html"

echo "[1/5] Pakettide uuendamine ja LAMP-i paigaldamine..."
apt-get update
# apache2 = veebiserver, mariadb-server = andmebaas,
# php + php-mysqli = PHP koos MySQL-laiendiga
DEBIAN_FRONTEND=noninteractive apt-get install -y \
    apache2 mariadb-server php php-mysqli libapache2-mod-php

echo "[2/5] Teenuste käivitamine..."
systemctl enable --now apache2
systemctl enable --now mariadb

echo "[3/5] Andmebaasi loomine skriptist andmebaas.sql..."
# Loome andmebaasi, tabelid, testandmed ja veebikasutaja
mysql < andmebaas.sql

echo "[4/5] Veebilehe failide kopeerimine kausta $VEEBIKAUST ..."
cp config.php index.php toode.php login.php otsing.php toode_turvaline.php "$VEEBIKAUST"/
# Eemaldame Apache vaikelehe, et index.php oleks avaleht
rm -f "$VEEBIKAUST"/index.html
chown -R www-data:www-data "$VEEBIKAUST"

echo "[5/5] Valmis!"
IP=$(hostname -I | awk '{print $1}')
echo "---------------------------------------------------------------"
echo " Veebileht töötab aadressil:  http://$IP/index.php"
echo " Tootevaade (haavatav):       http://$IP/toode.php?id=1"
echo " Otsing (haavatav):           http://$IP/otsing.php"
echo " Sisselogimine (haavatav):    http://$IP/login.php"
echo " Turvaline tootevaade:        http://$IP/toode_turvaline.php?id=1"
echo "---------------------------------------------------------------"
echo " Ava Kali Linuxis brauser või kasuta curl/sqlmap-i ründamiseks."
