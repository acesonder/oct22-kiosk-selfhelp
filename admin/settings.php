<?php
/**
 * Admin System Settings
 */

session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/Database.php';

$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_settings') {
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            // Update each setting
            foreach ($_POST as $key => $value) {
                if ($key !== 'action' && strpos($key, 'config_') === 0) {
                    $config_key = substr($key, 7); // Remove 'config_' prefix
                    $stmt = $conn->prepare("UPDATE system_config SET config_value = ? WHERE config_key = ?");
                    $stmt->execute([$value, $config_key]);
                }
            }
            
            $message = 'Settings updated successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error updating settings: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif ($action === 'add_setting') {
        $config_key = trim($_POST['new_config_key'] ?? '');
        $config_value = trim($_POST['new_config_value'] ?? '');
        $config_type = $_POST['new_config_type'] ?? 'string';
        $description = trim($_POST['new_description'] ?? '');
        
        if (empty($config_key)) {
            $message = 'Configuration key is required.';
            $messageType = 'error';
        } else {
            try {
                $db = Database::getInstance();
                $conn = $db->getConnection();
                
                // Check if key already exists
                $stmt = $conn->prepare("SELECT id FROM system_config WHERE config_key = ?");
                $stmt->execute([$config_key]);
                if ($stmt->fetch()) {
                    $message = 'Configuration key already exists.';
                    $messageType = 'error';
                } else {
                    $stmt = $conn->prepare("INSERT INTO system_config (config_key, config_value, config_type, description) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$config_key, $config_value, $config_type, $description]);
                    
                    $message = 'Setting added successfully!';
                    $messageType = 'success';
                }
            } catch (Exception $e) {
                $message = 'Error adding setting: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    } elseif ($action === 'delete_setting') {
        $config_id = (int)($_POST['config_id'] ?? 0);
        
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("DELETE FROM system_config WHERE id = ?");
            $stmt->execute([$config_id]);
            
            $message = 'Setting deleted successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error deleting setting: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Fetch settings
$settings = [];
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $stmt = $conn->query("SELECT * FROM system_config ORDER BY config_key");
    $settings = $stmt->fetchAll();
} catch (Exception $e) {
    $message = 'Error fetching settings: ' . $e->getMessage();
    $messageType = 'error';
}

// Group settings by category
$general_settings = [];
$feature_settings = [];
$other_settings = [];

foreach ($settings as $setting) {
    $key = $setting['config_key'];
    if (in_array($key, ['site_name', 'company_name', 'company_logo', 'admin_code', 'default_theme', 'app_version', 'database_version'])) {
        $general_settings[] = $setting;
    } elseif (in_array($key, ['enable_sms', 'enable_email'])) {
        $feature_settings[] = $setting;
    } else {
        $other_settings[] = $setting;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Admin - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .settings-section {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .settings-section h3 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: #667eea;
        }
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .setting-item:last-child {
            border-bottom: none;
        }
        .setting-info {
            flex: 1;
        }
        .setting-label {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .setting-description {
            font-size: 0.875rem;
            color: #666;
        }
        .setting-control {
            min-width: 300px;
        }
        .setting-control input,
        .setting-control select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 30px;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .toggle-slider {
            background-color: #43e97b;
        }
        input:checked + .toggle-slider:before {
            transform: translateX(30px);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
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
                <li class="active">
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
                <h1>System Settings</h1>
                <p>Configure your application settings</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="action" value="update_settings">

                <!-- General Settings -->
                <?php if (!empty($general_settings)): ?>
                <div class="settings-section">
                    <h3><i class="fas fa-cog"></i> General Settings</h3>
                    <?php foreach ($general_settings as $setting): ?>
                        <div class="setting-item">
                            <div class="setting-info">
                                <div class="setting-label"><?php echo ucwords(str_replace('_', ' ', $setting['config_key'])); ?></div>
                                <?php if ($setting['description']): ?>
                                    <div class="setting-description"><?php echo htmlspecialchars($setting['description']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="setting-control">
                                <?php if ($setting['config_key'] === 'default_theme'): ?>
                                    <select name="config_<?php echo $setting['config_key']; ?>">
                                        <option value="light" <?php echo $setting['config_value'] === 'light' ? 'selected' : ''; ?>>Light</option>
                                        <option value="dark" <?php echo $setting['config_value'] === 'dark' ? 'selected' : ''; ?>>Dark</option>
                                        <option value="compassion" <?php echo $setting['config_value'] === 'compassion' ? 'selected' : ''; ?>>Compassion</option>
                                        <option value="hope" <?php echo $setting['config_value'] === 'hope' ? 'selected' : ''; ?>>Hope</option>
                                    </select>
                                <?php else: ?>
                                    <input type="text" name="config_<?php echo $setting['config_key']; ?>" value="<?php echo htmlspecialchars($setting['config_value']); ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Feature Settings -->
                <?php if (!empty($feature_settings)): ?>
                <div class="settings-section">
                    <h3><i class="fas fa-toggle-on"></i> Features</h3>
                    <?php foreach ($feature_settings as $setting): ?>
                        <div class="setting-item">
                            <div class="setting-info">
                                <div class="setting-label"><?php echo ucwords(str_replace('_', ' ', $setting['config_key'])); ?></div>
                                <?php if ($setting['description']): ?>
                                    <div class="setting-description"><?php echo htmlspecialchars($setting['description']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="setting-control">
                                <label class="toggle-switch">
                                    <input type="hidden" name="config_<?php echo $setting['config_key']; ?>" value="false">
                                    <input type="checkbox" name="config_<?php echo $setting['config_key']; ?>" value="true" <?php echo $setting['config_value'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Other Settings -->
                <?php if (!empty($other_settings)): ?>
                <div class="settings-section">
                    <h3><i class="fas fa-sliders-h"></i> Additional Settings</h3>
                    <?php foreach ($other_settings as $setting): ?>
                        <div class="setting-item">
                            <div class="setting-info">
                                <div class="setting-label"><?php echo ucwords(str_replace('_', ' ', $setting['config_key'])); ?></div>
                                <?php if ($setting['description']): ?>
                                    <div class="setting-description"><?php echo htmlspecialchars($setting['description']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="setting-control">
                                <?php if ($setting['config_type'] === 'boolean'): ?>
                                    <label class="toggle-switch">
                                        <input type="hidden" name="config_<?php echo $setting['config_key']; ?>" value="false">
                                        <input type="checkbox" name="config_<?php echo $setting['config_key']; ?>" value="true" <?php echo $setting['config_value'] === 'true' ? 'checked' : ''; ?>>
                                        <span class="toggle-slider"></span>
                                    </label>
                                <?php else: ?>
                                    <input type="text" name="config_<?php echo $setting['config_key']; ?>" value="<?php echo htmlspecialchars($setting['config_value']); ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="openAddModal()" class="btn btn-secondary">
                        <i class="fas fa-plus"></i> Add Setting
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </main>
    </div>

    <!-- Add Setting Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2 style="margin-top: 0;">Add New Setting</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_setting">
                
                <div class="form-group">
                    <label for="new_config_key">Configuration Key *</label>
                    <input type="text" id="new_config_key" name="new_config_key" required placeholder="e.g., max_upload_size">
                </div>
                
                <div class="form-group">
                    <label for="new_config_value">Value</label>
                    <input type="text" id="new_config_value" name="new_config_value">
                </div>
                
                <div class="form-group">
                    <label for="new_config_type">Type</label>
                    <select id="new_config_type" name="new_config_type">
                        <option value="string">String</option>
                        <option value="boolean">Boolean</option>
                        <option value="integer">Integer</option>
                        <option value="json">JSON</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="new_description">Description</label>
                    <textarea id="new_description" name="new_description" rows="3"></textarea>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" onclick="closeAddModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Setting</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
        }

        // Close modals on background click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // Handle checkbox toggle for boolean settings
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const hiddenInput = this.previousElementSibling;
                if (hiddenInput && hiddenInput.type === 'hidden') {
                    hiddenInput.disabled = this.checked;
                }
            });
        });
    </script>

    <script src="../assets/js/main.js"></script>
</body>
</html>
