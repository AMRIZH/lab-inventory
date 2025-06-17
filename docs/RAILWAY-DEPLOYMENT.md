# Railway Deployment Guide for Lab Inventory

This guide covers deploying the Lab Inventory Laravel application to Railway using GitHub integration with Supabase as the database.

**🚨 Having deployment issues? See [RAILWAY-TROUBLESHOOTING.md](RAILWAY-TROUBLESHOOTING.md)**

## Prerequisites

1. **GitHub Repository**: Your code should be pushed to GitHub
2. **Railway Account**: Sign up at [railway.app](https://railway.app)
3. **Supabase Project**: Set up at [supabase.com](https://supabase.com)

## Deployment Methods

### Method 1: Railway GitHub Integration (Recommended)

This method automatically deploys from your GitHub repository without needing the Railway CLI.

#### Step 1: Connect GitHub to Railway

1. Go to [railway.app](https://railway.app) and sign in
2. Click "Start a New Project"
3. Select "Deploy from GitHub repo"
4. Connect your GitHub account if not already connected
5. Select your Lab Inventory repository

#### Step 2: Configure Environment Variables

In Railway project settings, add these environment variables:

```bash
# Application Settings
APP_NAME=Lab Inventory
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

# Database Configuration (Supabase)
DB_CONNECTION=pgsql
DB_HOST=db.your-project-ref.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_supabase_db_password

# Supabase Service Configuration
SUPABASE_URL=https://your-project-ref.supabase.co
SUPABASE_KEY=your_supabase_anon_key

# Email Confirmation URLs (Railway will auto-set APP_URL)
SUPABASE_REDIRECT_URL=${{RAILWAY_PUBLIC_DOMAIN}}/auth/callback
SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=${{RAILWAY_PUBLIC_DOMAIN}}/auth/confirm

# Cache and Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=info

# Production Settings
APP_TIMEZONE=UTC
```

#### Step 3: Railway-Specific Variables

Railway provides these automatic variables:
- `${{RAILWAY_PUBLIC_DOMAIN}}` - Your app's public URL
- `${{RAILWAY_ENVIRONMENT}}` - Current environment (production/staging)

#### Step 4: Deploy

1. Railway will automatically detect the `Dockerfile` and build
2. The `railway.json` file configures build and health check settings
3. Deployment typically takes 3-5 minutes

### Method 2: Railway CLI (Alternative)

If you prefer using the CLI:

```bash
# Install Railway CLI
npm install -g @railway/cli

# Login to Railway
railway login

# Initialize project
railway init

# Deploy
railway up
```

## Supabase Configuration

### Database Setup

1. In your Supabase project, note these values:
   - **Project URL**: `https://your-project-ref.supabase.co`
   - **Anon Key**: Found in Settings > API
   - **Database Password**: Set in Settings > Database

### Email Authentication Setup

1. In Supabase Dashboard → Authentication → URL Configuration:
   - **Site URL**: `https://your-app-name.up.railway.app`
   - **Redirect URLs**: Add:
     - `https://your-app-name.up.railway.app/auth/callback`
     - `https://your-app-name.up.railway.app/auth/confirm`

2. In Authentication → Email Templates:
   - Customize confirmation email template if needed
   - The app will handle the redirect automatically

## Project Structure for Railway

```
your-repo/
├── Dockerfile                 # Railway uses this for building
├── railway.json              # Railway deployment configuration
├── docker-compose.yml        # For local development only
├── .env.railway              # Railway environment template
├── app/                      # Laravel application
├── public/                   # Web root
└── storage/                  # Laravel storage (writable)
```

## Key Files for Railway

### `railway.json`
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "DOCKERFILE"
  },
  "deploy": {
    "healthcheckPath": "/health",
    "healthcheckTimeout": 300,
    "restartPolicyType": "ON_FAILURE"
  }
}
```

### Health Check Endpoint

The app includes a health check at `/health` that Railway uses to monitor the application.

## Troubleshooting

### Common Issues

1. **Build Failures**
   - Check Dockerfile syntax
   - Ensure all dependencies are properly specified
   - Check Railway build logs

2. **Environment Variables**
   - Verify all required variables are set in Railway dashboard
   - Use Railway variables like `${{RAILWAY_PUBLIC_DOMAIN}}`
   - Don't hardcode URLs

3. **Database Connection**
   - Verify Supabase credentials
   - Check Supabase project status
   - Test connection from Railway logs

4. **Email Confirmation**
   - Verify redirect URLs in Supabase dashboard
   - Check that APP_URL matches Railway domain
   - Test email flow after deployment

### Logs and Debugging

```bash
# View deployment logs in Railway dashboard
# Or use CLI if installed:
railway logs

# Check specific service logs
railway logs --service your-service-name
```

### Environment-Specific Configuration

Create `.env.railway` for Railway-specific settings:

```bash
# Railway Production Environment
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error

# Use Railway's domain variable
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
SUPABASE_REDIRECT_URL=${{RAILWAY_PUBLIC_DOMAIN}}/auth/callback
SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=${{RAILWAY_PUBLIC_DOMAIN}}/auth/confirm
```

## Monitoring and Maintenance

### Health Monitoring

Railway automatically monitors your app using the `/health` endpoint. You can also:

1. Check Railway dashboard for uptime
2. Monitor resource usage
3. Review deployment history

### Updates and Scaling

1. **Automatic Deployments**: Push to GitHub to trigger deployments
2. **Scaling**: Adjust resources in Railway dashboard
3. **Environment Management**: Use Railway's environment features

## Security Considerations

1. **Environment Variables**: Never commit sensitive data to Git
2. **HTTPS**: Railway provides HTTPS by default
3. **Database**: Supabase handles database security
4. **Session Security**: Configure session settings appropriately

## Cost Optimization

1. **Resource Allocation**: Monitor and adjust based on usage
2. **Caching**: Use file-based caching for better performance
3. **Database**: Monitor Supabase usage and optimize queries

## Support and Resources

- **Railway Documentation**: [docs.railway.app](https://docs.railway.app)
- **Supabase Documentation**: [supabase.com/docs](https://supabase.com/docs)
- **Laravel Documentation**: [laravel.com/docs](https://laravel.com/docs)

## Next Steps

After successful deployment:

1. Test all application features
2. Set up monitoring and alerts
3. Configure custom domain (optional)
4. Set up staging environment
5. Implement CI/CD improvements
