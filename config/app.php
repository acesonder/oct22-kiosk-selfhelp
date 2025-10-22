<?php
/**
 * Application Configuration
 */

return [
    // Application Settings
    'app_name' => 'KioskHelp',
    'app_version' => '1.0.0',
    'environment' => 'development', // development, staging, production
    
    // Security
    'session_lifetime' => 7200, // 2 hours in seconds
    'session_name' => 'KIOSKHELP_SESSION',
    'password_min_length' => 8,
    'max_login_attempts' => 5,
    'lockout_duration' => 900, // 15 minutes in seconds
    
    // File Upload
    'upload_max_size' => 5242880, // 5MB in bytes
    'allowed_file_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
    
    // Pagination
    'items_per_page' => 20,
    
    // Date/Time
    'timezone' => 'America/New_York',
    'date_format' => 'Y-m-d',
    'datetime_format' => 'Y-m-d H:i:s',
    'display_date_format' => 'M d, Y',
    'display_datetime_format' => 'M d, Y g:i A',
    
    // Paths
    'base_path' => dirname(__DIR__),
    'upload_path' => dirname(__DIR__) . '/uploads',
    'backup_path' => dirname(__DIR__) . '/backup',
    'log_path' => dirname(__DIR__) . '/logs',
    
    // URLs (adjust based on your server setup)
    'base_url' => 'http://localhost/kioskhelp',
    
    // Email (for notifications)
    'email_from' => 'noreply@kioskhelp.com',
    'email_from_name' => 'KioskHelp System',
    
    // Features
    'enable_registration' => true,
    'require_email_verification' => false,
    'enable_sms_notifications' => false,
    'enable_email_notifications' => true,
];
