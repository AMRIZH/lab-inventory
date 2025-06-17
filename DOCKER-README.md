# 🐳 Docker Deployment Guide - Lab Inventory

Complete Docker deployment guide for the Lab Inventory System with **Supabase** database integration and **email confirmation** support.

## 🌟 Features

- ✅ **Production-ready** Docker configuration
- ✅ **Supabase PostgreSQL** database integration  
- ✅ **Email confirmation** with dynamic redirect URLs
- ✅ **Apache web server** with optimized configuration
- ✅ **Health checks** and monitoring
- ✅ **Multi-environment** support (dev, staging, production)
- ✅ **Auto-restart** policies
- ✅ **Volume persistence** for storage and cache

## � Quick Start

### Prerequisites
- **Docker 20.10+** and **Docker Compose 2.0+**
- **Supabase account** with project setup
- **8GB RAM** minimum for optimal performance
- **Port 8080** available for the application

### 1. One-Click Deployment

**Easiest way (Windows):**
```cmd
.\docker-build.bat
```

**Or using Docker Compose:**
```bash
docker-compose up -d --build
```

### 2. Access Application

- **Main App**: http://localhost:8080
- **Dashboard**: http://localhost:8080/dashboard  
- **Lab FKI**: http://localhost:8080/labs/lab-fki
- **Export**: http://localhost:8080/export

## 🔧 Configuration

### Environment Setup

The application uses these key environment configurations:

#### **Current Configuration (docker-compose.yml)**
```yaml
environment:
  - APP_NAME=Lab Inventory
  - APP_ENV=production
  - APP_URL=http://localhost:8080
  
  # Supabase Database
  - DB_CONNECTION=pgsql
  - DB_HOST=db.icdscorcuiucwipusohb.supabase.co
  - DB_PORT=5432
  - DB_DATABASE=postgres
  - DB_USERNAME=postgres
  - DB_PASSWORD=${SUPABASE_DB_PASSWORD:-Alexgaming123!}
  
  # Supabase Services
  - SUPABASE_URL=https://icdscorcuiucwipusohb.supabase.co
  - SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
  
  # Email Confirmation URLs (Dynamic)
  - SUPABASE_REDIRECT_URL=${APP_URL}/auth/callback
  - SUPABASE_EMAIL_CONFIRM_REDIRECT_URL=${APP_URL}/auth/confirm
```

### Custom Environment Variables

You can override default values by setting environment variables:

```bash
# Set custom database password
export SUPABASE_DB_PASSWORD="your_secure_password"

# Set custom app URL for production
export APP_URL="https://yourdomain.com"

# Run with custom settings
docker-compose up -d
```

## � Supabase Setup & Configuration

### 1. Supabase Project Setup

1. **Get Database Credentials**:
   - Go to [Supabase Dashboard](https://supabase.com/dashboard)
   - Navigate to **Settings** → **Database**
   - Copy your database password
   - Note the connection string details

2. **Configure Authentication**:
   - Navigate to **Authentication** → **Settings** → **URL Configuration**
   - Add redirect URLs:
     ```
     http://localhost:8080/auth/confirm
     http://localhost:8080/auth/callback
     https://yourdomain.com/auth/confirm    (for production)
     https://yourdomain.com/auth/callback   (for production)
     ```

3. **Email Templates** (Optional):
   - Navigate to **Authentication** → **Templates**
   - Customize email confirmation templates
   - Set your brand colors and logo

### 2. Environment Variables Reference

| Variable | Description | Default | Required |
|----------|-------------|---------|----------|
| `APP_URL` | Application base URL | `http://localhost:8080` | ✅ |
| `APP_ENV` | Environment mode | `production` | ✅ |
| `DB_PASSWORD` | Supabase database password | `Alexgaming123!` | ✅ |
| `SUPABASE_URL` | Supabase project URL | Pre-configured | ✅ |
| `SUPABASE_KEY` | Supabase anon key | Pre-configured | ✅ |
| `SUPABASE_REDIRECT_URL` | Auth callback URL | `${APP_URL}/auth/callback` | ✅ |
| `SUPABASE_EMAIL_CONFIRM_REDIRECT_URL` | Email confirmation URL | `${APP_URL}/auth/confirm` | ✅ |
| `AUTO_MIGRATE` | Run migrations on startup | `false` | ❌ |

### 3. Multi-Environment Support

The Docker configuration automatically adapts to different environments:

#### **Development (localhost:8080)**
```yaml
environment:
  - APP_URL=http://localhost:8080
  - APP_ENV=production
  - APP_DEBUG=false
```

#### **Staging (staging.yourdomain.com)**
```yaml
environment:
  - APP_URL=https://staging.yourdomain.com
  - APP_ENV=staging
  - APP_DEBUG=true
```

#### **Production (yourdomain.com)**
```yaml
environment:
  - APP_URL=https://yourdomain.com
  - APP_ENV=production
  - APP_DEBUG=false
```

## 🚀 Deployment Options

### Option 1: Quick Deploy (Recommended)

**Windows Users:**
```cmd
.\docker-build.bat
```

**Linux/Mac Users:**
```bash
chmod +x docker-build.sh
./docker-build.sh
```

### Option 2: Docker Compose

**Basic deployment:**
```bash
docker-compose up -d
```

**With custom password:**
```bash
SUPABASE_DB_PASSWORD="your_password" docker-compose up -d
```

**Production deployment:**
```bash
# Set production environment
export APP_URL="https://yourdomain.com"
export SUPABASE_DB_PASSWORD="your_secure_password"

# Deploy
docker-compose up -d --build
```

### Option 3: Manual Docker

**Build image:**
```bash
docker build -t lab-inventory .
```

**Run container:**
```bash
docker run -d \
  --name lab-inventory-app \
  -p 8080:80 \
  -e APP_URL="http://localhost:8080" \
  -e DB_PASSWORD="your_password" \
  -e SUPABASE_URL="https://icdscorcuiucwipusohb.supabase.co" \
  -e SUPABASE_KEY="your_supabase_key" \
  --restart unless-stopped \
  lab-inventory
```

### Option 4: Development with Hot Reload

For development with file watching:
```bash
# Development override
docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d

# Mount source code for live editing
docker run -d \
  --name lab-inventory-dev \
  -p 8080:80 \
  -v $(pwd):/var/www/html \
  -e APP_ENV=local \
  -e APP_DEBUG=true \
  lab-inventory
```

## 🛠️ Docker Configuration Details

### Container Specifications

- **Base Image**: `php:8.2-apache`
- **Web Server**: Apache 2.4 with mod_rewrite and mod_headers
- **PHP Extensions**: pdo_pgsql, pgsql, mbstring, gd, zip, xml, bcmath
- **Build Tools**: Composer 2.x, Node.js 18+, npm
- **Health Check**: Custom script monitoring Apache and Laravel
- **Restart Policy**: `unless-stopped`

### Volume Mounts

```yaml
volumes:
  - ./storage:/var/www/html/storage           # Laravel storage
  - ./bootstrap/cache:/var/www/html/bootstrap/cache  # Laravel cache
```

### Port Mapping

| Container Port | Host Port | Service |
|---------------|-----------|---------|
| 80 | 8080 | Apache Web Server |

### Health Check

Custom health check script monitors:
- ✅ Apache process status
- ✅ HTTP response (200 OK)
- ✅ Laravel application status
- ✅ Database connectivity

```yaml
healthcheck:
  test: ["/usr/local/bin/healthcheck.sh"]
  interval: 30s
  timeout: 10s
  retries: 3
  start_period: 40s
```

## 🛠️ Management & Operations

### Container Management

**Start/Stop/Restart:**
```bash
# Start container
docker-compose up -d

# Stop container
docker-compose down

# Restart container
docker-compose restart

# View status
docker-compose ps
```

**Logs & Monitoring:**
```bash
# View all logs
docker-compose logs

# Follow logs in real-time
docker-compose logs -f

# View specific service logs
docker logs lab-inventory-app

# View last 100 lines
docker logs --tail 100 lab-inventory-app
```

**Execute Commands in Container:**
```bash
# Access container shell
docker exec -it lab-inventory-app bash

# Run Laravel commands
docker exec lab-inventory-app php artisan about
docker exec lab-inventory-app php artisan cache:clear
docker exec lab-inventory-app php artisan config:cache

# Check application status
docker exec lab-inventory-app /usr/local/bin/healthcheck.sh
```

### Scaling & Load Balancing

**Horizontal Scaling:**
```bash
# Scale to multiple instances
docker-compose up -d --scale lab-inventory=3

# With load balancer (nginx/traefik)
# Configure upstream servers pointing to different ports
```

**Resource Limits:**
```yaml
# Add to docker-compose.yml
deploy:
  resources:
    limits:
      cpus: '2.0'
      memory: 2G
    reservations:
      cpus: '0.5'
      memory: 512M
```

### Database Operations

**Database Migrations:**
```bash
# Enable auto-migration on startup
docker-compose up -d -e AUTO_MIGRATE=true

# Manual migration
docker exec lab-inventory-app php artisan migrate --force
```

**Database Backup:**
```bash
# Backup via Supabase (recommended)
# Use Supabase dashboard or API

# Or manual PostgreSQL backup
docker exec lab-inventory-app pg_dump \
  -h db.icdscorcuiucwipusohb.supabase.co \
  -U postgres \
  -d postgres > backup_$(date +%Y%m%d_%H%M%S).sql
```

## 🔍 Troubleshooting

### Common Issues & Solutions

#### **1. Container Won't Start**

**Check logs:**
```bash
docker logs lab-inventory-app
```

**Common solutions:**
```bash
# Port conflict
sudo lsof -i :8080
# Change port in docker-compose.yml

# Permission issues
docker exec lab-inventory-app chown -R www-data:www-data /var/www/html/storage

# Rebuild from scratch
docker-compose down
docker system prune -f
docker-compose up -d --build --no-cache
```

#### **2. Database Connection Issues**

**Verify environment:**
```bash
docker exec lab-inventory-app env | grep -E "(DB_|SUPABASE_)"
```

**Test connectivity:**
```bash
# Test Supabase connection
docker exec lab-inventory-app php artisan tinker
# In tinker: DB::connection()->getPdo();

# Test from outside container
curl -I https://icdscorcuiucwipusohb.supabase.co
```

#### **3. Email Confirmation Not Working**

**Check redirect URLs:**
```bash
# Verify environment variables
docker exec lab-inventory-app env | grep REDIRECT

# Check Supabase dashboard settings
# Authentication → Settings → URL Configuration
```

**Test email flow:**
```bash
# Check if email routes are accessible
curl http://localhost:8080/auth/confirm
curl -X POST http://localhost:8080/auth/confirm
```

#### **4. Performance Issues**

**Monitor resources:**
```bash
# Container stats
docker stats lab-inventory-app

# System resources
docker system df

# Memory usage
docker exec lab-inventory-app cat /proc/meminfo
```

**Optimize performance:**
```bash
# Clear all caches
docker exec lab-inventory-app php artisan optimize:clear
docker exec lab-inventory-app php artisan optimize

# Rebuild with optimizations
docker-compose up -d --build
```

#### **5. SSL/HTTPS Issues (Production)**

**Configure reverse proxy:**
```nginx
# nginx configuration example
server {
    listen 443 ssl;
    server_name yourdomain.com;
    
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    
    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### Debug Commands

**Application Status:**
```bash
# Laravel application info
docker exec lab-inventory-app php artisan about

# Environment configuration
docker exec lab-inventory-app php artisan env

# Route list
docker exec lab-inventory-app php artisan route:list

# Check Supabase connection
docker exec lab-inventory-app php artisan tinker
```

**System Status:**
```bash
# Apache status
docker exec lab-inventory-app service apache2 status

# PHP configuration
docker exec lab-inventory-app php --ini

# Disk usage
docker exec lab-inventory-app df -h

# Process list
docker exec lab-inventory-app ps aux
```

## 🌐 Production Deployment

### Pre-deployment Checklist

- [ ] **Domain Setup**: Configure DNS pointing to your server
- [ ] **SSL Certificate**: Obtain and configure HTTPS certificates
- [ ] **Firewall**: Configure firewall rules (allow 80, 443, 22)
- [ ] **Reverse Proxy**: Setup nginx/traefik for SSL termination
- [ ] **Monitoring**: Configure log monitoring and alerts
- [ ] **Backup Strategy**: Setup automated database backups
- [ ] **Environment Variables**: Secure sensitive credentials

### Reverse Proxy Configuration

#### **Nginx Example**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    
    ssl_certificate /etc/ssl/certs/yourdomain.com.crt;
    ssl_certificate_key /etc/ssl/private/yourdomain.com.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    
    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;
        
        # WebSocket support (if needed)
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        
        # Security headers
        add_header X-Frame-Options "SAMEORIGIN" always;
        add_header X-XSS-Protection "1; mode=block" always;
        add_header X-Content-Type-Options "nosniff" always;
        add_header Referrer-Policy "no-referrer-when-downgrade" always;
        add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
    }
}
```

#### **Traefik Example (docker-compose.traefik.yml)**
```yaml
version: '3.8'

services:
  traefik:
    image: traefik:v2.10
    command:
      - "--api.dashboard=true"
      - "--providers.docker=true"
      - "--providers.docker.exposedbydefault=false"
      - "--entrypoints.web.address=:80"
      - "--entrypoints.websecure.address=:443"
      - "--certificatesresolvers.myresolver.acme.tlschallenge=true"
      - "--certificatesresolvers.myresolver.acme.email=your-email@example.com"
      - "--certificatesresolvers.myresolver.acme.storage=/letsencrypt/acme.json"
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - "./letsencrypt:/letsencrypt"
      - "/var/run/docker.sock:/var/run/docker.sock:ro"

  lab-inventory:
    build: .
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.lab-inventory.rule=Host(`yourdomain.com`)"
      - "traefik.http.routers.lab-inventory.entrypoints=websecure"
      - "traefik.http.routers.lab-inventory.tls.certresolver=myresolver"
      - "traefik.http.services.lab-inventory.loadbalancer.server.port=80"
    environment:
      - APP_URL=https://yourdomain.com
    # ... rest of configuration
```

### Environment-Specific Configurations

#### **Production docker-compose.prod.yml**
```yaml
version: '3.8'

services:
  lab-inventory:
    image: lab-inventory:latest
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_URL=https://yourdomain.com
      - LOG_LEVEL=warning
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '1.0'
          memory: 1G
        reservations:
          cpus: '0.25'
          memory: 256M
      restart_policy:
        condition: on-failure
        delay: 5s
        max_attempts: 3
        window: 120s
    healthcheck:
      interval: 15s
      timeout: 5s
      retries: 5
```

#### **Staging docker-compose.staging.yml**
```yaml
version: '3.8'

services:
  lab-inventory:
    environment:
      - APP_ENV=staging
      - APP_DEBUG=true
      - APP_URL=https://staging.yourdomain.com
      - LOG_LEVEL=debug
```

## � Monitoring & Maintenance

### Application Monitoring

**Health Check Endpoint:**
```bash
# Application health
curl http://localhost:8080/

# Custom health endpoint (can be added)
curl http://localhost:8080/health
```

**Log Management:**
```bash
# Centralized logging with ELK stack
# Add to docker-compose.yml:
logging:
  driver: "json-file"
  options:
    max-size: "10m"
    max-file: "3"

# Or use external log aggregation
logging:
  driver: syslog
  options:
    syslog-address: "tcp://logserver:514"
```

**Metrics Collection:**
```bash
# Add Prometheus metrics (optional)
# Install Laravel Prometheus package
# Configure /metrics endpoint
```

### Backup Strategy

**Database Backup (Automated):**
```bash
# Create backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="lab_inventory_backup_${DATE}.sql"

# Backup via Supabase API or pg_dump
pg_dump -h db.icdscorcuiucwipusohb.supabase.co \
        -U postgres \
        -d postgres \
        -f "/backups/${BACKUP_FILE}"

# Upload to cloud storage (AWS S3, Google Cloud, etc.)
aws s3 cp "/backups/${BACKUP_FILE}" s3://your-backup-bucket/

# Cleanup old backups (keep last 30 days)
find /backups -name "*.sql" -mtime +30 -delete
```

**Application Backup:**
```bash
# Backup storage directory
tar -czf storage_backup_$(date +%Y%m%d_%H%M%S).tar.gz ./storage

# Backup configuration
cp .env env_backup_$(date +%Y%m%d_%H%M%S)
```

### Security Considerations

**Container Security:**
```dockerfile
# Add security configurations to Dockerfile
USER www-data
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

# Use non-root user
RUN groupadd -r labuser && useradd -r -g labuser labuser
USER labuser
```

**Environment Security:**
```bash
# Use Docker secrets for sensitive data
echo "your_db_password" | docker secret create db_password -

# Reference secrets in docker-compose.yml
secrets:
  - db_password
environment:
  - DB_PASSWORD_FILE=/run/secrets/db_password
```

**Network Security:**
```yaml
# Create isolated network
networks:
  lab-network:
    driver: bridge
    internal: true

services:
  lab-inventory:
    networks:
      - lab-network
```

## 📚 Additional Resources

### Documentation Links
- **Laravel Docker**: https://laravel.com/docs/10.x/deployment
- **Supabase Docker**: https://supabase.com/docs/guides/self-hosting/docker
- **Docker Best Practices**: https://docs.docker.com/develop/dev-best-practices/
- **Docker Compose**: https://docs.docker.com/compose/

### Performance Optimization
- **OPcache**: PHP bytecode caching (already configured)
- **Redis**: Session and cache storage (can be added)
- **CDN**: Static asset delivery (Cloudflare, AWS CloudFront)
- **Image Optimization**: Multi-stage builds, .dockerignore

### CI/CD Integration
```yaml
# GitHub Actions example (.github/workflows/deploy.yml)
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Deploy to server
        run: |
          ssh user@server 'cd /path/to/app && git pull && docker-compose up -d --build'
```

---

## 📞 Support

### Getting Help
- **Documentation**: Check README.md and this file
- **Logs**: `docker logs lab-inventory-app`
- **Health Check**: `docker exec lab-inventory-app /usr/local/bin/healthcheck.sh`
- **Laravel Status**: `docker exec lab-inventory-app php artisan about`

### Common Production Issues
| Issue | Solution |
|-------|----------|
| High memory usage | Add memory limits, optimize Laravel caches |
| Slow response time | Enable OPcache, add Redis, optimize database |
| SSL certificate issues | Check certificate expiry, verify nginx config |
| Database connection timeouts | Check Supabase connection limits, implement connection pooling |
| Email delivery issues | Verify Supabase email settings, check DNS records |

---

**🎉 Ready for Production!**

Your Lab Inventory application is now ready for production deployment with Docker. Follow the security and monitoring best practices above for optimal performance.

*Last updated: June 2025*
