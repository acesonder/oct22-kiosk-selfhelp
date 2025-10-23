# KioskHelp - Features Configuration Guide

**Version**: 1.0.0  
**For**: System Administrators  
**Purpose**: Enable, disable, and configure KioskHelp features

---

## Table of Contents

1. [Feature Overview](#feature-overview)
2. [Core Features](#core-features)
3. [Optional Features](#optional-features)
4. [Enabling/Disabling Features](#enablingdisabling-features)
5. [Feature-Specific Configuration](#feature-specific-configuration)
6. [Best Practices](#best-practices)
7. [Troubleshooting Features](#troubleshooting-features)

---

## Feature Overview

### Feature Categories

KioskHelp features are organized into three categories:

#### 1. Core Features (Always On)
- User authentication
- Client registration
- Dashboard
- Database management

#### 2. Configurable Features (Can be toggled)
- Client self-registration
- Assessments
- Automatic referrals
- Messaging
- Appointments
- Email notifications
- SMS notifications

#### 3. Optional Features (Require setup)
- Theme customization
- Email service
- SMS service
- File uploads
- Backup automation

---

## Core Features

### User Authentication

**Status**: Always enabled  
**Purpose**: Secure access to the system

**Configuration Options**:
- Session timeout (default: 2 hours)
- Password requirements
- Max login attempts (default: 5)
- Lockout duration (default: 15 minutes)

**How to Configure**:
```php
// config/app.php
'session_lifetime' => 7200, // seconds
'password_min_length' => 8,
'max_login_attempts' => 5,
'lockout_duration' => 900, // seconds
```

**Best Practices**:
- ✅ Use strong password requirements in production
- ✅ Enable session timeout for security
- ✅ Monitor failed login attempts
- ✅ Consider adding two-factor authentication

---

### Client Registration

**Status**: Can be disabled  
**Purpose**: Allow new clients to self-register

**Configuration Options**:
```php
// config/app.php
'enable_registration' => true, // or false to disable
'require_email_verification' => false, // email verification
```

**When to Disable**:
- During maintenance
- When at capacity
- For referral-only intake
- During system updates

**How to Disable**:
1. Login to admin panel
2. Go to System Configuration
3. Toggle "Enable Registration" to OFF
4. Save changes

**Effect When Disabled**:
- Registration page shows "Registration currently closed"
- Link to contact staff for manual registration
- Existing users can still login

**Re-enabling**:
1. Follow same steps
2. Toggle to ON
3. Test registration flow

---

### Dashboard

**Status**: Always enabled  
**Purpose**: Central hub for all users

**Configuration Options**:
- Which statistics to display
- Quick action links
- Help resources
- Layout preferences

**Customization**:
- Edit dashboard layouts in respective portal files
- Modify statistics queries in backend
- Customize quick actions

---

## Optional Features

### Assessment System

**Status**: Can be disabled  
**Purpose**: Comprehensive needs evaluation

**Configuration**:
```php
// config/app.php
'enable_assessments' => true,
'required_domains' => ['housing', 'food', 'healthcare'], // required sections
'optional_domains' => ['employment', 'legal', 'transportation'], // optional
```

**When to Disable**:
- Using external assessment tools
- Manual intake process only
- Simplified registration needed

**How to Disable**:
1. Admin panel → System Configuration
2. Toggle "Enable Assessments" to OFF
3. Dashboard will hide assessment links

**Impact**:
- No automatic referrals
- Manual referral creation required
- Clients skip assessment step

---

### Automatic Referrals

**Status**: Can be disabled  
**Purpose**: Match clients with providers automatically

**Configuration**:
```php
// config/app.php
'enable_auto_referrals' => true,
'referral_matching_algorithm' => 'priority', // or 'proximity', 'availability'
'max_auto_referrals' => 5, // per assessment
```

**Matching Algorithms**:
- **Priority**: Matches highest priority needs first
- **Proximity**: Matches geographically closest providers
- **Availability**: Matches providers with most capacity

**When to Disable**:
- All referrals manually created by staff
- Testing manual referral process
- Provider database not complete

**Manual Referral Mode**:
- Staff create all referrals
- No automatic matching
- More control over assignments

---

### Messaging System

**Status**: Can be disabled  
**Purpose**: Secure communication between users

**Configuration**:
```php
// config/app.php
'enable_messaging' => true,
'allow_attachments' => true,
'max_attachment_size' => 5242880, // 5MB in bytes
'allowed_recipients' => ['staff', 'providers'], // who clients can message
```

**Features**:
- One-to-one messaging
- File attachments
- Read receipts
- Message threading

**When to Disable**:
- Using external communication system
- Privacy concerns require alternative
- Reduced feature set needed

**Alternatives**:
- Email communication
- Phone-only contact
- In-person meetings

---

### Appointment Scheduling

**Status**: Can be disabled  
**Purpose**: Schedule meetings between clients and providers

**Configuration**:
```php
// config/app.php
'enable_appointments' => true,
'appointment_reminder_hours' => 24, // hours before appointment
'allow_client_scheduling' => true, // or staff-only scheduling
```

**Reminder Options**:
- Email reminders (requires email service)
- SMS reminders (requires SMS service)
- Dashboard notifications (always on)

**When to Disable**:
- Manual scheduling only
- Using external calendar system
- Simplified workflow needed

---

### Email Notifications

**Status**: Requires setup  
**Purpose**: Send automated email notifications

**Configuration**:
```php
// config/email.php (create this file)
return [
    'smtp_host' => 'smtp.example.com',
    'smtp_port' => 587,
    'smtp_username' => 'your-email@example.com',
    'smtp_password' => 'your-password',
    'smtp_encryption' => 'tls', // or 'ssl'
    'from_email' => 'noreply@kioskhelp.com',
    'from_name' => 'KioskHelp System',
];
```

**Email Types**:
- Registration confirmation
- Password reset
- Appointment reminders
- Referral notifications
- Message alerts

**Testing Email**:
1. Configure SMTP settings
2. Admin panel → Email Configuration
3. Click "Test Email"
4. Check your inbox

**Troubleshooting**:
- Verify SMTP credentials
- Check firewall for port 587/465
- Ensure TLS/SSL settings correct
- Check spam folder for test emails

---

### SMS Notifications

**Status**: Requires third-party service  
**Purpose**: Send text message notifications

**Configuration**:
```php
// config/sms.php (create this file)
return [
    'provider' => 'twilio', // or 'nexmo', 'aws-sns'
    'twilio_sid' => 'your-account-sid',
    'twilio_token' => 'your-auth-token',
    'from_number' => '+1234567890',
];
```

**Supported Providers**:
- Twilio (recommended)
- Nexmo/Vonage
- AWS SNS
- Custom integration

**Setup Steps**:
1. Create account with SMS provider
2. Get API credentials
3. Configure in KioskHelp
4. Test SMS delivery
5. Enable in System Configuration

**Cost Considerations**:
- SMS services typically charge per message
- Budget accordingly
- Consider email as free alternative

---

### File Uploads

**Status**: Configurable  
**Purpose**: Allow users to upload documents

**Configuration**:
```php
// config/app.php
'upload_max_size' => 5242880, // 5MB
'allowed_file_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
'upload_path' => '/path/to/uploads',
```

**Security Considerations**:
- ✅ Validate file types
- ✅ Scan for malware (if possible)
- ✅ Limit file sizes
- ✅ Store outside web root
- ✅ Restrict access

**File Type Limits**:
```php
'allowed_file_types' => [
    'documents' => ['pdf', 'doc', 'docx'],
    'images' => ['jpg', 'jpeg', 'png'],
    'max_size' => 5 * 1024 * 1024, // 5MB
];
```

**Disabling Uploads**:
1. Remove upload forms from UI
2. Or set `'upload_max_size' => 0`
3. Or set `'allowed_file_types' => []`

---

### Theme Customization

**Status**: Always available  
**Purpose**: Customize appearance and branding

**Customizable Elements**:

#### Colors
```css
/* assets/css/main.css */
:root {
    --primary-color: #2563eb; /* main brand color */
    --secondary-color: #7c3aed; /* accent color */
    --success-color: #059669;
    --warning-color: #f59e0b;
    --danger-color: #dc2626;
}
```

#### Logo
- Upload via Admin Panel → Theme Customization
- Recommended size: 200x50px
- Format: PNG with transparency

#### Welcome Message
```php
// config/app.php
'welcome_message' => 'Welcome to KioskHelp - We\'re here to help!',
'organization_name' => 'Your Organization Name',
```

#### Footer
- Edit in respective portal files
- Common footer: `includes/footer.php` (if exists)
- Customize contact info, links, resources

**Creating Theme Presets**:

1. **Light Theme** (default)
   - Light backgrounds
   - Dark text
   - High contrast

2. **Dark Theme**
   ```css
   :root {
       --bg-primary: #1f2937;
       --bg-secondary: #111827;
       --text-primary: #f9fafb;
   }
   ```

3. **High Contrast** (accessibility)
   ```css
   :root {
       --bg-primary: #ffffff;
       --text-primary: #000000;
       --primary-color: #0000ff;
   }
   ```

---

## Enabling/Disabling Features

### Method 1: Configuration File

**File**: `config/app.php`

```php
return [
    // Features
    'enable_registration' => true,
    'enable_assessments' => true,
    'enable_auto_referrals' => true,
    'enable_messaging' => true,
    'enable_appointments' => true,
    'enable_email_notifications' => true,
    'enable_sms_notifications' => false,
];
```

**Pros**:
- Permanent configuration
- Version controlled
- Fast (no database lookup)

**Cons**:
- Requires file editing
- Needs server access
- May require cache clear

---

### Method 2: Database Configuration

**Table**: `system_configuration`

**Via Admin Panel**:
1. Login to admin panel
2. Go to System Configuration
3. Toggle features on/off
4. Save changes

**Via SQL**:
```sql
UPDATE system_configuration 
SET value = 'true' 
WHERE key = 'enable_registration';
```

**Pros**:
- Easy to toggle via UI
- No file editing needed
- Immediate effect

**Cons**:
- Database dependency
- Slightly slower
- Need backup of config

---

### Method 3: Feature Flags

**Advanced**: Implement feature flags for A/B testing

```php
// includes/FeatureFlag.php
class FeatureFlag {
    public static function isEnabled($feature, $user = null) {
        // Check if feature enabled
        // Optionally check user-specific flags
        // Support percentage rollout
    }
}

// Usage
if (FeatureFlag::isEnabled('new_dashboard')) {
    // Show new dashboard
} else {
    // Show old dashboard
}
```

---

## Feature-Specific Configuration

### Assessment Configuration

**Domains**:
```php
'assessment_domains' => [
    'housing' => [
        'label' => 'Housing',
        'required' => true,
        'questions' => 5,
    ],
    'food' => [
        'label' => 'Food Security',
        'required' => true,
        'questions' => 3,
    ],
    // ... more domains
],
```

**Scoring**:
```php
'scoring_method' => 'weighted', // or 'simple', 'complex'
'priority_threshold' => 8, // 1-10 scale
```

---

### Referral Configuration

**Matching Rules**:
```php
'referral_rules' => [
    'max_distance' => 25, // miles
    'prefer_same_language' => true,
    'consider_wait_times' => true,
    'priority_weighting' => 0.7, // 70% weight on priority
    'proximity_weighting' => 0.3, // 30% weight on distance
],
```

**Auto-Referral Limits**:
```php
'referral_limits' => [
    'max_per_assessment' => 5,
    'max_per_domain' => 2,
    'max_high_priority' => 10, // total active high-priority
],
```

---

### Appointment Configuration

**Scheduling Rules**:
```php
'appointment_rules' => [
    'min_notice_hours' => 24, // minimum notice for scheduling
    'max_days_ahead' => 90, // how far ahead can schedule
    'slot_duration' => 60, // minutes
    'buffer_time' => 15, // minutes between appointments
],
```

**Working Hours**:
```php
'working_hours' => [
    'monday' => ['09:00', '17:00'],
    'tuesday' => ['09:00', '17:00'],
    // ... etc
    'saturday' => ['10:00', '14:00'],
    'sunday' => null, // closed
],
```

---

### Notification Configuration

**Triggers**:
```php
'notifications' => [
    'new_referral' => [
        'email' => true,
        'sms' => false,
        'dashboard' => true,
    ],
    'appointment_reminder' => [
        'email' => true,
        'sms' => true,
        'dashboard' => true,
        'hours_before' => 24,
    ],
    'new_message' => [
        'email' => true,
        'sms' => false,
        'dashboard' => true,
    ],
],
```

---

## Best Practices

### Feature Rollout

**1. Test in Staging First**
- Enable feature in test environment
- Test thoroughly with sample data
- Get user feedback
- Fix any issues
- Then roll out to production

**2. Gradual Rollout**
- Start with small percentage of users
- Monitor for issues
- Gradually increase
- Full rollout when stable

**3. Communication**
- Announce new features to users
- Provide training/documentation
- Gather feedback
- Iterate based on feedback

---

### Feature Deprecation

**If Disabling a Feature**:

1. **Announce in Advance**
   - 30 days notice recommended
   - Explain reason
   - Provide alternatives

2. **Export Data**
   - Allow users to export data
   - Backup feature data
   - Provide data in usable format

3. **Graceful Disable**
   - Read-only mode first
   - Then full disable
   - Maintain data access if needed

4. **Document Changes**
   - Update documentation
   - Update training materials
   - FAQ entries

---

### Performance Optimization

**Feature-Specific Optimizations**:

**Assessments**:
- Cache assessment templates
- Index assessment responses
- Paginate long forms

**Referrals**:
- Cache provider data
- Index on status and date
- Archive old referrals

**Messaging**:
- Implement pagination
- Archive old messages
- Compress attachments

**Appointments**:
- Index on date and user
- Archive past appointments
- Optimize calendar queries

---

## Troubleshooting Features

### Feature Won't Enable

**Check**:
1. Configuration file syntax
2. Database permissions
3. Required dependencies
4. File permissions
5. Cache needs clearing

**Solution**:
```bash
# Clear cache
rm -rf cache/*

# Check PHP syntax
php -l config/app.php

# Verify database
mysql -u user -p database -e "SELECT * FROM system_configuration;"
```

---

### Feature Enabled But Not Working

**Check**:
1. Frontend references feature flag
2. Backend implements feature
3. Database tables exist
4. Permissions are set
5. No JavaScript errors

**Debug**:
```php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check feature status
var_dump($config['enable_feature']);

// Check user permissions
var_dump($user->hasPermission('use_feature'));
```

---

### Performance Issues After Enabling Feature

**Investigate**:
1. Check slow query log
2. Monitor server resources
3. Review feature complexity
4. Check for n+1 queries

**Optimize**:
- Add database indexes
- Implement caching
- Optimize queries
- Consider async processing

---

## Feature Roadmap

### Planned Features

**Short Term** (Next 3 months):
- Advanced search for resources
- Bulk messaging
- Calendar integration
- Report generation

**Medium Term** (3-6 months):
- Mobile app
- Two-factor authentication
- Advanced analytics
- API for integrations

**Long Term** (6-12 months):
- Multi-language support
- AI-powered matching
- Predictive analytics
- Voice interface

---

## Feature Request Process

### How to Request New Features

**1. Document the Need**
- What problem does it solve?
- Who benefits?
- How urgent?
- What's the expected impact?

**2. Submit Request**
- Use feature request form
- Or email administrator
- Provide detailed description
- Include use cases

**3. Review Process**
- Administrator reviews
- Stakeholder feedback
- Feasibility assessment
- Priority ranking

**4. Implementation**
- Development
- Testing
- Documentation
- Training
- Rollout

---

## Conclusion

KioskHelp is designed to be flexible and configurable. Features can be enabled or disabled based on organizational needs, capacity, and workflow preferences.

**Key Takeaways**:
- ✅ Start with core features
- ✅ Add optional features as needed
- ✅ Test before enabling in production
- ✅ Monitor feature usage and performance
- ✅ Gather user feedback
- ✅ Iterate and improve

---

**Document Version**: 1.0.0  
**Last Updated**: October 23, 2025  
**For Support**: Contact system administrator

---

*This guide is updated as new features are added and configuration options change.*
