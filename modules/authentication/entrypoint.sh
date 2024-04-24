#!/bin/bash

# Install Apache2 utils if not already installed
if ! command -v htpasswd &> /dev/null
then
    echo "test1"
    apt-get update && apt-get install -y apache2-utils
fi

# Ensure the Nginx config directory is writable or create the file as root
if [ ! -f /etc/nginx/.htpasswd ]; then
    touch /etc/nginx/.htpasswd
fi

# Create or update .htpasswd file
htpasswd -cb /etc/nginx/.htpasswd username password

# Change permissions to ensure Nginx can read the file
chown www-data:www-data /etc/nginx/.htpasswd
chmod 644 /etc/nginx/.htpasswd

# Execute the default Nginx entrypoint and command (assumes Nginx is to be run in this container)
exec nginx -g 'daemon off;'
