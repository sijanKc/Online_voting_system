<?php 
require_once 'includes/config.php'; 

// Protection Logic
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'voter') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voter Dashboard | <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><?php echo __('site_name'); ?></a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white-50">Hello, <?php echo $_SESSION['user_name']; ?></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="fw-bold mb-4">Voter Dashboard</h2>
        
        <div class="alert alert-success">
            Welcome to your dashboard! Here you will see active elections and cast your votes.
        </div>

        <div class="row g-4 mt-4">
            <div class="col-md-8">
                <div class="card card-premium p-4 border-0">
                    <h5 class="fw-bold mb-3">Available Elections</h5>
                    <p class="text-muted">No active elections at the moment. Please check back later.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-premium p-4 border-0">
                    <h5 class="fw-bold mb-3">Your Profile</h5>
                    <ul class="list-unstyled small">
                        <li><strong>Status:</strong> <span class="badge bg-success">Approved</span></li>
                        <li><strong>Citizenship:</strong> Verify through Admin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
