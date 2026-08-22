FROM dunglas/frankenphp:1-php8.5

WORKDIR /app

RUN <<-EOF
	install-php-extensions \
		@composer \
		pdo_pgsql \
		opcache \
		xdebug
EOF

COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/docker-entrypoint

COPY . .

ENTRYPOINT [ "docker-entrypoint" ]

CMD [ "frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile" ]
