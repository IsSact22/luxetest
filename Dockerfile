FROM php:8.2-apache

# Instala dependencias del sistema y Node.js
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    gnupg ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring bcmath opcache exif \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite para Apache
RUN a2enmod rewrite

# Copia y habilita la configuración personalizada de Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el código fuente
COPY . .
RUN git config --global --add safe.directory /var/www/html
# Configura Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-scripts --no-progress --prefer-dist \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Instala dependencias de Vite
RUN npm install --legacy-peer-deps

# Expone los puertos para Apache y Vite
EXPOSE 80 5173

# Inicia ambos servidores: Apache y Vite
CMD ["sh", "-c", "npm run dev --host & apache2-foreground"]