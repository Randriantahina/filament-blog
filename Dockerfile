# Étape 1: Dépendances PHP avec Composer
FROM composer:2 as vendor
WORKDIR /app
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Étape 2: Dépendances Frontend avec Node
FROM node:18 as frontend
WORKDIR /app
COPY package.json package.json
COPY package-lock.json package-lock.json
RUN npm install
COPY . .
RUN npm run build

# Étape 3: Image finale de production
FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

# Installation des dépendances système et des extensions PHP
RUN apk add --no-cache nginx supervisor curl libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd bcmath exif \
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
