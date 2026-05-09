#!/bin/sh

# Cache configuration, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear caches
php artisan config:clear
php artisan cache:clear

# Run migrations (force for production)
php artisan migrate --force

# Fix permissions for logs/caches created by artisan as root
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start PHP-FPM in the background
php-fpm -D

# Start Nginx in the foreground
nginx -g "daemon off;"
