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

// Get assessment ID
$assessmentId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$assessmentId) {
    header('Location: assessment.php');
    exit;
}

// Fetch assessment details
try {
    $assessment = $db->fetch(
        "SELECT a.*, DATE_FORMAT(a.completed_at, '%M %d, %Y at %h:%i %p') as formatted_date 
         FROM assessments a 
         WHERE a.id = :id AND a.client_id = :client_id",
        ['id' => $assessmentId, 'client_id' => $user['client_id']]
    );
    
    if (!$assessment) {
        header('Location: assessment.php');
        exit;
    }
    
    // Fetch assessment responses grouped by domain
    $responses = $db->fetchAll(
        "SELECT * FROM assessment_responses 
         WHERE assessment_id = :id 
         ORDER BY domain, question_key",
        ['id' => $assessmentId]
    );
    
    // Group responses by domain
    $groupedResponses = [];
    foreach ($responses as $response) {
        $domain = $response['domain'];
        if (!isset($groupedResponses[$domain])) {
            $groupedResponses[$domain] = [];
        }
        $groupedResponses[$domain][] = $response;
    }
    
} catch (Exception $e) {
    error_log("Error fetching assessment: " . $e->getMessage());
    header('Location: assessment.php');
    exit;
}

// Domain names mapping
$domainNames = [
    'housing' => 'Housing Stability',
    'food' => 'Food Security',
    'healthcare' => 'Healthcare Access',
    'mental_health' => 'Mental Health',
    'employment' => 'Employment',
    'legal' => 'Legal Assistance',
    'transportation' => 'Transportation',
    'family' => 'Family Services'
];

// Domain icons mapping
$domainIcons = [
    'housing' => 'fa-home',
    'food' => 'fa-utensils',
    'healthcare' => 'fa-heartbeat',
    'mental_health' => 'fa-brain',
    'employment' => 'fa-briefcase',
    'legal' => 'fa-gavel',
    'transportation' => 'fa-bus',
    'family' => 'fa-users'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Details - KioskHelp</title>
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
                <div>
                    <h1>Assessment Details</h1>
                    <p>Completed on <?php echo htmlspecialchars($assessment['formatted_date']); ?></p>
                </div>
                <a href="assessment.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Assessments
                </a>
            </div>

            <!-- Assessment Summary -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <i class="fas fa-chart-bar"></i> Assessment Summary
                </div>
                <div class="card-body">
                    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                        <div>
                            <strong>Status:</strong>
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> Completed
                            </span>
                        </div>
                        <div>
                            <strong>Priority Score:</strong>
                            <span class="badge badge-<?php echo $assessment['priority_score'] > 15 ? 'error' : ($assessment['priority_score'] > 8 ? 'warning' : 'success'); ?>">
                                <?php echo $assessment['priority_score']; ?> points
                            </span>
                        </div>
                        <div>
                            <strong>Domains Assessed:</strong>
                            <?php echo count($groupedResponses); ?> of 8
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Responses by Domain -->
            <?php foreach ($groupedResponses as $domain => $domainResponses): ?>
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <i class="fas <?php echo $domainIcons[$domain] ?? 'fa-circle'; ?>"></i>
                    <?php echo $domainNames[$domain] ?? ucfirst($domain); ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Response</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($domainResponses as $response): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $response['question_key']))); ?></strong>
                                    </td>
                                    <td>
                                        <?php 
                                        $value = $response['response_value'];
                                        // Check if it's JSON array
                                        $decoded = json_decode($value, true);
                                        if (is_array($decoded)) {
                                            echo htmlspecialchars(implode(', ', array_map(function($v) {
                                                return ucwords(str_replace('_', ' ', $v));
                                            }, $decoded)));
                                        } else {
                                            echo htmlspecialchars(ucwords(str_replace('_', ' ', $value)));
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $response['priority_level'] == 3 ? 'error' : ($response['priority_level'] == 2 ? 'warning' : 'info'); ?>">
                                            <?php 
                                            $priorityLabels = [1 => 'Low', 2 => 'Medium', 3 => 'High'];
                                            echo $priorityLabels[$response['priority_level']] ?? 'N/A'; 
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($groupedResponses)): ?>
            <div class="alert alert-info">
                <p>No detailed responses recorded for this assessment.</p>
            </div>
            <?php endif; ?>

            <div class="section" style="margin-top: 2rem; text-align: center;">
                <a href="assessment.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Take New Assessment
                </a>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
