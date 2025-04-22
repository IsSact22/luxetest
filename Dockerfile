# Etapa 1: Laravel + Apache
FROM php:8.2-apache AS backend

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd \
    && docker-php-ext-install bcmath exif \
    && a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos del proyecto
COPY . .

# Crear directorios necesarios
RUN mkdir -p storage bootstrap/cache

# Cambiar el root de Apache a /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Cambiar a www-data antes de ejecutar composer install
USER www-data

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --no-progress --prefer-dist --no-suggest --ignore-platform-reqs

# Volver a cambiar al usuario root para ejecutar posteriores comandos relacionados a permisos
USER root

# Cambiar owner y permisos después de la instalación
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Exponer el puerto de Apache
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]

# Etapa 2: Frontend con Vite
FROM node:18-alpine AS frontend

WORKDIR /app
COPY . .

# Instalar dependencias de frontend
RUN npm install

# Exponer el puerto de Vite
EXPOSE 5173