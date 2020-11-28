FROM php:8.0-rc-alpine

RUN apk --no-cache add tzdata zip unzip composer autoconf build-base
RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-install opcache bcmath pdo_mysql
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

ARG TZ
RUN cp /usr/share/zoneinfo/${TZ} /etc/localtime

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

COPY ./docker/php/startup.sh /root
RUN chmod +x /root/startup.sh

WORKDIR /code

CMD ["/root/startup.sh"]
