FROM php:8.2-fpm

# Зависимости системы
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    zip unzip vim && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP расширения
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Redis расширение
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Пользователь
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

USER www
EXPOSE 9000
CMD ["php-fpm"]
