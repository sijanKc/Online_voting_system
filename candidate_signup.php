<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('register'); ?> (Candidate) | <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --candidate-color: #6366f1;
        }
        body { background-color: #f8fafc; }
        .signup-container { min-height: 100vh; display: flex; align-items: center; }
        .side-panel {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #fff;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 0 0 100px 0;
        }
        .form-panel { padding: 40px; }
        .theme-text { color: var(--candidate-color); }
        .theme-bg { background-color: var(--candidate-color); border-color: var(--candidate-color); }
        
        .fade-in { animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
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
                        <i class="fas fa-bullhorn fs-1"></i>
                    </div>
                    <h2 class="fw-bold fs-1 mb-0"><?php echo SITE_NAME; ?></h2>
                    <p class="opacity-75"><?php echo __('official_portal'); ?></p>
                </div>

                <div class="side-info active">
                    <h1 class="display-5 fw-bold mb-3"><?php echo __('candidate'); ?> Registration</h1>
                    <p class="fs-5 mb-4">Lead the change. Serve the nation.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> Official Campaign Dashboard</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> Secure Nomination Filing</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> Real-time Result Tracking</li>
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
                            <p class="text-muted mb-1 badge bg-indigo bg-opacity-10 text-indigo px-3 py-2 rounded-pill fw-bold" style="background-color: #e0e7ff; color: #4338ca;">Candidate Portal</p>
                            <h2 class="fw-bold mt-2">Candidate Nomination</h2>
                        </div>
                        <a href="login.php" class="text-primary fw-bold text-decoration-none"><?php echo __('login'); ?> <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger rounded-3 border-0 shadow-sm d-flex align-items-center mb-4">
                            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                            <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="actions/register_process.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="role" value="candidate">

                        <div class="row g-3">
                            <h5 class="fw-bold mb-3"><i class="fas fa-id-card me-2 theme-text"></i> <?php echo __('personal_info'); ?></h5>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">First Name</label>
                                <input type="text" name="first_name" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Last Name</label>
                                <input type="text" name="last_name" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('username'); ?></label>
                                <input type="text" name="username" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Date of Birth</label>
                                <input type="date" name="dob" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('email'); ?></label>
                                <input type="email" name="email" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('citizenship_no'); ?></label>
                                <input type="text" name="citizenship_no" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>

                            <!-- Location Section -->
                            <div class="col-12 mt-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-map-marker-alt me-2 theme-text"></i> <?php echo __('address'); ?></h5>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Province</label>
                                <select name="province_id" id="province_id" class="form-select form-select-lg border-0 bg-light rounded-3" required>
                                    <option value="">Select Province</option>
                                    <?php
                                    $stmt = $pdo->query("SELECT id, name_" . $lang . " as name FROM provinces ORDER BY id ASC");
                                    while($row = $stmt->fetch()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">District</label>
                                <select name="district_id" id="district_id" class="form-select form-select-lg border-0 bg-light rounded-3" disabled required>
                                    <option value="">Select Province First</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Constituency</label>
                                <select name="constituency_id" id="constituency_id" class="form-select form-select-lg border-0 bg-light rounded-3" disabled required>
                                    <option value="">Select District First</option>
                                </select>
                            </div>

                            <!-- Political Details -->
                            <div class="col-12 mt-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-vote-yea me-2 theme-text"></i> Political Details</h5>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label small fw-bold text-muted">Political Party</label>
                                <select name="party_id" id="party_id" class="form-select form-select-lg border-0 bg-light rounded-3" required>
                                    <option value="">Select Political Party</option>
                                    <?php
                                    $stmt = $pdo->query("SELECT id, name_" . $lang . " as name, logo_path FROM political_parties WHERE status = 'active' ORDER BY name ASC");
                                    while($row = $stmt->fetch()) {
                                        // Dynamic File Extension Check (fixes SVG/JPG issue)
                                        $base_name = pathinfo($row['logo_path'], PATHINFO_FILENAME);
                                        $dir = 'assets/images/parties/';
                                        $exts = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
                                        $final_path = $row['logo_path']; // Default fallback from DB
                                        
                                        // Check which file actually exists on server
                                        foreach($exts as $ext) {
                                            if(file_exists($dir . $base_name . '.' . $ext)) {
                                                $final_path = $dir . $base_name . '.' . $ext;
                                                break;
                                            }
                                        }

                                        echo "<option value='" . $row['id'] . "' data-logo='" . $final_path . "' data-name='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Logo Preview & Upload Section -->
                            <div class="col-md-12 mb-3" id="party_logo_wrapper" style="display: none;">
                                <label class="form-label small fw-bold text-muted">Election Symbol</label>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Preview Box -->
                                    <div class="bg-white p-2 rounded-3 border shadow-sm text-center" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                                        <img id="logo_preview" src="" alt="Party Logo" class="img-fluid" style="max-height: 80px; max-width: 80px;">
                                    </div>
                                    
                                    <!-- Info & Upload -->
                                    <div class="flex-grow-1">
                                        <p id="party_msg" class="small text-muted mb-2">Selected Party Symbol</p>
                                        
                                        <!-- File Input (Only for Independent) -->
                                        <div id="independent_upload_box" style="display: none;">
                                            <input type="file" name="party_logo" id="party_logo_input" class="form-control form-control-sm" accept="image/*" onchange="previewCustomLogo(this)">
                                            <div class="form-text small">Upload your custom symbol (Mobile Phone, etc.)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted"><?php echo __('manifesto'); ?></label>
                                <textarea name="manifesto" class="form-control border-0 bg-light rounded-3" rows="3" placeholder="Write your brief election manifesto..."></textarea>
                            </div>

                            <!-- Security -->
                            <div class="col-12 mt-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-lock me-2 theme-text"></i> Security</h5>
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

                        <div class="mt-5 mb-5">
                            <button type="submit" class="btn btn-primary theme-bg btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg">
                                Submit Nomination
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/location_cascade.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new LocationCascade({
                provinceId: 'province_id',
                districtId: 'district_id',
                constituencyId: 'constituency_id'
            });

            handlePartySelection('party_id', 'party_logo_container');
        });
    </script>
</body>
</html>
