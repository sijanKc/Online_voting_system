<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard | VOTEPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
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
                        <i class="fas fa-user-tie me-1"></i> Candidate Portal
                    </span>
                    <a href="actions/logout.php" class="btn btn-sm btn-light text-danger rounded-pill px-3">
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
                        <h5 class="fw-bold mb-0 text-dark">VOTEPORT</h5>
                        <small class="text-muted">Candidate Portal</small>
                    </div>
                </div>
                <div>
                    <a href="index.php" class="btn btn-outline-primary rounded-pill px-4 me-2">
                        <i class="fas fa-home me-2"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="candidate-sidebar">
        <div class="text-center mb-4 px-3">
            <div class="bg-white bg-opacity-10 p-3 rounded-3 d-inline-block mb-2">
                <i class="fas fa-user-tie fs-2 text-white"></i>
            </div>
            <h6 class="fw-bold text-white mb-0">Candidate Hub</h6>
            <small class="text-white-50">Campaign Management</small>
        </div>

        <div class="nav flex-column">
            <a href="candidate_dashboard.php" class="nav-link-candidate active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="my_profile.php" class="nav-link-candidate">
                <i class="fas fa-user-circle"></i> My Profile
            </a>
            <a href="my_elections.php" class="nav-link-candidate">
                <i class="fas fa-vote-yea"></i> My Elections
            </a>
            <a href="campaign_materials.php" class="nav-link-candidate">
                <i class="fas fa-bullhorn"></i> Campaign Materials
            </a>
            <a href="manifesto.php" class="nav-link-candidate">
                <i class="fas fa-file-alt"></i> Manifesto
            </a>
            <a href="analytics.php" class="nav-link-candidate">
                <i class="fas fa-chart-line"></i> Analytics
            </a>
        </div>
        
        <div class="mt-auto px-4 py-4 border-top border-white border-opacity-10 position-absolute bottom-0 w-100">
            <p class="small text-white-50 mb-0 text-center">
                <i class="fas fa-shield-alt me-1"></i> Verified Candidate
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Header -->
        <div class="mb-5">
            <h2 class="fw-bold text-dark mb-2">
                <i class="fas fa-bullhorn" style="color: var(--candidate-primary);"></i> Campaign Dashboard
            </h2>
            <p class="text-muted">Manage your electoral campaign and track your participation status.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-candidate">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Active Elections</p>
                            <h2 class="fw-bold mb-0 text-dark">0</h2>
                        </div>
                        <div class="stat-icon-candidate bg-success bg-opacity-10 text-success">
                            <i class="fas fa-vote-yea"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-candidate">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Total Votes</p>
                            <h2 class="fw-bold mb-0 text-dark">0</h2>
                        </div>
                        <div class="stat-icon-candidate" style="background: rgba(99,102,241,0.1); color: var(--candidate-primary);">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-candidate">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Applications</p>
                            <h2 class="fw-bold mb-0 text-dark">0</h2>
                        </div>
                        <div class="stat-icon-candidate bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-file-signature"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-candidate">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Profile Status</p>
                            <h6 class="fw-bold mb-0 text-success">Approved</h6>
                        </div>
                        <div class="stat-icon-candidate bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="my_elections.php" class="text-decoration-none">
                    <div class="stat-card-candidate border-start border-5" style="border-color: var(--candidate-primary) !important;">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-candidate me-3" style="background: rgba(99,102,241,0.1); color: var(--candidate-primary);">
                                <i class="fas fa-vote-yea"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Apply for Election</h6>
                                <p class="small text-muted mb-0">Submit your candidacy application</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="manifesto.php" class="text-decoration-none">
                    <div class="stat-card-candidate border-start border-5 border-info">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-candidate bg-info bg-opacity-10 text-info me-3">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Update Manifesto</h6>
                                <p class="small text-muted mb-0">Edit your campaign promises</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="analytics.php" class="text-decoration-none">
                    <div class="stat-card-candidate border-start border-5 border-success">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-candidate bg-success bg-opacity-10 text-success me-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">View Analytics</h6>
                                <p class="small text-muted mb-0">Track campaign performance</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Available Elections -->
        <div class="stat-card-candidate p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div>
                    <h5 class="fw-bold mb-1 text-dark">
                        <i class="fas fa-list-ul me-2" style="color: var(--candidate-primary);"></i> Available Elections
                    </h5>
                    <p class="small text-muted mb-0">Elections you can apply for</p>
                </div>
            </div>
            <div class="text-center py-5">
                <i class="fas fa-inbox fs-1 text-muted mb-3 d-block"></i>
                <h6 class="text-muted">No Active Elections</h6>
                <p class="small text-muted mb-0">New elections will appear here when they are announced by the admin.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
