#!/bin/bash
set -e

BRANCH=${1:-main}
APP_DIR=/home/admin/portfolio

echo "====================================="
echo " Deploying branch: $BRANCH"
echo "====================================="

cd $APP_DIR

echo "[1/6] Pulling latest code..."
git pull origin $BRANCH

echo "[2/7] Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-scripts

echo "[3/7] Clearing stale caches and rediscovering packages..."
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
php artisan optimize:clear
php artisan package:discover --ansi

echo "[4/7] Installing JS dependencies..."
npm ci

echo "[5/7] Building frontend assets..."
npm run build

echo "[6/7] Running database migrations..."
php artisan migrate --force

echo "[7/7] Caching config, routes and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link 2>/dev/null || true

echo "Setting permissions..."
sudo chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache
sudo chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

echo "====================================="
echo " Deploy complete! Branch: $BRANCH"
echo "====================================="
