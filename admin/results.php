<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-chart-pie text-success me-2"></i> Election Results
            </h2>
            <p class="text-muted small mb-0">Real-time vote analytics and counting.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="small text-muted fw-bold text-uppercase mb-1">Select Election</label>
                    <select name="election_id" class="form-select bg-light border-0">
                        <?php
                        // Default to latest active or closed
                        $elections = $pdo->query("SELECT * FROM elections ORDER BY created_at DESC")->fetchAll();
                        $selected_election = $_GET['election_id'] ?? ($elections[0]['id'] ?? '');

                        foreach($elections as $e) {
                            $sel = ($selected_election == $e['id']) ? 'selected' : '';
                            echo "<option value='{$e['id']}' $sel>{$e['title']} (" . ucfirst($e['status']) . ")</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold text-uppercase mb-1">Province</label>
                    <select name="province_id" class="form-select bg-light border-0">
                        <option value="">All Provinces</option>
                        <!-- Populate if needed, skipping for brevity/speed, focusing on constituency -->
                         <?php
                        $provs = $pdo->query("SELECT * FROM provinces")->fetchAll();
                        foreach($provs as $p) {
                            $sel = ($_GET['province_id'] ?? '') == $p['id'] ? 'selected' : '';
                            echo "<option value='{$p['id']}' $sel>{$p['name_en']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- Add more filters if needed -->
                 <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i> Filter</button>
                 </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Candidate</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Party</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Constituency</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Total Votes</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-end px-4">Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($selected_election) {
                            $start_time = microtime(true);
                            
                            // Query: Get all candidates and count their votes for this election
                            // We need LEFT JOIN on votes because some candidates might have 0 votes
                            $sql = "SELECT 
                                        u.first_name, u.last_name, 
                                        pp.name_en as party_name, pp.logo_path,
                                        c.name_en as constituency_name,
                                        COUNT(v.id) as vote_count
                                    FROM users u
                                    JOIN candidate_details cd ON u.id = cd.user_id
                                    JOIN political_parties pp ON cd.party_id = pp.id
                                    JOIN constituencies c ON u.constituency_id = c.id
                                    LEFT JOIN votes v ON u.id = v.candidate_id AND v.election_id = ?
                                    WHERE u.role = 'candidate'
                                    
                                    GROUP BY u.id
                                    ORDER BY vote_count DESC, u.first_name ASC
                                    LIMIT 50"; // Limit for display speed
                            
                            // Filter appending logic here if needed for province
                            
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$selected_election]);
                            $results = $stmt->fetchAll();
                            
                            // Calculate specific max for bar charts
                            $max_votes = ($results[0]['vote_count'] ?? 0);
                            if ($max_votes == 0) $max_votes = 1; // Avoid div by zero

                            foreach($results as $r) {
                                $percentage = ($r['vote_count'] / $max_votes) * 100;
                                ?>
                                <tr>
                                    <td class="px-4 fw-bold"><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="../<?php echo htmlspecialchars($r['logo_path']); ?>" class="me-2" style="width: 20px;">
                                            <span class="small"><?php echo htmlspecialchars($r['party_name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($r['constituency_name']); ?></td>
                                    <td class="text-center fw-bold fs-5 text-dark"><?php echo number_format($r['vote_count']); ?></td>
                                    <td class="px-4" style="width: 200px;">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-5'>Please create/select an election.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
