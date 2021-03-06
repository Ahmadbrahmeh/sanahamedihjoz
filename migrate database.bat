@echo off
echo Migration Database
php artisan migrate:fresh

@pause
