<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KioskHelp - Comprehensive Self-Help Kiosk System</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <i class="fas fa-hands-helping"></i>
                <span>KioskHelp</span>
            </div>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#services">Services</a>
                <a href="#about">About</a>
                <a href="client/login.php" class="btn-primary">Client Login</a>
                <a href="staff/login.php" class="btn-secondary">Staff Portal</a>
                <a href="provider/login.php" class="btn-secondary">Provider Portal</a>
                <a href="admin/login.php" class="btn-admin">Admin</a>
            </div>
            <div class="nav-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 class="hero-title">
                <span class="animate-fade-in">Empowering Change,</span>
                <span class="animate-fade-in delay-1">Supporting Hope</span>
            </h1>
            <p class="hero-subtitle animate-fade-in delay-2">
                A compassionate, comprehensive self-help kiosk system designed to support vulnerable individuals through registration, assessment, referral, and case management services.
            </p>
            <div class="hero-buttons animate-fade-in delay-3">
                <a href="client/register.php" class="btn-large btn-primary">
                    <i class="fas fa-user-plus"></i> Get Started
                </a>
                <a href="#features" class="btn-large btn-outline">
                    <i class="fas fa-info-circle"></i> Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title">Comprehensive Support Tools</h2>
            <p class="section-subtitle">Everything you need to navigate your journey to stability and success</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3>Easy Registration</h3>
                    <p>Secure, private registration with automatic username generation. Your information is protected and handled with care.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>Guided Assessment</h3>
                    <p>Comprehensive intake process to identify your immediate needs across housing, food, healthcare, and more.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h3>Smart Referrals</h3>
                    <p>Automatic matching with appropriate service providers based on your needs and priorities.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Appointments</h3>
                    <p>Schedule and manage appointments with service providers. Receive automated reminders.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Messaging</h3>
                    <p>Communicate securely with case managers and service providers through our platform.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Resource Library</h3>
                    <p>Access curated educational resources, guides, and support materials tailored to your needs.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>Case Management</h3>
                    <p>Track your progress, set goals, and work with case managers to achieve stability.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Self-Help Tools</h3>
                    <p>Budget planning, wellness tracking, goal setting, job search organizer, and more.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <div class="container">
            <h2 class="section-title">Service Areas We Support</h2>
            <div class="services-grid">
                <div class="service-item">
                    <i class="fas fa-home"></i>
                    <h4>Housing</h4>
                    <p>Emergency shelter, transitional housing, permanent housing assistance</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-utensils"></i>
                    <h4>Food Security</h4>
                    <p>Food banks, meal programs, nutrition assistance</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-heartbeat"></i>
                    <h4>Healthcare</h4>
                    <p>Primary care, dental, vision, prescription assistance</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-brain"></i>
                    <h4>Mental Health</h4>
                    <p>Counseling, crisis support, substance abuse treatment</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-briefcase"></i>
                    <h4>Employment</h4>
                    <p>Job training, resume help, interview preparation</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-gavel"></i>
                    <h4>Legal Aid</h4>
                    <p>Free legal consultation and assistance</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-bus"></i>
                    <h4>Transportation</h4>
                    <p>Bus passes, ride assistance, vehicle programs</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-users"></i>
                    <h4>Family Services</h4>
                    <p>Childcare, family counseling, parenting support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Self-Help Tools Section -->
    <section class="tools-showcase">
        <div class="container">
            <h2 class="section-title">Powerful Self-Help Tools</h2>
            <p class="section-subtitle">Take control of your journey with these essential tools</p>
            
            <div class="tools-grid">
                <div class="tool-card">
                    <i class="fas fa-calculator"></i>
                    <h4>Budget Planner</h4>
                    <p>Track income and expenses to manage your finances effectively</p>
                </div>
                <div class="tool-card">
                    <i class="fas fa-search-location"></i>
                    <h4>Housing Search</h4>
                    <p>Organize your housing applications and track progress</p>
                </div>
                <div class="tool-card">
                    <i class="fas fa-smile"></i>
                    <h4>Wellness Tracker</h4>
                    <p>Monitor your daily mood, sleep, and overall wellbeing</p>
                </div>
                <div class="tool-card">
                    <i class="fas fa-bullseye"></i>
                    <h4>Goal Planner</h4>
                    <p>Set personal goals and track your achievements</p>
                </div>
                <div class="tool-card">
                    <i class="fas fa-file-alt"></i>
                    <h4>Job Search Organizer</h4>
                    <p>Manage job applications and follow-ups efficiently</p>
                </div>
                <div class="tool-card">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Crisis Plan Creator</h4>
                    <p>Develop your personal crisis response and safety plan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>About KioskHelp</h2>
                    <p>KioskHelp is a comprehensive, compassionate platform designed to support vulnerable individuals in accessing the services and resources they need to achieve stability and success.</p>
                    <p>We believe that everyone deserves access to quality support services, delivered with dignity and respect. Our system streamlines the process of connecting with service providers, tracking progress, and managing your journey toward a better future.</p>
                    <div class="about-features">
                        <div class="about-feature">
                            <i class="fas fa-lock"></i>
                            <div>
                                <h4>Privacy First</h4>
                                <p>GDPR-compliant data handling ensures your information is secure</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <i class="fas fa-universal-access"></i>
                            <div>
                                <h4>Accessible</h4>
                                <p>Designed for easy use on desktop and mobile devices</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <i class="fas fa-heart"></i>
                            <div>
                                <h4>Compassionate</h4>
                                <p>Built with empathy and understanding for your unique situation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Take the first step toward stability and support</p>
            <a href="client/register.php" class="btn-large btn-primary">
                <i class="fas fa-user-plus"></i> Register Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-hands-helping"></i> KioskHelp</h4>
                    <p>Empowering change, supporting hope</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="client/login.php">Client Login</a></li>
                        <li><a href="staff/login.php">Staff Portal</a></li>
                        <li><a href="provider/login.php">Provider Portal</a></li>
                        <li><a href="admin/login.php">Admin</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#about">About</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Emergency</h4>
                    <p>If you're in crisis, call 988 (Suicide & Crisis Lifeline)</p>
                    <p>or 911 for immediate emergency assistance</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 KioskHelp. All rights reserved. | Version 1.0.0</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/landing.js"></script>
</body>
</html>
