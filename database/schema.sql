-- KioskHelp Database Schema
-- Comprehensive Self-Help Kiosk System
-- Created: 2025-10-22

-- Drop existing tables if they exist (for fresh installation)
DROP TABLE IF EXISTS appointment_reminders;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS case_notes;
DROP TABLE IF EXISTS case_goals;
DROP TABLE IF EXISTS cases;
DROP TABLE IF EXISTS referrals;
DROP TABLE IF EXISTS assessment_responses;
DROP TABLE IF EXISTS assessments;
DROP TABLE IF EXISTS client_resources;
DROP TABLE IF EXISTS resources;
DROP TABLE IF EXISTS service_providers;
DROP TABLE IF EXISTS consent_records;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS themes;
DROP TABLE IF EXISTS system_config;
DROP TABLE IF EXISTS error_logs;
DROP TABLE IF EXISTS self_help_tools;
DROP TABLE IF EXISTS budget_entries;
DROP TABLE IF EXISTS housing_checklist;
DROP TABLE IF EXISTS wellness_entries;
DROP TABLE IF EXISTS goals;
DROP TABLE IF EXISTS job_applications;
DROP TABLE IF EXISTS crisis_plans;

-- System Configuration Table
CREATE TABLE system_config (
    id INT PRIMARY KEY AUTO_INCREMENT,
    config_key VARCHAR(100) UNIQUE NOT NULL,
    config_value TEXT,
    config_type VARCHAR(50) DEFAULT 'string',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default system configuration
INSERT INTO system_config (config_key, config_value, config_type, description) VALUES
('site_name', 'KioskHelp', 'string', 'Website name'),
('company_name', 'Community Support Services', 'string', 'Company name'),
('company_logo', 'assets/images/logo.png', 'string', 'Path to company logo'),
('admin_code', '079777', 'string', 'Admin access code'),
('default_theme', 'light', 'string', 'Default theme'),
('enable_sms', 'false', 'boolean', 'Enable SMS notifications'),
('enable_email', 'true', 'boolean', 'Enable email notifications'),
('database_version', '1.0.0', 'string', 'Current database version'),
('app_version', '1.0.0', 'string', 'Application version');

-- Error Logs Table
CREATE TABLE error_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    error_type VARCHAR(50),
    error_message TEXT,
    error_file VARCHAR(255),
    error_line INT,
    stack_trace TEXT,
    user_id INT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_error_type (error_type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Themes Table
CREATE TABLE themes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    theme_name VARCHAR(100) UNIQUE NOT NULL,
    primary_color VARCHAR(7) DEFAULT '#1976D2',
    secondary_color VARCHAR(7) DEFAULT '#424242',
    accent_color VARCHAR(7) DEFAULT '#FFC107',
    background_color VARCHAR(7) DEFAULT '#FFFFFF',
    text_color VARCHAR(7) DEFAULT '#333333',
    font_family VARCHAR(100) DEFAULT 'Arial, sans-serif',
    custom_css TEXT,
    is_active BOOLEAN DEFAULT FALSE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default themes
INSERT INTO themes (theme_name, primary_color, secondary_color, accent_color, is_active) VALUES
('light', '#1976D2', '#424242', '#FFC107', TRUE),
('dark', '#2196F3', '#212121', '#FF9800', FALSE),
('compassion', '#9C27B0', '#4A148C', '#E1BEE7', FALSE),
('hope', '#4CAF50', '#1B5E20', '#C8E6C9', FALSE);

-- Users Table (for all system users including admin, staff, providers)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    role ENUM('admin', 'staff', 'provider', 'client') NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password_hash, email, role, first_name, last_name, is_active) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@kioskhelp.com', 'admin', 'System', 'Administrator', TRUE);

-- Clients Table (extended profile for clients)
CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    date_of_birth DATE NOT NULL,
    security_question VARCHAR(255) NOT NULL,
    security_answer_hash VARCHAR(255) NOT NULL,
    preferred_contact ENUM('email', 'sms', 'app') DEFAULT 'app',
    emergency_contact_name VARCHAR(255),
    emergency_contact_phone VARCHAR(20),
    notes TEXT,
    risk_level ENUM('low', 'medium', 'high', 'critical') DEFAULT 'low',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_dob (date_of_birth)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Consent Records Table
CREATE TABLE consent_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    consent_type VARCHAR(100) NOT NULL,
    consent_text TEXT NOT NULL,
    is_granted BOOLEAN DEFAULT FALSE,
    granted_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_client_consent (client_id, consent_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Service Providers Table
CREATE TABLE service_providers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    provider_name VARCHAR(255) NOT NULL,
    provider_type VARCHAR(100) NOT NULL,
    services_offered TEXT,
    description TEXT,
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(50),
    zip_code VARCHAR(20),
    phone VARCHAR(20),
    email VARCHAR(255),
    website VARCHAR(255),
    operating_hours TEXT,
    capacity INT,
    current_load INT DEFAULT 0,
    accepts_walkins BOOLEAN DEFAULT FALSE,
    requires_referral BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    rating DECIMAL(3,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_provider_type (provider_type),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample service providers
INSERT INTO service_providers (provider_name, provider_type, services_offered, description, city, phone, is_active) VALUES
('Downtown Shelter', 'housing', 'Emergency shelter, transitional housing', 'Emergency and transitional housing services', 'Downtown', '555-0101', TRUE),
('Community Food Bank', 'food', 'Food assistance, meal programs', 'Free food distribution and hot meals', 'Central', '555-0102', TRUE),
('Free Health Clinic', 'healthcare', 'Primary care, dental, vision', 'No-cost healthcare services', 'Westside', '555-0103', TRUE),
('Mental Health Center', 'mental_health', 'Counseling, crisis support', 'Mental health and crisis intervention', 'Eastside', '555-0104', TRUE),
('Job Training Center', 'employment', 'Job training, resume help', 'Employment services and training', 'Northside', '555-0105', TRUE),
('Legal Aid Society', 'legal', 'Free legal consultation', 'Free legal services for low-income individuals', 'Downtown', '555-0106', TRUE);

-- Assessments Table
CREATE TABLE assessments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    assessment_type VARCHAR(100) DEFAULT 'intake',
    status ENUM('in_progress', 'completed', 'reviewed') DEFAULT 'in_progress',
    priority_score INT DEFAULT 0,
    completed_at TIMESTAMP NULL,
    reviewed_by INT NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_client_status (client_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Assessment Responses Table
CREATE TABLE assessment_responses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    assessment_id INT NOT NULL,
    domain VARCHAR(100) NOT NULL,
    question_key VARCHAR(255) NOT NULL,
    response_value TEXT,
    priority_level INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE,
    INDEX idx_assessment_domain (assessment_id, domain)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Referrals Table
CREATE TABLE referrals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    provider_id INT NOT NULL,
    assessment_id INT NULL,
    referral_type VARCHAR(100) NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('pending', 'accepted', 'in_progress', 'completed', 'declined', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    referred_by INT NULL,
    referred_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accepted_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    outcome TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE SET NULL,
    FOREIGN KEY (referred_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_client_status (client_id, status),
    INDEX idx_provider_status (provider_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cases Table
CREATE TABLE cases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    case_number VARCHAR(50) UNIQUE NOT NULL,
    assessment_id INT NULL,
    assigned_to INT NULL,
    status ENUM('open', 'active', 'on_hold', 'closed') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    case_type VARCHAR(100),
    opened_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    closure_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_case_number (case_number),
    INDEX idx_client_status (client_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Case Goals Table
CREATE TABLE case_goals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    case_id INT NOT NULL,
    goal_description TEXT NOT NULL,
    goal_category VARCHAR(100),
    target_date DATE,
    status ENUM('not_started', 'in_progress', 'completed', 'abandoned') DEFAULT 'not_started',
    progress_percentage INT DEFAULT 0,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    INDEX idx_case_status (case_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Case Notes Table
CREATE TABLE case_notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    case_id INT NOT NULL,
    author_id INT NOT NULL,
    note_type VARCHAR(50) DEFAULT 'general',
    note_content TEXT NOT NULL,
    is_private BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_case_created (case_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Messages Table
CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    subject VARCHAR(255),
    message_body TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    parent_message_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_message_id) REFERENCES messages(id) ON DELETE SET NULL,
    INDEX idx_recipient_read (recipient_id, is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Appointments Table
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    provider_id INT NOT NULL,
    case_id INT NULL,
    appointment_type VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration_minutes INT DEFAULT 60,
    status ENUM('scheduled', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    notes TEXT,
    reminder_sent BOOLEAN DEFAULT FALSE,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE,
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_date_time (appointment_date, appointment_time),
    INDEX idx_client_status (client_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Appointment Reminders Table
CREATE TABLE appointment_reminders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    reminder_type ENUM('email', 'sms', 'app') NOT NULL,
    scheduled_for TIMESTAMP NOT NULL,
    sent_at TIMESTAMP NULL,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    INDEX idx_scheduled (scheduled_for, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Resources Table
CREATE TABLE resources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    resource_type ENUM('article', 'video', 'guide', 'form', 'link', 'document') NOT NULL,
    description TEXT,
    content TEXT,
    file_path VARCHAR(255),
    external_url VARCHAR(500),
    keywords TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    view_count INT DEFAULT 0,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_type (resource_type),
    FULLTEXT idx_search (title, description, keywords)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample resources
INSERT INTO resources (title, category, resource_type, description, keywords, is_featured) VALUES
('Understanding Homelessness', 'housing', 'article', 'Comprehensive guide to understanding homelessness and available support', 'homelessness, housing, support', TRUE),
('Mental Health First Aid', 'mental_health', 'guide', 'How to help someone experiencing a mental health crisis', 'mental health, crisis, help', TRUE),
('Job Application Tips', 'employment', 'article', 'Tips and strategies for successful job applications', 'job, employment, resume', FALSE),
('Nutrition on a Budget', 'food', 'guide', 'How to eat healthy on a limited budget', 'food, nutrition, budget', FALSE);

-- Client Resources Table (tracks which resources a client has accessed)
CREATE TABLE client_resources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    resource_id INT NOT NULL,
    accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_helpful BOOLEAN NULL,
    feedback TEXT,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE,
    INDEX idx_client_resource (client_id, resource_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Self-Help Tools Data Tables

-- Budget Planning
CREATE TABLE budget_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    entry_date DATE NOT NULL,
    category VARCHAR(100) NOT NULL,
    entry_type ENUM('income', 'expense') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description VARCHAR(255),
    is_recurring BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_client_date (client_id, entry_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Housing Search Checklist
CREATE TABLE housing_checklist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    property_address TEXT NOT NULL,
    landlord_name VARCHAR(255),
    landlord_phone VARCHAR(20),
    rent_amount DECIMAL(10, 2),
    application_date DATE,
    status ENUM('researching', 'applied', 'approved', 'rejected', 'accepted') DEFAULT 'researching',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_client_status (client_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wellness Tracker
CREATE TABLE wellness_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    entry_date DATE NOT NULL,
    mood_rating INT CHECK (mood_rating >= 1 AND mood_rating <= 10),
    sleep_hours DECIMAL(3, 1),
    exercise_minutes INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_client_date (client_id, entry_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Goal Planner
CREATE TABLE goals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    goal_title VARCHAR(255) NOT NULL,
    goal_description TEXT,
    goal_category VARCHAR(100),
    target_date DATE,
    status ENUM('not_started', 'in_progress', 'completed', 'abandoned') DEFAULT 'not_started',
    progress_percentage INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_client_status (client_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Job Search Organizer
CREATE TABLE job_applications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    position_title VARCHAR(255) NOT NULL,
    application_date DATE,
    status ENUM('researching', 'applied', 'interview_scheduled', 'interviewed', 'offer', 'rejected', 'accepted') DEFAULT 'researching',
    contact_name VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(20),
    notes TEXT,
    follow_up_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_client_status (client_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crisis Plan
CREATE TABLE crisis_plans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    warning_signs TEXT,
    coping_strategies TEXT,
    support_contacts TEXT,
    professional_contacts TEXT,
    safe_places TEXT,
    emergency_instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_client (client_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create views for reporting and analytics

-- Active Cases Summary View
CREATE VIEW active_cases_summary AS
SELECT 
    c.id,
    c.case_number,
    u.first_name,
    u.last_name,
    c.status,
    c.priority,
    c.opened_at,
    COALESCE(staff.first_name, 'Unassigned') as assigned_staff
FROM cases c
JOIN clients cl ON c.client_id = cl.id
JOIN users u ON cl.user_id = u.id
LEFT JOIN users staff ON c.assigned_to = staff.id
WHERE c.status IN ('open', 'active');

-- Client Dashboard View
CREATE VIEW client_dashboard AS
SELECT 
    cl.id as client_id,
    u.username,
    u.first_name,
    u.last_name,
    cl.risk_level,
    COUNT(DISTINCT c.id) as total_cases,
    COUNT(DISTINCT CASE WHEN c.status IN ('open', 'active') THEN c.id END) as active_cases,
    COUNT(DISTINCT r.id) as total_referrals,
    COUNT(DISTINCT CASE WHEN r.status = 'completed' THEN r.id END) as completed_referrals,
    COUNT(DISTINCT a.id) as total_appointments,
    COUNT(DISTINCT CASE WHEN a.status = 'scheduled' THEN a.id END) as upcoming_appointments
FROM clients cl
JOIN users u ON cl.user_id = u.id
LEFT JOIN cases c ON cl.id = c.client_id
LEFT JOIN referrals r ON cl.id = r.client_id
LEFT JOIN appointments a ON cl.id = a.client_id
GROUP BY cl.id, u.username, u.first_name, u.last_name, cl.risk_level;

-- Database initialization complete
-- Version: 1.0.0
