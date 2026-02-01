<?php
// actions/register_process.php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Password match check
    if ($password_raw !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../signup.php");
        exit;
    }

    $password = password_hash($password_raw, PASSWORD_BCRYPT);
    $citizenship = $_POST['citizenship_no'] ?? null;
    
    if (!in_array($role, ['voter', 'candidate'])) {
        $_SESSION['error'] = "Invalid registration role!";
        header("Location: ../signup.php");
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, password, role, citizenship_number, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $status = 'pending';
        $stmt->execute([$full_name, $username, $email, $password, $role, $citizenship, $status]);
        $user_id = $pdo->lastInsertId();

        if ($role === 'candidate') {
            $manifesto = $_POST['manifesto'] ?? '';
            $stmt = $pdo->prepare("INSERT INTO candidate_details (user_id, manifesto) VALUES (?, ?)");
            $stmt->execute([$user_id, $manifesto]);
        }

        $pdo->commit();

        $_SESSION['success'] = "Registration successful! Waiting for Admin Approval.";
        header("Location: ../login.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>
