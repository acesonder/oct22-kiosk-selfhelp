<?php
/**
 * Admin Error Logs
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
    
    if ($action === 'clear_logs') {
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $days = (int)($_POST['days'] ?? 30);
            $stmt = $conn->prepare("DELETE FROM error_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)");
            $stmt->execute([$days]);
            
            $deleted = $stmt->rowCount();
            $message = "Successfully deleted {$deleted} old log entries.";
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error clearing logs: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif ($action === 'delete_log') {
        $log_id = (int)($_POST['log_id'] ?? 0);
        
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("DELETE FROM error_logs WHERE id = ?");
            $stmt->execute([$log_id]);
            
            $message = 'Log entry deleted successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error deleting log: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get filter parameters
$error_type_filter = $_GET['type'] ?? 'all';
$days_filter = (int)($_GET['days'] ?? 7);
$page = (int)($_GET['page'] ?? 1);
$per_page = 50;
$offset = ($page - 1) * $per_page;

// Fetch error logs
$logs = [];
$total_logs = 0;

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Build query
    $where = ["created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)"];
    $params = [$days_filter];
    
    if ($error_type_filter !== 'all') {
        $where[] = "error_type = ?";
        $params[] = $error_type_filter;
    }
    
    $where_clause = implode(' AND ', $where);
    
    // Get total count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM error_logs WHERE {$where_clause}");
    $stmt->execute($params);
    $total_logs = $stmt->fetchColumn();
    
    // Get logs
    $params[] = $per_page;
    $params[] = $offset;
    $stmt = $conn->prepare("SELECT * FROM error_logs WHERE {$where_clause} ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->execute($params);
    $logs = $stmt->fetchAll();
    
    // Get error type counts
    $stmt = $conn->prepare("SELECT error_type, COUNT(*) as count FROM error_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY error_type");
    $stmt->execute([$days_filter]);
    $error_counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (Exception $e) {
    $message = 'Error fetching logs: ' . $e->getMessage();
    $messageType = 'error';
}

$total_pages = ceil($total_logs / $per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Logs - Admin - KioskHelp</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stats-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .stat-box {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1;
            min-width: 150px;
        }
        .stat-box h4 {
            margin: 0 0 0.5rem 0;
            font-size: 0.875rem;
            color: #666;
        }
        .stat-box .count {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .filters > div {
            flex: 1;
            min-width: 200px;
        }
        .filters label {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .filters select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }
        .logs-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .log-entry {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .log-entry:last-child {
            border-bottom: none;
        }
        .log-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }
        .log-type {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .log-type.error { background: #f5576c; color: white; }
        .log-type.warning { background: #FFC107; color: #333; }
        .log-type.notice { background: #4facfe; color: white; }
        .log-type.info { background: #43e97b; color: white; }
        .log-message {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .log-details {
            display: flex;
            gap: 1.5rem;
            font-size: 0.75rem;
            color: #666;
            flex-wrap: wrap;
        }
        .log-details span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .log-trace {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.75rem;
            max-height: 200px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .expand-btn {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-size: 0.875rem;
            padding: 0.25rem 0;
            margin-top: 0.5rem;
        }
        .expand-btn:hover {
            text-decoration: underline;
        }
        .pagination {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            padding: 1.5rem;
            background: white;
            border-radius: 8px;
            margin-top: 1rem;
        }
        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #667eea;
        }
        .pagination .current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .pagination a:hover {
            background: #f8f9fa;
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
                <li>
                    <a href="settings.php">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="active">
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
                <h1>Error Logs</h1>
                <p>Monitor system errors and issues</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="stats-bar">
                <div class="stat-box">
                    <h4>Total Errors (Last <?php echo $days_filter; ?> Days)</h4>
                    <div class="count"><?php echo $total_logs; ?></div>
                </div>
                <?php if (!empty($error_counts)): ?>
                    <?php foreach ($error_counts as $type => $count): ?>
                        <div class="stat-box">
                            <h4><?php echo ucfirst($type); ?> Errors</h4>
                            <div class="count"><?php echo $count; ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2>Error Logs</h2>
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to clear old logs?');">
                        <input type="hidden" name="action" value="clear_logs">
                        <input type="hidden" name="days" value="<?php echo $days_filter; ?>">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-trash-alt"></i> Clear Old Logs
                        </button>
                    </form>
                </div>

                <!-- Filters -->
                <div class="filters">
                    <div>
                        <label for="type-filter">Error Type:</label>
                        <select id="type-filter" onchange="applyFilters()">
                            <option value="all" <?php echo $error_type_filter === 'all' ? 'selected' : ''; ?>>All Types</option>
                            <option value="error" <?php echo $error_type_filter === 'error' ? 'selected' : ''; ?>>Error</option>
                            <option value="warning" <?php echo $error_type_filter === 'warning' ? 'selected' : ''; ?>>Warning</option>
                            <option value="notice" <?php echo $error_type_filter === 'notice' ? 'selected' : ''; ?>>Notice</option>
                            <option value="info" <?php echo $error_type_filter === 'info' ? 'selected' : ''; ?>>Info</option>
                        </select>
                    </div>
                    <div>
                        <label for="days-filter">Time Period:</label>
                        <select id="days-filter" onchange="applyFilters()">
                            <option value="1" <?php echo $days_filter === 1 ? 'selected' : ''; ?>>Last 24 Hours</option>
                            <option value="7" <?php echo $days_filter === 7 ? 'selected' : ''; ?>>Last 7 Days</option>
                            <option value="30" <?php echo $days_filter === 30 ? 'selected' : ''; ?>>Last 30 Days</option>
                            <option value="90" <?php echo $days_filter === 90 ? 'selected' : ''; ?>>Last 90 Days</option>
                        </select>
                    </div>
                </div>

                <!-- Logs -->
                <div class="logs-container">
                    <?php if (empty($logs)): ?>
                        <div style="text-align: center; padding: 3rem; color: #666;">
                            <i class="fas fa-check-circle" style="font-size: 3rem; color: #43e97b; margin-bottom: 1rem;"></i>
                            <p>No error logs found for the selected period. Your system is running smoothly!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <div class="log-entry">
                                <div class="log-header">
                                    <div>
                                        <span class="log-type <?php echo strtolower($log['error_type']); ?>">
                                            <?php echo htmlspecialchars($log['error_type']); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this log entry?');">
                                            <input type="hidden" name="action" value="delete_log">
                                            <input type="hidden" name="log_id" value="<?php echo $log['id']; ?>">
                                            <button type="submit" class="btn btn-small" style="background: #f5576c; padding: 0.25rem 0.5rem;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="log-message">
                                    <strong><?php echo htmlspecialchars($log['error_message']); ?></strong>
                                </div>
                                
                                <div class="log-details">
                                    <?php if ($log['error_file']): ?>
                                        <span>
                                            <i class="fas fa-file"></i>
                                            <?php echo htmlspecialchars($log['error_file']); ?>:<?php echo $log['error_line']; ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($log['ip_address']): ?>
                                        <span>
                                            <i class="fas fa-network-wired"></i>
                                            <?php echo htmlspecialchars($log['ip_address']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span>
                                        <i class="fas fa-clock"></i>
                                        <?php echo date('M d, Y g:i A', strtotime($log['created_at'])); ?>
                                    </span>
                                </div>
                                
                                <?php if ($log['stack_trace']): ?>
                                    <button class="expand-btn" onclick="toggleTrace(<?php echo $log['id']; ?>)">
                                        <i class="fas fa-chevron-down"></i> Show Stack Trace
                                    </button>
                                    <div id="trace-<?php echo $log['id']; ?>" class="log-trace" style="display: none;">
                                        <?php echo htmlspecialchars($log['stack_trace']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?type=<?php echo $error_type_filter; ?>&days=<?php echo $days_filter; ?>&page=<?php echo $page - 1; ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <?php if ($i === $page): ?>
                                <span class="current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?type=<?php echo $error_type_filter; ?>&days=<?php echo $days_filter; ?>&page=<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?type=<?php echo $error_type_filter; ?>&days=<?php echo $days_filter; ?>&page=<?php echo $page + 1; ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function applyFilters() {
            const type = document.getElementById('type-filter').value;
            const days = document.getElementById('days-filter').value;
            window.location.href = `logs.php?type=${type}&days=${days}`;
        }

        function toggleTrace(id) {
            const trace = document.getElementById('trace-' + id);
            const btn = event.target.closest('button');
            if (trace.style.display === 'none') {
                trace.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-chevron-up"></i> Hide Stack Trace';
            } else {
                trace.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-chevron-down"></i> Show Stack Trace';
            }
        }
    </script>

    <script src="../assets/js/main.js"></script>
</body>
</html>
