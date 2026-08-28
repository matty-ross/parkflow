#!/bin/sh
set -e

IS_DEV=$([ "$APP_ENV" = "dev" ] && echo true || echo false)

if [ "$IS_DEV" = true ]; then
    composer install --no-interaction
else
    composer install --no-dev --no-interaction
fi

php bin/console importmap:install --no-interaction

if [ "$IS_DEV" = false ]; then
    php bin/console asset-map:compile --no-interaction
fi

php bin/console doctrine:migrations:migrate --no-interaction

exec docker-php-entrypoint "$@"
