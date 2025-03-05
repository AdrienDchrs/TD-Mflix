FROM php:8.2-apache

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Activer les modules Apache pour le réécriture d'URL
RUN a2enmod rewrite

# Copier le code source de l'application
COPY . /var/www/html/

# Installer Composer et les dépendances
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN composer install

EXPOSE 80
