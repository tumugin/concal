#!/usr/bin/env sh

cd /code

# composer
composer install

# cache clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# roadrunner
composer server:roadrunner:setup
chmod +x rr
composer server:roadrunner:dev
