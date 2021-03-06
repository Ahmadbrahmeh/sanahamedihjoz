@echo off
echo Clear Cache
php artisan config:cache
php artisan cache:clear
php artisan view:clear 
php artisan route:clear
@pause