FROM php:7.2-cli

RUN apt-get update -y

RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends \
  apt-utils \
  git \
  libzip-dev \
  unzip

RUN apt-get autoremove -y && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions
RUN docker-php-ext-configure zip --with-libzip && \
  docker-php-ext-install zip

COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer
RUN composer --version

RUN pecl install xdebug-2.6.1 && docker-php-ext-enable xdebug

RUN useradd -m docker-php --uid=1000

USER 1000:1000
