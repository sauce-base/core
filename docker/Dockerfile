FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    mysql-dev \
    oniguruma-dev \
    libzip-dev \
    icu-dev \
    git \
    unzip \
    bash \
    nodejs \
    npm \
    supervisor

# Install PHP extensions
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

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure Git to trust the mounted volume directory
RUN git config --global --add safe.directory /var/www

# Set working directory
WORKDIR /var/www

# Expose PHP-FPM port
EXPOSE 9000

# Default command (can be overridden in docker-compose.yml)
CMD ["php-fpm"]
