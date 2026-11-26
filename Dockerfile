FROM php:8.4-apache-bookworm AS php-base

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl unzip libfreetype6-dev libjpeg62-turbo-dev libpng-dev libonig-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd mbstring pdo_mysql zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

FROM php-base AS dependencies
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --no-autoloader
COPY . .
RUN mkdir -p bootstrap/cache storage/framework/views \
    && composer dump-autoload --no-dev --optimize \
    && composer check-platform-reqs --no-dev

FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY --from=dependencies /var/www/html/vendor/laravel/framework/src/Illuminate/Pagination/resources/views ./vendor/laravel/framework/src/Illuminate/Pagination/resources/views
RUN npm run build

FROM php-base AS production
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY --from=dependencies /var/www/html /var/www/html
COPY --from=assets /app/public/build ./public/build
COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/app-entrypoint

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && ln -s /var/www/html/storage/app/public public/storage

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD curl --fail --silent http://127.0.0.1/up || exit 1

ENTRYPOINT ["app-entrypoint"]
CMD ["apache2-foreground"]
