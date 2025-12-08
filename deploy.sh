#!/bin/bash
# deploy.sh - Run this after uploading files

echo "🚀 Starting deployment..."

# Install dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Run admin seeder (creates admin user automatically)
php artisan db:seed --class=AdminSeeder --force

# Set proper permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 755 storage bootstrap/cache

echo "✅ Deployment complete!"