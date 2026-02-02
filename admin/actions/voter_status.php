<?php
require_once '../../includes/config.php';

// Check Admin Access (Basic check, reliable if session set properly)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    $status = ($action == 'ban') ? 'rejected' : 'approved';
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        $_SESSION['success'] = "Voter status updated efficiently."; // Standard message
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating status.";
    }
    
    header("Location: ../voter_list.php");
    exit;
}
?>
