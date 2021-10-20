FROM docker.io/library/node:16-alpine as npm

WORKDIR /app

COPY . .

RUN yarn install --frozen-lockfile && yarn prod

FROM docker.io/library/composer:2 as composer

COPY . .

COPY --from=npm /app/public ./public

RUN composer install \
        --ignore-platform-reqs \
        --no-interaction \
        --no-plugins \
        --no-progress \
        --no-dev \
        --no-scripts \
        --prefer-dist \
    && find /app -type d -exec chmod -R 555 {} \; \
    && find /app -type f -exec chmod -R 444 {} \; \
    && find /app/storage -type d -exec chmod -R 755 {} \; \
    && find /app/storage -type f -exec chmod -R 644 {} \; \
    && composer dump-autoload

FROM docker.io/existenz/webstack:8.0

RUN apk -U add --no-cache \
        php8-bcmath \
        php8-ctype \
        php8-curl \
        php8-dom \
        php8-exif \
        php8-fileinfo \
        php8-gd \
        php8-iconv \
        php8-intl \
        php8-json \
        php8-mbstring \
        php8-opcache \
        php8-openssl \
        php8-pcntl \
        php8-pdo \
        php8-pdo_mysql \
        php8-pecl-redis \
        php8-phar \
        php8-posix \
        php8-redis \
        php8-session \
        php8-simplexml \
        php8-tokenizer \
        php8-xml \
        php8-xmlreader \
        php8-xmlwriter \
        php8-zip \
    && rm -rf /var/cache/apk/*

RUN rm -rf /www/*
COPY --from=composer --chown=php:nginx /app /www
RUN php8 artisan optimize
