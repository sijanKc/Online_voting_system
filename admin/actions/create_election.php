<?php
require_once '../../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    try {
        $stmt = $pdo->prepare("INSERT INTO elections (title, description, start_date, end_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $start_date, $end_date]);

        $_SESSION['success'] = "New election session created successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error creating election: " . $e->getMessage();
    }
}

header("Location: ../elections.php");
exit();
