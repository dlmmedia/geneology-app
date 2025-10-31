#!/bin/bash

# Quick fix script to ensure photos are displayed
cd "$(dirname "$0")/.."

echo "=== Fixing Photo Display Issues ==="
echo ""

# Step 1: Ensure storage symlink exists
if [ ! -L "public/storage" ]; then
    echo "Creating storage symlink..."
    if command -v php &> /dev/null; then
        php artisan storage:link
    else
        ln -sfn ../storage/app/public public/storage
    fi
fi

# Step 2: Find PHP
if command -v php &> /dev/null; then
    PHP_CMD="php"
elif [ -f /usr/local/bin/php ]; then
    PHP_CMD="/usr/local/bin/php"
elif [ -f /opt/homebrew/bin/php ]; then
    PHP_CMD="/opt/homebrew/bin/php"
else
    echo "ERROR: PHP not found."
    echo "Please run manually: php artisan photos:ensure-all --team='BRITISH ROYALS'"
    exit 1
fi

echo "Using PHP: $PHP_CMD"
echo ""

# Step 3: Run the fix command
echo "Running photo fix command..."
$PHP_CMD artisan photos:ensure-all --team="BRITISH ROYALS"

echo ""
echo "✓ Done! Check your application now."

