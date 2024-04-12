#!/bin/bash

# Copy error pages
cp -r /path/to/errors /var/www/html/errors

# Ensure permissions are correct
chown -R nginx:nginx /var/www/html/errors

# Start Nginx in the foreground
nginx -g 'daemon off;'
