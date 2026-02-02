<?php
require_once '../includes/config.php';

// Set header to JSON
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, name_" . $lang . " as name, short_name_" . $lang . " as short_name, logo_path FROM political_parties WHERE status = 'active' ORDER BY name ASC");
    $parties = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Dynamic extension check (supports PNG, JPG, JPEG, SVG, WEBP)
    foreach ($parties as &$party) {
        $original_path = $party['logo_path'];
        $base_dir = '../assets/images/parties/';
        
        // Extract filename without extension (e.g. "assets/images/parties/tree.png" -> "tree")
        $filename = pathinfo($original_path, PATHINFO_FILENAME);
        
        // Check allowed extensions
        $extensions = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
        $found = false;
        
        foreach ($extensions as $ext) {
            $check_path = $base_dir . $filename . '.' . $ext;
            if (file_exists($check_path)) {
                // Update to the actual found file (remove ../ for frontend)
                $party['logo_path'] = 'assets/images/parties/' . $filename . '.' . $ext;
                $found = true;
                break;
            }
        }
        
        // If file not found, keep the original DB path (user might upload it later)
    }
    unset($party); // Break reference
    
    echo json_encode($parties);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error']);
}
?>
