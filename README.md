# 🇳🇵 VotePort - Online Voting System

Professional, secure, and multilingual digital voting platform inspired by the **Election Commission of Nepal**.

![VotePort Banner](https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?q=80&w=1200&auto=format&fit=crop)

## 🌟 Key Features

- **Official ECN Aesthetic**: Design language inspired by the official Election Commission of Nepal.
- **Bilingual Support**: Fully localized in **English** and **नेपाली**.
- **Multi-Role Authentication**: Specific portals for **Voters**, **Candidates**, and **Administrators**.
- **Dynamic Dashboards**: Role-specific themes and functionalities.
- **Modern Security**: Biometric placeholders, voter verification flows, and official government-style branding.
- **Landing Page Hub**: Includes Notice Boards, Press Releases, Voter Education, and Legal Document sections.

## 🛠️ Technology Stack

- **Backend**: PHP 8.x
- **Database**: MySQL (MariaDB)
- **Frontend**: Bootstrap 5, Vanilla CSS, FontAwesome 6
- **Scripts**: Vanilla JavaScript (ES6)
- **Localization**: PHP Array-based dynamic translation system

## 📂 Project Structure

```text
online_voting_system/
├── actions/            # PHP Processors (Login, Register, etc.)
├── assets/             # CSS, JS, and Media files
│   ├── css/
│   ├── js/
│   └── images/
├── database/           # SQL Schema files
├── includes/           # Config and helper functions
├── languages/          # EN/NE translation files
├── index.php           # Landing Page
├── login.php           # Login Portal
├── signup.php          # Registration Page
└── README.md           # Project Documentation
```

## 🚀 Installation Guide

1. **Prerequisites**:
   - Install [XAMPP](https://www.apachefriends.org/index.html) or any PHP/MySQL server.
   
2. **Database Setup**:
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `online_voting_system`.
   - Import the `database/schema.sql` file.

3. **Project Deployment**:
   - Copy the project folder to `C:\xampp\htdocs\online_voting_system`.
   - Start **Apache** and **MySQL** in XAMPP Control Panel.

4. **Access the Application**:
   - Open your browser and go to `http://localhost/online_voting_system/`.

## 🔑 Default Credentials

- **Admin Invitation Code**: `ADMIN123` (Set in `settings` table)

## 📜 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📅 Today's Accomplishments (Feb 1, 2026)

Today we transformed the project into a professional, government-grade voting portal!

### 🎨 UI & Design (ECN Style)
- **Official ECN Look**: Redesigned the entire landing page to match the **Election Commission of Nepal** aesthetic.
- **Brand Identity**: Created the **VOTEPORT** logo and branding (Fingerprint + Ballot mark).
- **Official Colors**: Integrated the exact National Crimson Red (#C8102E) and Navy Blue (#003893).
- **Gov Header**: Added the official Government Top Bar with the National Emblem and current date.
- **Interactive Ticker**: Added a scrolling "Latest Notice" ticker for breaking election news.

### 🚀 Key Features
- **Notice Board**: Fully functional UI for listing official notices and dates.
- **Press & Media**: Added a dedicated Press Release section and a Multimedia Awareness gallery.
- **Voter Services**: Implemented a 4-column service grid (Voter Search, Results, Downloads, Online Reg).
- **Legal Hub**: Added an "Acts & Regulations" section for official legal document downloads.
- **Refined Footer**: Detailed contact section with the official ECN address and 24/7 support info.

### 🔑 Authentication Refinements
- **Unified Auth Design**: Both **Login** and **Signup** now use a matching, premium split-panel layout.
- **Role-Based Themes**: Dynamic background and accent changes for **Voter** (Navy), **Candidate** (Indigo), and **Admin** (Crimson).
- **Back to Home**: Integrated a seamless "Back to Home" button for better navigation flow.
- **Security Check**: Restricted Admin registration to specific invitation codes only.
- **Cleanup**: Removed the photo upload option to streamline the registration process.

### 🌐 Localization
- **100% Bilingual**: Every new feature, button, and section is fully translated into **English** and **नेपाली**.

## 👤 Developed By

- **Sijan KC**

---
**Developed for Digital Nepal 🇳🇵**
