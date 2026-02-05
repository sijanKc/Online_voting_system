<?php
require_once __DIR__ . '/../includes/config.php';
try {
    $pdo->exec("ALTER TABLE elections ADD COLUMN province_id INT NULL AFTER type");
    echo "Successfully added province_id to elections table.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
