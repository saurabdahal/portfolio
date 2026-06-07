#!/bin/bash
set -e

# NOTE: git pull is handled by the stable wrapper at /home/admin/deploy.sh
# BEFORE this script runs, so the latest version of this file is always used.
# To change deploy steps, just edit this file and push - no server changes needed.

BRANCH=${1:-main}
APP_DIR=/home/admin/portfolio

echo "====================================="
echo " Deploying branch: $BRANCH"
echo "====================================="

cd $APP_DIR

echo "[1/6] Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-scripts

echo "[2/6] Clearing stale caches and rediscovering packages..."
sudo rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
php artisan optimize:clear
php artisan package:discover --ansi

echo "[3/6] Installing JS dependencies..."
npm ci

echo "[4/6] Building frontend assets..."
npm run build

echo "[5/6] Running database migrations..."
php artisan migrate --force
php artisan db:seed --class=BlogPostSeeder --force

echo "[6/6] Caching config, routes and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link 2>/dev/null || true

echo "Setting permissions..."
sudo chown -R admin:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache
sudo chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

echo "====================================="
echo " Deploy complete! Branch: $BRANCH"
echo "====================================="
