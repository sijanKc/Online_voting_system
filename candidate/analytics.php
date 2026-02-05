<?php require_once 'includes/candidate_header.php'; ?>
<?php require_once 'includes/candidate_sidebar.php'; ?>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-2">
            <i class="fas fa-chart-line text-success me-2"></i> Campaign Analytics
        </h2>
        <p class="text-muted">Monitor your live performance and voter engagement within your constituency.</p>
    </div>

    <?php
    try {
        $user_id = $_SESSION['user_id'];
        
        // Fetch votes by election
        $stmt = $pdo->prepare("SELECT e.title, COUNT(v.id) as vote_count 
                               FROM elections e
                               LEFT JOIN votes v ON e.id = v.election_id AND v.candidate_id = ?
                               JOIN candidate_applications ca ON e.id = ca.election_id AND ca.candidate_id = ?
                               WHERE ca.status = 'approved'
                               GROUP BY e.id");
        $stmt->execute([$user_id, $user_id]);
        $stats = $stmt->fetchAll();
        
        $labels = [];
        $data = [];
        foreach ($stats as $row) {
            $labels[] = $row['title'];
            $data[] = $row['vote_count'];
        }
    } catch (PDOException $e) {
        $stats = [];
        $labels = [];
        $data = [];
    }
    ?>

    <div class="row g-4">
        <!-- Main Chart -->
        <div class="col-lg-8">
            <div class="stat-card-candidate p-4 h-100">
                <h5 class="fw-bold mb-4">Vote Distribution per Election</h5>
                <?php if (empty($stats)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fs-1 text-muted mb-3 d-block"></i>
                        <h6 class="text-muted">No data available yet</h6>
                        <p class="small text-muted">Analytics will update as soon as voting begins in your constituency.</p>
                    </div>
                <?php else: ?>
                    <canvas id="votesChart" height="300"></canvas>
                <?php endif; ?>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="col-lg-4">
            <div class="stat-card-candidate p-4 mb-4 bg-primary bg-opacity-10 border-0 shadow-none">
                <h6 class="text-primary fw-bold text-uppercase small mb-3">Total Votes (All Time)</h6>
                <h2 class="fw-bold mb-0 text-primary"><?php echo array_sum($data); ?></h2>
                <p class="small text-primary text-opacity-75 mt-1">Real-time count across all active races</p>
            </div>

            <div class="stat-card-candidate p-4 border-start border-5 border-success">
                <h6 class="text-success fw-bold text-uppercase small mb-3">Voter Engagement</h6>
                <h3 class="fw-bold mb-0 text-dark">N/A</h3>
                <p class="small text-muted mt-1">Based on constituency-wide turnout (Coming Soon)</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($stats)): ?>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('votesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Votes Received',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                borderRadius: 10,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
<?php endif; ?>
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
