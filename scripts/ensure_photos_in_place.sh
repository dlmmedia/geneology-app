#!/bin/bash

# Script to ensure all photos are properly set up
# This script checks and updates the database photo references

cd "$(dirname "$0")/.."

echo "=== Ensuring All Photos Are In Place ==="
echo ""

# Check if we can find PHP
if command -v php &> /dev/null; then
    PHP_CMD="php"
elif [ -f /usr/local/bin/php ]; then
    PHP_CMD="/usr/local/bin/php"
elif [ -f /opt/homebrew/bin/php ]; then
    PHP_CMD="/opt/homebrew/bin/php"
else
    echo "ERROR: PHP not found. Please ensure PHP is installed and in your PATH."
    exit 1
fi

echo "Using PHP: $PHP_CMD"
echo ""

# Run the fix command if available
if [ -f artisan ]; then
    echo "Running: $PHP_CMD artisan photos:fix-all"
    $PHP_CMD artisan photos:fix-all --team="BRITISH ROYALS"
    echo ""
    
    echo "Checking photo references..."
    $PHP_CMD artisan photos:fix-all --team="BRITISH ROYALS"
else
    echo "ERROR: artisan file not found"
    exit 1
fi

echo ""
echo "✓ Done!"

