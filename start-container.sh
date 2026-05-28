#!/bin/bash
# Make sure this file has executable permissions, run chmod +x start-app.sh

# Exit the script if any command fails
set -e

# Run database migrations
php artisan migrate --force

# Run database seeder
php artisan db:seed --force

# Generate Swagger documentation
php artisan l5-swagger:generate

