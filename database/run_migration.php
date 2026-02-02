<?php
require_once '../includes/config.php';

try {
    $sql = file_get_contents('migration_nepal_hierarchy.sql');
    
    // Split SQL into individual queries because PDO might not handle multiple statements in one go depending on config
    // But MySQL usually supports it if emulation is on.
    // Let's try executing full block first.
    
    $pdo->exec($sql);
    echo "Migration completed successfully!";
    
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>
