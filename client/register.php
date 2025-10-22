<?php
/**
 * Client Registration Page
 */

session_start();
require_once '../includes/Auth.php';

$auth = new Auth();

// If already logged in, redirect to dashboard
if ($auth->isLoggedIn() && $auth->hasRole('client')) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$usernamePreview = '';

// Security questions
$securityQuestions = [
    "What was the name of your first pet?",
    "What is your mother's maiden name?",
    "What city were you born in?",
    "What was the name of your elementary school?",
    "What is your favorite color?",
    "What was the make of your first car?",
    "What is your favorite food?"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'date_of_birth' => $_POST['date_of_birth'] ?? '',
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
        'security_question' => $_POST['security_question'] ?? '',
        'security_answer' => trim($_POST['security_answer'] ?? ''),
        'preferred_contact' => $_POST['preferred_contact'] ?? 'app'
    ];
    
    // Validate required fields
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['date_of_birth']) || 
        empty($data['password']) || empty($data['security_question']) || empty($data['security_answer'])) {
        $error = 'Please fill in all required fields.';
    } elseif (!isset($_POST['consent_terms']) || !isset($_POST['consent_privacy'])) {
        $error = 'You must agree to the terms and privacy policy to register.';
    } else {
        $result = $auth->registerClient($data);
        
        if ($result['success']) {
            $_SESSION['registration_success'] = "Registration successful! Your username is: " . $result['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
    
    // Generate username preview if we have the required fields
    if (!empty($data['first_name']) && !empty($data['last_name']) && !empty($data['date_of_birth'])) {
        $usernamePreview = $auth->generateUsername($data['first_name'], $data['last_name'], $data['date_of_birth']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Registration - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/portal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="registration-container">
        <div class="registration-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="login-title">Create Your Account</h1>
                <p class="login-subtitle">Join KioskHelp to access support services</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form" id="registrationForm" data-validate>
                <!-- Personal Information -->
                <h3 style="margin-top: 1.5rem; margin-bottom: 1rem;">Personal Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                            required
                            onchange="updateUsernamePreview()"
                        >
                        <div class="form-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                            required
                            onchange="updateUsernamePreview()"
                        >
                        <div class="form-error"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                    <input 
                        type="date" 
                        id="date_of_birth" 
                        name="date_of_birth" 
                        class="form-control" 
                        value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? ''); ?>"
                        max="<?php echo date('Y-m-d'); ?>"
                        required
                        onchange="updateUsernamePreview()"
                    >
                    <div class="form-error"></div>
                </div>

                <!-- Username Preview -->
                <div id="usernamePreview" class="username-preview" style="<?php echo $usernamePreview ? '' : 'display: none;'; ?>">
                    <small>Your username will be:</small><br>
                    <strong id="previewUsername"><?php echo htmlspecialchars($usernamePreview); ?></strong>
                </div>

                <!-- Contact Information -->
                <h3 style="margin-top: 1.5rem; margin-bottom: 1rem;">Contact Information</h3>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email (Optional)</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="your.email@example.com"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                    <small class="form-text">For appointment reminders and notifications</small>
                    <div class="form-error"></div>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone (Optional)</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        class="form-control" 
                        placeholder="(555) 555-5555"
                        value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                    >
                    <div class="form-error"></div>
                </div>

                <div class="form-group">
                    <label for="preferred_contact" class="form-label">Preferred Contact Method</label>
                    <select id="preferred_contact" name="preferred_contact" class="form-control">
                        <option value="app" <?php echo ($_POST['preferred_contact'] ?? 'app') === 'app' ? 'selected' : ''; ?>>App Notifications</option>
                        <option value="email" <?php echo ($_POST['preferred_contact'] ?? '') === 'email' ? 'selected' : ''; ?>>Email</option>
                        <option value="sms" <?php echo ($_POST['preferred_contact'] ?? '') === 'sms' ? 'selected' : ''; ?>>SMS/Text</option>
                    </select>
                </div>

                <!-- Security -->
                <h3 style="margin-top: 1.5rem; margin-bottom: 1rem;">Security</h3>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password *</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="At least 8 characters"
                        minlength="8"
                        required
                    >
                    <small class="form-text">Minimum 8 characters</small>
                    <div class="form-error"></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password *</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-control" 
                        placeholder="Re-enter your password"
                        required
                    >
                    <div class="form-error"></div>
                </div>

                <div class="security-questions">
                    <div class="form-group">
                        <label for="security_question" class="form-label">Security Question *</label>
                        <select id="security_question" name="security_question" class="form-control" required>
                            <option value="">Choose a question...</option>
                            <?php foreach ($securityQuestions as $question): ?>
                                <option value="<?php echo htmlspecialchars($question); ?>" 
                                    <?php echo ($_POST['security_question'] ?? '') === $question ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($question); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text">Used for password recovery</small>
                        <div class="form-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="security_answer" class="form-label">Security Answer *</label>
                        <input 
                            type="text" 
                            id="security_answer" 
                            name="security_answer" 
                            class="form-control" 
                            placeholder="Enter your answer"
                            value="<?php echo htmlspecialchars($_POST['security_answer'] ?? ''); ?>"
                            required
                        >
                        <div class="form-error"></div>
                    </div>
                </div>

                <!-- Consent -->
                <div class="consent-section">
                    <h4 style="margin-bottom: 1rem;">Consent & Privacy</h4>
                    
                    <div class="consent-item">
                        <input 
                            type="checkbox" 
                            id="consent_terms" 
                            name="consent_terms" 
                            required
                        >
                        <label for="consent_terms">
                            I agree to the <a href="#" target="_blank">Terms of Service</a> *
                        </label>
                    </div>

                    <div class="consent-item">
                        <input 
                            type="checkbox" 
                            id="consent_privacy" 
                            name="consent_privacy" 
                            required
                        >
                        <label for="consent_privacy">
                            I understand and accept the <a href="#" target="_blank">Privacy Policy</a> *
                        </label>
                    </div>

                    <div class="consent-item">
                        <input 
                            type="checkbox" 
                            id="consent_data_sharing" 
                            name="consent_data_sharing"
                        >
                        <label for="consent_data_sharing">
                            I consent to sharing my information with service providers I am referred to
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-large" style="width: 100%; margin-top: 1.5rem;">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="login-footer">
                <p>Already have an account?</p>
                <a href="login.php" class="btn btn-outline" style="width: 100%; margin-top: 1rem;">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            </div>

            <div class="back-to-home">
                <a href="../index.php">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        function updateUsernamePreview() {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const dob = document.getElementById('date_of_birth').value;
            
            if (firstName && lastName && dob) {
                // Generate username preview via AJAX
                fetch('../api/generate_username.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ first_name: firstName, last_name: lastName, date_of_birth: dob })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.username) {
                        document.getElementById('previewUsername').textContent = data.username;
                        document.getElementById('usernamePreview').style.display = 'block';
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            // You can add visual feedback here
        });
    </script>
</body>
</html>
