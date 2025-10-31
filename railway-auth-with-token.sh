#!/bin/bash

# Railway Token-Based Authentication
# Get your token from: https://railway.app/token

echo "🚂 Railway Token Authentication"
echo "==============================="
echo ""

# Check if token is provided as argument
if [ -z "$1" ]; then
    echo "📋 To use token authentication:"
    echo ""
    echo "1. Get your Railway API token from: https://railway.app/token"
    echo "2. Run this script with your token:"
    echo "   ./railway-auth-with-token.sh YOUR_TOKEN_HERE"
    echo ""
    echo "Or set it as an environment variable:"
    echo "   export RAILWAY_TOKEN='your-token-here'"
    echo "   ./railway-auth-with-token.sh"
    echo ""
    
    # Check if token is in environment variable
    if [ ! -z "$RAILWAY_TOKEN" ]; then
        echo "✅ Found RAILWAY_TOKEN in environment"
        TOKEN="$RAILWAY_TOKEN"
    else
        echo "❌ No token provided"
        echo ""
        echo "Opening token page in browser..."
        open "https://railway.app/token" 2>/dev/null || xdg-open "https://railway.app/token" 2>/dev/null
        exit 1
    fi
else
    TOKEN="$1"
fi

echo "🔑 Authenticating with token..."
railway login --token "$TOKEN"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Successfully authenticated!"
    echo "Logged in as: $(railway whoami)"
    echo ""
    echo "🚀 You can now proceed with deployment!"
else
    echo ""
    echo "❌ Authentication failed"
    echo "Please check your token and try again"
    exit 1
fi

