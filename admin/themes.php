<?php
/**
 * Admin Theme Management
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
    
    if ($action === 'create_theme') {
        $theme_name = trim($_POST['theme_name'] ?? '');
        $primary_color = $_POST['primary_color'] ?? '#1976D2';
        $secondary_color = $_POST['secondary_color'] ?? '#424242';
        $accent_color = $_POST['accent_color'] ?? '#FFC107';
        $background_color = $_POST['background_color'] ?? '#FFFFFF';
        $text_color = $_POST['text_color'] ?? '#333333';
        $font_family = trim($_POST['font_family'] ?? 'Arial, sans-serif');
        $custom_css = trim($_POST['custom_css'] ?? '');
        
        if (empty($theme_name)) {
            $message = 'Theme name is required.';
            $messageType = 'error';
        } else {
            try {
                $db = Database::getInstance();
                $conn = $db->getConnection();
                
                // Check if theme name already exists
                $stmt = $conn->prepare("SELECT id FROM themes WHERE theme_name = ?");
                $stmt->execute([$theme_name]);
                if ($stmt->fetch()) {
                    $message = 'Theme name already exists.';
                    $messageType = 'error';
                } else {
                    // Create theme
                    $stmt = $conn->prepare("INSERT INTO themes (theme_name, primary_color, secondary_color, accent_color, background_color, text_color, font_family, custom_css, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$theme_name, $primary_color, $secondary_color, $accent_color, $background_color, $text_color, $font_family, $custom_css, 1]);
                    
                    $message = 'Theme created successfully!';
                    $messageType = 'success';
                }
            } catch (Exception $e) {
                $message = 'Error creating theme: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    } elseif ($action === 'update_theme') {
        $theme_id = (int)($_POST['theme_id'] ?? 0);
        $primary_color = $_POST['primary_color'] ?? '#1976D2';
        $secondary_color = $_POST['secondary_color'] ?? '#424242';
        $accent_color = $_POST['accent_color'] ?? '#FFC107';
        $background_color = $_POST['background_color'] ?? '#FFFFFF';
        $text_color = $_POST['text_color'] ?? '#333333';
        $font_family = trim($_POST['font_family'] ?? 'Arial, sans-serif');
        $custom_css = trim($_POST['custom_css'] ?? '');
        
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("UPDATE themes SET primary_color = ?, secondary_color = ?, accent_color = ?, background_color = ?, text_color = ?, font_family = ?, custom_css = ? WHERE id = ?");
            $stmt->execute([$primary_color, $secondary_color, $accent_color, $background_color, $text_color, $font_family, $custom_css, $theme_id]);
            
            $message = 'Theme updated successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error updating theme: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif ($action === 'activate_theme') {
        $theme_id = (int)($_POST['theme_id'] ?? 0);
        
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            // Deactivate all themes first
            $conn->exec("UPDATE themes SET is_active = 0");
            
            // Activate selected theme
            $stmt = $conn->prepare("UPDATE themes SET is_active = 1 WHERE id = ?");
            $stmt->execute([$theme_id]);
            
            $message = 'Theme activated successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error activating theme: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif ($action === 'delete_theme') {
        $theme_id = (int)($_POST['theme_id'] ?? 0);
        
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            // Check if theme is active
            $stmt = $conn->prepare("SELECT is_active FROM themes WHERE id = ?");
            $stmt->execute([$theme_id]);
            $theme = $stmt->fetch();
            
            if ($theme && $theme['is_active']) {
                $message = 'Cannot delete an active theme. Please activate another theme first.';
                $messageType = 'error';
            } else {
                $stmt = $conn->prepare("DELETE FROM themes WHERE id = ?");
                $stmt->execute([$theme_id]);
                
                $message = 'Theme deleted successfully!';
                $messageType = 'success';
            }
        } catch (Exception $e) {
            $message = 'Error deleting theme: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Fetch themes
$themes = [];
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $stmt = $conn->query("SELECT * FROM themes ORDER BY is_active DESC, created_at DESC");
    $themes = $stmt->fetchAll();
} catch (Exception $e) {
    $message = 'Error fetching themes: ' . $e->getMessage();
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Management - Admin - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .themes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .theme-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }
        .theme-card.active {
            border: 2px solid #43e97b;
        }
        .theme-card h3 {
            margin-top: 0;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .active-badge {
            background: #43e97b;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .color-preview {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .color-item {
            text-align: center;
        }
        .color-swatch {
            width: 100%;
            height: 60px;
            border-radius: 4px;
            margin-bottom: 0.25rem;
            border: 1px solid #ddd;
        }
        .color-label {
            font-size: 0.75rem;
            color: #666;
        }
        .theme-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
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
            max-width: 600px;
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
        .form-group textarea {
            min-height: 100px;
            font-family: monospace;
        }
        .color-input-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .color-input-group input[type="color"] {
            width: 60px;
            height: 40px;
            padding: 0.25rem;
            cursor: pointer;
        }
        .color-input-group input[type="text"] {
            flex: 1;
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
                <li class="active">
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
                <h1>Theme Management</h1>
                <p>Customize the appearance of your application</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2>Themes</h2>
                    <button onclick="openCreateModal()" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Theme
                    </button>
                </div>

                <!-- Themes Grid -->
                <div class="themes-grid">
                    <?php if (empty($themes)): ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                            No themes found. Create your first theme!
                        </div>
                    <?php else: ?>
                        <?php foreach ($themes as $theme): ?>
                            <div class="theme-card <?php echo $theme['is_active'] ? 'active' : ''; ?>">
                                <h3>
                                    <?php echo htmlspecialchars($theme['theme_name']); ?>
                                    <?php if ($theme['is_active']): ?>
                                        <span class="active-badge">Active</span>
                                    <?php endif; ?>
                                </h3>
                                
                                <div class="color-preview">
                                    <div class="color-item">
                                        <div class="color-swatch" style="background: <?php echo htmlspecialchars($theme['primary_color']); ?>;"></div>
                                        <div class="color-label">Primary</div>
                                    </div>
                                    <div class="color-item">
                                        <div class="color-swatch" style="background: <?php echo htmlspecialchars($theme['secondary_color']); ?>;"></div>
                                        <div class="color-label">Secondary</div>
                                    </div>
                                    <div class="color-item">
                                        <div class="color-swatch" style="background: <?php echo htmlspecialchars($theme['accent_color']); ?>;"></div>
                                        <div class="color-label">Accent</div>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 1rem;">
                                    <small style="color: #666;">
                                        <strong>Font:</strong> <?php echo htmlspecialchars($theme['font_family']); ?>
                                    </small>
                                </div>
                                
                                <div class="theme-actions">
                                    <?php if (!$theme['is_active']): ?>
                                        <form method="POST" style="flex: 1;">
                                            <input type="hidden" name="action" value="activate_theme">
                                            <input type="hidden" name="theme_id" value="<?php echo $theme['id']; ?>">
                                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                                <i class="fas fa-check"></i> Activate
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <button onclick='openEditModal(<?php echo json_encode($theme); ?>)' class="btn btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <?php if (!$theme['is_active']): ?>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this theme?');">
                                            <input type="hidden" name="action" value="delete_theme">
                                            <input type="hidden" name="theme_id" value="<?php echo $theme['id']; ?>">
                                            <button type="submit" class="btn" style="background: #f5576c;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Create Theme Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <h2 style="margin-top: 0;">Create New Theme</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create_theme">
                
                <div class="form-group">
                    <label for="theme_name">Theme Name *</label>
                    <input type="text" id="theme_name" name="theme_name" required>
                </div>
                
                <div class="form-group">
                    <label for="primary_color">Primary Color</label>
                    <div class="color-input-group">
                        <input type="color" id="primary_color" name="primary_color" value="#1976D2">
                        <input type="text" id="primary_color_text" value="#1976D2" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="secondary_color">Secondary Color</label>
                    <div class="color-input-group">
                        <input type="color" id="secondary_color" name="secondary_color" value="#424242">
                        <input type="text" id="secondary_color_text" value="#424242" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="accent_color">Accent Color</label>
                    <div class="color-input-group">
                        <input type="color" id="accent_color" name="accent_color" value="#FFC107">
                        <input type="text" id="accent_color_text" value="#FFC107" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="background_color">Background Color</label>
                    <div class="color-input-group">
                        <input type="color" id="background_color" name="background_color" value="#FFFFFF">
                        <input type="text" id="background_color_text" value="#FFFFFF" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="text_color">Text Color</label>
                    <div class="color-input-group">
                        <input type="color" id="text_color" name="text_color" value="#333333">
                        <input type="text" id="text_color_text" value="#333333" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="font_family">Font Family</label>
                    <input type="text" id="font_family" name="font_family" value="Arial, sans-serif">
                </div>
                
                <div class="form-group">
                    <label for="custom_css">Custom CSS (Optional)</label>
                    <textarea id="custom_css" name="custom_css"></textarea>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" onclick="closeCreateModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Theme</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Theme Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2 style="margin-top: 0;">Edit Theme</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_theme">
                <input type="hidden" id="edit_theme_id" name="theme_id">
                
                <div class="form-group">
                    <label>Theme Name</label>
                    <input type="text" id="edit_theme_name" disabled style="background: #f5f5f5;">
                </div>
                
                <div class="form-group">
                    <label for="edit_primary_color">Primary Color</label>
                    <div class="color-input-group">
                        <input type="color" id="edit_primary_color" name="primary_color">
                        <input type="text" id="edit_primary_color_text" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_secondary_color">Secondary Color</label>
                    <div class="color-input-group">
                        <input type="color" id="edit_secondary_color" name="secondary_color">
                        <input type="text" id="edit_secondary_color_text" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_accent_color">Accent Color</label>
                    <div class="color-input-group">
                        <input type="color" id="edit_accent_color" name="accent_color">
                        <input type="text" id="edit_accent_color_text" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_background_color">Background Color</label>
                    <div class="color-input-group">
                        <input type="color" id="edit_background_color" name="background_color">
                        <input type="text" id="edit_background_color_text" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_text_color">Text Color</label>
                    <div class="color-input-group">
                        <input type="color" id="edit_text_color" name="text_color">
                        <input type="text" id="edit_text_color_text" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_font_family">Font Family</label>
                    <input type="text" id="edit_font_family" name="font_family">
                </div>
                
                <div class="form-group">
                    <label for="edit_custom_css">Custom CSS (Optional)</label>
                    <textarea id="edit_custom_css" name="custom_css"></textarea>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Theme</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Color picker sync
        document.querySelectorAll('input[type="color"]').forEach(input => {
            const textInput = document.getElementById(input.id + '_text');
            if (textInput) {
                input.addEventListener('input', function() {
                    textInput.value = this.value.toUpperCase();
                });
            }
        });

        function openCreateModal() {
            document.getElementById('createModal').classList.add('active');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.remove('active');
        }

        function openEditModal(theme) {
            document.getElementById('edit_theme_id').value = theme.id;
            document.getElementById('edit_theme_name').value = theme.theme_name;
            
            document.getElementById('edit_primary_color').value = theme.primary_color;
            document.getElementById('edit_primary_color_text').value = theme.primary_color;
            
            document.getElementById('edit_secondary_color').value = theme.secondary_color;
            document.getElementById('edit_secondary_color_text').value = theme.secondary_color;
            
            document.getElementById('edit_accent_color').value = theme.accent_color;
            document.getElementById('edit_accent_color_text').value = theme.accent_color;
            
            document.getElementById('edit_background_color').value = theme.background_color;
            document.getElementById('edit_background_color_text').value = theme.background_color;
            
            document.getElementById('edit_text_color').value = theme.text_color;
            document.getElementById('edit_text_color_text').value = theme.text_color;
            
            document.getElementById('edit_font_family').value = theme.font_family;
            document.getElementById('edit_custom_css').value = theme.custom_css || '';
            
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        // Close modals on background click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
    </script>

    <script src="../assets/js/main.js"></script>
</body>
</html>
