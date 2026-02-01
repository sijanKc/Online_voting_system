<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('register'); ?> | <?php echo SITE_NAME; ?></title>
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
            --current-theme: var(--voter-color);
        }
        body {
            background-color: #f8fafc;
        }
        .signup-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .side-panel {
            background: linear-gradient(135deg, #C8102E 0%, #003893 100%);
            color: #fff;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 0 0 100px 0;
        }
        .form-panel {
            padding: 40px;
        }
        .role-btn {
            padding: 15px 25px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            height: 100%;
        }
        .role-btn.active {
            border-color: var(--current-theme);
            background-color: #eff6ff;
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .theme-text { color: var(--current-theme); }
        .theme-bg { background-color: var(--current-theme); border-color: var(--current-theme); }
        
        .role-voter.active { --current-theme: var(--voter-color); }
        .role-candidate.active { --current-theme: var(--candidate-color); }
        
        .side-info { display: none; }
        .side-info.active { display: block; animation: fadeIn 0.5s; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
    </style>
</head>
<body>

    <div class="signup-container container-fluid p-0 overflow-hidden">
        <div class="row g-0 h-100 min-vh-100">
            <!-- Left Side-Panel -->
            <div class="col-lg-5 side-panel d-none d-lg-flex position-relative">
                <a href="index.php" class="btn btn-outline-light rounded-pill position-absolute top-0 start-0 m-4 px-4 py-2 small fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> <?php echo __('back_home'); ?>
                </a>

                <div class="text-center mb-5">
                    <div class="bg-white p-3 rounded-4 d-inline-block text-primary shadow-lg mb-4">
                        <i class="fas fa-fingerprint fs-1"></i>
                    </div>
                    <h2 class="fw-bold fs-1 mb-0"><?php echo SITE_NAME; ?></h2>
                    <p class="opacity-75"><?php echo __('official_portal'); ?></p>
                </div>

                <div id="side_content_voter" class="side-info active">
                    <h1 class="display-5 fw-bold mb-3"><?php echo __('voter'); ?></h1>
                    <p class="fs-5 mb-4"><?php echo __('voter_subtitle'); ?></p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> Secure Biometric Authentication</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> Real-time Voter List Updates</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> 24/7 Digital Support</li>
                    </ul>
                </div>

                <div id="side_content_candidate" class="side-info">
                    <h1 class="display-5 fw-bold mb-3"><?php echo __('candidate'); ?></h1>
                    <p class="fs-5 mb-4"><?php echo __('candidate_subtitle'); ?></p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-bullhorn me-3 fs-4"></i> Official Campaign Dashboard</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-file-alt me-3 fs-4"></i> Submit Manifesto & Identity</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-chart-line me-3 fs-4"></i> Track Voter Engagement</li>
                    </ul>
                </div>

                <div class="mt-auto opacity-50 small">
                    &copy; 2026 <?php echo SITE_NAME; ?> | <?php echo __('gov_nepal'); ?>
                </div>
            </div>

            <!-- Right Form-Panel -->
            <div class="col-lg-7 form-panel bg-white">
                <div class="max-width-600 mx-auto py-5 px-3">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <p class="text-muted mb-1"><?php echo __('hero_badge'); ?></p>
                            <h2 class="fw-bold"><?php echo __('register'); ?></h2>
                        </div>
                        <a href="login.php" class="text-primary fw-bold text-decoration-none"><?php echo __('login'); ?> <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger rounded-3 border-0 shadow-sm d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                            <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="actions/register_process.php" method="POST">
                        <!-- Role Selection -->
                        <div class="row g-3 mb-5">
                            <div class="col-6">
                                <div class="role-btn active role-voter" onclick="switchRole('voter', this)">
                                    <i class="fas fa-user-check fs-2 mb-3 theme-text"></i>
                                    <h6 class="fw-bold mb-0"><?php echo __('voter'); ?></h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="role-btn role-candidate" onclick="switchRole('candidate', this)">
                                    <i class="fas fa-user-tie fs-2 mb-3 theme-text"></i>
                                    <h6 class="fw-bold mb-0"><?php echo __('candidate'); ?></h6>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="role" id="target_role" value="voter">

                        <div class="row g-3 mt-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-id-card me-2 theme-text"></i> <?php echo __('personal_info'); ?></h5>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('full_name'); ?></label>
                                <input type="text" name="full_name" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('username'); ?></label>
                                <input type="text" name="username" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('email'); ?></label>
                                <input type="email" name="email" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('citizenship_no'); ?></label>
                                <input type="text" name="citizenship_no" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('password'); ?></label>
                                <input type="password" name="password" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('confirm_password'); ?></label>
                                <input type="password" name="confirm_password" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                        </div>

                        <!-- Candidate Fields -->
                        <div id="candidate_fields" class="mt-5" style="display: none;">
                            <h5 class="fw-bold mb-3 theme-text"><i class="fas fa-bullhorn me-2"></i> <?php echo __('candidate_res'); ?></h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted"><?php echo __('manifesto'); ?></label>
                                    <textarea name="manifesto" class="form-control border-0 bg-light rounded-3" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 mb-5">
                            <button type="submit" class="btn btn-primary theme-bg btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg">
                                <?php echo __('register'); ?>
                            </button>
                        </div>

                        <div class="text-center d-lg-none">
                            <a href="index.php" class="text-muted text-decoration-none small">
                                <i class="fas fa-arrow-left me-1"></i> <?php echo __('back_home'); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchRole(role, el) {
            document.getElementById('target_role').value = role;
            
            // UI Update
            document.querySelectorAll('.role-btn').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');
            
            // Side Content Update
            document.querySelectorAll('.side-info').forEach(info => info.classList.remove('active'));
            document.getElementById('side_content_' + role).classList.add('active');
            
            // Dynamic Fields
            const candFields = document.getElementById('candidate_fields');
            candFields.style.display = (role === 'candidate') ? 'block' : 'none';

            // Logo theme update
            document.querySelector('.side-panel').style.background = (role === 'candidate') 
                ? 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)' 
                : 'linear-gradient(135deg, #C8102E 0%, #003893 100%)';
        }
    </script>
</body>
</html>
