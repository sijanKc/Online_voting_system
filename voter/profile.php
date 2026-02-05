<?php require_once 'includes/voter_header.php'; ?>
<?php require_once 'includes/voter_sidebar.php'; ?>

<div class="main-content">
    <?php
    $user_id = $_SESSION['user_id'];
    try {
        $stmt = $pdo->prepare("SELECT u.*, p.name_en as province_name, d.name_en as district_name, c.name_en as constituency_name
                               FROM users u
                               LEFT JOIN provinces p ON u.province_id = p.id
                               LEFT JOIN districts d ON u.district_id = d.id
                               LEFT JOIN constituencies c ON u.constituency_id = c.id
                               WHERE u.id = ?");
        $stmt->execute([$user_id]);
        $voter = $stmt->fetch();
    } catch (PDOException $e) { $voter = []; }
    ?>

    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-1">My Voter Info</h2>
        <p class="text-muted mb-0">Your verified electoral identity and registered location.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="voter-card p-4">
                <div class="d-flex align-items-center mb-4 border-bottom pb-4">
                    <div class="bg-primary bg-opacity-10 text-primary p-4 rounded-circle me-4">
                        <i class="fas fa-user-check fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($voter['first_name'] . ' ' . $voter['last_name']); ?></h4>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold">Verified Voter</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold">Active</span>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <label class="extra-small text-muted fw-bold text-uppercase mb-1 d-block">Citizenship Number</label>
                        <p class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($voter['citizenship_number']); ?></p>

                        <label class="extra-small text-muted fw-bold text-uppercase mb-1 d-block">Username</label>
                        <p class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($voter['username']); ?></p>
                        
                        <label class="extra-small text-muted fw-bold text-uppercase mb-1 d-block">Email Address</label>
                        <p class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($voter['email']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="extra-small text-muted fw-bold text-uppercase mb-1 d-block">Province</label>
                        <p class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($voter['province_name']); ?></p>
                        
                        <label class="extra-small text-muted fw-bold text-uppercase mb-1 d-block">District</label>
                        <p class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($voter['district_name']); ?></p>
                        
                        <label class="extra-small text-muted fw-bold text-uppercase mb-1 d-block">Constituency</label>
                        <p class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($voter['constituency_name']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="voter-card p-4 bg-dark text-white border-0 shadow-lg">
                <h5 class="fw-bold mb-4">Security Notice</h5>
                <div class="d-flex align-items-start mb-4">
                    <i class="fas fa-info-circle me-3 mt-1 text-primary"></i>
                    <p class="small opacity-75 mb-0">Changes to your constituency or citizenship data must be requested directly via your local Election Commission office.</p>
                </div>
                <div class="d-flex align-items-start mb-4">
                    <i class="fas fa-lock me-3 mt-1 text-primary"></i>
                    <p class="small opacity-75 mb-0">Your password is encrypted. ECN staff will never ask for your login credentials.</p>
                </div>
                <hr class="opacity-25 my-4">
                <button class="btn btn-outline-light btn-sm w-100 rounded-pill py-2" disabled>Change Password (Coming Soon)</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
