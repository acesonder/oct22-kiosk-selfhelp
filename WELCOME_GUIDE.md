# KioskHelp - Welcome Guide & User Manual

**Version**: 1.0.0  
**Last Updated**: October 23, 2025  
**Purpose**: Comprehensive guide for all users - Clients, Service Providers, Staff, and Administrators

---

## Table of Contents

1. [Introduction](#introduction)
2. [Getting Started](#getting-started)
3. [Client Portal Guide](#client-portal-guide)
4. [Admin Panel Guide](#admin-panel-guide)
5. [Staff Portal Guide](#staff-portal-guide)
6. [Provider Portal Guide](#provider-portal-guide)
7. [Feature Usage Guides](#feature-usage-guides)
8. [Troubleshooting](#troubleshooting)
9. [FAQ](#frequently-asked-questions)
10. [Support Resources](#support-resources)

---

## Introduction

### What is KioskHelp?

KioskHelp is a comprehensive, compassionate self-help kiosk system designed to support vulnerable individuals through:
- **Registration & Assessment** - Easy, secure sign-up and needs evaluation
- **Smart Referrals** - Automatic matching with appropriate service providers
- **Case Management** - Track progress, set goals, and work toward stability
- **Self-Help Tools** - Budget planning, wellness tracking, job search, and more
- **Communication** - Secure messaging with providers and staff

### Who Uses KioskHelp?

- **Clients**: Individuals seeking support services and resources
- **Service Providers**: Organizations offering specific services (housing, healthcare, etc.)
- **Outreach Staff**: Case managers and social workers supporting clients
- **Administrators**: System managers who configure and maintain the platform

### Key Features

✅ **Privacy-First Design** - GDPR-compliant, secure data handling  
✅ **Easy to Use** - Intuitive interface, mobile-friendly  
✅ **Comprehensive** - All services in one place  
✅ **Compassionate** - Built with empathy and understanding  
✅ **Accessible** - Works on desktop, tablet, and mobile devices

---

## Getting Started

### System Access

The KioskHelp system can be accessed through different portals based on your role:

| Role | Portal URL | Login Method |
|------|-----------|--------------|
| Client | `/client/login.php` | Username + Password |
| Staff | `/staff/login.php` | Username + Password |
| Provider | `/provider/login.php` | Username + Password |
| Admin | `/admin/login.php` | Admin Code (079777) |

### Landing Page

![Landing Page](docs/screenshots/landing/01-homepage.png)

The landing page provides:
- Overview of available services
- Quick access to all portals
- Information about features and tools
- Crisis resources (988 Suicide & Crisis Lifeline)

**Navigation Options:**
- **Get Started** - Register as a new client
- **Client Login** - Existing client access
- **Staff Portal** - For outreach workers
- **Provider Portal** - For service organizations
- **Admin** - System administration

---

## Client Portal Guide

### Overview

The Client Portal is designed for individuals seeking support services. It provides a complete journey from registration through case management.

### 1. Client Registration

**Page**: `/client/register.php`

**Purpose**: Create a new client account with automatic username generation.

**How to Register:**

1. **Navigate to Registration**
   - Click "Get Started" on the landing page, or
   - Click "Register" on the client login page

2. **Fill Out Personal Information**
   - First Name (required)
   - Last Name (required)
   - Date of Birth (required)
   - Email (optional but recommended)
   - Phone Number (optional)

3. **Username Generation**
   - Your username is automatically generated based on:
     - First 3 letters of first name
     - First 3 letters of last name
     - Date of birth (MMDDYY)
   - **Example**: Michael Brown, born May 6, 1984 = `MICBRO050684`
   - The system shows you a preview as you type

4. **Create Password**
   - Minimum 8 characters
   - Contains uppercase, lowercase, and numbers (recommended)
   - Use the "Show Password" toggle to verify

5. **Security Questions**
   - Select a security question
   - Provide an answer (used for password recovery)
   - Choose carefully - you'll need this to recover your account

6. **Consent & Privacy**
   - Read the data handling consent
   - Check the box to agree
   - Your data is protected under GDPR guidelines

7. **Complete Registration**
   - Click "Register"
   - You'll be automatically logged in
   - Redirected to your dashboard

**Key Features:**
- ✅ Real-time username preview
- ✅ Password strength indicator
- ✅ Auto-login after registration
- ✅ Privacy-focused consent process

**Troubleshooting Registration:**
- **Username already exists**: This is rare but can happen. Contact an admin.
- **Password too weak**: Ensure you meet minimum requirements
- **Form won't submit**: Check that all required fields are filled

---

### 2. Client Login

**Page**: `/client/login.php`

**Purpose**: Access your existing client account.

**How to Login:**

1. **Enter Your Credentials**
   - Username: The auto-generated username (e.g., MICBRO050684)
   - Password: The password you created during registration

2. **Login Options**
   - Click "Login" to access your dashboard
   - Click "Forgot Password?" if you can't remember your password
   - Click "Register" if you're a new user

3. **After Login**
   - You'll be redirected to your personalized dashboard
   - Your session remains active for 2 hours

**Security Features:**
- ✅ Secure password hashing
- ✅ Session-based authentication
- ✅ Failed login attempt logging
- ✅ Automatic session timeout

**Troubleshooting Login:**
- **Invalid credentials**: Double-check username and password (case-sensitive)
- **Forgot username**: Contact staff or check your registration email
- **Forgot password**: Use the password recovery process
- **Account locked**: Contact an administrator

---

### 3. Password Recovery

**Page**: `/client/recovery.php`

**Purpose**: Recover access to your account if you've forgotten your password.

**Recovery Process (3 Steps):**

**Step 1: Enter Username**
- Enter your username
- Click "Continue"
- System verifies the username exists

**Step 2: Answer Security Question**
- Your chosen security question will be displayed
- Enter the exact answer you provided during registration
- Click "Verify Answer"
- Answers are case-sensitive

**Step 3: Set New Password**
- Enter a new password
- Confirm the new password
- Click "Reset Password"
- You'll be automatically logged in with the new password

**Best Practices:**
- ✅ Remember your security question answer
- ✅ Use a strong, unique password
- ✅ Write down your username in a safe place
- ✅ Update your password regularly

**Troubleshooting Recovery:**
- **Username not found**: Verify spelling, check with staff
- **Security answer incorrect**: Answers must match exactly (case-sensitive)
- **Still can't access**: Contact an administrator for manual reset

---

### 4. Client Dashboard

**Page**: `/client/dashboard.php`

**Purpose**: Your central hub for accessing all features and viewing your status.

**Dashboard Sections:**

**Welcome Banner**
- Personalized greeting with your name
- Quick status overview
- Current date and time

**Statistics Cards**
Shows your activity at a glance:
- **Active Referrals**: Number of pending service referrals
- **Upcoming Appointments**: Scheduled meetings with providers
- **Unread Messages**: New communications from staff/providers
- **Resources Accessed**: Items you've viewed from the library

**Quick Actions**
Fast access to key features:
- 📋 **Start Assessment** - Complete a needs assessment
- 📞 **View Referrals** - See your service matches
- 📅 **My Appointments** - Manage scheduled meetings
- 💬 **Messages** - Check communications
- 📚 **Resources** - Browse the resource library
- 🛠️ **Self-Help Tools** - Access planning tools
- 👤 **My Profile** - Update your information

**Help & Support Section**
- Quick tips for getting started
- Crisis hotline information (988)
- Contact information for staff

**Navigation Sidebar**
- Always visible on the left
- Quick access to all sections
- Shows active page
- Logout option at the bottom

**Responsive Design:**
- Desktop: Full sidebar always visible
- Tablet/Mobile: Collapsible menu (hamburger icon)

---

### 5. Assessment

**Page**: `/client/assessment.php`

**Purpose**: Complete a comprehensive needs assessment to identify your support requirements.

**Assessment Domains:**

The assessment covers 8 key areas:

1. **Housing**
   - Current housing status
   - Emergency shelter needs
   - Long-term housing goals

2. **Food Security**
   - Access to regular meals
   - Food assistance needs
   - Dietary requirements

3. **Healthcare**
   - Primary care access
   - Dental and vision needs
   - Prescription assistance
   - Health insurance status

4. **Mental Health**
   - Counseling needs
   - Crisis support
   - Substance abuse treatment

5. **Employment**
   - Current employment status
   - Job training needs
   - Resume and interview help

6. **Legal Aid**
   - Legal consultation needs
   - Document assistance
   - Representation needs

7. **Transportation**
   - Access to transportation
   - Bus pass needs
   - Vehicle assistance

8. **Family Services**
   - Childcare needs
   - Family counseling
   - Parenting support

**How to Complete:**

1. **Start the Assessment**
   - Click "Start Assessment" from dashboard
   - Read the introduction

2. **Answer Each Section**
   - Rate your needs on a scale (1-5)
   - Provide additional details if requested
   - Mark urgency level

3. **Review & Submit**
   - Review all your responses
   - Edit if needed
   - Submit the assessment

4. **After Submission**
   - System generates referral recommendations
   - Priority areas are identified
   - Case is created automatically
   - Staff is notified for follow-up

**Best Practices:**
- ✅ Be honest about your needs
- ✅ Indicate urgency accurately
- ✅ Provide details to help matching
- ✅ Update assessment when needs change

---

### 6. Referrals

**Page**: `/client/referrals.php`

**Purpose**: View and manage referrals to service providers.

**Referral Information:**

Each referral shows:
- **Provider Name**: Organization providing the service
- **Service Type**: Category (housing, healthcare, etc.)
- **Status**: Pending, Accepted, In Progress, Completed, Declined
- **Priority**: High, Medium, Low
- **Date Created**: When the referral was made
- **Next Steps**: What to do next

**Referral Statuses:**

- **Pending**: Waiting for provider to respond
- **Accepted**: Provider accepted, awaiting appointment
- **In Progress**: Actively receiving services
- **Completed**: Service delivered successfully
- **Declined**: Provider unable to serve (will get alternative referral)

**Actions You Can Take:**
- View referral details
- Contact the provider
- Schedule an appointment
- Mark as completed
- Provide feedback

**Smart Matching:**
Referrals are generated based on:
- Your assessment responses
- Provider availability
- Geographic proximity
- Service specialization
- Your urgency level

---

### 7. Appointments

**Page**: `/client/appointments.php`

**Purpose**: Schedule and manage appointments with service providers.

**Appointment Features:**

**View Appointments:**
- Upcoming appointments
- Past appointments
- Cancelled appointments

**Schedule New Appointment:**
1. Select a provider (from your referrals)
2. Choose available time slot
3. Provide any special notes
4. Confirm appointment

**Appointment Details:**
- Provider name and contact
- Service type
- Date and time
- Location or virtual meeting link
- Special instructions
- Reminder settings

**Appointment Actions:**
- Reschedule
- Cancel (with notice period)
- Add to calendar
- Get directions
- Contact provider

**Reminders:**
- Automatic email reminder (24 hours before)
- SMS reminder (if phone provided)
- Dashboard notification

---

### 8. Messages

**Page**: `/client/messages.php`

**Purpose**: Secure communication with staff and service providers.

**Message Features:**

**Inbox:**
- List of all conversations
- Unread message indicator
- Sender information
- Message preview
- Timestamp

**Compose Message:**
1. Select recipient (staff or provider)
2. Enter subject
3. Write message
4. Attach files (if needed)
5. Send

**Message Thread:**
- View full conversation
- Reply to messages
- See read status
- Archive conversations

**Privacy & Security:**
- All messages encrypted
- Only authorized users can view
- Audit trail maintained
- GDPR compliant

**Best Practices:**
- ✅ Check messages regularly
- ✅ Respond to staff requests promptly
- ✅ Keep communications professional
- ✅ Don't share sensitive info (SSN, etc.)

---

### 9. Resources

**Page**: `/client/resources.php`

**Purpose**: Access curated educational materials and support resources.

**Resource Categories:**

- **Housing Guides**: Finding and securing housing
- **Employment**: Job search, resume writing, interview tips
- **Healthcare**: Insurance, finding providers, wellness
- **Financial**: Budgeting, assistance programs, financial literacy
- **Legal**: Know your rights, document help
- **Mental Health**: Coping strategies, crisis resources
- **Family**: Parenting, childcare, family support
- **General**: Life skills, community resources

**Resource Features:**

**Search & Filter:**
- Search by keyword
- Filter by category
- Sort by relevance or date
- View recently accessed

**Resource Details:**
- Title and description
- Category and tags
- Download/view option
- Related resources
- Access count

**Personalized Recommendations:**
- Based on your assessment
- Based on viewing history
- Popular resources
- Recently added

**Track Your Learning:**
- Mark resources as read
- Save favorites
- Track completion
- Get recommendations

---

### 10. Self-Help Tools

**Page**: `/client/tools.php`

**Purpose**: Access interactive tools for personal planning and progress tracking.

**Available Tools:**

#### 1. Budget Planner
**Purpose**: Track income and expenses to manage finances

**Features:**
- Add income sources
- Track expenses by category
- See spending patterns
- Set savings goals
- Generate monthly reports
- Get budget tips

**How to Use:**
1. Enter your monthly income
2. Add your fixed expenses (rent, utilities)
3. Track variable expenses (food, transportation)
4. Review your budget balance
5. Adjust spending as needed

#### 2. Housing Search Checklist
**Purpose**: Organize housing applications and track progress

**Features:**
- List potential housing options
- Track application status
- Record contact information
- Set follow-up reminders
- Note requirements/documents needed
- Mark applications complete

**How to Use:**
1. Add each housing opportunity
2. Note application deadline
3. Track documents submitted
4. Set follow-up dates
5. Update status as you progress

#### 3. Wellness Tracker
**Purpose**: Monitor daily mood, sleep, and overall wellbeing

**Features:**
- Daily mood logging
- Sleep quality tracking
- Physical activity recording
- Stress level monitoring
- Notes and reflections
- Weekly/monthly trends

**How to Use:**
1. Log your mood each day (1-10 scale)
2. Record sleep hours
3. Note any significant events
4. Review trends over time
5. Identify patterns

#### 4. Goal Planner
**Purpose**: Set personal goals and track achievements

**Features:**
- SMART goal setting
- Break down into steps
- Set deadlines
- Track progress
- Celebrate milestones
- Adjust goals as needed

**How to Use:**
1. Define your goal clearly
2. Set a target date
3. Break into smaller steps
4. Mark steps complete
5. Review progress regularly

#### 5. Job Search Organizer
**Purpose**: Manage job applications and follow-ups efficiently

**Features:**
- Track job applications
- Store company information
- Record interview dates
- Set follow-up reminders
- Note requirements
- Track responses

**How to Use:**
1. Add each job application
2. Record application date
3. Note interview dates
4. Set follow-up reminders
5. Update status
6. Track offers

#### 6. Crisis Plan Creator
**Purpose**: Develop personal crisis response and safety plan

**Features:**
- Identify warning signs
- List coping strategies
- Emergency contacts
- Professional resources
- Safe places/people
- Crisis hotlines

**How to Use:**
1. Identify your warning signs
2. List healthy coping strategies
3. Add trusted contacts
4. Note professional resources
5. Plan safe places
6. Review and update regularly

**Best Practices for Tools:**
- ✅ Use consistently for best results
- ✅ Update regularly
- ✅ Be honest in your tracking
- ✅ Review progress weekly
- ✅ Share with case manager if helpful

---

### 11. Profile Management

**Page**: `/client/profile.php`

**Purpose**: Update your personal information and preferences.

**Editable Information:**
- Contact information (email, phone)
- Address
- Emergency contact
- Communication preferences
- Privacy settings
- Password change

**How to Update:**
1. Navigate to Profile
2. Click "Edit" on section to update
3. Make changes
4. Click "Save Changes"
5. Confirm update

**Important Notes:**
- Username cannot be changed
- Date of birth cannot be changed
- Some changes may require verification
- Staff can be notified of critical changes

---

## Admin Panel Guide

### Overview

The Admin Panel provides system-wide control for administrators. Access requires an admin code (default: 079777).

**⚠️ Security Note**: Change the default admin code immediately after installation!

### 1. Admin Login

**Page**: `/admin/login.php`

**How to Access:**

1. Navigate to `/admin/login.php`
2. Enter admin code: `079777`
3. Click "Login"
4. Redirected to admin dashboard

**Security Features:**
- Failed login attempts are logged
- IP addresses are recorded
- Session timeout after inactivity
- Single admin code (change in production)

**Changing Admin Code:**
Currently hardcoded in login.php (line 15). For production:
1. Store in encrypted database table
2. Implement code rotation
3. Add two-factor authentication
4. Set up admin user accounts

---

### 2. Admin Dashboard

**Page**: `/admin/index.php`

**Purpose**: System overview and quick access to admin functions.

**Dashboard Sections:**

**System Statistics:**
- Total registered users
- Active clients
- Total referrals
- Pending appointments
- System uptime
- Database size
- Recent activity

**Quick Actions:**
- Database management
- User management
- Theme customization
- Company branding
- View error logs
- System backup
- Export data

**Recent Activity:**
- New registrations
- Recent assessments
- System errors
- Failed login attempts

**System Health:**
- Database status
- File permissions
- PHP version
- MySQL version
- Disk space usage
- Memory usage

---

### 3. Database Tools

**Page**: `/admin/database.php`

**Purpose**: Initialize, backup, and maintain the database.

**Available Tools:**

#### Initialize Database
**Purpose**: Set up database schema and default data

**When to Use:**
- First-time installation
- After database corruption
- Reset to clean state

**How to Initialize:**
1. Click "Initialize Database"
2. Confirm action (WARNING: Deletes existing data)
3. System creates all tables
4. Inserts default configuration
5. Creates sample data (if selected)
6. Shows success message

**What Gets Created:**
- All 25+ database tables
- Default roles (client, staff, provider, admin)
- System configuration
- Assessment templates
- Default themes

#### Test Database Connection
**Purpose**: Verify database connectivity

**How to Test:**
1. Click "Test Connection"
2. System attempts to connect
3. Shows success or error message
4. Displays connection details

**Connection Details Shown:**
- Host
- Database name
- Connection status
- MySQL version
- Character set

#### Backup Database
**Purpose**: Create a backup of all data

**How to Backup:**
1. Click "Create Backup"
2. System exports all tables
3. Creates .sql.gz file
4. Saves to /backup/ directory
5. Downloads to your computer

**Backup Includes:**
- All tables and data
- Schema structure
- Indexes and constraints
- Stored procedures
- Timestamp in filename

**Backup Schedule:**
Recommended:
- Daily automated backups
- Weekly full backups
- Before major updates
- Before database maintenance

#### View Database Statistics
**Purpose**: Monitor database health and size

**Statistics Shown:**
- Total tables
- Total records per table
- Database size (MB)
- Largest tables
- Index usage
- Query performance

**Best Practices:**
- ✅ Backup before initialization
- ✅ Test connection after changes
- ✅ Regular backups (daily recommended)
- ✅ Monitor database size
- ✅ Clean old data periodically

---

### 4. User Management

**Purpose**: Manage all user accounts across all roles.

**User Management Features:**

**View All Users:**
- List of all registered users
- Filter by role (client, staff, provider, admin)
- Search by name or username
- Sort by registration date

**User Details:**
- Username
- Full name
- Email
- Phone
- Role
- Registration date
- Last login
- Account status (active/inactive)

**User Actions:**
- View full profile
- Edit user information
- Reset password
- Deactivate account
- Delete user (with confirmation)
- Change user role
- View user activity

**Bulk Operations:**
- Export user list
- Bulk email users
- Bulk role assignment
- Account status changes

**Security Considerations:**
- All actions are logged
- Deletions require confirmation
- Cannot delete users with active cases
- Role changes require justification

---

### 5. Theme Customization

**Purpose**: Customize the visual appearance of KioskHelp.

**Customization Options:**

**Color Scheme:**
- Primary color
- Secondary color
- Accent color
- Background colors
- Text colors
- Button colors

**Branding:**
- Company logo
- Favicon
- Welcome message
- Footer text
- Contact information

**Layout Options:**
- Sidebar position
- Card styles
- Button shapes
- Font selection
- Spacing adjustments

**How to Customize:**
1. Navigate to Theme Customization
2. Select element to customize
3. Choose color or upload image
4. Preview changes
5. Save theme
6. Apply to all users or specific roles

**Theme Presets:**
- Light mode (default)
- Dark mode
- High contrast (accessibility)
- Custom themes

---

### 6. System Configuration

**Purpose**: Configure system-wide settings and features.

**Configuration Sections:**

**General Settings:**
- Site name
- Timezone
- Date format
- Language
- Contact email

**Security Settings:**
- Session timeout
- Password requirements
- Max login attempts
- Lockout duration

**Feature Toggles:**
- Enable/disable registration
- Enable/disable self-assessment
- Enable/disable messaging
- Enable/disable appointments

**Email Configuration:**
- SMTP settings
- Email templates
- Notification settings
- Automated emails

**File Upload Settings:**
- Max file size
- Allowed file types
- Upload directory
- Storage limits

---

### 7. Error Logs & Monitoring

**Purpose**: View and analyze system errors and issues.

**Log Features:**

**Error Log:**
- Timestamp
- Error type (PHP, SQL, Application)
- Error message
- File and line number
- Stack trace
- User context

**Access Log:**
- User login/logout
- Page access
- Failed login attempts
- IP addresses

**Activity Log:**
- User registrations
- Assessment completions
- Referral creations
- Appointment scheduling

**How to Use Logs:**
1. Navigate to Error Logs
2. Filter by date range
3. Filter by error type
4. Search for specific errors
5. View details
6. Export logs

**Common Issues to Monitor:**
- Database connection failures
- Failed login attempts
- PHP errors
- File upload issues
- Session problems

---

## Staff Portal Guide

### Overview

The Staff Portal is designed for outreach workers and case managers who support clients.

**Access**: `/staff/login.php` (requires staff account)

### 1. Staff Login

**Page**: `/staff/login.php`

**Credentials:**
- Username: Provided by admin
- Password: Set during account creation

**After Login:**
- Access to staff dashboard
- View assigned cases
- Manage client referrals
- Schedule appointments

### 2. Staff Dashboard

**Purpose**: Central hub for case management and client support.

**Dashboard Features:**

**My Caseload:**
- Total assigned clients
- Active cases
- Pending assessments
- Upcoming appointments

**Quick Actions:**
- Create new client
- Schedule appointment
- Add case note
- Create referral
- Send message

**Today's Schedule:**
- Appointments for today
- Pending tasks
- Follow-ups needed
- Priority cases

---

### 3. Client Case Management

**Purpose**: Manage individual client cases and track progress.

**Case Features:**

**Client Overview:**
- Client information
- Assessment results
- Active referrals
- Appointment history
- Communication log

**Case Actions:**
- Add case notes
- Update case status
- Create referrals
- Schedule appointments
- Generate reports

**Case Notes:**
- Date and time stamped
- Category (phone call, meeting, email)
- Subject and details
- Follow-up needed
- Privacy level

**Progress Tracking:**
- Goals set and achieved
- Services accessed
- Barriers encountered
- Next steps
- Success metrics

**Best Practices:**
- ✅ Document all interactions
- ✅ Update cases regularly
- ✅ Follow up on referrals
- ✅ Maintain confidentiality
- ✅ Coordinate with providers

---

### 4. Referral Management

**Purpose**: Create and manage client referrals to service providers.

**Creating Referrals:**

1. **Select Client**
   - Choose from your caseload
   - Or create new client

2. **Choose Service Type**
   - Housing
   - Healthcare
   - Employment
   - Mental health
   - Legal aid
   - Transportation
   - Family services
   - Food assistance

3. **Provider Matching**
   - System suggests best matches
   - Based on client needs
   - Provider availability
   - Geographic proximity

4. **Set Priority**
   - High (urgent need)
   - Medium (important)
   - Low (when available)

5. **Add Notes**
   - Specific client needs
   - Special considerations
   - Urgency explanation

6. **Submit Referral**
   - Provider is notified
   - Client is notified
   - Case is updated
   - Follow-up scheduled

**Tracking Referrals:**
- View all referrals
- Filter by status
- Monitor response times
- Follow up on pending
- Close completed referrals

---

### 5. Appointment Scheduling

**Purpose**: Schedule and manage client appointments with providers.

**Scheduling Process:**

1. Select client
2. Choose provider
3. Select service type
4. Pick available time slot
5. Add location/virtual meeting link
6. Include special instructions
7. Confirm appointment

**Appointment Features:**
- Calendar view
- List view
- Filter by client/provider
- Reschedule capability
- Cancellation with notice
- Reminder settings

**Reminders:**
- Automatic to client
- Automatic to provider
- Staff notification
- Follow-up prompts

---

### 6. Communication & Messaging

**Purpose**: Communicate with clients and providers.

**Messaging Features:**
- Send messages to clients
- Coordinate with providers
- Team communication
- Group messages
- Attachments
- Read receipts

**Communication Best Practices:**
- ✅ Professional tone
- ✅ Timely responses
- ✅ Document important conversations
- ✅ Maintain confidentiality
- ✅ Follow organizational policies

---

## Provider Portal Guide

### Overview

The Provider Portal is for service organizations that accept client referrals.

**Access**: `/provider/login.php` (requires provider account)

### 1. Provider Login

**Page**: `/provider/login.php`

**Credentials:**
- Username: Organization username
- Password: Set during provider registration

### 2. Provider Dashboard

**Purpose**: Manage incoming referrals and client appointments.

**Dashboard Sections:**

**Referral Queue:**
- New referrals (pending review)
- Accepted referrals
- In-progress services
- Completed referrals

**Appointments:**
- Today's appointments
- Upcoming schedule
- Past appointments
- Cancellations

**Statistics:**
- Total referrals received
- Acceptance rate
- Services provided
- Client satisfaction

---

### 3. Managing Referrals

**Purpose**: Review and respond to client referrals.

**Referral Actions:**

**Accept Referral:**
1. Review client needs
2. Check service availability
3. Accept referral
4. Contact client
5. Schedule initial appointment

**Decline Referral:**
1. Review referral
2. Select decline reason
3. Suggest alternative providers
4. Submit response
5. System notifies staff

**Referral Information:**
- Client name and contact
- Service needed
- Priority level
- Staff contact
- Special notes
- Assessment results (if shared)

**Best Practices:**
- ✅ Respond to referrals within 48 hours
- ✅ Accept only if you can serve
- ✅ Suggest alternatives if declining
- ✅ Communicate clearly with staff
- ✅ Update referral status regularly

---

### 4. Service Management

**Purpose**: Update your available services and capacity.

**Service Features:**

**Service Catalog:**
- List of services offered
- Service descriptions
- Eligibility requirements
- Contact information
- Hours of operation

**Capacity Management:**
- Current availability
- Waitlist status
- Service restrictions
- Geographic coverage

**Updating Information:**
- Add new services
- Update capacity
- Change hours
- Update contact info
- Add special programs

---

### 5. Client Communication

**Purpose**: Communicate with referred clients.

**Communication Options:**
- Secure messaging
- Appointment reminders
- Service updates
- Follow-up requests
- Satisfaction surveys

**Privacy Considerations:**
- All communications are logged
- Comply with HIPAA/confidentiality
- Professional communication only
- No sharing of client info

---

## Feature Usage Guides

### How to Enable/Disable Features

**Admin Access Required**

#### Disable Client Registration

**Why**: During maintenance or to close intake

**How:**
1. Login to admin panel
2. Navigate to System Configuration
3. Find "Enable Registration" toggle
4. Set to "Disabled"
5. Save changes

**Effect**: Registration page shows "Registration currently closed" message

#### Enable Email Notifications

**Why**: Automatic email reminders and updates

**How:**
1. Configure SMTP settings in config
2. Test email connection
3. Enable in System Configuration
4. Configure email templates
5. Set notification preferences

**Requirements:**
- SMTP server credentials
- Valid email address
- Email templates configured

#### Configure Appointment Reminders

**Why**: Reduce no-shows and improve attendance

**How:**
1. Navigate to System Configuration
2. Find "Appointment Reminders" section
3. Set reminder timing (24 hours before)
4. Choose notification method (email/SMS)
5. Customize reminder message
6. Save settings

---

### How to Use Self-Help Tools Effectively

#### Budget Planner Tips

1. **Be Realistic**
   - Enter actual income, not hoped-for
   - Include all expenses, even small ones
   - Update monthly

2. **Categorize Properly**
   - Fixed costs (rent, insurance)
   - Variable costs (food, transportation)
   - Discretionary (entertainment)
   - Savings

3. **Review Regularly**
   - Check weekly spending
   - Adjust categories as needed
   - Identify saving opportunities
   - Track progress toward goals

#### Wellness Tracker Best Practices

1. **Daily Consistency**
   - Log at the same time each day
   - Be honest about mood
   - Note contributing factors

2. **Look for Patterns**
   - What affects your mood?
   - Sleep correlation
   - Stress triggers
   - Positive activities

3. **Share with Providers**
   - Bring data to appointments
   - Discuss trends
   - Adjust treatment plans

#### Goal Setting Guidelines

1. **SMART Goals**
   - Specific: Clear, detailed objective
   - Measurable: Track progress
   - Achievable: Realistic for you
   - Relevant: Aligns with values
   - Time-bound: Set deadline

2. **Break Down Large Goals**
   - Major goal: Find stable housing
   - Step 1: Research options (Week 1)
   - Step 2: Gather documents (Week 2)
   - Step 3: Submit applications (Week 3)
   - Step 4: Follow up (Week 4)

3. **Celebrate Milestones**
   - Acknowledge small wins
   - Reward progress
   - Maintain motivation

---

## Troubleshooting

### Common Issues and Solutions

#### Login Issues

**Problem**: Can't login, "Invalid credentials" error

**Solutions:**
1. ✅ Verify username is correct (case-sensitive)
2. ✅ Check password (case-sensitive)
3. ✅ Use password recovery if forgot password
4. ✅ Clear browser cache and cookies
5. ✅ Try different browser
6. ✅ Contact staff if still can't access

**Problem**: Account locked after failed attempts

**Solutions:**
1. ✅ Wait 15 minutes (automatic unlock)
2. ✅ Contact administrator for manual unlock
3. ✅ Use password recovery process

---

#### Registration Issues

**Problem**: "Username already exists" error

**Solutions:**
1. ✅ Check if you already have an account
2. ✅ Try password recovery with that username
3. ✅ Contact admin to verify/reset
4. ✅ Very rare - may be name/DOB collision

**Problem**: Form won't submit

**Solutions:**
1. ✅ Check all required fields are filled
2. ✅ Verify email format is correct
3. ✅ Ensure password meets requirements
4. ✅ Accept consent checkbox
5. ✅ Disable browser autofill
6. ✅ Try different browser

---

#### Password Recovery Issues

**Problem**: Security answer not accepted

**Solutions:**
1. ✅ Answers are case-sensitive - try exact match
2. ✅ Check for extra spaces
3. ✅ Try variations (Dr. vs Doctor)
4. ✅ Contact staff for manual reset if can't remember

**Problem**: Username not found

**Solutions:**
1. ✅ Verify spelling carefully
2. ✅ Check if you registered
3. ✅ Contact staff to look up your account
4. ✅ May need to re-register

---

#### Dashboard Loading Issues

**Problem**: Dashboard won't load or shows errors

**Solutions:**
1. ✅ Refresh the page
2. ✅ Clear browser cache
3. ✅ Check internet connection
4. ✅ Try logging out and back in
5. ✅ Report to staff if persists

**Problem**: Statistics show zero or incorrect numbers

**Solutions:**
1. ✅ Database may be updating - wait a moment
2. ✅ Refresh the page
3. ✅ If consistently wrong, report to admin

---

#### Assessment Issues

**Problem**: Can't submit assessment

**Solutions:**
1. ✅ Ensure all required questions answered
2. ✅ Check for validation errors
3. ✅ Try saving as draft first
4. ✅ Take screenshots of progress
5. ✅ Contact staff for assistance

---

#### Message Delivery Issues

**Problem**: Messages not sending

**Solutions:**
1. ✅ Check internet connection
2. ✅ Verify recipient is correct
3. ✅ Check message size (if attachments)
4. ✅ Try again after a few minutes
5. ✅ Contact staff if urgent

**Problem**: Not receiving notifications

**Solutions:**
1. ✅ Check email spam folder
2. ✅ Verify email address in profile
3. ✅ Check notification preferences
4. ✅ Whitelist KioskHelp email address

---

#### File Upload Issues

**Problem**: Can't upload attachments

**Solutions:**
1. ✅ Check file size (must be under 5MB)
2. ✅ Verify file type is allowed
3. ✅ Try renaming file (remove special characters)
4. ✅ Try different file format
5. ✅ Compress large files

**Allowed file types:**
- Documents: PDF, DOC, DOCX
- Images: JPG, JPEG, PNG
- Maximum size: 5MB per file

---

#### Mobile Access Issues

**Problem**: Site doesn't display correctly on mobile

**Solutions:**
1. ✅ Use mobile browser (Chrome, Safari)
2. ✅ Ensure you're not in desktop mode
3. ✅ Update browser to latest version
4. ✅ Clear browser cache
5. ✅ Try landscape orientation for forms

---

#### Browser Compatibility

**Supported Browsers:**
- ✅ Chrome (latest version)
- ✅ Firefox (latest version)
- ✅ Safari (latest version)
- ✅ Edge (latest version)

**Not Supported:**
- ❌ Internet Explorer
- ❌ Very old browser versions

---

## Frequently Asked Questions

### General Questions

**Q: Is KioskHelp free to use?**  
A: Yes, KioskHelp is completely free for clients seeking services.

**Q: Is my information private?**  
A: Yes. KioskHelp is GDPR-compliant. Your data is encrypted, secure, and only shared with authorized staff and providers you're referred to.

**Q: Can I use KioskHelp on my phone?**  
A: Yes! KioskHelp is fully responsive and works on desktop, tablet, and mobile devices.

**Q: What if I don't have email?**  
A: Email is optional. You can register and use the system without it, though you won't receive email notifications.

**Q: How do I change my password?**  
A: Go to your Profile page and use the "Change Password" option.

**Q: Can I delete my account?**  
A: Contact an administrator to request account deletion. Some data may be retained for legal/compliance reasons.

---

### Assessment Questions

**Q: How often should I complete an assessment?**  
A: Complete an initial assessment when you register. Update it whenever your needs change significantly (recommended: every 3-6 months).

**Q: Who can see my assessment?**  
A: Only authorized staff, case managers assigned to you, and service providers you're referred to (with your consent).

**Q: Can I edit my assessment after submitting?**  
A: Yes, you can complete a new assessment anytime. Previous assessments are saved for reference.

**Q: What if my needs are urgent?**  
A: Mark urgent needs as "High Priority" in your assessment. Also contact staff directly or call crisis lines (988).

---

### Referral Questions

**Q: How are referrals made?**  
A: After your assessment, the system automatically matches you with appropriate providers based on your needs, location, and provider availability.

**Q: What if I don't like a referral?**  
A: You can decline any referral. Contact your case manager to discuss alternatives.

**Q: How long until I hear from a provider?**  
A: Providers typically respond within 48 hours. If no response, contact your case manager.

**Q: Can I request a specific provider?**  
A: Yes! Contact your case manager to request a specific provider if you know of one.

---

### Appointment Questions

**Q: How do I cancel an appointment?**  
A: Go to Appointments, select the appointment, and click "Cancel." Please provide as much notice as possible.

**Q: What if I miss an appointment?**  
A: Contact the provider to reschedule. Repeated no-shows may affect future referrals.

**Q: Can I have virtual appointments?**  
A: Many providers offer virtual appointments. Check the appointment details for meeting link.

**Q: How do I reschedule?**  
A: Cancel the current appointment and schedule a new one, or contact the provider to reschedule directly.

---

### Technical Questions

**Q: What if I forget my username?**  
A: Your username is generated from your name and birthdate (e.g., MICBRO050684). Contact staff if you can't remember.

**Q: Why can't I access certain features?**  
A: Some features may be disabled or require certain steps to be completed first (like assessment before referrals).

**Q: Is there a mobile app?**  
A: Currently, KioskHelp is web-based. You can access it through your mobile browser. A dedicated app may be developed in the future.

**Q: How long does my session last?**  
A: Sessions last 2 hours of inactivity. You'll be automatically logged out for security.

---

### Privacy & Security Questions

**Q: Who can see my information?**  
A: Only authorized staff, your assigned case manager, and providers you're referred to (with consent).

**Q: Is my communication encrypted?**  
A: Yes, all messages and data transmission is encrypted using industry-standard security.

**Q: What data is collected?**  
A: Only information necessary to provide services: name, contact info, assessment responses, referrals, appointments, and messages.

**Q: Can I opt out of data sharing?**  
A: You can manage privacy preferences in your profile. However, some data sharing is necessary to receive services.

**Q: How long is my data kept?**  
A: Data is retained according to legal requirements (typically 7 years). You can request deletion by contacting an admin.

---

## Support Resources

### Crisis Resources

**Immediate Danger**: Call 911

**Suicide & Crisis Lifeline**: 988  
- 24/7 confidential support
- Call or text 988
- Chat at 988lifeline.org

**Crisis Text Line**: Text HOME to 741741  
- 24/7 crisis support via text
- Confidential
- Trained crisis counselors

**Domestic Violence Hotline**: 1-800-799-7233  
- 24/7 support
- Help for abuse victims
- Safety planning

**Substance Abuse Hotline**: 1-800-662-4357  
- SAMHSA National Helpline
- 24/7 treatment referral
- Free and confidential

---

### Contact Information

**Technical Support**  
For login issues, technical problems, or bug reports:
- Email: support@kioskhelp.com (if configured)
- Contact your local staff administrator

**Case Management**  
For questions about services, referrals, or your case:
- Contact your assigned case manager
- Use the messaging system in KioskHelp
- Visit your local service center

**Administrator**  
For account issues, data requests, or general inquiries:
- Contact the system administrator at your organization

---

### Additional Resources

**Documentation**
- README.md - System overview
- INSTALL.md - Installation guide
- QUICKSTART.md - Quick setup guide
- PROJECT_STATUS.md - Development status

**Training Materials**
- Video tutorials (if available)
- User training sessions
- Staff onboarding materials
- Provider orientation

**Community**
- User feedback portal
- Feature requests
- Bug reports
- Community forum (if available)

---

## Appendix

### Glossary of Terms

**Assessment**: Comprehensive evaluation of client needs across multiple service domains

**Case Management**: Coordinated approach to supporting client progress toward goals

**Client**: Individual seeking support services through KioskHelp

**Provider**: Organization offering specific services (housing, healthcare, etc.)

**Referral**: Connection made between client and service provider based on needs

**Staff**: Outreach workers, case managers, and social workers supporting clients

**GDPR**: General Data Protection Regulation - privacy and data protection law

**SMART Goals**: Specific, Measurable, Achievable, Relevant, Time-bound objectives

---

### Keyboard Shortcuts

**Navigation:**
- `Alt + H` - Home/Dashboard
- `Alt + P` - Profile
- `Alt + M` - Messages
- `Alt + L` - Logout

**Forms:**
- `Tab` - Next field
- `Shift + Tab` - Previous field
- `Enter` - Submit form

**General:**
- `Ctrl/Cmd + F` - Search page
- `Esc` - Close modal/dialog

---

### System Requirements

**Minimum Requirements:**
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Internet connection
- JavaScript enabled
- Cookies enabled

**Recommended:**
- Broadband internet connection
- Updated browser (latest version)
- Screen resolution: 1280x720 or higher
- Popup blocker disabled for KioskHelp

---

### Version History

**Version 1.0.0** (October 2025)
- Initial release
- Core features: Registration, Login, Assessment, Referrals
- Client, Staff, Provider, and Admin portals
- Self-help tools suite
- Messaging system
- Appointment scheduling
- Resource library
- Admin panel with database tools

---

### Credits & Acknowledgments

**Development Team**  
Built with compassion for vulnerable communities, designed to empower change and support hope.

**Technology Stack**
- Backend: PHP with PDO
- Database: MySQL
- Frontend: HTML5, CSS3, JavaScript
- Design: Responsive, mobile-first approach

**Open Source**  
KioskHelp is open source software available under the MIT License.

---

## Document Information

**Document Version**: 1.0.0  
**Last Updated**: October 23, 2025  
**Maintained By**: Development Team  
**Contact**: support@kioskhelp.com

---

**Thank you for using KioskHelp!**

We're committed to supporting your journey toward stability and success. If you have questions, feedback, or need assistance, please don't hesitate to reach out.

**Remember**: You're not alone. Help is available, and you deserve support.

---

*This guide is a living document and will be updated as new features are added and improvements are made.*
