# ----------------------------
# Étape 1 : Base PHP avec extensions
# ----------------------------
FROM php:8.2-fpm

# Installer les dépendances système et extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpq-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y default-mysql-client    

# ----------------------------
# Étape 2 : Installer Composer
# ----------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ----------------------------
# Étape 3 : Préparer le projet
# ----------------------------
WORKDIR /var/www

# Copier tout le projet d’un coup (pour que artisan soit dispo)
COPY . .

# Définir les permissions correctes
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# ----------------------------
# Étape 4 : Installer les dépendances PHP via Composer
# ----------------------------
RUN composer install --no-dev --optimize-autoloader

# ----------------------------
# Étape 5 : Découverte des packages Laravel
# ----------------------------
RUN php artisan package:discover --ansi

# ----------------------------
# Étape 6 : Commande par défaut
# ----------------------------
CMD ["php-fpm"]

# Expose le port par défaut de PHP-FPM
EXPOSE 9000
