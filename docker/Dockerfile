FROM webdevops/php-nginx:8.2

USER application
WORKDIR /app

RUN composer dump-env prod && \
    composer install