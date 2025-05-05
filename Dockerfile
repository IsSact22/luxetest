FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \ 
    git curl zip unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    gnupg ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring bcmath opcache exif \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

WORKDIR /var/www/html

COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader
RUN npm install --legacy-peer-deps
RUN npm run build

RUN chown -R www-data:www-data storage bootstrap/cache public/build
RUN chmod -R 775 storage bootstrap/cache public/build

EXPOSE 80

CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && npm run dev & apache2-foreground"]


