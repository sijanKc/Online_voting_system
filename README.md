# 🇳🇵 VOTEPORT - Automated Online Voting System

**VOTEPORT** is a professional, secure, and fully bilingual (English/Nepali) digital voting ecosystem inspired by the **Election Commission of Nepal (ECN)**. It is designed to modernize the electoral process through geographic security, role-based workflows, and a premium government aesthetic.

![VotePort Banner](https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?q=80&w=1200&auto=format&fit=crop)

---

## 🌟 Key Features

### 🏛️ Official Government Aesthetic
- **ECN Design Language**: Built using National Crimson Red (`#C8102E`) and Navy Blue (`#003893`) palettes.
- **Official Top Bar**: Persistent government header with the National Emblem, real-time date, and language toggle.
- **Interactive News Ticker**: Scrolling "Latest Notice" bar for breaking electoral updates.

### 🌐 Comprehensive Localization
- **Bilingual Interface**: Seamless toggling between **English** and **नेपाली** across all portals.
- **Dynamic Content Localization**: Geographic entities (Provinces, Districts, Constituencies) and Political Parties automatically update based on the selected language.

### 🛡️ Geographic Lockdown & Security
- **Smart Geofencing**: Voters are automatically restricted to see and vote only in elections relevant to their registered Province and Constituency.
- **Nomination Workflow**: A secure "Apply -> Review -> Approve" chain ensuring only verified candidates appear on the ballot.
- **Encryption**: Placeholder end-to-end encryption and biometric authentication flows.

### 👥 Specialized Portals
- **Voter Portal**: Secure ballot casting, profile management, and result tracking.
- **Candidate Portal**: Campaign dashboard, manifesto management, and real-time nomination tracking.
- **Admin Command Center**: User approval system, election creation (Provincial/Parliamentary), and live participation analytics.

---

## 🛠️ Technology Stack

- **Backend**: PHP 8.x (PDO for Secure MySQL Interaction)
- **Database**: MySQL (MariaDB)
- **Frontend**: Bootstrap 5, Vanilla CSS3, FontAwesome 6
- **Architecture**: Procedural PHP with a centralized Translation Engine

---

## 📂 Project Structure

```text
online_voting_system/
├── actions/            # PHP Logic (Login, Signup, Vote Casting)
├── assets/             # Global Assets (CSS, JS, Logos)
├── database/           # SQL Schemas & Geographic Data
├── includes/           # Core Config, Session handling & DB Connection
├── languages/          # Bilingual JSON/PHP translation files (EN/NE)
├── admin/              # Election Commission Management Portal
├── voter/              # Citizen Voting Portal
├── candidate/          # Candidate Campaign Portal
└── index.php           # Official Landing Page Hub
```

---

## 🚀 Installation Guide

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/sijanKc/Online_voting_system.git
   ```

2. **Server Setup**:
   - Ensure you are using **XAMPP**, **WAMP**, or any LAMP stack.
   - Move the project to your `htdocs` or `www` directory.

3. **Database Setup**:
   - Create a database named `online_voting_system`.
   - Import `database/fresh_setup_nepal_election.sql` to populate provinces, districts, and the core schema.

4. **Configuration**:
   - Update `includes/config.php` with your local database credentials if they differ from the default.

5. **Run**:
   - Navigate to `http://localhost/online_voting_system/`.

---

## 🔑 Access Information

- **Voter Registration**: Requires a valid (simulated) 16-digit Citizenship Number.
- **Admin Access**: Requires a secure **Invitation Code** (Default: `ADMIN123`).
- **Nomination Flow**: Candidates must be approved by an Admin before appearing on any Ballot.

---

## 👤 Developed By

**Sijan KC**  
*Dedicated to the Digitalization of Nepal's Democracy.*

---
**VOTEPORT 🇳🇵 | 2026 Registration Division**
