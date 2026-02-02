<?php
// database/seed_candidates.php
require_once __DIR__ . '/../includes/config.php';
set_time_limit(0);

echo "Starting Candidate Seeding Process (2 Candidates per Constituency)...\n";

try {
    // 1. Fetch Location Data
    $sql = "SELECT c.id as constituency_id, c.district_id, d.province_id 
            FROM constituencies c
            JOIN districts d ON c.district_id = d.id";
    $constituencies = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    // 2. Fetch Parties (excluding 'Independent' to force party variety first, or include it)
    $parties = $pdo->query("SELECT id FROM political_parties WHERE status='active'")->fetchAll(PDO::FETCH_COLUMN);
    
    // Names dataset
    $first_names = ['Bikesh', 'Suresh', 'Manoj', 'Rabin', 'Kiran', 'Sarita', 'Priya', 'Goma', 'Laxman', 'Bharat', 'Rajendra', 'Madhav', 'Sher', 'Pushpa', 'KP', 'Gagan'];
    $last_names = ['Oli', 'Deuba', 'Dahal', 'Nepal', 'Thapa', 'Lingden', 'Yadav', 'Bhattarai', 'Khanal', 'Poudel', 'Raut', 'Chaudhary', 'Shaky'];
    
    $pdo->beginTransaction();

    $user_stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, citizenship_number, dob, password, role, status, province_id, district_id, constituency_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, 'candidate', 'approved', ?, ?, ?)");
    
    $detail_stmt = $pdo->prepare("INSERT INTO candidate_details (user_id, party_id, verification_status, bio, manifesto) VALUES (?, ?, 'verified', ?, ?)");
    
    $default_pass = password_hash('12345', PASSWORD_DEFAULT);
    $count = 0;

    foreach ($constituencies as $loc) {
        $c_id = $loc['constituency_id'];
        
        // Pick 2 random DISTINCT parties
        $keys = (count($parties) >= 2) ? array_rand($parties, 2) : [0, 0]; // Fallback safety
        $party_ids = is_array($keys) ? [$parties[$keys[0]], $parties[$keys[1]]] : [$parties[$keys]]; // Handle edge case
        
        foreach ($party_ids as $idx => $pid) {
            // Generate Data
            $fname = $first_names[array_rand($first_names)];
            $lname = $last_names[array_rand($last_names)];
            $username = strtolower("cand_{$c_id}_{$pid}_{$idx}_" . rand(100,999));
            $email = $username . "@candidate.np";
            $citizenship = "CAND-CIT-{$c_id}-{$pid}-" . rand(1000,9999);
            $dob = "19" . rand(70, 99) . "-01-01";
            
            // Insert User
            $user_stmt->execute([
                $fname, $lname, $username, $email, $citizenship, $dob, $default_pass, 
                $loc['province_id'], $loc['district_id'], $c_id
            ]);
            $user_id = $pdo->lastInsertId();
            
            // Insert Details
            $bio = "Dedicated leader committed to the development of Constituency $c_id.";
            $manifesto = "1. Education for all.\n2. Infrastructure development.\n3. Healthcare accessibility.";
            
            $detail_stmt->execute([$user_id, $pid, $bio, $manifesto]);
            $count++;
        }
    }
    
    $pdo->commit();
    echo "SUCCESS: Seeded {$count} candidates across " . count($constituencies) . " constituencies.\n";

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "ERROR: " . $e->getMessage();
}
?>
