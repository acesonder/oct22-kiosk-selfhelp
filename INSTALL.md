# KioskHelp Installation Guide

This guide will walk you through installing and configuring the KioskHelp system on your server.

## System Requirements

### Server Requirements
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 7.4 or higher (8.0+ recommended)
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Disk Space**: Minimum 500MB free space
- **Memory**: Minimum 256MB PHP memory limit

### Required PHP Extensions
- PDO
- pdo_mysql
- mbstring
- json
- session
- fileinfo
- gd (optional, for image processing)

### Browser Requirements (Client-side)
- Modern browsers: Chrome, Firefox, Safari, Edge (latest versions)
- JavaScript enabled
- Cookies enabled

## Installation Steps

### 1. Download and Extract Files

```bash
# Clone from GitHub
git clone https://github.com/acesonder/oct22-kiosk-selfhelp.git
cd oct22-kiosk-selfhelp

# OR download ZIP and extract
unzip oct22-kiosk-selfhelp.zip
cd oct22-kiosk-selfhelp
```

### 2. Set File Permissions

```bash
# Make directories writable by web server
chmod 755 uploads/ backup/ logs/ cache/ tmp/

# On Linux/Unix systems, you may need:
sudo chown -R www-data:www-data uploads/ backup/ logs/ cache/ tmp/

# Or for Apache:
sudo chown -R apache:apache uploads/ backup/ logs/ cache/ tmp/
```

### 3. Configure Apache (if using Apache)

Create or edit `.htaccess` in the root directory:

```apache
# Enable rewrite engine
RewriteEngine On

# Redirect to index.php if file doesn't exist
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L,QSA]

# Deny access to sensitive files
<FilesMatch "\.(env|json|sql|md|lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Deny access to config directory
<DirectoryMatch "config">
    Order allow,deny
    Deny from all
</DirectoryMatch>

# Deny access to includes directory
<DirectoryMatch "includes">
    Order allow,deny
    Deny from all
</DirectoryMatch>

# Protect database files
<DirectoryMatch "database">
    Order allow,deny
    Deny from all
</DirectoryMatch>

# Enable PHP error display (disable in production)
php_flag display_errors off
php_flag display_startup_errors off
php_value error_reporting 0
php_value error_log logs/php_errors.log
```

### 4. Configure Nginx (if using Nginx)

Add this to your Nginx site configuration:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/kioskhelp;
    index index.php index.html;

    # Logging
    access_log /var/log/nginx/kioskhelp_access.log;
    error_log /var/log/nginx/kioskhelp_error.log;

    # Main location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /(config|includes|database)/ {
        deny all;
    }

    location ~* \.(env|json|sql|md|lock)$ {
        deny all;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

### 5. Create MySQL Database

```bash
# Login to MySQL
mysql -u root -p

# Create database and user
CREATE DATABASE kioskhelp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kioskhelp_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON kioskhelp.* TO 'kioskhelp_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 6. Initialize Database via Admin Panel

1. Navigate to your installation: `http://your-domain.com/admin/login.php`
2. Enter admin code: **079777**
3. Click on **"Database Tools"** in the sidebar
4. Fill in database credentials:
   - **Host**: localhost (or your database server)
   - **Database Name**: kioskhelp
   - **Username**: kioskhelp_user
   - **Password**: your_secure_password
5. Click **"Test Connection"** to verify
6. Click **"Initialize Database"** to create all tables

### 7. Verify Installation

1. **Test the landing page**: Navigate to `http://your-domain.com/`
2. **Test client registration**: Go to `http://your-domain.com/client/register.php`
3. **Test admin panel**: Go to `http://your-domain.com/admin/login.php` (code: 079777)
4. **Check database**: Verify tables were created in phpMyAdmin or MySQL Workbench

## Post-Installation Configuration

### 1. Update Admin Code

For security, change the default admin code:

1. Go to Admin Panel > Settings
2. Change admin code from `079777` to your own secure code
3. Save changes

### 2. Configure Site Settings

1. Go to Admin Panel > Settings
2. Update:
   - Company Name
   - Site Name
   - Company Logo (upload your logo)
   - Email settings (if using notifications)
3. Save changes

### 3. Create Initial Users

#### Create Staff User:
1. Go to Admin Panel > User Management
2. Click "Create New User"
3. Set role to "Staff"
4. Provide user details

#### Create Provider User:
1. Go to Admin Panel > User Management
2. Click "Create New User"
3. Set role to "Provider"
4. Link to service provider profile

### 4. Set Up Service Providers

1. Go to Admin Panel > Service Providers
2. Add or edit the sample providers
3. Configure:
   - Provider name and contact info
   - Services offered
   - Operating hours
   - Capacity

### 5. Add Resources

1. Go to Admin Panel > Resources
2. Add helpful guides and articles
3. Categorize resources
4. Mark featured resources

### 6. Configure Backup Schedule

Set up automatic database backups using cron:

```bash
# Edit crontab
crontab -e

# Add daily backup at 2 AM
0 2 * * * cd /var/www/kioskhelp && php scripts/backup.php >> logs/backup.log 2>&1

# Add weekly backup cleanup (keep last 30 days)
0 3 * * 0 find /var/www/kioskhelp/backup -name "*.sql" -mtime +30 -delete
```

## Security Hardening

### 1. Secure Configuration Files

```bash
chmod 600 config/database.php
chmod 600 config/email.php
```

### 2. Disable Directory Listing

Ensure `.htaccess` includes:
```apache
Options -Indexes
```

### 3. Enable HTTPS

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get SSL certificate
sudo certbot --apache -d your-domain.com
```

### 4. Set PHP Security Settings

In `php.ini`:
```ini
expose_php = Off
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/www/kioskhelp/logs/php_errors.log
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
```

### 5. Implement Rate Limiting

Install and configure fail2ban:

```bash
sudo apt install fail2ban

# Create custom filter for KioskHelp
sudo nano /etc/fail2ban/filter.d/kioskhelp.conf
```

Add:
```ini
[Definition]
failregex = ^<HOST> .* "POST /client/login.php HTTP.*" (401|403)
ignoreregex =
```

## Troubleshooting

### Database Connection Issues

**Problem**: "Database connection failed"

**Solution**:
1. Verify MySQL is running: `sudo systemctl status mysql`
2. Check credentials in Admin Panel > Database Tools
3. Test connection manually:
   ```bash
   mysql -h localhost -u kioskhelp_user -p kioskhelp
   ```
4. Check MySQL error log: `/var/log/mysql/error.log`

### Permission Errors

**Problem**: "Cannot write to directory"

**Solution**:
```bash
# Check current permissions
ls -la uploads/ logs/ backup/

# Fix permissions
sudo chown -R www-data:www-data uploads/ logs/ backup/ cache/ tmp/
sudo chmod 755 uploads/ logs/ backup/ cache/ tmp/
```

### PHP Errors

**Problem**: White screen or PHP errors

**Solution**:
1. Check PHP error log: `tail -f logs/php_errors.log`
2. Verify PHP version: `php -v` (must be 7.4+)
3. Check required extensions: `php -m`
4. Enable error display temporarily in `config/app.php`:
   ```php
   'environment' => 'development'
   ```

### Session Issues

**Problem**: "Not logged in" after login

**Solution**:
1. Check session directory permissions
2. Verify PHP session configuration:
   ```bash
   php -i | grep session.save_path
   ```
3. Clear browser cookies
4. Check `session.cookie_secure` if not using HTTPS

## Maintenance

### Regular Backups

```bash
# Manual backup via admin panel or CLI
cd /var/www/kioskhelp
php scripts/backup.php
```

### Update Application

```bash
# Backup first!
cd /var/www/kioskhelp

# Pull updates
git pull origin main

# Run any migrations
php scripts/migrate.php

# Clear cache
rm -rf cache/*
```

### Monitor Logs

```bash
# Watch error logs
tail -f logs/php_errors.log
tail -f logs/database_errors.log

# Check disk space
df -h

# Check database size
mysql -u root -p -e "SELECT table_schema 'Database', SUM(data_length + index_length) / 1024 / 1024 'Size (MB)' FROM information_schema.TABLES WHERE table_schema='kioskhelp';"
```

## Support

For issues or questions:
- GitHub Issues: https://github.com/acesonder/oct22-kiosk-selfhelp/issues
- Documentation: See README.md
- Email: support@kioskhelp.com

## License

MIT License - See LICENSE file for details

---

**Installation Complete!** 🎉

Your KioskHelp system should now be operational. Test all features thoroughly before going into production use.
