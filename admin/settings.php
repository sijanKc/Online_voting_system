<?php require_once 'includes/admin_header.php'; ?>
<?php require_once 'includes/admin_sidebar.php'; ?>

<div class="main-content">
    <h2 class="fw-bold text-dark mb-4">
        <i class="fas fa-cog text-secondary me-2"></i> System Settings
    </h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold">General Configuration</h5>
                </div>
                <div class="card-body p-4">
                    <form>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="maintenanceMode">
                            <label class="form-check-label" for="maintenanceMode">Maintenance Mode</label>
                            <div class="form-text">If enabled, only admins can access the site.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">System Name</label>
                            <input type="text" class="form-control" value="Nepal Online Voting System">
                        </div>
                        <button type="button" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
             <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold">Admin Security</h5>
                </div>
                <div class="card-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control">
                        </div>
                        <button type="button" class="btn btn-danger rounded-pill px-4">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
