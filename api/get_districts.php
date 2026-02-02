<?php
require_once '../includes/config.php';

// Set header to JSON
header('Content-Type: application/json');

if (!isset($_GET['province_id'])) {
    echo json_encode(['error' => 'Province ID is required']);
    exit;
}

$province_id = intval($_GET['province_id']);

try {
    $stmt = $pdo->prepare("SELECT id, name_" . $lang . " as name FROM districts WHERE province_id = ? ORDER BY name ASC");
    $stmt->execute([$province_id]);
    $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($districts);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error']);
}
?>
