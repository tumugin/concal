#!/usr/bin/env sh
set -eux

cd /code

# composer
composer install --ignore-platform-reqs

# cache clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# roadrunner
composer server:roadrunner:setup
chmod +x rr
composer server:roadrunner:dev
