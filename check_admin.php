<?php
require_once 'includes/config.php';

echo "<h2>Current Admin Credentials in Database</h2>";

try {
    $stmt = $pdo->prepare("SELECT id, full_name, email, role, status FROM users WHERE role = 'admin' LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin) {
        echo "<div style='background: #e8f5e9; padding: 20px; border-radius: 10px; border: 1px solid #4caf50;'>";
        echo "<h3 style='color: #2e7d32;'>✅ Admin Account Found!</h3>";
        echo "<table style='width: 100%; border-collapse: collapse;'>";
        echo "<tr><td style='padding: 10px; border-bottom: 1px solid #ddd;'><b>ID:</b></td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . $admin['id'] . "</td></tr>";
        echo "<tr><td style='padding: 10px; border-bottom: 1px solid #ddd;'><b>Name:</b></td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . $admin['full_name'] . "</td></tr>";
        echo "<tr><td style='padding: 10px; border-bottom: 1px solid #ddd;'><b>Email:</b></td><td style='padding: 10px; border-bottom: 1px solid #ddd;'><code>" . $admin['email'] . "</code></td></tr>";
        echo "<tr><td style='padding: 10px; border-bottom: 1px solid #ddd;'><b>Role:</b></td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . $admin['role'] . "</td></tr>";
        echo "<tr><td style='padding: 10px; border-bottom: 1px solid #ddd;'><b>Status:</b></td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . $admin['status'] . "</td></tr>";
        echo "</table>";
        echo "</div>";
        
        echo "<div style='background: #fff3e0; padding: 20px; border-radius: 10px; border: 1px solid #ff9800; margin-top: 20px;'>";
        echo "<h3>🔑 Login Instructions:</h3>";
        echo "<p><b>URL:</b> <a href='login.php'>Go to Login Page</a></p>";
        echo "<p><b>Select Role:</b> Admin 🔴</p>";
        echo "<p><b>Email/Username:</b> <code>" . $admin['email'] . "</code></p>";
        echo "<p><b>Password:</b> <code>12345</code> (if you just ran fix_admin.php)</p>";
        echo "<p style='color: #e65100;'><b>Note:</b> If password doesn't work, run <a href='fix_admin.php'>fix_admin.php</a> to reset it to '12345'</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 20px; border-radius: 10px; border: 1px solid #f44336;'>";
        echo "<h3 style='color: #c62828;'>⚠️ No Admin Account Found!</h3>";
        echo "<p>Please run <a href='fix_admin.php'>fix_admin.php</a> to create an admin account.</p>";
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "<div style='color: red; padding: 20px; border: 1px solid red;'>";
    echo "<h3>⚠️ Database Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?>
