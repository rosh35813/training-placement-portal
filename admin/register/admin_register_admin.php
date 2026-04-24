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

  if($_SESSION['user']=='company')

  {

    header("Location: index_company.php");

  }

?>

<?php

require_once __DIR__ . '/../config/config.php';

$admin_id="";

$admin_name="";

$a_email="";

$post="";

$con_number="";

$dob2="";

$qualification="";

$errors = array();

$db = db_connect();



if (isset($_POST['admin_reg_admin']))

{

  $admin_id = mysqli_real_escape_string($db, $_POST['admin_id']);

  $admin_name = mysqli_real_escape_string($db, $_POST['admin_name']);

  $post = mysqli_real_escape_string($db, $_POST['post']);

  $con_number = mysqli_real_escape_string($db, $_POST['con_number']);

  $dob2 = mysqli_real_escape_string($db, $_POST['dob2']);

  $qualification = mysqli_real_escape_string($db, $_POST['qualification']);

  $a_email = mysqli_real_escape_string($db, $_POST['a_email']);

  $admin_password1 = mysqli_real_escape_string($db, $_POST['admin_password1']);

  $admin_password2 = mysqli_real_escape_string($db, $_POST['admin_password2']);



  if (empty($admin_id)) 

  { 

    array_push($errors, "Admin ID is required"); 

  }

  if (empty($admin_name)) 

  { 

    array_push($errors, "Admin Name is required"); 

  }

  if (empty($a_email)) 

  { 

    array_push($errors, "Email is required"); 

  }

  if (empty($post)) 

  { 

    array_push($errors, "Post/Designation is required"); 

  }

  if (empty($con_number)) 

  { 

    array_push($errors, "Contact Number is required"); 

  }

  if (empty($qualification)) 

  { 

    array_push($errors, "Qualification is required"); 

  }

  if (empty($dob2)) 

  { 

    array_push($errors, "Date of Birth is required"); 

  }

  if (empty($admin_password1)) 

  { 

    array_push($errors, "Password is required"); 

  }

  if ($admin_password1 != $admin_password2) 

  {

    array_push($errors, "Passwords do not match");

  }

  

  if (!filter_var($a_email, FILTER_VALIDATE_EMAIL)) 

  {

    array_push($errors, "Email is not a valid email address"); 

  }

  

  if(strlen($con_number)!=10)

  {

    array_push($errors, "Contact Number must be 10 digits"); 

  }



  $stmt = $db->prepare("SELECT * FROM admin WHERE ADMIN_NAME=? OR ADMIN_ID=? OR EMAIL=? LIMIT 1");

  $stmt->bind_param('sss', $admin_name, $admin_id, $a_email);

  $stmt->execute();

  $result = $stmt->get_result();

  $user = $result->fetch_assoc();

  $stmt->close();

  

  if ($user)

  { 

    if ($user['ADMIN_NAME'] === $admin_name) 

    {

      array_push($errors, "Username already exists");

    }

    if ($user['ADMIN_ID'] === $admin_id) 

    {

      array_push($errors, "Admin ID already exists");

    }

    if ($user['EMAIL'] === $a_email) 

    {

      array_push($errors, "Email already registered");

    }

  }

  

  if (count($errors) == 0) 

  {

    $password = password_hash($admin_password1, PASSWORD_BCRYPT);

    $stmt = $db->prepare("INSERT INTO admin (ADMIN_ID,ADMIN_NAME,A_PASSWORD,POST,EMAIL,CONTACT_NO,DOB,QUALIFICATION) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param('ssssssss', $admin_id, $admin_name, $password, $post, $a_email, $con_number, $dob2, $qualification);

    $stmt->execute();

    $stmt->close();

    $_SESSION['success'] = "Admin registered successfully";

    header('location: ../admin/dashboard/index_admin.php');

  }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Registration - T&P SVNIT</title>

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



        /* Main Container */

        .container {

            flex: 1;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 2rem;

        }



        .registration-card {

            background: white;

            border-radius: 16px;

            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);

            width: 100%;

            max-width: 900px;

            overflow: hidden;

        }



        .card-header {

            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);

            color: white;

            padding: 2rem;

            text-align: center;

        }



        .card-header h2 {

            font-size: 1.75rem;

            font-weight: 600;

        }



        .card-header p {

            margin-top: 0.5rem;

            opacity: 0.95;

            font-size: 0.95rem;

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

            max-height: 300px;

            overflow-y: auto;

        }



        .error-container p {

            color: #dc2626;

            font-size: 0.875rem;

            margin: 0.25rem 0;

            display: flex;

            align-items: center;

            gap: 0.5rem;

        }



        .error-container p::before {

            content: "⚠";

            font-size: 1rem;

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



        .input-group input,

        .input-group select {

            width: 100%;

            padding: 0.875rem 1rem;

            border: 2px solid #e2e8f0;

            border-radius: 8px;

            font-size: 0.95rem;

            transition: all 0.3s;

            font-family: 'Inter', sans-serif;

            background: white;

        }



        .input-group input:focus,

        .input-group select:focus {

            outline: none;

            border-color: #dc2626;

            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);

        }



        .input-group input:hover,

        .input-group select:hover {

            border-color: #cbd5e1;

        }



        .input-group select {

            cursor: pointer;

            appearance: none;

            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");

            background-repeat: no-repeat;

            background-position: right 0.75rem center;

            background-size: 1.25rem;

            padding-right: 3rem;

        }



        /* Form Grid */

        .form-grid {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 1.5rem;

        }



        /* Button */

        .btn {

            width: 100%;

            padding: 1rem;

            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);

            color: white;

            border: none;

            border-radius: 8px;

            font-size: 1rem;

            font-weight: 600;

            cursor: pointer;

            transition: all 0.3s;

            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);

            margin-top: 1rem;

        }



        .btn:hover {

            transform: translate(-2px);

            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);

        }



        .btn:active {

            transform: translate(0);

        }



        /* Footer Text */

        .footer-text {

            text-align: center;

            margin-top: 1.5rem;

            color: #64748b;

            font-size: 0.95rem;

        }



        .footer-text a {

            color: #dc2626;

            text-decoration: none;

            font-weight: 600;

            transition: color 0.3s;

        }



        .footer-text a:hover {

            color: #ef4444;

        }



        /* Info Box */

        .info-box {

            background: #eff6ff;

            border-left: 4px solid #3b82f6;

            padding: 1rem;

            border-radius: 8px;

            margin-bottom: 1.5rem;

            font-size: 0.9rem;

            color: #1e40af;

        }



        .info-box strong {

            display: block;

            margin-bottom: 0.25rem;

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



            .form-grid {

                grid-template-columns: 1fr;

            }

        }

    </style>

</head>

<body>



<!-- Top Header -->

<div class="top-header">

    <div class="header-content">

        <div class="logo">

            <a href="svnit.php">

                <img src="../assets/images/Svnit_logo.png" alt="SVNIT SURAT" />

            </a>

        </div>

        <div class="header-text">

            Training &amp; Placement, SVNIT SURAT

        </div>

    </div>

</div>



<!-- Main Container -->

<div class="container">

    <div class="registration-card">

        <div class="card-header">

            <h2>Admin Registration</h2>

            <p>Register new Training &amp; Placement Administrator</p>

        </div>

        

        <div class="card-body">

            <div class="info-box">

                <strong>Administrator Access</strong>

                This form is for registering new T&P administrators with full system access privileges.

            </div>



            <form method="post" action="admin_register_admin.php">

                <?php if (count($errors) > 0) : ?>

                <div class="error-container">

                    <?php foreach ($errors as $error) : ?>

                        <p><?php echo $error; ?></p>

                    <?php endforeach ?>

                </div>

                <?php endif ?>



                <!-- Basic Information -->

                <div class="section-header">Basic Information</div>



                <div class="form-grid">

                    <div class="input-group">

                        <label>Admin ID</label>

                        <input type="text" name="admin_id" value="<?php echo $admin_id; ?>" placeholder="e.g., ADM001" required>

                    </div>



                    <div class="input-group">

                        <label>Admin Name</label>

                        <input type="text" name="admin_name" value="<?php echo $admin_name; ?>" placeholder="Full Name" required>

                    </div>

                </div>



                <div class="form-grid">

                    <div class="input-group">

                        <label>Post/Designation</label>

                        <select name="post" required>

                            <option value="">Select Designation</option>

                            <option value="Training & Placement Officer" <?php echo ($post == 'Training & Placement Officer') ? 'selected' : ''; ?>>Training & Placement Officer</option>

                            <option value="Assistant TPO" <?php echo ($post == 'Assistant TPO') ? 'selected' : ''; ?>>Assistant TPO</option>

                            <option value="Administrative Officer" <?php echo ($post == 'Administrative Officer') ? 'selected' : ''; ?>>Administrative Officer</option>

                            <option value="Coordinator" <?php echo ($post == 'Coordinator') ? 'selected' : ''; ?>>Coordinator</option>

                            <option value="Manager" <?php echo ($post == 'Manager') ? 'selected' : ''; ?>>Manager</option>

                        </select>

                    </div>



                    <div class="input-group">

                        <label>Date of Birth</label>

                        <input type="date" name="dob2" value="<?php echo $dob2; ?>" required>

                    </div>

                </div>



                <div class="input-group">

                    <label>Qualification</label>

                    <input type="text" name="qualification" value="<?php echo $qualification; ?>" placeholder="e.g., M.Tech, MBA, Ph.D." required>

                </div>



                <!-- Contact Information -->

                <div class="section-header">Contact Information</div>



                <div class="form-grid">

                    <div class="input-group">

                        <label>Email</label>

                        <input type="email" name="a_email" value="<?php echo $a_email; ?>" placeholder="admin@svnit.ac.in" required>

                    </div>



                    <div class="input-group">

                        <label>Contact Number</label>

                        <input type="number" name="con_number" value="<?php echo $con_number; ?>" placeholder="10-digit mobile number" required>

                    </div>

                </div>



                <!-- Account Security -->

                <div class="section-header">Account Security</div>



                <div class="form-grid">

                    <div class="input-group">

                        <label>Password</label>

                        <input type="password" name="admin_password1" placeholder="Enter secure password" required>

                    </div>



                    <div class="input-group">

                        <label>Confirm Password</label>

                        <input type="password" name="admin_password2" placeholder="Confirm password" required>

                    </div>

                </div>



                <div class="input-group">

                    <button type="submit" class="btn" name="admin_reg_admin">Register Administrator</button>

                </div>



                <p class="footer-text">

                    Return to <a href="index_admin.php">Admin Dashboard</a>

                </p>

            </form>

        </div>

    </div>

</div>



</body>

</html>