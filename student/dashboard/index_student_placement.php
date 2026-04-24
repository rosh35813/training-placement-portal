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
if($_SESSION['user']=='student_int')
{
    header("Location: index_student_intern.php");
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
    <title>Student Profile - T&P SVNIT</title>
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
            gap: 1.5rem;
        }

        .logo img {
            height: 60px;
        }

        .header-text {
            flex-grow: 1;
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

        .logout-link {
            padding: 0.5rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .logout-link:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            padding: 3rem 0;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .hero-content h2 {
            font-size: 2rem;
            color: #1e40af;
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: #475569;
        }

        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Section */
        .section {
            margin: 3rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .section-title h3 {
            font-size: 1.75rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: #64748b;
            font-size: 1rem;
        }

        /* Cards Grid - 2 columns layout */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .card {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            border-color: #3b82f6;
        }

        .card-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .card h4 {
            color: #1e293b;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card p {
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .card-button {
            display: inline-block;
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
            border: none;
            cursor: pointer;
        }

        .card-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.4);
        }

        .card-button:active {
            transform: translateY(0);
        }

        /* Info Card */
        .info-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .info-card h4 {
            color: #1e40af;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .info-card p {
            color: #475569;
            margin-bottom: 1rem;
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .info-card a {
            color: #1e40af;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .info-card a:hover {
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
        @media (max-width: 1024px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .header-text h1 {
                font-size: 1.25rem;
            }

            .hero-content h2 {
                font-size: 1.5rem;
            }

            .main-container {
                padding: 0 1rem;
            }

            .cards-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .section-title h3 {
                font-size: 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <a href="svnit.php">
                    <img src="../assets/images/Svnit_logo.png" alt="SVNIT SURAT" />
                </a>
            </div>
            <div class="header-text">
                <h1><a href="svnit.php">Training &amp; Placement Cell</a></h1>
                <p><a href="http://www.svnit.ac.in" target="_blank">S.V. National Institute of Technology, Surat</a></p>
            </div>
            <a href="logout.php" class="logout-link" title="<?php echo $_SESSION['student_name']; ?>">Log Out</a>
        </div>
    </div>


    <!-- Main Content -->
    <div class="main-container">
        <!-- Quick Actions Section -->
        <div class="section">
            <div class="section-title">
                <h3>Quick Actions</h3>
                <p>Access your profile and applications</p>
            </div>

            <div class="cards-grid">
                <div class="card">
                    <div class="card-icon">👤</div>
                    <h4>View Profile</h4>
                    <p>Review your current profile information and submitted details</p>
                    <a href="profile_student2.php" class="card-button">View Now</a>
                </div>

                <div class="card">
                    <div class="card-icon">✏️</div>
                    <h4>Update Profile</h4>
                    <p>Modify your profile information and keep it up to date</p>
                    <a href="profile_student_update_place.php" class="card-button">Update Now</a>
                </div>

                <div class="card">
                    <div class="card-icon">📋</div>
                    <h4>Apply For Company</h4>
                    <p>Browse and apply for available job opportunities</p>
                    <a href="apply_place.php" class="card-button">Apply Now</a>
                </div>

                <div class="card">
                    <div class="card-icon">🔔</div>
                    <h4>Notifications</h4>
                    <p>Check latest updates and placement notifications</p>
                    <a href="place_notification.php" class="card-button">View Notifications</a>
                </div>
            </div>
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
