<?php
/**
 * Admin Dashboard
 */

session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/Database.php';
require_once '../includes/DatabaseInstaller.php';

$message = '';
$messageType = '';

// Get database stats if connected
$stats = null;
$dbConnected = false;

try {
    $db = Database::getInstance();
    if ($db->testConnection()) {
        $dbConnected = true;
        $installer = new DatabaseInstaller();
        $result = $installer->getStats();
        if ($result['success']) {
            $stats = $result['stats'];
        }
    }
} catch (Exception $e) {
    $dbConnected = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="dashboard-nav" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="nav-brand">
            <i class="fas fa-shield-alt"></i>
            <span>KioskHelp Admin</span>
        </div>
        <div class="nav-user">
            <span>Administrator</span>
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
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="database.php">
                        <i class="fas fa-database"></i>
                        <span>Database Tools</span>
                    </a>
                </li>
                <li>
                    <a href="users.php">
                        <i class="fas fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li>
                    <a href="themes.php">
                        <i class="fas fa-palette"></i>
                        <span>Themes</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <a href="logs.php">
                        <i class="fas fa-file-alt"></i>
                        <span>Error Logs</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Admin Dashboard</h1>
                <p>System management and configuration</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- System Status -->
            <div class="section">
                <h2>System Status</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: <?php echo $dbConnected ? 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)' : 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'; ?>;">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $dbConnected ? 'Connected' : 'Not Connected'; ?></div>
                            <div class="stat-label">Database Status</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-code-branch"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">1.0.0</div>
                            <div class="stat-label">App Version</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo PHP_VERSION; ?></div>
                            <div class="stat-label">PHP Version</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo date('H:i'); ?></div>
                            <div class="stat-label">Server Time</div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($dbConnected && $stats): ?>
            <!-- Database Statistics -->
            <div class="section">
                <h2>Database Statistics</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $stats['users']; ?></div>
                            <div class="stat-label">Total Users</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $stats['clients']; ?></div>
                            <div class="stat-label">Clients</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $stats['cases']; ?></div>
                            <div class="stat-label">Cases</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $stats['referrals']; ?></div>
                            <div class="stat-label">Referrals</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $stats['appointments']; ?></div>
                            <div class="stat-label">Appointments</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $stats['messages']; ?></div>
                            <div class="stat-label">Messages</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $stats['providers']; ?></div>
                            <div class="stat-label">Service Providers</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $stats['assessments']; ?></div>
                            <div class="stat-label">Assessments</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="section">
                <h2>Quick Actions</h2>
                <div class="action-grid">
                    <?php if (!$dbConnected): ?>
                    <a href="database.php" class="action-card" style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);">
                        <i class="fas fa-database" style="color: #f5576c;"></i>
                        <h3>Initialize Database</h3>
                        <p>Set up the database for first time use</p>
                    </a>
                    <?php endif; ?>

                    <a href="users.php" class="action-card">
                        <i class="fas fa-user-plus"></i>
                        <h3>Create User</h3>
                        <p>Add new users with any role</p>
                    </a>

                    <a href="themes.php" class="action-card">
                        <i class="fas fa-palette"></i>
                        <h3>Customize Theme</h3>
                        <p>Change colors and appearance</p>
                    </a>

                    <a href="settings.php" class="action-card">
                        <i class="fas fa-cog"></i>
                        <h3>System Settings</h3>
                        <p>Configure site name, logo, and more</p>
                    </a>

                    <a href="database.php" class="action-card">
                        <i class="fas fa-download"></i>
                        <h3>Backup Database</h3>
                        <p>Create a backup of all data</p>
                    </a>

                    <a href="logs.php" class="action-card">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>View Error Logs</h3>
                        <p>Monitor system errors and issues</p>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
