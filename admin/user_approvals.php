<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark">User Approvals</h2>
            <p class="text-muted">Verify and approve voters and candidates for the elections.</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="admin-card card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-start">Name & Citizenship</th>
                        <th class="border-0">Role</th>
                        <th class="border-0">Contact</th>
                        <th class="border-0 text-center">Identity</th>
                        <th class="border-0 rounded-end text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE status = 'pending' ORDER BY created_at ASC");
                    $stmt->execute();
                    $pending_users = $stmt->fetchAll();

                    if(empty($pending_users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-user-check fs-1 text-light mb-3 d-block"></i>
                                <h6 class="text-muted">No pending approvals at the moment.</h6>
                            </td>
                        </tr>
                    <?php endif;

                    foreach ($pending_users as $user):
                    ?>
                    <tr>
                        <td>
                            <div class="fw-bold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                            <div class="text-muted small">ID: <?php echo htmlspecialchars($user['citizenship_number']); ?></div>
                        </td>
                        <td><span class="badge bg-light text-dark px-3 py-2 rounded-pill small text-capitalize"><?php echo $user['role']; ?></span></td>
                        <td>
                            <div class="small"><i class="fas fa-envelope text-muted me-2"></i><?php echo $user['email']; ?></div>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-light rounded-pill" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $user['id']; ?>">
                                <i class="fas fa-eye me-1"></i> Verify
                            </button>
                        </td>
                        <td class="text-end">
                            <form action="actions/process_approval.php" method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm me-1">Approve</button>
                                <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm">Reject</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Verification Modal -->
                    <div class="modal fade" id="viewModal<?php echo $user['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Identity Verification</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="text-center mb-4">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 d-inline-block mb-3">
                                            <i class="fas fa-user-shield fs-1"></i>
                                        </div>
                                        <h4 class="fw-bold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h4>
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill"><?php echo ucfirst($user['role']); ?> Application</span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="small text-muted fw-bold">Citizenship Number</label>
                                            <p class="fw-bold"><?php echo $user['citizenship_number']; ?></p>
                                        </div>
                                        <div class="col-6">
                                            <label class="small text-muted fw-bold">Email Address</label>
                                            <p class="fw-bold"><?php echo $user['email']; ?></p>
                                        </div>
                                    </div>
                                    <?php if($user['role'] === 'candidate'): 
                                        $c_stmt = $pdo->prepare("SELECT * FROM candidate_details WHERE user_id = ?");
                                        $c_stmt->execute([$user['id']]);
                                        $c_data = $c_stmt->fetch();
                                    ?>
                                        <hr>
                                        <label class="small text-muted fw-bold">Candidate Manifesto</label>
                                        <p class="small"><?php echo $c_data['manifesto'] ?? 'No manifesto provided.'; ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
