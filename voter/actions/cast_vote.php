<?php
session_start();
require_once '../../includes/config.php';

// Check if user is logged in and is a voter
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    exit("Unauthorized Access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['election_id']) && isset($_POST['candidate_id'])) {
    $election_id = intval($_POST['election_id']);
    $candidate_id = intval($_POST['candidate_id']);
    $user_id = $_SESSION['user_id'];

    try {
        // 1. Double check if election is active
        $stmt = $pdo->prepare("SELECT status FROM elections WHERE id = ?");
        $stmt->execute([$election_id]);
        $status = $stmt->fetchColumn();

        if ($status !== 'active') {
            $_SESSION['error'] = "This election is no longer active.";
            header("Location: ../dashboard.php");
            exit;
        }

        // 2. Double check if already voted (Server-side defense)
        $stmt = $pdo->prepare("SELECT id FROM votes WHERE election_id = ? AND voter_id = ?");
        $stmt->execute([$election_id, $user_id]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "You have already cast your vote in this election!";
            header("Location: ../dashboard.php");
            exit;
        }

        // 3. Record the Vote
        $ip_address = $_SERVER['REMOTE_ADDR'];
        // Generate a pseudo-hash for audit (conceptually)
        $vote_hash = hash('sha256', $user_id . $election_id . time());

        $stmt = $pdo->prepare("INSERT INTO votes (voter_id, election_id, candidate_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$user_id, $election_id, $candidate_id])) {
            $_SESSION['vote_confirmed'] = $vote_hash; // Store hash for current session confirmation
            $_SESSION['success'] = "Your vote has been securely recorded!";
            header("Location: ../my_votes.php");
        } else {
            $_SESSION['error'] = "System error recording your vote. Please contact support.";
            header("Location: ../dashboard.php");
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: ../dashboard.php");
    }
} else {
    header("Location: ../dashboard.php");
}
exit();
