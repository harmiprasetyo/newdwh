FROM php:7.4-fpm
RUN apt-get update && ap-get install -y \
    git curl zip unzip liboniq-dev libxml2-dev libzip-dev libicu-dev\
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql mbstring xml intl zip gd opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR  /var/www/html
COPY . .
EXPOSE 9000

CMD ["php-fpm"]
