# Vercel Deployment Guide

## 1. Environment Setup

You have successfully configured the application for Vercel deployment. The following changes were made:
- Created `vercel.json` for PHP runtime configuration.
- Updated `bootstrap/app.php` to trust Vercel proxies.
- Added `ext-pgsql` and `glhd/laravel-vercel-blob` to `composer.json`.
- Configured `config/filesystems.php` to use Vercel Blob.

## 2. Database (Neon)

1.  Create a project on **Neon** (or use Vercel Postgres).
2.  Get your connection string (Postgres).
3.  In your Vercel Project Settings -> Environment Variables, add:
    ```
    DB_CONNECTION=pgsql
    DB_HOST=your-neon-host.neondb.net
    DB_PORT=5432
    DB_DATABASE=neondb
    DB_USERNAME=your-username
    DB_PASSWORD=your-password
    ```

## 3. Storage (Vercel Blob)

1.  Go to Vercel Dashboard -> Storage -> Create Database -> **Blob**.
2.  Connect it to your project.
3.  This will automatically add `BLOB_READ_WRITE_TOKEN` to your environment variables.
4.  Set the filesystem driver in Environment Variables:
    ```
    FILESYSTEM_DISK=vercel-blob
    ```

## 4. Other Environment Variables

Add these to Vercel Environment Variables:
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...(your key)...
APP_URL=https://your-project.vercel.app
ASSET_URL=https://your-project.vercel.app
SESSION_DRIVER=cookie
CACHE_DRIVER=array
```
*(Note: For `SESSION_DRIVER` and `CACHE_DRIVER`, using Redis (Vercel KV) is recommended for better performance, but `cookie`/`array` works for simple setups. File driver will NOT work.)*

## 5. Deploy

1.  Push your changes to GitHub/GitLab.
2.  Import the repository in Vercel.
3.  Vercel will detect `vercel.json` and deploy using the PHP runtime.

## 6. Run Migrations

Since you cannot SSH into Vercel, you can run migrations via a route (if secured) or connect to your Neon database locally and run:
```bash
php artisan migrate --force
```
(Make sure your local `.env` points to the Neon DB temporarily, or use a separate DB management tool).

