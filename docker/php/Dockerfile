FROM php:8.0.0

# install php libs
RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-install pdo pdo_mysql opcache bcmath sockets
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# copy composer
COPY --from=composer:2.0.6 /usr/bin/composer /usr/bin/composer

ARG TZ
RUN ln -sf /usr/share/zoneinfo/${TZ} /etc/localtime

COPY ./docker/php/php.ini ${PHP_INI_DIR}/php.ini

COPY ./docker/php/startup.sh /root
RUN chmod +x /root/startup.sh

WORKDIR /code

CMD ["/root/startup.sh"]
