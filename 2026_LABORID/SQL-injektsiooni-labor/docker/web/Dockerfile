# =====================================================================
#  Dockerfile — haavatav PHP + Apache veebiserver
#  Ehitamise kontekst on projekti juurkaust (vt docker-compose.yml)
# =====================================================================
FROM php:8.1-apache

# Paigaldame mysqli-laiendi (PHP <-> MySQL/MariaDB ühendus)
RUN docker-php-ext-install mysqli

# Kopeerime veebilehe failid (ehitamise kontekst = projekti juur)
COPY config.php index.php toode.php login.php otsing.php toode_turvaline.php /var/www/html/

# Tahtlikult nõrk PHP seadistus (veateadete kuvamine)
COPY docker/web/haavatav.ini /usr/local/etc/php/conf.d/zz-haavatav.ini

# Maailmale kirjutatav upload-kaust webshelli demoks (Labor 7).
# chmod 777 kandub esmakäivitusel ka jagatud "upload" köitesse.
RUN mkdir -p /var/www/html/upload && chmod 777 /var/www/html/upload
