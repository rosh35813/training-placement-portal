<?php

session_start();

if (!isset($_SESSION['user'])) {

    header("Location: svnit.php");

    exit();

}

if ($_SESSION['user'] == 'admin') {

    header("Location: index_admin.php");

    exit();

}

if ($_SESSION['user'] == 'student_place') {

    header("Location: index_student_placement.php");

    exit();

}

if ($_SESSION['user'] == 'company') {

    header("Location: index_company.php");

    exit();

}

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Student Dashboard - T&P SVNIT</title>

    <link rel="shortcut icon" type="image/png" href="../assets/images/Svnit_logo.png" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <style>

        /* Base Reset */

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

            min-height: 100vh;

            display: flex;

            flex-direction: column;

        }

        

        /* Header */

        .header {

            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);

            padding: 1.5rem 2rem;

            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

            display: flex;

            align-items: center;

            justify-content: space-between;

        }

        

        .logo img {

            height: 60px;

            display: block;

        }

        

        .header-text {

            flex-grow: 1;

            margin-left: 1rem;

        }

        

        .header-text h1 {

            font-weight: 600;

            font-size: 1.5rem;

            color: white;

            margin-bottom: 0.2rem;

        }

        

        .header-text h1 a {

            color: white;

            text-decoration: none;

            transition: opacity 0.3s;

        }

        

        .header-text h1 a:hover {

            opacity: 0.8;

        }

        

        .header-text p {

            font-size: 0.9rem;

            color: rgba(255, 255, 255, 0.9);

        }

        

        .logout-link {

            background: rgba(255, 255, 255, 0.2);

            color: white;

            padding: 0.5rem 1.5rem;

            border-radius: 8px;

            font-weight: 500;

            font-size: 0.9rem;

            text-decoration: none;

            transition: all 0.3s;

            white-space: nowrap;

        }

        

        .logout-link:hover {

            background: rgba(255, 255, 255, 0.35);

            transform: translateY(-2px);

        }

        

        /* Main container */

        .main-container {

            max-width: 1200px;

            margin: 3rem auto 4rem;

            padding: 0 2rem;

            flex-grow: 1;

        }

        

        /* Section Title */

        .section-title {

            text-align: center;

            margin-bottom: 2.5rem;

        }

        

        .section-title h3 {

            font-size: 1.75rem;

            color: #1e293b;

            margin-bottom: 0.5rem;

            font-weight: 700;

        }

        

        .section-title p {

            color: #64748b;

            font-size: 1rem;

        }

        

        /* Cards Grid */

        .cards-grid {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 2rem;

        }

        

        /* Card Styles */

        .card {

            background: white;

            padding: 2.5rem 2rem;

            border-radius: 12px;

            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);

            transition: all 0.3s;

            border: 1px solid #e2e8f0;

            text-align: center;

            display: flex;

            flex-direction: column;

            align-items: center;

        }

        

        .card:hover {

            transform: translateY(-8px);

            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);

            border-color: #3b82f6;

        }

        

        .card-icon {

            width: 64px;

            height: 64px;

            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 2.5rem;

            margin-bottom: 1.5rem;

            user-select: none;

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

            padding: 0.875rem 1.5rem;

            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);

            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-weight: 600;

            font-size: 1rem;

            border: none;

            cursor: pointer;

            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);

            transition: all 0.3s;

            width: 100%;

            display: inline-block;

        }

        

        .card-button:hover {

            transform: translateY(-2px);

            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.4);

        }

        

        .card-button:active {

            transform: translateY(0);

        }

        

        /* Footer */

        .footer {

            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);

            color: white;

            padding: 3rem 2rem 1.5rem;

            margin-top: auto;

        }

        

        .footer-content {

            max-width: 1200px;

            margin: 0 auto;

            display: grid;

            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));

            gap: 3rem;

        }

        

        .footer-section h3 {

            margin-bottom: 1rem;

            font-size: 1.25rem;

            color: #60a5fa;

            font-weight: 600;

        }

        

        .footer-section p,

        .footer-section a {

            color: #cbd5e1;

            text-decoration: none;

            line-height: 1.8;

            font-size: 0.95rem;

            font-weight: 400;

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

            font-weight: 400;

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

            font-weight: 400;

        }

        

        /* Responsive */

        @media (max-width: 1024px) {

            .cards-grid {

                grid-template-columns: repeat(2, 1fr);

            }

        }

        

        @media (max-width: 768px) {

            .header {

                flex-direction: column;

                text-align: center;

                gap: 1rem;

            }

            .header-text {

                margin: 0;

            }

            .cards-grid {

                grid-template-columns: 1fr;

                gap: 1.5rem;

            }

        }

    </style>

</head>



<body>

    <!-- Header -->

    <header class="header">

        <div class="logo">

            <a href="svnit.php" title="SVNIT Surat">

                <img src="../assets/images/Svnit_logo.png" alt="SVNIT SURAT" />

            </a>

        </div>

        <div class="header-text">

            <h1><a href="svnit.php">Training &amp; Placement Cell</a></h1>

            <p>S.V. National Institute of Technology, Surat</p>

        </div>

        <a href="logout.php" class="logout-link" title="<?php echo htmlspecialchars($_SESSION['student_name']); ?>">Log Out</a>

    </header>



    <!-- Main Content -->

    <main class="main-container">

        <section class="section-title">

            <h3>Quick Actions</h3>

            <p>Access your profile and applications</p>

        </section>



        <div class="cards-grid">

            <div class="card">

                <div class="card-icon" aria-hidden="true">👤</div>

                <h4>View Profile</h4>

                <p>Review your current profile information and submitted details</p>

                <a href="profile_student.php" class="card-button">View Now</a>

            </div>



            <div class="card">

                <div class="card-icon" aria-hidden="true">🔔</div>

                <h4>Notifications</h4>

                <p>Check latest updates and internship notifications</p>

                <a href="intern_notification.php" class="card-button">View Notifications</a>

            </div>



            <div class="card">

                <div class="card-icon" aria-hidden="true">✏</div>

                <h4>Update Profile</h4>

                <p>Modify your profile information and keep it up to date</p>

                <a href="profile_student_update_int.php" class="card-button">Update Now</a>

            </div>



            <div class="card">

                <div class="card-icon" aria-hidden="true">📋</div>

                <h4>Apply For Company</h4>

                <p>Browse and apply for available internship opportunities</p>

                <a href="apply_intern.php" class="card-button">Apply Now</a>

            </div>

        </div>

    </main>



    <!-- Footer -->

    <footer class="footer">

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

                <p>Ground Floor, CDC<br />S.V. National Institute of Technology<br />Surat - 395007</p>

            </div>



            <div class="footer-section">

                <h3>Contact Information</h3>

                <p><strong>Phone:</strong><br />

                    <a href="tel:+912612201771">+91-0261-2201771</a><br />

                    <a href="tel:+918849931589">+91-8849931589</a>

                </p>

                <p style="margin-top: 1rem;"><strong>Email:</strong><br />

                    <a href="mailto:cdc@svnit.ac.in">cdc@svnit.ac.in</a>

                </p>

            </div>

        </div>

        <div class="footer-bottom">

            <p>&copy; 2025 Training &amp; Placement Cell, SVNIT Surat. All rights reserved.</p>

        </div>

    </footer>

</body>



</html>