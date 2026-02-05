<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('login'); ?> | <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --voter-color: #003893;
            --candidate-color: #6366f1;
            --admin-color: #C8102E;
            --current-theme: var(--voter-color);
        }
        body {
            background-color: #ffffff;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .side-panel {
            background: linear-gradient(135deg, #003893 0%, #001a4d 100%);
            color: #fff;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 0 0 150px 0;
            transition: all 0.5s ease;
        }
        .form-panel {
            padding: 40px;
        }
        .role-pill {
            display: flex;
            background: #f1f5f9;
            padding: 6px;
            border-radius: 50px;
            margin-bottom: 40px;
        }
        .role-pill-btn {
            flex: 1;
            padding: 12px;
            border-radius: 50px;
            border: none;
            background: transparent;
            font-weight: 700;
            font-size: 0.9rem;
            color: #64748b;
            transition: 0.3s;
        }
        .role-pill-btn.active {
            background: #ffffff;
            color: var(--current-theme);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .theme-text { color: var(--current-theme); transition: color 0.3s; }
        .theme-bg { background-color: var(--current-theme); border-color: var(--current-theme); transition: all 0.3s; }
        
        .side-info { display: none; }
        .side-info.active { display: block; animation: fadeIn 0.5s; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }
    </style>
</head>
<body>

    <!-- Official Government Top Bar -->
    <div class="gov-top-bar bg-light py-1 border-bottom d-none d-lg-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                <i class="fas fa-landmark me-2 text-primary"></i>
                <span class="fw-bold"><?php echo __('gov_nepal'); ?></span> | <?php echo __('official_portal'); ?>
            </div>
            <div class="small text-muted">
                <i class="far fa-calendar-alt me-1"></i> <?php echo __('date_today'); ?> <?php echo date('Y-m-d'); ?>
                <span class="mx-2">|</span>
                <a href="?lang=en" class="text-decoration-none text-muted <?php echo $lang == 'en' ? 'fw-bold text-primary' : ''; ?>">English</a>
                <span class="mx-1">/</span>
                <a href="?lang=ne" class="text-decoration-none text-muted <?php echo $lang == 'ne' ? 'fw-bold text-primary' : ''; ?>">नेपाली</a>
            </div>
        </div>
    </div>

    <div class="login-container container-fluid p-0 overflow-hidden">
        <div class="row g-0 h-100 min-vh-100">
            
            <!-- Left Side-Panel (Info) -->
            <div class="col-lg-5 side-panel d-none d-lg-flex position-relative">
                <div class="back-btn">
                    <a href="index.php" class="btn btn-outline-light rounded-pill px-4 py-2 small fw-bold">
                        <i class="fas fa-arrow-left me-2"></i> <?php echo __('back_home'); ?>
                    </a>
                </div>

                <div class="text-center mb-5 mt-5">
                    <div class="bg-white p-3 rounded-4 d-inline-block text-primary shadow-lg mb-4">
                        <i class="fas fa-fingerprint fs-1"></i>
                    </div>
                    <h1 class="fw-bold display-5 mb-0"><?php echo SITE_NAME; ?></h1>
                    <p class="opacity-75 fs-5"><?php echo __('official_portal'); ?></p>
                </div>

                <div id="side_info_voter" class="side-info active">
                    <h2 class="fw-bold mb-3"><?php echo __('voter'); ?> Portal</h2>
                    <p class="fs-5 opacity-75 mb-4"><?php echo __('voter_desc'); ?></p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-shield-alt"></i></div>
                        <span><?php echo __('encryption'); ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-check-double"></i></div>
                        <span><?php echo __('one_person_one_vote'); ?></span>
                    </div>
                </div>

                <div id="side_info_candidate" class="side-info">
                    <h2 class="fw-bold mb-3"><?php echo __('candidate'); ?> Portal</h2>
                    <p class="fs-5 opacity-75 mb-4"><?php echo __('candidate_desc'); ?></p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-chart-bar"></i></div>
                        <span><?php echo __('campaign_analytics'); ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-user-edit"></i></div>
                        <span><?php echo __('profile'); ?> Management</span>
                    </div>
                </div>

                <div id="side_info_admin" class="side-info">
                    <h2 class="fw-bold mb-3"><?php echo __('admin'); ?> Control</h2>
                    <p class="fs-5 opacity-75 mb-4"><?php echo __('admin_desc'); ?></p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-tools"></i></div>
                        <span><?php echo __('system_config'); ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-user-shield"></i></div>
                        <span><?php echo __('voter'); ?> Verification</span>
                    </div>
                </div>

                <div class="mt-auto opacity-50 small">
                    <?php echo __('gov_nepal'); ?> | &copy; 2026 <?php echo __('official_portal'); ?>
                </div>
            </div>

            <!-- Right Form-Panel -->
            <div class="col-lg-7 form-panel bg-white d-flex align-items-center">
                <div class="w-100 mx-auto" style="max-width: 450px;">
                    
                    <div class="text-center mb-5 d-lg-none">
                        <h2 class="fw-bold theme-text"><?php echo SITE_NAME; ?></h2>
                        <a href="index.php" class="text-muted text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> <?php echo __('back_home'); ?></a>
                    </div>

                    <div class="mb-5">
                        <h2 class="fw-bold mb-2"><?php echo __('login'); ?></h2>
                        <p class="text-muted"><?php echo __('voter_subtitle'); ?></p>
                    </div>

                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="actions/login_process.php" method="POST">
                        <!-- Role Toggle Pill -->
                        <div class="role-pill">
                            <button type="button" class="role-pill-btn active" onclick="setRole('voter', this)"><?php echo __('voter'); ?></button>
                            <button type="button" class="role-pill-btn" onclick="setRole('candidate', this)"><?php echo __('candidate'); ?></button>
                            <button type="button" class="role-pill-btn" onclick="setRole('admin', this)"><?php echo __('admin'); ?></button>
                        </div>

                        <input type="hidden" name="role" id="login_role" value="voter">

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted"><?php echo __('user_id_email'); ?></label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light rounded-start-4 px-3"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="username" class="form-control form-control-lg border-0 bg-light rounded-end-4" required>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label small fw-bold text-muted"><?php echo __('password'); ?></label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light rounded-start-4 px-3"><i class="fas fa-key text-muted"></i></span>
                                <input type="password" name="password" class="form-control form-control-lg border-0 bg-light rounded-end-4" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary theme-bg btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg mb-4">
                            <?php echo __('login'); ?>
                        </button>

                        <div class="text-center">
                            <p class="text-muted mb-0"><?php echo __('dont_have_account'); ?> <a href="signup.php" class="theme-text fw-bold text-decoration-none"><?php echo __('register'); ?></a></p>
                        </div>
                   </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setRole(role, btn) {
            document.getElementById('login_role').value = role;
            
            // Update Toggle
            document.querySelectorAll('.role-pill-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Update Side Info
            document.querySelectorAll('.side-info').forEach(info => info.classList.remove('active'));
            document.getElementById('side_info_' + role).classList.add('active');
            
            // Update Colors
            const root = document.documentElement;
            const sidePanel = document.querySelector('.side-panel');
            
            if(role === 'candidate') {
                root.style.setProperty('--current-theme', '#6366f1');
                sidePanel.style.background = 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)';
            } else if(role === 'admin') {
                root.style.setProperty('--current-theme', '#C8102E');
                sidePanel.style.background = 'linear-gradient(135deg, #C8102E 0%, #a30d25 100%)';
            } else {
                root.style.setProperty('--current-theme', '#003893');
                sidePanel.style.background = 'linear-gradient(135deg, #003893 0%, #001a4d 100%)';
            }
        }
    </script>
</body>
</html>
