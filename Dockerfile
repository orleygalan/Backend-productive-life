FROM php:8.2-cli

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio
WORKDIR /app

# Copiar proyecto
COPY . .

# Instalar Laravel
RUN composer install --no-dev --optimize-autoloader

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 10000

# Comando para correr
CMD php artisan serve --host=0.0.0.0 --port=10000
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
