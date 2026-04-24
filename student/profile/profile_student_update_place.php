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
if($_SESSION['user']=='student_int' )
{
    header("Location: index_student_intern.php");
}
if($_SESSION['user']=='company')
{
    header("Location: index_company.php");
}

$student_name="";
$dob="";
$st_email="";
$address1="";
$contact_num="";
$tenth_per="";
$twelfth_per="";
$cgpa="";
$st_password1="";
$st_password2="";
$errors = array();
require_once __DIR__ . '/../config/config.php';
$db = db_connect();

$vari=$_SESSION['student_id'];
$stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_ID=? LIMIT 1");
$stmt->bind_param('s', $vari);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$student_name=$user['STUDENT_NAME'];
$dob=$user['DOB'];
$st_email=$user['EMAIL'];
$address1=$user['ADDRESS'];
$contact_num=$user['CONTACT_NO'];
$tenth_per=$user['TENTH_PER'];
$twelfth_per=$user['TWELTH_PER'];
$cgpa=$user['CGPA'];

if (isset($_POST['profile_student_update_place'])) 
{
    $student_name = mysqli_real_escape_string($db, $_POST['student_name']);
    $dob = mysqli_real_escape_string($db, $_POST['dob']);
    $st_email = mysqli_real_escape_string($db, $_POST['st_email']);
    $address1 = mysqli_real_escape_string($db, $_POST['address1']);
    $contact_num = mysqli_real_escape_string($db, $_POST['contact_num']);
    $tenth_per = mysqli_real_escape_string($db, $_POST['tenth_per']);
    $twelfth_per = mysqli_real_escape_string($db, $_POST['twelfth_per']);
    $cgpa = mysqli_real_escape_string($db, $_POST['cgpa']);
    $st_password2 = mysqli_real_escape_string($db, $_POST['st_password2']);
    $st_password1 = mysqli_real_escape_string($db, $_POST['st_password1']);

    if(empty($st_password2) and empty($st_password1))
    {
    }
    else if(empty($st_password2))
    {
        array_push($errors, "Current password is required");   
    }
    else if(empty($st_password1))
    {
        array_push($errors, "New password is required"); 
    }
    else
    {
        if(!password_verify($st_password2, $user['S_PASSWORD']))
        {
            array_push($errors, "Current Password is incorrect"); 
        }
    }

    if (empty($student_name)) 
    { 
        array_push($errors, "Student Name is required"); 
    }
    if (empty($dob)) 
    { 
        array_push($errors, "Date of birth is required"); 
    }
    if (empty($st_email)) 
    { 
        array_push($errors, "Email is required"); 
    }
    if (empty($address1)) 
    { 
        array_push($errors, "Address is required"); 
    }
    if (empty($tenth_per)) 
    { 
        array_push($errors, "10th % is required"); 
    }
    if (empty($twelfth_per)) 
    { 
        array_push($errors, "12th % is required"); 
    }
    if (empty($cgpa)) 
    { 
        array_push($errors, "CGPA is required"); 
    }
    
    if (filter_var($st_email, FILTER_VALIDATE_EMAIL)) 
    {
    }
    else 
    {
        array_push($errors, "Email is not a valid email address"); 
    }
    if(strlen($contact_num)!=10)
    {
        array_push($errors, "Contact Number not correct"); 
    }
    if((int)$cgpa<1 or (int)$cgpa>10)
    {
        array_push($errors, "This CGPA not possible");
    }
    if((int)$tenth_per<1 or (int)$tenth_per>100)
    {
        array_push($errors, "This 10th % not possible");
    }
    if((int)$twelfth_per<1 or (int)$twelfth_per>100)
    {
        array_push($errors, "This 12th % not possible");
    }

    if (count($errors) == 0) 
    {
        if(empty($st_password2) and empty($st_password1))
        {
            $password=$user['S_PASSWORD'];
        }
        else
        {
            $password = password_hash($st_password1, PASSWORD_BCRYPT);
        }

        $stmt = $db->prepare("UPDATE STUDENT SET STUDENT_NAME=?, DOB=?, EMAIL=?, ADDRESS=?, CONTACT_NO=?, TENTH_PER=?, TWELTH_PER=?, CGPA=?, S_PASSWORD=? WHERE STUDENT_ID=?");
        $stmt->bind_param('ssssssssss', $student_name, $dob, $st_email, $address1, $contact_num, $tenth_per, $twelfth_per, $cgpa, $password, $vari);
        $stmt->execute();
        $stmt->close();

        header('location:index_student_placement.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | T&P SVNIT</title>
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

        .update-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .card-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
        }

        .card-body {
            padding: 2.5rem;
        }

        /* Error Messages */
        .error-container {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .error-container p {
            color: #dc2626;
            font-size: 0.875rem;
            margin: 0.25rem 0;
        }

        /* Section Headers */
        .section-header {
            color: #1e293b;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-header:first-of-type {
            margin-top: 0;
        }

        /* Info Box */
        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-box p {
            color: #1e40af;
            font-size: 0.875rem;
            line-height: 1.6;
            margin: 0;
        }

        /* Form Groups */
        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            color: #1e293b;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            background: white;
        }

        .input-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-group input:hover {
            border-color: #cbd5e1;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-grid-three {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
            margin-top: 1rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Footer Text */
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.95rem;
        }

        .footer-text a {
            color: #1e40af;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .footer-text a:hover {
            color: #3b82f6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-header h2 {
                font-size: 1.5rem;
            }

            .form-grid,
            .form-grid-three {
                grid-template-columns: 1fr;
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
    <div class="update-card">
        <div class="card-header">
            <h2>Update Profile</h2>
        </div>
        
        <div class="card-body">
            <form method="post" action="profile_student_update_place.php">
                <?php if (count($errors) > 0) : ?>
                <div class="error-container">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

                <!-- Personal Information -->
                <div class="section-header">Personal Information</div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Student Name</label>
                        <input type="text" name="student_name" value="<?php echo $student_name; ?>" placeholder="Full Name">
                    </div>

                    <div class="input-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo $dob; ?>">
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="section-header">Contact Information</div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="st_email" value="<?php echo $st_email; ?>" placeholder="your.email@example.com">
                    </div>

                    <div class="input-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact_num" value="<?php echo $contact_num; ?>" placeholder="10-digit mobile number">
                    </div>
                </div>

                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="address1" value="<?php echo $address1; ?>" placeholder="Complete Address">
                </div>

                <!-- Academic Information -->
                <div class="section-header">Academic Information</div>

                <div class="form-grid-three">
                    <div class="input-group">
                        <label>10th Percentage</label>
                        <input type="text" name="tenth_per" value="<?php echo $tenth_per; ?>" placeholder="e.g., 85.5">
                    </div>

                    <div class="input-group">
                        <label>12th Percentage</label>
                        <input type="text" name="twelfth_per" value="<?php echo $twelfth_per; ?>" placeholder="e.g., 90.5">
                    </div>

                    <div class="input-group">
                        <label>CGPA</label>
                        <input type="text" name="cgpa" value="<?php echo $cgpa; ?>" placeholder="e.g., 8.5">
                    </div>
                </div>

                <!-- Change Password -->
                <div class="section-header">Change Password (Optional)</div>

                <div class="info-box">
                    <p>💡 Leave password fields empty if you don't want to change your password. You must enter your current password to set a new one.</p>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Current Password</label>
                        <input type="password" name="st_password2" placeholder="Enter current password">
                    </div>

                    <div class="input-group">
                        <label>New Password</label>
                        <input type="password" name="st_password1" placeholder="Enter new password">
                    </div>
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="profile_student_update_place">Update Profile</button>
                </div>

                <p class="footer-text">
                    Done updating? <a href="index_student_placement.php">Go Back</a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>
