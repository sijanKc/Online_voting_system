<?php
// actions/login_process.php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username']); // Field name is 'username' but accepts email
    $password = $_POST['password'];
    $role = $_POST['role'];

    try {
        // Fetch user from DB using email
        $stmt = $pdo->prepare("SELECT id, full_name, password, role, status FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            
            // Check status
            if ($user['status'] !== 'approved') {
                $_SESSION['error'] = "Your account is " . $user['status'] . ". Contact Admin.";
                header("Location: ../login.php");
                exit;
            }

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role']; // Using 'role' for consistency in admin check

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../admin/dashboard.php");
                    break;
                case 'candidate':
                    header("Location: ../candidate_dashboard.php");
                    break;
                default:
                    header("Location: ../voter_dashboard.php");
                    break;
            }
            exit;

        } else {
            $_SESSION['error'] = "Invalid Email, Role, or Password!";
            header("Location: ../login.php");
            exit;
        }

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
