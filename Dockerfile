# Base oficial de PHP con FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema esenciales
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP para el motor de Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# INSTALAR COMPOSER (Paso faltante crítico)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalación de Node.js 22 (Versión requerida por Vite 7)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs
    
# Establecer el directorio de trabajo
WORKDIR /var/www

# Exponer los puertos (Informativo para Docker)
EXPOSE 8000
EXPOSE 5173