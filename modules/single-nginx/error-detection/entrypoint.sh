#!/bin/bash

# Copy error pages
cp -r /path/to/errors /var/www/html/pages

# Ensure permissions are correct
chown -R nginx:nginx /var/www/html/pages

# Start Nginx in the foreground
nginx -g 'daemon off;'
