<?php
/**
 * Sidebar Navigation Include
 */
 
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <ul class="sidebar-menu">
        <li class="<?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
            <a href="dashboard.php">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="<?php echo $currentPage === 'assessment.php' ? 'active' : ''; ?>">
            <a href="assessment.php">
                <i class="fas fa-clipboard-list"></i>
                <span>Assessment</span>
            </a>
        </li>
        <li class="<?php echo $currentPage === 'referrals.php' ? 'active' : ''; ?>">
            <a href="referrals.php">
                <i class="fas fa-network-wired"></i>
                <span>Referrals</span>
            </a>
        </li>
        <li class="<?php echo $currentPage === 'appointments.php' ? 'active' : ''; ?>">
            <a href="appointments.php">
                <i class="fas fa-calendar-check"></i>
                <span>Appointments</span>
            </a>
        </li>
        <li class="<?php echo $currentPage === 'messages.php' ? 'active' : ''; ?>">
            <a href="messages.php">
                <i class="fas fa-comments"></i>
                <span>Messages</span>
            </a>
        </li>
        <li class="<?php echo $currentPage === 'resources.php' ? 'active' : ''; ?>">
            <a href="resources.php">
                <i class="fas fa-book-open"></i>
                <span>Resources</span>
            </a>
        </li>
        <li class="<?php echo $currentPage === 'tools.php' ? 'active' : ''; ?>">
            <a href="tools.php">
                <i class="fas fa-tools"></i>
                <span>Self-Help Tools</span>
            </a>
        </li>
        <li class="<?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>">
            <a href="profile.php">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
        </li>
    </ul>
</aside>
