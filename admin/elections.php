<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark">Election Management</h2>
            <p class="text-muted">Create and oversee election sessions across the country.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createElectionModal">
            <i class="fas fa-plus me-2"></i> Create New Election
        </button>
    </div>

    <!-- Alert Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Elections Grid -->
    <div class="row g-4">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM elections ORDER BY created_at DESC");
        $stmt->execute();
        $elections = $stmt->fetchAll();

        if(empty($elections)): ?>
            <div class="col-12">
                <div class="admin-card card p-5 text-center">
                    <i class="fas fa-vote-yea fs-1 text-light mb-3"></i>
                    <h5 class="text-muted">No elections have been created yet.</h5>
                </div>
            </div>
        <?php endif;

        foreach ($elections as $election):
            $status_class = [
                'upcoming' => 'bg-info',
                'active' => 'bg-success',
                'completed' => 'bg-secondary'
            ];
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="admin-card card h-100 p-4 border-top border-4 <?php echo str_replace('bg-', 'border-', $status_class[$election['status']]); ?>">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge <?php echo $status_class[$election['status']]; ?> rounded-pill px-3 py-1">
                        <?php echo ucfirst($election['status']); ?>
                    </span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-white border-0" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                            <li><a class="dropdown-item small" href="#"><i class="fas fa-edit me-2"></i> Edit</a></li>
                            <li><a class="dropdown-item small text-danger" href="#"><i class="fas fa-trash me-2"></i> Delete</a></li>
                        </ul>
                    </div>
                </div>
                <h5 class="fw-bold mb-2"><?php echo $election['title']; ?></h5>
                <p class="small text-muted mb-4"><?php echo substr($election['description'], 0, 100); ?>...</p>
                
                <div class="bg-light rounded-3 p-3 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted"><i class="fas fa-calendar-alt me-2"></i> Starts:</span>
                        <span class="small fw-bold"><?php echo date('M d, Y', strtotime($election['start_date'])); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted"><i class="fas fa-calendar-check me-2"></i> Ends:</span>
                        <span class="small fw-bold"><?php echo date('M d, Y', strtotime($election['end_date'])); ?></span>
                    </div>
                </div>

                <div class="mt-auto pt-3 border-top border-light">
                    <a href="manage_candidates.php?election_id=<?php echo $election['id']; ?>" class="btn btn-sm btn-outline-primary w-100 rounded-pill">Manage Candidates</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Create Election Modal -->
    <div class="modal fade" id="createElectionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <form action="actions/create_election.php" method="POST">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Create New Election</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Election Title</label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="e.g. House of Representatives 2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Brief overview of the election..."></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Start Date</label>
                                <input type="datetime-local" name="start_date" class="form-control rounded-3" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">End Date</label>
                                <input type="datetime-local" name="end_date" class="form-control rounded-3" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Create Session</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
