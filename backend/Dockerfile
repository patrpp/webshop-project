FROM php:8.2-apache

# PHP bővítmények
RUN docker-php-ext-install pdo pdo_mysql

# Composer másolása
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dolgozói mappa
WORKDIR /var/www/html

# Symfony DocumentRoot beállítása a public/ könyvtárra
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Projekt bemásolása
COPY . .

# Függőségek telepítése
RUN composer install
