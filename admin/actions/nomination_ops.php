<?php
session_start();
require_once '../../includes/config.php';

// Auth Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app_id = intval($_POST['app_id']);
    $action = $_POST['action'];

    try {
        if ($action === 'approve') {
            $stmt = $pdo->prepare("UPDATE candidate_applications SET status = 'approved' WHERE id = ?");
            if ($stmt->execute([$app_id])) {
                $_SESSION['success'] = "Nomination approved successfully!";
            }
        } elseif ($action === 'reject') {
            $stmt = $pdo->prepare("UPDATE candidate_applications SET status = 'rejected' WHERE id = ?");
            if ($stmt->execute([$app_id])) {
                $_SESSION['success'] = "Nomination has been rejected.";
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header("Location: ../nominations.php");
exit;
