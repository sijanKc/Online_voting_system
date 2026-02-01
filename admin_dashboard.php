<?php 
require_once 'includes/config.php'; 

// Protection Logic
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Placeholder Sidebar -->
        <div class="bg-dark text-white p-4" style="min-height: 100vh; width: 250px;">
            <h4 class="fw-bold mb-5"><?php echo __('site_name'); ?></h4>
            <ul class="nav flex-column gap-3">
                <li class="nav-item"><a href="#" class="nav-link text-white active">Overview</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-secondary">Elections</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-secondary">Voters</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-secondary">Candidates</a></li>
                <li class="nav-item mt-5"><a href="logout.php" class="nav-link text-danger">Logout</a></li>
            </ul>
        </div>

        <div class="flex-grow-1 p-5">
            <header class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="fw-bold">Admin Central Control</h2>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">Welcome, <strong><?php echo $_SESSION['user_name']; ?></strong></span>
                    <img src="https://ui-avatars.com/api/?name=Admin" class="rounded-circle" width="40">
                </div>
            </header>

            <div class="alert alert-info">
                This is the <strong>Admin Dashboard Shell</strong>. Full management features will be added in Phase 2 & 3.
            </div>

            <!-- Stats Grid -->
            <div class="row g-4 mt-2">
                <div class="col-md-3">
                    <div class="card card-premium p-4 border-0">
                        <small class="text-muted text-uppercase fw-bold">Total Voters</small>
                        <h2 class="fw-bold mb-0">--</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-premium p-4 border-0">
                        <small class="text-muted text-uppercase fw-bold">Total Candidates</small>
                        <h2 class="fw-bold mb-0">--</h2>
                    </div>
                </div>
                <!-- ... other stats ... -->
            </div>
        </div>
    </div>
</body>
</html>
