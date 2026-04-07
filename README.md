# Student Registration System - Admin Dashboard

## Overview
This system allows students to register for events and provides an admin dashboard for monitoring participation and analyzing trends.

## Files
- `form.php` - Student registration form
- `connect.php` - Handles form submission and database operations
- `view.php` - View individual registrations
- `admin.php` - Admin dashboard with live monitoring and trend analysis
- `admin_data.php` - API endpoint for live data updates

## Admin Dashboard Features

### Live Monitoring
- Real-time display of total registrations
- Today's registration count
- Recent registrations table (updates every 30 seconds)
- Active events count

### Trend Analysis
- Daily registration trends (last 7 days) - Line chart
- Event popularity distribution - Doughnut chart
- Event statistics table with percentages

### Live Updates
The dashboard automatically refreshes data every 30 seconds to show live participation.

## Setup Requirements
1. XAMPP or similar PHP/MySQL server
2. MySQL database: `registration_form_db`
3. Table: `registrations` (auto-created by connect.php)

## Database Schema
```sql
CREATE TABLE registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    event VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Usage
1. Start XAMPP (Apache and MySQL)
2. Place all files in your web server root (e.g., htdocs folder)
3. Access the admin dashboard at: `http://localhost/admin.php`
4. Student registration: `http://localhost/form.php`
5. View registrations: `http://localhost/view.php?id=X` or `?email=X` or `?name=X`

## Charts
- Uses Chart.js library (loaded from CDN)
- Responsive design for mobile devices
- Automatic updates without page refresh