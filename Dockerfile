# Build assets using Node
FROM node:20-alpine as assets-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Use PHP with FPM and Nginx
FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    oniguruma-dev \
    icu-dev \
    postgresql-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl bcmath intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built assets from assets-builder
COPY --from=assets-builder /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
