cd /code

# composer
composer install

php artisan key:generate

# cache clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

chmod -R 777 /code/storage bootstrap/cache

# roadrunner
composer server:roadrunner:setup
chmod +x rr
composer server:roadrunner:dev
