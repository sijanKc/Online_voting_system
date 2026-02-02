<?php
require_once '../includes/config.php';

// Set header to JSON
header('Content-Type: application/json');

if (!isset($_GET['district_id'])) {
    echo json_encode(['error' => 'District ID is required']);
    exit;
}

$district_id = intval($_GET['district_id']);

try {
    $stmt = $pdo->prepare("SELECT id, name_" . $lang . " as name FROM constituencies WHERE district_id = ? ORDER BY id ASC");
    $stmt->execute([$district_id]);
    $constituencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($constituencies);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error']);
}
?>
