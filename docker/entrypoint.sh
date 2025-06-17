#!/bin/bash

echo "🚀 Starting Lab Inventory Application..."

# Test Apache configuration first
echo "🔧 Testing Apache configuration..."
if ! apache2ctl configtest 2>/dev/null; then
    echo "⚠️ Apache configuration test failed, but continuing..."
    echo "📋 Available modules:"
    apache2ctl -M 2>/dev/null | grep -E "(rewrite|headers)" || echo "No rewrite/headers modules found"
fi

# Wait for a moment to ensure all environment variables are loaded
sleep 2

# Check if we have an APP_KEY, if not generate one
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear and rebuild caches
echo "🗄️ Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear application cache with proper error handling
if php artisan cache:clear 2>/dev/null; then
    echo "✅ Application cache cleared successfully"
else
    echo "⚠️ Application cache clear failed, but continuing..."
fi

# Rebuild optimized caches
echo "⚡ Building optimized caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Try to optimize other caches
if php artisan optimize 2>/dev/null; then
    echo "✅ Application optimized successfully"
else
    echo "⚠️ Application optimization skipped"
fi

# Ensure proper permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage 2>/dev/null || echo "⚠️ Storage permissions: some files may be owned by root"
chown -R www-data:www-data /var/www/html/bootstrap/cache 2>/dev/null || echo "⚠️ Cache permissions: some files may be owned by root"
chmod -R 775 /var/www/html/storage 2>/dev/null || echo "⚠️ Storage chmod: some permissions may not be set"
chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || echo "⚠️ Cache chmod: some permissions may not be set"

# Create cache directories if they don't exist
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/bootstrap/cache

# Run database migrations if needed (optional)
if [ "$AUTO_MIGRATE" = "true" ]; then
    echo "🗃️ Running database migrations..."
    php artisan migrate --force
fi

echo "✅ Application ready!"

# Execute the main command
exec "$@"
