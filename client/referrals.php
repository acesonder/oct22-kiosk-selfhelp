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

// Fetch client referrals
$clientId = $user['client_id'];
$referrals = [];

try {
    $referrals = $db->fetchAll(
        "SELECT r.*, sp.provider_name, sp.provider_type, sp.phone, sp.email, sp.address, sp.city, sp.state,
                DATE_FORMAT(r.referred_at, '%M %d, %Y') as formatted_date
         FROM referrals r
         LEFT JOIN service_providers sp ON r.provider_id = sp.id
         WHERE r.client_id = :client_id
         ORDER BY 
            CASE r.priority
                WHEN 'urgent' THEN 1
                WHEN 'high' THEN 2
                WHEN 'medium' THEN 3
                WHEN 'low' THEN 4
            END,
            r.referred_at DESC",
        ['client_id' => $clientId]
    );
} catch (Exception $e) {
    error_log("Error fetching referrals: " . $e->getMessage());
}

// Group referrals by status
$groupedReferrals = [
    'pending' => [],
    'accepted' => [],
    'in_progress' => [],
    'completed' => [],
    'declined' => [],
    'cancelled' => []
];

foreach ($referrals as $referral) {
    $status = $referral['status'];
    if (isset($groupedReferrals[$status])) {
        $groupedReferrals[$status][] = $referral;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referrals - KioskHelp</title>
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
                <h1>My Referrals</h1>
                <p>Track your referrals to service providers</p>
            </div>

            <?php if (empty($referrals)): ?>
            <div class="alert alert-info">
                <p><strong><i class="fas fa-info-circle"></i> No Referrals Yet</strong></p>
                <p>You haven't been referred to any service providers yet. Complete an assessment to get matched with services that can help you.</p>
                <div style="margin-top: 1rem;">
                    <a href="assessment.php" class="btn btn-primary">
                        <i class="fas fa-clipboard-list"></i> Take Assessment
                    </a>
                </div>
            </div>
            <?php else: ?>
            
            <!-- Summary Stats -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($referrals); ?></div>
                        <div class="stat-label">Total Referrals</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($groupedReferrals['pending']) + count($groupedReferrals['accepted']) + count($groupedReferrals['in_progress']); ?></div>
                        <div class="stat-label">Active Referrals</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($groupedReferrals['completed']); ?></div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
            </div>

            <!-- Active Referrals -->
            <?php if (count($groupedReferrals['pending']) + count($groupedReferrals['accepted']) + count($groupedReferrals['in_progress']) > 0): ?>
            <div class="section" style="margin-bottom: 2rem;">
                <h2><i class="fas fa-hourglass-half"></i> Active Referrals</h2>
                <div class="card">
                    <div class="card-body">
                        <?php 
                        $activeStatuses = ['pending', 'accepted', 'in_progress'];
                        foreach ($activeStatuses as $status):
                            foreach ($groupedReferrals[$status] as $referral):
                        ?>
                        <div class="referral-item" style="padding: 1.5rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <h3 style="margin: 0;"><?php echo htmlspecialchars($referral['provider_name']); ?></h3>
                                    <span class="badge badge-<?php echo $referral['priority'] === 'urgent' ? 'error' : ($referral['priority'] === 'high' ? 'warning' : 'info'); ?>">
                                        <?php echo ucfirst($referral['priority']); ?>
                                    </span>
                                    <span class="badge badge-<?php echo $status === 'in_progress' ? 'warning' : ($status === 'accepted' ? 'info' : 'secondary'); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                    </span>
                                </div>
                                <div style="color: #666; margin-bottom: 0.5rem;">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $referral['referral_type']))); ?>
                                </div>
                                <?php if ($referral['address']): ?>
                                <div style="color: #666; margin-bottom: 0.5rem;">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    <?php echo htmlspecialchars($referral['address']); ?>
                                    <?php if ($referral['city']): ?>
                                        , <?php echo htmlspecialchars($referral['city']); ?><?php echo $referral['state'] ? ', ' . htmlspecialchars($referral['state']) : ''; ?>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($referral['phone']): ?>
                                <div style="color: #666; margin-bottom: 0.5rem;">
                                    <i class="fas fa-phone"></i> <a href="tel:<?php echo htmlspecialchars($referral['phone']); ?>"><?php echo htmlspecialchars($referral['phone']); ?></a>
                                </div>
                                <?php endif; ?>
                                <div style="color: #999; font-size: 0.9rem;">
                                    <i class="fas fa-calendar"></i> Referred on <?php echo htmlspecialchars($referral['formatted_date']); ?>
                                </div>
                                <?php if ($referral['notes']): ?>
                                <div style="margin-top: 0.5rem; padding: 0.75rem; background: #f5f5f5; border-radius: 4px;">
                                    <strong>Notes:</strong> <?php echo htmlspecialchars($referral['notes']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="view_referral.php?id=<?php echo $referral['id']; ?>" class="btn btn-small btn-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                        <?php 
                            endforeach;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Completed Referrals -->
            <?php if (count($groupedReferrals['completed']) > 0): ?>
            <div class="section" style="margin-bottom: 2rem;">
                <h2><i class="fas fa-check-circle"></i> Completed Referrals</h2>
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($groupedReferrals['completed'] as $referral): ?>
                        <div class="referral-item" style="padding: 1.5rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: start; gap: 1rem; opacity: 0.8;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <h3 style="margin: 0;"><?php echo htmlspecialchars($referral['provider_name']); ?></h3>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Completed
                                    </span>
                                </div>
                                <div style="color: #666; margin-bottom: 0.5rem;">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $referral['referral_type']))); ?>
                                </div>
                                <div style="color: #999; font-size: 0.9rem;">
                                    <i class="fas fa-calendar"></i> Completed on <?php echo date('F d, Y', strtotime($referral['completed_at'])); ?>
                                </div>
                            </div>
                            <div>
                                <a href="view_referral.php?id=<?php echo $referral['id']; ?>" class="btn btn-small btn-secondary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Declined/Cancelled Referrals -->
            <?php if (count($groupedReferrals['declined']) + count($groupedReferrals['cancelled']) > 0): ?>
            <div class="section">
                <details>
                    <summary style="cursor: pointer; font-size: 1.1rem; font-weight: 600; padding: 0.5rem 0;">
                        <i class="fas fa-times-circle"></i> Declined or Cancelled Referrals (<?php echo count($groupedReferrals['declined']) + count($groupedReferrals['cancelled']); ?>)
                    </summary>
                    <div class="card" style="margin-top: 1rem;">
                        <div class="card-body">
                            <?php 
                            foreach (array_merge($groupedReferrals['declined'], $groupedReferrals['cancelled']) as $referral): 
                            ?>
                            <div class="referral-item" style="padding: 1rem; border-bottom: 1px solid #eee; opacity: 0.6;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong><?php echo htmlspecialchars($referral['provider_name']); ?></strong>
                                        <span class="badge badge-error" style="margin-left: 0.5rem;">
                                            <?php echo ucfirst($referral['status']); ?>
                                        </span>
                                    </div>
                                    <div style="font-size: 0.9rem; color: #999;">
                                        <?php echo htmlspecialchars($referral['formatted_date']); ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </details>
            </div>
            <?php endif; ?>

            <?php endif; ?>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>
