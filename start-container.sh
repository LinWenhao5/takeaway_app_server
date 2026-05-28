#!/bin/bash
# Make sure this file has executable permissions, run chmod +x start-app.sh

# Exit the script if any command fails
set -e

# Generate Swagger documentation
php artisan l5-swagger:generate

