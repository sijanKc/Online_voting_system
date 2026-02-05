<div class="admin-sidebar">
    <div class="text-center mb-4 px-3">
        <div class="bg-white bg-opacity-10 p-3 rounded-3 d-inline-block mb-2">
            <i class="fas fa-shield-alt fs-2 text-white"></i>
        </div>
        <h6 class="fw-bold text-white mb-0"><?php echo __('admin'); ?> Portal</h6>
        <small class="text-white-50"><?php echo __('official_portal'); ?></small>
    </div>

    <div class="nav flex-column">
        <a href="dashboard.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> <?php echo __('dashboard'); ?>
        </a>
        <a href="user_approvals.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'user_approvals.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-check"></i> <?php echo __('user_approvals'); ?>
        </a>
        <a href="voter_list.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'voter_list.php' ? 'active' : ''; ?>">
            <i class="fas fa-address-book"></i> <?php echo __('voter_roll'); ?>
        </a>
        <a href="elections.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'elections.php' ? 'active' : ''; ?>">
            <i class="fas fa-vote-yea"></i> <?php echo __('elections'); ?>
        </a>
        <a href="candidates.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'candidates.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-tie"></i> <?php echo __('candidate'); ?> List
        </a>
        <a href="nominations.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'nominations.php' ? 'active' : ''; ?>">
            <i class="fas fa-file-signature"></i> <?php echo __('nomination_desk'); ?>
        </a>
        <a href="results.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i> <?php echo __('results'); ?>
        </a>
        <a href="settings.php" class="nav-link-admin <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> <?php echo __('system_settings'); ?>
        </a>
    </div>
    
    <div class="mt-auto px-4 py-4 border-top border-white border-opacity-10 position-absolute bottom-0 w-100">
        <p class="small text-white-50 mb-0 text-center">
            <i class="fas fa-shield-alt me-1"></i> Secure Admin Access
        </p>
    </div>
</div>
