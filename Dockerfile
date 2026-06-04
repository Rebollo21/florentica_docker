# 1. Usar imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    curl libpng-dev libonig-dev libxml2-dev zip unzip git \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Instalar Composer y Node.js 22
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# 5. Configurar Apache para apuntar a la carpeta 'public' de Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# 6. Copiar el código fuente
COPY . /var/www/html

# 7. Instalar dependencias de Laravel y Frontend
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 8. Permisos necesarios
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Configurar el puerto dinámico de Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 10. Script de arranque: Ejecutar migraciones y luego iniciar Apache
CMD php artisan migrate --force && apache2-foreground
