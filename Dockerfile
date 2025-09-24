# Laravel PHP-FPM Container
FROM php:8.4-fpm-alpine

# 作業ディレクトリ設定
WORKDIR /var/www/html

# システムパッケージインストール
RUN apk add --no-cache \
    build-base \
    freetype-dev \
    jpeg-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    nodejs \
    npm \
    git \
    mysql-client \
    supervisor

# PHP拡張機能インストール
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache

# PHP-FPM設定
RUN echo "pm.max_children = 20" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 3" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 2" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 4" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_requests = 200" >> /usr/local/etc/php-fpm.d/www.conf

# PHP設定
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/upload.ini \
    && echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/upload.ini \
    && echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=192" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_wasted_percentage=10" >> /usr/local/etc/php/conf.d/opcache.ini

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ユーザー作成（セキュリティ向上）
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -D -S -G www www

# アプリケーションファイルコピー
COPY --chown=www:www composer.json composer.lock ./
RUN composer install --optimize-autoloader

COPY --chown=www:www package.json package-lock.json ./
RUN npm ci

COPY --chown=www:www . .

# アプリケーション最適化
RUN composer dump-autoload --optimize \
    && npm run build

# 権限設定
RUN chown -R www:www /var/www/html \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# ヘルスチェックスクリプト
COPY docker/php/healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/healthcheck.sh

# Supervisord設定（PHP-FPM + Queue Worker管理）
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

USER www

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD /usr/local/bin/healthcheck.sh

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]