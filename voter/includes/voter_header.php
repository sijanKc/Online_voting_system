<?php
require_once __DIR__ . '/../../includes/config.php';

// Check if user is logged in and is a voter
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    $_SESSION['error'] = "Unauthorized access! Please login as a Voter.";
    header("Location: ../login.php");
    exit();
}

$lang = $_SESSION['lang'] ?? 'en';
require_once __DIR__ . "/../../languages/$lang.php";

// Fetch voter location data if not in session
if (!isset($_SESSION['constituency_id'])) {
    $stmt = $pdo->prepare("SELECT province_id, district_id, constituency_id FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $u = $stmt->fetch();
    $_SESSION['province_id'] = $u['province_id'];
    $_SESSION['district_id'] = $u['district_id'];
    $_SESSION['constituency_id'] = $u['constituency_id'];
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Portal | <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --voter-primary: #003893;
            --voter-secondary: #C8102E;
            --voter-dark: #001a4d;
            --voter-light-bg: #f8fafc;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--voter-light-bg);
        }
        
        /* Government Top Bar */
        .gov-voter-bar {
            background: linear-gradient(135deg, var(--voter-primary) 0%, var(--voter-dark) 100%);
            color: white;
            padding: 10px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Voter Navbar */
        .voter-navbar {
            background: white;
            border-bottom: 3px solid var(--voter-primary);
            padding: 12px 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .voter-navbar .logo-box {
            background: var(--voter-primary);
            padding: 8px;
            border-radius: 8px;
        }
        
        /* Sidebar */
        .voter-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: #fff;
            border-right: 1px solid rgba(0,0,0,0.05);
            padding-top: 130px;
            z-index: 999;
            overflow-y: auto;
        }
        
        .nav-link-voter {
            color: #64748b;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.3s;
            font-weight: 500;
            border-radius: 0 50px 50px 0;
            margin-right: 15px;
            margin-bottom: 5px;
        }
        
        .nav-link-voter:hover, .nav-link-voter.active {
            color: var(--voter-primary);
            background: rgba(0, 56, 147, 0.05);
        }
        
        .nav-link-voter.active {
            color: #fff;
            background: var(--voter-primary);
        }
        
        .nav-link-voter i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
            padding-top: 150px;
        }
        
        /* Dashboard Cards */
        .voter-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            transition: 0.3s;
        }
        
        .voter-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }

        .stat-icon-voter {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .badge-active { background: #e0f2fe; color: #0369a1; }
        .badge-upcoming { background: #fef3c7; color: #92400e; }
        .badge-completed { background: #f1f5f9; color: #475569; }
    </style>
</head>
<body>

    <!-- Official Government Top Bar -->
    <div class="gov-voter-bar position-fixed w-100 top-0" style="z-index: 1050;">
        <div class="container-fluid px-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-landmark me-2"></i>
                        <span class="fw-bold small"><?php echo __('gov_nepal'); ?> | Voter Portal</span>
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
                    <a href="<?php echo BASE_URL; ?>logout.php" class="btn btn-sm btn-light text-danger rounded-pill px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> <?php echo __('logout'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Voter Navbar -->
    <nav class="voter-navbar position-fixed w-100" style="top: 45px; z-index: 1040;">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="logo-box text-white me-3">
                        <i class="fas fa-fingerprint fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><?php echo SITE_NAME; ?></h5>
                        <small class="text-muted"><?php echo __('voter'); ?> Portal</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="text-end me-3 d-none d-md-block">
                        <p class="small fw-bold mb-0"><?php echo $_SESSION['user_name']; ?></p>
                        <p class="extra-small text-muted mb-0">Registered Voter</p>
                    </div>
                    <div class="bg-light p-2 rounded-circle">
                        <i class="fas fa-user-circle fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>
