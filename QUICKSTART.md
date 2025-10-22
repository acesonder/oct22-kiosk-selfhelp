# KioskHelp - Quick Start Guide

Get up and running with KioskHelp in minutes!

## Prerequisites

- PHP 7.4+ installed
- MySQL 5.7+ installed
- Web server (Apache or Nginx)
- Basic command line knowledge

## 5-Minute Setup

### Step 1: Download
```bash
git clone https://github.com/acesonder/oct22-kiosk-selfhelp.git
cd oct22-kiosk-selfhelp
```

### Step 2: Set Permissions
```bash
chmod 755 uploads/ backup/ logs/ cache/ tmp/
```

### Step 3: Create Database
```bash
mysql -u root -p
```
```sql
CREATE DATABASE kioskhelp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kioskhelp_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON kioskhelp.* TO 'kioskhelp_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 4: Start Web Server
```bash
# If using PHP built-in server (development only)
php -S localhost:8000

# OR configure Apache/Nginx (see INSTALL.md)
```

### Step 5: Initialize Database
1. Open browser: `http://localhost:8000/admin/login.php`
2. Enter admin code: `079777`
3. Click "Database Tools"
4. Fill in credentials:
   - Host: `localhost`
   - Database: `kioskhelp`
   - Username: `kioskhelp_user`
   - Password: `your_password`
5. Click "Initialize Database"

### Step 6: Start Using!

**Landing Page**: http://localhost:8000/

**Client Portal**: 
- Register: http://localhost:8000/client/register.php
- Login: http://localhost:8000/client/login.php

**Admin Panel**: 
- Login: http://localhost:8000/admin/login.php (code: 079777)

## Default Credentials

### Admin Access
- Code: `079777` (change after installation!)

### Test User (created during database initialization)
- Username: `admin`
- Password: `admin123`
- Role: Admin

## Key Features to Try

### For Clients:
1. **Register** - Create a new account and see auto-generated username
2. **Dashboard** - View your personalized dashboard
3. **Password Recovery** - Test the recovery flow
4. **Explore Tools** - Check out the self-help tools overview

### For Admins:
1. **Database Tools** - Initialize, backup database
2. **System Statistics** - View user and activity stats
3. **Monitor System** - Check database connection status

## Common Issues

### Can't connect to database?
```bash
# Check MySQL is running
sudo systemctl status mysql

# Test connection
mysql -h localhost -u kioskhelp_user -p kioskhelp
```

### Permission errors?
```bash
# Fix directory permissions
sudo chown -R www-data:www-data uploads/ logs/ backup/
chmod 755 uploads/ logs/ backup/ cache/ tmp/
```

### White screen?
```bash
# Check PHP error log
tail -f logs/php_errors.log

# Enable error display temporarily
# Edit config/app.php: 'environment' => 'development'
```

## What's Next?

- Read the full [INSTALL.md](INSTALL.md) for production setup
- Review [README.md](README.md) for feature documentation
- Explore the code structure
- Customize themes and branding
- Add service providers
- Create staff and provider accounts

## Testing the System

### Test Client Registration
1. Go to http://localhost:8000/client/register.php
2. Fill in the form with test data:
   - First Name: Michael
   - Last Name: Brown
   - DOB: 1984-05-06
   - Expected username: MICBRO050684
3. Complete registration
4. Verify auto-login to dashboard

### Test Password Recovery
1. Go to http://localhost:8000/client/recovery.php
2. Enter test user's information
3. Answer security question
4. See username revealed
5. Reset password
6. Verify auto-login

### Test Admin Panel
1. Go to http://localhost:8000/admin/login.php
2. Enter code: 079777
3. View system statistics
4. Create database backup
5. Check database status

## File Structure Overview

```
kioskhelp/
├── admin/              # Admin dashboard
├── api/                # API endpoints
├── assets/             # CSS, JS, images
├── backup/             # Database backups
├── client/             # Client portal
├── config/             # Configuration
├── database/           # SQL schema
├── includes/           # PHP classes
├── logs/               # Error logs
├── provider/           # Provider portal (placeholder)
├── staff/              # Staff portal (placeholder)
└── index.php           # Landing page
```

## Security Notes

⚠️ **Before going to production:**

1. Change admin code from `079777`
2. Update default credentials
3. Enable HTTPS
4. Set secure file permissions
5. Disable error display
6. Configure regular backups
7. Set up fail2ban
8. Review security headers

## Getting Help

- **Documentation**: See README.md and INSTALL.md
- **Issues**: https://github.com/acesonder/oct22-kiosk-selfhelp/issues
- **Code**: Well-commented throughout

## Next Steps

Now that you have KioskHelp running:

1. **Customize branding** - Update company name, logo
2. **Add service providers** - Populate provider directory
3. **Create resources** - Add helpful guides and articles
4. **Test workflows** - Try all user journeys
5. **Deploy to production** - Follow INSTALL.md for production setup

---

**You're ready to go!** 🚀

The core system is operational. Start exploring and building on this foundation.
