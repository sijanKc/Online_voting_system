<?php
session_start();
require_once '../../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidate') {
    exit("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['party_logo'])) {
    $user_id = $_SESSION['user_id'];
    
    if ($_FILES['party_logo']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $ext = strtolower(pathinfo($_FILES['party_logo']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $upload_dir = '../../uploads/party_logos/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $filename = 'custom_' . $user_id . '_' . time() . '.' . $ext;
            $destination = $upload_dir . $filename;
            $db_path = 'uploads/party_logos/' . $filename;

            if (move_uploaded_file($_FILES['party_logo']['tmp_name'], $destination)) {
                try {
                    $stmt = $pdo->prepare("UPDATE candidate_details SET party_logo = ? WHERE user_id = ?");
                    $stmt->execute([$db_path, $user_id]);
                    $_SESSION['success'] = "Election symbol updated!";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Database error.";
                }
            } else {
                $_SESSION['error'] = "Upload failed.";
            }
        } else {
            $_SESSION['error'] = "Invalid file type.";
        }
    } else {
        $_SESSION['error'] = "File error.";
    }
}

header("Location: ../manifesto.php");
exit();
