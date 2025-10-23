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

// Get referral ID
$referralId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$referralId) {
    header('Location: referrals.php');
    exit;
}

// Fetch referral details
try {
    $referral = $db->fetch(
        "SELECT r.*, sp.provider_name, sp.provider_type, sp.phone, sp.email, sp.website,
                sp.address, sp.city, sp.state, sp.zip_code, sp.description, sp.services_offered,
                sp.operating_hours, sp.accepts_walkins,
                DATE_FORMAT(r.referred_at, '%M %d, %Y at %h:%i %p') as formatted_referred_date,
                DATE_FORMAT(r.accepted_at, '%M %d, %Y at %h:%i %p') as formatted_accepted_date,
                DATE_FORMAT(r.completed_at, '%M %d, %Y at %h:%i %p') as formatted_completed_date
         FROM referrals r
         LEFT JOIN service_providers sp ON r.provider_id = sp.id
         WHERE r.id = :id AND r.client_id = :client_id",
        ['id' => $referralId, 'client_id' => $user['client_id']]
    );
    
    if (!$referral) {
        header('Location: referrals.php');
        exit;
    }
} catch (Exception $e) {
    error_log("Error fetching referral: " . $e->getMessage());
    header('Location: referrals.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Details - KioskHelp</title>
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
                    <h1>Referral Details</h1>
                    <p><?php echo htmlspecialchars($referral['provider_name']); ?></p>
                </div>
                <a href="referrals.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Referrals
                </a>
            </div>

            <!-- Referral Status -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Referral Status
                </div>
                <div class="card-body">
                    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                        <div>
                            <strong>Status:</strong>
                            <span class="badge badge-<?php 
                                $statusColors = [
                                    'pending' => 'secondary',
                                    'accepted' => 'info',
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                    'declined' => 'error',
                                    'cancelled' => 'error'
                                ];
                                echo $statusColors[$referral['status']] ?? 'secondary';
                            ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $referral['status'])); ?>
                            </span>
                        </div>
                        <div>
                            <strong>Priority:</strong>
                            <span class="badge badge-<?php echo $referral['priority'] === 'urgent' ? 'error' : ($referral['priority'] === 'high' ? 'warning' : 'info'); ?>">
                                <?php echo ucfirst($referral['priority']); ?>
                            </span>
                        </div>
                        <div>
                            <strong>Referral Type:</strong>
                            <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $referral['referral_type']))); ?>
                        </div>
                        <div>
                            <strong>Referred On:</strong>
                            <?php echo htmlspecialchars($referral['formatted_referred_date']); ?>
                        </div>
                        <?php if ($referral['accepted_at']): ?>
                        <div>
                            <strong>Accepted On:</strong>
                            <?php echo htmlspecialchars($referral['formatted_accepted_date']); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($referral['completed_at']): ?>
                        <div>
                            <strong>Completed On:</strong>
                            <?php echo htmlspecialchars($referral['formatted_completed_date']); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($referral['notes']): ?>
                    <div style="margin-top: 1.5rem;">
                        <strong>Referral Notes:</strong>
                        <div style="margin-top: 0.5rem; padding: 1rem; background: #f5f5f5; border-radius: 4px;">
                            <?php echo nl2br(htmlspecialchars($referral['notes'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($referral['outcome']): ?>
                    <div style="margin-top: 1.5rem;">
                        <strong>Outcome:</strong>
                        <div style="margin-top: 0.5rem; padding: 1rem; background: #e8f5e9; border-radius: 4px;">
                            <?php echo nl2br(htmlspecialchars($referral['outcome'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Service Provider Information -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <i class="fas fa-building"></i> Service Provider Information
                </div>
                <div class="card-body">
                    <h2 style="margin-top: 0;"><?php echo htmlspecialchars($referral['provider_name']); ?></h2>
                    
                    <?php if ($referral['description']): ?>
                    <p style="color: #666; margin-bottom: 1.5rem;">
                        <?php echo nl2br(htmlspecialchars($referral['description'])); ?>
                    </p>
                    <?php endif; ?>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <div>
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt"></i> Location</h3>
                            <?php if ($referral['address']): ?>
                            <p style="margin: 0;">
                                <?php echo htmlspecialchars($referral['address']); ?><br>
                                <?php if ($referral['city']): ?>
                                    <?php echo htmlspecialchars($referral['city']); ?><?php echo $referral['state'] ? ', ' . htmlspecialchars($referral['state']) : ''; ?> <?php echo htmlspecialchars($referral['zip_code']); ?>
                                <?php endif; ?>
                            </p>
                            <?php else: ?>
                            <p style="margin: 0; color: #999;">Address not available</p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem;"><i class="fas fa-phone"></i> Contact</h3>
                            <?php if ($referral['phone']): ?>
                            <p style="margin: 0;">
                                Phone: <a href="tel:<?php echo htmlspecialchars($referral['phone']); ?>"><?php echo htmlspecialchars($referral['phone']); ?></a>
                            </p>
                            <?php endif; ?>
                            <?php if ($referral['email']): ?>
                            <p style="margin: 0;">
                                Email: <a href="mailto:<?php echo htmlspecialchars($referral['email']); ?>"><?php echo htmlspecialchars($referral['email']); ?></a>
                            </p>
                            <?php endif; ?>
                            <?php if ($referral['website']): ?>
                            <p style="margin: 0;">
                                Website: <a href="<?php echo htmlspecialchars($referral['website']); ?>" target="_blank"><?php echo htmlspecialchars($referral['website']); ?></a>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($referral['operating_hours']): ?>
                    <div style="margin-top: 1.5rem;">
                        <h3 style="font-size: 1rem; margin-bottom: 0.5rem;"><i class="fas fa-clock"></i> Operating Hours</h3>
                        <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($referral['operating_hours'])); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($referral['services_offered']): ?>
                    <div style="margin-top: 1.5rem;">
                        <h3 style="font-size: 1rem; margin-bottom: 0.5rem;"><i class="fas fa-list"></i> Services Offered</h3>
                        <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($referral['services_offered'])); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($referral['accepts_walkins']): ?>
                    <div style="margin-top: 1.5rem;">
                        <span class="badge badge-success">
                            <i class="fas fa-check"></i> Accepts Walk-ins
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tools"></i> Actions
                </div>
                <div class="card-body">
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <?php if ($referral['phone']): ?>
                        <a href="tel:<?php echo htmlspecialchars($referral['phone']); ?>" class="btn btn-primary">
                            <i class="fas fa-phone"></i> Call Provider
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($referral['email']): ?>
                        <a href="mailto:<?php echo htmlspecialchars($referral['email']); ?>" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Email Provider
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($referral['address']): ?>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($referral['address'] . ' ' . $referral['city'] . ' ' . $referral['state']); ?>" target="_blank" class="btn btn-secondary">
                            <i class="fas fa-map"></i> Get Directions
                        </a>
                        <?php endif; ?>
                        
                        <a href="appointments.php?referral_id=<?php echo $referral['id']; ?>" class="btn btn-success">
                            <i class="fas fa-calendar-plus"></i> Schedule Appointment
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
