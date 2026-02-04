#!/usr/bin/env bash
set -euo pipefail

PROJECT_PATH="${1:-/var/www/vhosts/example.com/httpdocs}"

echo "== Gaming CMS Deploy (Plesk/Linux) =="
echo "Path: ${PROJECT_PATH}"

if [ ! -d "${PROJECT_PATH}" ]; then
  echo "Projektpfad nicht gefunden: ${PROJECT_PATH}"
  exit 1
fi

cd "${PROJECT_PATH}"

# Composer Platform fuer Server-PHP
export COMPOSER_PLATFORM_PHP=8.4.17

echo "-> Composer install (no-dev)"
composer install --no-dev --optimize-autoloader

echo "-> Laravel cache clear"
php artisan optimize:clear

echo "-> Migrations"
php artisan migrate --force

echo "-> Storage link"
php artisan storage:link

echo "-> Cache build"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "-> Assets build"
npm install
npm run build

echo "== Deploy fertig =="
