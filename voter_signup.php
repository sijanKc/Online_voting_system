<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('register'); ?> (Voter) | <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --voter-color: #003893;
        }
        body { background-color: #f8fafc; }
        .signup-container { min-height: 100vh; display: flex; align-items: center; }
        .side-panel {
            background: linear-gradient(135deg, #C8102E 0%, #003893 100%);
            color: #fff;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 0 0 100px 0;
        }
        .form-panel { padding: 40px; }
        .theme-text { color: var(--voter-color); }
        .theme-bg { background-color: var(--voter-color); border-color: var(--voter-color); }
        .modal-body th { width: 40%; background-color: #f8fafc; }
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

                <div class="side-info active">
                    <h1 class="display-5 fw-bold mb-3"><?php echo __('voter'); ?> Registration</h1>
                    <p class="fs-5 mb-4">Join the digital democracy of Nepal.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> Secure Biometric Authentication</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> Real-time Voter List Updates</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-3 fs-4"></i> 24/7 Digital Support</li>
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
                            <p class="text-muted mb-1 badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">Voter Portal</p>
                            <h2 class="fw-bold mt-2">Create Account</h2>
                        </div>
                        <a href="login.php" class="text-primary fw-bold text-decoration-none"><?php echo __('login'); ?> <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger rounded-3 border-0 shadow-sm d-flex align-items-center mb-4">
                            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                            <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form id="voterForm" action="actions/register_process.php" method="POST">
                        <input type="hidden" name="role" value="voter">

                        <div class="row g-3">
                            <h5 class="fw-bold mb-3"><i class="fas fa-id-card me-2 theme-text"></i> <?php echo __('personal_info'); ?></h5>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('username'); ?></label>
                                <input type="text" name="username" id="username" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Date of Birth</label>
                                <input type="date" name="dob" id="dob" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                                <div id="dob-error" class="text-danger small mt-1" style="display:none;">You must be 18 years or older.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('email'); ?></label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('citizenship_no'); ?></label>
                                <input type="text" name="citizenship_no" id="citizenship_no" class="form-control form-control-lg border-0 bg-light rounded-3" required>
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

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-lock me-2 theme-text"></i> Security</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('password'); ?></label>
                                <input type="password" name="password" id="password" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><?php echo __('confirm_password'); ?></label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg border-0 bg-light rounded-3" required>
                                <div id="password-error" class="text-danger small mt-1" style="display:none;">Passwords do not match.</div>
                            </div>
                        </div>

                        <div class="mt-5 mb-5">
                            <button type="button" class="btn btn-primary theme-bg btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg" onclick="validateAndReview()">
                                Proceed to Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-bottom-0 rounded-top-4">
                    <h5 class="modal-title fw-bold" id="reviewModalLabel"><i class="fas fa-check-double me-2"></i> Review Your Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="alert alert-info border-0 rounded-3 small"><i class="fas fa-info-circle me-2"></i> Please ensure all details are correct. You cannot edit them after submission.</p>
                    <table class="table table-bordered rounded-3 overflow-hidden">
                        <tbody>
                            <tr><th>Name</th><td><span id="rev_firstname"></span> <span id="rev_lastname"></span></td></tr>
                            <tr><th>Date of Birth</th><td id="rev_dob"></td></tr>
                            <tr><th>Username</th><td id="rev_username"></td></tr>
                            <tr><th>Email</th><td id="rev_email"></td></tr>
                            <tr><th>Citizenship No</th><td id="rev_citizenship"></td></tr>
                            <tr><th>Province</th><td id="rev_province"></td></tr>
                            <tr><th>District</th><td id="rev_district"></td></tr>
                            <tr><th>Constituency</th><td id="rev_constituency"></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer border-top-0 p-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Edit Details</button>
                    <button type="button" class="btn btn-primary theme-bg rounded-pill px-4 fw-bold shadow-sm" onclick="submitForm()">Confirm & Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/location_cascade.js"></script>
    <script>
        // Initialize Cascade System
        document.addEventListener('DOMContentLoaded', () => {
            new LocationCascade({
                provinceId: 'province_id',
                districtId: 'district_id',
                constituencyId: 'constituency_id'
            });
        });

        function validateAndReview() {
            const form = document.getElementById('voterForm');
            
            // Basic Validity Check
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Password Match Check
            const pwd = document.getElementById('password').value;
            const cpwd = document.getElementById('confirm_password').value;
            if (pwd !== cpwd) {
                document.getElementById('password-error').style.display = 'block';
                return;
            } else {
                document.getElementById('password-error').style.display = 'none';
            }

            // Age Validation (18+)
            const dob = new Date(document.getElementById('dob').value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            if (age < 18) {
                document.getElementById('dob-error').style.display = 'block';
                return;
            } else {
                document.getElementById('dob-error').style.display = 'none';
            }

            // Populate Review Modal
            document.getElementById('rev_firstname').textContent = document.getElementById('first_name').value;
            document.getElementById('rev_lastname').textContent = document.getElementById('last_name').value;
            document.getElementById('rev_dob').textContent = document.getElementById('dob').value;
            document.getElementById('rev_username').textContent = document.getElementById('username').value;
            document.getElementById('rev_email').textContent = document.getElementById('email').value;
            document.getElementById('rev_citizenship').textContent = document.getElementById('citizenship_no').value;
            
            const provSelect = document.getElementById('province_id');
            document.getElementById('rev_province').textContent = provSelect.options[provSelect.selectedIndex].text;

            const distSelect = document.getElementById('district_id');
            document.getElementById('rev_district').textContent = distSelect.options[distSelect.selectedIndex].text;

            const constSelect = document.getElementById('constituency_id');
            document.getElementById('rev_constituency').textContent = constSelect.options[constSelect.selectedIndex].text;

            // Show Modal
            const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            modal.show();
        }

        function submitForm() {
            document.getElementById('voterForm').submit();
        }
    </script>
</body>
</html>
