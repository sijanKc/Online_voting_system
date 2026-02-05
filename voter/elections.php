<?php require_once 'includes/voter_header.php'; ?>
<?php require_once 'includes/voter_sidebar.php'; ?>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-1">Elections</h2>
        <p class="text-muted mb-0">Browse and participate in democratic processes within your constituency.</p>
    </div>

    <!-- Filters Section (Optional/Future) -->
    
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="voter-card p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3">Available Elections</h5>

                <?php
                try {
                    $user_id = $_SESSION['user_id'];
                    $constituency_id = $_SESSION['constituency_id'];

                    // Fetch voter's province
                    $stmt = $pdo->prepare("SELECT province_id FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $province_id = $stmt->fetchColumn();

                    // Fetch elections and voter participation status
                    $stmt = $pdo->prepare("SELECT e.*, 
                                          (SELECT COUNT(*) FROM votes WHERE election_id = e.id AND voter_id = ?) as has_voted
                                          FROM elections e 
                                          WHERE e.status IN ('active', 'upcoming') 
                                          AND (e.type = 'parliamentary' OR (e.type = 'provincial' AND e.province_id = ?))
                                          ORDER BY e.status ASC, e.start_date ASC");
                    $stmt->execute([$user_id, $province_id]);
                    $elections = $stmt->fetchAll();

                    if (empty($elections)):
                ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fs-1 text-muted mb-3 d-block"></i>
                        <h6 class="text-muted">No elections currently scheduled</h6>
                        <p class="small text-muted">Stay tuned for official announcements from the ECN.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Election Title</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th>My Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($elections as $election): ?>
                                    <tr>
                                        <td>
                                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($election['title']); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($election['description'] ?? ''); ?></small>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div class="mb-1"><i class="far fa-calendar-check me-2 text-success"></i>Start: <?php echo date('M d, Y', strtotime($election['start_date'])); ?></div>
                                                <div><i class="far fa-calendar-times me-2 text-danger"></i>End: <?php echo date('M d, Y', strtotime($election['end_date'])); ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $election['status']; ?> rounded-pill px-3 py-2">
                                                <?php echo ucfirst($election['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($election['status'] === 'active'): ?>
                                                <?php if ($election['has_voted'] > 0): ?>
                                                    <button class="btn btn-success btn-sm rounded-pill px-4" disabled>
                                                        <i class="fas fa-check-circle me-1"></i> Recorded
                                                    </button>
                                                <?php else: ?>
                                                    <a href="ballot.php?id=<?php echo $election['id']; ?>" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                                                        <i class="fas fa-vote-yea me-1"></i> Vote Now
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <button class="btn btn-light btn-sm rounded-pill px-4 text-muted" disabled>
                                                    <i class="fas fa-clock me-1"></i> Upcoming
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <?php } catch (PDOException $e) { echo "Error loading elections."; } ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
