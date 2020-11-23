FROM php:7.4-alpine

ARG TZ

WORKDIR /code

ADD . .

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

COPY ./docker/php/startup.sh /root


RUN set -eux && \
  apk add --update-cache --no-cache --virtual=.build-dependencies tzdata && \
  apk --no-cache add zip unzip composer npm && \
  cp /usr/share/zoneinfo/${TZ} /etc/localtime && \
  docker-php-ext-configure opcache --enable-opcache && \
  docker-php-ext-install opcache bcmath pdo_mysql && \
  chmod +x /root/startup.sh

CMD ["/root/startup.sh"]

