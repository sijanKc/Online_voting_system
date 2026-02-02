<?php
require_once '../../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'voter'");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Voter deleted successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting voter.";
    }
    
    header("Location: ../voter_list.php");
    exit;
}
?>
