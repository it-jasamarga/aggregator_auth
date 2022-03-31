#!/bin/sh
export COMPOSER_HOME="$HOME/.config/composer";

whoami

# stop application
# php artisan down

# update source code
git pull origin develop

# update PHP dependencies
composer install --no-interaction

# --no-interaction Do not ask any interactive question

# update database
php artisan migrate --force
# --force  Required to run when in production.

php artisan config:clear
php artisan view:clear
php artisan cache:clear

# php artisan up
