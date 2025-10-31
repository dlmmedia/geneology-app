#!/bin/bash

# Railway Deployment Helper Script
# This script helps deploy the Genealogy application to Railway

set -e

echo "🚂 Railway Deployment Helper"
echo "============================"
echo ""

# Check if Railway CLI is installed
if ! command -v railway &> /dev/null; then
    echo "❌ Railway CLI is not installed."
    echo "   Please install it first:"
    echo "   macOS: brew tap railwayapp/railway && brew install railway"
    echo "   Or: npm install -g @railway/cli"
    echo ""
    exit 1
fi

echo "✅ Railway CLI found: $(railway --version)"
echo ""

# Check if logged in
if ! railway whoami &> /dev/null; then
    echo "⚠️  Not logged in to Railway."
    echo "   Please run: railway login"
    echo ""
    exit 1
fi

echo "✅ Logged in as: $(railway whoami)"
echo ""

# Check if project is linked
if [ ! -f ".railway/service.toml" ]; then
    echo "📦 Project is not linked to Railway."
    echo "   Initializing Railway project..."
    railway init
    echo ""
fi

echo "📋 Current Railway project status:"
railway status
echo ""

# Function to set environment variables interactively
set_env_vars() {
    echo "🔧 Setting up environment variables..."
    echo ""
    
    # Check if APP_KEY is set
    if ! railway variables get APP_KEY &> /dev/null; then
        echo "⚠️  APP_KEY not set. Generating..."
        APP_KEY=$(php artisan key:generate --show 2>/dev/null | grep -oP '(?<=APP_KEY=).*' || echo "")
        if [ -z "$APP_KEY" ]; then
            echo "   Please generate APP_KEY manually: php artisan key:generate --show"
            echo "   Then set it: railway variables set APP_KEY='your-key-here'"
        else
            railway variables set "APP_KEY=$APP_KEY"
            echo "✅ APP_KEY set"
        fi
        echo ""
    fi
    
    # Set default app variables
    railway variables set APP_NAME="Genealogy" 2>/dev/null || true
    railway variables set APP_ENV="production" 2>/dev/null || true
    railway variables set APP_DEBUG="false" 2>/dev/null || true
    
    echo "✅ Basic environment variables configured"
    echo ""
}

# Ask user what to do
echo "What would you like to do?"
echo "1) Set up environment variables"
echo "2) Deploy application"
echo "3) Run database migrations"
echo "4) Generate domain"
echo "5) View logs"
echo "6) Do everything (setup + deploy + migrate)"
echo ""
read -p "Enter choice [1-6]: " choice

case $choice in
    1)
        set_env_vars
        ;;
    2)
        echo "🚀 Deploying to Railway..."
        railway up
        ;;
    3)
        echo "📊 Running database migrations..."
        railway run php artisan migrate --force
        ;;
    4)
        echo "🌐 Generating domain..."
        railway domain
        ;;
    5)
        echo "📋 Showing logs..."
        railway logs
        ;;
    6)
        set_env_vars
        echo ""
        echo "🚀 Deploying to Railway..."
        railway up
        echo ""
        echo "⏳ Waiting for deployment to complete..."
        sleep 10
        echo ""
        echo "📊 Running database migrations..."
        railway run php artisan migrate --force
        echo ""
        echo "🔗 Creating storage link..."
        railway run php artisan storage:link
        echo ""
        echo "🌐 Generating domain..."
        railway domain
        echo ""
        echo "✅ Deployment complete!"
        ;;
    *)
        echo "Invalid choice"
        exit 1
        ;;
esac

echo ""
echo "✨ Done!"

