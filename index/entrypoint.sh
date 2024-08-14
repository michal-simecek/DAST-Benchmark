#!/bin/sh
rm /var/log/nginx/access.log
touch /var/log/nginx/access.log
nginx -g 'daemon off;'