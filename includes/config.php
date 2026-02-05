<?php
// config.php - Database Configuration

$host = 'localhost';
$dbname = 'online_voting_system';
$username = 'root'; // Default XAMPP username
$password = '';     // Default XAMPP password

try {
    // Using PDO for better security (SQL Injection prevention)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start Session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle Language Selection
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'ne' ? 'ne' : 'en';
}
$lang = $_SESSION['lang'] ?? 'en';
$lang_file = dirname(__DIR__) . "/languages/$lang.php";
$translations = file_exists($lang_file) ? include($lang_file) : [];

// Helper function for translations
function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}

/**
 * Resolves the correct path for a party logo by checking multiple extensions
 * @param string $db_path The path stored in database
 * @return string The actual existing path
 */
function resolve_party_logo($db_path) {
    if (empty($db_path)) return 'assets/images/parties/independent.jpg';
    
    // If it's a custom upload (contains 'uploads/'), it should have the correct extension already
    if (strpos($db_path, 'uploads/') !== false) {
        return $db_path;
    }

    $base_name = pathinfo($db_path, PATHINFO_FILENAME);
    $dir = 'assets/images/parties/';
    $exts = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
    
    // We use __DIR__ because config.php is in /includes/
    // The project root is one level up from /includes/
    $root = dirname(__DIR__);
    
    foreach ($exts as $ext) {
        $test_path = $dir . $base_name . '.' . $ext;
        if (file_exists($root . '/' . $test_path)) {
            return $test_path;
        }
    }
    
    return $db_path; // Fallback to whatever was in DB
}

// Global constants
define('SITE_NAME', __('site_name'));
define('BASE_URL', 'http://localhost/online_voting_system/');
?>
