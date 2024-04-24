#!/bin/bash
docker-php-ext-install mysqli
# echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini
# pecl install mongodb
# docker-php-ext-enable mongodb
exec docker-php-entrypoint php-fpm
