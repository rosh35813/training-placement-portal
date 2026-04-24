# Project Structure Guide

## Current File Organization

The project maintains a flat structure for simplicity, but files are logically grouped by functionality:

### 📁 Core Configuration Files
```
config.php          - Main configuration (DB, Email, App settings)
load_env.php        - Environment variable loader
server.php          - Session security configuration
csrf.php            - CSRF protection functions
security.php        - Additional security helpers
```

### 📁 Student Portal Files
```
student.php                    - Student portal landing page
student_register.php          - Student registration
student_login_int.php         - Internship login
student_login_placement.php   - Placement login
index_student_intern.php      - Internship dashboard
index_student_placement.php   - Placement dashboard
profile_student.php           - View profile
profile_student_update_int.php - Update internship profile
profile_student_update_place.php - Update placement profile
apply_intern.php              - Apply for internship
apply_place.php               - Apply for placement
intern_notification.php       - Internship notifications
intern_general_notification.php - General internship notifications
place_notification.php        - Placement notifications
place_general_notification.php - General placement notifications
intern_forgot.php             - Password reset (internship)
place_forgot.php              - Password reset (placement)
intern_reg_company.php        - View registered companies (internship)
intern_visit_company.php      - Visit company details (internship)
place_reg_company.php         - View registered companies (placement)
place_visit_company.php       - Visit company details (placement)
```

### 📁 Company Portal Files
```
company.php                   - Company portal landing page
company_register.php         - Company registration
company_login.php            - Company login
index_company.php            - Company dashboard
index_company_details.php   - Add company recruitment details
```

### 📁 Admin Portal Files
```
admin.php                    - Admin portal landing page
admin_register.php          - Admin registration
admin_login.php             - Admin login
index_admin.php             - Admin dashboard
admin_company_approval.php  - Company approval management
admin_student_status.php    - Student status management
admin_notification.php      - Notification management
admin_noti_reg_int.php      - Register internship notification
admin_noti_reg_place.php    - Register placement notification
admin_noti_select_int.php   - Select internship notification
admin_noti_select_place.php - Select placement notification
admin_absent_student.php    - Manage absent students
admin_register_admin.php    - Register new admin (by existing admin)
```

### 📁 Utility Files
```
svnit.php                    - Main landing page
logout.php                   - Logout functionality
reset_password.php           - Password reset handler
index.php                    - Redirect page
positive.php                 - Success page
wrong.php                    - Error page
```

### 📁 Email & Libraries
```
class.phpmailer.php         - PHPMailer library
class.smtp.php              - SMTP library
credential.php              - Email credentials (legacy)
```

### 📁 Database
```
database1.sql                - Database schema and initial data
```

### 📁 Assets
```
Svnit_logo.png              - SVNIT logo
a1.png, s1.jpg, s4.jpg      - Image assets
```

### 📁 Documentation
```
README.md                    - Main project documentation
SETUP_ENV.md                - Environment setup guide
PROJECT_STRUCTURE.md        - This file
.gitignore                  - Git ignore rules
env.example                 - Environment variables template
```

### 📁 Logs (Generated at runtime)
```
email_log.txt               - Email activity log
security_log.txt            - Security event log (if using security.php)
```

## File Naming Conventions

- **Portal pages**: `student.php`, `company.php`, `admin.php`
- **Index pages**: `index_*.php` (dashboard pages)
- **Login pages**: `*_login.php`
- **Registration pages**: `*_register.php`
- **Profile pages**: `profile_*.php`
- **Notification pages**: `*_notification.php`
- **Admin pages**: `admin_*.php`

## Recommended File Organization (Optional)

For larger projects, you could organize into directories:

```
TnP/
├── config/
│   ├── config.php
│   ├── load_env.php
│   └── server.php
├── includes/
│   ├── csrf.php
│   └── security.php
├── student/
│   ├── login/
│   ├── register/
│   ├── dashboard/
│   └── profile/
├── company/
│   ├── login/
│   ├── register/
│   └── dashboard/
├── admin/
│   ├── login/
│   ├── register/
│   └── dashboard/
├── assets/
│   ├── images/
│   ├── css/
│   └── js/
├── lib/
│   ├── class.phpmailer.php
│   └── class.smtp.php
├── database/
│   └── database1.sql
└── docs/
    ├── README.md
    └── SETUP_ENV.md
```

**Note:** Current flat structure is maintained for simplicity and easy deployment.

