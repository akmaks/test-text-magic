FROM dunglas/frankenphp:1-php8.3  AS app

RUN apt-get update && apt-get install --no-install-recommends -y \
	acl \
	file \
	gettext \
	git \
	&& rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
	;

RUN set -eux; \
	install-php-extensions pdo_pgsql

COPY composer.json composer.lock symfony.lock ./

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer install --no-cache --prefer-dist --no-scripts --no-progress; \
	composer dump-autoload --classmap-authoritative;

COPY docker/php-cli/conf.d/app.ini $PHP_INI_DIR/conf.d/app.ini

COPY docker/php-cli/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

VOLUME var/log

ENTRYPOINT ["docker-entrypoint"]
CMD ["/bin/sh"]