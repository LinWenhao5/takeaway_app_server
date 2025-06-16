#!/bin/bash
# Make sure this file has executable permissions, run chmod +x start-app.sh

# Exit the script if any command fails
set -e

# Clear cache
php artisan optimize:clear

# Cache the various components of the Laravel application
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Run database seeder
php artisan db:seed --force

# Generate Swagger documentation
php artisan l5-swagger:generate

php artisan serve --host=0.0.0.0 --port=${APP_PORT:-8080}