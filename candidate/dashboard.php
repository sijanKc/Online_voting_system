<?php require_once 'includes/candidate_header.php'; ?>
<?php require_once 'includes/candidate_sidebar.php'; ?>

<div class="main-content">
    <!-- Welcome Header -->
    <div class="mb-5">
        <?php
        try {
            $user_id = $_SESSION['user_id'];
            
            // Fetch candidate, party and location details
            $stmt = $pdo->prepare("SELECT u.*, cd.manifesto, p.name_$lang as party_name, p.logo_path as party_logo,
                                   c.name_$lang as constituency_name, d.name_$lang as district_name
                                   FROM users u
                                   LEFT JOIN candidate_details cd ON u.id = cd.user_id
                                   LEFT JOIN political_parties p ON cd.party_id = p.id
                                   LEFT JOIN constituencies c ON u.constituency_id = c.id
                                   LEFT JOIN districts d ON u.district_id = d.id
                                   WHERE u.id = ?");
            $stmt->execute([$user_id]);
            $candidate = $stmt->fetch();
            
            // Count active elections the candidate is participating in
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM candidate_applications ca 
                                   JOIN elections e ON ca.election_id = e.id 
                                   WHERE ca.candidate_id = ? AND e.status = 'active' AND ca.status = 'approved'");
            $stmt->execute([$user_id]);
            $active_elections = $stmt->fetchColumn();
            
            // Count total votes (from votes table)
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE candidate_id = ?");
            $stmt->execute([$user_id]);
            $total_votes = $stmt->fetchColumn();
            
            // Count applications
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM candidate_applications WHERE candidate_id = ?");
            $stmt->execute([$user_id]);
            $applications = $stmt->fetchColumn();
            
            $profile_status = $candidate['status'] ?? 'pending';
            
        } catch (PDOException $e) {
            $active_elections = $total_votes = $applications = 0;
            $profile_status = 'pending';
            $candidate = [];
        }
        ?>
        <div class="d-flex justify-content-between align-items-end">
            <div>
                <h2 class="fw-bold text-dark mb-2">
                    <i class="fas fa-bullhorn" style="color: var(--candidate-primary);"></i> <?php echo __('campaign_dashboard', 'Campaign Dashboard'); ?>
                </h2>
                <p class="text-muted mb-0"><?php echo __('official_portal'); ?></p>
            </div>
            <div class="text-end">
                <div class="badge bg-white shadow-sm text-dark px-3 py-2 rounded-3 border">
                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                    <span class="fw-bold"><?php echo $candidate['constituency_name'] ?? 'N/A'; ?></span>
                    <span class="text-muted ms-1">| <?php echo $candidate['district_name'] ?? 'N/A'; ?></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-candidate">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('active_elections'); ?></p>
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
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('total_votes'); ?></p>
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
                        <p class="text-muted small fw-bold text-uppercase mb-1"><?php echo __('status'); ?></p>
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

    <!-- Candidate Profile Quick View -->
    <div class="stat-card-candidate mb-5 p-4" style="border-left: 5px solid #10b981;">
        <div class="row align-items-center">
            <div class="col-auto">
                <?php 
                $logo_to_show = !empty($candidate['party_logo']) ? $candidate['party_logo'] : '';
                $resolved_logo = resolve_party_logo($logo_to_show);
                ?>
                <img src="../<?php echo htmlspecialchars($resolved_logo); ?>" alt="Party Logo" class="rounded shadow-sm" style="width: 80px; height: 80px; object-fit: contain; background: #fff; padding: 5px;">
            </div>
            <div class="col">
                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h4>
                <div class="d-flex flex-wrap gap-3 mt-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-shield-alt me-1"></i> <?php echo htmlspecialchars($candidate['party_name'] ?? 'Independent'); ?>
                    </span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                        <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($candidate['constituency_name'] ?? 'Not Assigned'); ?>
                    </span>
                </div>
            </div>
            <div class="col-md-auto mt-3 mt-md-0">
                <a href="my_profile.php" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-user-edit me-2"></i> <?php echo __('profile'); ?>
                </a>
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
                            <h6 class="fw-bold mb-1 text-dark"><?php echo __('apply_election'); ?></h6>
                            <p class="small text-muted mb-0"><?php echo __('nomination_desk'); ?></p>
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
                    <i class="fas fa-list-ul me-2" style="color: var(--candidate-primary);"></i> <?php echo __('active_elections'); ?>
                </h5>
                <p class="small text-muted mb-0"><?php echo __('official_portal'); ?></p>
            </div>
        </div>
        
        <?php
        // Fetch available elections
        try {
            // Filter elections: Parliamentary (Global) OR Provincial (Matching Candidate's Province)
            $stmt = $pdo->prepare("SELECT e.*, 
                                  (SELECT status FROM candidate_applications WHERE election_id = e.id AND candidate_id = ?) as application_status
                                  FROM elections e 
                                  WHERE e.status IN ('upcoming', 'active') 
                                  AND (e.type = 'parliamentary' OR (e.type = 'provincial' AND e.province_id = ?))
                                  ORDER BY e.created_at DESC");
            $stmt->execute([$user_id, $candidate['province_id']]);
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
            <div class="border-bottom pb-4 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-3 rounded-3 me-3 text-primary">
                            <i class="fas fa-vote-yea fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($election['title']); ?></h6>
                            <p class="small text-muted mb-2">
                                <i class="far fa-calendar-alt me-1"></i> Ends: <?php echo date('M d, Y', strtotime($election['end_date'])); ?>
                            </p>
                            <span class="badge badge-<?php echo $election['status']; ?> px-3 py-1 rounded-pill">
                                <?php echo ucfirst($election['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <?php if ($election['application_status'] === 'approved'): ?>
                            <span class="badge bg-success px-4 py-2 rounded-pill fw-bold">
                                <i class="fas fa-check-circle me-1"></i> Nominated
                            </span>
                        <?php elseif ($election['application_status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark px-4 py-2 rounded-pill fw-bold">
                                <i class="fas fa-clock me-1"></i> Application Pending
                            </span>
                        <?php elseif ($election['application_status'] === 'rejected'): ?>
                            <span class="badge bg-danger px-4 py-2 rounded-pill fw-bold">
                                <i class="fas fa-times-circle me-1"></i> Rejected
                            </span>
                        <?php else: ?>
                            <a href="my_elections.php?apply=<?php echo $election['id']; ?>" class="btn btn-primary rounded-pill px-4 fw-bold">
                                <i class="fas fa-paper-plane me-1"></i> Submit Nomination
                            </a>
                        <?php endif; ?>
                    </div>
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
