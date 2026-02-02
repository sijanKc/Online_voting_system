<div class="candidate-sidebar">
    <div class="text-center mb-4 px-3">
        <div class="bg-white bg-opacity-10 p-3 rounded-3 d-inline-block mb-2">
            <i class="fas fa-user-tie fs-2 text-white"></i>
        </div>
        <h6 class="fw-bold text-white mb-0">Candidate Hub</h6>
        <small class="text-white-50">Campaign Management</small>
    </div>

    <div class="nav flex-column">
        <a href="dashboard.php" class="nav-link-candidate <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="my_profile.php" class="nav-link-candidate <?php echo basename($_SERVER['PHP_SELF']) == 'my_profile.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-circle"></i> My Profile
        </a>
        <a href="my_elections.php" class="nav-link-candidate <?php echo basename($_SERVER['PHP_SELF']) == 'my_elections.php' ? 'active' : ''; ?>">
            <i class="fas fa-vote-yea"></i> My Elections
        </a>
        <a href="campaign_materials.php" class="nav-link-candidate <?php echo basename($_SERVER['PHP_SELF']) == 'campaign_materials.php' ? 'active' : ''; ?>">
            <i class="fas fa-bullhorn"></i> Campaign Materials
        </a>
        <a href="manifesto.php" class="nav-link-candidate <?php echo basename($_SERVER['PHP_SELF']) == 'manifesto.php' ? 'active' : ''; ?>">
            <i class="fas fa-file-alt"></i> Manifesto
        </a>
        <a href="analytics.php" class="nav-link-candidate <?php echo basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> Analytics
        </a>
    </div>
    
    <div class="mt-auto px-4 py-4 border-top border-white border-opacity-10 position-absolute bottom-0 w-100">
        <p class="small text-white-50 mb-0 text-center">
            <i class="fas fa-shield-alt me-1"></i> Verified Candidate
        </p>
    </div>
</div>
