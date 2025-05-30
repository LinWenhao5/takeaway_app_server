#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x build-app.sh`

# Exit the script if any command fails
set -e

# Build assets using NPM
npm run build

php artisan optimize:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear