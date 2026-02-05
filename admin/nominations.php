<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-file-signature text-warning me-2"></i> Nomination Desk
            </h2>
            <p class="text-muted small mb-0">Review candidate applications for electoral seats.</p>
        </div>
        <div class="stat-card-ecn py-2 px-4 shadow-sm">
            <span class="text-muted small fw-bold">Pending Nominations:</span>
            <span class="ms-2 fw-bold text-danger">
                <?php 
                echo $pdo->query("SELECT COUNT(*) FROM candidate_applications WHERE status = 'pending'")->fetchColumn(); 
                ?>
            </span>
        </div>
    </div>

    <!-- Feedback -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Candidate</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Election</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Constituency</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Status</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-end px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT ca.*, u.first_name, u.last_name, u.email, 
                                       e.title as election_title, e.type as election_type,
                                       c.name_en as constituency_name, d.name_en as district_name
                                FROM candidate_applications ca
                                JOIN users u ON ca.candidate_id = u.id
                                JOIN elections e ON ca.election_id = e.id
                                JOIN constituencies c ON u.constituency_id = c.id
                                JOIN districts d ON c.district_id = d.id
                                ORDER BY CASE WHEN ca.status = 'pending' THEN 0 ELSE 1 END, ca.applied_at DESC";
                        
                        $stmt = $pdo->query($sql);
                        $apps = $stmt->fetchAll();

                        if (empty($apps)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">No nominations found.</td></tr>
                        <?php endif;

                        foreach ($apps as $a):
                        ?>
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($a['first_name'] . ' ' . $a['last_name']); ?></div>
                                <div class="extra-small text-muted"><?php echo htmlspecialchars($a['email']); ?></div>
                            </td>
                            <td>
                                <div class="small fw-bold"><?php echo htmlspecialchars($a['election_title']); ?></div>
                                <span class="badge bg-light text-muted extra-small rounded-pill"><?php echo ucfirst($a['election_type']); ?></span>
                            </td>
                            <td>
                                <div class="small text-dark fw-bold"><?php echo htmlspecialchars($a['constituency_name']); ?></div>
                                <div class="extra-small text-muted"><?php echo htmlspecialchars($a['district_name']); ?></div>
                            </td>
                            <td class="text-center">
                                <?php
                                $statusClass = ($a['status'] == 'approved') ? 'success' : (($a['status'] == 'pending') ? 'warning' : 'danger');
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?> bg-opacity-10 text-<?php echo $statusClass; ?> rounded-pill px-3 py-2">
                                    <?php echo ucfirst($a['status']); ?>
                                </span>
                            </td>
                            <td class="text-end px-4">
                                <?php if($a['status'] === 'pending'): ?>
                                    <form action="actions/nomination_ops.php" method="POST" class="d-inline">
                                        <input type="hidden" name="app_id" value="<?php echo $a['id']; ?>">
                                        <button name="action" value="approve" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                        <button name="action" value="reject" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm ms-1">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted extra-small"><?php echo date('M d, Y', strtotime($a['applied_at'])); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
