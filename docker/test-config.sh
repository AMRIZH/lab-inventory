#!/bin/bash

echo "🔧 Testing Apache Configuration..."

# Test Apache configuration
if apache2ctl configtest; then
    echo "✅ Apache configuration is valid"
else
    echo "❌ Apache configuration has errors"
    echo "📋 Available Apache modules:"
    apache2ctl -M | grep -E "(rewrite|headers)"
fi

# Test PHP
echo "🐘 Testing PHP..."
php --version

# Test Laravel
echo "🎯 Testing Laravel..."
cd /var/www/html
if php artisan --version; then
    echo "✅ Laravel is working"
else
    echo "❌ Laravel has issues"
fi

# Check permissions
echo "🔐 Checking permissions..."
ls -la storage/
ls -la bootstrap/cache/

echo "🌐 Starting Apache..."
