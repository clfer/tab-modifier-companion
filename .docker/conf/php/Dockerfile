FROM php:5.6-apache

COPY php.ini /usr/local/etc/php/
RUN apt-get update && apt-get install -y \
                   		libfreetype6-dev \
                   		libpng-dev \
                   	&& docker-php-ext-install iconv \
                   	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ \
                   	&& docker-php-ext-install gd

COPY tab-modifier-companion.local.conf  /etc/apache2/sites-available/
COPY hosts  /etc/hosts

RUN service apache2 restart
WORKDIR /etc/apache2/sites-available/
RUN a2ensite tab-modifier-companion.local.conf


