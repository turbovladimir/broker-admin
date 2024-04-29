FROM webdevops/php-nginx:8.2 as php_base

FROM php_base as app_base
COPY --chown=application:application . /app
USER application
WORKDIR /app

FROM app_base as app_beta
COPY --from=app_base /app /app
COPY .env.beta .env
RUN ls -la \
    && composer install \
    && php bin/console doctrine:migrations:migrate --allow-no-migration --all-or-nothing

FROM app_base as app_dev
COPY --from=app_base /app /app
COPY .env.dev .env
RUN ls -la \
    && composer install \
    && php bin/console doctrine:migrations:migrate --allow-no-migration --all-or-nothing

FROM app_base as app_prod
COPY --from=app_base /app /app
COPY .env.prod .env
RUN ls -la \
    && composer install \
    && php bin/console doctrine:migrations:migrate --allow-no-migration --all-or-nothing