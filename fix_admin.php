<?php
require_once 'includes/config.php';

echo "<h2>Database Synchronization & Admin Reset</h2>";

try {
    // 1. Force add the 'username' column if it doesn't exist
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN username VARCHAR(50) NOT NULL UNIQUE AFTER full_name");
        echo "<p style='color: green;'>✅ Successfully added 'username' column to the users table.</p>";
    } catch (PDOException $e) {
        // Error code 42S21 means column already exists
        if (strpos($e->getMessage(), 'Duplicate column name') !== false || $e->getCode() == '42S21') {
            echo "<p style='color: blue;'>ℹ️ Column 'username' already exists in the users table.</p>";
        } else {
            throw $e;
        }
    }

    // 2. Setup the Admin credentials (admin / 12345)
    $target_user = 'admin';
    $target_pass = '12345';
    $hashed_pass = password_hash($target_pass, PASSWORD_BCRYPT);

    // Try to find an existing admin or 'admin' username
    $stmt = $pdo->prepare("SELECT id FROM users WHERE role = 'admin' OR username = ? LIMIT 1");
    $stmt->execute([$target_user]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, status = 'approved', role = 'admin' WHERE id = ?");
        $stmt->execute([$target_user, $hashed_pass, $existing_user['id']]);
        echo "<p style='color: green;'>✅ Admin credentials updated successfully!</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['System Admin', $target_user, 'admin@vote.com', $hashed_pass, 'admin', 'approved']);
        echo "<p style='color: green;'>✅ New Admin account created successfully!</p>";
    }

    echo "<div style='background: #fdf2f2; padding: 20px; border: 1px solid #c8102e; border-radius: 10px; margin-top: 20px;'>";
    echo "<h3>Ready to Login!</h3>";
    echo "<p><b>URL:</b> <a href='login.php'>Go to Login Page</a></p>";
    echo "<p><b>Role:</b> Select <b>Admin</b> 🔴</p>";
    echo "<p><b>Username:</b> <code>admin</code></p>";
    echo "<p><b>Password:</b> <code>12345</code></p>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<div style='color: red; padding: 20px; border: 1px solid red;'>";
    echo "<h3>⚠️ Error Encountered:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p><b>Solution:</b> Open phpMyAdmin, select your database, and run this SQL:</p>";
    echo "<code>ALTER TABLE users ADD username VARCHAR(50) NOT NULL UNIQUE AFTER full_name;</code>";
    echo "</div>";
}
?>
