# 🎓 Training & Placement Cell Portal - SVNIT

<div align="center">

![SVNIT Logo](assets/images/Svnit_logo.png)

**A comprehensive, secure, and production-ready web-based portal for managing Training and Placement activities at Sardar Vallabhbhai National Institute of Technology (SVNIT), Surat.**

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Security](https://img.shields.io/badge/Security-Hardened-red.svg)](#security-features)

</div>

---

## 📋 Quick Start

1. **Install XAMPP** and start Apache & MySQL
2. **Copy files** to `C:\xampp\htdocs\TnP`
3. **Import database**: `database/database1.sql` via phpMyAdmin
4. **Create `.env` file** from `env.example` and configure email
5. **Access**: `http://localhost/TnP/svnit.php`

For detailed instructions, see [docs/README.md](docs/README.md)

---

## 📁 Project Structure

```
TnP/
├── config/              # Configuration files
│   ├── config.php       # Main configuration
│   ├── load_env.php     # Environment loader
│   └── server.php       # Session configuration
├── includes/            # Security & utility files
│   ├── csrf.php         # CSRF protection
│   └── security.php     # Security helpers
├── student/             # Student portal
│   ├── login/          # Login pages
│   ├── register/       # Registration
│   ├── dashboard/      # Dashboard & features
│   └── profile/        # Profile management
├── company/             # Company portal
│   ├── login/          # Login pages
│   ├── register/       # Registration
│   └── dashboard/      # Dashboard & features
├── admin/               # Admin portal
│   ├── login/          # Login pages
│   ├── register/       # Registration
│   └── dashboard/      # Admin features
├── assets/              # Static assets
│   └── images/         # Image files
├── lib/                 # Third-party libraries
│   ├── class.phpmailer.php
│   └── class.smtp.php
├── database/            # Database files
│   └── database1.sql   # Database schema
├── docs/                # Documentation
│   ├── README.md       # Full documentation
│   ├── SETUP_ENV.md    # Environment setup
│   └── PROJECT_STRUCTURE.md
├── bootstrap.php        # Path configuration
├── svnit.php            # Main landing page
├── env.example          # Environment template
└── .gitignore          # Git ignore rules
```

---

## 🔒 Security Features

- ✅ SQL Injection Protection (Prepared Statements)
- ✅ CSRF Protection (All Forms)
- ✅ Secure Password Hashing
- ✅ Session Security
- ✅ Input Validation & Sanitization
- ✅ XSS Protection

---

## 📚 Documentation

- **[Full Documentation](docs/README.md)** - Complete project documentation
- **[Environment Setup](docs/SETUP_ENV.md)** - Email & environment configuration
- **[Project Structure](docs/PROJECT_STRUCTURE.md)** - Detailed file organization

---

## 🚀 Features

### For Students
- Separate Internship & Placement portals
- Profile management
- Company applications
- Notifications

### For Companies
- Registration & approval system
- Post opportunities
- Manage applications
- Student database access

### For Administrators
- User management
- Company approval
- Notification system
- Reports & analytics

---

## 📞 Support

- **Email**: tnpcell.svnit@gmail.com

---

<div align="center">

**Made with ❤️ for SVNIT Training & Placement Cell**

⭐ Star this repo if you find it helpful!

</div>

