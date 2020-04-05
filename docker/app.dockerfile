FROM php:7.4-fpm

# Lots of irrelevant stuff in here but it's the default php image I use and didn't want to
# waste time using another image

# ghostscript is needed for PDF conversion
# libpq-dev is needed for missing includes for pdo_pgsql
RUN apt-get update && apt-get install -y --no-install-recommends \
    libmagickwand-dev \
    ghostscript \
    libpq-dev \
    libzip-dev \
    redis-tools \
    supervisor

RUN pecl install zip \
    && pecl install imagick \
    && pecl install redis

RUN docker-php-ext-enable zip \
    && docker-php-ext-enable imagick \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql \
    && docker-php-ext-install gd \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets

RUN  pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN ln -s /var/www/.docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN /usr/bin/supervisord &
