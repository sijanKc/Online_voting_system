<?php
require_once '../../includes/config.php';

// Check Admin Access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Default password for reset
    $new_password_plain = '12345';
    $new_password_hash = password_hash($new_password_plain, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new_password_hash, $id]);
        
        $_SESSION['success'] = "Password reset successfully! The new password is: <strong>$new_password_plain</strong>";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error resetting password.";
    }
    
    header("Location: ../voter_list.php");
    exit;
}
?>
