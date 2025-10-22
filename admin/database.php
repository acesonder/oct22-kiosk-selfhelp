<?php
/**
 * Admin Database Management
 */

session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/DatabaseInstaller.php';

$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'initialize') {
        $host = $_POST['db_host'] ?? 'localhost';
        $username = $_POST['db_username'] ?? '';
        $password = $_POST['db_password'] ?? '';
        $database = $_POST['db_database'] ?? '';
        
        if (empty($username) || empty($database)) {
            $message = 'Please provide database username and database name.';
            $messageType = 'error';
        } else {
            $installer = new DatabaseInstaller();
            $result = $installer->install($host, $username, $password, $database);
            
            if ($result['success']) {
                // Save database config
                $configContent = "<?php\n";
                $configContent .= "/**\n * Database Configuration\n */\n\n";
                $configContent .= "return [\n";
                $configContent .= "    'host' => '{$host}',\n";
                $configContent .= "    'database' => '{$database}',\n";
                $configContent .= "    'username' => '{$username}',\n";
                $configContent .= "    'password' => '{$password}',\n";
                $configContent .= "    'charset' => 'utf8mb4',\n";
                $configContent .= "    'collation' => 'utf8mb4_unicode_ci',\n";
                $configContent .= "    'port' => 3306,\n";
                $configContent .= "    'options' => [\n";
                $configContent .= "        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,\n";
                $configContent .= "        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n";
                $configContent .= "        PDO::ATTR_EMULATE_PREPARES => false,\n";
                $configContent .= "    ]\n";
                $configContent .= "];\n";
                
                file_put_contents('../config/database.php', $configContent);
                
                $message = $result['message'];
                $messageType = 'success';
            } else {
                $message = $result['message'];
                $messageType = 'error';
            }
        }
    } elseif ($action === 'backup') {
        $filename = '../backup/backup_' . date('Y-m-d_His') . '.sql';
        $installer = new DatabaseInstaller();
        $result = $installer->backup($filename);
        
        if ($result['success']) {
            $message = 'Backup created successfully: ' . basename($result['file']);
            $messageType = 'success';
        } else {
            $message = $result['message'];
            $messageType = 'error';
        }
    } elseif ($action === 'test_connection') {
        $host = $_POST['db_host'] ?? 'localhost';
        $username = $_POST['db_username'] ?? '';
        $password = $_POST['db_password'] ?? '';
        $database = $_POST['db_database'] ?? '';
        
        $installer = new DatabaseInstaller();
        $result = $installer->testConnection($host, $username, $password, $database);
        
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
    }
}

// Check if database.php exists
$dbConfigExists = file_exists('../config/database.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Tools - Admin - KioskHelp</title>
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
                <li>
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
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
                <h1>Database Tools</h1>
                <p>Initialize, backup, and manage the database</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Database Status -->
            <div class="section">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i> Database Status
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Configuration File:</strong> 
                            <?php if ($dbConfigExists): ?>
                                <span class="badge badge-success">Exists</span>
                            <?php else: ?>
                                <span class="badge badge-error">Not Found</span>
                            <?php endif; ?>
                        </p>
                        <p>
                            <strong>Status:</strong> 
                            <?php 
                            try {
                                require_once '../includes/Database.php';
                                $db = Database::getInstance();
                                if ($db->testConnection()) {
                                    echo '<span class="badge badge-success">Connected</span>';
                                } else {
                                    echo '<span class="badge badge-error">Not Connected</span>';
                                }
                            } catch (Exception $e) {
                                echo '<span class="badge badge-error">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Initialize Database -->
            <div class="section">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-database"></i> Initialize / Reinstall Database
                    </div>
                    <div class="card-body">
                        <p>This will create or recreate all database tables. <strong>Warning:</strong> This will drop existing tables and all data!</p>
                        
                        <form method="POST" id="initForm">
                            <input type="hidden" name="action" value="initialize">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="db_host" class="form-label">Database Host</label>
                                    <input type="text" id="db_host" name="db_host" class="form-control" value="localhost" required>
                                </div>

                                <div class="form-group">
                                    <label for="db_database" class="form-label">Database Name</label>
                                    <input type="text" id="db_database" name="db_database" class="form-control" placeholder="kioskhelp" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="db_username" class="form-label">Database Username</label>
                                    <input type="text" id="db_username" name="db_username" class="form-control" placeholder="root" required>
                                </div>

                                <div class="form-group">
                                    <label for="db_password" class="form-label">Database Password</label>
                                    <input type="password" id="db_password" name="db_password" class="form-control" placeholder="Leave blank if none">
                                </div>
                            </div>

                            <div style="display: flex; gap: 1rem;">
                                <button type="button" onclick="testConnection()" class="btn btn-secondary">
                                    <i class="fas fa-plug"></i> Test Connection
                                </button>
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure? This will drop all existing tables and data!')">
                                    <i class="fas fa-database"></i> Initialize Database
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Backup Database -->
            <?php if ($dbConfigExists): ?>
            <div class="section">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-download"></i> Backup Database
                    </div>
                    <div class="card-body">
                        <p>Create a complete backup of the database including all tables and data.</p>
                        
                        <form method="POST" onsubmit="return confirm('Create database backup?')">
                            <input type="hidden" name="action" value="backup">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-download"></i> Create Backup
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Existing Backups -->
            <div class="section">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-archive"></i> Existing Backups
                    </div>
                    <div class="card-body">
                        <?php
                        $backupDir = '../backup/';
                        $backups = glob($backupDir . '*.sql');
                        
                        if (empty($backups)):
                        ?>
                            <p class="text-center" style="color: var(--text-secondary);">No backups found</p>
                        <?php else: ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Size</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_reverse($backups) as $backup): ?>
                                    <tr>
                                        <td><?php echo basename($backup); ?></td>
                                        <td><?php echo number_format(filesize($backup) / 1024, 2); ?> KB</td>
                                        <td><?php echo date('Y-m-d H:i:s', filemtime($backup)); ?></td>
                                        <td>
                                            <a href="<?php echo $backup; ?>" download class="btn btn-small btn-primary">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        function testConnection() {
            const form = document.getElementById('initForm');
            const formData = new FormData(form);
            formData.set('action', 'test_connection');
            
            showLoading();
            
            fetch('database.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                hideLoading();
                // Reload page to show result
                location.reload();
            })
            .catch(error => {
                hideLoading();
                showAlert('Error testing connection', 'error');
            });
        }
    </script>
</body>
</html>
