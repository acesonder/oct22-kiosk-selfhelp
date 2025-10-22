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
    <title>Resources - KioskHelp</title>
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
            <div class="page-header"><h1>Resource Library</h1><p>Access guides, articles, and support materials</p></div>
            <div class="alert alert-info"><p><strong>Resource Library coming soon!</strong></p><p>Browse curated educational resources, guides, and support materials tailored to your needs.</p></div>
        </main>
    </div>
    <script src="../assets/js/main.js"></script>
</body>
</html>
