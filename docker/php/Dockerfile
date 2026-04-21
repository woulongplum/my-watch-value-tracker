FROM php:8.3-fpm-alpine

# Laravelに必要なライブラリとPHP拡張をインストール
RUN apk update && apk add --no-cache \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo_mysql mbstring zip

# Composerをインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
