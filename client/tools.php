<?php
/**
 * Self-Help Tools Page
 */

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
    <title>Self-Help Tools - KioskHelp</title>
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
                <h1>Self-Help Tools</h1>
                <p>Empower yourself with tools to manage your journey</p>
            </div>

            <div class="alert alert-success">
                <p><strong>Budget Planner is Now Available!</strong></p>
                <p>The Budget Planner tool is ready to use. Click on it above to start tracking your income and expenses.</p>
            </div>
            
            <div class="alert alert-info">
                <p><strong>Additional Self-Help Tools Coming Soon!</strong></p>
                <p>The remaining tools are currently in development and will be available in future updates.</p>
            </div>

            <div class="action-grid">
                <a href="tools/budget.php" class="action-card">
                    <i class="fas fa-calculator"></i>
                    <h3>Budget Planner</h3>
                    <p>Track your income and expenses to manage your finances effectively</p>
                    <div style="margin-top: 1rem;">
                        <span class="badge badge-success">Available</span>
                    </div>
                </a>

                <div class="action-card">
                    <i class="fas fa-search-location"></i>
                    <h3>Housing Search Checklist</h3>
                    <p>Organize your housing applications and track your progress</p>
                    <div style="margin-top: 1rem;">
                        <span class="badge badge-warning">Coming Soon</span>
                    </div>
                </div>

                <div class="action-card">
                    <i class="fas fa-smile"></i>
                    <h3>Wellness Tracker</h3>
                    <p>Monitor your daily mood, sleep patterns, and overall wellbeing</p>
                    <div style="margin-top: 1rem;">
                        <span class="badge badge-warning">Coming Soon</span>
                    </div>
                </div>

                <div class="action-card">
                    <i class="fas fa-bullseye"></i>
                    <h3>Goal Planner</h3>
                    <p>Set personal goals and track your achievements over time</p>
                    <div style="margin-top: 1rem;">
                        <span class="badge badge-warning">Coming Soon</span>
                    </div>
                </div>

                <div class="action-card">
                    <i class="fas fa-file-alt"></i>
                    <h3>Job Search Organizer</h3>
                    <p>Manage job applications, interviews, and follow-ups efficiently</p>
                    <div style="margin-top: 1rem;">
                        <span class="badge badge-warning">Coming Soon</span>
                    </div>
                </div>

                <div class="action-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Crisis Plan Creator</h3>
                    <p>Develop your personal crisis response and safety plan</p>
                    <div style="margin-top: 1rem;">
                        <span class="badge badge-warning">Coming Soon</span>
                    </div>
                </div>
            </div>

            <div class="section" style="margin-top: 3rem;">
                <h2>Why Use Self-Help Tools?</h2>
                <div class="card">
                    <div class="card-body">
                        <ul style="list-style-position: inside;">
                            <li><strong>Track Progress</strong> - Visualize your journey and see how far you've come</li>
                            <li><strong>Stay Organized</strong> - Keep all important information in one place</li>
                            <li><strong>Build Skills</strong> - Develop practical life management skills</li>
                            <li><strong>Set Goals</strong> - Create achievable goals and work towards them</li>
                            <li><strong>Gain Insights</strong> - Understand patterns and make informed decisions</li>
                            <li><strong>Stay Motivated</strong> - Celebrate achievements and stay on track</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
