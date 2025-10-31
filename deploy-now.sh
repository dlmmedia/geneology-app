#!/bin/bash

# Automated Railway Deployment Script
# This script will deploy the genealogy app to Railway

set -e

echo "🚂 Railway Deployment - Starting..."
echo "===================================="
echo ""

# Check if logged in
if ! railway whoami &> /dev/null; then
    echo "❌ Not logged in to Railway."
    echo "   Please run: railway login"
    exit 1
fi

echo "✅ Logged in as: $(railway whoami)"
echo ""

# Check if project is linked, if not create/link
if [ ! -f ".railway/service.toml" ]; then
    echo "📦 Creating Railway project..."
    railway init
    echo ""
else
    echo "✅ Project already linked"
    echo ""
fi

# Show current project status
echo "📋 Current project:"
railway status
echo ""

# Check if MySQL service exists, if not add it
echo "🗄️  Checking for MySQL database service..."
if ! railway service 2>&1 | grep -i mysql > /dev/null; then
    echo "   Adding MySQL database service..."
    railway add mysql
    echo "✅ MySQL service added"
    echo ""
else
    echo "✅ MySQL service already exists"
    echo ""
fi

# Generate APP_KEY if needed
echo "🔑 Setting up application key..."
if ! railway variables get APP_KEY &> /dev/null; then
    echo "   Generating APP_KEY..."
    APP_KEY=$(php artisan key:generate --show 2>&1 | grep "APP_KEY=" | cut -d '=' -f2 || echo "")
    if [ -z "$APP_KEY" ]; then
        echo "   ⚠️  Could not auto-generate key. Please set manually:"
        echo "   railway variables set APP_KEY='base64:...'"
    else
        railway variables set "APP_KEY=$APP_KEY"
        echo "✅ APP_KEY set"
    fi
else
    echo "✅ APP_KEY already configured"
fi
echo ""

# Set application environment variables
echo "⚙️  Setting application environment variables..."
railway variables set APP_NAME="Genealogy" || true
railway variables set APP_ENV="production" || true
railway variables set APP_DEBUG="false" || true

# Set database connection (Railway MySQL plugin provides these automatically)
# We need to reference the MySQL service variables
railway variables set DB_CONNECTION="mysql" || true
railway variables set DB_HOST="\${{MySQL.MYSQLHOST}}" || true
railway variables set DB_PORT="\${{MySQL.MYSQLPORT}}" || true
railway variables set DB_DATABASE="\${{MySQL.MYSQLDATABASE}}" || true
railway variables set DB_USERNAME="\${{MySQL.MYSQLUSER}}" || true
railway variables set DB_PASSWORD="\${{MySQL.MYSQLPASSWORD}}" || true

# Set session and cache drivers
railway variables set SESSION_DRIVER="database" || true
railway variables set CACHE_DRIVER="file" || true
railway variables set QUEUE_CONNECTION="database" || true

echo "✅ Environment variables configured"
echo ""

# Get the app URL if available
if railway domain &> /dev/null; then
    APP_URL=$(railway domain 2>&1 | grep -oP 'https://[^\s]+' | head -1 || echo "")
    if [ ! -z "$APP_URL" ]; then
        railway variables set APP_URL="$APP_URL" || true
        echo "✅ APP_URL set to: $APP_URL"
    fi
fi
echo ""

# Deploy the application
echo "🚀 Deploying application to Railway..."
railway up
echo ""

echo "⏳ Waiting for deployment to initialize (30 seconds)..."
sleep 30
echo ""

# Run migrations
echo "📊 Running database migrations..."
railway run php artisan migrate --force || echo "⚠️  Migrations may need to run manually"
echo ""

# Create storage link
echo "🔗 Creating storage link..."
railway run php artisan storage:link || echo "⚠️  Storage link may already exist"
echo ""

# Get domain
echo "🌐 Generating public domain..."
railway domain || echo "⚠️  Domain generation may have issues"
echo ""

# Show final status
echo "✅ Deployment complete!"
echo ""
echo "📋 Final status:"
railway status
echo ""
echo "🔗 Your application should be available at the domain shown above"
echo ""
echo "📝 Next steps:"
echo "   1. Set up mail configuration: railway variables set MAIL_*"
echo "   2. Configure any additional services if needed"
echo "   3. Visit your Railway dashboard to monitor the deployment"
echo ""

