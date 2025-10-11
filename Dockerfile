# Étape 1: Dépendances PHP avec Composer, en utilisant PHP 8.3
# On utilise une image PHP 8.3, on y installe les dépendances système et l'extension 'intl'
# puis on copie l'exécutable de Composer pour installer les dépendances du projet.
FROM php:8.3-cli-alpine as vendor
RUN apk add --no-cache icu-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl pdo pdo_pgsql zip gd bcmath exif
WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock
COPY .env.example .env
RUN php artisan key:generate
RUN composer install --no-interaction --no-dev --optimize-autoloader -vvv

# Étape 2: Dépendances Frontend avec Node
# On construit les assets CSS/JS dans une image Node séparée.
FROM node:18 as frontend
WORKDIR /app
COPY package.json package.json
RUN npm install
COPY . .
RUN npm run build

# Étape 3: Image finale de production
# On part d'une image PHP-FPM 8.3 légère et on y installe Nginx, Supervisor,
# et toutes les extensions PHP nécessaires pour l'application.
FROM php:8.3-fpm-alpine
WORKDIR /var/www/html

# Installation des dépendances système et des extensions PHP
RUN apk add --no-cache nginx supervisor curl libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev postgresql-dev icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd bcmath exif intl \
    && docker-php-ext-enable opcache

# Copie des fichiers de configuration pour Nginx et Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copie des dépendances et du code depuis les étapes précédentes
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend /app/public/ /var/www/html/public/
COPY . /var/www/html

# Définition des permissions pour le stockage et le cache de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposition du port et commande de démarrage
EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]