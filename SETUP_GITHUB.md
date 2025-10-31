# GitHub Repository Setup Guide

## Step 1: Prepare Your Code

Make sure all your changes are ready:

```bash
# Check status
git status

# Add all new files (photo fix commands, scripts, etc.)
git add .

# Commit changes
git commit -m "Add photo management commands and deployment configs"
```

## Step 2: Create GitHub Repository

### Option A: Using GitHub CLI (if installed)

```bash
# Install GitHub CLI if not installed:
# brew install gh

# Authenticate
gh auth login

# Create repository
gh repo create genealogy-app --public --source=. --remote=origin --push
```

### Option B: Using GitHub Web Interface

1. Go to https://github.com/new
2. Repository name: `genealogy-app` (or your preferred name)
3. Description: "Family tree genealogy application built with Laravel"
4. Choose Public or Private
5. **DO NOT** initialize with README, .gitignore, or license (we already have these)
6. Click "Create repository"

Then connect your local repo:

```bash
# Add remote (replace YOUR_USERNAME with your GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/geneology-app.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## Step 3: Deploy to Railway (Recommended for Laravel)

1. Go to https://railway.app
2. Sign up/login with GitHub
3. Click "New Project"
4. Select "Deploy from GitHub repo"
5. Choose your `geneology-app` repository
6. Railway will auto-detect Laravel and set it up
7. Add MySQL database:
   - Click "+ New" → Database → MySQL
8. Configure environment variables (see `.env.example`)
9. Deploy!

Railway will automatically:
- Build your app
- Run migrations
- Provide a public URL

## Step 4: Deploy to Render (Alternative)

1. Go to https://render.com
2. Sign up/login with GitHub
3. Click "New +" → "Web Service"
4. Connect your GitHub repository
5. Settings:
   - **Name**: genealogy-app
   - **Environment**: PHP
   - **Build Command**: `composer install --no-dev --optimize-autoloader && php artisan key:generate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache`
   - **Start Command**: `php artisan serve --host 0.0.0.0 --port $PORT`
6. Add PostgreSQL or MySQL database
7. Configure environment variables
8. Deploy!

## Important Environment Variables

Set these in your deployment platform:

```env
APP_NAME=Genealogy
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-url.railway.app

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

## Post-Deployment

After deployment, run these commands:

```bash
# SSH into your deployment or use Railway/Render CLI
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Note About Vercel

Vercel is **not recommended** for Laravel applications because:
- Laravel requires PHP runtime (Vercel is Node.js focused)
- Need persistent database connection
- Background jobs (queues) don't work well
- Storage needs to be persistent

If you really want to use Vercel, you'd need to:
1. Deploy Laravel backend to Railway/Render
2. Deploy a Next.js frontend to Vercel
3. Connect them via API

This is much more complex and not worth it for a full-stack Laravel app.

