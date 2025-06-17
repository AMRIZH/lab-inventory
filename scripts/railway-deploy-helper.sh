#!/bin/bash
# Railway Deployment Helper Script
# This script helps prepare your project for Railway deployment

echo "🚂 Lab Inventory - Railway Deployment Helper"
echo "============================================="

# Check if we're in the correct directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the Lab Inventory root directory"
    exit 1
fi

echo "📋 Pre-deployment Checklist:"
echo ""

# Check required files
echo "✅ Checking required files..."
if [ ! -f "Dockerfile" ]; then
    echo "❌ Dockerfile missing"
    exit 1
fi

if [ ! -f "railway.json" ]; then
    echo "❌ railway.json missing"
    exit 1
fi

if [ ! -f ".env.railway" ]; then
    echo "❌ .env.railway template missing"
    exit 1
fi

echo "✅ All required files present"
echo ""

# Environment setup reminder
echo "🔧 Environment Variables Setup:"
echo "When configuring Railway, make sure to set these variables:"
echo ""
echo "Required:"
echo "- APP_KEY (generate with: php artisan key:generate --show)"
echo "- DB_PASSWORD (your Supabase database password)"
echo "- SUPABASE_URL (your Supabase project URL)"
echo "- SUPABASE_KEY (your Supabase anon key)"
echo ""
echo "Automatic:"
echo "- APP_URL=\${{RAILWAY_PUBLIC_DOMAIN}}"
echo "- SUPABASE_REDIRECT_URL=\${{RAILWAY_PUBLIC_DOMAIN}}/auth/callback"
echo "- SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=\${{RAILWAY_PUBLIC_DOMAIN}}/auth/confirm"
echo ""

# Generate APP_KEY
echo "🔑 Generating APP_KEY for Railway:"
if command -v php >/dev/null 2>&1; then
    APP_KEY=$(php artisan key:generate --show)
    echo "APP_KEY=$APP_KEY"
    echo ""
    echo "📋 Copy this APP_KEY to your Railway environment variables"
else
    echo "⚠️  PHP not found. Generate APP_KEY after deployment with: php artisan key:generate --show"
fi
echo ""

# Deployment steps
echo "🚀 Railway Deployment Steps:"
echo "1. Push your code to GitHub"
echo "2. Go to railway.app and create new project"
echo "3. Select 'Deploy from GitHub repo'"
echo "4. Choose your repository"
echo "5. Set environment variables (see list above)"
echo "6. Deploy automatically!"
echo ""
echo "📖 Full guide: RAILWAY-DEPLOYMENT.md"
echo ""

# Supabase setup reminder
echo "🗄️  Don't forget to configure Supabase:"
echo "1. Go to Supabase Dashboard > Authentication > URL Configuration"
echo "2. Set Site URL to your Railway domain"
echo "3. Add redirect URLs:"
echo "   - https://your-app.up.railway.app/auth/callback"
echo "   - https://your-app.up.railway.app/auth/confirm"
echo ""
echo "📖 Supabase guide: SUPABASE-EMAIL-CONFIG.md"
echo ""

echo "✅ Your project is ready for Railway deployment!"
echo "🚀 Good luck with your deployment!"
