<?php
require_once '../../includes/config.php';

// Check if user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    
    $status = ($action === 'approve') ? 'approved' : 'rejected';

    try {
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$status, $user_id]);

        $_SESSION['success'] = "User has been " . ucfirst($status) . " successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating user status: " . $e->getMessage();
    }
}

header("Location: ../user_approvals.php");
exit();
