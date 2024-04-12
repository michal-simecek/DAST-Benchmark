#!/bin/bash

# Create uploads directory and set correct permissions
mkdir -p /var/www/html/uploads
chown www-data:www-data /var/www/html/uploads  #run this in the php file inclusion container in case of permission denied when uploading file
chmod 755 /var/www/html/uploads                #run this in the php file inclusion container in case of permission denied when uploading file

# Continue with container's main command
exec docker-php-entrypoint php-fpm
