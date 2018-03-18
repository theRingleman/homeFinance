#!/bin/bash
echo "export VERSION=$version" >> /etc/apache2/envvars
echo "export ENV=$env" >> /etc/apache2/envvars

echo "Linking in libraries from composer...";
ln -s /var/www/vendor /var/www/html/vendor

echo "Launching Apache2 webserver...";
exec apache2-foreground