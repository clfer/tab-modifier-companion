FROM php:7.2-apache

COPY php.ini /usr/local/etc/php/
RUN apt-get update && apt-get install -y \
                   		libfreetype6-dev \
                   		libpng-dev \
                   	&& docker-php-ext-install iconv \
                   	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ \
                   	&& docker-php-ext-install gd

RUN pecl install xdebug \
  && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY tab-modifier-companion.local.conf  /etc/apache2/sites-available/
COPY hosts  /etc/hosts

RUN service apache2 restart
WORKDIR /etc/apache2/sites-available/
RUN a2ensite tab-modifier-companion.local.conf


