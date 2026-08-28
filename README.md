# QUEST Complaint Portal

> A web-based complaint management system for students and administrators at Quaid-e-Awam University of Engineering, Science & Technology (QUEST), Nawabshah.

---

## 📌 Overview

The **QUEST Complaint Portal** is a PHP/MySQL application that provides a structured, transparent channel for students to submit complaints about campus facilities and university services — and for administrators to review, respond to, and resolve them. Built as an academic project at QUEST, it addresses the gap between student concerns and institutional response by digitizing the entire complaint lifecycle.

---

## ✨ Features

### 👨‍🎓 Student Side
- **Secure Registration & Login** — Students register with their university email and roll number
- **Submit Complaints** — Choose from predefined categories and describe the issue in detail
- **Track Status** — Real-time visibility into complaint status (Pending → In Progress → Resolved)
- **View History** — Full complaint history with timestamps and admin responses
- **Complaint Details** — Drill down into individual complaints to see admin feedback

### 🛠️ Admin Side
- **Admin Dashboard** — Overview of all complaints across the university
- **Manage Complaints** — View, filter, and prioritize incoming complaints
- **Respond & Update** — Add responses and update complaint status
- **Department-wise Access** — Admins are scoped to their departments

---

## 🗂️ Complaint Categories

| Category | Description |
|---|---|
| Classroom Issues | Seating, lighting, and academic facility problems |
| Laboratory Issues | Equipment, computers, and technical facility faults |
| Internet & Wi-Fi | Connectivity and campus network problems |
| Electricity Problems | Power outages, electrical faults, and maintenance |
| Water Supply | Water availability and supply interruptions |
| Cleanliness & Maintenance | Waste management and campus upkeep |
| Security Concerns | Campus safety and security issues |
| Other Issues | Anything that doesn't fit the above categories |

---

## 🔄 How It Works

```
Student Logs In → Submits Complaint → Admin Reviews → Status Updated → Issue Resolved
```

1. **Login** — Student signs in with university credentials
2. **Submit** — Selects a category, writes a title and description, submits
3. **Review** — Admin receives and reviews the complaint
4. **Response** — Admin adds a response and updates the status
5. **Resolution** — Student tracks progress until marked Resolved

---

## 🗃️ Database Schema

### Setup

```sql
CREATE DATABASE quest_complaint_portal;
USE quest_complaint_portal;
```

---

### Table: `students`

Stores registered student accounts.

```sql
CREATE TABLE students (
    student_id    INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(100)  NOT NULL,
    university_email VARCHAR(100) NOT NULL UNIQUE,
    password      VARCHAR(255)  NOT NULL,
    department    VARCHAR(100)  NOT NULL,
    roll_number   VARCHAR(30)   NOT NULL UNIQUE,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);
```

---

### Table: `admins`

Stores administrator accounts.

```sql
CREATE TABLE admins (
    admin_id      INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(100)  NOT NULL,
    university_email VARCHAR(100) NOT NULL UNIQUE,
    password      VARCHAR(255)  NOT NULL,
    department    VARCHAR(100)  NOT NULL,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);
```

---

### Table: `complaints`

Stores all student-submitted complaints and their resolution state.

```sql
CREATE TABLE complaints (
    complaint_id          INT AUTO_INCREMENT PRIMARY KEY,
    student_id            INT           NOT NULL,
    category              VARCHAR(100)  NOT NULL,
    complaint_title       VARCHAR(200)  NOT NULL,
    complaint_description TEXT          NOT NULL,
    status                ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
    admin_response        TEXT,
    submitted_at          TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE
);
```

---

## 📁 Project Structure

```
QUEST-Complaint-Portal/
│
├── assets/                     # Static assets (images, CSS, JS)
├── config/                     # Database connection configuration
├── includes/                   # Reusable PHP partials (header, navbar, footer)
│
├── index.php                   # Landing / home page
├── login.php                   # Login page (students & admins)
├── signup.php                  # Student registration page
├── logout.php                  # Session termination
│
├── authenticate.php            # Login authentication handler
├── authenticate_signup.php     # Registration form handler
│
├── student_dashboard.php       # Student home after login
├── submit_complaint.php        # Complaint submission form
├── complaint_details.php       # View a single complaint (student)
├── complaint_history.php       # All complaints submitted by student
├── manage_complaints.php       # Student complaint management
│
├── admin_dashboard.php         # Admin home after login
├── admin_complaints.php        # Admin view of all complaints
├── admin_complaint_details.php # Admin view of a single complaint
├── update_complaint.php        # Admin status update handler
│
├── about.php                   # About the portal
└── contact.php                 # Contact page
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP (procedural) |
| Database | MySQL |
| Server | Apache (via XAMPP / WAMP) |

---

## 🚀 Local Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) or any Apache + PHP + MySQL stack
- PHP >= 7.4
- MySQL >= 5.7

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Haziq8900/QUEST-Complaint-Portal.git
   ```

2. **Move to your server's web root**
   ```bash
   # For XAMPP on Windows:
   mv QUEST-Complaint-Portal C:/xampp/htdocs/
   ```

3. **Start Apache and MySQL** from the XAMPP Control Panel

4. **Create the database**
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Run the SQL from the [Database Schema](#️-database-schema) section above
   - Or import the provided `.sql` file if included

5. **Configure the database connection**
   - Open `config/` and update your DB credentials:
     ```php
     $host     = 'localhost';
     $dbname   = 'quest_complaint_portal';
     $username = 'root';
     $password = '';
     ```

6. **Access the portal**
   ```
   http://localhost/QUEST-Complaint-Portal/
   ```

---

## 🔐 Security Notes

- Sessions are used for authentication state management
- Student records cascade-delete their complaints on account removal
- University emails are enforced as unique identifiers

---

## 👤 Author

**Haziq Khan**
Software Engineering Student — 2nd Year
Quaid-e-Awam University of Engineering, Science & Technology (QUEST), Nawabshah

- 📧 [haziqkhan8900@gmail.com](mailto:haziqkhan8900@gmail.com)
- 🐙 [github.com/Haziq8900](https://github.com/Haziq8900)

---

## 📄 License

This project was developed as an academic project at QUEST. Feel free to reference or build upon it with proper attribution.