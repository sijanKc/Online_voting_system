<?php require_once 'includes/candidate_header.php'; ?>
<?php require_once 'includes/candidate_sidebar.php'; ?>

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
        <?php
        // Fetch candidate-specific stats
        try {
            $user_id = $_SESSION['user_id'];
            
            // Count active elections the candidate is participating in
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM candidate_elections ce 
                                   JOIN elections e ON ce.election_id = e.id 
                                   WHERE ce.user_id = ? AND e.status = 'active'");
            $stmt->execute([$user_id]);
            $active_elections = $stmt->fetchColumn();
            
            // Count total votes (placeholder - will be implemented with voting system)
            $total_votes = 0;
            
            // Count applications
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM candidate_elections WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $applications = $stmt->fetchColumn();
            
            // Get profile status
            $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $profile_status = $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            $active_elections = $total_votes = $applications = 0;
            $profile_status = 'pending';
        }
        ?>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-candidate">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1">Active Elections</p>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo $active_elections; ?></h2>
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
                        <h2 class="fw-bold mb-0 text-dark"><?php echo number_format($total_votes); ?></h2>
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
                        <h2 class="fw-bold mb-0 text-dark"><?php echo $applications; ?></h2>
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
                        <h6 class="fw-bold mb-0 <?php echo $profile_status == 'approved' ? 'text-success' : 'text-warning'; ?>">
                            <?php echo ucfirst($profile_status); ?>
                        </h6>
                    </div>
                    <div class="stat-icon-candidate bg-<?php echo $profile_status == 'approved' ? 'success' : 'warning'; ?> bg-opacity-10 text-<?php echo $profile_status == 'approved' ? 'success' : 'warning'; ?>">
                        <i class="fas fa-<?php echo $profile_status == 'approved' ? 'check-circle' : 'clock'; ?>"></i>
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
        
        <?php
        // Fetch available elections
        try {
            $stmt = $pdo->prepare("SELECT * FROM elections WHERE status IN ('upcoming', 'active') ORDER BY created_at DESC");
            $stmt->execute();
            $elections = $stmt->fetchAll();
            
            if (empty($elections)):
        ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fs-1 text-muted mb-3 d-block"></i>
                <h6 class="text-muted">No Active Elections</h6>
                <p class="small text-muted mb-0">New elections will appear here when they are announced by the admin.</p>
            </div>
        <?php 
            else:
                foreach ($elections as $election):
        ?>
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($election['title']); ?></h6>
                        <p class="small text-muted mb-2"><?php echo htmlspecialchars($election['description']); ?></p>
                        <span class="badge badge-<?php echo $election['status']; ?> px-3 py-1 rounded-pill">
                            <?php echo ucfirst($election['status']); ?>
                        </span>
                    </div>
                    <a href="my_elections.php?apply=<?php echo $election['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-paper-plane me-1"></i> Apply
                    </a>
                </div>
            </div>
        <?php 
                endforeach;
            endif;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error loading elections.</div>';
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
