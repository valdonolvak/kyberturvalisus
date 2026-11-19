#!/usr/bin/env bash
# =====================================================================
#  haavatav_server.sh — teeb Ubuntu serveri TAHTLIKULT nõrgaks
#  (õppelabori jaoks, AINULT isoleeritud VNet-is!)
#
#  Käivita PÄRAST paigaldus.sh-d:
#      sudo bash haavatav_server.sh
#
#  Mida see teeb:
#   1. Lülitab PHP veateadete kuvamise SISSE (display_errors = On)
#      -> võimaldab veapõhist (error-based) süstimist.
#   2. Lubab MySQL/MariaDB failioperatsioonid (secure_file_priv tühjaks,
#      local_infile sisse) -> võimaldab LOAD_FILE ja INTO OUTFILE ründeid.
#   3. Annab veebikasutajale FILE-õiguse (globaalne) -> failide lugemine/
#      kirjutamine SQLi kaudu.
#   4. Loob maailmale kirjutatava kausta /var/www/html/upload (chmod 777)
#      -> veebimahuti (webshell) kirjutamise demo (SQLi -> RCE).
#
#  HOIATUS: see on turvaauk turvaaugu otsas. Mitte kunagi tootmises!
# =====================================================================
set -e

echo "[1/4] PHP: lülitan veateadete kuvamise sisse..."
# Leiame kõik Apache php.ini failid (PHP versioon kaustanimes, nt 8.1)
for INI in /etc/php/*/apache2/php.ini; do
    [ -f "$INI" ] || continue
    sed -i 's/^display_errors = Off/display_errors = On/'               "$INI"
    sed -i 's/^display_startup_errors = Off/display_startup_errors = On/' "$INI"
    sed -i 's/^;error_reporting = .*/error_reporting = E_ALL/'          "$INI"
    echo "    muudetud: $INI"
done

echo "[2/4] MySQL/MariaDB: lubatud failioperatsioonid..."
# Kirjutame seadistuse mõlemale mootorile loetavasse kausta
CNF_DIR="/etc/mysql/conf.d"
[ -d "$CNF_DIR" ] || CNF_DIR="/etc/mysql/mariadb.conf.d"
cat > "$CNF_DIR/labor.cnf" <<'EOF'
[mysqld]
# Lubab lugeda/kirjutada faile ükskõik kuhu (TAHTLIK turvaauk)
secure_file_priv=
local_infile=1
EOF
echo "    kirjutatud: $CNF_DIR/labor.cnf"

echo "[3/4] Veebikasutajale FILE-õigus (globaalne)..."
mysql <<'EOF'
GRANT FILE ON *.* TO 'veebipood'@'localhost';
FLUSH PRIVILEGES;
EOF

echo "[4/4] Maailmale kirjutatav upload-kaust (webshell-demo jaoks)..."
mkdir -p /var/www/html/upload
chmod 777 /var/www/html/upload

echo "Taaskäivitan teenused..."
systemctl restart apache2
# Mõnes süsteemis on teenus 'mysql', teises 'mariadb'
systemctl restart mysql 2>/dev/null || systemctl restart mariadb

echo "---------------------------------------------------------------"
echo " Server on nüüd TAHTLIKULT haavatav. Kontrolli versioone:"
echo "---------------------------------------------------------------"
php -v | head -n1
apache2 -v | head -n1
mysql --version
echo "---------------------------------------------------------------"
