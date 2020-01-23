FROM romeoz/docker-nginx-php:7.3

WORKDIR /var/www/app/

# install php-mongo extension
RUN apt-get update \
    && echo "Y" | apt-get install -y php7.3-dev \
    && pecl install mongodb \
    && echo "extension=mongodb.so" > ${PHP_CONF_DIR}/fpm/conf.d/30-mongodb.ini \
    && echo "extension=mongodb.so" > ${PHP_CONF_DIR}/cli/conf.d/30-mongodb.ini \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 443

CMD ["/usr/bin/supervisord"]
