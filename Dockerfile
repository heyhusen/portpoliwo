# Dependencies
FROM docker.io/library/composer:2 as dep

COPY . /app

RUN composer install --optimize-autoloader --no-interaction --no-progress \
    --no-dev --ignore-platform-reqs

# Application
FROM docker.io/trafex/php-nginx:2.2.0 as prod

USER root
RUN apk add --no-cache php8-bcmath php8-fileinfo php8-pdo php8-pdo_mysql \
    php8-tokenizer php8-exif php8-pecl-redis
COPY .container/nginx.conf /etc/nginx/conf.d/server.conf
USER nobody

RUN rm -rf /var/www/html/*
COPY --chown=nginx . /var/www/html
COPY --chown=nginx --from=dep /app/vendor /var/www/html/vendor

EXPOSE 8081
