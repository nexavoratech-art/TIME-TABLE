# Automatic Timetable Maker - Complete Setup & Configuration Guide

## Project Overview
- **Type**: Laravel 13.8 Web Application (PHP 8.3+)
- **Purpose**: Automatic Timetable Generation System for Educational Institutions
- **Database**: MySQL
- **Frontend**: Vue/Vite + Tailwind CSS + Bootstrap 5
- **Status**: Development Ready

---

## System Requirements

### Installed & Verified ✓
- **PHP**: 8.5.0 (Requires 8.3+)
- **Node.js/npm**: 11.17.0
- **Composer**: 2.10.2
- **npm dependencies**: Installed ✓

### External Requirements
- **MySQL**: 5.7+ or 8.0+
- **Git**: For version control

---

## Project Modules & Features

| Module | Routes | Purpose | Controller |
|--------|--------|---------|-----------|
| Dashboard | GET / | Main interface | DashboardController |
| Programs | GET/POST /programs | Manage academic programs | ProgramController |
| Courses | GET/POST /courses | Add courses to programs | CourseController |
| Instructors | GET/POST /instructors | Register teachers | InstructorController |
| Student Groups | GET/POST /students | Create student cohorts | StudentGroupController |
| Venues | GET/POST /venues | Register classrooms | VenueController |
| Availability | GET/POST /availability | Set instructor availability | TimeSlotController |
| Timetable | GET /timetable | View generated schedule | - |

---

## Database Tables
The system creates these tables on migration:
- `users` - System users and authentication
- `departments` - Academic departments
- `programs` - Degree programs
- `student_groups` - Student cohorts
- `courses` - Course information
- `instructors` - Teacher profiles
- `venues` - Classrooms/facilities
- `time_slots` - Available time periods
- `instructor_availabilities` - Teacher availability
- `timetable_entries` - Final generated schedule
- `cache`, `jobs`, `sessions` - Laravel system tables

---

## Quick Start - Step by Step

### Step 1: Clear Composer Cache (If needed)
```bash
composer clear-cache
composer global clear-cache
```

### Step 2: Install PHP Dependencies
```bash
composer install --no-dev
```

### Step 3: Generate APP_KEY
```bash
php artisan key:generate
```

### Step 4: Configure Database (.env file)
Uncomment and update these lines in `.env`:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=your_password
```

Or create the database manually:
```bash
mysql -u root -p -e "CREATE DATABASE laravel;"
```

### Step 5: Build Frontend Assets
```bash
npm run build
```

### Step 6: Run Database Migrations
```bash
php artisan migrate
```
This creates all database tables.

### Step 7: Start the Development Server
```bash
php artisan serve
```

**Access the application**: http://localhost:8000

---

## Alternative: Full Development Mode

Run everything together with auto-reloading:
```bash
composer run dev
```

This starts:
- PHP artisan server
- Laravel queue listener
- Laravel pail (logging)
- npm Vite dev server with hot reload

---

## Configuration Files

### .env (Environment Variables)
Located at: `automatic-timetableMaker/.env`

**Key settings**:
```
APP_NAME=Laravel
APP_ENV=local          # Change to 'production' for deployment
APP_DEBUG=true         # Set to false in production
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Routes Configuration
**File**: `routes/web.php`
- All routes are defined here
- Routes point to controllers in `app/Http/Controllers/`
- Views are in `resources/views/`

### Database Configuration
**File**: `config/database.php`
- Default connection: MySQL
- Can be changed to SQLite, PostgreSQL, etc.

---

## Project Structure

```
automatic-timetableMaker/
├── app/
│   ├── Http/
│   │   └── Controllers/           # 8 main controllers
│   │       ├── DashboardController.php
│   │       ├── ProgramController.php
│   │       ├── CourseController.php
│   │       ├── InstructorController.php
│   │       ├── StudentGroupController.php
│   │       ├── VenueController.php
│   │       ├── TimeSlotController.php
│   │       └── Controller.php (base)
│   └── Models/                    # 8 database models
│       ├── User.php
│       ├── Program.php
│       ├── Course.php
│       ├── Instructor.php
│       ├── StudentGroup.php
│       ├── Venue.php
│       ├── TimeSlot.php
│       └── Department.php
│
├── database/
│   ├── migrations/               # Schema definitions (9 migrations)
│   ├── factories/
│   │   └── UserFactory.php
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── dashboard.blade.php
│   │   ├── programs.blade.php
│   │   ├── courses.blade.php
│   │   ├── instructors.blade.php
│   │   ├── students.blade.php
│   │   ├── availability.blade.php
│   │   ├── venues.blade.php
│   │   ├── timetable.blade.php
│   │   ├── layouts/
│   │   │   └── app.blade.php (main layout)
│   │   └── partials/             # Reusable components
│   ├── css/
│   │   └── app.css
│   ├── sass/
│   │   └── app.scss
│   └── js/
│       ├── app.js
│       └── bootstrap.js
│
├── routes/
│   ├── web.php                  # Web routes
│   └── console.php              # Artisan commands
│
├── public/
│   ├── index.php               # Application entry point
│   ├── robots.txt
│   └── hot (Vite development)
│
├── config/
│   ├── app.php                 # Application config
│   ├── database.php            # Database config
│   ├── auth.php                # Authentication
│   └── ... (12+ config files)
│
├── bootstrap/
│   ├── app.php                 # Bootstrap the application
│   └── providers.php           # Service providers
│
├── storage/
│   ├── app/                    # Application storage
│   ├── framework/              # Framework storage
│   ├── logs/                   # Application logs
│   └── sessions/               # Session data
│
├── tests/
│   ├── Feature/                # Feature tests
│   └── Unit/                   # Unit tests
│
├── vendor/                      # PHP dependencies (created by composer)
├── node_modules/               # JavaScript dependencies (created by npm)
│
├── .env                         # Environment variables (CRITICAL)
├── .env.example                 # Example environment file
├── composer.json               # PHP dependencies
├── composer.lock               # Locked PHP versions
├── package.json                # JavaScript dependencies
├── vite.config.js              # Vite configuration
├── artisan                      # Laravel command line
└── README.md                    # Project documentation
```

---

## Common Commands

### Laravel Commands
```bash
# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Seed database with test data
php artisan db:seed

# Start development server
php artisan serve

# Start server on specific port
php artisan serve --port=8080

# View application in Tinker shell
php artisan tinker

# Clear all caches
php artisan cache:clear

# Generate optimized autoloader
php artisan config:cache

# View environment
php artisan env:show
```

### NPM Commands
```bash
# Install dependencies
npm install

# Build for production
npm run build

# Development server with hot reload
npm run dev

# Update dependencies
npm update
```

### Composer Commands
```bash
# Install dependencies
composer install

# Install without dev packages
composer install --no-dev

# Update dependencies
composer update

# Clear cache
composer clear-cache

# Show installed packages
composer show

# Check for security issues
composer audit
```

---

## Troubleshooting Guide

### Issue: Composer Install Fails
**Error**: "Invalid package found during dependency resolution"
**Solutions**:
1. Clear composer cache: `composer clear-cache`
2. Update composer: `composer self-update`
3. Restore lock file: `git checkout composer.lock`
4. Retry: `composer install --no-dev`

### Issue: "php artisan: command not found"
**Causes**: Vendor directory doesn't exist
**Solution**:
```bash
composer install
```

### Issue: Database Connection Error
**Check**:
1. MySQL is running
2. Credentials in .env are correct
3. Database exists: `mysql -u root -p -e "SHOW DATABASES;"`

**Create database**:
```bash
mysql -u root -p -e "CREATE DATABASE laravel;"
```

### Issue: Port 8000 Already in Use
**Solution**: Use different port
```bash
php artisan serve --port=8080
```

### Issue: Assets Not Loading (Missing CSS/JS)
**Solution**: Build frontend assets
```bash
npm run build
```

### Issue: Permission Denied on Storage
**Solutions**:
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache

# Windows (run as admin)
icacls "storage" /grant:r "%username%":F /t
icacls "bootstrap\cache" /grant:r "%username%":F /t
```

### Issue: "Class not found" errors
**Solution**:
```bash
# Regenerate autoloader
composer dump-autoload

# Or with optimization
composer dump-autoload -o
```

---

## Development Workflow

### Basic Workflow
1. **Start server**: `php artisan serve`
2. **Make changes** to controllers, models, views
3. **Build assets**: `npm run build` (if CSS/JS changes)
4. **Refresh browser** to see changes

### With Live Reload
1. Terminal 1: `php artisan serve`
2. Terminal 2: `npm run dev`
3. Changes auto-compile and browser refreshes

### With Database Changes
1. Create migration: `php artisan make:migration create_table_name`
2. Edit migration file in `database/migrations/`
3. Run migration: `php artisan migrate`
4. Create model: `php artisan make:model ModelName`
5. Update controllers to use new model

---

## Environment Checklist

### Before Starting Server
- [ ] .env file exists
- [ ] Database credentials in .env are correct
- [ ] APP_KEY is generated (not empty)
- [ ] MySQL is running
- [ ] All dependencies installed (`composer install`)
- [ ] npm packages installed (`npm install`)
- [ ] Assets built (`npm run build`)
- [ ] Migrations ran (`php artisan migrate`)

### Production Checklist
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] Cache cleared: `php artisan config:cache`
- [ ] Assets optimized: `npm run build`
- [ ] Database backed up
- [ ] Logs accessible
- [ ] Security headers configured
- [ ] Composer optimized: `composer dump-autoload -o`

---

## Support & Resources

### Laravel Documentation
- Official: https://laravel.com/docs/13.x
- API: https://laravel.com/api/13.x

### Related Technologies
- **Blade Templating**: https://laravel.com/docs/blade
- **Eloquent ORM**: https://laravel.com/docs/eloquent
- **Vite**: https://vitejs.dev
- **Tailwind CSS**: https://tailwindcss.com
- **Bootstrap 5**: https://getbootstrap.com

### Common Artisan Generators
```bash
# Generate controller
php artisan make:controller ControllerName

# Generate model with migration
php artisan make:model ModelName -m

# Generate migration
php artisan make:migration create_table_name

# Generate seeder
php artisan make:seeder SeederName
```

---

## Next Steps After Setup

1. **Test the dashboard**: http://localhost:8000
2. **Create programs**: /programs
3. **Add courses**: /courses
4. **Register instructors**: /instructors
5. **Add student groups**: /students
6. **Set up venues**: /venues
7. **Configure availability**: /availability
8. **Generate timetable**: /timetable

---

## Important Notes

- **Keep .env secure**: Never commit to version control
- **Database backups**: Backup before major changes
- **Logs**: Check `storage/logs/` for issues
- **Sessions**: Database driver stores in `sessions` table
- **Cache**: Configure CACHE_STORE in .env
- **Queue**: Jobs stored in `jobs` table (database driver)

---

## Version Information

- Laravel: 13.8
- PHP: 8.3+
- MySQL: 5.7+
- Node.js: 16+
- npm: 8+

**Setup Date**: August 14, 2026
**Status**: Ready for Development

---

## File Locations

| Purpose | Path |
|---------|------|
| Configuration | `config/` |
| Controllers | `app/Http/Controllers/` |
| Models | `app/Models/` |
| Views | `resources/views/` |
| Database | `.env` (connection info) |
| Routes | `routes/web.php` |
| Logs | `storage/logs/` |
| Environment | `.env` |
| Cache | `bootstrap/cache/` |

---

This guide covers the complete setup, configuration, and operational procedures for the Automatic Timetable Maker system. Follow the steps systematically, and the application will be running successfully.

**Last Updated**: August 14, 2026
**Created For**: System Development & Deployment
