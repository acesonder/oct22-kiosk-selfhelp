# KioskHelp - Frequently Asked Questions (FAQ)

**Version**: 1.0.0  
**Last Updated**: October 23, 2025

---

## Table of Contents

- [General Questions](#general-questions)
- [Account & Registration](#account--registration)
- [Privacy & Security](#privacy--security)
- [Assessment](#assessment)
- [Referrals](#referrals)
- [Appointments](#appointments)
- [Messages](#messages)
- [Self-Help Tools](#self-help-tools)
- [Technical Questions](#technical-questions)
- [For Staff](#for-staff)
- [For Providers](#for-providers)
- [For Administrators](#for-administrators)

---

## General Questions

### What is KioskHelp?

KioskHelp is a comprehensive self-help kiosk system designed to support vulnerable individuals through registration, assessment, referral, and case management services. It connects people who need help with service providers who can help them.

### Is KioskHelp free?

Yes! KioskHelp is completely free for clients seeking services. There are no fees or charges to register, complete assessments, receive referrals, or use any of the tools.

### Who can use KioskHelp?

Anyone seeking support services can register as a client. The system also supports:
- **Service Providers**: Organizations offering services (housing, healthcare, etc.)
- **Staff**: Case managers and outreach workers
- **Administrators**: System managers

### What services does KioskHelp help with?

KioskHelp connects you with services in 8 key areas:
1. Housing (emergency shelter, transitional, permanent)
2. Food Security (food banks, meal programs)
3. Healthcare (primary care, dental, vision, prescriptions)
4. Mental Health (counseling, crisis support, substance abuse treatment)
5. Employment (job training, resume help, interview prep)
6. Legal Aid (free legal consultation and assistance)
7. Transportation (bus passes, ride assistance)
8. Family Services (childcare, family counseling, parenting support)

### Do I need a computer or smartphone?

You can access KioskHelp on:
- Desktop or laptop computer
- Tablet (iPad, Android tablets)
- Smartphone (iPhone, Android)
- Public kiosk terminals (if available at service centers)

The system is fully responsive and works on all screen sizes.

### Can I use KioskHelp in another language?

Currently, KioskHelp is available in English only. Multi-language support may be added in future versions. Contact your local service center for translation assistance if needed.

---

## Account & Registration

### How do I create an account?

1. Go to the KioskHelp website
2. Click "Get Started" or "Register"
3. Fill out the registration form:
   - First and last name
   - Date of birth
   - Email (optional)
   - Phone (optional)
4. Your username will be auto-generated
5. Create a password
6. Choose a security question and answer
7. Accept the consent agreement
8. Submit - you'll be automatically logged in!

### How is my username created?

Your username is automatically generated using:
- First 3 letters of your first name
- First 3 letters of your last name
- Your date of birth (MMDDYY format)

**Example**: Michael Brown, born May 6, 1984 = `MICBRO050684`

This ensures your username is unique and easy to remember.

### Can I choose my own username?

No, usernames are auto-generated for consistency and uniqueness. However, you'll see a preview as you fill out the registration form, so you'll know your username before submitting.

### What if I forget my username?

Your username follows the pattern: First3Last3MMDDYY

If you can't remember it:
- Try reconstructing it from your name and birthdate
- Contact staff - they can look it up for you
- Check any confirmation emails you received

### What if I forget my password?

Use the password recovery process:

1. Click "Forgot Password?" on the login page
2. Enter your username
3. Answer your security question
4. Create a new password
5. You'll be automatically logged in

**Important**: Security answers are case-sensitive and must match exactly what you entered during registration.

### Can I change my password?

Yes! After logging in:
1. Go to your Profile
2. Click "Change Password"
3. Enter current password
4. Enter new password
5. Confirm new password
6. Save changes

### Can I change my email or phone number?

Yes! Go to your Profile and update your contact information. However, you cannot change:
- Your username
- Your date of birth
- Your first/last name (contact admin if legal name change)

### What if my username already exists?

This is very rare (requires same first 3 letters of first/last name AND same birthdate). If this happens:
- Try logging in - you may already have an account
- Use password recovery to access existing account
- Contact an administrator - they can verify and assist

### Can I delete my account?

Yes, but contact an administrator to request account deletion. Some data may be retained for legal compliance (typically 7 years). Active cases may need to be closed before account deletion.

---

## Privacy & Security

### Is my information private and secure?

Yes! KioskHelp takes privacy seriously:
- ✅ GDPR-compliant data handling
- ✅ Encrypted data transmission (HTTPS)
- ✅ Secure password hashing (bcrypt)
- ✅ SQL injection protection
- ✅ XSS protection
- ✅ Session-based authentication
- ✅ Role-based access control

### Who can see my information?

Only authorized users:
- **Your assigned case manager** - Full access to help you
- **Service providers you're referred to** - Only relevant information with your consent
- **System administrators** - For technical support and troubleshooting
- **Nobody else** - Your data is not shared with unauthorized parties

### What data does KioskHelp collect?

Only information necessary to provide services:
- **Personal**: Name, date of birth, contact information
- **Assessment**: Your needs and priorities across service areas
- **Service History**: Referrals, appointments, services received
- **Communications**: Messages with staff and providers
- **Usage**: Login times, features used (for system improvement)

**NOT collected**: 
- Social Security Number (unless specifically needed by a provider)
- Financial information (credit cards, bank accounts)
- Detailed medical records
- Criminal history (unless relevant to services)

### Can I opt out of data sharing?

You can manage privacy preferences in your Profile. However, some data sharing is necessary to receive services:
- Case managers need access to help you
- Providers need information to serve you
- Admins need access for technical support

You can choose:
- Which providers receive your information
- Whether to share assessment details
- Communication preferences

### How long is my data kept?

Data retention follows legal requirements (typically 7 years for compliance). You can request data deletion by contacting an administrator, though some data may need to be retained.

### Are my messages encrypted?

Yes, all communications within KioskHelp are encrypted during transmission. Messages are stored securely and only accessible to authorized parties (sender, recipient, and system admins).

### What if there's a data breach?

In the unlikely event of a data breach:
1. You will be notified immediately
2. Steps taken to secure the system
3. Guidance provided on protective measures
4. Incident reported to authorities as required
5. System security enhanced to prevent recurrence

### Can staff or providers see my password?

No! Passwords are encrypted (hashed) using industry-standard bcrypt. Nobody, including administrators, can see your actual password. This is why password recovery requires security questions instead of password retrieval.

---

## Assessment

### What is an assessment?

An assessment is a comprehensive evaluation of your needs across 8 service areas. It helps identify what support you need most urgently and matches you with appropriate service providers.

### Do I have to complete an assessment?

While not strictly required, completing an assessment is highly recommended:
- Gets you matched with appropriate services
- Prioritizes your most urgent needs
- Creates a baseline for tracking progress
- Helps case managers understand how to help you

Without an assessment, you won't receive automatic referrals.

### How long does an assessment take?

Typically 10-15 minutes. You can:
- Complete it all at once
- Save as draft and finish later
- Take breaks as needed

### Can I update my assessment later?

Yes! You should update your assessment:
- When your needs change significantly
- Every 3-6 months (recommended)
- When starting new goals
- After major life changes

Previous assessments are saved for reference and progress tracking.

### Who sees my assessment results?

- Your assigned case manager (full access)
- Staff creating referrals (relevant sections)
- Service providers you're referred to (only their area)
- System administrators (for technical support)

### What if I don't need help in all areas?

That's perfectly fine! Only complete sections relevant to your needs. You can indicate "No need" or "Low priority" for areas where you don't need help.

### Can I skip questions?

Some questions are required (marked with *), but many are optional. However, providing more detail helps:
- Better service matching
- More accurate prioritization
- Faster connection with providers
- More effective case management

### What does "priority" mean?

Priority indicates urgency:
- **High**: Urgent, immediate need (within days)
- **Medium**: Important, needed soon (within weeks)
- **Low**: Would be helpful but not urgent (within months)

High-priority needs get matched first and receive expedited attention.

---

## Referrals

### What is a referral?

A referral is a connection between you and a service provider who can help with a specific need. Based on your assessment, the system automatically matches you with appropriate providers.

### How do I get referrals?

1. Complete your assessment
2. Submit the assessment
3. System automatically generates referrals
4. Referrals appear in your "Referrals" section
5. Providers are notified
6. You're notified when providers respond

### How long until I get referrals?

Referrals are generated immediately after submitting your assessment. However:
- Providers typically respond within 48 hours
- High-priority referrals are expedited
- Some services have waitlists
- Staff may create additional manual referrals

### What if I don't like a referral?

You can:
- Decline any referral
- Request alternatives from your case manager
- Provide feedback on why it's not a good fit
- Ask for a different provider

You're never required to accept a referral.

### Can I request a specific provider?

Yes! Contact your case manager and ask for a specific provider. They can:
- Create a manual referral
- Check provider availability
- Coordinate the connection

### What if a provider declines my referral?

If a provider declines:
- The system suggests alternative providers
- Your case manager is notified
- You'll be matched with another provider
- Decline reasons help improve future matching

Common decline reasons: at capacity, outside service area, different specialization needed

### How do I know the status of my referrals?

Check your "Referrals" page to see:
- **Pending**: Waiting for provider response
- **Accepted**: Provider agreed, schedule appointment
- **In Progress**: Currently receiving services
- **Completed**: Service delivered successfully
- **Declined**: Provider unable to serve, alternative suggested

### Can I have multiple referrals at once?

Yes! You can have referrals to multiple providers for different services (e.g., housing + healthcare + employment). You can also have multiple referrals for the same service type if needed.

---

## Appointments

### How do I schedule an appointment?

1. Go to "My Appointments"
2. Click "Schedule New Appointment"
3. Select a provider (from your accepted referrals)
4. Choose available time slot
5. Add any special notes
6. Confirm appointment

Alternatively, providers or staff may schedule appointments for you.

### Can I reschedule an appointment?

Yes! 
1. Go to "My Appointments"
2. Click on the appointment
3. Click "Reschedule"
4. Choose new time
5. Confirm

**Important**: Try to reschedule with at least 24 hours notice to be courteous to providers.

### How do I cancel an appointment?

1. Go to "My Appointments"
2. Click on the appointment
3. Click "Cancel"
4. Optionally provide a reason
5. Confirm cancellation

**Note**: Repeated no-shows or last-minute cancellations may affect future referrals.

### What if I miss an appointment?

If you miss an appointment:
1. Contact the provider as soon as possible
2. Apologize and explain if comfortable
3. Reschedule at their earliest convenience
4. Set reminders for future appointments

### Will I get appointment reminders?

Yes! If you provided email or phone:
- Email reminder 24 hours before
- SMS reminder 24 hours before (if configured)
- Dashboard notification
- You can customize reminder settings in your profile

### Can appointments be virtual?

Many providers offer virtual appointments (phone or video). Check the appointment details for:
- Meeting link (for video)
- Phone number (for phone)
- In-person address (if on-site)

### What if the appointment time doesn't work for me?

Contact the provider or your case manager to find a better time. Most providers are flexible and want to accommodate your schedule.

---

## Messages

### How do I send a message?

1. Go to "Messages"
2. Click "Compose New Message"
3. Select recipient (staff or provider)
4. Enter subject
5. Write your message
6. Optionally attach files
7. Click "Send"

### Who can I message?

You can send messages to:
- Your assigned case manager
- Service providers you're referred to
- Other staff members working with you

You cannot message other clients (for privacy).

### How do I know if I have new messages?

- Dashboard shows unread message count
- Email notification (if enabled)
- SMS notification (if configured and enabled)
- Red badge on Messages menu item

### Can I attach files to messages?

Yes! You can attach:
- Documents (PDF, DOC, DOCX)
- Images (JPG, JPEG, PNG)
- Maximum 5MB per file

Use attachments for:
- Sharing documents with providers
- Sending copies of paperwork
- Providing requested information

### Are messages private?

Yes, messages are:
- Encrypted during transmission
- Only visible to sender and recipient
- Viewable by admins for technical support only
- Not shared with unauthorized parties

### How quickly will I get a response?

Response times vary:
- **Staff**: Usually within 24-48 hours
- **Providers**: Within 48-72 hours
- **Urgent matters**: Contact directly by phone
- **Emergencies**: Call 911 or 988

### Can I delete messages?

You can delete messages from your view, but:
- They remain in the system for records
- They're still visible to the recipient
- Admins can access for auditing/compliance

---

## Self-Help Tools

### What are self-help tools?

Self-help tools are interactive features that help you manage different aspects of your journey toward stability. They include:
- Budget Planner
- Housing Search Checklist
- Wellness Tracker
- Goal Planner
- Job Search Organizer
- Crisis Plan Creator

### Are the tools required?

No, they're optional. However, they're highly recommended because they:
- Help you stay organized
- Track your progress
- Identify patterns
- Build important skills
- Empower self-sufficiency

### Can I share my tool data with my case manager?

Yes! Sharing tool data helps your case manager:
- Understand your progress
- Identify challenges
- Celebrate successes
- Provide targeted support

You control what you share and when.

### How often should I use the tools?

Recommended frequency:
- **Budget Planner**: Weekly (track spending)
- **Wellness Tracker**: Daily (log mood and sleep)
- **Housing Checklist**: As needed (when searching)
- **Goal Planner**: Weekly (review progress)
- **Job Organizer**: Daily (when job searching)
- **Crisis Plan**: Create once, review monthly

### Is my tool data private?

Yes! Tool data is:
- Private by default
- Only visible to you
- Shared only with your permission
- Not required to share

### Can I access tools on my phone?

Yes! All tools work on:
- Desktop computers
- Tablets
- Smartphones

The interface adapts to your screen size.

---

## Technical Questions

### What browsers are supported?

Supported browsers:
- ✅ Chrome (latest version)
- ✅ Firefox (latest version)
- ✅ Safari (latest version)
- ✅ Edge (latest version)
- ❌ Internet Explorer (not supported)

Always use the latest version for best performance and security.

### Do I need to download anything?

No! KioskHelp is web-based. Simply:
1. Open your web browser
2. Go to the KioskHelp website
3. Start using immediately

No downloads, installations, or apps required.

### Does KioskHelp have a mobile app?

Currently, KioskHelp is web-based and fully responsive. You can access it through your mobile browser. A dedicated mobile app may be developed in future versions.

### What if the site is slow or not loading?

Try these steps:
1. Refresh the page (F5 or Ctrl+R)
2. Clear browser cache and cookies
3. Check your internet connection
4. Try a different browser
5. Contact support if problem persists

See TROUBLESHOOTING.md for more details.

### Can I use KioskHelp offline?

No, KioskHelp requires an internet connection to:
- Access your account
- Submit assessments
- Send messages
- View referrals
- Use tools

### How long does my session last?

Sessions last 2 hours of inactivity. Any action (clicking, scrolling, typing) resets the timer. After 2 hours inactive, you'll be logged out for security.

### What if I get an error message?

1. Take a screenshot of the error
2. Note what you were doing
3. Try refreshing the page
4. If it persists, report to staff or admin
5. Include error details when reporting

See TROUBLESHOOTING.md for common errors and solutions.

---

## For Staff

### How do I get a staff account?

Contact your system administrator. They will:
1. Create your staff account
2. Provide username and temporary password
3. Give access to staff portal
4. Assign appropriate permissions

### How do I view my caseload?

1. Login to staff portal
2. Dashboard shows your assigned clients
3. Click on any client to view details
4. See assessments, referrals, appointments, notes

### How do I create a referral for a client?

1. Select the client
2. Click "Create Referral"
3. Choose service type
4. Review system-suggested providers
5. Select provider
6. Set priority level
7. Add any notes
8. Submit referral

### How do I add case notes?

1. Open client's case
2. Click "Add Note"
3. Select note type (phone call, meeting, email)
4. Enter subject and details
5. Indicate if follow-up needed
6. Save note

All notes are timestamped and attributed to you.

### Can I message multiple clients at once?

Currently, messages are one-to-one. For bulk communication, contact administrator about email notification features.

### How do I close a case?

1. Open the client's case
2. Ensure all goals are met or transferred
3. Click "Close Case"
4. Select closure reason
5. Add final notes
6. Confirm closure

Closed cases can be reopened if client returns.

---

## For Providers

### How do I register as a provider?

Contact the system administrator. Provide:
- Organization name
- Services offered
- Service areas
- Capacity information
- Contact details

Administrator will create your provider account.

### How do I accept or decline a referral?

1. Login to provider portal
2. View referral queue
3. Click on a referral to review
4. Check client needs and your capacity
5. Click "Accept" or "Decline"
6. If declining, select reason and suggest alternatives
7. Submit response

### How do I update my service capacity?

1. Login to provider portal
2. Go to "Service Management"
3. Update capacity for each service
4. Indicate waitlist status if applicable
5. Save changes

This ensures you receive appropriate referrals.

### Can I see all referrals or just mine?

You only see referrals sent to your organization. You cannot see referrals sent to other providers (privacy and competition reasons).

### How do I communicate with referred clients?

1. Go to referral details
2. Click "Contact Client"
3. Use secure messaging system
4. Or use provided contact information (with consent)

All communication should respect client privacy.

### What if I can no longer provide services?

1. Update your service capacity to 0
2. Contact administrator
3. They can pause or deactivate your account
4. Existing referrals can be transferred

---

## For Administrators

### How do I initialize the database?

1. Login to admin panel (code: 079777)
2. Go to Database Tools
3. Click "Test Connection" to verify database access
4. Click "Initialize Database"
5. Confirm action (WARNING: deletes existing data)
6. Wait for completion

See INSTALL.md for detailed setup instructions.

### How do I create user accounts?

1. Login to admin panel
2. Go to User Management
3. Click "Add New User"
4. Select user role (client, staff, provider, admin)
5. Fill in user details
6. Set initial password
7. Save user

User will receive login credentials.

### How do I backup the database?

1. Login to admin panel
2. Go to Database Tools
3. Click "Create Backup"
4. Wait for backup to generate
5. Download backup file
6. Store securely off-site

**Recommended**: Set up automated daily backups.

### How do I change the admin code?

Currently hardcoded in `/admin/login.php` (line 15). For production:
1. Edit the file or
2. Move code to encrypted database table
3. Implement admin user accounts with passwords
4. Add two-factor authentication

**IMPORTANT**: Change from default (079777) before production!

### How do I view system logs?

1. Login to admin panel
2. Go to "Error Logs & Monitoring"
3. Select log type:
   - Error logs (PHP/SQL/application errors)
   - Access logs (user activity)
   - Activity logs (registrations, assessments, etc.)
4. Filter by date/type/user
5. Export if needed

### How do I customize the theme?

1. Login to admin panel
2. Go to "Theme Customization"
3. Modify:
   - Color scheme
   - Logo and branding
   - Welcome messages
   - Layout options
4. Preview changes
5. Save and apply

---

## Still Have Questions?

### Documentation

📖 **Full User Manual**: WELCOME_GUIDE.md  
🔧 **Troubleshooting Guide**: TROUBLESHOOTING.md  
🚀 **Quick Start**: QUICKSTART_USERS.md  
💻 **Technical Setup**: INSTALL.md

### Support

- **For Clients**: Contact your case manager
- **For Staff/Providers**: Contact system administrator
- **For Everyone**: Use the messaging system

### Emergency

If you're in crisis and need immediate help:
- **Suicide & Crisis Lifeline**: 988 (call or text)
- **Crisis Text Line**: Text HOME to 741741
- **Emergency**: 911

Don't wait for system access if you're in crisis!

---

**Document Version**: 1.0.0  
**Last Updated**: October 23, 2025  
**This FAQ is continuously updated based on user questions**

---

*Have a question not answered here? Contact your case manager or submit feedback to help us improve this FAQ!*
