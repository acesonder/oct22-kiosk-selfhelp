# KioskHelp - Comprehensive Self-Help Kiosk System

A professional, compassionate kiosk-based self-help system designed to support vulnerable clients through registration, assessment, referral, and case management services.

## Overview

KioskHelp provides a comprehensive platform for vulnerable individuals to access services, resources, and support through an easy-to-use kiosk interface. The system is designed to be accessible, privacy-focused, and empowering for clients seeking help.

## Features

### 1. Client Registration & Consent Management
- Secure registration for new and existing clients
- Automatic username generation (e.g., MICBRO050684 from Michael Brown, May 6, 1984)
- Security question-based password recovery
- GDPR-compliant data handling
- Auto-login after registration/recovery

### 2. Intake & Assessment
- Guided intake process to identify immediate needs
- Multi-domain assessments: housing, food, healthcare, mental health, employment, legal, transportation, family services
- Intelligent need prioritization

### 3. Intelligent Referral System
- Automatic matching with appropriate service providers
- Comprehensive provider directory
- Priority-based referral routing
- Referral tracking and status updates

### 4. Communication & Appointments
- Client messaging system
- Appointment scheduling with service providers
- Automated appointment reminders
- Multiple communication channels

### 5. Resource Library
- Curated educational resources
- Searchable by category or keyword
- Resource access tracking
- Personalized recommendations

### 6. Smart Case Management
- Automated case creation from assessments
- Goal setting and tracking
- Progress monitoring with metrics
- Case notes and activity logging
- Intelligent insights and recommendations

### 7. Self-Help Tools
- **Budget Planning Tool** - Create and manage personal budgets
- **Housing Search Checklist** - Track housing applications
- **Wellness Tracker** - Monitor daily mood and wellbeing
- **Goal Planner** - Set and track personal goals
- **Job Search Organizer** - Manage job applications
- **Crisis Plan Creator** - Develop personal crisis response plans
- **Resource Finder** - Locate community resources

### 8. Admin Dashboard
- Secure admin access (code: 079777)
- User management with role-based access
- Theme customization
- Company branding configuration
- Database backup/export/repair tools
- Error log viewer
- Version control

## Technology Stack

- **Backend**: PHP with PDO for MySQL
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **AJAX**: Native Fetch API
- **Design**: Responsive, mobile-first design
- **Animations**: CSS transitions and animations

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for Apache)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/acesonder/oct22-kiosk-selfhelp.git
   cd oct22-kiosk-selfhelp
   ```

2. **Configure database**
   ```bash
   cp config/database.example.php config/database.php
   ```
   Edit `config/database.php` with your MySQL credentials.

3. **Initialize the database**
   - Navigate to `admin/login.php`
   - Login with admin code: `079777`
   - Go to Database Tools
   - Click "Initialize Database"

4. **Set up file permissions**
   ```bash
   chmod 755 uploads/ backup/ logs/ cache/ tmp/
   ```

5. **Access the application**
   - Landing page: `http://localhost/kioskhelp/`
   - Client portal: `http://localhost/kioskhelp/client/`
   - Admin panel: `http://localhost/kioskhelp/admin/`

## User Roles

### Client
- Self-registration with auto-generated username
- Complete assessments
- View referrals and appointments
- Access self-help tools
- Communicate with providers

### Staff (Outreach Worker)
- Manage client cases
- Create and track referrals
- Schedule appointments
- Add case notes
- Monitor client progress

### Provider (Service Provider)
- View and manage referrals
- Schedule appointments
- Update service availability
- Communicate with clients

### Admin
- Full system access
- User management
- Theme customization
- Database management
- System configuration
- Error monitoring

## Security Features

- Password hashing using bcrypt
- SQL injection protection via prepared statements
- XSS protection
- CSRF protection
- Session management
- Role-based access control
- GDPR-compliant data handling
- Secure password recovery

## Default Credentials

### Admin Access
- Code: `079777`

### Test User (after database initialization)
- Username: `admin`
- Password: `admin123`

**Important**: Change default credentials immediately after installation!

## Directory Structure

```
kioskhelp/
├── admin/              # Admin dashboard
├── api/                # API endpoints
├── assets/             # Static assets
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── images/        # Images
├── backup/            # Database backups
├── cache/             # Cache files
├── client/            # Client portal
├── config/            # Configuration files
├── database/          # Database schema
├── includes/          # PHP classes and utilities
├── logs/              # Error and access logs
├── provider/          # Provider portal
├── staff/             # Staff portal
├── tmp/               # Temporary files
├── uploads/           # User uploads
└── index.php          # Landing page
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For support, please contact the development team or open an issue on GitHub.

## Version

Current Version: **1.0.0**

## Acknowledgments

Built with compassion for vulnerable communities, designed to empower change and support hope.

---

**Crisis Resources:**
- National Suicide Prevention Lifeline: 988
- Crisis Text Line: Text HOME to 741741
- Emergency Services: 911