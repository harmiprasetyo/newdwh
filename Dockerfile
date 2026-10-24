
FROM php:8.5.12-fpm

# =====================================================
# SYSTEM DEPENDENCIES + LIBREOFFICE
# =====================================================
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libreoffice-impress \
    fonts-dejavu-core \
    fonts-liberation \
    fontconfig \
    && rm -rf /var/lib/apt/lists/*

# =====================================================
# PHP EXTENSIONS
# =====================================================
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install -j1 pdo_mysql
RUN docker-php-ext-install -j1 mbstring
RUN docker-php-ext-install -j1 xml
RUN docker-php-ext-install -j1 intl
RUN docker-php-ext-install -j1 zip
RUN docker-php-ext-install -j1 gd

# OPcache may already be available depending on image.
RUN php -m | grep -qi '^Zend OPcache$' \
    || docker-php-ext-install -j1 opcache

# =====================================================
# FONTS
# =====================================================
RUN fc-cache -f

# =====================================================
# COMPOSER
# =====================================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =====================================================
# APPLICATION
# =====================================================
WORKDIR /var/www/html

COPY . .

EXPOSE 9000

CMD ["php-fpm"]
