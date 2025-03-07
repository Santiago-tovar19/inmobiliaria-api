#!/usr/bin/env bash
# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Ejecutar migraciones
php artisan migrate --force

# Iniciar servidor
php artisan serve --host 0.0.0.0 --port $PORT
