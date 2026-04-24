<?php
  session_start();
    if(!isset($_SESSION['user']))
    {
      header("Location: svnit.php");
    }
    if($_SESSION['user']=='student_int' )
    {
      header("Location: index_student_intern.php");
    }
    if($_SESSION['user']=='student_place' )
    {
      header("Location: index_student_placement.php");
    }
  if($_SESSION['user']=='admin')
    {
      header("Location: index_admin.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - T&P SVNIT</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/Svnit_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .top-header {
            background: rgba(255, 255, 255, 0.98);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo img {
            height: 40px;
        }

        .header-text {
            color: #1e293b;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .logout-btn {
            padding: 0.625rem 1.5rem;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
            font-size: 0.95rem;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        }

        /* Main Container */
        .container {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
            width: 100%;
        }

        /* Welcome Section */
        .welcome-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-card h1 {
            color: #1e293b;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-card p {
            color: #64748b;
            font-size: 1.1rem;
        }

        /* Action Card */
        .action-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .action-card h2 {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .action-button {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.4);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
            color: #60a5fa;
        }

        .footer-section p,
        .footer-section a {
            color: #cbd5e1;
            text-decoration: none;
            line-height: 1.8;
        }

        .footer-section a:hover {
            color: #60a5fa;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-link {
            padding: 0.5rem 0;
            transition: all 0.3s;
            display: inline-block;
        }

        .footer-link:hover {
            padding-left: 0.5rem;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid #475569;
            color: #94a3b8;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header-left {
                flex-direction: column;
            }

            .header-text {
                font-size: 0.95rem;
            }

            .container {
                padding: 2rem 1rem;
            }

            .welcome-card h1 {
                font-size: 1.5rem;
            }

            .welcome-card,
            .action-card {
                padding: 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
    </style>
</head>
<body>

<!-- Top Header -->
<div class="top-header">
    <div class="header-content">
        <div class="header-left">
            <div class="logo">
                <a href="svnit.php">
                    <img src="../assets/images/Svnit_logo.png" alt="SVNIT SURAT" />
                </a>
            </div>
            <div class="header-text">
                Training &amp; Placement, SVNIT SURAT
            </div>
        </div>
        <a href="logout.php" class="logout-btn" title="<?php echo $_SESSION['company_name']; ?>">
            Log Out
        </a>
    </div>
</div>

<!-- Main Container -->
<div class="container">
    <!-- Welcome Section -->
    <div class="welcome-card">
        <h1>Welcome, <?php echo isset($_SESSION['company_name']) ? $_SESSION['company_name'] : 'Company'; ?>!</h1>
        <p>Manage your company details and recruitment activities</p>
    </div>

    <!-- Action Section -->
    <div class="action-card">
        <h2>Quick Actions</h2>
        <a href="index_company_details.php" class="action-button">Add Company Details</a>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Quick Links</h3>
            <div class="footer-links">
                <a href="https://www.svnit.ac.in/web/t&p/about.php" class="footer-link">About Us</a>
                <a href="https://www.svnit.ac.in/web/t&p/contact.php" class="footer-link">Contact Us</a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Training &amp; Placement Office</h3>
            <p>Ground Floor, CDC<br>
            S.V. National Institute of Technology<br>
            Surat - 395007</p>
        </div>

        <div class="footer-section">
            <h3>Contact Information</h3>
            <p><strong>Phone:</strong><br>
            <a href="tel:+912612201771">+91-0261-2201771</a><br>
            <a href="tel:+918849931589">+91-8849931589</a></p>
            <p style="margin-top: 1rem;"><strong>Email:</strong><br>
            <a href="mailto:cdc@svnit.ac.in">cdc@svnit.ac.in</a></p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 Training & Placement Cell, SVNIT Surat. All rights reserved.</p>
    </div>
</div>

</body>
</html>