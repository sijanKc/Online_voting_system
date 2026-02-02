<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-user-tie text-info me-2"></i> Candidate Management
            </h2>
            <p class="text-muted small mb-0">Oversee election contenders and verification.</p>
        </div>
        <div>
            <span class="badge bg-info rounded-pill px-3 py-2">
                Total Candidates: 
                <?php 
                $cnt = $pdo->query("SELECT COUNT(*) FROM users WHERE role='candidate'")->fetchColumn(); 
                echo number_format($cnt); 
                ?>
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><i class="fas fa-filter me-2"></i> Filter Candidates</h6>
            <form action="" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="party" class="form-select bg-light border-0">
                        <option value="">All Parties</option>
                        <?php
                        $parties = $pdo->query("SELECT * FROM political_parties ORDER BY name_en")->fetchAll();
                        foreach($parties as $pt) {
                            $sel = ($_GET['party'] ?? '') == $pt['id'] ? 'selected' : '';
                            echo "<option value='{$pt['id']}' $sel>{$pt['name_en']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="province" class="form-select bg-light border-0">
                        <option value="">All Provinces</option>
                        <?php
                        $provs = $pdo->query("SELECT * FROM provinces")->fetchAll();
                        foreach($provs as $p) {
                            $sel = ($_GET['province'] ?? '') == $p['id'] ? 'selected' : '';
                            echo "<option value='{$p['id']}' $sel>{$p['name_en']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- Search -->
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search Name..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info text-white w-100"><i class="fas fa-search"></i> Filter</button>
                </div>
                <div class="col-md-1">
                     <a href="candidates.php" class="btn btn-outline-secondary w-100"><i class="fas fa-undo"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Candidate</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Political Party</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Constituency</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Status</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Pagination
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $limit = 10;
                        $offset = ($page - 1) * $limit;

                        // Query Building
                        $where = "WHERE u.role = 'candidate'";
                        $params = [];
                        
                        if (!empty($_GET['party'])) {
                            $where .= " AND cd.party_id = ?";
                            $params[] = $_GET['party'];
                        }
                        if (!empty($_GET['province'])) {
                            $where .= " AND u.province_id = ?";
                            $params[] = $_GET['province'];
                        }
                        if (!empty($_GET['search'])) {
                            $term = "%" . $_GET['search'] . "%";
                            $where .= " AND (u.first_name LIKE ? OR u.last_name LIKE ?)";
                            $params[] = $term;
                            $params[] = $term;
                        }

                        // Count
                        $count_sql = "SELECT COUNT(*) FROM users u 
                                      LEFT JOIN candidate_details cd ON u.id = cd.user_id 
                                      $where";
                        $stmt = $pdo->prepare($count_sql);
                        $stmt->execute($params);
                        $total_records = $stmt->fetchColumn();
                        $total_pages = ceil($total_records / $limit);

                        // Fetch Data
                        $sql = "SELECT u.*, cd.party_id, cd.manifesto, cd.verification_status,
                                       pp.name_en as party_name, pp.logo_path,
                                       p.name_en as province, d.name_en as district, c.name_en as constituency
                                FROM users u
                                LEFT JOIN candidate_details cd ON u.id = cd.user_id
                                LEFT JOIN political_parties pp ON cd.party_id = pp.id
                                LEFT JOIN provinces p ON u.province_id = p.id
                                LEFT JOIN districts d ON u.district_id = d.id
                                LEFT JOIN constituencies c ON u.constituency_id = c.id
                                $where
                                ORDER BY u.created_at DESC
                                LIMIT $limit OFFSET $offset";
                        
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($params);
                        $candidates = $stmt->fetchAll();

                        if(count($candidates) > 0) {
                            foreach($candidates as $c) {
                                // Dynamic Logo Check (Fix for SVG/JPG)
                                $logo_db = $c['logo_path']; 
                                $base_name = pathinfo($logo_db, PATHINFO_FILENAME);
                                $dir_pub = 'assets/images/parties/'; // Public path for frontend
                                $exts = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
                                $final_logo = $logo_db; // Default
                                
                                // Check physical existence relative to admin folder
                                foreach($exts as $ext) {
                                    if(file_exists('../' . $dir_pub . $base_name . '.' . $ext)) {
                                        $final_logo = $dir_pub . $base_name . '.' . $ext;
                                        break;
                                    }
                                }
                                
                                // Fallback image if totally missing
                                if (!file_exists('../' . $final_logo) && !file_exists($final_logo)) { // Double check
                                     $final_logo = 'assets/images/no-logo.png';
                                }

                                $status_badge = ($c['status'] == 'approved') ? 'success' : 'warning';
                                ?>
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 text-info rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($c['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="../<?php echo htmlspecialchars($final_logo); ?>" alt="logo" class="me-2" style="width: 30px; height: 30px; object-fit: contain;">
                                            <span class="small fw-bold"><?php echo htmlspecialchars($c['party_name'] ?? 'Independent'); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="fw-bold"><?php echo htmlspecialchars($c['district']); ?></div>
                                            <div class="text-muted"><?php echo htmlspecialchars($c['constituency']); ?></div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-<?php echo $status_badge; ?> rounded-pill"><?php echo ucfirst($c['status']); ?></span></td>
                                    <td class="text-end px-4">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-info me-1" 
                                                    data-bs-toggle="modal" data-bs-target="#viewCandModal"
                                                    data-id="<?php echo $c['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?>"
                                                    data-username="<?php echo htmlspecialchars($c['username']); ?>"
                                                    data-party="<?php echo htmlspecialchars($c['party_name'] ?? 'Independent'); ?>"
                                                    data-logo="../<?php echo htmlspecialchars($final_logo); ?>"
                                                    data-location="<?php echo htmlspecialchars($c['province'] . ', ' . $c['district'] . ', ' . $c['constituency']); ?>"
                                                    data-manifesto="<?php echo htmlspecialchars($c['manifesto']); ?>"
                                                    data-status="<?php echo ucfirst($c['status']); ?>"
                                                    title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- Reuse Voter Actions which work on USERS table -->
                                            <?php if($c['status'] !== 'rejected'): ?>
                                                <a href="actions/voter_status.php?id=<?php echo $c['id']; ?>&action=ban" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ban this candidate?');"><i class="fas fa-ban"></i></a>
                                            <?php else: ?>
                                                <a href="actions/voter_status.php?id=<?php echo $c['id']; ?>&action=approve" class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i></a>
                                            <?php endif; ?>
                                            
                                            <a href="actions/voter_delete.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-secondary ms-1" onclick="return confirm('Delete candidate permanently?');"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                             echo "<tr><td colspan='5' class='text-center py-5 text-muted'>No candidates found matching filters.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
            <div class="card-footer bg-white border-0 py-3">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <!-- Simplified Pagination Links -->
                        <?php 
                        $qs = http_build_query(array_merge($_GET, ['page' => $page-1]));
                        if($page > 1) echo "<li class='page-item'><a class='page-link' href='?$qs'>&laquo;</a></li>";
                        
                        echo "<li class='page-item disabled'><span class='page-link'>Page $page / $total_pages</span></li>";
                        
                        $qs = http_build_query(array_merge($_GET, ['page' => $page+1]));
                        if($page < $total_pages) echo "<li class='page-item'><a class='page-link' href='?$qs'>&raquo;</a></li>";
                        ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewCandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <!-- Header with Official Gradient -->
            <div class="modal-header border-0 text-white position-relative" style="background: linear-gradient(135deg, #003893 0%, #001a4d 100%); height: 140px;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body px-5 pb-5 pt-0">
                <!-- Profile Image / Logo Wrapper -->
                <div class="d-flex justify-content-between align-items-end mb-4" style="margin-top: -60px;">
                    <div class="bg-white p-2 rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <img id="m_logo" src="" class="img-fluid rounded-circle" style="max-height: 90px; max-width: 90px;">
                    </div>
                    <div class="mb-2">
                        <a href="#" id="m_reset_btn" class="btn btn-warning rounded-pill fw-bold shadow-sm btn-sm px-4">
                            <i class="fas fa-key me-2"></i> Reset Password
                        </a>
                    </div>
                </div>

                <!-- Main Info -->
                <div class="row">
                    <div class="col-md-7">
                        <h6 class="text-uppercase fw-bold text-muted small mb-1" id="m_party">Party Name</h6>
                        <h2 class="fw-bold text-dark mb-2" id="m_name">Candidate Name</h2>
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge bg-success rounded-pill px-3 me-2" id="m_status">Verified</span>
                            <span class="text-muted small"><i class="fas fa-map-marker-alt text-danger me-1"></i> <span id="m_location">Location</span></span>
                        </div>
                    </div>
                    <div class="col-md-5">
                       <div class="p-3 bg-light rounded-3 border border-light-subtle">
                           <small class="d-block text-muted fw-bold mb-1">Username / ID</small>
                           <div class="font-monospace text-dark text-break" id="m_username" style="font-size: 0.9em;">---</div>
                       </div>
                    </div>
                </div>

                <!-- Manifesto Section -->
                <div class="mt-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="fas fa-scroll text-primary me-2"></i>Election Manifesto</h5>
                    <div class="p-4 bg-light rounded-3 border-start border-4 border-primary position-relative fst-italic text-muted">
                        <i class="fas fa-quote-left position-absolute top-0 start-0 m-3 text-primary opacity-25 fs-1"></i>
                        <p class="mb-0 text-dark" id="m_manifesto" style="white-space: pre-wrap; position: relative; z-index: 1; line-height: 1.6;">...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const cModal = document.getElementById('viewCandModal');
    if (cModal) {
        cModal.addEventListener('show.bs.modal', e => {
            const btn = e.relatedTarget;
            cModal.querySelector('#m_name').textContent = btn.dataset.name;
            cModal.querySelector('#m_party').textContent = btn.dataset.party;
            cModal.querySelector('#m_logo').src = btn.dataset.logo;
            cModal.querySelector('#m_location').textContent = btn.dataset.location;
            cModal.querySelector('#m_manifesto').textContent = btn.dataset.manifesto || "No manifesto uploaded.";
            cModal.querySelector('#m_status').textContent = btn.dataset.status;
            cModal.querySelector('#m_username').textContent = btn.dataset.username || 'N/A';
            
            cModal.querySelector('#m_reset_btn').href = 'actions/voter_reset_pass.php?id=' + btn.dataset.id;
            
            // Dynamic Verification Badge
            const status = btn.dataset.status.toLowerCase();
            const badge = cModal.querySelector('#m_status');
            if(status === 'approved') {
                badge.className = 'badge bg-success rounded-pill px-3 me-2';
                badge.textContent = 'Verified';
            } else {
                badge.className = 'badge bg-warning text-dark rounded-pill px-3 me-2';
                badge.textContent = 'Pending';
            }
        });
    }
</script>
</body>
</html>
