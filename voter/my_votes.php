<?php require_once 'includes/voter_header.php'; ?>
<?php require_once 'includes/voter_sidebar.php'; ?>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-1">My Voting History</h2>
        <p class="text-muted mb-0">Track your past participation in democratic processes.</p>
    </div>

    <!-- Success Feedback after voting -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 shadow-lg rounded-4 mb-5 p-4 d-flex align-items-center animate__animated animate__fadeIn">
            <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-4">
                <i class="fas fa-check-circle fs-2"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1">Success!</h5>
                <p class="mb-0 opacity-75"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                <?php if(isset($_SESSION['vote_confirmed'])): ?>
                    <p class="extra-small mt-2 text-muted fw-bold">Verification Hash: <span class="bg-light px-2 py-1 rounded"><?php echo $_SESSION['vote_confirmed']; unset($_SESSION['vote_confirmed']); ?></span></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="voter-card p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3">Your Votes</h5>

                <?php
                try {
                    $user_id = $_SESSION['user_id'];
                    $stmt = $pdo->prepare("SELECT v.*, e.title as election_title, e.end_date,
                                           u.first_name as cand_first, u.last_name as cand_last,
                                           p.name_en as party_name
                                           FROM votes v
                                           JOIN elections e ON v.election_id = e.id
                                           JOIN users u ON v.candidate_id = u.id
                                           LEFT JOIN candidate_details cd ON u.id = cd.user_id
                                           LEFT JOIN political_parties p ON cd.party_id = p.id
                                           WHERE v.voter_id = ?
                                           ORDER BY v.voted_at DESC");
                    $stmt->execute([$user_id]);
                    $my_votes = $stmt->fetchAll();

                    if (empty($my_votes)):
                ?>
                    <div class="text-center py-5">
                        <i class="fas fa-history fs-1 text-muted mb-3 d-block"></i>
                        <h6 class="text-muted">You haven't cast any votes yet.</h6>
                        <p class="small text-muted">Your voting history will appear here once you participate in an election.</p>
                        <a href="elections.php" class="btn btn-outline-primary rounded-pill px-4 mt-3 fw-bold">Browse Elections</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Election</th>
                                    <th>Candidate Voted For</th>
                                    <th>Voted On</th>
                                    <th>Proof of Participation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_votes as $vote): ?>
                                    <tr>
                                        <td>
                                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($vote['election_title']); ?></h6>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user-tie small"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold small"><?php echo htmlspecialchars($vote['cand_first'] . ' ' . $vote['cand_last']); ?></div>
                                                    <div class="extra-small text-muted"><?php echo htmlspecialchars($vote['party_name'] ?? 'Independent'); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small text-muted">
                                            <?php echo date('M d, Y', strtotime($vote['voted_at'])); ?> <br>
                                            <span class="extra-small opacity-50"><?php echo date('H:i:s', strtotime($vote['voted_at'])); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold small">
                                                <i class="fas fa-check-circle me-1"></i> Securely Locked
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <?php } catch (PDOException $e) { echo "Error loading history."; } ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
