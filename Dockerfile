FROM php:8.3-apache

# Activer mod_rewrite pour les URL propres
RUN a2enmod rewrite

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Copier la config Apache
COPY infra/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copier la config PHP
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Définir le dossier de travail
WORKDIR /var/www/html
