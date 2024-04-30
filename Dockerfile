FROM webdevops/php-nginx:8.2 as php_base

FROM php_base as app_base
COPY --chown=application:application . /app
USER application
WORKDIR /app
COPY .env.prod .env
RUN ls -la \
    && composer install \
    && php bin/console doctrine:migrations:migrate --allow-no-migration --all-or-nothing
