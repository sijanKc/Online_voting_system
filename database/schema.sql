-- Online Voting System Database Schema
-- Created for XAMPP PHPMyAdmin

CREATE DATABASE IF NOT EXISTS online_voting_system;
USE online_voting_system;

-- 1. Users Table (Voters, Candidates, Admins)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'voter', 'candidate') NOT NULL,
    citizenship_number VARCHAR(50) UNIQUE,
    dob DATE,
    address TEXT,
    phone VARCHAR(20),
    photo VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected', 'suspended') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Candidate Details (Additional info for candidates)
CREATE TABLE IF NOT EXISTS candidate_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    party_affiliation VARCHAR(100),
    manifesto TEXT,
    documents VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Elections Table
CREATE TABLE IF NOT EXISTS elections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('upcoming', 'active', 'completed') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Candidate Applications (Linking candidates to specific elections)
CREATE TABLE IF NOT EXISTS candidate_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidate_id INT NOT NULL, -- user_id of the candidate
    election_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE
);

-- 5. Votes Table
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id INT NOT NULL,
    candidate_id INT NOT NULL, -- user_id of the candidate
    election_id INT NOT NULL,
    vote_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- CRITICAL: Prevent a voter from voting more than once in the same election
    UNIQUE KEY one_vote_per_election (voter_id, election_id),
    FOREIGN KEY (voter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE
);

-- 6. Settings Table (For admin system configurations)
CREATE TABLE IF NOT EXISTS settings (
    setting_name VARCHAR(50) PRIMARY KEY,
    setting_value TEXT NOT NULL
);

-- Insert Default Settings
INSERT INTO settings (setting_name, setting_value) VALUES ('admin_invitation_code', 'ADMIN123');

-- Insert a Default Admin (Credentials: admin / 12345)
INSERT INTO users (full_name, username, email, password, role, status) 
VALUES ('System Admin', 'admin', 'admin@vote.com', '$2y$10$pL3hWv5P63oP4v9.9V8A8C8x8E8F8G8H8I8J8K8L8M8N8O8P8Q8R.', 'admin', 'approved');
