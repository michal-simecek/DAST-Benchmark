#!/bin/bash
docker-php-ext-install mysqli
exec docker-php-entrypoint php-fpm