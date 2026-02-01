<?php
// actions/register_process.php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Additional fields
    $citizenship = $_POST['citizenship_number'] ?? null;
    $phone = $_POST['phone'] ?? null;
    
    // Role validation
    if (!in_array($role, ['voter', 'candidate'])) {
        $_SESSION['error'] = "Invalid registration role!";
        header("Location: ../signup.php");
        exit;
    }

    // Check if email already exists

    // photo handling removed as requested

    try {
        $pdo->beginTransaction();

        // 1. Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role, citizenship_number, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        // Admin role registration is now disabled via signup page.
        // All web registrations are set to 'pending' by default.
        $status = 'pending';
        $stmt->execute([$full_name, $email, $password, $role, $citizenship, $phone, $status]);
        $user_id = $pdo->lastInsertId();

        // 2. Insert into candidate_details if role is candidate
        if ($role === 'candidate') {
            $party = $_POST['party_affiliation'] ?? '';
            $manifesto = $_POST['manifesto'] ?? '';
            
            $stmt = $pdo->prepare("INSERT INTO candidate_details (user_id, party_affiliation, manifesto) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $party, $manifesto]);
        }

        $pdo->commit();

        $_SESSION['success'] = "Registration successful! " . (($status === 'pending') ? "Weighting for Admin Approval." : "You can now login.");
        header("Location: ../login.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>
