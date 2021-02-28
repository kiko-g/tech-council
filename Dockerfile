FROM php:8-apache

# Copy static HTML pages (when building a new image)
COPY html /var/www/html

