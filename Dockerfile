# =====================================================================
# SIMAKSI.COM — image untuk Vercel (runtime container + FrankenPHP)
# Berdasarkan panduan resmi: https://vercel.com/kb/guide/laravel-php-with-docker
# Database: Neon (PostgreSQL). Rahasia (APP_KEY, DB_*) diisi di dashboard
# Vercel → Settings → Environment Variables, BUKAN di file ini.
# =====================================================================
FROM dunglas/frankenphp:1-php8.3-bookworm AS base

# pdo_pgsql/pgsql → Neon, gd → gambar di PDF (dompdf), opcache → lebih cepat
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && install-php-extensions pdo_pgsql pgsql intl zip gd bcmath opcache

WORKDIR /app

# ---------------------------------------------------------------------
FROM base AS dependencies

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader \
    --prefer-dist

COPY . .
# Folder tulis Laravel + symlink public/storage (untuk file upload)
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
    && rm -rf public/storage \
    && ln -s /app/storage/app/public public/storage \
    && composer dump-autoload --no-dev --classmap-authoritative

# ---------------------------------------------------------------------
FROM base AS runtime

COPY --from=dependencies --chown=www-data:www-data /app /app
COPY --chown=www-data:www-data Caddyfile /etc/frankenphp/Caddyfile

RUN setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp \
    && chown -R www-data:www-data /config/caddy /data/caddy

# Nilai non-rahasia. Sesi & cache disimpan di database (Neon) agar login
# tetap tersimpan walau Vercel menjalankan beberapa instance.
ENV PORT=80 \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=pgsql \
    DB_PORT=5432 \
    DB_SSLMODE=require \
    SESSION_DRIVER=database \
    CACHE_STORE=database \
    QUEUE_CONNECTION=sync \
    FILESYSTEM_DISK=local

USER www-data

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
