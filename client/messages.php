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

// Handle mark as read
if (isset($_POST['mark_read']) && isset($_POST['message_id'])) {
    try {
        $db->update('messages',
            ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')],
            'id = :id AND recipient_id = :recipient_id',
            ['id' => $_POST['message_id'], 'recipient_id' => $user['id']]
        );
    } catch (Exception $e) {
        error_log("Error marking message as read: " . $e->getMessage());
    }
}

// Fetch messages
$messages = [];
try {
    $messages = $db->fetchAll(
        "SELECT m.*, 
                sender.username as sender_username,
                sender.first_name as sender_first_name,
                sender.last_name as sender_last_name,
                DATE_FORMAT(m.created_at, '%M %d, %Y at %h:%i %p') as formatted_date
         FROM messages m
         LEFT JOIN users sender ON m.sender_id = sender.id
         WHERE m.recipient_id = :user_id
         ORDER BY m.created_at DESC",
        ['user_id' => $user['id']]
    );
} catch (Exception $e) {
    error_log("Error fetching messages: " . $e->getMessage());
}

// Separate unread and read messages
$unreadMessages = array_filter($messages, function($m) { return !$m['is_read']; });
$readMessages = array_filter($messages, function($m) { return $m['is_read']; });
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - KioskHelp</title>
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
                <h1>Messages</h1>
                <p>Communicate with case managers and service providers</p>
            </div>

            <?php if (empty($messages)): ?>
            <div class="alert alert-info">
                <p><strong><i class="fas fa-info-circle"></i> No Messages</strong></p>
                <p>You don't have any messages yet. Your case manager or service providers will send you updates here.</p>
            </div>
            <?php else: ?>
            
            <!-- Summary Stats -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($messages); ?></div>
                        <div class="stat-label">Total Messages</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($unreadMessages); ?></div>
                        <div class="stat-label">Unread</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-value"><?php echo count($readMessages); ?></div>
                        <div class="stat-label">Read</div>
                    </div>
                </div>
            </div>

            <!-- Unread Messages -->
            <?php if (!empty($unreadMessages)): ?>
            <div class="section" style="margin-bottom: 2rem;">
                <h2><i class="fas fa-envelope"></i> Unread Messages</h2>
                <div class="card">
                    <div class="card-body" style="padding: 0;">
                        <?php foreach ($unreadMessages as $message): ?>
                        <div class="message-item" style="padding: 1.5rem; border-bottom: 1px solid #eee; background: #f8f9fa; cursor: pointer;" onclick="viewMessage(<?php echo $message['id']; ?>)">
                            <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-envelope" style="color: #1976D2;"></i>
                                        <strong style="font-size: 1.1rem;">
                                            <?php echo htmlspecialchars($message['sender_first_name'] . ' ' . $message['sender_last_name']); ?>
                                        </strong>
                                        <span class="badge badge-primary">New</span>
                                    </div>
                                    <?php if ($message['subject']): ?>
                                    <div style="font-weight: 600; margin-bottom: 0.5rem;">
                                        <?php echo htmlspecialchars($message['subject']); ?>
                                    </div>
                                    <?php endif; ?>
                                    <div style="color: #666; margin-bottom: 0.5rem;">
                                        <?php 
                                        $preview = substr(strip_tags($message['message_body']), 0, 150);
                                        echo htmlspecialchars($preview);
                                        if (strlen($message['message_body']) > 150) echo '...';
                                        ?>
                                    </div>
                                    <div style="color: #999; font-size: 0.9rem;">
                                        <i class="fas fa-clock"></i> <?php echo htmlspecialchars($message['formatted_date']); ?>
                                    </div>
                                </div>
                                <div>
                                    <button onclick="event.stopPropagation(); markAsRead(<?php echo $message['id']; ?>)" class="btn btn-small btn-primary">
                                        <i class="fas fa-check"></i> Mark Read
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Read Messages -->
            <?php if (!empty($readMessages)): ?>
            <div class="section">
                <h2><i class="fas fa-envelope-open"></i> Read Messages</h2>
                <div class="card">
                    <div class="card-body" style="padding: 0;">
                        <?php foreach (array_slice($readMessages, 0, 20) as $message): ?>
                        <div class="message-item" style="padding: 1rem; border-bottom: 1px solid #eee; opacity: 0.85; cursor: pointer;" onclick="viewMessage(<?php echo $message['id']; ?>)">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-envelope-open" style="color: #999;"></i>
                                        <strong>
                                            <?php echo htmlspecialchars($message['sender_first_name'] . ' ' . $message['sender_last_name']); ?>
                                        </strong>
                                    </div>
                                    <?php if ($message['subject']): ?>
                                    <div style="margin-bottom: 0.5rem;">
                                        <?php echo htmlspecialchars($message['subject']); ?>
                                    </div>
                                    <?php endif; ?>
                                    <div style="color: #999; font-size: 0.9rem;">
                                        <i class="fas fa-clock"></i> <?php echo htmlspecialchars($message['formatted_date']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (count($readMessages) > 20): ?>
                        <div style="text-align: center; padding: 1rem;">
                            <p style="color: #666;">Showing 20 most recent read messages</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php endif; ?>

            <!-- Message Modal -->
            <div id="messageModal" class="modal" style="display: none;">
                <div class="modal-content" style="max-width: 700px;">
                    <div class="modal-header">
                        <h2 id="modalSubject">Message</h2>
                        <button onclick="closeMessageModal()" class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="modalFrom" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;"></div>
                        <div id="modalBody" style="line-height: 1.6;"></div>
                    </div>
                    <div class="modal-footer">
                        <button onclick="closeMessageModal()" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        const messages = <?php echo json_encode($messages); ?>;

        function viewMessage(messageId) {
            const message = messages.find(m => m.id == messageId);
            if (!message) return;

            document.getElementById('modalSubject').textContent = message.subject || 'Message';
            document.getElementById('modalFrom').innerHTML = `
                <strong>From:</strong> ${message.sender_first_name} ${message.sender_last_name}<br>
                <strong>Date:</strong> ${message.formatted_date}
            `;
            document.getElementById('modalBody').innerHTML = message.message_body.replace(/\n/g, '<br>');
            
            document.getElementById('messageModal').style.display = 'flex';

            // Mark as read if unread
            if (!message.is_read) {
                markAsRead(messageId);
            }
        }

        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        function markAsRead(messageId) {
            const formData = new FormData();
            formData.append('mark_read', '1');
            formData.append('message_id', messageId);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            }).then(() => {
                location.reload();
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target == modal) {
                closeMessageModal();
            }
        }
    </script>
</body>
</html>
