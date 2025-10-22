<?php
/**
 * Authentication and Session Management
 */

require_once __DIR__ . '/Database.php';

class Auth {
    private $db;
    private $config;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../config/app.php';
        $this->startSession();
    }
    
    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($this->config['session_name']);
            session_start();
        }
    }
    
    /**
     * Generate unique username from name and DOB
     * Format: MICBRO050684 (First 3 of first name + First 3 of last name + MMDDYY)
     */
    public function generateUsername($firstName, $lastName, $dob) {
        $firstName = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $firstName), 0, 3));
        $lastName = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $lastName), 0, 3));
        
        $dobParts = date_parse($dob);
        $dateStr = sprintf('%02d%02d%02d', $dobParts['month'], $dobParts['day'], $dobParts['year'] % 100);
        
        $username = $firstName . $lastName . $dateStr;
        
        // Check if username exists and add suffix if needed
        $originalUsername = $username;
        $suffix = 1;
        while ($this->usernameExists($username)) {
            $username = $originalUsername . $suffix;
            $suffix++;
        }
        
        return $username;
    }
    
    public function usernameExists($username) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $result = $this->db->fetch($sql, ['username' => $username]);
        return $result['count'] > 0;
    }
    
    /**
     * Register a new client
     */
    public function registerClient($data) {
        try {
            // Generate username
            $username = $this->generateUsername($data['first_name'], $data['last_name'], $data['date_of_birth']);
            
            // Validate password
            if (strlen($data['password']) < $this->config['password_min_length']) {
                return [
                    'success' => false,
                    'message' => "Password must be at least {$this->config['password_min_length']} characters long."
                ];
            }
            
            if ($data['password'] !== $data['confirm_password']) {
                return ['success' => false, 'message' => 'Passwords do not match.'];
            }
            
            $this->db->beginTransaction();
            
            // Insert user
            $userId = $this->db->insert('users', [
                'username' => $username,
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'email' => $data['email'] ?? null,
                'role' => 'client',
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? null,
                'is_active' => 1
            ]);
            
            // Insert client details
            $clientId = $this->db->insert('clients', [
                'user_id' => $userId,
                'date_of_birth' => $data['date_of_birth'],
                'security_question' => $data['security_question'],
                'security_answer_hash' => password_hash(strtolower(trim($data['security_answer'])), PASSWORD_DEFAULT),
                'preferred_contact' => $data['preferred_contact'] ?? 'app'
            ]);
            
            $this->db->commit();
            
            // Auto-login
            $this->login($username, $data['password']);
            
            return [
                'success' => true,
                'message' => 'Registration successful!',
                'username' => $username,
                'user_id' => $userId
            ];
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Registration Error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }
    
    /**
     * Login user
     */
    public function login($username, $password) {
        $sql = "SELECT u.*, c.id as client_id FROM users u 
                LEFT JOIN clients c ON u.id = c.user_id 
                WHERE u.username = :username AND u.is_active = 1";
        
        $user = $this->db->fetch($sql, ['username' => $username]);
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }
        
        // Update last login
        $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $user['id']]);
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['client_id'] = $user['client_id'];
        $_SESSION['logged_in'] = true;
        
        return ['success' => true, 'role' => $user['role']];
    }
    
    /**
     * Logout user
     */
    public function logout() {
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Get current user
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'client_id' => $_SESSION['client_id'] ?? null
        ];
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($role) {
        return $this->isLoggedIn() && $_SESSION['role'] === $role;
    }
    
    /**
     * Password recovery - verify identity
     */
    public function verifyRecoveryIdentity($firstName, $lastName, $dob) {
        $sql = "SELECT u.*, c.security_question, c.security_answer_hash 
                FROM users u 
                JOIN clients c ON u.id = c.user_id 
                WHERE u.first_name = :first_name 
                AND u.last_name = :last_name 
                AND c.date_of_birth = :dob 
                AND u.is_active = 1";
        
        $user = $this->db->fetch($sql, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'dob' => $dob
        ]);
        
        if ($user) {
            // Store recovery session temporarily
            $_SESSION['recovery_user_id'] = $user['id'];
            $_SESSION['recovery_username'] = $user['username'];
            $_SESSION['recovery_question'] = $user['security_question'];
            $_SESSION['recovery_answer_hash'] = $user['security_answer_hash'];
            
            return [
                'success' => true,
                'security_question' => $user['security_question']
            ];
        }
        
        return ['success' => false, 'message' => 'No matching account found.'];
    }
    
    /**
     * Verify security answer and show username
     */
    public function verifySecurityAnswer($answer) {
        if (!isset($_SESSION['recovery_answer_hash'])) {
            return ['success' => false, 'message' => 'Session expired. Please start over.'];
        }
        
        if (password_verify(strtolower(trim($answer)), $_SESSION['recovery_answer_hash'])) {
            return [
                'success' => true,
                'username' => $_SESSION['recovery_username']
            ];
        }
        
        return ['success' => false, 'message' => 'Incorrect answer.'];
    }
    
    /**
     * Reset password
     */
    public function resetPassword($newPassword, $confirmPassword) {
        if (!isset($_SESSION['recovery_user_id'])) {
            return ['success' => false, 'message' => 'Session expired. Please start over.'];
        }
        
        if (strlen($newPassword) < $this->config['password_min_length']) {
            return [
                'success' => false,
                'message' => "Password must be at least {$this->config['password_min_length']} characters long."
            ];
        }
        
        if ($newPassword !== $confirmPassword) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }
        
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->db->update('users', ['password_hash' => $passwordHash], 'id = :id', ['id' => $_SESSION['recovery_user_id']]);
        
        $username = $_SESSION['recovery_username'];
        
        // Clear recovery session
        unset($_SESSION['recovery_user_id']);
        unset($_SESSION['recovery_username']);
        unset($_SESSION['recovery_question']);
        unset($_SESSION['recovery_answer_hash']);
        
        // Auto-login
        $this->login($username, $newPassword);
        
        return ['success' => true, 'message' => 'Password reset successful!'];
    }
}
