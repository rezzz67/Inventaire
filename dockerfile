FROM php:7.4-fpm

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Nettoyage après l'installation
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
