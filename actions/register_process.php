<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Sanitize & Validate Common Fields
    $role = $_POST['role'] ?? 'voter'; // 'voter' or 'candidate'
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    // Construct full_name for backward compatibility if needed, or just insert into new columns
    // We decided to drop full_name usage, but let's keep it empty or concat for now if table expects it (we made it nullable)
    
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $citizenship = trim($_POST['citizenship_no']);
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Location Fields
    $province_id = !empty($_POST['province_id']) ? intval($_POST['province_id']) : null;
    $district_id = !empty($_POST['district_id']) ? intval($_POST['district_id']) : null;
    $constituency_id = !empty($_POST['constituency_id']) ? intval($_POST['constituency_id']) : null;

    // Basic Validation
    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($citizenship) || empty($password) || empty($dob)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../" . ($role == 'candidate' ? 'candidate_signup.php' : 'voter_signup.php'));
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../" . ($role == 'candidate' ? 'candidate_signup.php' : 'voter_signup.php'));
        exit;
    }

    // Age Validation (18+)
    $dob_date = new DateTime($dob);
    $now = new DateTime();
    $age = $now->diff($dob_date)->y;
    if ($age < 18) {
        $_SESSION['error'] = "You must be at least 18 years old to register.";
        header("Location: ../" . ($role == 'candidate' ? 'candidate_signup.php' : 'voter_signup.php'));
        exit;
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? OR citizenship_number = ?");
    $stmt->execute([$email, $username, $citizenship]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "User with these details already exists.";
        header("Location: ../" . ($role == 'candidate' ? 'candidate_signup.php' : 'voter_signup.php'));
        exit;
    }

    // 2. Handle File Uploads (for Candidate)
    $party_logo_path = null;
    $party_id = null;
    $manifesto = null;

    if ($role == 'candidate') {
        $party_id = !empty($_POST['party_id']) ? intval($_POST['party_id']) : null;
        $manifesto = trim($_POST['manifesto']);

        // Handle Logo Upload
        if (isset($_FILES['party_logo']) && $_FILES['party_logo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $filename = $_FILES['party_logo']['name'];
            $filetype = $_FILES['party_logo']['type'];
            $filesize = $_FILES['party_logo']['size'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $_SESSION['error'] = "Invalid file type. Only JPG, PNG, WEBP allowed.";
                header("Location: ../candidate_signup.php");
                exit;
            }

            if ($filesize > 2 * 1024 * 1024) { // 2MB
                $_SESSION['error'] = "File size too large. Max 2MB.";
                header("Location: ../candidate_signup.php");
                exit;
            }

            // Create upload dir if not exists
            $upload_dir = '../uploads/party_logos/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $new_filename = uniqid('logo_') . '.' . $ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['party_logo']['tmp_name'], $destination)) {
                $party_logo_path = 'uploads/party_logos/' . $new_filename;
            }
        }
    }

    // 3. Insert User
    try {
        $pdo->beginTransaction();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert with new fields: first_name, last_name, dob
        // Note: full_name is nullable, we can skip it or concat it
        $sql = "INSERT INTO users (first_name, last_name, username, email, citizenship_number, dob, password, role, province_id, district_id, constituency_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $first_name, 
            $last_name,
            $username, 
            $email, 
            $citizenship, 
            $dob,
            $hashed_password, 
            $role, 
            $province_id, 
            $district_id, 
            $constituency_id
        ]);
        
        $user_id = $pdo->lastInsertId();

        // 4. Insert Candidate Details
        if ($role == 'candidate') {
            $stmt = $pdo->prepare("INSERT INTO candidate_details (user_id, party_id, party_logo, manifesto) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $party_id, $party_logo_path, $manifesto]);
        }

        $pdo->commit();
        
        $_SESSION['success'] = "Registration successful! Please wait for admin approval.";
        header("Location: ../login.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Registration failed: " . $e->getMessage();
        header("Location: ../" . ($role == 'candidate' ? 'candidate_signup.php' : 'voter_signup.php'));
        exit;
    }

} else {
    header("Location: ../index.php");
    exit;
}
?>
