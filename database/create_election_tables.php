<?php
require_once '../includes/config.php';

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS elections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            type ENUM('parliamentary', 'provincial', 'local') DEFAULT 'parliamentary',
            start_date DATETIME NOT NULL,
            end_date DATETIME NOT NULL,
            status ENUM('upcoming', 'active', 'closed') DEFAULT 'upcoming',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS votes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            election_id INT NOT NULL,
            user_id INT NOT NULL, -- The Voter
            candidate_id INT NOT NULL, -- The Candidate (User ID or Candidate ID? Let's link to users.id of candidate usually, or candidate_details.id. Stick to users.id to be safe/simple, or verify relation).
            -- Actually, linking to candidate_details is better if we want party info, but candidate_details links to users. 
            -- Let's use candidate_id as the USER_ID of the candidate for simplicity in joining users table directly.
            
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE,
            
            UNIQUE KEY unique_vote (election_id, user_id) -- One vote per voter per election
        );
    ");
    echo "Tables 'elections' and 'votes' created successfully.";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>
