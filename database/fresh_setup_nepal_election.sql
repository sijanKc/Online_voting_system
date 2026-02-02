-- MASTER SETUP: Nepal Election System
-- Version: 2.0 (Refined Schema)
-- This script wipes existing data and sets up a fresh environment.

SET FOREIGN_KEY_CHECKS=0;

-- ==========================================
-- 1. DROP EXISTING TABLES
-- ==========================================
DROP TABLE IF EXISTS candidate_details;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS constituencies;
DROP TABLE IF EXISTS districts;
DROP TABLE IF EXISTS provinces;
DROP TABLE IF EXISTS political_parties;

-- ==========================================
-- 2. CREATE SCHEMA
-- ==========================================

-- A. Provinces
CREATE TABLE provinces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_ne VARCHAR(100) NOT NULL,
    capital_en VARCHAR(100),
    capital_ne VARCHAR(100)
);

-- B. Districts
CREATE TABLE districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    province_id INT NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    name_ne VARCHAR(100) NOT NULL,
    FOREIGN KEY (province_id) REFERENCES provinces(id) ON DELETE CASCADE
);

-- C. Constituencies
CREATE TABLE constituencies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    district_id INT NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    name_ne VARCHAR(100) NOT NULL,
    constituency_number INT NOT NULL,
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE CASCADE
);

-- D. Political Parties
CREATE TABLE political_parties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(255) NOT NULL,
    name_ne VARCHAR(255) NOT NULL,
    short_name_en VARCHAR(50),
    short_name_ne VARCHAR(50),
    logo_path VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- E. Users (Voters & Candidates)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    full_name VARCHAR(100) NULL, -- Kept for legacy compatibility if needed, but nullable
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    citizenship_number VARCHAR(50) NOT NULL UNIQUE,
    dob DATE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'voter', 'candidate') NOT NULL DEFAULT 'voter',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    
    -- Location Links
    province_id INT NULL,
    district_id INT NULL,
    constituency_id INT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (province_id) REFERENCES provinces(id) ON DELETE SET NULL,
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE SET NULL,
    FOREIGN KEY (constituency_id) REFERENCES constituencies(id) ON DELETE SET NULL
);

-- F. Candidate Details
CREATE TABLE candidate_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    party_id INT NULL,
    party_logo VARCHAR(255) NULL, -- Custom symbol for independents
    manifesto TEXT,
    bio TEXT,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (party_id) REFERENCES political_parties(id) ON DELETE SET NULL
);

-- ==========================================
-- 3. DATA POPULATION (NEPAL HIERARCHY)
-- ==========================================

-- 3.1 Provinces
INSERT INTO provinces (id, name_en, name_ne, capital_en) VALUES
(1, 'Koshi Province', 'कोशी प्रदेश', 'Biratnagar'),
(2, 'Madhesh Province', 'मधेश प्रदेश', 'Janakpur'),
(3, 'Bagmati Province', 'बागमती प्रदेश', 'Hetauda'),
(4, 'Gandaki Province', 'गण्डकी प्रदेश', 'Pokhara'),
(5, 'Lumbini Province', 'लुम्बिनी प्रदेश', 'Deukhuri'),
(6, 'Karnali Province', 'कर्णाली प्रदेश', 'Birendranagar'),
(7, 'Sudurpashchim Province', 'सुदूरपश्चिम प्रदेश', 'Godawari');

-- 3.2 Districts
INSERT INTO districts (id, province_id, name_en, name_ne) VALUES
-- Koshi (14)
(1, 1, 'Taplejung', 'ताप्लेजुङ'), (2, 1, 'Panchthar', 'पाँचथर'), (3, 1, 'Ilam', 'इलाम'), (4, 1, 'Jhapa', 'झापा'), 
(5, 1, 'Morang', 'मोरङ'), (6, 1, 'Sunsari', 'सुनसरी'), (7, 1, 'Dhankuta', 'धनकुटा'), (8, 1, 'Tehrathum', 'तेह्रथुम'), 
(9, 1, 'Sankhuwasabha', 'संखुवासभा'), (10, 1, 'Bhojpur', 'भोजपुर'), (11, 1, 'Solukhumbu', 'सोलुखुम्बु'), (12, 1, 'Okhaldhunga', 'ओखलढुङ्गा'), 
(13, 1, 'Khotang', 'खोटाङ'), (14, 1, 'Udayapur', 'उदयपुर'),
-- Madhesh (8)
(15, 2, 'Saptari', 'सप्तरी'), (16, 2, 'Siraha', 'सिरहा'), (17, 2, 'Dhanusha', 'धनुषा'), (18, 2, 'Mahottari', 'महोत्तरी'), 
(19, 2, 'Sarlahi', 'सर्लाही'), (20, 2, 'Rautahat', 'रौतहट'), (21, 2, 'Bara', 'बारा'), (22, 2, 'Parsa', 'पर्सा'),
-- Bagmati (13)
(23, 3, 'Dolakha', 'दोलखा'), (24, 3, 'Ramechhap', 'रामेछाप'), (25, 3, 'Sindhuli', 'सिन्धुली'), (26, 3, 'Kavrepalanchok', 'काभ्रेपलाञ्चोक'), 
(27, 3, 'Sindhupalchok', 'सिन्धुपाल्चोक'), (28, 3, 'Rasuwa', 'रसुवा'), (29, 3, 'Nuwakot', 'नुवाकोट'), (30, 3, 'Dhading', 'धादिङ'), 
(31, 3, 'Chitwan', 'चितवन'), (32, 3, 'Makwanpur', 'मकवानपुर'), (33, 3, 'Bhaktapur', 'भक्तपुर'), (34, 3, 'Lalitpur', 'ललितपुर'), (35, 3, 'Kathmandu', 'काठमाडौँ'),
-- Gandaki (11)
(36, 4, 'Gorkha', 'गोरखा'), (37, 4, 'Lamjung', 'लमजुङ'), (38, 4, 'Tanahun', 'तनहुँ'), (39, 4, 'Syangja', 'स्याङ्जा'), 
(40, 4, 'Kaski', 'कास्की'), (41, 4, 'Manang', 'मनाङ'), (42, 4, 'Mustang', 'मुस्ताङ'), (43, 4, 'Myagdi', 'म्याग्दी'), 
(44, 4, 'Parbat', 'पर्वत'), (45, 4, 'Baglung', 'बागलुङ'), (46, 4, 'Nawalparasi (East)', 'नवलपरासी (बर्दघाट सुस्ता पूर्व)'),
-- Lumbini (12)
(47, 5, 'Gulmi', 'गुल्मी'), (48, 5, 'Palpa', 'पाल्पा'), (49, 5, 'Rupandehi', 'रुपन्देही'), (50, 5, 'Kapilvastu', 'कपिलवस्तु'), 
(51, 5, 'Arghakhanchi', 'अर्घाखाँची'), (52, 5, 'Pyuthan', 'प्युठान'), (53, 5, 'Rolpa', 'रोल्पा'), (54, 5, 'Eastern Rukum', 'रुक्म (पूर्व)'), 
(55, 5, 'Banke', 'बाँके'), (56, 5, 'Bardiya', 'बर्दिया'), (57, 5, 'Nawalparasi (West)', 'नवलपरासी (बर्दघाट सुस्ता पश्चिम)'), (58, 5, 'Dang', 'दाङ'),
-- Karnali (10)
(59, 6, 'Western Rukum', 'रुक्म (पश्चिम)'), (60, 6, 'Salyan', 'सल्यान'), (61, 6, 'Dolpa', 'डोल्पा'), (62, 6, 'Jumla', 'जुम्ला'), 
(63, 6, 'Mugu', 'मुगु'), (64, 6, 'Humla', 'हुम्ला'), (65, 6, 'Kalikot', 'कालिकोट'), (66, 6, 'Jajarkot', 'जाजरकोट'), 
(67, 6, 'Dailekh', 'दैलेख'), (68, 6, 'Surkhet', 'सुर्खेत'),
-- Sudurpashchim (9)
(69, 7, 'Bajura', 'बाजुरा'), (70, 7, 'Bajhang', 'बझाङ'), (71, 7, 'Achham', 'अछाम'), (72, 7, 'Doti', 'डोटी'), 
(73, 7, 'Kailali', 'कैलाली'), (74, 7, 'Kanchanpur', 'कञ्चनपुर'), (75, 7, 'Dadeldhura', 'डडेलधुरा'), (76, 7, 'Baitadi', 'बैतडी'), (77, 7, 'Darchula', 'दार्चुला');

-- 3.3 Constituencies (165)
INSERT INTO constituencies (district_id, name_en, name_ne, constituency_number) VALUES
-- Koshi
(1, 'Taplejung 1', 'ताप्लेजुङ १', 1),
(2, 'Panchthar 1', 'पाँचथर १', 1),
(3, 'Ilam 1', 'इलाम १', 1), (3, 'Ilam 2', 'इलाम २', 2),
(4, 'Jhapa 1', 'झापा १', 1), (4, 'Jhapa 2', 'झापा २', 2), (4, 'Jhapa 3', 'झापा ३', 3), (4, 'Jhapa 4', 'झापा ४', 4), (4, 'Jhapa 5', 'झापा ५', 5),
(5, 'Morang 1', 'मोरङ १', 1), (5, 'Morang 2', 'मोरङ २', 2), (5, 'Morang 3', 'मोरङ ३', 3), (5, 'Morang 4', 'मोरङ ४', 4), (5, 'Morang 5', 'मोरङ ५', 5), (5, 'Morang 6', 'मोरङ ६', 6),
(6, 'Sunsari 1', 'सुनसरी १', 1), (6, 'Sunsari 2', 'सुनसरी २', 2), (6, 'Sunsari 3', 'सुनसरी ३', 3), (6, 'Sunsari 4', 'सुनसरी ४', 4),
(7, 'Dhankuta 1', 'धनकुटा १', 1),
(8, 'Tehrathum 1', 'तेह्रथुम १', 1),
(9, 'Sankhuwasabha 1', 'संखुवासभा १', 1),
(10, 'Bhojpur 1', 'भोजपुर १', 1),
(11, 'Solukhumbu 1', 'सोलुखुम्बु १', 1),
(12, 'Okhaldhunga 1', 'ओखलढुङ्गा १', 1),
(13, 'Khotang 1', 'खोटाङ १', 1),
(14, 'Udayapur 1', 'उदयपुर १', 1), (14, 'Udayapur 2', 'उदयपुर २', 2),
-- Madhesh
(15, 'Saptari 1', 'सप्तरी १', 1), (15, 'Saptari 2', 'सप्तरी २', 2), (15, 'Saptari 3', 'सप्तरी ३', 3), (15, 'Saptari 4', 'सप्तरी ४', 4),
(16, 'Siraha 1', 'सिरहा १', 1), (16, 'Siraha 2', 'सिरहा २', 2), (16, 'Siraha 3', 'सिरहा ३', 3), (16, 'Siraha 4', 'सिरहा ४', 4),
(17, 'Dhanusha 1', 'धनुषा १', 1), (17, 'Dhanusha 2', 'धनुषा २', 2), (17, 'Dhanusha 3', 'धनुषा ३', 3), (17, 'Dhanusha 4', 'धनुषा ४', 4),
(18, 'Mahottari 1', 'महोत्तरी १', 1), (18, 'Mahottari 2', 'महोत्तरी २', 2), (18, 'Mahottari 3', 'महोत्तरी ३', 3), (18, 'Mahottari 4', 'महोत्तरी ४', 4),
(19, 'Sarlahi 1', 'सर्लाही १', 1), (19, 'Sarlahi 2', 'सर्लाही २', 2), (19, 'Sarlahi 3', 'सर्लाही ३', 3), (19, 'Sarlahi 4', 'सर्लाही ४', 4),
(20, 'Rautahat 1', 'रौतहट १', 1), (20, 'Rautahat 2', 'रौतहट २', 2), (20, 'Rautahat 3', 'रौतहट ३', 3), (20, 'Rautahat 4', 'रौतहट ४', 4),
(21, 'Bara 1', 'बारा १', 1), (21, 'Bara 2', 'बारा २', 2), (21, 'Bara 3', 'बारा ३', 3), (21, 'Bara 4', 'बारा ४', 4),
(22, 'Parsa 1', 'पर्सा १', 1), (22, 'Parsa 2', 'पर्सा २', 2), (22, 'Parsa 3', 'पर्सा ३', 3), (22, 'Parsa 4', 'पर्सा ४', 4),
-- Bagmati
(23, 'Dolakha 1', 'दोलखा १', 1),
(24, 'Ramechhap 1', 'रामेछाप १', 1),
(25, 'Sindhuli 1', 'सिन्धुली १', 1), (25, 'Sindhuli 2', 'सिन्धुली २', 2),
(26, 'Kavrepalanchok 1', 'काभ्रेपलाञ्चोक १', 1), (26, 'Kavrepalanchok 2', 'काभ्रेपलाञ्चोक २', 2),
(27, 'Sindhupalchok 1', 'सिन्धुपाल्चोक १', 1), (27, 'Sindhupalchok 2', 'सिन्धुपाल्चोक २', 2),
(28, 'Rasuwa 1', 'रसुवा १', 1),
(29, 'Nuwakot 1', 'नुवाकोट १', 1), (29, 'Nuwakot 2', 'नुवाकोट २', 2),
(30, 'Dhading 1', 'धादिङ १', 1), (30, 'Dhading 2', 'धादिङ २', 2),
(31, 'Chitwan 1', 'चितवन १', 1), (31, 'Chitwan 2', 'चितवन २', 2), (31, 'Chitwan 3', 'चितवन ३', 3),
(32, 'Makwanpur 1', 'मकवानपुर १', 1), (32, 'Makwanpur 2', 'मकवानपुर २', 2),
(33, 'Bhaktapur 1', 'भक्तपुर १', 1), (33, 'Bhaktapur 2', 'भक्तपुर २', 2),
(34, 'Lalitpur 1', 'ललितपुर १', 1), (34, 'Lalitpur 2', 'ललितपुर २', 2), (34, 'Lalitpur 3', 'ललितपुर ३', 3),
(35, 'Kathmandu 1', 'काठमाडौँ १', 1), (35, 'Kathmandu 2', 'काठमाडौँ २', 2), (35, 'Kathmandu 3', 'काठमाडौँ ३', 3), (35, 'Kathmandu 4', 'काठमाडौँ ४', 4), (35, 'Kathmandu 5', 'काठमाडौँ ५', 5),
(35, 'Kathmandu 6', 'काठमाडौँ ६', 6), (35, 'Kathmandu 7', 'काठमाडौँ ७', 7), (35, 'Kathmandu 8', 'काठमाडौँ ८', 8), (35, 'Kathmandu 9', 'काठमाडौँ ९', 9), (35, 'Kathmandu 10', 'काठमाडौँ १०', 10),
-- Gandaki (Partial list for brevity, continuing from known districts...)
(36, 'Gorkha 1', 'गोरखा १', 1), (36, 'Gorkha 2', 'गोरखा २', 2),
(37, 'Lamjung 1', 'लमजुङ १', 1),
(38, 'Tanahun 1', 'तनहुँ १', 1), (38, 'Tanahun 2', 'तनहुँ २', 2),
(39, 'Syangja 1', 'स्याङ्जा १', 1), (39, 'Syangja 2', 'स्याङ्जा २', 2),
(40, 'Kaski 1', 'कास्की १', 1), (40, 'Kaski 2', 'कास्की २', 2), (40, 'Kaski 3', 'कास्की ३', 3),
(41, 'Manang 1', 'मनाङ १', 1),
(42, 'Mustang 1', 'मुस्ताङ १', 1),
(43, 'Myagdi 1', 'म्याग्दी १', 1),
(44, 'Parbat 1', 'पर्वत १', 1),
(45, 'Baglung 1', 'बागलुङ १', 1), (45, 'Baglung 2', 'बागलुङ २', 2),
(46, 'Nawalparasi East 1', 'नवलपरासी (बर्दघाट सुस्ता पूर्व) १', 1), (46, 'Nawalparasi East 2', 'नवलपरासी (बर्दघाट सुस्ता पूर्व) २', 2),
-- Lumbini
(47, 'Gulmi 1', 'गुल्मी १', 1), (47, 'Gulmi 2', 'गुल्मी २', 2),
(48, 'Palpa 1', 'पाल्पा १', 1), (48, 'Palpa 2', 'पाल्पा २', 2),
(49, 'Rupandehi 1', 'रुपन्देही १', 1), (49, 'Rupandehi 2', 'रुपन्देही २', 2), (49, 'Rupandehi 3', 'रुपन्देही ३', 3), (49, 'Rupandehi 4', 'रुपन्देही ४', 4), (49, 'Rupandehi 5', 'रुपन्देही ५', 5),
(50, 'Kapilvastu 1', 'कपिलवस्तु १', 1), (50, 'Kapilvastu 2', 'कपिलवस्तु २', 2), (50, 'Kapilvastu 3', 'कपिलवस्तु ३', 3),
(51, 'Arghakhanchi 1', 'अर्घाखाँची १', 1),
(52, 'Pyuthan 1', 'प्युठान १', 1),
(53, 'Rolpa 1', 'रोल्पा १', 1),
(54, 'Eastern Rukum 1', 'रुक्म (पूर्व) १', 1),
(55, 'Banke 1', 'बाँके १', 1), (55, 'Banke 2', 'बाँके २', 2), (55, 'Banke 3', 'बाँके ३', 3),
(56, 'Bardiya 1', 'बर्दिया १', 1), (56, 'Bardiya 2', 'बर्दिया २', 2),
(57, 'Nawalparasi West 1', 'नवलपरासी (बर्दघाट सुस्ता पश्चिम) १', 1), (57, 'Nawalparasi West 2', 'नवलपरासी (बर्दघाट सुस्ता पश्चिम) २', 2),
(58, 'Dang 1', 'दाङ १', 1), (58, 'Dang 2', 'दाङ २', 2), (58, 'Dang 3', 'दाङ ३', 3),
-- Karnali
(59, 'Western Rukum 1', 'रुक्म (पश्चिम) १', 1),
(60, 'Salyan 1', 'सल्यान १', 1),
(61, 'Dolpa 1', 'डोल्पा १', 1),
(62, 'Jumla 1', 'जुम्ला १', 1),
(63, 'Mugu 1', 'मुगु १', 1),
(64, 'Humla 1', 'हुम्ला १', 1),
(65, 'Kalikot 1', 'कालिकोट १', 1),
(66, 'Jajarkot 1', 'जाजरकोट १', 1),
(67, 'Dailekh 1', 'दैलेख १', 1), (67, 'Dailekh 2', 'दैलेख २', 2),
(68, 'Surkhet 1', 'सुर्खेत १', 1), (68, 'Surkhet 2', 'सुर्खेत २', 2),
-- Sudurpashchim
(69, 'Bajura 1', 'बाजुरा १', 1),
(70, 'Bajhang 1', 'बझाङ १', 1),
(71, 'Achham 1', 'अछाम १', 1), (71, 'Achham 2', 'अछाम २', 2),
(72, 'Doti 1', 'डोटी १', 1),
(73, 'Kailali 1', 'कैलाली १', 1), (73, 'Kailali 2', 'कैलाली २', 2), (73, 'Kailali 3', 'कैलाली ३', 3), (73, 'Kailali 4', 'कैलाली ४', 4), (73, 'Kailali 5', 'कैलाली ५', 5),
(74, 'Kanchanpur 1', 'कञ्चनपुर १', 1), (74, 'Kanchanpur 2', 'कञ्चनपुर २', 2), (74, 'Kanchanpur 3', 'कञ्चनपुर ३', 3),
(75, 'Dadeldhura 1', 'डडेलधुरा १', 1),
(76, 'Baitadi 1', 'बैतडी १', 1),
(77, 'Darchula 1', 'दार्चुला १', 1);

-- 3.4 Political Parties
INSERT INTO political_parties (name_en, name_ne, short_name_en, short_name_ne, logo_path, status) VALUES
('Independent', 'स्वतन्त्र', 'IND', 'स्वतन्त्र', 'assets/images/parties/independent.png', 'active'),
('Nepali Congress', 'नेपाली कांग्रेस', 'NC', 'नेका', 'assets/images/parties/tree.png', 'active'),
('CPN (Unified Marxist–Leninist)', 'नेकपा (एमाले)', 'CPN-UML', 'एमाले', 'assets/images/parties/sun.png', 'active'),
('CPN (Maoist Centre)', 'नेकपा (माओवादी केन्द्र)', 'CPN-MC', 'माओवादी', 'assets/images/parties/hammer_sickle.png', 'active'),
('Rastriya Swatantra Party', 'राष्ट्रिय स्वतन्त्र पार्टी', 'RSP', 'रास्वपा', 'assets/images/parties/bell.png', 'active'),
('Rastriya Prajatantra Party', 'राष्ट्रिय प्रजातन्त्र पार्टी', 'RPP', 'राप्रपा', 'assets/images/parties/plough.png', 'active'),
('People''s Socialist Party, Nepal', 'जनता समाजवादी पार्टी, नेपाल', 'JSPN', 'जसपा', 'assets/images/parties/umbrella.png', 'active'),
('Janamat Party', 'जनमत पार्टी', 'JP', 'जनमत', 'assets/images/parties/loudspeaker.png', 'active'),
('CPN (Unified Socialist)', 'नेकपा (एकीकृत समाजवादी)', 'CPN-US', 'एस', 'assets/images/parties/pen.png', 'active'),
('Nagarik Unmukti Party', 'नागरिक उन्मुक्ति पार्टी', 'NUP', 'नाउपा', 'assets/images/parties/chair.png', 'active'),
('Loktantrik Samajwadi Party', 'लोकतान्त्रिक समाजवादी पार्टी', 'LSP', 'लोसपा', 'assets/images/parties/bicycle.png', 'active'),
('Nepal Workers Peasants Party', 'नेपाल मजदुर किसान पार्टी', 'NWPP', 'नेमकिपा', 'assets/images/parties/madal.png', 'active'),
('Rastriya Janamorcha', 'राष्ट्रिय जनमोर्चा', 'RJM', 'राजमो', 'assets/images/parties/glass.png', 'active');

SET FOREIGN_KEY_CHECKS=1;
