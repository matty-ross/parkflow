FROM dunglas/frankenphp:1-php8.5

WORKDIR /app

RUN <<-EOF
	install-php-extensions \
		@composer \
		pdo_pgsql \
		opcache \
		xdebug
EOF

COPY . .

RUN composer install --no-dev
