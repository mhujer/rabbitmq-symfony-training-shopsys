FROM php:7.3.5-cli

RUN apt-get update && apt-get install -y \
        supervisor \
        vim \
        htop \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) sockets \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install -j$(nproc) zip \
    && rm -rf /var/lib/apt/lists/*

RUN echo "memory_limit = -1;" > $PHP_INI_DIR/conf.d/memory_limit.ini \
    && echo "date.timezone = UTC;" >> $PHP_INI_DIR/conf.d/timezone.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY supervisor/webui.conf /etc/supervisor/conf.d/webui.conf
EXPOSE 9001

WORKDIR /srv/app/

RUN echo "alias x='composer'" >> ~/.bashrc \
    && echo "alias c='bin/console'" >> ~/.bashrc
