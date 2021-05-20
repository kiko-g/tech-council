#!/bin/bash

# Stop execution if a step fails
set -e

DOCKER_USERNAME=lbaw2132
IMAGE_NAME=lbaw2132

# Ensure that dependencies are available
composer install
php artisan clear-compiled
php artisan optimize

docker build -t $DOCKER_USERNAME/$IMAGE_NAME .
docker push $DOCKER_USERNAME/$IMAGE_NAME
# docker build -t lbaw2132/lbaw2132 .
# docker push lbaw2132/lbaw2132
