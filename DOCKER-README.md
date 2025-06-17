# Docker Deployment Guide for Lab Inventory

This guide shows how to deploy the Lab Inventory application using Docker with Supabase as the database.

## 🐳 Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Supabase account and project set up
- Your Supabase database password

### 1. Environment Setup

Copy the environment template:
```bash
cp .env.production .env
```

Update the `.env` file with your Supabase database password:
```env
DB_PASSWORD=your_actual_supabase_password
```

### 2. Build and Run

Using Docker Compose (Recommended):
```bash
docker-compose up -d
```

Or using Docker directly:
```bash
# Build the image
docker build -t lab-inventory .

# Run the container
docker run -d \
  --name lab-inventory \
  -p 8080:80 \
  --env-file .env \
  lab-inventory
```

### 3. Access the Application

Open your browser and go to:
- **Main App**: http://localhost:8080
- **Dashboard**: http://localhost:8080/dashboard
- **Lab FKI**: http://localhost:8080/labs/lab-fki

## 🔧 Configuration

### Supabase Setup

1. **Get your database password**:
   - Go to your Supabase project dashboard
   - Navigate to Settings → Database
   - Use your database password

2. **Update database configuration**:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=db.icdscorcuiucwipusohb.supabase.co
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=your_supabase_password
   ```

### Environment Variables

Key environment variables for Docker deployment:

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_URL` | Application URL | `http://localhost:8080` |
| `APP_DEBUG` | Debug mode | `false` |
| `DB_PASSWORD` | Supabase database password | **Required** |
| `SUPABASE_URL` | Supabase project URL | Pre-configured |
| `SUPABASE_KEY` | Supabase anon key | Pre-configured |

## 🚀 Deployment Options

### Option 1: Docker Compose (Recommended)

```yaml
# docker-compose.yml
version: '3.8'
services:
  lab-inventory:
    build: .
    ports:
      - "8080:80"
    environment:
      - DB_PASSWORD=${SUPABASE_DB_PASSWORD}
    volumes:
      - ./storage:/var/www/html/storage
    restart: unless-stopped
```

Run with:
```bash
SUPABASE_DB_PASSWORD=your_password docker-compose up -d
```

### Option 2: Production Deployment

For production deployment, create a `.env.docker` file:
```bash
cp .env.docker.example .env.docker
# Edit .env.docker with your production values
```

Then run:
```bash
docker-compose -f docker-compose.yml --env-file .env.docker up -d
```

## 🛠️ Docker Commands

### Building
```bash
# Build the image
docker build -t lab-inventory .

# Build with no cache
docker build --no-cache -t lab-inventory .
```

### Running
```bash
# Run in background
docker run -d --name lab-inventory -p 8080:80 lab-inventory

# Run with environment file
docker run -d --name lab-inventory -p 8080:80 --env-file .env lab-inventory

# Run with interactive terminal (for debugging)
docker run -it --name lab-inventory -p 8080:80 lab-inventory bash
```

### Management
```bash
# View logs
docker logs lab-inventory

# Follow logs
docker logs -f lab-inventory

# Stop container
docker stop lab-inventory

# Remove container
docker rm lab-inventory

# Restart container
docker restart lab-inventory
```

### Docker Compose Commands
```bash
# Start services
docker-compose up -d

# View logs
docker-compose logs

# Stop services
docker-compose down

# Rebuild and restart
docker-compose up -d --build

# Scale services
docker-compose up -d --scale lab-inventory=2
```

## 🔍 Troubleshooting

### Common Issues

1. **Database Connection Error**
   ```bash
   # Check if Supabase credentials are correct
   docker logs lab-inventory
   ```

2. **Permission Issues**
   ```bash
   # Fix storage permissions
   docker exec lab-inventory chown -R www-data:www-data /var/www/html/storage
   ```

3. **Asset Issues**
   ```bash
   # Rebuild assets
   docker exec lab-inventory npm run build
   ```

### Health Check
```bash
# Check if container is healthy
docker ps
curl http://localhost:8080

# Check application status
docker exec lab-inventory php artisan about
```

### Database Migration
```bash
# Run migrations (if needed)
docker exec lab-inventory php artisan migrate --force

# Seed database
docker exec lab-inventory php artisan db:seed --force
```

## 📦 Image Information

- **Base Image**: PHP 8.2 with Apache
- **Database**: PostgreSQL (via Supabase)
- **Web Server**: Apache with mod_rewrite
- **Frontend**: Built assets included (Vite + Tailwind)
- **Size**: ~500MB (optimized)

## 🔒 Security Considerations

- Application runs in production mode (`APP_DEBUG=false`)
- Sensitive environment variables should be stored securely
- Use HTTPS in production
- Regularly update the base image
- Monitor application logs

## 📋 Maintenance

### Updates
```bash
# Update and rebuild
git pull
docker-compose down
docker-compose up -d --build
```

### Backups
```bash
# Backup storage
docker cp lab-inventory:/var/www/html/storage ./backup-storage

# Database backup (via Supabase dashboard recommended)
```

## 🌐 Production Deployment

For production deployment:

1. Use a reverse proxy (nginx/traefik)
2. Set up SSL certificates
3. Configure proper domain names
4. Use Docker secrets for sensitive data
5. Set up monitoring and logging
6. Configure proper backup strategies

Example nginx configuration:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    
    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```
