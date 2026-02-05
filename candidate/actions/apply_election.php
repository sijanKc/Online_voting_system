<?php
session_start();
require_once '../../includes/config.php';

// Check if user is logged in and is a candidate
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidate') {
    $_SESSION['error'] = "Unauthorized access!";
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['election_id'])) {
    $election_id = intval($_POST['election_id']);
    $candidate_id = $_SESSION['user_id'];

    try {
        // 1. Double check if already applied
        $stmt = $pdo->prepare("SELECT id FROM candidate_applications WHERE candidate_id = ? AND election_id = ?");
        $stmt->execute([$candidate_id, $election_id]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "You have already applied for this election.";
            header("Location: ../my_elections.php");
            exit();
        }

        // 2. Check if election is still open for applications
        $stmt = $pdo->prepare("SELECT status FROM elections WHERE id = ?");
        $stmt->execute([$election_id]);
        $election = $stmt->fetch();

        if (!$election || !in_array($election['status'], ['upcoming', 'active'])) {
            $_SESSION['error'] = "This election is not currently accepting applications.";
            header("Location: ../my_elections.php");
            exit();
        }

        // 3. Insert Application
        $stmt = $pdo->prepare("INSERT INTO candidate_applications (candidate_id, election_id, status) VALUES (?, ?, 'pending')");
        if ($stmt->execute([$candidate_id, $election_id])) {
            $_SESSION['success'] = "Nomination application submitted successfully!";
        } else {
            $_SESSION['error'] = "Failed to submit application. Please try again.";
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
}

header("Location: ../my_elections.php");
exit();
