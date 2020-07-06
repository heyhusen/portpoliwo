FROM php:7.4-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install extensions
RUN apk add --no-cache oniguruma-dev libzip-dev

RUN docker-php-ext-install pdo_mysql bcmath exif pcntl mbstring zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user
RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data
USER www-data