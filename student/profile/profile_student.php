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

require_once __DIR__ . '/../config/config.php';

$db = db_connect();

$student_id="";
$student_name="";
$dob="";
$gender="";
$st_email="";
$address1="";
$contact_num="";
$branch="";
$tenth_per="";
$tenth_pass="";
$twelfth_per="";
$twelfth_pass="";
$cgpa="";
$pass="";
$backlogs="";
$apply="";
$status="";

$vari=$_SESSION['student_id'];
$stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_ID=? LIMIT 1");
$stmt->bind_param('s', $vari);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$student_id=$user['STUDENT_ID'];
$student_name=$user['STUDENT_NAME'];
$dob=$user['DOB'];
$gender=$user['GENDER'];
$st_email=$user['EMAIL'];
$address1=$user['ADDRESS'];
$contact_num=$user['CONTACT_NO'];
$branch=$user['BRANCH'];
$tenth_per=$user['TENTH_PER'];
$tenth_pass=$user['TENTH_PASS_YEAR'];
$twelfth_per=$user['TWELTH_PER'];
$twelfth_pass=$user['TWELTH_PASS_YEAR'];
$cgpa=$user['CGPA'];
$pass=$user['PASSING_YEAR'];
$backlogs=$user['BACKLOGS'];
$apply=$user['APPLY_FOR'];
$status=$user['STATUS'];

if($status=='NS')
{
    $status='-';
}
else
{
    $stmt = $db->prepare("SELECT * FROM student_internship WHERE STUDENT_ID=? LIMIT 1");
    $stmt->bind_param('s', $vari);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if ($user) {
        $com_name=$user['COMPANY_NAME'];
        $status=$com_name;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | T&P SVNIT</title>
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
            padding-bottom: 2rem;
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

        .logout-link {
            color: #1e40af;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s;
            background: rgba(30, 64, 175, 0.1);
        }

        .logout-link:hover {
            background: rgba(30, 64, 175, 0.2);
            transform: translateY(-1px);
        }

        /* Main Container */
        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .profile-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .profile-image-wrapper {
            margin-bottom: 1rem;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .profile-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .profile-id {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 1rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Profile Body */
        .profile-body {
            padding: 2.5rem;
        }

        .section-title {
            color: #1e40af;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }

        .info-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #1e293b;
            font-size: 1rem;
            font-weight: 600;
        }

        /* Academic Section */
        .academic-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .academic-card {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            padding: 1.25rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #bfdbfe;
        }

        .academic-label {
            color: #1e40af;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .academic-value {
            color: #1e293b;
            font-size: 1.25rem;
            font-weight: 700;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .profile-body {
                padding: 1.5rem;
            }

            .info-grid,
            .academic-grid {
                grid-template-columns: 1fr;
            }

            .profile-name {
                font-size: 1.5rem;
            }

            .profile-image {
                width: 120px;
                height: 120px;
            }

            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .header-left {
                flex-direction: column;
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
        <a href="logout.php" class="logout-link" title="<?php echo $_SESSION['student_name']; ?>">Log Out</a>
    </div>
</div>

<!-- Main Container -->
<div class="container">
    <div class="profile-card">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-image-wrapper">
                <?php 
                $varie=$_SESSION['student_id'];
                $stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_ID=?");
                $stmt->bind_param('s', $varie);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();
                echo '<img src="data:image/jpeg;base64,'.base64_encode($user['IMAGE']).'" class="profile-image" alt="Profile Picture" />'; 
                ?>
            </div>
            <h1 class="profile-name"><?php echo $student_name; ?></h1>
            <p class="profile-id">Student ID: <?php echo $student_id; ?></p>
            <span class="status-badge">
                <?php echo ($status == '-') ? 'Not Placed' : 'Internship at ' . $status; ?>
            </span>
        </div>

        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Personal Information -->
            <h2 class="section-title">Personal Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value"><?php echo $dob; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Gender</div>
                    <div class="info-value"><?php echo $gender; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo $st_email; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Contact Number</div>
                    <div class="info-value"><?php echo $contact_num; ?></div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Address</div>
                    <div class="info-value"><?php echo $address1; ?></div>
                </div>
            </div>

            <!-- Academic Information -->
            <h2 class="section-title">Academic Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Branch</div>
                    <div class="info-value"><?php echo $branch; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Passing Year</div>
                    <div class="info-value"><?php echo $pass; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Apply For</div>
                    <div class="info-value"><?php echo $apply; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Backlogs</div>
                    <div class="info-value"><?php echo ($backlogs == '') ? '0' : $backlogs; ?></div>
                </div>
            </div>

            <!-- Academic Performance -->
            <h2 class="section-title">Academic Performance</h2>
            <div class="academic-grid">
                <div class="academic-card">
                    <div class="academic-label">10th Percentage</div>
                    <div class="academic-value"><?php echo $tenth_per; ?>%</div>
                    <div class="info-label" style="margin-top: 0.5rem;">Year: <?php echo $tenth_pass; ?></div>
                </div>
                <div class="academic-card">
                    <div class="academic-label">12th Percentage</div>
                    <div class="academic-value"><?php echo $twelfth_per; ?>%</div>
                    <div class="info-label" style="margin-top: 0.5rem;">Year: <?php echo $twelfth_pass; ?></div>
                </div>
                <div class="academic-card">
                    <div class="academic-label">Current CGPA</div>
                    <div class="academic-value"><?php echo $cgpa; ?></div>
                    <div class="info-label" style="margin-top: 0.5rem;">Out of 10</div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
