<?php
require_once __DIR__ . '/../../includes/config.php';

// Check if user is logged in and is a candidate
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidate') {
    $_SESSION['error'] = "Unauthorized access! Please login as Candidate.";
    header("Location: ../login.php");
    exit();
}

$lang = $_SESSION['lang'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard | <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --candidate-primary: #6366f1;
            --candidate-dark: #4f46e5;
            --candidate-light: #818cf8;
            --bg-light: #f5f7fb;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
        }
        
        /* Government Top Bar */
        .gov-candidate-bar {
            background: linear-gradient(135deg, var(--candidate-primary) 0%, var(--candidate-dark) 100%);
            color: white;
            padding: 12px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Candidate Navbar */
        .candidate-navbar {
            background: white;
            border-bottom: 3px solid var(--candidate-primary);
            padding: 15px 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .candidate-navbar .logo-box {
            background: var(--candidate-primary);
            padding: 10px;
            border-radius: 10px;
        }
        
        /* Sidebar */
        .candidate-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            padding-top: 140px;
            z-index: 999;
            overflow-y: auto;
        }
        
        .nav-link-candidate {
            color: rgba(255,255,255,0.7);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }
        
        .nav-link-candidate:hover, .nav-link-candidate.active {
            color: #fff;
            background: rgba(99,102,241,0.2);
            border-left-color: var(--candidate-primary);
        }
        
        .nav-link-candidate i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
            padding-top: 160px;
        }
        
        /* Stats Cards */
        .stat-card-candidate {
            background: white;
            border-radius: 15px;
            padding: 25px;
            border-left: 5px solid var(--candidate-primary);
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s;
        }
        
        .stat-card-candidate:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        
        .stat-icon-candidate {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .badge-active { background: #e8f5e9; color: #2e7d32; font-weight: 600; }
        .badge-upcoming { background: #fff3e0; color: #ef6c00; font-weight: 600; }
        .badge-completed { background: #e3f2fd; color: #1565c0; font-weight: 600; }
    </style>
</head>
<body>

    <!-- Government Top Bar -->
    <div class="gov-candidate-bar position-fixed w-100 top-0" style="z-index: 1050;">
        <div class="container-fluid px-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-landmark me-2"></i>
                        <span class="fw-bold small">Government of Nepal | Official Portal</span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <span class="small me-3">
                        <i class="far fa-calendar-alt me-1"></i> <?php echo date('Y-m-d'); ?>
                    </span>
                    <span class="small me-3">
                        <i class="fas fa-user-tie me-1"></i> <?php echo $_SESSION['user_name'] ?? 'Candidate'; ?>
                    </span>
                    <a href="../actions/logout.php" class="btn btn-sm btn-light text-danger rounded-pill px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Candidate Navbar -->
    <nav class="candidate-navbar position-fixed w-100" style="top: 48px; z-index: 1040;">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="logo-box text-white me-3">
                        <i class="fas fa-fingerprint fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><?php echo SITE_NAME; ?></h5>
                        <small class="text-muted">Candidate Portal</small>
                    </div>
                </div>
                <div>
                    <a href="../index.php" class="btn btn-outline-primary rounded-pill px-4 me-2">
                        <i class="fas fa-external-link-alt me-2"></i> View Site
                    </a>
                </div>
            </div>
        </div>
    </nav>
