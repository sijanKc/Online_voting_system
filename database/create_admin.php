<?php
require_once __DIR__ . '/../includes/config.php';

try {
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        $password = password_hash('12345', PASSWORD_DEFAULT);
        
        // Note: Full Name is split in new schema
        $sql = "INSERT INTO users (first_name, last_name, username, email, citizenship_number, dob, password, role, status) 
                VALUES ('System', 'Admin', 'admin', 'admin@nepalelection.gov.np', 'ADMIN-001', '2000-01-01', ?, 'admin', 'approved')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$password]);
        
        echo "Admin User Created Successfully! (Username: admin, Password: 12345)";
    } else {
        echo "Admin user already exists.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
