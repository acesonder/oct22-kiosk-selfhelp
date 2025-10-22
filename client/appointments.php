<?php
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
    <title>Appointments - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="dashboard-nav">
        <div class="nav-brand"><i class="fas fa-hands-helping"></i><span>KioskHelp</span></div>
        <div class="nav-user">
            <span>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</span>
            <a href="logout.php" class="btn btn-secondary btn-small"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>
        <main class="main-content">
            <div class="page-header"><h1>My Appointments</h1><p>Schedule and manage appointments with service providers</p></div>
            <div class="alert alert-info"><p><strong>Appointments feature coming soon!</strong></p><p>Schedule appointments with service providers and receive automated reminders.</p></div>
        </main>
    </div>
    <script src="../assets/js/main.js"></script>
</body>
</html>
