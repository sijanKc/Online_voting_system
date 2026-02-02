<?php
// Use __DIR__ to ensure correct path resolution
require_once __DIR__ . '/../includes/config.php';

try {
    $sql_file = __DIR__ . '/fresh_setup_nepal_election.sql';
    if (!file_exists($sql_file)) {
        die("Master SQL file not found at: " . $sql_file);
    }
    
    $sql = file_get_contents($sql_file);
    
    // Split key statements by delimiter if needed, but given the complexity, 
    // let's try direct executions or robust splitting.
    // The previous Robust method worked well.
    $statements = explode(';', $sql);
    
    // Explicitly disable FK checks to allow dropping tables in any order
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

    foreach ($statements as $statement) {
        $stmt_sql = trim($statement);
        if (!empty($stmt_sql)) {
            try {
                $pdo->exec($stmt_sql);
            } catch (PDOException $e) {
                // Determine if critical. For a FRESH setup, errors are bad unless it's just empty lines.
                // We'll output all errors to help debug.
                echo "Error on SQL: " . substr($stmt_sql, 0, 50) . "... -> " . $e->getMessage() . "\n";
            }
        }
    }
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    echo "Fresh Election Database Setup Successfully Completed!\n";
    
} catch (Exception $e) {
    echo "Critical Setup Failure: " . $e->getMessage() . "\n";
}
?>
