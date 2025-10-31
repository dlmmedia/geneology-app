#!/bin/sh
# Start script for Railway deployment
# Convert PORT to integer and start Laravel server

PORT_INT=$(echo $PORT | sed 's/[^0-9]//g')
if [ -z "$PORT_INT" ]; then
    PORT_INT=8000
fi

# Clear all caches
echo "Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration failed or already up to date"

# Seed essential data (settings, etc.)
echo "Seeding essential data..."
php artisan db:seed --class=SettingSeeder --force || echo "Seeding failed or already seeded"

# Create storage link if it doesn't exist
echo "Creating storage link..."
php artisan storage:link || echo "Storage link already exists"

# Start Laravel server
echo "Starting Laravel server on port $PORT_INT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT_INT
