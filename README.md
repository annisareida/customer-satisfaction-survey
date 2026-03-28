# 📊 Customer Satisfaction Survey System - BPKARSS
> **A Digital Transformation Project for Light Rail Transit (LRT) South Sumatra.**

This repository contains a web-based application developed to modernize the feedback collection process at **Balai Pengelola Kereta Api Ringan Sumatera Selatan (BPKARSS)**. The system transitions paper-based surveys into a digital format for real-time data analysis and service improvement.

---

## 📖 Project Overview

During my internship as an Informatics Engineering student, I developed this system to bridge the gap between passenger feedback and management response. The application provides an intuitive interface for passengers and a robust management system for administrators.

### 🎯 Core Objectives
* **Digitization:** Eliminating paper waste and manual data entry.
* **Content Management:** Centralized control for news (`berita`), gallery, and FAQs.
* **Data Integrity:** Ensuring survey responses are stored securely in a relational database.

---

## 🛠️ Technical Stack & Tools

| Component | Technology |
| :--- | :--- |
| **Backend** | PHP Native (Procedural) |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Database** | MySQL |
| **Design-to-Code** | Anima Tool |
| **Editor** | Visual Studio Code |

---

## 📂 System Architecture & Directory Structure

To ensure the project is scalable and easy to maintain (following **Clean Code** principles), the files are organized into a modular directory structure:

```text
WEB-SURVEY-BPKARSS/
│
├── actions/            # Backend Logic: Processing CRUD for News, Gallery, and Surveys
├── assets/             # Static Assets: CSS, JS, and user-uploaded media
│   ├── css/            # Modular stylesheets for each view
│   ├── uploads/        # Directory for stored image assets
│   └── js/             # Client-side scripts
├── includes/           # Core: Database connection (config.php)
├── views/              # UI Components: Templates, Login, and Dashboard layouts
├── index.html           # Application Entry Point
└── README.md           # Technical Documentation
```

---

## 🚀 Key Features

### 1. Passenger Survey Interface

* Structured survey forms for service evaluation.
* Interactive feedback loops for LRT passenger satisfaction.

### 2. Comprehensive Admin CMS (CRUD)

The administrator can manage all site content through a secure dashboard:

* **Berita:** Management of latest updates and news.
* **Galeri:** Image management for BPKARSS activities.
* **Pertanyaan:** Dynamic FAQ and survey question management.

### 3. Responsive Design

* Optimized for mobile and desktop viewing, ensuring passengers can fill out surveys easily at the station.

---

## 🔧 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/annisareida/customer-satisfaction-survey.git
```

### 2. Database Configuration

- Create a database named `db_bpkarss`
- Import the `database.sql` file
- Update `includes/config.php` with your local credentials:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_bpkarss";
```

### 3. Run Locally

- Move the folder to `htdocs` (XAMPP)
- Access via:

```
http://localhost/customer-satisfaction-survey
```

---

## ✍️ Technical Documentation Insights

From a **Technical Writer** perspective, this project emphasizes:

* **Modularity:** Separating logic (`actions/`) from presentation (`views/`) to simplify debugging.
* **Descriptive Naming:** Clear file naming conventions for better collaboration.
* **User-Centric Design:** Simplifying the survey flow for non-technical end-users.

---

## 👤 Author

**Annisa Reida Raheima**

* Informatics Engineering - Sriwijaya University  
* [LinkedIn](https://linkedin.com/in/annisareida)  
* Email: annisaheyy@gmail.com  

---

*Developed as part of a professional initiative to support data-driven decision-making at BPKARSS.*

---
