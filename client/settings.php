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

// Handle theme update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_theme'])) {
    $selectedTheme = $_POST['theme'] ?? 'light';
    
    try {
        // Update user's theme preference
        $db->update('users', 
            ['theme_preference' => $selectedTheme],
            'id = :id',
            ['id' => $user['id']]
        );
        
        // Update session
        $_SESSION['theme'] = $selectedTheme;
        
        $success = "Theme updated successfully!";
    } catch (Exception $e) {
        error_log("Theme update error: " . $e->getMessage());
        $error = "Failed to update theme. Please try again.";
    }
}

// Get available themes
$themes = [];
try {
    $themes = $db->fetchAll("SELECT * FROM themes ORDER BY theme_name");
} catch (Exception $e) {
    error_log("Error fetching themes: " . $e->getMessage());
}

// Get user's current theme
$currentTheme = $_SESSION['theme'] ?? $user['theme_preference'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - KioskHelp</title>
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
                <h1>Settings</h1>
                <p>Customize your KioskHelp experience</p>
            </div>

            <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <!-- Theme Customization -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-palette"></i> Theme Customization
                </div>
                <div class="card-body">
                    <p style="margin-bottom: 1.5rem;">Choose a color theme that works best for you. The theme will be applied across all pages.</p>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Select Theme</label>
                            <div class="theme-options" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem;">
                                <?php foreach ($themes as $theme): ?>
                                <div class="theme-option" style="border: 2px solid <?php echo $theme['theme_name'] === $currentTheme ? $theme['primary_color'] : '#ddd'; ?>; border-radius: 8px; padding: 1rem; cursor: pointer; transition: all 0.3s;" onclick="selectTheme('<?php echo htmlspecialchars($theme['theme_name']); ?>')">
                                    <input type="radio" 
                                           name="theme" 
                                           value="<?php echo htmlspecialchars($theme['theme_name']); ?>" 
                                           id="theme_<?php echo htmlspecialchars($theme['theme_name']); ?>"
                                           <?php echo $theme['theme_name'] === $currentTheme ? 'checked' : ''; ?>
                                           style="display: none;">
                                    
                                    <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                                        <i class="fas fa-<?php echo $theme['theme_name'] === $currentTheme ? 'check-circle' : 'circle'; ?>" style="color: <?php echo $theme['primary_color']; ?>; margin-right: 0.5rem; font-size: 1.2rem;"></i>
                                        <strong style="font-size: 1.1rem;"><?php echo htmlspecialchars(ucfirst($theme['theme_name'])); ?></strong>
                                    </div>
                                    
                                    <div class="theme-preview" style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <div style="width: 30px; height: 30px; background-color: <?php echo htmlspecialchars($theme['primary_color']); ?>; border-radius: 4px;" title="Primary Color"></div>
                                        <div style="width: 30px; height: 30px; background-color: <?php echo htmlspecialchars($theme['secondary_color']); ?>; border-radius: 4px;" title="Secondary Color"></div>
                                        <div style="width: 30px; height: 30px; background-color: <?php echo htmlspecialchars($theme['accent_color']); ?>; border-radius: 4px;" title="Accent Color"></div>
                                    </div>
                                    
                                    <div style="font-size: 0.9rem; color: #666;">
                                        <?php
                                        $descriptions = [
                                            'light' => 'Clean and bright interface',
                                            'dark' => 'Easy on the eyes in low light',
                                            'compassion' => 'Warm and supportive purple tones',
                                            'hope' => 'Uplifting green palette'
                                        ];
                                        echo $descriptions[$theme['theme_name']] ?? 'Custom theme';
                                        ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 2rem;">
                            <button type="submit" name="update_theme" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Theme Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <i class="fas fa-bell"></i> Notification Preferences
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p><strong>Coming Soon!</strong></p>
                        <p>Notification preferences will allow you to customize how and when you receive updates about appointments, messages, and referrals.</p>
                    </div>
                </div>
            </div>

            <!-- Privacy Settings -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <i class="fas fa-shield-alt"></i> Privacy Settings
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p><strong>Coming Soon!</strong></p>
                        <p>Privacy settings will allow you to manage your data sharing preferences and view your privacy rights.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        function selectTheme(themeName) {
            // Check the corresponding radio button
            document.getElementById('theme_' + themeName).checked = true;
            
            // Update all theme options to reflect selection
            const themeOptions = document.querySelectorAll('.theme-option');
            themeOptions.forEach(option => {
                const radio = option.querySelector('input[type="radio"]');
                const icon = option.querySelector('i');
                const theme = document.getElementById('theme_' + themeName);
                
                if (radio.value === themeName) {
                    option.style.borderColor = getComputedStyle(option.querySelector('[title="Primary Color"]')).backgroundColor;
                    icon.className = 'fas fa-check-circle';
                } else {
                    option.style.borderColor = '#ddd';
                    icon.className = 'fas fa-circle';
                }
            });
        }
    </script>
</body>
</html>
