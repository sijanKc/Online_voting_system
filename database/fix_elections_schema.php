<?php
require_once __DIR__ . '/../includes/config.php';
try {
    $pdo->exec("ALTER TABLE elections 
                ADD COLUMN type ENUM('parliamentary', 'provincial', 'local') DEFAULT 'parliamentary' AFTER title,
                ADD COLUMN province_id INT NULL AFTER type");
    echo "Successfully updated elections table schema.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Columns already exist.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
