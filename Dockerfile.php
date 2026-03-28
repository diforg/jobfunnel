ARG APP_ENV=development

FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY postcss.config.cjs ./
COPY tailwind.config.cjs ./
RUN npm run build

FROM php:8.3-fpm-alpine

ARG APP_ENV=development
ENV APP_ENV=${APP_ENV}

# Install system dependencies
RUN apk add --no-cache \
    autoconf \
    automake \
    bash \
    build-base \
    curl \
    git \
    libpq \
    libpq-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-client \
    supervisor \
    unzip

# Install PHP extensions one by one to debug issues
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install xml
RUN docker-php-ext-install zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure git to allow this directory (fix for composer)
RUN git config --global --add safe.directory /var/www/html

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built frontend assets (used in production mode)
COPY --from=assets /app/public/build /var/www/html/public/build

# Create storage and bootstrap/cache directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install PHP dependencies according to target environment
RUN if [ "$APP_ENV" = "production" ]; then \
            composer install --no-dev --no-interaction --optimize-autoloader --classmap-authoritative; \
        else \
            composer install --no-interaction --optimize-autoloader; \
        fi

# Create non-root user
RUN addgroup -g 1000 laravel && adduser -D -u 1000 -G laravel laravel

# Copy entrypoint script as root first
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint && \
    mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache public && \
    chown -R laravel:laravel /var/www/html

USER laravel

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint"]
CMD ["php-fpm"]
