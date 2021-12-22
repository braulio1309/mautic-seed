FROM php:7.4-apache

RUN apt-get update && apt-get install --no-install-recommends -y \
    cron \
    git \
    wget \
    sudo \
    libc-client-dev \
    libicu-dev \
    libkrb5-dev \
    libmcrypt-dev \
    libssl-dev \
    libz-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    unzip \
    zip \
    nano \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
    && rm -rf /var/lib/apt/lists/* \
    && rm /etc/cron.daily/*

# mcrypt not working
RUN docker-php-ext-configure imap --with-imap --with-imap-ssl --with-kerberos \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install imap intl mbstring mysqli pdo_mysql zip opcache bcmath gd \
    && docker-php-ext-enable imap intl mbstring mysqli pdo_mysql zip opcache bcmath gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Setting PHP properties
ENV PHP_INI_DATE_TIMEZONE='UTC' \
    PHP_MEMORY_LIMIT=1.8G \
    PHP_MAX_UPLOAD=128M \
    PHP_MAX_EXECUTION_TIME=300 \
    AWS_SENDER_NAME='Info' \
    AWS_SENDER_EMAIL='info@destiny.systems'

COPY . /var/www/html
RUN cd /var/www/html && COMPOSER_MEMORY_LIMIT=-1 composer install
RUN chown -R www-data:www-data /var/www/html

# Copy init scripts and custom .htaccess
COPY docker-files/docker-entrypoint.sh /entrypoint.sh
COPY docker-files/makeconfig.php /makeconfig.php
COPY docker-files/makedb.php /makedb.php
COPY docker-files/mautic.crontab /etc/cron.d/mautic
RUN chmod 644 /etc/cron.d/mautic

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Apply necessary permissions
RUN ["chmod", "+x", "/entrypoint.sh"]
ENTRYPOINT ["/entrypoint.sh"]

CMD ["apache2-foreground"]
