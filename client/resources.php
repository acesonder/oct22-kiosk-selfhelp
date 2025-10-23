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

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch resources
$resources = [];
try {
    $query = "SELECT r.*, 
              DATE_FORMAT(r.created_at, '%M %d, %Y') as formatted_date
              FROM resources r 
              WHERE r.is_active = 1";
    
    $params = [];
    
    if ($category) {
        $query .= " AND r.category = :category";
        $params['category'] = $category;
    }
    
    if ($search) {
        $query .= " AND (r.title LIKE :search OR r.description LIKE :search OR r.keywords LIKE :search)";
        $params['search'] = '%' . $search . '%';
    }
    
    $query .= " ORDER BY r.is_featured DESC, r.created_at DESC";
    
    $resources = $db->fetchAll($query, $params);
} catch (Exception $e) {
    error_log("Error fetching resources: " . $e->getMessage());
}

// Get resource categories
$categories = [];
try {
    $categories = $db->fetchAll(
        "SELECT DISTINCT category FROM resources WHERE is_active = 1 ORDER BY category"
    );
} catch (Exception $e) {
    error_log("Error fetching categories: " . $e->getMessage());
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
    <title>Resources - KioskHelp</title>
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
                <h1>Resource Library</h1>
                <p>Access guides, articles, and support materials</p>
            </div>

            <!-- Search and Filter -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-body">
                    <form method="GET" action="" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
                        <div class="form-group" style="margin: 0;">
                            <label>Search Resources</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by title, description, or keywords..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label>Filter by Category</label>
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $cat['category']))); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if ($category || $search): ?>
                            <a href="resources.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (empty($resources)): ?>
            <div class="alert alert-info">
                <p><strong><i class="fas fa-info-circle"></i> No Resources Found</strong></p>
                <?php if ($category || $search): ?>
                <p>No resources match your search criteria. Try adjusting your filters.</p>
                <?php else: ?>
                <p>The resource library is being updated with helpful guides and materials.</p>
                <?php endif; ?>
            </div>
            <?php else: ?>

            <!-- Featured Resources -->
            <?php 
            $featuredResources = array_filter($resources, function($r) { return $r['is_featured']; });
            if (!empty($featuredResources) && !$category && !$search): 
            ?>
            <div class="section" style="margin-bottom: 2rem;">
                <h2><i class="fas fa-star"></i> Featured Resources</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                    <?php foreach ($featuredResources as $resource): ?>
                    <div class="card" style="cursor: pointer; transition: transform 0.2s;" onclick="viewResource(<?php echo $resource['id']; ?>)">
                        <div class="card-body">
                            <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1rem;">
                                <div style="font-size: 2rem; color: #1976D2;">
                                    <i class="fas <?php echo $typeIcons[$resource['resource_type']] ?? 'fa-file'; ?>"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.1rem;">
                                        <?php echo htmlspecialchars($resource['title']); ?>
                                    </h3>
                                    <span class="badge badge-primary">
                                        <i class="fas <?php echo $categoryIcons[$resource['category']] ?? 'fa-tag'; ?>"></i>
                                        <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $resource['category']))); ?>
                                    </span>
                                </div>
                            </div>
                            <p style="color: #666; margin: 0 0 1rem 0; font-size: 0.9rem;">
                                <?php echo htmlspecialchars(substr($resource['description'], 0, 120)) . (strlen($resource['description']) > 120 ? '...' : ''); ?>
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #999;">
                                <span>
                                    <i class="fas fa-eye"></i> <?php echo number_format($resource['view_count']); ?> views
                                </span>
                                <span class="badge badge-secondary">
                                    <?php echo ucfirst($resource['resource_type']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- All Resources -->
            <div class="section">
                <h2>
                    <i class="fas fa-book-open"></i> 
                    <?php if ($category): ?>
                        <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $category))); ?> Resources
                    <?php elseif ($search): ?>
                        Search Results for "<?php echo htmlspecialchars($search); ?>"
                    <?php else: ?>
                        All Resources
                    <?php endif; ?>
                    <span style="font-size: 0.9rem; color: #666; font-weight: normal;">(<?php echo count($resources); ?>)</span>
                </h2>
                
                <div style="display: grid; gap: 1rem;">
                    <?php foreach ($resources as $resource): ?>
                    <div class="card" style="cursor: pointer; transition: all 0.2s;" onclick="viewResource(<?php echo $resource['id']; ?>)">
                        <div class="card-body" style="display: flex; gap: 1.5rem; align-items: start;">
                            <div style="font-size: 2.5rem; color: #1976D2;">
                                <i class="fas <?php echo $typeIcons[$resource['resource_type']] ?? 'fa-file'; ?>"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: start; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <h3 style="margin: 0; font-size: 1.2rem;">
                                        <?php echo htmlspecialchars($resource['title']); ?>
                                        <?php if ($resource['is_featured']): ?>
                                        <i class="fas fa-star" style="color: #FFC107; font-size: 0.9rem; margin-left: 0.25rem;"></i>
                                        <?php endif; ?>
                                    </h3>
                                </div>
                                <div style="margin-bottom: 0.75rem;">
                                    <span class="badge badge-info">
                                        <i class="fas <?php echo $categoryIcons[$resource['category']] ?? 'fa-tag'; ?>"></i>
                                        <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $resource['category']))); ?>
                                    </span>
                                    <span class="badge badge-secondary">
                                        <?php echo ucfirst($resource['resource_type']); ?>
                                    </span>
                                </div>
                                <p style="color: #666; margin: 0 0 0.75rem 0;">
                                    <?php echo htmlspecialchars($resource['description']); ?>
                                </p>
                                <div style="display: flex; gap: 1.5rem; font-size: 0.9rem; color: #999;">
                                    <span><i class="fas fa-eye"></i> <?php echo number_format($resource['view_count']); ?> views</span>
                                    <span><i class="fas fa-calendar"></i> Added <?php echo htmlspecialchars($resource['formatted_date']); ?></span>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-small">
                                    <i class="fas fa-arrow-right"></i> View
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php endif; ?>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        function viewResource(resourceId) {
            window.location.href = 'view_resource.php?id=' + resourceId;
        }
    </script>
</body>
</html>
