<?php include(__DIR__ . '/../config/server.php') ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="assets/images/Svnit_logo.png">
    <title>Home - T&P SVNIT SURAT</title>
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

        /* Navigation */
        .navigation {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .nav-link {
            display: inline-block;
            padding: 1rem 1.5rem;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
        }

        .nav-link:hover {
            color: #1e40af;
            border-bottom-color: #1e40af;
        }

        /* Slideshow */
        .slideshow-section {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .slideshow-container {
            position: relative;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .mySlides {
            display: none;
            position: relative;
        }

        .mySlides img {
            width: 100%;
            height: 425px;
            object-fit: cover;
        }

        .numbertext {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .text {
            position: absolute;
            bottom: 1.5rem;
            left: 1.5rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1.25rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.4);
        }

        .fade {
            animation: fade 1s;
        }

        @keyframes fade {
            from { opacity: 0.4; }
            to { opacity: 1; }
        }

        /* Dots */
        .dots-container {
            text-align: center;
            margin-top: 1.5rem;
        }

        .dot {
            height: 12px;
            width: 12px;
            margin: 0 6px;
            background-color: #cbd5e1;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s;
        }

        .dot.active,
        .dot:hover {
            background-color: #1e40af;
            transform: scale(1.2);
        }

        /* Content Cards */
        .content-section {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .card h3 {
            color: #1e40af;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .card-link {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .card-link:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
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
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .nav-container {
                flex-direction: column;
                gap: 0;
            }

            .nav-link {
                border-bottom: 1px solid #e2e8f0;
                border-left: none;
            }

            .mySlides img {
                height: 750px;
            }

            .cards-grid {
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
                <img src="assets/images/Svnit_logo.png" alt="SVNIT SURAT" />
            </a>
        </div>
        <div class="header-text">
            <h1><a href="svnit.php">Training &amp; Placement Cell</a></h1>
            <p><a href="http://www.svnit.ac.in" target="_blank">S.V. National Institute of Technology, Surat</a></p>
        </div>
    </div>
</div>

<!-- Navigation -->
<div class="navigation">
    <div class="nav-container">
        <a href="company.php" class="nav-link">Company</a>
        <a href="student.php" class="nav-link">Student</a>
        <a href="admin.php" class="nav-link">Admin</a>
    </div>
</div>

<!-- Slideshow -->
<div class="slideshow-section">
    <div class="slideshow-container">
        <div class="mySlides fade">
            <!-- <div class="numbertext">2 / 3</div> -->
            <img src="../assets/images/s1.jpg" alt="SVNIT Campus">
        </div>
		 <div class="mySlides fade">
            <!-- <div class="numbertext">3 / 3</div> -->
            <img src="assets/images/a1.png" alt="NIT SURAT">
        </div>
        <div class="mySlides fade">
            <!-- <div class="numbertext">1 / 3</div> -->
            <img src="assets/images/s4.jpg" alt="SVNIT Campus">
        </div>
    </div>

    <div class="dots-container">
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>
</div>

<!-- Content Cards -->
<div class="content-section">
    <div class="cards-grid">
        <div class="card">
            <h3>Placement & Training Policy</h3>
            <p>Access comprehensive information about our placement and training policies for the academic year 2024-25.</p>
            <a href="https://www.svnit.ac.in/web/t&p/pdf/Placement%20Policy,%20CDC_2024-25.pdf" class="card-link">View Policy →</a>
        </div>

        <div class="card">
            <h3>Placement Reports</h3>
            <p>Explore previous placement and internship reports to understand our track record and success stories.</p>
            <a href="https://www.svnit.ac.in/web/t&p/placement.php" class="card-link">View Reports →</a>
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

<script>
var slideIndex = 0;
showSlides();

function showSlides() {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
    
    setTimeout(showSlides, 3000);
}
</script>

</body>
</html>