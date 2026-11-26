#!/bin/sh
set -eu

: "${APP_KEY:?Set APP_KEY in the container environment}"

mkdir -p storage/app/private storage/app/public \
    storage/framework/cache/data storage/framework/sessions \
    storage/framework/views storage/fonts storage/logs bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage bootstrap/cache

exec docker-php-entrypoint "$@"
