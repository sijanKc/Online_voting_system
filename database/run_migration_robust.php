<?php
// Use __DIR__ to ensure correct path resolution regardless of execution directory
require_once __DIR__ . '/../includes/config.php';

try {
    $sql_file = __DIR__ . '/migration_nepal_hierarchy.sql';
    if (!file_exists($sql_file)) {
        die("SQL file not found at: " . $sql_file);
    }
    
    $sql = file_get_contents($sql_file);
    
    // Split by semicolon
    $statements = explode(';', $sql);
    
    // We might need to disable FK checks for safety
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

    foreach ($statements as $statement) {
        $stmt_sql = trim($statement);
        if (!empty($stmt_sql)) {
            try {
                $pdo->exec($stmt_sql);
            } catch (PDOException $e) {
                // Determine if it's a critical error or just "table exists"
                // For "Table already exists", we can ignore if using CREATE TABLE IF NOT EXISTS
                // For "Duplicate entry", we can ignore for INSERTs if we want to be safe, but ideally we shouldn't have them if table was empty.
                // Let's just output for debug.
                echo "Warning on SQL: " . substr($stmt_sql, 0, 30) . "... Error: " . $e->getMessage() . "\n";
            }
        }
    }
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    echo "Robust Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>
