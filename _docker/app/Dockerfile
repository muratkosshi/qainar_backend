FROM php:8.1-fpm
ENV USER_NAME=murat_developer
ENV USER_HOME=/home/murat_developer
RUN useradd -m murat_developer

RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip unzip \
      git \
      supervisor && \
      docker-php-ext-install pdo pdo_pgsql pgsql && \
      docker-php-ext-install bcmath && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./_docker/app/supervisor/ /etc/supervisor/conf.d
# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug


RUN service supervisor start

RUN chmod -R 777 /var/www/*
WORKDIR /var/www
