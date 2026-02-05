<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    <!-- Welcome Header -->
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-2">
            <i class="fas fa-shield-alt text-danger me-2"></i> <?php echo __('admin'); ?> Control Center
        </h2>
        <p class="text-muted"><?php echo __('official_portal'); ?></p>
    </div>

    <!-- Stats Cards (ECN Style) -->
    <div class="row g-4 mb-5">
        <?php
        // Fetch counts for dashboard stats
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'voter'");
            $stmt->execute();
            $total_voters = $stmt->fetchColumn();

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'candidate'");
            $stmt->execute();
            $total_candidates = $stmt->fetchColumn();

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE status = 'pending'");
            $stmt->execute();
            $pending_approvals = $stmt->fetchColumn();

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM elections WHERE status = 'active'");
            $stmt->execute();
            $active_elections = $stmt->fetchColumn();
        } catch (PDOException $e) {
            $total_voters = $total_candidates = $pending_approvals = $active_elections = 0;
        }
        ?>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-ecn">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('stats_voters'); ?></p>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo number_format($total_voters); ?></h2>
                    </div>
                    <div class="stat-icon-ecn bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-ecn">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('total_candidates'); ?></p>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo number_format($total_candidates); ?></h2>
                    </div>
                    <div class="stat-icon-ecn bg-info bg-opacity-10 text-info">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-ecn">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('pending_approvals'); ?></p>
                        <h2 class="fw-bold mb-0" style="color: #C8102E;"><?php echo number_format($pending_approvals); ?></h2>
                    </div>
                    <div class="stat-icon-ecn bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-ecn">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('active_elections'); ?></p>
                        <h2 class="fw-bold mb-0 text-success"><?php echo number_format($active_elections); ?></h2>
                    </div>
                    <div class="stat-icon-ecn bg-success bg-opacity-10 text-success">
                        <i class="fas fa-vote-yea"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions (ECN Style) -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <a href="user_approvals.php" class="text-decoration-none">
                <div class="stat-card-ecn border-start border-5 border-danger">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-ecn bg-danger bg-opacity-10 text-danger me-3">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark"><?php echo __('user_approvals'); ?></h6>
                            <p class="small text-muted mb-0"><?php echo __('verify_desc', 'Verify applications'); ?></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="elections.php" class="text-decoration-none">
                <div class="stat-card-ecn border-start border-5 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-ecn bg-primary bg-opacity-10 text-primary me-3">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Create Election</h6>
                            <p class="small text-muted mb-0">Set up new electoral sessions</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="results.php" class="text-decoration-none">
                <div class="stat-card-ecn border-start border-5 border-success">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-ecn bg-success bg-opacity-10 text-success me-3">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">View Results</h6>
                            <p class="small text-muted mb-0">Access election analytics</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Registrations Table (ECN Style) -->
    <div class="ecn-table p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">
                    <i class="fas fa-list-ul text-primary me-2"></i> <?php echo __('recent_registrations'); ?>
                </h5>
                <p class="small text-muted mb-0"><?php echo __('pending_approvals'); ?></p>
            </div>
            <a href="user_approvals.php" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                View All <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th class="border-0 py-3 fw-bold small text-uppercase text-muted"><?php echo __('applicant'); ?></th>
                        <th class="border-0 py-3 fw-bold small text-uppercase text-muted"><?php echo __('role'); ?></th>
                        <th class="border-0 py-3 fw-bold small text-uppercase text-muted"><?php echo __('applied_on'); ?></th>
                        <th class="border-0 py-3 fw-bold small text-uppercase text-muted"><?php echo __('status'); ?></th>
                        <th class="border-0 py-3 fw-bold small text-uppercase text-muted text-end"><?php echo __('action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC LIMIT 8");
                    $stmt->execute();
                    $recent_users = $stmt->fetchAll();

                    if (empty($recent_users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-inbox fs-1 text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">No registrations yet. Users will appear here once they sign up.</p>
                            </td>
                        </tr>
                    <?php endif;

                    foreach ($recent_users as $user):
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                                    <div class="text-muted small"><?php echo htmlspecialchars($user['email']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill text-capitalize">
                                <i class="fas fa-<?php echo $user['role'] == 'voter' ? 'user-check' : 'user-tie'; ?> me-1"></i>
                                <?php echo $user['role']; ?>
                            </span>
                        </td>
                        <td class="text-muted"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $user['status']; ?> px-3 py-2 rounded-pill">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="user_approvals.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                <i class="fas fa-eye me-1"></i> Review
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
