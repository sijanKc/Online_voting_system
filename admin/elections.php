<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-vote-yea text-primary me-2"></i> Election Management
            </h2>
            <p class="text-muted small mb-0">Create and oversee electoral events.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-info rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#rulesModal">
                <i class="fas fa-info-circle me-2"></i> Election Rules
            </button>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createElectionModal">
                <i class="fas fa-plus me-2"></i> Create Election
            </button>
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

    <!-- Elections Grid -->
    <div class="row g-4">
        <?php
        $stmt = $pdo->query("SELECT * FROM elections ORDER BY start_date DESC");
        $elections = $stmt->fetchAll();

        if (count($elections) > 0) {
            foreach($elections as $e) {
                // Status Color
                $statusColor = 'secondary';
                $statusIcon = 'clock';
                if ($e['status'] == 'active') { $statusColor = 'success'; $statusIcon = 'check-circle'; }
                if ($e['status'] == 'closed') { $statusColor = 'danger'; $statusIcon = 'lock'; }
                ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden stat-card-ecn">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between">
                            <span class="badge bg-<?php echo $statusColor; ?> bg-opacity-10 text-<?php echo $statusColor; ?> px-3 py-2 rounded-pill">
                                <i class="fas fa-<?php echo $statusIcon; ?> me-1"></i> <?php echo ucfirst($e['status']); ?>
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3">
                                    <?php if($e['status'] == 'upcoming'): ?>
                                    <li><a class="dropdown-item text-success fw-bold" href="actions/election_ops.php?id=<?php echo $e['id']; ?>&action=open"><i class="fas fa-play me-2"></i> Start Election</a></li>
                                    <?php endif; ?>
                                    
                                    <?php if($e['status'] == 'active'): ?>
                                    <li><a class="dropdown-item text-danger fw-bold" href="actions/election_ops.php?id=<?php echo $e['id']; ?>&action=close" onclick="return confirm('Are you sure? Voting will stop immediately.');"><i class="fas fa-stop me-2"></i> End Election</a></li>
                                    <?php endif; ?>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="actions/election_ops.php?id=<?php echo $e['id']; ?>&action=delete" onclick="return confirm('Permanently delete this election? Votes will be lost.');"><i class="fas fa-trash-alt me-2"></i> Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body px-4 pt-2">
                            <h5 class="fw-bold text-dark mb-3 mt-2"><?php echo htmlspecialchars($e['title']); ?></h5>
                            
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="fas fa-calendar-alt me-3 text-primary opacity-50" style="width: 20px;"></i>
                                <span class="small">
                                    Start: <strong><?php echo date('M d, Y h:i A', strtotime($e['start_date'])); ?></strong>
                                </span>
                            </div>
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-calendar-check me-3 text-danger opacity-50" style="width: 20px;"></i>
                                <span class="small">
                                    End: <strong><?php echo date('M d, Y h:i A', strtotime($e['end_date'])); ?></strong>
                                </span>
                            </div>

                            <hr class="my-4 border-light">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-center">
                                    <h5 class="fw-bold mb-0 text-dark">
                                        <?php 
                                        // Count Votes
                                        $vStmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE election_id = ?");
                                        $vStmt->execute([$e['id']]);
                                        echo number_format($vStmt->fetchColumn());
                                        ?>
                                    </h5>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Votes Cast</small>
                                </div>
                                <div class="vr bg-light"></div>
                                <div class="text-end">
                                    <a href="results.php?election_id=<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        View Results <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 py-2 d-flex justify-content-between align-items-center">
                             <small class="text-muted"><i class="fas fa-globe me-1"></i> <?php echo ucfirst($e['type']); ?> Level</small>
                             <?php if($e['type'] === 'provincial' && !empty($e['province_id'])): 
                                $pStmt = $pdo->prepare("SELECT name_en FROM provinces WHERE id = ?");
                                $pStmt->execute([$e['province_id']]);
                                $pName = $pStmt->fetchColumn();
                             ?>
                                <span class="badge bg-white text-info border border-info-subtle small"><?php echo $pName; ?></span>
                             <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="col-12 text-center py-5">
                <div class="py-5">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3 text-muted">
                        <i class="fas fa-box-open fa-3x"></i>
                    </div>
                    <h5 class="fw-bold text-muted">No Elections Found</h5>
                    <p class="text-muted small">Create your first election to get started.</p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createElectionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Create New Election</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="actions/election_ops.php" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-body p-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Election Title</label>
                        <input type="text" name="title" class="form-control bg-light border-0 py-2" placeholder="e.g. Federal General Election 2084" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Election Type</label>
                        <select name="type" id="electionType" class="form-select bg-light border-0" required>
                            <option value="parliamentary">Parliamentary (National)</option>
                            <option value="provincial">Provincial (State Level)</option>
                        </select>
                    </div>

                    <div id="provinceWrapper" class="mb-3 d-none">
                        <label class="form-label fw-bold small text-uppercase text-muted">Target Province</label>
                        <select name="province_id" class="form-select bg-light border-0">
                            <option value="">Select Province...</option>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM provinces ORDER BY name_en");
                            while($p = $stmt->fetch()) {
                                echo "<option value='{$p['id']}'>{$p['name_en']}</option>";
                            }
                            ?>
                        </select>
                        <small class="text-muted extra-small">Only candidates and voters from this province will participate.</small>
                    </div>

                    <div class="row g-3 px-1">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Start Date</label>
                            <input type="datetime-local" name="start_date" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">End Date</label>
                            <input type="datetime-local" name="end_date" class="form-control bg-light border-0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Election</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rules Modal -->
<div class="modal fade" id="rulesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 bg-info text-white">
                <h5 class="modal-title fw-bold">Electoral Rulebook & Workflow</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex mb-4">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Creation Logic</h6>
                        <p class="small text-muted mb-0"><b>Parliamentary</b> elections are nationwide. <b>Provincial</b> elections are locked to a specific province's candidates and voters.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Nomination Workflow</h6>
                        <p class="small text-muted mb-0">1. Admin creates election. <br>2. Candidate submits <b>Nomination Application</b>. <br>3. Admin <b>Approves</b> nomination. <br>4. Candidate appears on Ballot.</p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Geographic Lockdown</h6>
                        <p class="small text-muted mb-0">The system uses <b>Immutable Identity Locking</b>. Voters only see candidates in their registered Constituency. Double voting is impossible.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4 w-100" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('electionType').addEventListener('change', function() {
        const provinceWrapper = document.getElementById('provinceWrapper');
        if (this.value === 'provincial') {
            provinceWrapper.classList.remove('d-none');
            provinceWrapper.querySelector('select').setAttribute('required', 'required');
        } else {
            provinceWrapper.classList.add('d-none');
            provinceWrapper.querySelector('select').removeAttribute('required');
        }
    });
</script>
</body>
</html>
