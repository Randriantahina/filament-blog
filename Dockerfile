# Étape 1: Dépendances PHP avec Composer, en utilisant PHP 8.3
FROM php:8.3-cli-alpine as vendor
WORKDIR /app

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers et installer
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Étape 2: Dépendances Frontend avec Node
FROM node:18 as frontend
WORKDIR /app
COPY package.json package.json
RUN npm install
COPY . .
RUN npm run build

# Étape 3: Image finale de production
FROM php:8.3-fpm-alpine
WORKDIR /var/www/html

# Installation des dépendances système et des extensions PHP
RUN apk add --no-cache nginx supervisor curl libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev postgresql-dev icu-dev 
    && docker-php-ext-configure gd --with-freetype --with-jpeg 
    && docker-php-ext-install pdo pdo_pgsql zip gd bcmath exif intl 

    && docker-php-ext-enable opcache

# Copie des fichiers de configuration pour Nginx et Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copie des dépendances et du code
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend /app/public/ /var/www/html/public/
COPY --from=frontend /app/vendor/ /var/www/html/vendor/
COPY . /var/www/html

# Définition des permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposition du port et commande de démarrage
EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
