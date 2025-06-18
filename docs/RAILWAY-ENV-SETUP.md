# 🚂 Railway Environment Variables Setup

Copy these variables to your Railway project environment settings:

## Required Variables (Copy these exactly)

```bash
APP_NAME="Lab Inventory"
APP_ENV=production
APP_KEY=base64:FLkfOrzIgcbQV3/EtQ3mcjMRnHsFP1kxf0Cngirnw6w=
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

LOG_CHANNEL=stack
LOG_LEVEL=error

# Supabase Database Configuration
DB_CONNECTION=pgsql
DB_HOST=db.icdscorcuiucwipusohb.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=Alexgaming123!

# Supabase Service Configuration
SUPABASE_URL=https://icdscorcuiucwipusohb.supabase.co
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImljZHNjb3JjdWl1Y3dpcHVzb2hiIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDkyMjA1NTEsImV4cCI6MjA2NDc5NjU1MX0.Vu2iXcsqM6aJnGP4qp0WiMLeDTryZTE_jtwybp7d800

# Email Confirmation URLs (Railway will auto-replace ${{RAILWAY_PUBLIC_DOMAIN}})
SUPABASE_REDIRECT_URL=${{RAILWAY_PUBLIC_DOMAIN}}/auth/callback
SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=${{RAILWAY_PUBLIC_DOMAIN}}/auth/confirm

# Cache and Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# File System
FILESYSTEM_DISK=local

# Queue
QUEUE_CONNECTION=sync

# Broadcasting
BROADCAST_DRIVER=log
```

## Important Notes:

1. **APP_URL**: Use `${{RAILWAY_PUBLIC_DOMAIN}}` (Railway will replace this automatically)
2. **APP_DEBUG**: Set to `false` for production
3. **APP_ENV**: Set to `production`
4. **LOG_LEVEL**: Set to `error` or `info` for production

## Health Check Issue Fix:

The health check is failing because the service isn't starting. This is usually because:

1. **Missing environment variables** (most likely cause)
2. **Database connection fails**
3. **Application errors during startup**

## Quick Fix Steps:

1. **Add ALL the variables above to Railway**
2. **Redeploy the service**
3. **Check Railway logs for any errors**

The service should start successfully once all environment variables are properly set.
