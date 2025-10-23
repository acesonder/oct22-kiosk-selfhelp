<?php
session_start();
require_once '../includes/Auth.php';
require_once '../includes/Database.php';

$auth = new Auth();

if (!$auth->isLoggedIn() || !$auth->hasRole('client')) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
$db = Database::getInstance();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $errors = [];
    $success = false;
    
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Validation
    if (empty($firstName)) {
        $errors[] = "First name is required";
    }
    if (empty($lastName)) {
        $errors[] = "Last name is required";
    }
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($errors)) {
        try {
            // Update user record
            $db->update('users',
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => $phone
                ],
                'id = :id',
                ['id' => $user['id']]
            );
            
            // Update session
            $_SESSION['user']['first_name'] = $firstName;
            $_SESSION['user']['last_name'] = $lastName;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            
            $success = "Profile updated successfully!";
            $user = $auth->getCurrentUser(); // Refresh user data
        } catch (Exception $e) {
            error_log("Profile update error: " . $e->getMessage());
            $errors[] = "Failed to update profile. Please try again.";
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $pwdErrors = [];
    $pwdSuccess = false;
    
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($currentPassword)) {
        $pwdErrors[] = "Current password is required";
    } elseif (!password_verify($currentPassword, $user['password_hash'])) {
        $pwdErrors[] = "Current password is incorrect";
    }
    
    if (empty($newPassword)) {
        $pwdErrors[] = "New password is required";
    } elseif (strlen($newPassword) < 8) {
        $pwdErrors[] = "New password must be at least 8 characters";
    }
    
    if ($newPassword !== $confirmPassword) {
        $pwdErrors[] = "New passwords do not match";
    }
    
    if (empty($pwdErrors)) {
        try {
            $db->update('users',
                ['password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)],
                'id = :id',
                ['id' => $user['id']]
            );
            
            $pwdSuccess = "Password changed successfully!";
        } catch (Exception $e) {
            error_log("Password change error: " . $e->getMessage());
            $pwdErrors[] = "Failed to change password. Please try again.";
        }
    }
}

// Get client details
$clientDetails = [];
try {
    $clientDetails = $db->fetch(
        "SELECT c.*, DATE_FORMAT(c.created_at, '%M %d, %Y') as registration_date
         FROM clients c 
         WHERE c.user_id = :user_id",
        ['user_id' => $user['id']]
    );
} catch (Exception $e) {
    error_log("Error fetching client details: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="dashboard-nav">
        <div class="nav-brand">
            <i class="fas fa-hands-helping"></i>
            <span>KioskHelp</span>
        </div>
        <div class="nav-user">
            <span>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</span>
            <a href="logout.php" class="btn btn-secondary btn-small">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>
    
    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>My Profile</h1>
                <p>Manage your account information and preferences</p>
            </div>

            <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php foreach ($errors as $error): ?>
                    <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Account Information -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <i class="fas fa-id-card"></i> Account Information
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                        <div>
                            <strong>Username:</strong><br>
                            <?php echo htmlspecialchars($user['username']); ?>
                        </div>
                        <div>
                            <strong>Role:</strong><br>
                            <?php echo ucfirst($user['role']); ?>
                        </div>
                        <?php if ($clientDetails): ?>
                        <div>
                            <strong>Client ID:</strong><br>
                            <?php echo htmlspecialchars($clientDetails['id']); ?>
                        </div>
                        <div>
                            <strong>Member Since:</strong><br>
                            <?php echo htmlspecialchars($clientDetails['registration_date']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Update Profile Form -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <i class="fas fa-user-edit"></i> Update Profile
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            <div class="form-group">
                                <label>First Name *</label>
                                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name *</label>
                                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-key"></i> Change Password
                </div>
                <div class="card-body">
                    <?php if (isset($pwdSuccess)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($pwdSuccess); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($pwdErrors) && !empty($pwdErrors)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php foreach ($pwdErrors as $error): ?>
                            <div><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Current Password *</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            <div class="form-group">
                                <label>New Password *</label>
                                <input type="password" name="new_password" class="form-control" required>
                                <small style="color: #666;">Must be at least 8 characters</small>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password *</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="change_password" class="btn btn-warning">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>
