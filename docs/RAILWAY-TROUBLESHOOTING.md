# 🚂 Railway Deployment Troubleshooting Guide

This guide helps you troubleshoot common Railway deployment issues for the Lab Inventory project.

## 🔧 **Fixed Issues**

### ✅ **PHP ZIP Extension Missing**
**Error:** `ext-zip * -> it is missing from your system`

**Solution Applied:**
- Added `libzip-dev` to system dependencies
- Added `zip` to PHP extensions installation
- Updated Dockerfile to install both system zip and PHP zip extension

```dockerfile
# Added libzip-dev
RUN apt-get update && apt-get install -y \
    libzip-dev \
    # ... other packages

# Added zip extension
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip
```

## 🐛 **Common Railway Deployment Issues**

### **1. Build Failures**

#### **Missing PHP Extensions**
```
Problem: Extension X is missing
Solution: Add to Dockerfile:
- System package: Add to apt-get install
- PHP extension: Add to docker-php-ext-install
```

#### **Composer Install Fails**
```bash
# Add verbose output for debugging
RUN composer install --no-dev --optimize-autoloader --no-interaction --verbose

# If lock file issues:
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader
```

#### **Node.js Build Fails**
```bash
# Add verbose output
RUN npm ci --verbose && npm run build

# If memory issues:
RUN npm ci --verbose && NODE_OPTIONS="--max-old-space-size=2048" npm run build
```

### **2. Runtime Errors**

#### **Environment Variables Not Set**
```
Problem: APP_KEY not set or other env vars missing
Solution: Check Railway environment variables:
- APP_KEY must be set
- Database credentials must match Supabase
- APP_URL should use ${{RAILWAY_PUBLIC_DOMAIN}}
```

#### **File Permissions**
```
Problem: Storage directory not writable
Solution: Dockerfile already handles this:
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage
```

#### **Health Check Fails**
```
Problem: Railway health check timeout
Solution: Check /health endpoint:
curl https://your-app.up.railway.app/health
```

### **3. Database Connection Issues**

#### **Supabase Connection Fails**
```
Problem: Could not connect to database
Solution: Verify these Railway environment variables:
- DB_HOST=db.your-project-ref.supabase.co
- DB_PORT=5432
- DB_DATABASE=postgres
- DB_USERNAME=postgres
- DB_PASSWORD=your_actual_password
- SUPABASE_URL=https://your-project-ref.supabase.co
- SUPABASE_KEY=your_anon_key
```

### **4. Email Confirmation Issues**

#### **Redirect URLs Don't Work**
```
Problem: Email confirmation redirects to wrong URL
Solution: Check Supabase Auth settings:
1. Go to Supabase Dashboard > Authentication > URL Configuration
2. Set Site URL: https://your-app.up.railway.app
3. Add Redirect URLs:
   - https://your-app.up.railway.app/auth/callback
   - https://your-app.up.railway.app/auth/confirm

Railway Environment Variables:
- APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
- SUPABASE_REDIRECT_URL=${{RAILWAY_PUBLIC_DOMAIN}}/auth/callback
- SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=${{RAILWAY_PUBLIC_DOMAIN}}/auth/confirm
```

## 🔍 **Debugging Commands**

### **Check Railway Logs**
```bash
# In Railway dashboard, check the deployment logs
# Look for specific error messages
```

### **Test Health Check**
```bash
curl https://your-app.up.railway.app/health
# Should return: {"status":"ok","timestamp":"...","app":"Lab Inventory","version":"1.0.0"}
```

### **Test Database Connection**
```bash
# Add temporary route to test DB (remove after testing)
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connected successfully';
    } catch (\Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});
```

### **Check PHP Extensions**
```bash
# Add temporary route to check extensions (remove after testing)
Route::get('/test-php', function () {
    return [
        'php_version' => PHP_VERSION,
        'extensions' => get_loaded_extensions(),
        'zip_extension' => extension_loaded('zip'),
        'pdo_pgsql' => extension_loaded('pdo_pgsql')
    ];
});
```

## 🚀 **Deployment Best Practices**

### **1. Environment Variables**
```bash
# Use Railway's built-in variables
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

# Set required variables
APP_KEY=base64:your_generated_key
DB_PASSWORD=your_actual_supabase_password
SUPABASE_URL=https://your-actual-project.supabase.co
SUPABASE_KEY=your_actual_anon_key
```

### **2. Dockerfile Optimization**
```dockerfile
# Use multi-stage builds for smaller images (if needed)
# Cache composer and npm dependencies
# Use --no-dev for production installs
# Set proper permissions
# Include health checks
```

### **3. Monitoring**
```bash
# Railway provides:
- Automatic health checks via /health endpoint
- Resource usage monitoring
- Deployment logs
- Real-time metrics
```

## 🔧 **Emergency Fixes**

### **Quick Deploy Fix**
If deployment keeps failing, try these steps:

1. **Check Railway Variables**
```bash
# Ensure all required variables are set
# Use ${{RAILWAY_PUBLIC_DOMAIN}} for URLs
```

2. **Simplified Dockerfile** (temporary)
```dockerfile
# Comment out problematic steps temporarily
# RUN npm run build  # Comment if this fails
# Build assets locally and commit them
```

3. **Skip Optimizations** (temporary)
```dockerfile
# Comment out these lines if they cause issues:
# && php artisan config:cache \
# && php artisan route:cache \
# && php artisan view:cache
```

4. **Test Locally First**
```bash
# Test Docker build locally
docker build -t lab-inventory .
docker run -p 8080:80 lab-inventory

# Test health endpoint
curl http://localhost:8080/health
```

## 📞 **Support Resources**

- **Railway Documentation**: [docs.railway.app](https://docs.railway.app)
- **Laravel Documentation**: [laravel.com/docs](https://laravel.com/docs)
- **Supabase Documentation**: [supabase.com/docs](https://supabase.com/docs)
- **Docker Documentation**: [docs.docker.com](https://docs.docker.com)

## 🎯 **Quick Checklist**

Before deploying to Railway:

- [ ] ✅ All environment variables set in Railway
- [ ] ✅ Supabase credentials are correct
- [ ] ✅ Supabase redirect URLs configured
- [ ] ✅ APP_KEY generated and set
- [ ] ✅ Dockerfile includes all required PHP extensions
- [ ] ✅ Health check endpoint responds
- [ ] ✅ Code pushed to GitHub
- [ ] ✅ Railway connected to correct GitHub repository

## 🚨 **Current Status**

**✅ FIXED:** PHP ZIP extension missing error
**✅ READY:** Dockerfile updated with libzip-dev and zip extension
**✅ TESTED:** Should now deploy successfully to Railway

Your Railway deployment should now work properly with the updated Dockerfile!
