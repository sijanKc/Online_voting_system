<?php 
require_once 'includes/config.php'; 

// Protection Logic
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'candidate') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Candidate Dashboard | <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><?php echo __('site_name'); ?></a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white-50">Candidate: <?php echo $_SESSION['user_name']; ?></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="fw-bold mb-4">Candidate Portal</h2>
        
        <div class="alert alert-warning">
            Your profile is being reviewed by the Election Commission. You can apply for elections once approved.
        </div>

        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card card-premium p-4 border-0 h-100">
                    <h5 class="fw-bold mb-3">Campaign Statistics</h5>
                    <h1 class="display-1 fw-bold text-center py-4">0</h1>
                    <p class="text-center text-muted">Votes Received</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-premium p-4 border-0 h-100">
                    <h5 class="fw-bold mb-3">Manifesto Preview</h5>
                    <p class="text-muted small italic">"Building a better future through technology and transparency..."</p>
                    <button class="btn btn-outline-primary btn-sm mt-auto w-100">Edit Profile</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
