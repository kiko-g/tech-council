#!/bin/bash

# Stop execution if a step fails
set -e

DOCKER_USERNAME=YOUR_DOCKER_ACCOUNT # Replace by your docker hub username
IMAGE_NAME=lbaw21GG                 # Replace with your group's image name

# Ensure that dependencies are available
composer install
php artisan clear-compiled
php artisan optimize

docker build -t $DOCKER_USERNAME/$IMAGE_NAME .
docker push $DOCKER_USERNAME/$IMAGE_NAME
