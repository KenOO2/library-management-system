FROM php:8.2-apache

# Instalamos la extensión pdo_mysql, necesaria para conectar PHP con MySQL vía PDO
RUN docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /var/www/html

COPY . .
