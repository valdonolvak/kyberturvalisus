FROM php:8.3-apache

# PDO MySQL draiver andmebaasiühenduse jaoks
RUN docker-php-ext-install pdo_mysql

# Kaust, kuhu PARANDATUD üleslaadimine faile salvestab (väljaspool veebijuurt)
RUN mkdir -p /var/www/private-uploads

# Käivitamisel anname veebiserverile kirjutusõiguse üleslaadimise kaustadesse
CMD ["bash", "-c", "mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads /var/www/private-uploads && exec apache2-foreground"]
