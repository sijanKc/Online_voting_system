<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-address-book text-primary me-2"></i> Voter Roll
            </h2>
            <p class="text-muted small mb-0">Manage national voter registry and verification.</p>
        </div>
        <div>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                Total Voters: 
                <?php 
                $cnt = $pdo->query("SELECT COUNT(*) FROM users WHERE role='voter'")->fetchColumn(); 
                echo number_format($cnt); 
                ?>
            </span>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><i class="fas fa-filter me-2"></i> Filter Voters</h6>
            <form action="" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="province" id="province_id" class="form-select bg-light border-0">
                        <option value="">Select Province</option>
                        <?php
                        $provs = $pdo->query("SELECT * FROM provinces")->fetchAll();
                        foreach($provs as $p) {
                            $sel = ($_GET['province'] ?? '') == $p['id'] ? 'selected' : '';
                            echo "<option value='{$p['id']}' $sel>{$p['name_en']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- Dynamic loading for district/constituency handled by JS, but for simple GET filtering, PHP can re-render if selected -->
                <!-- For now, simplifying to just Province filter or Search for MVP, or better, use same JS but trigger submit on change if we want server-side? 
                     Actually, with 800 voters, server-side search is best.
                     Let's keep it simple: Search + Province Filter.
                -->
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search ID or Name..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
                </div>
                <div class="col-md-2">
                     <a href="voter_list.php" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Voter Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0 small text-muted text-uppercase fw-bold">Voter Details</th>
                            <th class="py-3 border-0 small text-muted text-uppercase fw-bold">Citizenship</th>
                            <th class="py-3 border-0 small text-muted text-uppercase fw-bold">Location</th>
                            <th class="py-3 border-0 small text-muted text-uppercase fw-bold">Status</th>
                            <th class="py-3 border-0 small text-muted text-uppercase fw-bold text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Pagination logic
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $limit = 10;
                        $offset = ($page - 1) * $limit;

                        // Build Query
                        $where = "WHERE u.role = 'voter'";
                        $params = [];
                        
                        if (!empty($_GET['province'])) {
                            $where .= " AND u.province_id = ?";
                            $params[] = $_GET['province'];
                        }
                        
                        if (!empty($_GET['search'])) {
                            $term = "%" . $_GET['search'] . "%";
                            $where .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.citizenship_number LIKE ?)";
                            $params[] = $term;
                            $params[] = $term;
                            $params[] = $term;
                        }

                        // Total Count for Pagination
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users u $where");
                        $stmt->execute($params);
                        $total_records = $stmt->fetchColumn();
                        $total_pages = ceil($total_records / $limit);

                        // Main Fetch
                        $sql = "SELECT u.*, p.name_en as province, d.name_en as district, c.name_en as constituency 
                                FROM users u 
                                LEFT JOIN provinces p ON u.province_id = p.id 
                                LEFT JOIN districts d ON u.district_id = d.id 
                                LEFT JOIN constituencies c ON u.constituency_id = c.id 
                                $where 
                                ORDER BY u.created_at DESC 
                                LIMIT $limit OFFSET $offset";
                                
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($params);
                        $voters = $stmt->fetchAll();

                        if (count($voters) > 0) {
                            foreach ($voters as $v) {
                                $status_badge = match($v['status']) {
                                    'approved' => 'success',
                                    'pending' => 'warning',
                                    'rejected' => 'danger',
                                    default => 'secondary'
                                };
                                ?>
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($v['first_name'] . ' ' . $v['last_name']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($v['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="font-monospace text-dark"><?php echo htmlspecialchars($v['citizenship_number']); ?></span></td>
                                    <td>
                                        <div class="small">
                                            <div class="fw-bold"><?php echo htmlspecialchars($v['province']); ?></div>
                                            <div class="text-muted"><?php echo htmlspecialchars($v['district']); ?> | <?php echo htmlspecialchars($v['constituency']); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $status_badge; ?>-subtle text-<?php echo $status_badge; ?> rounded-pill px-3">
                                            <?php echo ucfirst($v['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end px-4">
                                        <!-- Actions -->
                                        <div class="btn-group">
                                            <!-- View Action -->
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewVoterModal"
                                                data-id="<?php echo $v['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($v['first_name'] . ' ' . $v['last_name']); ?>"
                                                data-username="<?php echo htmlspecialchars($v['username']); ?>"
                                                data-email="<?php echo htmlspecialchars($v['email']); ?>"
                                                data-dob="<?php echo htmlspecialchars($v['dob']); ?>"
                                                data-citizenship="<?php echo htmlspecialchars($v['citizenship_number']); ?>"
                                                data-address="<?php echo htmlspecialchars($v['province'] . ', ' . $v['district'] . ', ' . $v['constituency']); ?>"
                                                data-status="<?php echo ucfirst($v['status']); ?>"
                                                data-joined="<?php echo date('F j, Y', strtotime($v['created_at'])); ?>"
                                                title="View Full Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <?php if($v['status'] !== 'rejected'): ?>
                                                <a href="actions/voter_status.php?id=<?php echo $v['id']; ?>&action=ban" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to BAN this voter?');" title="Ban Voter">
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="actions/voter_status.php?id=<?php echo $v['id']; ?>&action=approve" class="btn btn-sm btn-outline-success" onclick="return confirm('Re-activate this voter?');" title="Re-activate">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="actions/voter_delete.php?id=<?php echo $v['id']; ?>" class="btn btn-sm btn-outline-secondary ms-1" onclick="return confirm('Permanently DELETE this voter?');" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>No voters found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
            <div class="card-footer bg-white border-0 py-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">
                        <?php if($page > 1): ?>
                            <li class="page-item"><a class="page-link border-0" href="?page=<?php echo $page-1; ?>&province=<?php echo $_GET['province']??''; ?>&search=<?php echo $_GET['search']??''; ?>"><i class="fas fa-chevron-left"></i></a></li>
                        <?php endif; ?>
                        
                        <li class="page-item disabled"><span class="page-link border-0 text-muted">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span></li>
                        
                        <?php if($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link border-0" href="?page=<?php echo $page+1; ?>&province=<?php echo $_GET['province']??''; ?>&search=<?php echo $_GET['search']??''; ?>"><i class="fas fa-chevron-right"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<!-- Voter Details Modal -->
<div class="modal fade" id="viewVoterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-id-card text-primary me-2"></i> Voter Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-inline-block mb-3">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-0" id="modal_name">User Name</h4>
                    <span class="badge bg-secondary rounded-pill mt-2" id="modal_status">Status</span>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-muted d-block fw-bold text-uppercase">Citizenship No</small>
                        <span id="modal_citizenship" class="fs-6 font-monospace">---</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block fw-bold text-uppercase">Date of Birth</small>
                        <span id="modal_dob" class="fs-6">---</span>
                    </div>
                    
                    <div class="col-12 border-top my-2 pt-2"></div>
                    
                    <div class="col-6">
                        <small class="text-muted d-block fw-bold text-uppercase">Username</small>
                        <span id="modal_username" class="fs-6">---</span>
                    </div>
                    <!-- Email moved to full width for better layout -->
                    <div class="col-12">
                        <small class="text-muted d-block fw-bold text-uppercase">Email Address</small>
                        <span id="modal_email" class="fs-6 text-break">---</span>
                    </div>
                    
                    <div class="col-12 mt-2">
                        <small class="text-muted d-block fw-bold text-uppercase">Registered Address</small>
                        <p id="modal_address" class="mb-0 bg-light p-2 rounded-3 border">---</p>
                    </div>

                    <div class="col-12 mt-3 p-3 bg-warning bg-opacity-10 rounded-3 border border-warning border-dashed">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block fw-bold text-uppercase mb-1">Password</small>
                                <div class="small text-muted"><i class="fas fa-lock me-1"></i> Encrypted (Cannot be viewed)</div>
                            </div>
                            <a href="#" id="reset_pass_btn" class="btn btn-sm btn-warning fw-bold shadow-sm" onclick="return confirm('Reset this user\'s password to 12345?');">
                                <i class="fas fa-key me-1"></i> Reset to '12345'
                            </a>
                        </div>
                    </div>
                    
                     <div class="col-12 text-center mt-3 text-muted small">
                        Registered On: <span id="modal_joined">---</span>
                     </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill w-100" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle Modal Data Population
    const viewModal = document.getElementById('viewVoterModal');
    if (viewModal) {
        viewModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            
            // Extract info
            viewModal.querySelector('#modal_name').textContent = button.getAttribute('data-name');
            viewModal.querySelector('#modal_username').textContent = button.getAttribute('data-username');
            viewModal.querySelector('#modal_email').textContent = button.getAttribute('data-email');
            viewModal.querySelector('#modal_citizenship').textContent = button.getAttribute('data-citizenship');
            viewModal.querySelector('#modal_dob').textContent = button.getAttribute('data-dob');
            viewModal.querySelector('#modal_address').textContent = button.getAttribute('data-address');
            viewModal.querySelector('#modal_status').textContent = button.getAttribute('data-status');
            viewModal.querySelector('#modal_joined').textContent = button.getAttribute('data-joined');
            
            // Update Reset Link
            const userId = button.getAttribute('data-id');
            const resetBtn = viewModal.querySelector('#reset_pass_btn');
            resetBtn.href = 'actions/voter_reset_pass.php?id=' + userId;
            
            // Style Status Badge
            const status = button.getAttribute('data-status').toLowerCase();
            const badge = viewModal.querySelector('#modal_status');
            badge.className = 'badge rounded-pill mt-2'; // Reset
            if(status === 'approved') badge.classList.add('bg-success');
            else if(status === 'rejected') badge.classList.add('bg-danger');
            else badge.classList.add('bg-warning', 'text-dark');
        });
    }
</script>
</body>
</html>
