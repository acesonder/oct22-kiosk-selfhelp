# KioskHelp Troubleshooting Guide

**Version**: 1.0.0  
**Quick Reference**: Common issues and solutions

---

## Quick Issue Index

- [Login & Authentication](#login--authentication-issues)
- [Registration Problems](#registration-issues)
- [Password Recovery](#password-recovery-issues)
- [Dashboard & Navigation](#dashboard--navigation-issues)
- [Assessment & Forms](#assessment--form-issues)
- [Messaging](#messaging-issues)
- [File Uploads](#file-upload-issues)
- [Admin Panel](#admin-panel-issues)
- [Database Issues](#database-issues)
- [Performance Problems](#performance-issues)
- [Mobile & Browser](#mobile--browser-issues)

---

## Login & Authentication Issues

### Issue: "Invalid credentials" error

**Symptoms:**
- Error message when trying to login
- Username and password not accepted

**Causes:**
- Incorrect username or password
- Case-sensitive fields
- Account doesn't exist
- Account locked

**Solutions:**

1. **Verify Username Format**
   ```
   Format: FIRSTLAST MMDDYY
   Example: MICBRO050684 (Michael Brown, May 6, 1984)
   ```
   - Check exact spelling
   - Ensure all capitals
   - No spaces

2. **Check Password**
   - Passwords are case-sensitive
   - Use "Show Password" toggle to verify
   - Try copying and pasting if typed multiple times

3. **Use Password Recovery**
   - Click "Forgot Password?"
   - Follow 3-step recovery process
   - Answer security question exactly as registered

4. **Contact Support**
   - If still unable to login
   - Contact staff or administrator
   - Provide username for verification

---

### Issue: Account locked after multiple failed attempts

**Symptoms:**
- "Account locked" message
- Cannot attempt login

**Causes:**
- 5 or more failed login attempts
- Security measure to prevent brute force

**Solutions:**

1. **Wait for Auto-Unlock**
   - Default lockout: 15 minutes
   - Lockout clears automatically
   - Try again after waiting period

2. **Contact Administrator**
   - For immediate unlock
   - Verify identity
   - Admin can manually unlock

3. **Prevention**
   - Use password manager
   - Store credentials securely
   - Use password recovery if unsure

---

### Issue: Session timeout / Logged out unexpectedly

**Symptoms:**
- Redirected to login page
- "Session expired" message
- Lose unsaved work

**Causes:**
- Session timeout (2 hours of inactivity)
- Browser closed
- Network interruption

**Solutions:**

1. **Re-login**
   - Simply log back in
   - Resume where you left off
   - Most pages auto-save drafts

2. **Stay Active**
   - Sessions timeout after 2 hours inactive
   - Any action resets the timer
   - Save work frequently

3. **Browser Settings**
   - Enable cookies
   - Don't use private/incognito mode
   - Allow session storage

---

## Registration Issues

### Issue: "Username already exists"

**Symptoms:**
- Error during registration
- Auto-generated username rejected

**Causes:**
- Another person with same first 3 letters of first/last name and same birthdate
- You already have an account

**Solutions:**

1. **Check Existing Account**
   - Try logging in with that username
   - Use password recovery if forgot password
   - Contact staff to verify

2. **Very Rare Collision**
   - If truly a name/DOB collision
   - Contact administrator
   - Manual username assignment needed

3. **Verify Information**
   - Double-check name spelling
   - Verify birthdate entered correctly
   - Try refreshing the page

---

### Issue: Registration form won't submit

**Symptoms:**
- Submit button doesn't work
- Form validation errors
- Page doesn't progress

**Causes:**
- Missing required fields
- Invalid email format
- Password doesn't meet requirements
- Consent not checked
- JavaScript errors

**Solutions:**

1. **Check Required Fields**
   - First Name (required)
   - Last Name (required)
   - Date of Birth (required)
   - Password (required, 8+ characters)
   - Security Question & Answer (required)
   - Consent checkbox (required)

2. **Verify Email Format**
   ```
   Valid: user@example.com
   Invalid: user@example (missing domain)
   Invalid: @example.com (missing username)
   ```

3. **Password Requirements**
   - Minimum 8 characters
   - Recommended: Mix of uppercase, lowercase, numbers
   - No special characters required but allowed

4. **Browser Issues**
   - Disable browser autofill
   - Clear browser cache
   - Try different browser
   - Check JavaScript is enabled

5. **Network Issues**
   - Check internet connection
   - Try refreshing page
   - Wait a moment and retry

---

## Password Recovery Issues

### Issue: Security question answer not accepted

**Symptoms:**
- "Incorrect answer" error on Step 2
- Cannot proceed to password reset

**Causes:**
- Answer doesn't match registration
- Case sensitivity
- Extra spaces or formatting
- Variations in answer

**Solutions:**

1. **Case Sensitivity**
   - Answers are case-sensitive
   - "doctor" ≠ "Doctor"
   - Try exact match from registration

2. **Formatting**
   ```
   Registered: "Dr. Smith"
   Try: "Dr. Smith" (with period and space)
   Also try: "Dr Smith" (without period)
   Also try: "Doctor Smith" (spelled out)
   ```

3. **Common Variations**
   - Abbreviations vs. full words
   - Punctuation differences
   - Spacing differences

4. **Manual Reset**
   - If can't remember exact answer
   - Contact administrator
   - Provide identity verification
   - Admin can reset password manually

---

### Issue: Username not found in recovery

**Symptoms:**
- "Username not found" on Step 1
- Cannot start recovery process

**Causes:**
- Username entered incorrectly
- Never registered
- Account deleted

**Solutions:**

1. **Verify Username**
   - Check spelling carefully
   - All uppercase
   - Correct format (MICBRO050684)

2. **Check Registration**
   - Verify you completed registration
   - Check confirmation email (if provided)
   - Contact staff to verify account exists

3. **Alternative Options**
   - Try registering as new user
   - Contact staff with your name/DOB
   - Staff can look up your username

---

## Dashboard & Navigation Issues

### Issue: Dashboard won't load or shows blank page

**Symptoms:**
- White/blank screen
- Partial loading
- Error messages

**Causes:**
- Database connection failure
- Session expired
- Network issues
- Browser cache issues

**Solutions:**

1. **Refresh Page**
   - Press F5 or Ctrl+R
   - Hard refresh: Ctrl+Shift+R
   - May resolve temporary glitches

2. **Clear Cache**
   ```
   Chrome: Ctrl+Shift+Delete
   Firefox: Ctrl+Shift+Delete
   Safari: Cmd+Option+E
   ```
   - Select "Cached images and files"
   - Clear and restart browser

3. **Check Session**
   - Log out completely
   - Close browser
   - Reopen and login again

4. **Browser Console**
   - Press F12 to open developer tools
   - Check Console tab for errors
   - Report errors to administrator

---

### Issue: Statistics showing zero or incorrect numbers

**Symptoms:**
- Dashboard stats show 0 for everything
- Numbers don't match actual data
- Stats not updating

**Causes:**
- Database query lag
- Cache not cleared
- New account (no data yet)
- Database sync issue

**Solutions:**

1. **Wait and Refresh**
   - Database may be updating
   - Wait 30 seconds
   - Refresh the page

2. **New Account**
   - If just registered, stats will be 0
   - Complete actions to see updates:
     - Complete assessment → see referrals
     - Schedule appointment → see count
     - Read messages → see unread count

3. **Clear Browser Cache**
   - Stats may be cached
   - Clear cache and reload
   - See solution above for cache clearing

4. **Persistent Issues**
   - If stats consistently wrong
   - Report to administrator
   - May indicate database issue

---

### Issue: Sidebar navigation not working on mobile

**Symptoms:**
- Menu button doesn't open sidebar
- Cannot navigate between pages
- Sidebar off-screen

**Causes:**
- JavaScript not loaded
- Mobile browser compatibility
- Touch events not working

**Solutions:**

1. **Hamburger Menu**
   - Look for ☰ icon (top-left or top-right)
   - Tap to open sidebar
   - Tap again or outside to close

2. **Browser Compatibility**
   - Use modern mobile browser
   - Chrome Mobile (recommended)
   - Safari iOS (recommended)
   - Update browser to latest version

3. **Orientation**
   - Try landscape mode
   - Better visibility on some devices

4. **Direct URLs**
   - Navigate directly using URLs:
     - `/client/dashboard.php`
     - `/client/assessment.php`
     - `/client/referrals.php`

---

## Assessment & Form Issues

### Issue: Cannot submit assessment

**Symptoms:**
- Submit button doesn't work
- Validation errors
- Form resets or doesn't save

**Causes:**
- Missing required answers
- Invalid responses
- Session timeout
- Network issues

**Solutions:**

1. **Check Required Questions**
   - All questions with * are required
   - Scroll through entire form
   - Look for red validation messages

2. **Save as Draft**
   - If available, save progress
   - Complete later
   - Prevents data loss

3. **Screenshot Progress**
   - Take screenshots of completed sections
   - Reference if form resets
   - Can manually re-enter if needed

4. **Network Check**
   - Ensure stable connection
   - Don't submit on unstable WiFi
   - Save frequently

5. **Session Active**
   - Don't let session timeout
   - Keep another tab open and active
   - Refresh periodically while filling

---

### Issue: Dropdown menus not working

**Symptoms:**
- Cannot select from dropdown
- Dropdown doesn't open
- Selected value doesn't save

**Causes:**
- JavaScript error
- Browser compatibility
- Touch event issue (mobile)

**Solutions:**

1. **Desktop:**
   - Click directly on dropdown
   - Use arrow keys to navigate
   - Press Enter to select

2. **Mobile:**
   - Tap dropdown to open
   - Scroll to select option
   - Tap outside to close

3. **Browser Issues:**
   - Try different browser
   - Update current browser
   - Clear cache and cookies

---

## Messaging Issues

### Issue: Messages not sending

**Symptoms:**
- Message appears to send but doesn't
- "Error sending message"
- Recipient doesn't receive

**Causes:**
- Network interruption
- Session expired
- Invalid recipient
- Attachment too large

**Solutions:**

1. **Check Connection**
   - Verify internet connection
   - Try again after a moment
   - Check if dashboard still loads

2. **Verify Recipient**
   - Ensure recipient selected correctly
   - Check recipient is active user
   - Try sending to different recipient

3. **Attachment Size**
   - Maximum file size: 5MB
   - Compress large files
   - Split into multiple messages
   - Remove unnecessary attachments

4. **Re-login**
   - If session expired
   - Log out and back in
   - Compose message again

---

### Issue: Not receiving notifications

**Symptoms:**
- No email notifications
- Missing message alerts
- No appointment reminders

**Causes:**
- Email not provided
- Notifications disabled
- Email in spam folder
- Email system not configured

**Solutions:**

1. **Check Email Address**
   - Go to Profile
   - Verify email is correct
   - Update if necessary

2. **Spam Folder**
   - Check junk/spam folder
   - Add kioskhelp email to contacts
   - Mark as "Not Spam"
   - Whitelist domain

3. **Notification Preferences**
   - Check Profile > Preferences
   - Enable desired notifications
   - Save changes

4. **Email Configuration**
   - If admin: Check SMTP settings
   - Test email delivery
   - Configure email service

---

## File Upload Issues

### Issue: Cannot upload files

**Symptoms:**
- Upload button doesn't work
- "Upload failed" error
- File doesn't attach

**Causes:**
- File too large (>5MB)
- Invalid file type
- Network issues
- Browser limitations

**Solutions:**

1. **Check File Size**
   ```
   Maximum: 5MB (5,242,880 bytes)
   ```
   - Check file properties
   - Compress large files
   - Use online compression tools
   - Split large documents

2. **Allowed File Types**
   ```
   Documents: PDF, DOC, DOCX
   Images: JPG, JPEG, PNG
   ```
   - Convert unsupported types
   - Use online converters
   - Save in compatible format

3. **File Name Issues**
   - Remove special characters
   - Keep name simple
   - Example: `resume.pdf` instead of `My Resume (2025)!.pdf`

4. **Compression Tools**
   - For images: TinyPNG, Compressor.io
   - For PDFs: SmallPDF, iLovePDF
   - ZIP multiple files if needed

---

## Admin Panel Issues

### Issue: Admin code not working

**Symptoms:**
- "Invalid admin code" error
- Cannot access admin panel

**Causes:**
- Wrong code entered
- Code changed
- Typo in entry

**Solutions:**

1. **Default Code**
   ```
   Default: 079777
   ```
   - Try default code
   - Check for typos
   - Code is numeric only

2. **Code Changed**
   - Contact system administrator
   - May have been changed for security
   - Request new code

3. **Security**
   - Failed attempts are logged
   - May trigger lockout
   - Wait before retrying

---

### Issue: Database initialization fails

**Symptoms:**
- "Initialization failed" error
- Database tables not created
- Error messages

**Causes:**
- Database connection failure
- Permission issues
- Database already exists
- Syntax errors in schema

**Solutions:**

1. **Check Connection**
   - Use "Test Connection" button
   - Verify database credentials
   - Ensure MySQL is running

2. **Database Permissions**
   - User needs CREATE, DROP permissions
   - GRANT ALL PRIVILEGES needed
   - Contact database administrator

3. **Existing Database**
   - Initialization drops existing tables
   - Backup first if data exists
   - Confirm overwrite

4. **Manual Setup**
   ```bash
   mysql -u username -p database_name < database/schema.sql
   ```

---

### Issue: Backup creation fails

**Symptoms:**
- "Backup failed" error
- Backup file not created
- Download doesn't start

**Causes:**
- Write permission issues
- Disk space full
- Database too large
- Timeout

**Solutions:**

1. **Check Permissions**
   ```bash
   chmod 755 backup/
   chown www-data:www-data backup/
   ```

2. **Disk Space**
   - Check available disk space
   - Clean old backups
   - Free up space

3. **Manual Backup**
   ```bash
   mysqldump -u username -p database_name > backup.sql
   gzip backup.sql
   ```

4. **Large Databases**
   - May timeout on large databases
   - Use command line instead
   - Schedule during off-hours

---

## Database Issues

### Issue: "Database connection failed"

**Symptoms:**
- Error on most pages
- "Database connection failed" message
- 500 Internal Server Error

**Causes:**
- MySQL not running
- Wrong credentials
- Host not accessible
- Connection limits reached

**Solutions:**

1. **Check MySQL Service**
   ```bash
   # Ubuntu/Debian
   sudo service mysql status
   sudo service mysql start
   
   # CentOS/RHEL
   sudo systemctl status mysqld
   sudo systemctl start mysqld
   ```

2. **Verify Credentials**
   - Check `config/database.php`
   - Verify host, username, password
   - Test with command line:
     ```bash
     mysql -h host -u username -p
     ```

3. **Connection Limits**
   - Check MySQL max connections
   - Monitor active connections
   - Increase limit if needed:
     ```sql
     SET GLOBAL max_connections = 200;
     ```

4. **Firewall**
   - Check if port 3306 is open
   - Verify firewall rules
   - Allow connection from web server

---

### Issue: Slow database queries

**Symptoms:**
- Pages load slowly
- Timeout errors
- High server load

**Causes:**
- Missing indexes
- Large datasets
- Inefficient queries
- Server resources

**Solutions:**

1. **Check Database Size**
   - View statistics in admin panel
   - Identify largest tables
   - Archive old data

2. **Optimize Tables**
   ```sql
   OPTIMIZE TABLE table_name;
   ```

3. **Add Indexes**
   - Ensure proper indexing
   - Check schema for indexes
   - Add where missing

4. **Server Resources**
   - Monitor CPU/RAM usage
   - Upgrade server if needed
   - Optimize MySQL configuration

---

## Performance Issues

### Issue: Slow page loading

**Symptoms:**
- Pages take long to load
- Timeouts
- Unresponsive interface

**Causes:**
- Network latency
- Server overload
- Large images
- Database queries
- Browser issues

**Solutions:**

1. **Network**
   - Check internet speed
   - Use wired connection
   - Avoid peak hours

2. **Browser Cache**
   - Allow browser to cache
   - Don't clear cache constantly
   - Speeds up repeat visits

3. **Image Optimization**
   - If admin: Optimize images
   - Compress before upload
   - Use appropriate sizes

4. **Server Resources**
   - If admin: Monitor server
   - Check PHP/MySQL resources
   - Scale if needed

---

## Mobile & Browser Issues

### Issue: Site doesn't display correctly on mobile

**Symptoms:**
- Layout broken
- Elements off-screen
- Cannot interact with buttons
- Text too small

**Causes:**
- Desktop mode enabled
- Old browser version
- Responsive design issue
- Viewport settings

**Solutions:**

1. **Request Mobile Site**
   - Ensure not in "Desktop Mode"
   - Browser settings → Request mobile site
   - Refresh page

2. **Update Browser**
   - Use latest version
   - Chrome Mobile or Safari iOS recommended
   - Update from app store

3. **Clear Cache**
   - Mobile browser cache
   - Site data
   - Cookies

4. **Orientation**
   - Try rotating device
   - Some forms better in landscape
   - Adapt as needed

5. **Zoom**
   - If text too small, pinch to zoom
   - Or adjust browser text size
   - Settings → Accessibility → Text Size

---

### Issue: Browser compatibility problems

**Symptoms:**
- Features don't work
- Styling broken
- JavaScript errors

**Causes:**
- Unsupported browser
- Old browser version
- JavaScript disabled
- Privacy extensions

**Supported Browsers:**
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ❌ Internet Explorer (not supported)

**Solutions:**

1. **Update Browser**
   - Use latest version
   - Enable auto-updates
   - Download from official site

2. **Enable JavaScript**
   - Required for KioskHelp
   - Check browser settings
   - Enable for kioskhelp domain

3. **Disable Conflicting Extensions**
   - Ad blockers may interfere
   - Privacy extensions may block features
   - Try incognito/private mode
   - Whitelist KioskHelp

4. **Try Different Browser**
   - Chrome recommended
   - Download and test
   - Report issue if persists

---

## Emergency Procedures

### Critical Issues

**If you cannot access your account and it's urgent:**
1. Contact staff directly (phone/in-person)
2. Explain the issue
3. Provide identification
4. Staff can assist manually or reset account

**If you're in crisis:**
1. **Don't wait for system access**
2. Call 988 (Suicide & Crisis Lifeline)
3. Call 911 if immediate danger
4. Contact local crisis center
5. System access can be resolved later

**If system is completely down:**
1. Contact staff via phone
2. Visit service center in person
3. Use backup paper forms if needed
4. Administrator will be notified

---

## Reporting Bugs

**How to Report:**

1. **Document the Issue**
   - What you were trying to do
   - What happened instead
   - Error messages (take screenshot)
   - When it occurred

2. **Gather Information**
   - Browser and version
   - Operating system
   - Username (for account-specific issues)
   - Steps to reproduce

3. **Contact Method**
   - Report to staff
   - Email administrator
   - Use feedback form (if available)

4. **Include Details**
   ```
   Subject: [BUG] Brief description
   
   Description: Detailed explanation
   
   Steps to Reproduce:
   1. Login to client portal
   2. Navigate to Assessment
   3. Fill out form
   4. Click Submit
   
   Expected: Form submits successfully
   Actual: Error message appears
   
   Error: "Database connection failed"
   
   Browser: Chrome 118.0
   OS: Windows 11
   Time: 2025-10-22 14:30
   ```

---

## Getting Additional Help

### Support Channels

**Staff Support**
- Best for: Account issues, service questions
- Contact: Through messaging system or in person

**Technical Support**
- Best for: Login issues, bugs, technical problems
- Contact: Administrator or support email

**Administrator**
- Best for: System issues, data requests, configuration
- Contact: Via staff or directly if you have contact info

### Self-Help Resources

- **WELCOME_GUIDE.md** - Full user manual
- **README.md** - System overview
- **FAQ Section** - Common questions
- **This Document** - Troubleshooting

### Training

- Ask staff about training sessions
- Request one-on-one assistance
- Video tutorials (if available)
- Practice in test environment

---

## Prevention Tips

### Best Practices

✅ **Save your username** - Write it down securely  
✅ **Remember security question** - Choose answer you won't forget  
✅ **Use strong password** - But one you can remember  
✅ **Keep email updated** - For notifications and recovery  
✅ **Save work frequently** - Don't lose progress  
✅ **Clear cache periodically** - Prevents old data issues  
✅ **Update browser** - Ensures compatibility  
✅ **Check spam folder** - Don't miss notifications  
✅ **Report issues early** - Easier to fix  
✅ **Ask for help** - Don't struggle alone

---

**Document Version**: 1.0.0  
**Last Updated**: October 23, 2025  
**For Latest Version**: See WELCOME_GUIDE.md

---

*Remember: Most issues have simple solutions. Don't hesitate to ask for help!*
