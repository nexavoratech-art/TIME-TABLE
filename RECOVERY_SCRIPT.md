# Recovery Script - Run this when Packagist is fixed

## Quick Recovery (Copy & Paste This)

Run these commands in the project directory:

```powershell
# Navigate to project
cd "e:\CIT Lesson notes\TIME-TABLE\automatic-timetableMaker"

# Step 1: Clear composer caches
composer clear-cache
composer global clear-cache

# Step 2: Install PHP dependencies  
composer install --no-dev

# Step 3: Generate key (if needed)
php artisan key:generate

# Step 4: Build frontend
npm run build

# Step 5: Run migrations
php artisan migrate

# Step 6: Start server
php artisan serve
```

Then open: **http://localhost:8000**

---

## Alternative: Full Clean Install

If the above doesn't work, try:

```powershell
# Clean start
rm -r vendor composer.lock
rm -r node_modules package-lock.json

# Fresh install
composer install --no-dev
npm install
npm run build

# Database setup
php artisan migrate

# Run server
php artisan serve
```

---

## Using Docker (If Composer Still Fails)

Create `Dockerfile` in project root:

```dockerfile
FROM php:8.3-apache
RUN apt-get update && apt-get install -y \
    git curl composer mysql-client \
    libssl-dev libcurl4-openssl-dev

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev
RUN npm install && npm run build

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
```

Build and run:
```bash
docker build -t timetable .
docker run -p 8000:8000 timetable
```

Access: http://localhost:8000

---

## Troubleshooting Recovery

**If "php artisan not found":**
- Composer install failed
- Try: `composer install --no-dev` again

**If "Database connection failed":**
- MySQL not running
- Check .env database credentials
- Verify database "laravel" exists

**If "Port 8000 in use":**
- Use different port: `php artisan serve --port=8080`
- Access: http://localhost:8080

**If npm build fails:**
- Clear cache: `npm cache clean --force`
- Try: `npm install` and `npm run build` again

---

## Success Indicators

Once running successfully, you should see:

```
   INFO  Server running on [http://127.0.0.1:8000]

  Press Ctrl+C to quit
```

Then open http://localhost:8000 and you should see:
- Dashboard (main page)
- All navigation menus working
- Database tables created

---

## Files to Know

**In project root:**
- `.env` - Database configuration
- `composer.json` - PHP dependencies
- `package.json` - JavaScript dependencies
- `routes/web.php` - All application routes
- `app/Models/` - Database models
- `resources/views/` - HTML templates
- `app/Http/Controllers/` - Business logic

---

## Current Project Status

- ✓ Configuration: Ready
- ✓ Frontend: Ready  
- ✗ Backend: Waiting on Composer
- ✗ Database: Waiting on Migrations
- ✗ Server: Waiting on Installation

Once Composer works, all will be complete!

---

**Estimated Time to Full Setup:**
- Installation: 2-5 minutes
- Migrations: 30 seconds
- Total: ~5 minutes

**You're almost there!**
