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

// Global constants
define('SITE_NAME', __('site_name'));
define('BASE_URL', 'http://localhost/online_voting_system/');
?>
