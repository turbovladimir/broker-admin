FROM webdevops/php-nginx:8.2 as php_base
RUN apt-get -y update
RUN apt-get -y install vim nano iputils-ping

FROM php_base as app_base
COPY --chown=application:application . /app
USER application
WORKDIR /app
ARG APP_ENV_FILE

RUN cat $APP_ENV_FILE > .env \
    && rm -rf .env.* \
    && ls -la \
    && cat .env
