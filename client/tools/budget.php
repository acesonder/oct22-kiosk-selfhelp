<?php
session_start();
require_once '../../includes/Auth.php';
require_once '../../includes/Database.php';

$auth = new Auth();

if (!$auth->isLoggedIn() || !$auth->hasRole('client')) {
    header('Location: ../login.php');
    exit;
}

$user = $auth->getCurrentUser();
$db = Database::getInstance();
$clientId = $user['client_id'];

// Handle add entry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_entry'])) {
    try {
        $db->insert('budget_entries', [
            'client_id' => $clientId,
            'entry_date' => $_POST['entry_date'],
            'category' => $_POST['category'],
            'entry_type' => $_POST['entry_type'],
            'amount' => $_POST['amount'],
            'description' => $_POST['description'] ?? '',
            'is_recurring' => isset($_POST['is_recurring']) ? 1 : 0
        ]);
        $success = "Budget entry added successfully!";
    } catch (Exception $e) {
        error_log("Error adding budget entry: " . $e->getMessage());
        $error = "Failed to add entry. Please try again.";
    }
}

// Handle delete entry
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $db->query(
            "DELETE FROM budget_entries WHERE id = :id AND client_id = :client_id",
            ['id' => $_GET['delete'], 'client_id' => $clientId]
        );
        header('Location: budget.php');
        exit;
    } catch (Exception $e) {
        error_log("Error deleting budget entry: " . $e->getMessage());
    }
}

// Fetch budget entries
$entries = [];
$totals = ['income' => 0, 'expense' => 0];

try {
    // Get current month's entries
    $currentMonth = date('Y-m');
    $entries = $db->fetchAll(
        "SELECT *, DATE_FORMAT(entry_date, '%M %d, %Y') as formatted_date
         FROM budget_entries 
         WHERE client_id = :client_id 
         AND DATE_FORMAT(entry_date, '%Y-%m') = :month
         ORDER BY entry_date DESC, created_at DESC",
        ['client_id' => $clientId, 'month' => $currentMonth]
    );
    
    // Calculate totals
    foreach ($entries as $entry) {
        $totals[$entry['entry_type']] += $entry['amount'];
    }
} catch (Exception $e) {
    error_log("Error fetching budget entries: " . $e->getMessage());
}

$balance = $totals['income'] - $totals['expense'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Planner - KioskHelp</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
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
            <a href="../logout.php" class="btn btn-secondary btn-small">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1><i class="fas fa-calculator"></i> Budget Planner</h1>
                    <p>Track your income and expenses for <?php echo date('F Y'); ?></p>
                </div>
                <a href="../tools.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Tools
                </a>
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

            <!-- Budget Summary -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">$<?php echo number_format($totals['income'], 2); ?></div>
                        <div class="stat-label">Total Income</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">$<?php echo number_format($totals['expense'], 2); ?></div>
                        <div class="stat-label">Total Expenses</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, <?php echo $balance >= 0 ? '#667eea 0%, #764ba2' : '#ff6b6b 0%, #c92a2a'; ?> 100%);">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value" style="color: <?php echo $balance >= 0 ? '#4CAF50' : '#f44336'; ?>">
                            $<?php echo number_format(abs($balance), 2); ?>
                        </div>
                        <div class="stat-label"><?php echo $balance >= 0 ? 'Surplus' : 'Deficit'; ?></div>
                    </div>
                </div>
            </div>

            <!-- Add Entry Form -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <i class="fas fa-plus-circle"></i> Add Budget Entry
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <div class="form-group">
                                <label>Date *</label>
                                <input type="date" name="entry_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Type *</label>
                                <select name="entry_type" class="form-control" required>
                                    <option value="income">Income</option>
                                    <option value="expense">Expense</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Category *</label>
                                <select name="category" class="form-control" required>
                                    <optgroup label="Income Categories">
                                        <option value="salary">Salary/Wages</option>
                                        <option value="benefits">Government Benefits</option>
                                        <option value="freelance">Freelance/Gig Work</option>
                                        <option value="other_income">Other Income</option>
                                    </optgroup>
                                    <optgroup label="Expense Categories">
                                        <option value="housing">Housing/Rent</option>
                                        <option value="utilities">Utilities</option>
                                        <option value="food">Food/Groceries</option>
                                        <option value="transportation">Transportation</option>
                                        <option value="healthcare">Healthcare</option>
                                        <option value="personal">Personal Care</option>
                                        <option value="debt">Debt Payment</option>
                                        <option value="other_expense">Other Expense</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount *</label>
                                <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Optional note about this entry">
                        </div>

                        <div class="form-group">
                            <label class="checkbox-option">
                                <input type="checkbox" name="is_recurring">
                                <span>This is a recurring entry</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="add_entry" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Budget Entries -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> Budget Entries (<?php echo count($entries); ?>)
                </div>
                <div class="card-body">
                    <?php if (empty($entries)): ?>
                    <div class="alert alert-info">
                        <p>No budget entries for this month yet. Add your first entry using the form above.</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entries as $entry): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($entry['formatted_date']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $entry['entry_type'] === 'income' ? 'success' : 'error'; ?>">
                                            <i class="fas fa-arrow-<?php echo $entry['entry_type'] === 'income' ? 'down' : 'up'; ?>"></i>
                                            <?php echo ucfirst($entry['entry_type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $entry['category']))); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($entry['description']); ?>
                                        <?php if ($entry['is_recurring']): ?>
                                        <i class="fas fa-sync-alt" title="Recurring" style="color: #1976D2;"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-weight: bold; color: <?php echo $entry['entry_type'] === 'income' ? '#4CAF50' : '#f44336'; ?>">
                                        $<?php echo number_format($entry['amount'], 2); ?>
                                    </td>
                                    <td>
                                        <a href="?delete=<?php echo $entry['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this entry?')"
                                           class="btn btn-small btn-error">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
