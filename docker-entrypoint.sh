#!/bin/sh
set -e

# Generate a bcrypt hash of FLIPCLOCK_PASSWORD and write to /var/www/.env
# PHP reads $_SERVER['HOME']/.env, and www-data's HOME is /var/www
PASSWORD="${FLIPCLOCK_PASSWORD:-test123}"
HASH=$(php -r "echo password_hash('${PASSWORD}', PASSWORD_BCRYPT);")

mkdir -p /var/www
echo "FLIPCLOCKPASSWORD=${HASH}" > /var/www/.env
chown www-data:www-data /var/www/.env
chmod 600 /var/www/.env

echo "FlipClock ready — password is '${PASSWORD}' (set FLIPCLOCK_PASSWORD to change)"

exec "$@"
