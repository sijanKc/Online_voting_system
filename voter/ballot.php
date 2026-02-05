<?php require_once 'includes/voter_header.php'; ?>
<?php require_once 'includes/voter_sidebar.php'; ?>

<div class="main-content">
    <?php
    if (!isset($_GET['id'])) {
        echo "<script>window.location.href='elections.php';</script>";
        exit;
    }

    $election_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $constituency_id = $_SESSION['constituency_id'];

    // 1. Validate election exists and is active
    $stmt = $pdo->prepare("SELECT * FROM elections WHERE id = ? AND status = 'active'");
    $stmt->execute([$election_id]);
    $election = $stmt->fetch();

    if (!$election) {
        echo "<div class='alert alert-danger rounded-4 border-0 shadow-sm'><i class='fas fa-exclamation-triangle me-2'></i> This election is not currently active or available.</div>";
        echo "<a href='elections.php' class='btn btn-primary rounded-pill px-4'>Back to Elections</a>";
        exit;
    }

    // 2. Check if user already voted in this election
    $stmt = $pdo->prepare("SELECT id FROM votes WHERE election_id = ? AND voter_id = ?");
    $stmt->execute([$election_id, $user_id]);
    if ($stmt->fetch()) {
        echo "<div class='alert alert-info rounded-4 border-0 shadow-sm'><i class='fas fa-info-circle me-2'></i> You have already cast your vote for this election.</div>";
        echo "<a href='elections.php' class='btn btn-primary rounded-pill px-4'>Back to Elections</a>";
        exit;
    }

    // 3. Fetch approved candidates for this election in THIS constituency
    $stmt = $pdo->prepare("SELECT u.first_name, u.last_name, cd.user_id as candidate_id, cd.manifesto, cd.party_id, 
                           p.name_en as party_name, p.logo_path as party_logo, cd.party_logo as custom_logo
                           FROM candidate_applications ca
                           JOIN users u ON ca.candidate_id = u.id
                           JOIN candidate_details cd ON u.id = cd.user_id
                           LEFT JOIN political_parties p ON cd.party_id = p.id
                           WHERE ca.election_id = ? 
                           AND ca.status = 'approved' 
                           AND u.constituency_id = ?");
    $stmt->execute([$election_id, $constituency_id]);
    $candidates = $stmt->fetchAll();
    ?>

    <div class="mb-5 text-center">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-3 ls-1">OFFICIAL BALLOT</span>
        <h2 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($election['title']); ?></h2>
        <p class="text-muted">Electoral Seat: <span class="text-primary fw-bold"><?php 
            $cStmt = $pdo->prepare("SELECT name_en FROM constituencies WHERE id = ?");
            $cStmt->execute([$constituency_id]);
            echo $cStmt->fetchColumn(); 
        ?></span></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="voter-card p-0 overflow-hidden border-0 shadow-lg">
                <div class="bg-dark p-4 text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0">Cast Your Vote</h5>
                        <p class="small opacity-75 mb-0">Select one candidate and click "Confirm My Vote"</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-shield-alt fs-3 opacity-50"></i>
                    </div>
                </div>

                <div class="p-4 bg-light">
                    <?php if (empty($candidates)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users-slash fs-1 text-muted mb-3 d-block"></i>
                            <h6 class="text-muted">No approved candidates found for your constituency in this election.</h6>
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($candidates as $candidate): 
                                $logo_to_show = !empty($candidate['custom_logo']) ? $candidate['custom_logo'] : $candidate['party_logo'];
                                $resolved_logo = resolve_party_logo($logo_to_show);
                            ?>
                                <div class="col-md-6">
                                    <div class="candidate-select-card h-100 bg-white p-4 rounded-4 border-2 border-transparent shadow-sm" onclick="selectCandidate(this, <?php echo $candidate['candidate_id']; ?>)">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="candidate-img-box me-3">
                                                <img src="../<?php echo htmlspecialchars($resolved_logo); ?>" alt="Symbol" class="img-fluid rounded-3" style="width: 60px; height: 60px; object-fit: contain;">
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($candidate['first_name'] . ' ' . $candidate['last_name']); ?></h6>
                                                <small class="text-primary fw-bold text-uppercase" style="font-size: 0.7rem;"><?php echo htmlspecialchars($candidate['party_name'] ?? 'Independent'); ?></small>
                                            </div>
                                            <div class="ms-auto">
                                                <div class="selection-indicator">
                                                    <i class="far fa-circle fs-4 text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="manifesto-preview mt-3 p-3 bg-light rounded-3">
                                            <p class="extra-small text-muted mb-0 lh-sm line-clamp-3">
                                                <?php echo !empty($candidate['manifesto']) ? htmlspecialchars(substr($candidate['manifesto'], 0, 150)) . '...' : 'No manifesto provided by this candidate.'; ?>
                                            </p>
                                        </div>

                                        <!-- Hidden Radio -->
                                        <input type="radio" name="candidate_radio" value="<?php echo $candidate['candidate_id']; ?>" class="d-none">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-5 text-center">
                            <form id="voteForm" action="actions/cast_vote.php" method="POST">
                                <input type="hidden" name="election_id" value="<?php echo $election_id; ?>">
                                <input type="hidden" name="candidate_id" id="selected_candidate_id" value="">
                                
                                <div id="vote-notice" class="mb-4 text-muted small px-3">
                                    <i class="fas fa-info-circle me-1"></i> Selection is final. Ensure you have researched your candidate properly.
                                </div>
                                
                                <button type="button" id="submitBtn" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg" disabled onclick="confirmVote()">
                                    <i class="fas fa-paper-plane me-2"></i> Confirm My Vote
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Confirmation -->
<div class="modal fade" id="confirmVoteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body p-5 text-center">
                <div class="text-voter-primary mb-4">
                    <i class="fas fa-fingerprint display-4"></i>
                </div>
                <h4 class="fw-bold mb-3">Final Confirmation</h4>
                <p class="text-muted mb-4">Are you sure you want to cast your vote for the selected candidate? This action is immutable and cannot be undone.</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold" onclick="executeVote()">Yes, Cast My Vote</button>
                    <button type="button" class="btn btn-light btn-lg rounded-pill py-3 text-muted" data-bs-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.candidate-select-card {
    cursor: pointer;
    transition: 0.2s all;
}
.candidate-select-card:hover {
    border-color: rgba(0, 56, 147, 0.2) !important;
    background: #fff;
}
.candidate-select-card.selected {
    border: 2px solid var(--voter-primary) !important;
    background: rgba(0, 56, 147, 0.02);
}
.candidate-select-card.selected .selection-indicator i {
    color: var(--voter-primary);
}
.candidate-select-card.selected .selection-indicator i:before {
    content: "\f058"; /* fa-check-circle */
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
</style>

<script>
function selectCandidate(element, id) {
    // Remove selected class from all
    document.querySelectorAll('.candidate-select-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add to clicked
    element.classList.add('selected');
    
    // Set hidden ID
    document.getElementById('selected_candidate_id').value = id;
    
    // Enable button
    document.getElementById('submitBtn').disabled = false;
}

function confirmVote() {
    new bootstrap.Modal(document.getElementById('confirmVoteModal')).show();
}

function executeVote() {
    document.getElementById('voteForm').submit();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
