FROM php:7.0-apache
#RUN docker-php-ext-install mysqli
RUN apt-get update \
    && apt-get install -y \
    sendmail \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpq-dev \
    libpng-dev \
    libicu-dev \
    zlib1g-dev

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-configure intl

RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install mysqli pdo_mysql zip intl opcache

COPY . /var/www/html/
COPY .docker/vhost/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/html/ \
    && a2enmod rewrite \
    && service apache2 restart

EXPOSE 80

WORKDIR /var/www/html
# Optionally install application dependencies here. For example using composer.
