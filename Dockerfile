# Usa la imagen base de PHP con Apache
FROM php:8.2-apache

# Habilitar el módulo 'rewrite' para Laravel
RUN a2enmod rewrite

# Instalar dependencias necesarias para Laravel y manejo de imágenes
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    sendmail \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zlib1g-dev \
    libonig-dev \
    libxml2-dev \
    libmcrypt-dev \
    libicu-dev \
    libmagickwand-dev --no-install-recommends \
    g++ \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP requeridas para Laravel y el manejo de imágenes
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    mbstring \
    bcmath \
    exif \
    intl \
    opcache

# Instalar y habilitar la extensión Imagick para procesamiento avanzado de imágenes
RUN pecl install imagick && docker-php-ext-enable imagick

# Instalar Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar permisos para el usuario especificado en docker-compose (UID 1000, GID 1000)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Cambiar al usuario www-data para la seguridad del contenedor
USER www-data

# Copiar archivo php.ini personalizado
COPY php.ini /usr/local/etc/php/php.ini

# Configurar el directorio de trabajo
WORKDIR /var/www/html/photoeventpro

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto
CMD ["apache2-foreground"]
