<?php
require_once '../includes/config.php';

try {
    $sql = file_get_contents('migration_voter_refinement.sql');
    
    // Execute
    $pdo->exec($sql);
    echo "Refinement Migration completed successfully!";
    
} catch (PDOException $e) {
    echo "Refinement Migration failed: " . $e->getMessage();
}
?>
