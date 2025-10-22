<?php
/**
 * Client Dashboard
 */

session_start();
require_once '../includes/Auth.php';
require_once '../includes/Database.php';

$auth = new Auth();

// Check if user is logged in
if (!$auth->isLoggedIn() || !$auth->hasRole('client')) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
$db = Database::getInstance();

// Get client stats
$clientId = $user['client_id'];
$stats = [
    'active_cases' => 0,
    'referrals' => 0,
    'appointments' => 0,
    'messages' => 0
];

try {
    $stats['active_cases'] = $db->fetch(
        "SELECT COUNT(*) as count FROM cases WHERE client_id = :client_id AND status IN ('open', 'active')",
        ['client_id' => $clientId]
    )['count'];
    
    $stats['referrals'] = $db->fetch(
        "SELECT COUNT(*) as count FROM referrals WHERE client_id = :client_id",
        ['client_id' => $clientId]
    )['count'];
    
    $stats['appointments'] = $db->fetch(
        "SELECT COUNT(*) as count FROM appointments WHERE client_id = :client_id AND status = 'scheduled' AND appointment_date >= CURDATE()",
        ['client_id' => $clientId]
    )['count'];
    
    $stats['messages'] = $db->fetch(
        "SELECT COUNT(*) as count FROM messages WHERE recipient_id = :user_id AND is_read = 0",
        ['user_id' => $user['id']]
    )['count'];
} catch (Exception $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
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
        <!-- Sidebar -->
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li class="active">
                    <a href="dashboard.php">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="assessment.php">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Assessment</span>
                    </a>
                </li>
                <li>
                    <a href="referrals.php">
                        <i class="fas fa-network-wired"></i>
                        <span>Referrals</span>
                    </a>
                </li>
                <li>
                    <a href="appointments.php">
                        <i class="fas fa-calendar-check"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="messages.php">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        <?php if ($stats['messages'] > 0): ?>
                            <span class="badge badge-error"><?php echo $stats['messages']; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="resources.php">
                        <i class="fas fa-book-open"></i>
                        <span>Resources</span>
                    </a>
                </li>
                <li>
                    <a href="tools.php">
                        <i class="fas fa-tools"></i>
                        <span>Self-Help Tools</span>
                    </a>
                </li>
                <li>
                    <a href="profile.php">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <?php if (isset($_SESSION['registration_success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo htmlspecialchars($_SESSION['registration_success']); 
                    unset($_SESSION['registration_success']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="page-header">
                <h1>Welcome to Your Dashboard</h1>
                <p>Access your services, track your progress, and find support</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['active_cases']; ?></div>
                        <div class="stat-label">Active Cases</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['referrals']; ?></div>
                        <div class="stat-label">Referrals</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['appointments']; ?></div>
                        <div class="stat-label">Upcoming Appointments</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['messages']; ?></div>
                        <div class="stat-label">Unread Messages</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="section">
                <h2>Quick Actions</h2>
                <div class="action-grid">
                    <a href="assessment.php" class="action-card">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Start Assessment</h3>
                        <p>Complete a needs assessment to connect with services</p>
                    </a>

                    <a href="resources.php" class="action-card">
                        <i class="fas fa-book-open"></i>
                        <h3>Browse Resources</h3>
                        <p>Find helpful guides, articles, and support materials</p>
                    </a>

                    <a href="tools.php" class="action-card">
                        <i class="fas fa-tools"></i>
                        <h3>Self-Help Tools</h3>
                        <p>Access budget planner, wellness tracker, and more</p>
                    </a>

                    <a href="appointments.php" class="action-card">
                        <i class="fas fa-calendar-plus"></i>
                        <h3>Schedule Appointment</h3>
                        <p>Book appointments with service providers</p>
                    </a>
                </div>
            </div>

            <!-- Need Help Section -->
            <div class="section">
                <div class="help-banner">
                    <div class="help-content">
                        <i class="fas fa-phone-volume"></i>
                        <div>
                            <h3>Need Immediate Help?</h3>
                            <p>If you're in crisis, help is available 24/7</p>
                        </div>
                    </div>
                    <div class="help-actions">
                        <a href="tel:988" class="btn btn-error">
                            <i class="fas fa-phone"></i> Crisis Line: 988
                        </a>
                        <a href="tel:911" class="btn btn-warning">
                            <i class="fas fa-exclamation-triangle"></i> Emergency: 911
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
