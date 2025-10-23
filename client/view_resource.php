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

// Get resource ID
$resourceId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$resourceId) {
    header('Location: resources.php');
    exit;
}

// Fetch resource details
try {
    $resource = $db->fetch(
        "SELECT r.*, DATE_FORMAT(r.created_at, '%M %d, %Y') as formatted_date
         FROM resources r 
         WHERE r.id = :id AND r.is_active = 1",
        ['id' => $resourceId]
    );
    
    if (!$resource) {
        header('Location: resources.php');
        exit;
    }
    
    // Increment view count
    $db->update('resources', 
        ['view_count' => $resource['view_count'] + 1],
        'id = :id',
        ['id' => $resourceId]
    );
    
    // Track that client accessed this resource
    try {
        $clientId = $user['client_id'];
        $existingAccess = $db->fetch(
            "SELECT id FROM client_resources WHERE client_id = :client_id AND resource_id = :resource_id",
            ['client_id' => $clientId, 'resource_id' => $resourceId]
        );
        
        if (!$existingAccess) {
            $db->insert('client_resources', [
                'client_id' => $clientId,
                'resource_id' => $resourceId
            ]);
        } else {
            // Update access time
            $db->update('client_resources',
                ['accessed_at' => date('Y-m-d H:i:s')],
                'id = :id',
                ['id' => $existingAccess['id']]
            );
        }
    } catch (Exception $e) {
        error_log("Error tracking resource access: " . $e->getMessage());
    }
    
} catch (Exception $e) {
    error_log("Error fetching resource: " . $e->getMessage());
    header('Location: resources.php');
    exit;
}

// Category icons mapping
$categoryIcons = [
    'housing' => 'fa-home',
    'food' => 'fa-utensils',
    'healthcare' => 'fa-heartbeat',
    'mental_health' => 'fa-brain',
    'employment' => 'fa-briefcase',
    'legal' => 'fa-gavel',
    'transportation' => 'fa-bus',
    'family' => 'fa-users',
    'education' => 'fa-graduation-cap',
    'financial' => 'fa-dollar-sign'
];

// Resource type icons mapping
$typeIcons = [
    'article' => 'fa-file-alt',
    'video' => 'fa-video',
    'guide' => 'fa-book',
    'form' => 'fa-file-invoice',
    'link' => 'fa-link',
    'document' => 'fa-file-pdf'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($resource['title']); ?> - KioskHelp</title>
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
                    <h1><?php echo htmlspecialchars($resource['title']); ?></h1>
                    <p>
                        <span class="badge badge-info">
                            <i class="fas <?php echo $categoryIcons[$resource['category']] ?? 'fa-tag'; ?>"></i>
                            <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $resource['category']))); ?>
                        </span>
                        <span class="badge badge-secondary">
                            <i class="fas <?php echo $typeIcons[$resource['resource_type']] ?? 'fa-file'; ?>"></i>
                            <?php echo ucfirst($resource['resource_type']); ?>
                        </span>
                    </p>
                </div>
                <a href="resources.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Resources
                </a>
            </div>

            <!-- Resource Details -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-body">
                    <?php if ($resource['description']): ?>
                    <div style="padding: 1rem; background: #f5f5f5; border-radius: 4px; margin-bottom: 1.5rem;">
                        <strong>About this resource:</strong><br>
                        <?php echo nl2br(htmlspecialchars($resource['description'])); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($resource['external_url']): ?>
                    <div class="alert alert-info">
                        <p><strong><i class="fas fa-external-link-alt"></i> External Resource</strong></p>
                        <p>This resource is hosted externally. Click the link below to access it:</p>
                        <a href="<?php echo htmlspecialchars($resource['external_url']); ?>" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> Open External Resource
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if ($resource['content']): ?>
                    <div style="line-height: 1.8; margin-top: 1.5rem;">
                        <?php echo nl2br(htmlspecialchars($resource['content'])); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($resource['file_path']): ?>
                    <div style="margin-top: 2rem; padding: 1rem; background: #e3f2fd; border-radius: 4px;">
                        <strong><i class="fas fa-download"></i> Download Resource</strong>
                        <p style="margin: 0.5rem 0 0 0;">
                            <a href="<?php echo htmlspecialchars($resource['file_path']); ?>" class="btn btn-primary" download>
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Resource Metadata -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Resource Information
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div>
                            <strong>Category:</strong><br>
                            <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $resource['category']))); ?>
                        </div>
                        <div>
                            <strong>Type:</strong><br>
                            <?php echo ucfirst($resource['resource_type']); ?>
                        </div>
                        <div>
                            <strong>Views:</strong><br>
                            <?php echo number_format($resource['view_count']); ?>
                        </div>
                        <div>
                            <strong>Added:</strong><br>
                            <?php echo htmlspecialchars($resource['formatted_date']); ?>
                        </div>
                    </div>

                    <?php if ($resource['keywords']): ?>
                    <div style="margin-top: 1.5rem;">
                        <strong>Keywords:</strong><br>
                        <?php 
                        $keywords = explode(',', $resource['keywords']);
                        foreach ($keywords as $keyword): 
                        ?>
                        <span class="badge badge-secondary" style="margin-right: 0.5rem; margin-top: 0.5rem;">
                            <?php echo htmlspecialchars(trim($keyword)); ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div style="margin-top: 2rem; text-align: center;">
                <a href="resources.php?category=<?php echo urlencode($resource['category']); ?>" class="btn btn-secondary">
                    <i class="fas fa-tag"></i> More in <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $resource['category']))); ?>
                </a>
                <a href="resources.php" class="btn btn-primary">
                    <i class="fas fa-book-open"></i> Browse All Resources
                </a>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
