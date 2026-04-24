# 🎓 Training & Placement Cell Portal - SVNIT

<div align="center">

![SVNIT Logo](Svnit_logo.png)

**A comprehensive, secure, and production-ready web-based portal for managing Training and Placement activities at Sardar Vallabhbhai National Institute of Technology (SVNIT), Surat.**

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Security](https://img.shields.io/badge/Security-Hardened-red.svg)](#security-features)

</div>

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Security Features](#security-features)
- [Installation & Setup](#installation--setup)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [Deployment](#deployment)
- [API Documentation](#api-documentation)
- [Contributing](#contributing)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## 🎯 Overview

The Training & Placement Cell Portal is a full-featured web application designed to streamline the recruitment process for SVNIT. It provides separate interfaces for **Students**, **Companies**, and **Administrators**, enabling efficient management of internships, placements, and related activities.

### Key Highlights

- ✅ **Production-Ready**: Security-hardened with SQL injection protection, CSRF tokens, and secure session management
- ✅ **Mobile Responsive**: Fully responsive design that works seamlessly on all devices
- ✅ **User-Friendly**: Modern, intuitive UI with excellent UX
- ✅ **Comprehensive**: Complete feature set covering all aspects of placement management
- ✅ **Scalable**: Well-structured codebase with proper separation of concerns

---

## ✨ Features

### 👨‍🎓 For Students

#### Internship Portal
- **Registration & Profile Management**
  - Complete student profile with academic details
  - Profile image upload (PNG, JPG, JPEG - Max 512KB)
  - Update personal and academic information
  - View profile with formatted display

- **Company Applications**
  - Browse available internship opportunities
  - Filter companies by branch and eligibility criteria
  - Apply for internships with automatic eligibility checking
  - Track application status
  - View company details and requirements

- **Notifications**
  - General internship notifications
  - Company-specific notifications
  - Real-time updates on new opportunities

#### Placement Portal
- **Job Applications**
  - Browse placement opportunities
  - Apply for positions matching eligibility
  - Track placement status
  - View salary packages and job profiles

- **Profile Management**
  - Separate placement profile
  - Update placement-specific information
  - View application history

### 🏢 For Companies

- **Registration & Authentication**
  - Company registration with validation
  - Secure login system
  - Profile management

- **Recruitment Management**
  - Post internship opportunities
  - Post placement opportunities
  - Set eligibility criteria (CGPA, backlogs, branch)
  - Define job profiles and locations
  - Set salary/stipend ranges

- **Student Database Access**
  - View eligible students
  - Filter by branch and criteria
  - Access student profiles
  - Manage applications

- **Company Details Management**
  - Add multiple branches
  - Set different criteria per branch
  - Update company information

### 👨‍💼 For Administrators

- **User Management**
  - Approve/reject company registrations
  - Manage student registrations
  - Create new admin accounts
  - View all user accounts

- **Notification System**
  - Create general notifications
  - Send branch-specific notifications
  - Manage notification visibility
  - Bulk notification features

- **Application Tracking**
  - Monitor all student applications
  - Track company visits
  - View application statistics
  - Manage student status (Placed/Not Placed)

- **Reports & Analytics**
  - Student attendance tracking
  - Application statistics
  - Company visit schedules
  - Placement reports

---

## 🛠 Technology Stack

### Frontend
- **HTML5**: Semantic markup
- **CSS3**: Modern styling with gradients, animations, and responsive design
- **JavaScript**: Form validation and interactive features
- **Google Fonts**: Inter font family for typography

### Backend
- **PHP 7.4+**: Server-side scripting
- **MySQL 5.7+**: Relational database management
- **PHPMailer**: Email functionality via SMTP

### Server
- **Apache**: Web server (via XAMPP/WAMP)
- **XAMPP/WAMP**: Development environment

### Security Libraries
- **password_hash/password_verify**: Secure password hashing
- **Prepared Statements**: SQL injection prevention
- **CSRF Protection**: Cross-site request forgery prevention
- **Session Security**: Secure session management

---

## 🔒 Security Features

This project implements industry-standard security practices:

### ✅ Implemented Security Measures

1. **SQL Injection Protection**
   - All database queries use prepared statements
   - Parameter binding for all user inputs
   - No direct string concatenation in SQL queries

2. **CSRF Protection**
   - CSRF tokens on all forms
   - Token validation on form submission
   - Secure token generation using `random_bytes()`

3. **Password Security**
   - Passwords hashed using `password_hash()` with `PASSWORD_DEFAULT`
   - Password verification using `password_verify()`
   - No plain text password storage

4. **Session Security**
   - Secure session configuration
   - Session regeneration on login
   - HttpOnly cookies
   - SameSite cookie attribute
   - Session timeout (30 minutes)

5. **Input Validation & Sanitization**
   - Server-side validation for all inputs
   - Email validation using `filter_var()`
   - URL validation
   - File upload validation (MIME type, size)
   - XSS protection with `htmlspecialchars()`

6. **File Upload Security**
   - File type validation (MIME type checking)
   - File size limits (512KB for images)
   - Secure file handling

7. **Error Handling**
   - Secure error messages (no sensitive data exposure)
   - Error logging for debugging
   - User-friendly error displays

### Security Files

- `csrf.php`: CSRF token generation and validation
- `security.php`: Additional security helper functions
- `server.php`: Enhanced session security configuration
- `config.php`: Centralized configuration with environment variable support

---

## 📦 Installation & Setup

### Prerequisites

- **XAMPP** (Windows) or **WAMP** (Windows) or **LAMP** (Linux) or **MAMP** (Mac)
- **PHP 7.4** or higher
- **MySQL 5.7** or higher
- **Web Browser** (Chrome, Firefox, Edge, Safari)

### Step-by-Step Installation

#### 1. Install XAMPP

Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)

#### 2. Clone/Download the Repository

```bash
# Using Git
git clone https://github.com/yourusername/tnp-portal.git

# Or download as ZIP and extract
```

#### 3. Copy Files to Web Server Directory

**Windows (XAMPP):**
```
Copy all files to: C:\xampp\htdocs\TnP
```

**Linux/Mac:**
```
Copy all files to: /var/www/html/TnP
# Or your web server's document root
```

#### 4. Start Services

1. Open **XAMPP Control Panel**
2. Start **Apache** service
3. Start **MySQL** service

#### 5. Database Setup

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `placement`
3. Import the database:
   - Click on the `placement` database
   - Go to **Import** tab
   - Choose file: `database1.sql`
   - Click **Go**

#### 6. Configure Database Connection

Edit `config.php` if needed (default settings work for XAMPP):

```php
// Default settings (usually work as-is)
DB_HOST: localhost
DB_USER: root
DB_PASS: (empty)
DB_NAME: placement
```

#### 7. Configure Email (Optional)

**Option 1: Using .env file (Recommended)**

1. Copy the example file:
   ```bash
   # Windows
   copy env.example .env
   
   # Linux/Mac
   cp env.example .env
   ```

2. Edit `.env` file and update email settings:
   ```env
   TNP_SMTP_EMAIL=your-email@gmail.com
   TNP_SMTP_PASS=your-app-password
   ```

**Option 2: Using config.php directly**

Edit `config.php` and update the default values.

**Note:** For Gmail, use an [App Password](https://support.google.com/accounts/answer/185833) instead of your regular password.

See `SETUP_ENV.md` for detailed email configuration instructions.

#### 8. Set File Permissions (Linux/Mac)

```bash
chmod 755 TnP/
chmod 644 TnP/*.php
```

#### 9. Access the Application

Open your web browser and navigate to:

```
http://localhost/TnP/svnit.php
```

---

## ⚙️ Configuration

### Environment Variables

You can configure the application using environment variables (recommended for production):

```bash
# Database Configuration
export TNP_DB_HOST=localhost
export TNP_DB_USER=your_db_user
export TNP_DB_PASS=your_db_password
export TNP_DB_NAME=placement

# Email Configuration
export TNP_SMTP_EMAIL=your-email@gmail.com
export TNP_SMTP_PASS=your-app-password

# Base URL (for production)
export TNP_BASE_URL=https://yourdomain.com/TnP
```

### Configuration File

Edit `config.php` for direct configuration:

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'placement');

// Email Configuration
define('SMTP_EMAIL', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');

// Base URL (optional, for production)
define('BASE_URL', 'https://yourdomain.com/TnP');
```

### Default Credentials

**⚠️ IMPORTANT: Change these after first login!**

- **Admin Account**: Create via admin registration (first admin can be created directly)

---

## 📖 Usage Guide

### For Students

#### Registration

1. Navigate to **Student Portal**
2. Click **Register Now**
3. Fill in all required information:
   - Personal details (Name, DOB, Gender, etc.)
   - Contact information (Email, Phone, Address)
   - Academic details (10th, 12th, CGPA, Branch)
   - Upload profile picture
   - Choose: Internship or Placement
4. Submit the form

#### Login

1. Go to **Student Portal**
2. Choose **Internship Login** or **Placement Login**
3. Enter:
   - Student Name
   - Student ID
   - Password
4. Click **Sign In**

#### Apply for Companies

1. After login, go to **Apply for Internship/Placement**
2. Select a company from the dropdown
3. Click **Submit Application**
4. System will check eligibility automatically

### For Companies

#### Registration

1. Navigate to **Company Portal**
2. Click **Company Register**
3. Fill in company details:
   - Company ID
   - Company Name
   - Website URL
   - Address
   - Coming Date
   - Password
4. Submit and wait for admin approval

#### Adding Company Details

1. After login, go to **Company Details**
2. Fill in recruitment details:
   - Company Type (Internship/Placement)
   - Branch
   - Minimum CGPA
   - Maximum Backlogs
   - Salary/Stipend
   - Job Profile
   - Place of Posting
3. Submit details

### For Administrators

#### First Admin Registration

1. Navigate to **Admin Portal**
2. Click **Admin Register**
3. Fill in admin details
4. Submit (first admin can be created without existing admin)

#### Company Approval

1. Login to admin dashboard
2. Go to **Company Approval**
3. View pending companies
4. Approve or reject companies

#### Creating Notifications

1. Go to **Notifications**
2. Choose notification type:
   - General Notification
   - Branch-specific Notification
3. Enter notification text
4. Submit

---

## 📁 Project Structure

```
TnP/
├── 📄 Configuration Files
│   ├── config.php              # Database and email configuration
│   ├── server.php              # Session security configuration
│   ├── csrf.php                # CSRF protection functions
│   └── security.php            # Additional security helpers
│
├── 🎓 Student Files
│   ├── student.php             # Student portal landing page
│   ├── student_register.php     # Student registration
│   ├── student_login_int.php   # Internship login
│   ├── student_login_placement.php  # Placement login
│   ├── index_student_intern.php    # Internship dashboard
│   ├── index_student_placement.php  # Placement dashboard
│   ├── profile_student.php     # View profile
│   ├── profile_student_update_int.php  # Update internship profile
│   ├── profile_student_update_place.php # Update placement profile
│   ├── apply_intern.php        # Apply for internship
│   ├── apply_place.php         # Apply for placement
│   ├── intern_notification.php # Internship notifications
│   ├── intern_general_notification.php # General internship notifications
│   ├── place_notification.php  # Placement notifications
│   ├── place_general_notification.php  # General placement notifications
│   ├── intern_forgot.php       # Password reset (internship)
│   └── place_forgot.php        # Password reset (placement)
│
├── 🏢 Company Files
│   ├── company.php             # Company portal landing page
│   ├── company_register.php   # Company registration
│   ├── company_login.php       # Company login
│   ├── index_company.php       # Company dashboard
│   ├── index_company_details.php # Add company details
│   └── company_*.php           # Other company-related pages
│
├── 👨‍💼 Admin Files
│   ├── admin.php               # Admin portal landing page
│   ├── admin_register.php     # Admin registration
│   ├── admin_login.php        # Admin login
│   ├── index_admin.php        # Admin dashboard
│   ├── admin_company_approval.php # Company approval
│   ├── admin_student_status.php   # Student status management
│   ├── admin_notification.php    # Notification management
│   └── admin_*.php             # Other admin-related pages
│
├── 🔧 Utility Files
│   ├── logout.php              # Logout functionality
│   ├── reset_password.php      # Password reset handler
│   ├── class.phpmailer.php    # PHPMailer library
│   └── class.smtp.php          # SMTP library
│
├── 🎨 Assets
│   ├── Svnit_logo.png          # SVNIT logo
│   ├── a1.png                  # Additional images
│   └── *.jpg                   # Image assets
│
├── 📊 Database
│   └── database1.sql           # Database schema and initial data
│
└── 📝 Documentation
    └── README.md               # This file
```

---

## 🗄 Database Schema

### Main Tables

#### `student`
Stores student information and academic details.

**Key Fields:**
- `STUDENT_ID` (Primary Key)
- `S_PASSWORD` (Hashed)
- `STUDENT_NAME`, `EMAIL`, `BRANCH`
- `CGPA`, `BACKLOGS`, `STATUS`
- `APPLY_FOR` (Internship/Placement)
- `IMAGE` (Profile picture as BLOB)

#### `company`
Stores company registration information.

**Key Fields:**
- `COMPANY_ID` (Primary Key)
- `COMPANY_NAME`
- `C_PASSWORD` (Hashed)
- `APPROVAL` (approved/not approved)
- `STATUS` (visiting/not visiting)

#### `companybranch`
Stores company recruitment details per branch.

**Key Fields:**
- `COMPANY_NAME`, `C_TYPE`, `BRANCH` (Composite Primary Key)
- `MIN_CGPA`, `MAX_BACKLOGS`
- `MAX_SALARY`, `MAX_STIPEND`
- `JOB_PROFILE`, `PLACE_OF_POSTING`

#### `registered_interns`
Tracks student internship applications.

#### `registered_placements`
Tracks student placement applications.

#### `admin`
Stores administrator accounts.

### Database Features

- **Triggers**: Automatic updates (e.g., increment apply count)
- **Stored Procedures**: Complex operations (e.g., bulk notifications)
- **Foreign Keys**: Referential integrity
- **Indexes**: Optimized queries

---

## 🚀 Deployment

### Production Deployment Checklist

#### 1. Security Hardening

- [ ] Change default admin credentials
- [ ] Set strong database passwords
- [ ] Configure environment variables
- [ ] Enable HTTPS (SSL certificate)
- [ ] Update `config.php` with production values
- [ ] Set `session.cookie_secure = 1` in `server.php` (for HTTPS)
- [ ] Remove or secure `email_log.txt` and `security_log.txt`

#### 2. Server Configuration

**Apache Configuration (.htaccess):**

```apache
# Enable HTTPS redirect
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>

# Disable directory listing
Options -Indexes

# Protect sensitive files
<FilesMatch "\.(sql|log|txt)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

#### 3. PHP Configuration

Update `php.ini`:

```ini
; Security Settings
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log

; File Upload
upload_max_filesize = 5M
post_max_size = 5M

; Session Security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_only_cookies = 1
```

#### 4. Database Security

- Create dedicated database user with limited privileges
- Use strong passwords
- Enable MySQL SSL if possible
- Regular backups

#### 5. File Permissions

```bash
# Set proper permissions
chmod 755 TnP/
chmod 644 TnP/*.php
chmod 600 TnP/config.php  # If contains sensitive data
```

#### 6. Environment Variables

Set environment variables on your server:

```bash
# Apache (in httpd.conf or .htaccess)
SetEnv TNP_DB_HOST localhost
SetEnv TNP_DB_USER your_db_user
SetEnv TNP_DB_PASS your_secure_password
SetEnv TNP_DB_NAME placement
SetEnv TNP_SMTP_EMAIL your-email@gmail.com
SetEnv TNP_SMTP_PASS your-app-password
```

### Deployment Platforms

#### Shared Hosting (cPanel, etc.)

1. Upload all files via FTP/cPanel File Manager
2. Create database via cPanel MySQL Databases
3. Import `database1.sql`
4. Update `config.php` with hosting credentials
5. Set file permissions

#### VPS/Cloud (AWS, DigitalOcean, etc.)

1. Install LAMP stack
2. Clone repository
3. Configure Apache virtual host
4. Set up MySQL database
5. Configure SSL certificate (Let's Encrypt)
6. Set up firewall rules

#### Docker Deployment (Optional)

Create `Dockerfile`:

```dockerfile
FROM php:7.4-apache
COPY . /var/www/html/
RUN docker-php-ext-install mysqli pdo pdo_mysql
EXPOSE 80
```

---

## 📱 Mobile Responsiveness

The application is fully responsive and optimized for:

- ✅ **Mobile Phones** (320px - 768px)
- ✅ **Tablets** (768px - 1024px)
- ✅ **Desktops** (1024px+)

### Responsive Features

- Flexible grid layouts
- Touch-friendly buttons and inputs
- Optimized navigation menus
- Responsive images and cards
- Mobile-first CSS approach

### Testing

Test on various devices or use browser developer tools:
- Chrome DevTools (F12 → Toggle device toolbar)
- Firefox Responsive Design Mode
- Online tools: BrowserStack, Responsive Design Checker

---

## 🔌 API Documentation

### Authentication Endpoints

#### Student Login (Internship)
```
POST /student_login_int.php
Parameters:
  - student_name: string
  - student_id: string
  - apply: "Internship"
  - st_password: string
  - __csrf: token
```

#### Student Login (Placement)
```
POST /student_login_placement.php
Parameters:
  - student_name: string
  - student_id: string
  - apply: "Placement"
  - st_password: string
  - __csrf: token
```

#### Company Login
```
POST /company_login.php
Parameters:
  - company_name: string
  - company_id: string
  - c_password: string
  - __csrf: token
```

#### Admin Login
```
POST /admin_login.php
Parameters:
  - admin_name: string
  - admin_id: string
  - admin_password: string
  - __csrf: token
```

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Make your changes**
   - Follow PHP PSR coding standards
   - Add comments for complex logic
   - Test thoroughly
4. **Commit your changes**
   ```bash
   git commit -m 'Add amazing feature'
   ```
5. **Push to the branch**
   ```bash
   git push origin feature/amazing-feature
   ```
6. **Open a Pull Request**

### Coding Standards

- Use prepared statements for all database queries
- Add CSRF protection to all forms
- Validate and sanitize all user inputs
- Use meaningful variable names
- Add comments for complex functions
- Follow existing code style

---

## 🐛 Troubleshooting

### Common Issues

#### 1. Database Connection Error

**Problem:** Cannot connect to database

**Solutions:**
- Check if MySQL service is running
- Verify database credentials in `config.php`
- Ensure database `placement` exists
- Check MySQL port (default: 3306)

#### 2. Session Not Working

**Problem:** Sessions not persisting, logged out frequently

**Solutions:**
- Check `server.php` is included
- Verify session directory permissions
- Clear browser cookies
- Check PHP `session.save_path` in `php.ini`

#### 3. File Upload Not Working

**Problem:** Cannot upload profile images

**Solutions:**
- Check PHP `upload_max_filesize` in `php.ini`
- Verify file permissions on upload directory
- Check file size (max 512KB)
- Ensure correct file type (PNG, JPG, JPEG)

#### 4. Email Not Sending

**Problem:** Password reset emails not received

**Solutions:**
- Verify SMTP credentials in `config.php`
- For Gmail, use App Password (not regular password)
- Check firewall/network restrictions
- Check `email_log.txt` for errors

#### 5. CSRF Token Error

**Problem:** "CSRF token validation failed"

**Solutions:**
- Ensure `csrf.php` is included
- Check if session is started
- Verify form includes `<?php echo csrf_field(); ?>`
- Clear browser cache

#### 6. 404 Errors

**Problem:** Pages not found

**Solutions:**
- Verify file paths are correct
- Check Apache mod_rewrite is enabled
- Ensure `.htaccess` is configured (if using)
- Check file permissions

### Debug Mode

To enable debug mode, edit `config.php`:

```php
// Add at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**⚠️ Remember to disable in production!**

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Authors & Credits

- **Development Team**: SVNIT T&P Cell
- **Institution**: Sardar Vallabhbhai National Institute of Technology, Surat
- **Project Type**: DBMS Mini Project

---

## 🙏 Acknowledgments

- SVNIT Training & Placement Cell
- PHPMailer for email functionality
- Google Fonts (Inter) for typography
- All contributors and testers

---

## 📞 Support & Contact

For issues, questions, or contributions:

- **Email**: cdc@svnit.ac.in
- **Phone**: +91-0261-2201771, +91-8849931589
- **Website**: [SVNIT T&P Portal](https://www.svnit.ac.in/web/t&p/)

---

## 🔄 Version History

### Version 2.0 (Current)
- ✅ Security hardening (SQL injection, CSRF protection)
- ✅ Enhanced session security
- ✅ Mobile responsiveness improvements
- ✅ Comprehensive documentation
- ✅ Production-ready deployment guide

### Version 1.0
- Initial release
- Basic functionality
- Student, Company, Admin portals

---

<div align="center">

**Made with ❤️ for SVNIT Training & Placement Cell**

⭐ Star this repo if you find it helpful!

</div>
