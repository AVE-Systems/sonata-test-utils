FROM php:7.2-cli

RUN apt-get update

RUN DEBIAN_FRONTEND=noninteractive apt-get install -y git

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN pecl install xdebug-2.6.1 && docker-php-ext-enable xdebug

RUN useradd -m docker-php --uid=1000

USER 1000:1000
