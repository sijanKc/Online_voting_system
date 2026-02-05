<?php require_once 'includes/candidate_header.php'; ?>
<?php require_once 'includes/candidate_sidebar.php'; ?>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-2">
            <i class="fas fa-file-alt text-info me-2"></i> Manifesto Management
        </h2>
        <p class="text-muted">Draft and refine your campaign promises and political platform.</p>
    </div>

    <!-- Feedback Alerts -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php
    try {
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT cd.*, p.name_en as party_name, p.logo_path as default_party_logo
                               FROM candidate_details cd
                               LEFT JOIN political_parties p ON cd.party_id = p.id
                               WHERE cd.user_id = ?");
        $stmt->execute([$user_id]);
        $details = $stmt->fetch();
        
        $logo_to_resolve = !empty($details['party_logo']) ? $details['party_logo'] : ($details['default_party_logo'] ?? '');
        $current_logo = '../' . resolve_party_logo($logo_to_resolve);
    } catch (PDOException $e) {
        $details = [];
        $current_logo = '';
    }
    ?>

    <div class="row g-4">
        <!-- Manifesto Editor -->
        <div class="col-lg-8">
            <div class="stat-card-candidate p-4">
                <form action="actions/update_manifesto.php" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Campaign Manifesto</label>
                        <textarea name="manifesto" class="form-control border-0 bg-light p-4 rounded-4" rows="15" placeholder="Share your vision for the constituency..."><?php echo htmlspecialchars($details['manifesto'] ?? ''); ?></textarea>
                        <div class="form-text mt-2 text-muted">
                            <i class="fas fa-info-circle me-1"></i> Your manifesto will be visible to all voters in your constituency.
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg">
                        <i class="fas fa-save me-2"></i> Save Manifesto
                    </button>
                </form>
            </div>
        </div>

        <!-- Political Identity -->
        <div class="col-lg-4">
            <div class="stat-card-candidate p-4 mb-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3">Political Identity</h5>
                
                <div class="text-center mb-4">
                    <div class="bg-white p-3 rounded-4 border shadow-sm d-inline-block mb-3">
                        <img id="logo_preview" src="<?php echo $current_logo; ?>" alt="Election Symbol" class="img-fluid" style="max-height: 120px; max-width: 120px;">
                    </div>
                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($details['party_name'] ?? 'Independent'); ?></h6>
                    <p class="small text-muted mb-0">Official Election Symbol</p>
                </div>

                <hr class="my-4">

                <form action="actions/update_symbol.php" method="POST" enctype="multipart/form-data">
                    <label class="form-label small fw-bold text-muted">Update Election Symbol</label>
                    <input type="file" name="party_logo" class="form-control form-control-sm mb-3" accept="image/*" onchange="previewSymbol(this)">
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold">
                        <i class="fas fa-upload me-1"></i> Upload Custom Symbol
                    </button>
                    <div class="form-text mt-2 extra-small text-muted">
                        Only for independent candidates or special party overrides.
                    </div>
                </form>
            </div>

            <div class="alert alert-info border-0 shadow-sm rounded-4">
                <h6 class="fw-bold"><i class="fas fa-lightbulb me-2"></i> Pro Tip</h6>
                <p class="small mb-0">A clear, concise manifesto increases voter trust. Focus on local issues in your constituency.</p>
            </div>
        </div>
    </div>
</div>

<script>
function previewSymbol(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logo_preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
