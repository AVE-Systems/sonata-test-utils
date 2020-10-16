FROM php:7.2-cli

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
	&& php composer-setup.php --install-dir=/usr/bin --filename=composer \
	&& php -r "unlink('composer-setup.php');"

RUN pecl install xdebug-2.6.1 \
    && docker-php-ext-enable xdebug \
    && cd /usr/local/etc/php/conf.d \
    && echo xdebug.remote_enable=1 >> docker-php-ext-xdebug.ini \
    && echo xdebug.remote_host=host.docker.internal >> docker-php-ext-xdebug.ini \
    && echo xdebug.idekey=${XDEBUG_IDEKEY:-PHPSTORM} >> docker-php-ext-xdebug.ini

#RUN useradd -m docker-php --uid=1000

#USER 1000:1000

