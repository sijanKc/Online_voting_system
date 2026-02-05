<?php require_once 'includes/voter_header.php'; ?>
<?php require_once 'includes/voter_sidebar.php'; ?>

<div class="main-content">
    <?php
    $user_id = $_SESSION['user_id'];
    $constituency_id = $_SESSION['constituency_id'];

    // Fetch constituency, district and province details
    $stmt = $pdo->prepare("SELECT u.province_id, c.name_$lang as constituency_name, d.name_$lang as district_name 
                           FROM users u
                           JOIN constituencies c ON u.constituency_id = c.id 
                           JOIN districts d ON c.district_id = d.id 
                           WHERE u.id = ?");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();
    $province_id = $user_info['province_id'];

    // Count active elections in this area (Parliamentary or Matching Province)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM elections 
                           WHERE status = 'active' 
                           AND (type = 'parliamentary' OR (type = 'provincial' AND province_id = ?))");
    $stmt->execute([$province_id]);
    $active_count = $stmt->fetchColumn();

    // Count votes cast by this user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE voter_id = ?");
    $stmt->execute([$user_id]);
    $voted_count = $stmt->fetchColumn();

    // Fetch account status
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_status = $stmt->fetchColumn();
    ?>

    <!-- Welcome Header -->
    <div class="mb-5 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark mb-1"><?php echo __('voter'); ?> <?php echo __('dashboard'); ?></h2>
            <p class="text-muted mb-0"><?php echo __('hero_subtitle'); ?></p>
        </div>
        <div class="text-end">
            <span class="badge bg-white shadow-sm text-primary px-3 py-2 rounded-3 border">
                <i class="fas fa-map-marker-alt me-2"></i>
                <?php echo $user_info['constituency_name']; ?> | <?php echo $user_info['district_name']; ?>
            </span>
        </div>
    </div>

    <!-- Status Alert if not active -->
    <?php if($user_status !== 'approved'): ?>
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-5 d-flex align-items-center">
            <i class="fas fa-user-clock fs-4 me-3"></i>
            <div>
                <h6 class="fw-bold mb-1"><?php echo __('account_under_review', 'Account Under Review'); ?></h6>
                <p class="small mb-0 opacity-75"><?php echo __('step_2_desc'); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-xl-4 col-md-6">
            <div class="voter-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('active_elections'); ?></p>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo $active_count; ?></h2>
                    </div>
                    <div class="stat-icon-voter bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-vote-yea"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="voter-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('voted'); ?></p>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo $voted_count; ?></h2>
                    </div>
                    <div class="stat-icon-voter bg-success bg-opacity-10 text-success">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="voter-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('status'); ?></p>
                        <h6 class="fw-bold mb-0 text-success">
                            <i class="fas fa-id-card me-1"></i> <?php echo ucfirst($user_status); ?>
                        </h6>
                    </div>
                    <div class="stat-icon-voter bg-info bg-opacity-10 text-info">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Live Elections -->
        <div class="col-lg-8">
            <div class="voter-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0 text-dark"><?php echo __('active_elections'); ?></h5>
                    <a href="elections.php" class="small text-primary text-decoration-none fw-bold"><?php echo __('view_all'); ?> <i class="fas fa-arrow-right ms-1"></i></a>
                </div>

                <?php
                // Fetch current elections and check if already voted
                $stmt = $pdo->prepare("SELECT e.*, 
                                      (SELECT COUNT(*) FROM votes WHERE election_id = e.id AND voter_id = ?) as has_voted
                                      FROM elections e 
                                      WHERE e.status = 'active' 
                                      AND (e.type = 'parliamentary' OR (e.type = 'provincial' AND e.province_id = ?))
                                      ORDER BY e.start_date ASC LIMIT 5");
                $stmt->execute([$user_id, $province_id]);
                $elections = $stmt->fetchAll();

                if (empty($elections)):
                ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fs-1 text-muted mb-3 d-block"></i>
                        <h6 class="text-muted">No active elections in your area</h6>
                    </div>
                <?php else: ?>
                    <?php foreach($elections as $election): ?>
                        <div class="p-3 bg-light rounded-4 mb-3 border border-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($election['title']); ?></h6>
                                    <p class="extra-small text-muted mb-0">
                                        <i class="far fa-clock me-1"></i> Closes: <?php echo date('M d, H:i', strtotime($election['end_date'])); ?>
                                    </p>
                                </div>
                                <?php if($election['has_voted'] > 0): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">
                                        <i class="fas fa-check-circle me-1"></i> <?php echo __('voted'); ?>
                                    </span>
                                <?php else: ?>
                                    <a href="ballot.php?id=<?php echo $election['id']; ?>" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                                        <?php echo __('vote_now'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-4">
            <div class="voter-card p-4">
                <h5 class="fw-bold mb-4"><?php echo __('quick_links'); ?></h5>
                
                <a href="results.php" class="text-decoration-none">
                    <div class="p-3 bg-light rounded-4 mb-3 d-flex align-items-center">
                        <div class="avatar-sm bg-danger bg-opacity-10 text-danger rounded-3 p-2 me-3">
                            <i class="fas fa-poll"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark small mb-0"><?php echo __('result_portal'); ?></h6>
                            <p class="extra-small text-muted mb-0"><?php echo __('view_all'); ?></p>
                        </div>
                    </div>
                </a>

                <a href="profile.php" class="text-decoration-none">
                    <div class="p-3 bg-light rounded-4 mb-3 d-flex align-items-center">
                        <div class="avatar-sm bg-warning bg-opacity-10 text-warning rounded-3 p-2 me-3">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark small mb-0">Your Documents</h6>
                            <p class="extra-small text-muted mb-0">Verify your citizenship info</p>
                        </div>
                    </div>
                </a>

                <div class="mt-4 p-3 bg-primary bg-opacity-5 rounded-4">
                    <p class="extra-small text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Voteport uses blockchain-inspired unique hashes to ensure your vote is immutable and secure.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
