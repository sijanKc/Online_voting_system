<?php require_once 'includes/candidate_header.php'; ?>
<?php require_once 'includes/candidate_sidebar.php'; ?>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-2">
            <i class="fas fa-user-circle text-primary me-2"></i> My Profile
        </h2>
        <p class="text-muted">Manage your personal information and account security.</p>
    </div>

    <?php
    try {
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT u.*, pr.name_en as province_name, d.name_en as district_name, c.name_en as constituency_name
                               FROM users u
                               LEFT JOIN provinces pr ON u.province_id = pr.id
                               LEFT JOIN districts d ON u.district_id = d.id
                               LEFT JOIN constituencies c ON u.constituency_id = c.id
                               WHERE u.id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        $user = [];
    }
    ?>

    <div class="row g-4">
        <!-- Personal Info -->
        <div class="col-lg-8">
            <div class="stat-card-candidate p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3">Personal Details</h5>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">First Name</label>
                        <p class="bg-light p-3 rounded-3 mb-0"><?php echo htmlspecialchars($user['first_name'] ?? ''); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Last Name</label>
                        <p class="bg-light p-3 rounded-3 mb-0"><?php echo htmlspecialchars($user['last_name'] ?? ''); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Email</label>
                        <p class="bg-light p-3 rounded-3 mb-0"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Username</label>
                        <p class="bg-light p-3 rounded-3 mb-0"><?php echo htmlspecialchars($user['username'] ?? ''); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Citizenship No.</label>
                        <p class="bg-light p-3 rounded-3 mb-0"><?php echo htmlspecialchars($user['citizenship_number'] ?? ''); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Date of Birth</label>
                        <p class="bg-light p-3 rounded-3 mb-0"><?php echo htmlspecialchars($user['dob'] ?? ''); ?></p>
                    </div>
                </div>

                <div class="mt-5 border-top pt-4 text-end">
                    <button class="btn btn-primary rounded-pill px-4" disabled>
                        <i class="fas fa-lock me-2"></i> Edit Profile (Contact Admin)
                    </button>
                    <p class="small text-muted mt-2">Personal details require administrative verification for changes.</p>
                </div>
            </div>
        </div>

        <!-- Location Info -->
        <div class="col-lg-4">
            <div class="stat-card-candidate p-4 mb-4" style="background: #f8fafc;">
                <h5 class="fw-bold mb-4 border-bottom pb-3">Electoral Area</h5>
                
                <div class="mb-3">
                    <label class="extra-small text-muted fw-bold text-uppercase">Province</label>
                    <p class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($user['province_name'] ?? 'N/A'); ?></p>
                </div>
                <div class="mb-3">
                    <label class="extra-small text-muted fw-bold text-uppercase">District</label>
                    <p class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($user['district_name'] ?? 'N/A'); ?></p>
                </div>
                <div class="mb-0">
                    <label class="extra-small text-muted fw-bold text-uppercase">Constituency</label>
                    <p class="fw-bold mb-0 text-indigo" style="color: #4f46e5;"><?php echo htmlspecialchars($user['constituency_name'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <div class="stat-card-candidate p-4 border-start border-5 border-warning">
                <h6 class="fw-bold mb-3">Account Security</h6>
                <a href="change_password.php" class="btn btn-outline-warning btn-sm w-100 rounded-pill fw-bold">
                    <i class="fas fa-key me-1"></i> Change Password
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
