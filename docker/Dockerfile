
FROM php:7.0.8-apache

# add Composer
ADD composer.json /var/www/composer.json

# install custom php.ini
ADD php.ini /usr/local/etc/php/

# Install custom apache config
ADD apache2.conf /etc/apache2/apache2.conf
RUN a2enmod rewrite headers

RUN apt-get update \
&& apt-get install -y \
    mysql-client \
    git \
    unzip \
    libcurl4-openssl-dev \
    libxml2-dev \
    wget \
    zlib1g-dev \
&& docker-php-ext-install mysqli pdo curl pdo_mysql soap zip \
&& cd /var/www;curl -sS https://getcomposer.org/installer | php \
&& cd /var/www;php composer.phar install \
&& a2enmod rewrite headers

# wire in a startup script
ADD bootstrap.sh /var/www/bootstrap.sh
CMD ["/var/www/bootstrap.sh"]nf