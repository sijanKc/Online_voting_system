<?php require_once 'includes/candidate_header.php'; ?>
<?php require_once 'includes/candidate_sidebar.php'; ?>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-2">
            <i class="fas fa-bullhorn text-warning me-2"></i> Campaign Materials
        </h2>
        <p class="text-muted">Upload and manage banners, videos, and promotional content.</p>
    </div>

    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-5 d-flex align-items-center">
        <i class="fas fa-tools fs-4 me-3"></i>
        <div>
            <h6 class="fw-bold mb-1">Feature Under Development</h6>
            <p class="small mb-0 opacity-75">The media management system is currently being optimized for high-resolution campaign assets.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card-candidate p-4 text-center border-top border-5 border-indigo">
                <i class="fas fa-image fs-1 text-muted mb-3"></i>
                <h6 class="fw-bold">Visual Assets</h6>
                <p class="small text-muted">Upload campaign posters and digital banners.</p>
                <button class="btn btn-sm btn-light rounded-pill w-100" disabled>Coming Soon</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-candidate p-4 text-center border-top border-5 border-danger">
                <i class="fas fa-video fs-1 text-muted mb-3"></i>
                <h6 class="fw-bold">Video Content</h6>
                <p class="small text-muted">Manage campaign ads and speech recordings.</p>
                <button class="btn btn-sm btn-light rounded-pill w-100" disabled>Coming Soon</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-candidate p-4 text-center border-top border-5 border-success">
                <i class="fas fa-file-pdf fs-1 text-muted mb-3"></i>
                <h6 class="fw-bold">Documents</h6>
                <p class="small text-muted">Upload detailed policy papers or flyers.</p>
                <button class="btn btn-sm btn-light rounded-pill w-100" disabled>Coming Soon</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
