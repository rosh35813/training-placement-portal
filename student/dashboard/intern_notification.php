<?php
session_start();
if(!isset($_SESSION['user']))
{
    header("Location: svnit.php");
}
if($_SESSION['user']=='admin')
{
    header("Location: index_admin.php");
}
if($_SESSION['user']=='student_place' )
{
    header("Location: index_student_placement.php");
}
if($_SESSION['user']=='company')
{
    header("Location: index_company.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Internship | T&P SVNIT</title>
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
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            gap: 1.5rem;
        }

        .logo img {
            height: 60px;
        }

        .header-text h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .header-text p {
            font-size: 0.9rem;
            opacity: 0.95;
        }

        .header-text a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .header-text a:hover {
            opacity: 0.8;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 3rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #1e40af;
        }

        .welcome-section h2 {
            color: #1e40af;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .dashboard-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .dashboard-card h3 {
            color: #1e40af;
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
        }

        .dashboard-card p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        .btn-secondary {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: white;
            color: #1e40af;
            border: 2px solid #1e40af;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #eff6ff;
            transform: translateX(3px);
        }

        /* Info Section */
        .info-section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-top: 3rem;
        }

        .info-section h3 {
            color: #1e40af;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .info-item {
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }

        .info-item strong {
            display: block;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .info-item p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .info-item a {
            color: #1e40af;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .info-item a:hover {
            color: #3b82f6;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: #60a5fa;
        }

        .footer-section p,
        .footer-section a {
            color: #cbd5e1;
            text-decoration: none;
            line-height: 1.8;
            font-size: 0.9rem;
        }

        .footer-section a:hover {
            color: #60a5fa;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #475569;
            color: #94a3b8;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .header-left {
                width: 100%;
                justify-content: center;
            }

            .dashboard-container {
                padding: 0 1rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-card {
                padding: 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="header-content">
        <div class="header-left">
            <div class="logo">
                <a href="svnit.php">
                    <img src="../assets/images/Svnit_logo.png" alt="SVNIT SURAT" />
                </a>
            </div>
            <div class="header-text">
                <h1><a href="svnit.php">Training &amp; Placement Cell</a></h1>
                <p><a href="http://www.svnit.ac.in" target="_blank">S.V. National Institute of Technology, Surat</a></p>
            </div>
        </div>
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
</div>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <div class="welcome-section">
        <h2>👋 Welcome, <?php echo $_SESSION['student_name']; ?>!</h2>
        <p>Manage your internship applications and stay updated with the latest opportunities.</p>
    </div>

    <!-- Main Dashboard Cards -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">🏢</div>
            <h3>Visiting Companies</h3>
            <p>Explore all approved companies coming for internship drives.</p>
            <a href="intern_visit_company.php" class="btn-primary">View Companies →</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📋</div>
            <h3>My Applications</h3>
            <p>Track all your internship applications and company status.</p>
            <a href="intern_reg_company.php" class="btn-primary">View Applications →</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">🔔</div>
            <h3>Notifications</h3>
            <p>Stay informed with the latest announcements and updates.</p>
            <a href="intern_general_notification.php" class="btn-primary">View Notifications →</a>
        </div>

    </div>

    
</div>

</body>
</html>
