#!/bin/bash
# Make sure this file has executable permissions, run chmod +x start-app.sh

# Exit the script if any command fails
set -e

# Run database seeder
php artisan db:seed --force

# Generate Swagger documentation
php artisan l5-swagger:generate

echo "Business startup logic finished."

