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
    && chmod -R 775 storage bootstrap/cache \
    && mkdir -p storage/framework/sessions \
    && chmod -R 775 storage/framework/sessions \
    && chown -R www-data:www-data storage/framework/sessions
# Instala dependencias de Vite
RUN npm install --legacy-peer-deps

# Expone los puertos para Apache y Vite
EXPOSE 80 5173
# Crear un archivo .env básico si no existe
RUN if [ ! -f .env ]; then touch .env; fi
# Limpia la caché de la aplicación
RUN php artisan config:clear && php artisan cache:clear && php artisan route:clear
# Inicia ambos servidores: Apache y Vite
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && npm run dev --host & apache2-foreground"]




### version 2 de produccion 

# FROM php:8.2-apache

# # Instala PHP extensiones + Node.js
# RUN apt-get update && apt-get install -y \
#     git curl zip unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
#     gnupg ca-certificates \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install pdo pdo_mysql zip gd mbstring bcmath opcache exif \
#     && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
#     && apt-get install -y nodejs \
#     && apt-get clean && rm -rf /var/lib/apt/lists/*

# # Mod rewrite en Apache
# RUN a2enmod rewrite

# # Copia configuración de Apache
# COPY apache.conf /etc/apache2/sites-available/000-default.conf

# # Setea working directory
# WORKDIR /var/www/html

# # Copia todo el código
# COPY . .

# RUN git config --global --add safe.directory /var/www/html

# # Composer y dependencias
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# RUN composer install --no-dev --optimize-autoloader

# # Permisos
# RUN chown -R www-data:www-data storage bootstrap/cache
# RUN chmod -R 775 storage bootstrap/cache

# # Node.js dependencias y build de Vite
# RUN npm install --legacy-peer-deps && npm run build

# # Limpieza y caché de Laravel
# RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# # Exponer solo Apache
# EXPOSE 80

# # Comando default
# CMD ["apache2-foreground"]
