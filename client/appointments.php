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

// Fetch client appointments
$clientId = $user['client_id'];
$appointments = [];

try {
    $appointments = $db->fetchAll(
        "SELECT a.*, sp.provider_name, sp.provider_type, sp.phone, sp.address, sp.city, sp.state,
                DATE_FORMAT(a.appointment_date, '%M %d, %Y') as formatted_date,
                DATE_FORMAT(a.appointment_time, '%h:%i %p') as formatted_time
         FROM appointments a
         LEFT JOIN service_providers sp ON a.provider_id = sp.id
         WHERE a.client_id = :client_id
         ORDER BY a.appointment_date DESC, a.appointment_time DESC",
        ['client_id' => $clientId]
    );
} catch (Exception $e) {
    error_log("Error fetching appointments: " . $e->getMessage());
}

// Separate upcoming and past appointments
$upcomingAppointments = [];
$pastAppointments = [];
$today = date('Y-m-d');

foreach ($appointments as $appointment) {
    if ($appointment['appointment_date'] >= $today && in_array($appointment['status'], ['scheduled', 'confirmed'])) {
        $upcomingAppointments[] = $appointment;
    } else {
        $pastAppointments[] = $appointment;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - KioskHelp</title>
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
                <h1>My Appointments</h1>
                <p>Schedule and manage appointments with service providers</p>
            </div>

            <?php if (empty($appointments)): ?>
            <div class="alert alert-info">
                <p><strong><i class="fas fa-info-circle"></i> No Appointments Scheduled</strong></p>
                <p>You don't have any appointments yet. Schedule an appointment with a service provider to get started.</p>
                <div style="margin-top: 1rem;">
                    <a href="referrals.php" class="btn btn-primary">
                        <i class="fas fa-network-wired"></i> View Referrals
                    </a>
                </div>
            </div>
            <?php else: ?>
            
            <!-- Summary Stats -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($appointments); ?></div>
                        <div class="stat-label">Total Appointments</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($upcomingAppointments); ?></div>
                        <div class="stat-label">Upcoming</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count(array_filter($appointments, function($a) { return $a['status'] === 'completed'; })); ?></div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <?php if (!empty($upcomingAppointments)): ?>
            <div class="section" style="margin-bottom: 2rem;">
                <h2><i class="fas fa-calendar-check"></i> Upcoming Appointments</h2>
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($upcomingAppointments as $appointment): ?>
                        <div class="appointment-item" style="padding: 1.5rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <h3 style="margin: 0;"><?php echo htmlspecialchars($appointment['provider_name']); ?></h3>
                                    <span class="badge badge-<?php echo $appointment['status'] === 'confirmed' ? 'success' : 'info'; ?>">
                                        <?php echo ucfirst($appointment['status']); ?>
                                    </span>
                                </div>
                                <div style="color: #666; margin-bottom: 0.5rem;">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $appointment['appointment_type']))); ?>
                                </div>
                                <div style="color: #666; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 1.5rem;">
                                    <span>
                                        <i class="fas fa-calendar"></i> <?php echo htmlspecialchars($appointment['formatted_date']); ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-clock"></i> <?php echo htmlspecialchars($appointment['formatted_time']); ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-hourglass-half"></i> <?php echo $appointment['duration_minutes']; ?> minutes
                                    </span>
                                </div>
                                <?php if ($appointment['address']): ?>
                                <div style="color: #666; margin-bottom: 0.5rem;">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    <?php echo htmlspecialchars($appointment['address']); ?>
                                    <?php if ($appointment['city']): ?>
                                        , <?php echo htmlspecialchars($appointment['city']); ?><?php echo $appointment['state'] ? ', ' . htmlspecialchars($appointment['state']) : ''; ?>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($appointment['phone']): ?>
                                <div style="color: #666; margin-bottom: 0.5rem;">
                                    <i class="fas fa-phone"></i> <a href="tel:<?php echo htmlspecialchars($appointment['phone']); ?>"><?php echo htmlspecialchars($appointment['phone']); ?></a>
                                </div>
                                <?php endif; ?>
                                <?php if ($appointment['notes']): ?>
                                <div style="margin-top: 0.5rem; padding: 0.75rem; background: #f5f5f5; border-radius: 4px;">
                                    <strong>Notes:</strong> <?php echo htmlspecialchars($appointment['notes']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <?php if ($appointment['address']): ?>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($appointment['address'] . ' ' . $appointment['city'] . ' ' . $appointment['state']); ?>" target="_blank" class="btn btn-small btn-secondary">
                                    <i class="fas fa-map"></i> Directions
                                </a>
                                <?php endif; ?>
                                <button onclick="cancelAppointment(<?php echo $appointment['id']; ?>)" class="btn btn-small btn-error">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Past Appointments -->
            <?php if (!empty($pastAppointments)): ?>
            <div class="section">
                <h2><i class="fas fa-history"></i> Past Appointments</h2>
                <div class="card">
                    <div class="card-body">
                        <?php foreach (array_slice($pastAppointments, 0, 10) as $appointment): ?>
                        <div class="appointment-item" style="padding: 1rem; border-bottom: 1px solid #eee; opacity: 0.8;">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <strong><?php echo htmlspecialchars($appointment['provider_name']); ?></strong>
                                        <span class="badge badge-<?php 
                                            $statusColors = ['completed' => 'success', 'cancelled' => 'error', 'no_show' => 'warning'];
                                            echo $statusColors[$appointment['status']] ?? 'secondary';
                                        ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $appointment['status'])); ?>
                                        </span>
                                    </div>
                                    <div style="color: #666; font-size: 0.9rem;">
                                        <i class="fas fa-calendar"></i> <?php echo htmlspecialchars($appointment['formatted_date']); ?> at <?php echo htmlspecialchars($appointment['formatted_time']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (count($pastAppointments) > 10): ?>
                        <div style="text-align: center; padding: 1rem;">
                            <p style="color: #666;">Showing 10 most recent appointments</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php endif; ?>

            <!-- Help Text -->
            <div class="alert alert-info" style="margin-top: 2rem;">
                <p><strong><i class="fas fa-lightbulb"></i> Tip:</strong> To schedule a new appointment, visit your referrals page and select a service provider.</p>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        function cancelAppointment(appointmentId) {
            if (confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')) {
                // In a real implementation, this would call an API endpoint
                showAlert('info', 'Appointment cancellation feature will be implemented in a future update.');
                // TODO: Implement API call to cancel appointment
                // fetch('../api/cancel_appointment.php', {
                //     method: 'POST',
                //     headers: { 'Content-Type': 'application/json' },
                //     body: JSON.stringify({ appointment_id: appointmentId })
                // }).then(response => response.json())
                //   .then(data => {
                //       if (data.success) {
                //           showAlert('success', 'Appointment cancelled successfully');
                //           setTimeout(() => location.reload(), 1500);
                //       } else {
                //           showAlert('error', data.message || 'Failed to cancel appointment');
                //       }
                //   });
            }
        }
    </script>
</body>
</html>
