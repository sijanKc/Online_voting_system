<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="voter-sidebar d-none d-lg-block">
    <div class="px-4 mb-4">
        <h6 class="text-uppercase extra-small fw-bold text-muted mb-3 ls-1">Core Navigation</h6>
    </div>
    
    <a href="dashboard.php" class="nav-link-voter <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
        <i class="fas fa-th-large"></i> <?php echo __('dashboard'); ?>
    </a>
    
    <a href="elections.php" class="nav-link-voter <?php echo $current_page == 'elections.php' ? 'active' : ''; ?>">
        <i class="fas fa-vote-yea"></i> <?php echo __('active_elections'); ?>
    </a>
    
    <a href="my_votes.php" class="nav-link-voter <?php echo $current_page == 'my_votes.php' ? 'active' : ''; ?>">
        <i class="fas fa-history"></i> <?php echo __('voting_history'); ?>
    </a>

    <div class="px-4 mt-5 mb-3">
        <h6 class="text-uppercase extra-small fw-bold text-muted mb-3 ls-1">Resources</h6>
    </div>
    
    <a href="results.php" class="nav-link-voter <?php echo $current_page == 'results.php' ? 'active' : ''; ?>">
        <i class="fas fa-chart-pie"></i> <?php echo __('results'); ?>
    </a>
    
    <a href="profile.php" class="nav-link-voter <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
        <i class="fas fa-user-check"></i> <?php echo __('profile'); ?>
    </a>

    <div class="mt-auto p-4">
        <div class="bg-primary bg-opacity-10 p-3 rounded-4">
            <h6 class="fw-bold text-primary small mb-1"><?php echo __('support_hub'); ?>?</h6>
            <p class="extra-small text-muted mb-0"><?php echo __('contact_desc'); ?></p>
        </div>
    </div>
</div>
