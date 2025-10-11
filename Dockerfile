# ===========================
# Étape 1: Dépendances PHP avec Composer
# ===========================
FROM php:8.3-cli-alpine as vendor

RUN apk add --no-cache icu-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev postgresql-dev git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl pdo pdo_pgsql zip gd bcmath exif

WORKDIR /app

# Copier Composer et les fichiers nécessaires
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
COPY database/ database/

# Installer les dépendances PHP sans scripts (plus rapide)
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

# ===========================
# Étape 2: Dépendances Frontend avec Node
# ===========================
FROM node:18 as frontend
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install
COPY . .
RUN npm run build

# ===========================
# Étape 3: Image finale de production
# ===========================
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Installer dépendances système + PHP extensions
RUN apk add --no-cache nginx supervisor curl libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev postgresql-dev icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd bcmath exif intl \
    && docker-php-ext-enable opcache

# Copier les configs Nginx et Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copier les dépendances et le code
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend /app/public/ /var/www/html/public/
COPY . /var/www/html

# Donner les permissions nécessaires
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Générer la clé d’application et découvrir les paquets (Ici Laravel est complet)
RUN cp .env.example .env \
    && php artisan key:generate \
    && php artisan package:discover --ansi

# Exposer le port et démarrer supervisord
EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]