<?php require_once 'includes/candidate_header.php'; ?>
<?php require_once 'includes/candidate_sidebar.php'; ?>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-2">
            <i class="fas fa-vote-yea text-primary me-2"></i> My Elections
        </h2>
        <p class="text-muted">Apply for upcoming elections and track your nomination status.</p>
    </div>

    <!-- Feedback Alerts -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Application History -->
        <div class="col-lg-8">
            <div class="stat-card-candidate p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3">
                    <i class="fas fa-history text-muted me-2"></i> Application History
                </h5>

                <?php
                try {
                    $user_id = $_SESSION['user_id'];
                    $stmt = $pdo->prepare("SELECT ca.*, e.title, e.start_date, e.end_date, e.status as election_status 
                                           FROM candidate_applications ca
                                           JOIN elections e ON ca.election_id = e.id
                                           WHERE ca.candidate_id = ?
                                           ORDER BY ca.applied_at DESC");
                    $stmt->execute([$user_id]);
                    $applications = $stmt->fetchAll();

                    if (empty($applications)):
                ?>
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fs-1 text-muted mb-3 d-block"></i>
                        <h6 class="text-muted">No applications yet</h6>
                        <p class="small text-muted">Submit your first nomination from the available elections list.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Election</th>
                                    <th>Applied On</th>
                                    <th>Election Status</th>
                                    <th>Nomination Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><?php echo htmlspecialchars($app['title']); ?></span>
                                        </td>
                                        <td class="small text-muted">
                                            <?php echo date('M d, Y', strtotime($app['applied_at'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $app['election_status']; ?> rounded-pill">
                                                <?php echo ucfirst($app['election_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($app['status'] === 'approved'): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i> Approved
                                                </span>
                                            <?php elseif ($app['status'] === 'pending'): ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                                    <i class="fas fa-clock me-1"></i> Pending
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                                    <i class="fas fa-times-circle me-1"></i> Rejected
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <?php } catch (PDOException $e) { echo "Error loading applications."; } ?>
            </div>
        </div>

        <!-- Available for Application -->
        <div class="col-lg-4">
            <div class="stat-card-candidate p-4 border-top border-5 border-primary">
                <h5 class="fw-bold mb-4 border-bottom pb-3">Available Now</h5>
                
                <?php
                try {
                    // Fetch elections the candidate HAS NOT applied for yet
                    $stmt = $pdo->prepare("SELECT * FROM elections 
                                           WHERE status IN ('upcoming', 'active') 
                                           AND id NOT IN (SELECT election_id FROM candidate_applications WHERE candidate_id = ?)
                                           ORDER BY start_date ASC");
                    $stmt->execute([$user_id]);
                    $available = $stmt->fetchAll();

                    if (empty($available)):
                ?>
                    <p class="small text-muted text-center py-4">No new elections available for nomination.</p>
                <?php else: ?>
                    <?php foreach ($available as $election): ?>
                        <div class="p-3 bg-light rounded-3 mb-3">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($election['title']); ?></h6>
                            <p class="small text-muted mb-3">
                                Ends: <?php echo date('M d, Y', strtotime($election['end_date'])); ?>
                            </p>
                            <form action="actions/apply_election.php" method="POST">
                                <input type="hidden" name="election_id" value="<?php echo $election['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill fw-bold">
                                    <i class="fas fa-paper-plane me-1"></i> Submit Nomination
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php } catch (PDOException $e) { echo "Error loading availability."; } ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
