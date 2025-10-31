# Deployment Guide

## ⚠️ Important: Vercel vs Laravel

**Vercel is designed for frontend frameworks (Next.js, React, Vue) and serverless functions.** 

Laravel applications require:
- PHP runtime
- Database (MySQL/PostgreSQL)
- Persistent storage
- Background job processing
- Full server capabilities

### Recommended Deployment Options for Laravel:

1. **Railway** - Easy Laravel deployment with database
   - https://railway.app
   - Supports PHP, MySQL, PostgreSQL
   - Automatic HTTPS

2. **Laravel Forge** - Professional Laravel hosting
   - https://forge.laravel.com
   - Full server management
   - Auto-deployments from GitHub

3. **DigitalOcean App Platform** - Simple PaaS
   - https://www.digitalocean.com/products/app-platform
   - Supports Laravel out of the box

4. **Render** - Modern PaaS
   - https://render.com
   - Free tier available
   - Supports Laravel

5. **VPS + Docker** - Full control
   - Use Dockerfile included in project
   - Deploy to any VPS provider

## If You Still Want Vercel

Vercel can host the frontend, but you'll need:
- Separate backend API (Laravel on Railway/Render)
- CORS configuration
- API proxy

This is complex and not recommended for a full-stack Laravel app.

## GitHub Setup

```bash
# Initialize git (if not already)
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit: Genealogy application"

# Create repository on GitHub (via web or CLI)
gh repo create genealogy-app --public --source=. --remote=origin --push
```

## Environment Variables

Before deploying, set these environment variables:

```env
APP_NAME="Genealogy"
APP_ENV=production
APP_KEY=base64:... (generate with: php artisan key:generate)
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

