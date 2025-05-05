FROM php:8.4-fpm AS base

RUN apt-get update \
    && apt-get install -y --fix-missing curl gnupg git zip unzip \
    libzip-dev libxml2-dev libfreetype6-dev libonig-dev libcap2-bin libpng-dev libjpeg62-turbo-dev \
    librsvg2-bin libpq-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install zip mbstring exif pcntl bcmath -j$(nproc) gd intl pdo_pgsql

RUN pecl install redis && docker-php-ext-enable redis

RUN apt-get -y autoremove && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./.docker/app/php.ini /usr/local/etc/php/conf.d/docker-php.ini

COPY ./.docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]


FROM base AS development

ENV BUILD_ENV=development

ARG WWWGROUP
ARG WWWUSER
ARG APP_USER

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN git config --global --add safe.directory /var/www/html

RUN groupadd --force -g $WWWGROUP $APP_USER
RUN useradd -ms /bin/bash -g $WWWGROUP -u $WWWUSER $APP_USER

RUN sed -i '/^user = /d' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i '/^group = /d' /usr/local/etc/php-fpm.d/www.conf

USER $APP_USER


FROM composer:2 AS builder

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --no-progress --no-autoloader --no-scripts

COPY . /app

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative


FROM base AS production

ENV BUILD_ENV=production

COPY --from=builder --chown=www-data:www-data /app /var/www/html
