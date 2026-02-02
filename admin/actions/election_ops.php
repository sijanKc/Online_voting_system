<?php
require_once '../../includes/config.php';

// Auth Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'create') {
        $title = $_POST['title'];
        $type = $_POST['type'];
        $start = $_POST['start_date'];
        $end = $_POST['end_date'];
        
        $stmt = $pdo->prepare("INSERT INTO elections (title, type, start_date, end_date, status) VALUES (?, ?, ?, ?, 'upcoming')");
        $stmt->execute([$title, $type, $start, $end]);
        
        $_SESSION['success'] = "Election '$title' created successfully.";
    }
    elseif ($action === 'open') {
        $id = $_GET['id'];
        
        // Optional: Close all other active elections if we only allow one at a time? 
        // For now, let's just open this one.
        $stmt = $pdo->prepare("UPDATE elections SET status = 'active' WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Election is now live!";
    }
    elseif ($action === 'close') {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("UPDATE elections SET status = 'closed' WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Election closed successfully.";
    }
    elseif ($action === 'delete') {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM elections WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Election deleted.";
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

header("Location: ../elections.php");
exit;
?>
