<?php
require_once __DIR__ . '/../../includes/config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Unauthorized access! Please login as Admin.";
    header("Location: ../login.php");
    exit();
}

$lang = $_SESSION['lang'] ?? 'en';
require_once __DIR__ . "/../../languages/$lang.php";
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --admin-crimson: #C8102E;
            --admin-navy: #003893;
            --admin-dark: #1a1a1a;
            --admin-light-bg: #f5f7fb;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--admin-light-bg);
        }
        
        /* Government Top Bar (matching landing page) */
        .gov-admin-bar {
            background: linear-gradient(135deg, var(--admin-crimson) 0%, var(--admin-navy) 100%);
            color: white;
            padding: 12px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Admin Navbar */
        .admin-navbar {
            background: white;
            border-bottom: 3px solid var(--admin-crimson);
            padding: 15px 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .admin-navbar .logo-box {
            background: var(--admin-crimson);
            padding: 10px;
            border-radius: 10px;
        }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: var(--admin-dark);
            color: white;
            padding-top: 140px;
            z-index: 999;
            overflow-y: auto;
        }
        
        .nav-link-admin {
            color: rgba(255,255,255,0.7);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }
        
        .nav-link-admin:hover, .nav-link-admin.active {
            color: #fff;
            background: rgba(200,16,46,0.2);
            border-left-color: var(--admin-crimson);
        }
        
        .nav-link-admin i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
            padding-top: 160px;
        }
        
        /* Stats Cards */
        .stat-card-ecn {
            background: white;
            border-radius: 15px;
            padding: 25px;
            border-left: 5px solid var(--admin-crimson);
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s;
        }
        
        .stat-card-ecn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        
        .stat-icon-ecn {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        /* Table Styling */
        .ecn-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .badge-pending { background: #fff3e0; color: #ef6c00; font-weight: 600; }
        .badge-approved { background: #e8f5e9; color: #2e7d32; font-weight: 600; }
        .badge-rejected { background: #ffebee; color: #c62828; font-weight: 600; }
    </style>
</head>
<body>

    <!-- Official Government Top Bar (ECN Style) -->
    <div class="gov-admin-bar position-fixed w-100 top-0" style="z-index: 1050;">
        <div class="container-fluid px-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-landmark me-2"></i>
                        <span class="fw-bold small"><?php echo __('gov_nepal'); ?> | <?php echo __('official_portal'); ?></span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <span class="small me-3">
                        <i class="far fa-calendar-alt me-1"></i> <?php echo __('date_today'); ?> <?php echo date('Y-m-d'); ?>
                    </span>
                    <span class="small me-3 border-end pe-3">
                        <a href="?lang=en" class="text-decoration-none text-white <?php echo $lang == 'en' ? 'fw-bold border-bottom' : 'opacity-75'; ?>">EN</a>
                        <span class="mx-1">/</span>
                        <a href="?lang=ne" class="text-decoration-none text-white <?php echo $lang == 'ne' ? 'fw-bold border-bottom' : 'opacity-75'; ?>">नेपाली</a>
                    </span>
                    <span class="small me-3">
                        <i class="fas fa-user-shield me-1"></i> <?php echo $_SESSION['user_name']; ?>
                    </span>
                    <a href="<?php echo BASE_URL; ?>logout.php" class="btn btn-sm btn-light text-danger rounded-pill px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> <?php echo __('logout'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Navbar (ECN Style) -->
    <nav class="admin-navbar position-fixed w-100" style="top: 48px; z-index: 1040;">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="logo-box text-white me-3">
                        <i class="fas fa-fingerprint fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><?php echo SITE_NAME; ?></h5>
                        <small class="text-muted"><?php echo __('admin'); ?> Control Panel</small>
                    </div>
                </div>
                <div>
                    <!-- View Site removed as requested -->
                </div>
            </div>
        </div>
    </nav>
