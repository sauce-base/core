FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    oniguruma-dev \
    libzip-dev

# Install essential PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    mbstring \
    zip \
    pcntl

# Install Redis extension via PECL
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Set working directory
WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]