FROM php:8.1-fpm-alpine

ENV ALPINE_MIRROR "http://dl-cdn.alpinelinux.org/alpine"
COPY ./docker-php-memlimit.ini /usr/local/etc/php/conf.d/

RUN apk update && apk add --no-cache \
    curl \
    openssl \
    bash \
    caddy \
    npm \
    mariadb-client \
    ffmpeg \
    git \
    freetype-dev \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    wget \
    zlib-dev \
    libmcrypt-dev \
    && echo "* * * * * sh /srv/crontab" > /etc/crontabs/root

RUN docker-php-ext-configure gd \
        --with-jpeg=/usr/include/  \
        --with-freetype=/usr/include/

RUN docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) gd

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN adduser \
    --disabled-password \
    --gecos "" \
    --home "/srv" \
    --ingroup www-data \
    fairy

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /srv
COPY --chown=fairy:www-admin . /srv

EXPOSE 8080

CMD php-fpm -D && caddy run --config ./Caddyfile --adapter caddyfile
