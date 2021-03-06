@echo off
echo Config Al-Rowad Project
ren .env.example .env
composer install
php artisan storage:link
composer dump-autoload
php artisan key:generate
php artisan config:cache
@pause