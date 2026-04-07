#  Student Event Registration System (PHP + MySQL)

##  Project Overview

This project is a **web-based student event registration system** built using **PHP, MySQL, HTML, CSS, and JavaScript**.

It allows students to:

* Register for events
* View their registration details

It also includes an **Admin Dashboard** to:

* Monitor registrations in real-time
* Analyze trends and event popularity

---

##  Project Architecture

```
Client (Browser)
   │
   ▼
Frontend (HTML, CSS, JS)
   │
   ▼
PHP Backend (Business Logic)
   │
   ▼
MySQL Database (XAMPP)
```

###  Flow:

1. User fills form → `form.php`
2. Data sent to → `connect.php`
3. Stored in → MySQL Database
4. View data → `view.php`
5. Admin analytics → `admin.php`
6. Live updates → `admin_data.php`

---

##  Project Structure

```
registration-form/
│
├── form.php          # Registration form UI
├── connect.php       # Handles form submission & DB logic
├── view.php          # View registration details
├── admin.php         # Admin dashboard (charts + stats)
├── admin_data.php    # API for live updates (JSON)
├── README.md         # Project documentation
```

---

##  Technologies Used

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL
* **Server:** XAMPP
* **Charts:** Chart.js (CDN)

---

##  Features

###  Student Side

* Event registration form
* Input validation (name, email, event)
* Success popup
* View registration by ID / name

###  Admin Dashboard

* Total registrations
* Today's registrations
* Event-wise statistics
* Recent registrations table
* Live updates (every 30 seconds)
* Charts:

  *  Event popularity (Doughnut chart)
  *  Registration trends (Line chart)

---

##  Database Details

### Database Name:

```
registration_form_db
```

### Table: `registrations`

```sql
CREATE TABLE registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    event VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

 The table is **automatically created** by `connect.php` 

---

##  How to Run the Project

###  Step 1: Install Requirements

* Install **XAMPP**
* Start:

  * Apache 
  * MySQL 

---

### 🔹 Step 2: Move Project

Copy your project folder into:

```
C:\xampp\htdocs\
```

---

###  Step 3: Create Database

1. Open **phpMyAdmin**
2. Create database:

```
registration_form_db
```

---

###  Step 4: Run Project

Open browser:

###  Registration Form:

```
http://localhost/your-folder-name/form.php
```

###  Admin Dashboard:

```
http://localhost/your-folder-name/admin.php
```

### View Registration:

```
http://localhost/your-folder-name/view.php
```

---

##  API Endpoint (Live Data)

`admin_data.php` provides real-time data in JSON format 

Used by:

* Admin dashboard for live updates

---

##  Admin Dashboard Details

File: `admin.php` 

### Features:

* Live statistics
* Event analysis
* Charts (Chart.js)
* Auto-refresh every 30 seconds

---

##  Important Notes

* Default DB credentials:

  ```
  username: root
  password: (empty)
  ```
* Make sure MySQL is running
* Internet required for Chart.js CDN

---

##  Future Improvements

* Login system (Admin authentication)
* Email confirmation after registration
* Cloud deployment (Firebase / GCP)
* Better UI with frameworks (React / Bootstrap)
* Export data to CSV

---

##  Author

Developed as part of a **cloud-based registration system project**.

---

##  If you like this project

Give it a star on GitHub 
