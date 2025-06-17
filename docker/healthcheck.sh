#!/bin/bash

echo "🏥 Health Check for Lab Inventory Application"
echo "=============================================="

# Check if Apache is running
if pgrep apache2 > /dev/null; then
    echo "✅ Apache is running"
else
    echo "❌ Apache is not running"
    exit 1
fi

# Check if the application responds
if curl -f -s http://localhost/ > /dev/null; then
    echo "✅ Application is responding"
else
    echo "❌ Application is not responding"
    exit 1
fi

# Check Laravel status
cd /var/www/html
if php artisan about --only=environment 2>/dev/null | grep -q "production\|local"; then
    echo "✅ Laravel is working"
else
    echo "❌ Laravel has issues"
    exit 1
fi

echo "✅ All health checks passed!"
exit 0
