# Quick Start Commands - Copy & Paste Ready

## ONE-TIME SETUP (Run Once)

```powershell
# Step 1: Clear composer caches
composer clear-cache
composer global clear-cache

# Step 2: Install PHP packages
composer install --no-dev

# Step 3: Generate encryption key
php artisan key:generate

# Step 4: Build frontend assets
npm run build
```

## CONFIGURE DATABASE

Edit the `.env` file and uncomment/update:

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Or create database from command line:

```powershell
mysql -u root -p -e "CREATE DATABASE laravel;"
```

## RUN DATABASE MIGRATIONS

```powershell
php artisan migrate
```

This creates all tables:
- users, departments, programs, student_groups, courses
- instructors, venues, time_slots, instructor_availabilities
- timetable_entries, cache, jobs, sessions

## START THE SERVER

### Option 1: Basic Development Server
```powershell
php artisan serve
```
Then open: http://localhost:8000

### Option 2: Full Development Environment (All Services)
```powershell
composer run dev
```
This runs:
- PHP server on port 8000
- Queue listener
- Logs viewer (pail)
- Vite dev server with hot reload

Then open: http://localhost:8000

### Option 3: Custom Port
```powershell
php artisan serve --port=8080
```

## BUILD FRONTEND (When Changing CSS/JS)

```powershell
npm run build
```

For development with auto-recompile:
```powershell
npm run dev
```

## COMMON TROUBLESHOOTING COMMANDS

```powershell
# Clear all caches
php artisan cache:clear

# Show environment variables
php artisan env:show

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Regenerate autoloader
composer dump-autoload -o

# Rollback last migration
php artisan migrate:rollback

# Fresh database (WARNING: Deletes all data)
php artisan migrate:refresh

# Seed database with test data
php artisan db:seed
```

## PROJECT ROUTES (After Server Started)

- Dashboard: http://localhost:8000/
- Programs: http://localhost:8000/programs
- Courses: http://localhost:8000/courses
- Instructors: http://localhost:8000/instructors
- Student Groups: http://localhost:8000/students
- Venues: http://localhost:8000/venues
- Instructor Availability: http://localhost:8000/availability
- Timetable: http://localhost:8000/timetable

## If Composer Install Fails

Try these strategies in order:

```powershell
# Strategy 1: Clear everything
composer clear-cache
composer global clear-cache
rm composer.lock
git checkout composer.lock
composer install --no-dev

# Strategy 2: Update composer
composer self-update
composer install --no-dev

# Strategy 3: Ignore platform requirements
composer install --ignore-platform-reqs --no-dev

# Strategy 4: Use different install
composer update --no-dev --no-audit

# Strategy 5: Fresh lock file
rm composer.lock
rm composer.json
git checkout .
composer install --no-dev
```

## If "php artisan" Command Not Found

This means vendor directory is missing.

```powershell
composer install
```

If composer also fails, check:
1. Is PHP installed? → `php -v`
2. Is Composer installed? → `composer -v`
3. Do you have internet connection?

## If Database Connection Fails

```powershell
# Check if MySQL is running (Windows)
tasklist | findstr mysql

# Check MySQL status
mysql -u root -p -e "SELECT 1"

# Create database if it doesn't exist
mysql -u root -p -e "CREATE DATABASE laravel;"

# Show all databases
mysql -u root -p -e "SHOW DATABASES;"
```

## Environment File (.env) Template

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:GENERATED_BY_KEY_GENERATE
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=your_password

BROADCAST_CONNECTION=log
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database

MAIL_MAILER=log
```

## Directory Permissions (Linux/Mac Only)

```bash
chmod -R 775 storage bootstrap/cache
```

## Performance Commands (Production Only)

```powershell
# Optimize autoloader
composer dump-autoload -o

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Minify CSS/JS
npm run build
```

## Clear Cache (When Things Break)

```powershell
# Application cache
php artisan cache:clear

# Config cache
php artisan config:clear

# Route cache
php artisan route:clear

# View cache
php artisan view:clear

# All caches
php artisan optimize:clear
```

---

## PROJECT LOCATION

```
E:\CIT Lesson notes\TIME-TABLE\automatic-timetableMaker\
```

## KEY FILES

- `.env` - Configuration
- `routes/web.php` - All routes
- `app/Http/Controllers/` - Business logic
- `app/Models/` - Database models
- `resources/views/` - HTML templates
- `database/migrations/` - Database schema
- `storage/logs/` - Application logs

---

**Last Updated**: August 14, 2026
**Status**: Ready for Setup
