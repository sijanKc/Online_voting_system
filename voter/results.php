<?php require_once 'includes/voter_header.php'; ?>
<?php require_once 'includes/voter_sidebar.php'; ?>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-1">Live Results</h2>
        <p class="text-muted mb-0">Transparency is a pillar of democracy. View real-time results from your area.</p>
    </div>

    <div class="row g-4">
        <?php
        try {
            $user_id = $_SESSION['user_id'];
            $constituency_id = $_SESSION['constituency_id'];

            // Fetch completed or active elections to show results
            $stmt = $pdo->prepare("SELECT e.* FROM elections e 
                                  WHERE e.status IN ('active', 'completed') 
                                  ORDER BY e.status ASC, e.end_date DESC");
            $stmt->execute();
            $elections = $stmt->fetchAll();

            if (empty($elections)):
        ?>
            <div class="col-12 text-center py-5">
                <div class="voter-card">
                    <i class="fas fa-chart-line fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted">No live results available at this time.</h6>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($elections as $election): ?>
                <div class="col-lg-6">
                    <div class="voter-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <div>
                                <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($election['title']); ?></h6>
                                <span class="badge badge-<?php echo $election['status']; ?> extra-small rounded-pill mt-2">
                                    <?php echo ucfirst($election['status']); ?>
                                </span>
                            </div>
                            <?php if ($election['status'] === 'active'): ?>
                                <span class="text-danger small animate__animated animate__flash animate__infinite">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> LIVE
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php
                        // Fetch vote breakdown for this election in THIS constituency
                        $stmt_votes = $pdo->prepare("SELECT u.first_name, u.last_name, p.name_en as party_name,
                                                   (SELECT COUNT(*) FROM votes WHERE election_id = ? AND candidate_id = u.id) as vote_count
                                                   FROM candidate_applications ca
                                                   JOIN users u ON ca.candidate_id = u.id
                                                   LEFT JOIN candidate_details cd ON u.id = cd.user_id
                                                   LEFT JOIN political_parties p ON cd.party_id = p.id
                                                   WHERE ca.election_id = ? AND ca.status = 'approved' AND u.constituency_id = ?
                                                   ORDER BY vote_count DESC");
                        $stmt_votes->execute([$election['id'], $election['id'], $constituency_id]);
                        $results = $stmt_votes->fetchAll();
                        
                        $total_votes_const = 0;
                        foreach($results as $r) $total_votes_const += $r['vote_count'];

                        if (empty($results)):
                        ?>
                            <p class="text-center text-muted small py-4">No candidates approved for this constituency.</p>
                        <?php else: ?>
                            <?php foreach ($results as $res): 
                                $pct = ($total_votes_const > 0) ? round(($res['vote_count'] / $total_votes_const) * 100, 1) : 0;
                            ?>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="small fw-bold text-dark">
                                            <?php echo htmlspecialchars($res['first_name'] . ' ' . $res['last_name']); ?>
                                            <span class="text-muted fw-normal extra-small ms-1">(<?php echo htmlspecialchars($res['party_name'] ?? 'IND'); ?>)</span>
                                        </div>
                                        <div class="small fw-bold text-primary"><?php echo $res['vote_count']; ?> <span class="text-muted fw-normal extra-small">(<?php echo $pct; ?>%)</span></div>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 10px;">
                                        <div class="progress-bar rounded-pill bg-primary" role="progressbar" style="width: <?php echo $pct; ?>%" aria-valuenow="<?php echo $pct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="text-end border-top pt-3">
                                <small class="text-muted">Total Votes in Constituency: <strong><?php echo $total_votes_const; ?></strong></small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php } catch (PDOException $e) { echo "Error loading results."; } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
