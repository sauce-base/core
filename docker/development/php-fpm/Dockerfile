FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    mysql-dev \
    oniguruma-dev \
    libzip-dev \
    icu-dev

# Install essential PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    pcntl \
    intl \
    exif

# Install Redis and Xdebug extensions via PECL
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
    && pecl install redis xdebug \
    && docker-php-ext-enable redis xdebug \
    && apk del .build-deps

# PHP configuration files will be mounted as volumes in docker-compose.yml

# Set working directory
WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]