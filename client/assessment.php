<?php
/**
 * Client Assessment Page (Placeholder)
 */

session_start();
require_once '../includes/Auth.php';

$auth = new Auth();

if (!$auth->isLoggedIn() || !$auth->hasRole('client')) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment - KioskHelp</title>
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
                <h1>Needs Assessment</h1>
                <p>Help us understand your needs to connect you with the right services</p>
            </div>

            <div class="alert alert-info">
                <p><strong>Assessment feature coming soon!</strong></p>
                <p>This comprehensive assessment will evaluate your needs across multiple areas including housing, food security, healthcare, mental health, employment, legal assistance, transportation, and family services.</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clipboard-list"></i> Assessment Domains
                </div>
                <div class="card-body">
                    <ul>
                        <li><i class="fas fa-home"></i> <strong>Housing Stability</strong> - Current living situation and housing needs</li>
                        <li><i class="fas fa-utensils"></i> <strong>Food Security</strong> - Access to nutritious food</li>
                        <li><i class="fas fa-heartbeat"></i> <strong>Healthcare Access</strong> - Medical, dental, and vision care</li>
                        <li><i class="fas fa-brain"></i> <strong>Mental Health</strong> - Emotional wellbeing and support</li>
                        <li><i class="fas fa-briefcase"></i> <strong>Employment</strong> - Job status and training needs</li>
                        <li><i class="fas fa-gavel"></i> <strong>Legal Assistance</strong> - Legal issues and needs</li>
                        <li><i class="fas fa-bus"></i> <strong>Transportation</strong> - Mobility and transportation access</li>
                        <li><i class="fas fa-users"></i> <strong>Family Services</strong> - Childcare and family support</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
