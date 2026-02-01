<div class="admin-sidebar">
    <div class="text-center mb-4 px-3">
        <div class="bg-white bg-opacity-10 p-3 rounded-3 d-inline-block mb-2">
            <i class="fas fa-shield-alt fs-2 text-white"></i>
        </div>
        <h6 class="fw-bold text-white mb-0">Admin Portal</h6>
        <small class="text-white-50">Electoral Management</small>
    </div>

    <div class="nav flex-column">
        <a href="dashboard.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="user_approvals.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'user_approvals.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-check"></i> User Approvals
        </a>
        <a href="elections.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'elections.php' ? 'active' : ''; ?>">
            <i class="fas fa-vote-yea"></i> Elections
        </a>
        <a href="candidates.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'candidates.php' ? 'active' : ''; ?>">
            <i class="fas fa-users-viewfinder"></i> Candidates
        </a>
        <a href="results.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i> Results
        </a>
        <a href="settings.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Settings
        </a>
    </div>
    
    <div class="mt-auto px-4 py-4 border-top border-white border-opacity-10 position-absolute bottom-0 w-100">
        <p class="small text-white-50 mb-0 text-center">
            <i class="fas fa-shield-alt me-1"></i> Secure Admin Access
        </p>
    </div>
</div>
