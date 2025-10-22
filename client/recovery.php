<?php
/**
 * Password Recovery Page
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
$success = '';
$currentStep = 1;
$securityQuestion = '';
$recoveredUsername = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $step = $_POST['step'] ?? '1';
    
    if ($step === '1') {
        // Step 1: Verify identity
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $dob = $_POST['date_of_birth'] ?? '';
        
        if (empty($firstName) || empty($lastName) || empty($dob)) {
            $error = 'Please fill in all fields.';
        } else {
            $result = $auth->verifyRecoveryIdentity($firstName, $lastName, $dob);
            
            if ($result['success']) {
                $currentStep = 2;
                $securityQuestion = $result['security_question'];
            } else {
                $error = $result['message'];
            }
        }
    } elseif ($step === '2') {
        // Step 2: Verify security answer
        $answer = trim($_POST['security_answer'] ?? '');
        
        if (empty($answer)) {
            $error = 'Please provide an answer to the security question.';
            $currentStep = 2;
            $securityQuestion = $_SESSION['recovery_question'] ?? '';
        } else {
            $result = $auth->verifySecurityAnswer($answer);
            
            if ($result['success']) {
                $currentStep = 3;
                $recoveredUsername = $result['username'];
            } else {
                $error = $result['message'];
                $currentStep = 2;
                $securityQuestion = $_SESSION['recovery_question'] ?? '';
            }
        }
    } elseif ($step === '3') {
        // Step 3: Reset password
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($newPassword) || empty($confirmPassword)) {
            $error = 'Please fill in both password fields.';
            $currentStep = 3;
            $recoveredUsername = $_SESSION['recovery_username'] ?? '';
        } else {
            $result = $auth->resetPassword($newPassword, $confirmPassword);
            
            if ($result['success']) {
                $_SESSION['registration_success'] = 'Password reset successful! You are now logged in.';
                header('Location: dashboard.php');
                exit;
            } else {
                $error = $result['message'];
                $currentStep = 3;
                $recoveredUsername = $_SESSION['recovery_username'] ?? '';
            }
        }
    }
}

// Restore session data if available
if ($currentStep === 1) {
    if (isset($_SESSION['recovery_question'])) {
        $currentStep = 2;
        $securityQuestion = $_SESSION['recovery_question'];
    }
    if (isset($_SESSION['recovery_username'])) {
        $currentStep = 3;
        $recoveredUsername = $_SESSION['recovery_username'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Recovery - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/portal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h1 class="login-title">Account Recovery</h1>
                <p class="login-subtitle">Recover your username and reset your password</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <span class="step-dot <?php echo $currentStep >= 1 ? 'active' : ''; ?> <?php echo $currentStep > 1 ? 'completed' : ''; ?>"></span>
                <span class="step-dot <?php echo $currentStep >= 2 ? 'active' : ''; ?> <?php echo $currentStep > 2 ? 'completed' : ''; ?>"></span>
                <span class="step-dot <?php echo $currentStep >= 3 ? 'active' : ''; ?>"></span>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="recovery-steps">
                <!-- Step 1: Verify Identity -->
                <div class="step <?php echo $currentStep === 1 ? 'active' : ''; ?>" id="step1">
                    <h3>Step 1: Verify Your Identity</h3>
                    <p>Please provide your personal information to verify your identity.</p>
                    
                    <form method="POST" class="login-form" data-validate>
                        <input type="hidden" name="step" value="1">
                        
                        <div class="form-group">
                            <label for="first_name" class="form-label">First Name</label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                class="form-control" 
                                required
                                autofocus
                            >
                            <div class="form-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                class="form-control" 
                                required
                            >
                            <div class="form-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input 
                                type="date" 
                                id="date_of_birth" 
                                name="date_of_birth" 
                                class="form-control" 
                                max="<?php echo date('Y-m-d'); ?>"
                                required
                            >
                            <div class="form-error"></div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">
                            Continue <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- Step 2: Security Question -->
                <div class="step <?php echo $currentStep === 2 ? 'active' : ''; ?>" id="step2">
                    <h3>Step 2: Security Question</h3>
                    <p>Please answer your security question.</p>
                    
                    <form method="POST" class="login-form" data-validate>
                        <input type="hidden" name="step" value="2">
                        
                        <div class="form-group">
                            <label class="form-label">Security Question:</label>
                            <p style="background-color: var(--bg-secondary); padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1rem;">
                                <strong><?php echo htmlspecialchars($securityQuestion); ?></strong>
                            </p>
                        </div>

                        <div class="form-group">
                            <label for="security_answer" class="form-label">Your Answer</label>
                            <input 
                                type="text" 
                                id="security_answer" 
                                name="security_answer" 
                                class="form-control" 
                                placeholder="Enter your answer"
                                required
                                <?php echo $currentStep === 2 ? 'autofocus' : ''; ?>
                            >
                            <div class="form-error"></div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">
                            Verify Answer <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- Step 3: Reset Password -->
                <div class="step <?php echo $currentStep === 3 ? 'active' : ''; ?>" id="step3">
                    <h3>Step 3: Reset Your Password</h3>
                    
                    <?php if ($recoveredUsername): ?>
                        <div class="alert alert-success">
                            <strong>Your username is:</strong> <?php echo htmlspecialchars($recoveredUsername); ?>
                        </div>
                    <?php endif; ?>
                    
                    <p>Please create a new password for your account.</p>
                    
                    <form method="POST" class="login-form" data-validate>
                        <input type="hidden" name="step" value="3">
                        
                        <div class="form-group">
                            <label for="new_password" class="form-label">New Password</label>
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                class="form-control" 
                                placeholder="At least 8 characters"
                                minlength="8"
                                required
                                <?php echo $currentStep === 3 ? 'autofocus' : ''; ?>
                            >
                            <small class="form-text">Minimum 8 characters</small>
                            <div class="form-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="form-control" 
                                placeholder="Re-enter your new password"
                                required
                            >
                            <div class="form-error"></div>
                        </div>

                        <button type="submit" class="btn btn-success btn-large" style="width: 100%;">
                            <i class="fas fa-check"></i> Reset Password & Login
                        </button>
                    </form>
                </div>
            </div>

            <div class="back-to-home" style="margin-top: 2rem;">
                <a href="login.php">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
