<?php
session_start();
require_once '../../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidate') {
    exit("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manifesto = trim($_POST['manifesto']);
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("UPDATE candidate_details SET manifesto = ? WHERE user_id = ?");
        if ($stmt->execute([$manifesto, $user_id])) {
            $_SESSION['success'] = "Manifesto updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update manifesto.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
}

header("Location: ../manifesto.php");
exit();
