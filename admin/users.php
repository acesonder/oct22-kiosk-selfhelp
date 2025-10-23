<?php
/**
 * Admin User Management
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
    
    if ($action === 'create_user') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? '';
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        
        if (empty($username) || empty($password) || empty($role)) {
            $message = 'Username, password, and role are required.';
            $messageType = 'error';
        } elseif (!in_array($role, ['admin', 'staff', 'provider', 'client'])) {
            $message = 'Invalid role selected.';
            $messageType = 'error';
        } else {
            try {
                $db = Database::getInstance();
                $conn = $db->getConnection();
                
                // Check if username already exists
                $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetch()) {
                    $message = 'Username already exists.';
                    $messageType = 'error';
                } else {
                    // Create user
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, role, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$username, $password_hash, $email, $role, $first_name, $last_name, $phone]);
                    
                    $message = 'User created successfully!';
                    $messageType = 'success';
                }
            } catch (Exception $e) {
                $message = 'Error creating user: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    } elseif ($action === 'toggle_active') {
        $user_id = (int)($_POST['user_id'] ?? 0);
        $is_active = (int)($_POST['is_active'] ?? 0);
        
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id = ?");
            $stmt->execute([$is_active, $user_id]);
            
            $message = 'User status updated successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error updating user: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif ($action === 'update_user') {
        $user_id = (int)($_POST['user_id'] ?? 0);
        $email = trim($_POST['email'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ?, phone = ? WHERE id = ?");
            $stmt->execute([$email, $first_name, $last_name, $phone, $user_id]);
            
            $message = 'User updated successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error updating user: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get filter
$role_filter = $_GET['role'] ?? 'all';
$status_filter = $_GET['status'] ?? 'all';

// Fetch users
$users = [];
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $sql = "SELECT id, username, email, role, first_name, last_name, phone, is_active, last_login, created_at FROM users WHERE 1=1";
    $params = [];
    
    if ($role_filter !== 'all') {
        $sql .= " AND role = ?";
        $params[] = $role_filter;
    }
    
    if ($status_filter === 'active') {
        $sql .= " AND is_active = 1";
    } elseif ($status_filter === 'inactive') {
        $sql .= " AND is_active = 0";
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    $message = 'Error fetching users: ' . $e->getMessage();
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .filters select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }
        .users-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .users-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .users-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .users-table tr:hover {
            background: #f8f9fa;
        }
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .role-admin { background: #667eea; color: white; }
        .role-staff { background: #4facfe; color: white; }
        .role-provider { background: #43e97b; color: white; }
        .role-client { background: #fa709a; color: white; }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-active { background: #43e97b; color: white; }
        .status-inactive { background: #f5576c; color: white; }
        .action-btns {
            display: flex;
            gap: 0.5rem;
        }
        .btn-small {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
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
        .form-group select {
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
                <li class="active">
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
                <h1>User Management</h1>
                <p>Manage system users and their roles</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2>Users</h2>
                    <button onclick="openCreateModal()" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Create User
                    </button>
                </div>

                <!-- Filters -->
                <div class="filters">
                    <div>
                        <label for="role-filter" style="display: block; margin-bottom: 0.25rem; font-size: 0.875rem;">Role:</label>
                        <select id="role-filter" onchange="applyFilters()">
                            <option value="all" <?php echo $role_filter === 'all' ? 'selected' : ''; ?>>All Roles</option>
                            <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="staff" <?php echo $role_filter === 'staff' ? 'selected' : ''; ?>>Staff</option>
                            <option value="provider" <?php echo $role_filter === 'provider' ? 'selected' : ''; ?>>Provider</option>
                            <option value="client" <?php echo $role_filter === 'client' ? 'selected' : ''; ?>>Client</option>
                        </select>
                    </div>
                    <div>
                        <label for="status-filter" style="display: block; margin-bottom: 0.25rem; font-size: 0.875rem;">Status:</label>
                        <select id="status-filter" onchange="applyFilters()">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="users-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 2rem;">No users found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                                        <td>
                                            <span class="role-badge role-<?php echo $user['role']; ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                                <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $user['last_login'] ? date('M d, Y', strtotime($user['last_login'])) : 'Never'; ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)" class="btn btn-secondary btn-small">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="action" value="toggle_active">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <input type="hidden" name="is_active" value="<?php echo $user['is_active'] ? 0 : 1; ?>">
                                                    <button type="submit" class="btn btn-small" style="background: <?php echo $user['is_active'] ? '#f5576c' : '#43e97b'; ?>;">
                                                        <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Create User Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <h2 style="margin-top: 0;">Create New User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create_user">
                
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="provider">Provider</option>
                        <option value="client">Client</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name">
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone">
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" onclick="closeCreateModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2 style="margin-top: 0;">Edit User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_user">
                <input type="hidden" id="edit_user_id" name="user_id">
                
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" id="edit_username" disabled style="background: #f5f5f5;">
                </div>
                
                <div class="form-group">
                    <label for="edit_first_name">First Name</label>
                    <input type="text" id="edit_first_name" name="first_name">
                </div>
                
                <div class="form-group">
                    <label for="edit_last_name">Last Name</label>
                    <input type="text" id="edit_last_name" name="last_name">
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email">
                </div>
                
                <div class="form-group">
                    <label for="edit_phone">Phone</label>
                    <input type="text" id="edit_phone" name="phone">
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function applyFilters() {
            const role = document.getElementById('role-filter').value;
            const status = document.getElementById('status-filter').value;
            window.location.href = `users.php?role=${role}&status=${status}`;
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.add('active');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.remove('active');
        }

        function openEditModal(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_first_name').value = user.first_name || '';
            document.getElementById('edit_last_name').value = user.last_name || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_phone').value = user.phone || '';
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
