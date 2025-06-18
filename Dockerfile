# Railway Dockerfile for GitHub deployment
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install additional tools for debugging
RUN apt-get update && apt-get install -y curl

# Enable Apache modules
RUN a2enmod rewrite headers

# Set global ServerName to suppress Apache warning
RUN echo "ServerName lab-inventory.railway.app" >> /etc/apache2/apache2.conf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure Apache DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy application files
COPY . /var/www/html

# Create a minimal .env file for build process
RUN echo 'APP_NAME="Lab Inventory"' > .env \
    && echo 'APP_ENV=production' >> .env \
    && echo 'APP_KEY=base64:FLkfOrzIgcbQV3/EtQ3mcjMRnHsFP1kxf0Cngirnw6w=' >> .env \
    && echo 'APP_DEBUG=false' >> .env \
    && echo 'APP_URL=http://localhost' >> .env \
    && echo 'DB_CONNECTION=pgsql' >> .env \
    && echo 'DB_HOST=localhost' >> .env \
    && echo 'DB_PORT=5432' >> .env \
    && echo 'DB_DATABASE=postgres' >> .env \
    && echo 'DB_USERNAME=postgres' >> .env \
    && echo 'DB_PASSWORD=dummy' >> .env \
    && echo 'CACHE_DRIVER=file' >> .env \
    && echo 'SESSION_DRIVER=file' >> .env \
    && echo 'LOG_CHANNEL=stack' >> .env

# Validate .env file format
RUN echo "Checking .env file:" && head -5 .env

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --verbose

# Install Node.js dependencies and build assets
RUN npm ci --verbose && npm run build

# Copy custom Apache configuration if it exists, otherwise use default
RUN if [ -f docker/apache.conf ]; then \
        cp docker/apache.conf /etc/apache2/sites-available/000-default.conf; \
    else \
        echo "Warning: docker/apache.conf not found, using default Apache configuration"; \
    fi

# Create storage and cache directories if they don't exist
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Generate application key and optimize Laravel
RUN php artisan key:generate --force || echo "Key generation failed, using existing key" \
    && php artisan config:cache || echo "Config cache failed" \
    && php artisan route:cache || echo "Route cache failed" \
    && php artisan view:cache || echo "View cache failed"

# Ensure Laravel can start by checking basic functionality
RUN php artisan --version

# Copy and set up health check script if it exists
RUN if [ -f docker/healthcheck.sh ]; then \
        cp docker/healthcheck.sh /usr/local/bin/healthcheck.sh && \
        chmod +x /usr/local/bin/healthcheck.sh; \
    else \
        echo '#!/bin/bash\ncurl -f http://localhost/health.php || exit 1' > /usr/local/bin/healthcheck.sh && \
        chmod +x /usr/local/bin/healthcheck.sh; \
    fi

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
