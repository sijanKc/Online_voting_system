<?php
// database/seed_voters.php
require_once __DIR__ . '/../includes/config.php';

// Set unlimited execution time for large inserts
set_time_limit(0);

echo "Starting Voter Seeding Process (5 Voters per Constituency)...\n";

try {
    // 1. Fetch all constituencies with their location details
    $sql = "SELECT 
                c.id as constituency_id, 
                c.district_id, 
                d.province_id 
            FROM constituencies c
            JOIN districts d ON c.district_id = d.id";
            
    $stmt = $pdo->query($sql);
    $constituencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $total_constituencies = count($constituencies);
    echo "Found {$total_constituencies} Constituencies.\n";
    
    // Arrays for random name generation
    $first_names = ['Ram', 'Sita', 'Hari', 'Gita', 'Shyam', 'Rita', 'Bishal', 'Anita', 'Krishna', 'Radha', 'Arjun', 'Saraswati', 'Binod', 'Manju', 'Dipak', 'Pooja', 'Ramesh', 'Sunita', 'Suresh', 'Laxmi'];
    $last_names = ['Sharma', 'Shrestha', 'Adhikari', 'Thapa', 'Rana', 'Tamang', 'Gurung', 'Rai', 'Limbu', 'Karki', 'Bhandari', 'Basnet', 'Koirala', 'Dahal', 'Yadav', 'Jha', 'Chaudhary', 'Mahato', 'Singh'];
    
    $voters_per_constituency = 5;
    $count = 0;
    
    $pdo->beginTransaction();
    
    // Prepare Insert Statement
    $insert_sql = "INSERT INTO users (first_name, last_name, username, email, citizenship_number, dob, password, role, status, province_id, district_id, constituency_id) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, 'voter', 'approved', ?, ?, ?)";
    $stmt_insert = $pdo->prepare($insert_sql);
    
    // Default password hash for '12345'
    $default_pass = password_hash('12345', PASSWORD_DEFAULT);
    
    foreach ($constituencies as $constituency) {
        $c_id = $constituency['constituency_id'];
        $d_id = $constituency['district_id'];
        $p_id = $constituency['province_id'];
        
        for ($i = 1; $i <= $voters_per_constituency; $i++) {
            // Generate Random Data
            $fname = $first_names[array_rand($first_names)];
            $lname = $last_names[array_rand($last_names)];
            
            // Unique modifiers
            $unique_suffix = $c_id . "_" . $i . "_" . rand(100, 999);
            
            $username = strtolower($fname . $lname . $unique_suffix);
            $email = $username . "@example.com";
            $citizenship = "CIT-" . $d_id . "-" . $c_id . "-" . $unique_suffix;
            
            // Random DOB (18+ years ago)
            $year = rand(1970, 2005);
            $month = rand(1, 12);
            $day = rand(1, 28);
            $dob = "$year-$month-$day";
            
            $stmt_insert->execute([
                $fname, $lname, $username, $email, $citizenship, $dob, $default_pass, $p_id, $d_id, $c_id
            ]);
            
            $count++;
        }
        
        // Commit every 100 constituencies to keep transaction size reasonable if needed, 
        // but 825 is small enough for one go.
    }
    
    $pdo->commit();
    echo "SUCCESS: Seeded {$count} voters across {$total_constituencies} constituencies.\n";
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "ERROR: Seeding failed - " . $e->getMessage() . "\n";
}
?>
