# Railway Deployment Guide

This guide will help you deploy the Genealogy application to Railway.

## Prerequisites

1. **Install Railway CLI globally** (required for MCP server):
   ```bash
   # macOS (using Homebrew)
   brew tap railwayapp/railway
   brew install railway
   
   # Or using npm (if you have npm installed)
   npm install -g @railway/cli
   ```

2. **Login to Railway**:
   ```bash
   railway login
   ```
   This will open your browser for authentication.

## Deployment Steps

### Step 1: Create or Link Railway Project

If you don't have a Railway project yet:

```bash
# Create a new project and link it
railway init

# Or if you already have a project
railway link
```

### Step 2: Add Required Services

Your application needs:
- **Application Service** (PHP/Laravel)
- **MySQL Database Service**

You can add these via Railway dashboard or CLI:

```bash
# Add MySQL database (Railway will auto-generate connection variables)
railway add mysql
```

### Step 3: Set Environment Variables

Set the required environment variables in Railway:

```bash
# Application settings
railway variables set APP_NAME="Genealogy"
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false
railway variables set APP_URL=https://your-app.railway.app

# Generate app key (run locally first)
php artisan key:generate --show

# Then set it in Railway
railway variables set APP_KEY="base64:your-generated-key-here"

# Database (Railway MySQL plugin auto-provides these, but verify they exist)
# ${{MySQL.MYSQLHOST}}
# ${{MySQL.MYSQLPORT}}
# ${{MySQL.MYSQLDATABASE}}
# ${{MySQL.MYSQLUSER}}
# ${{MySQL.MYSQLPASSWORD}}

# Mail configuration (update with your SMTP settings)
railway variables set MAIL_MAILER=smtp
railway variables set MAIL_HOST=your-smtp-host
railway variables set MAIL_PORT=587
railway variables set MAIL_USERNAME=your-smtp-username
railway variables set MAIL_PASSWORD=your-smtp-password
railway variables set MAIL_ENCRYPTION=tls
railway variables set MAIL_FROM_ADDRESS=noreply@yourdomain.com

# Session & Cache (use Redis if available, otherwise file/database)
railway variables set SESSION_DRIVER=database
railway variables set CACHE_DRIVER=file
railway variables set QUEUE_CONNECTION=database
```

### Step 4: Configure railway.json

The `railway.json` file is already configured for Laravel deployment. It includes:

- Build command: Installs dependencies and optimizes Laravel
- Start command: Runs PHP development server (for production, you may want to use PHP-FPM with Nginx)

### Step 5: Deploy

Deploy your application:

```bash
railway up
```

Or deploy to a specific environment:

```bash
railway up --environment production
```

### Step 6: Run Migrations

After first deployment, run database migrations:

```bash
railway run php artisan migrate --force
```

Or via Railway dashboard:
1. Go to your service
2. Click on "Deployments"
3. Click on the latest deployment
4. Go to "Shell" tab
5. Run: `php artisan migrate --force`

### Step 7: Generate Domain

Get a public URL for your application:

```bash
railway domain
```

## Post-Deployment

1. **Create Storage Link**:
   ```bash
   railway run php artisan storage:link
   ```

2. **Cache Configuration** (for better performance):
   ```bash
   railway run php artisan config:cache
   railway run php artisan route:cache
   railway run php artisan view:cache
   ```

3. **Seed Database** (optional, for demo data):
   ```bash
   railway run php artisan db:seed
   ```

## Troubleshooting

### View Logs
```bash
railway logs
```

### Check Status
```bash
railway status
```

### Run Artisan Commands
```bash
railway run php artisan [command]
```

### Access Shell
```bash
railway shell
```

## Database Connection Variables

Railway's MySQL plugin automatically provides these variables:
- `MYSQLHOST` → Use as `DB_HOST`
- `MYSQLPORT` → Use as `DB_PORT`
- `MYSQLDATABASE` → Use as `DB_DATABASE`
- `MYSQLUSER` → Use as `DB_USERNAME`
- `MYSQLPASSWORD` → Use as `DB_PASSWORD`

In your Railway environment variables, reference them as:
```
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

## Production Recommendations

1. **Use Redis** for caching and sessions (if available on Railway)
2. **Enable Queue Workers** for background jobs
3. **Set up Backup** strategy for database
4. **Configure Custom Domain** (instead of Railway subdomain)
5. **Set up Monitoring** and alerts

## Notes

- Railway automatically detects PHP applications
- The `railway.json` configures build and start commands
- For production, consider using PHP-FPM with Nginx (may need custom Dockerfile)
- Railway provides automatic HTTPS for your domains

