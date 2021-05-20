#!/bin/bash
set -e

cd /var/www; php artisan config:cache; rm public/storage -rf; php artisan storage:link; php artisan migrate:refresh --seed --force;
env >> /var/www/.env
php-fpm7.4 -D

nginx -g "daemon off;"
