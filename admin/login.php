<?php
/**
 * Admin Login Page
 */

session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminCode = $_POST['admin_code'] ?? '';
    
    // In a real system, this would be stored encrypted in the database
    $correctCode = '079777';
    
    if ($adminCode === $correctCode) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login_time'] = time();
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid admin code. Please try again.';
        // Log failed attempt
        error_log("Failed admin login attempt from IP: " . $_SERVER['REMOTE_ADDR']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/portal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="login-title">Admin Access</h1>
                <p class="login-subtitle">Enter admin code to continue</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form" id="adminLoginForm" data-validate>
                <div class="form-group">
                    <label for="admin_code" class="form-label">Admin Code</label>
                    <input 
                        type="password" 
                        id="admin_code" 
                        name="admin_code" 
                        class="form-control" 
                        placeholder="Enter 6-digit code"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        required
                        autofocus
                        style="font-size: 1.5rem; text-align: center; letter-spacing: 0.5rem;"
                    >
                    <div class="form-error"></div>
                </div>

                <button type="submit" class="btn btn-admin btn-large" style="width: 100%;">
                    <i class="fas fa-unlock"></i> Access Admin Panel
                </button>
            </form>

            <div class="back-to-home" style="margin-top: 2rem;">
                <a href="../index.php">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        // Only allow numeric input
        document.getElementById('admin_code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
