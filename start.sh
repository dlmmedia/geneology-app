#!/bin/sh
# Start script for Railway deployment
# Convert PORT to integer and start Laravel server

PORT_INT=$(echo $PORT | sed 's/[^0-9]//g')
if [ -z "$PORT_INT" ]; then
    PORT_INT=8000
fi

exec php artisan serve --host=0.0.0.0 --port=$PORT_INT
