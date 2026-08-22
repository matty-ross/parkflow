#!/bin/sh
set -e

if [ "$APP_ENV" = "dev" ]; then
    composer install --no-interaction
else
    composer install --no-dev --no-interaction
    php bin/console asset-map:compile --no-interaction
fi

php bin/console doctrine:migrations:migrate --no-interaction

exec docker-php-entrypoint "$@"
