@echo off
echo Run Ahjiz Project
php artisan config:cache
php artisan cache:clear
php artisan view:clear 
php artisan route:clear

start C:\xampp\mysql_start.bat
start chrome.exe  http://127.0.0.1:8000/
php artisan serve
@pause