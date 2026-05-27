# Deployment & Security Fix Guide

## Issues Fixed

### 1. **HTTPS Security Warning** ✅
**Problem**: Forms showing "not secure" warning when submitting.

**Root Cause**: 
- App served over HTTP instead of HTTPS on Render
- Laravel not recognizing HTTPS from reverse proxy

**Solutions Applied**:
- ✅ Updated `AppServiceProvider.php` to trust all proxies
- ✅ Added `trustProxies()` middleware in `bootstrap/app.php`
- ✅ Enabled `URL::forceScheme('https')` in production

### 2. **Bootstrap/CSS Not Showing** ✅
**Problem**: Styling not appearing on the rendered pages.

**Root Cause**:
- Vite assets not being compiled during Docker build
- `public/build/` directory might not exist
- Manifest file missing

**Solutions Applied**:
- ✅ Updated `Dockerfile` to ensure proper asset compilation
- ✅ Changed from `php artisan serve` to proper production server setup

---

## Deployment Steps for Render

### Step 1: Set Environment Variables in Render

1. Go to your Render service settings
2. Add these environment variables:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=[Generate new key with: php artisan key:generate]
APP_URL=https://your-app-domain.onrender.com
DB_CONNECTION=mysql
DB_HOST=[Your database host]
DB_PORT=3306
DB_DATABASE=[Your database name]
DB_USERNAME=[Your database username]
DB_PASSWORD=[Your database password]
```

### Step 2: Generate Application Key

Before first deployment, generate an app key:
```bash
php artisan key:generate
```

Copy the generated key to your Render environment variable `APP_KEY`.

### Step 3: Redeploy

1. Push your changes to your git repository
2. Render will automatically rebuild and redeploy
3. The Dockerfile will:
   - Install PHP dependencies
   - Install Node dependencies
   - **Compile assets with Vite** (this generates the CSS/JS)
   - Run migrations
   - Seed admin user
   - Start the application

### Step 4: Verify Everything Works

After deployment:
1. Visit your application URL in a browser
2. Check browser console (F12) for any CSS/JS errors
3. Try logging in - should NOT show security warning
4. Verify Bootstrap styling is visible

---

## Key Changes Made

### File: `app/Providers/AppServiceProvider.php`
```php
// Added proxy trust configuration
Request::setTrustedProxies(['*'], Request::HEADER_X_FORWARDED_ALL);
```

### File: `bootstrap/app.php`
```php
// Added proxy middleware
$middleware->trustProxies(at: '*');
```

### File: `Dockerfile`
Changes:
- Kept asset compilation: `npm run build`
- Changed port from 10000 to 8000
- Changed from `php artisan serve` to production server: `php -S 0.0.0.0:${PORT} -t public/`

---

## Troubleshooting

### CSS Still Not Showing?
1. **Check Render logs** for build errors:
   - Go to Render Dashboard → Your Service → Logs
   - Look for `npm run build` errors
   - Check for `vite` related issues

2. **Manual build locally**:
   ```bash
   npm install
   npm run build
   ```
   This should create `public/build/` directory

3. **Clear cache**:
   ```bash
   php artisan optimize:clear
   ```

### Still Getting HTTPS Warning?
1. Ensure `APP_URL` in Render env var starts with `https://`
2. Verify Render service is using HTTPS (it should by default)
3. Check browser console for mixed content warnings
4. Try private/incognito window to bypass browser cache

### Database Connection Issues?
1. Verify `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` are correct
2. Ensure database is accessible from Render region
3. Check database firewall settings allow Render IP ranges
4. Test connection locally first: `php artisan tinker` then `DB::connection()->getPdo();`

---

## Testing Locally with HTTPS

To test HTTPS locally:
```bash
composer require laravel/valet
valet install
cd your-project
valet link
valet secure jmmb-project
```

Then visit: `https://jmmb-project.test`

---

## Production Checklist

- [ ] `APP_KEY` is set and unique
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://your-domain.com`
- [ ] Database credentials are correct
- [ ] All required environment variables are set in Render
- [ ] Migrations run successfully
- [ ] Assets are visible (CSS/Bootstrap)
- [ ] Login works without security warnings
- [ ] Check Render logs for any errors

---

## Need More Help?

Check Render logs at: Your Service → Logs tab
Search for: "vite", "npm", "migrate", or error keywords
