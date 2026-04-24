<?php 
  session_start();
  if(isset($_SESSION['user']))
  {
    if($_SESSION['user']=='admin')
    {
      header("Location: index_admin.php");
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
  }
?>
<?php
$admin_id="";
$admin_name="";
$a_email="";
$post="";
$con_number="";
$dob2="";
$qualification="";
$errors = array();
require_once __DIR__ . '/../config/config.php';
$db = db_connect();
if (isset($_POST['reg_admin']))
{
  require_once __DIR__ . '/../includes/csrf.php';
  if (!verify_csrf()) {
      die('CSRF token validation failed');
  }
  
  $admin_id = mysqli_real_escape_string($db, $_POST['admin_id']);
  $admin_name = mysqli_real_escape_string($db, $_POST['admin_name']);
  $post = mysqli_real_escape_string($db, $_POST['post']);
  $con_number = mysqli_real_escape_string($db, $_POST['con_number']);
  $dob2 = mysqli_real_escape_string($db, $_POST['dob2']);
  $qualification = mysqli_real_escape_string($db, $_POST['qualification']);
  $a_email = mysqli_real_escape_string($db, $_POST['a_email']);
  $admin_password1 = mysqli_real_escape_string($db, $_POST['admin_password1']);
  $admin_password2 = mysqli_real_escape_string($db, $_POST['admin_password2']);

  if (empty($admin_name)) 
  { 
    array_push($errors, "Username is required"); 
  }
  if (empty($admin_id)) 
  { 
    array_push($errors, "Id is required"); 
  }
  if (empty($a_email)) 
  { 
    array_push($errors, "Email is required"); 
  }
  if (empty($post)) 
  { 
    array_push($errors, "Post is required"); 
  }
  if (empty($con_number)) 
  { 
    array_push($errors, "Contact details required"); 
  }
  if (empty($qualification)) 
  { 
    array_push($errors, "fill qualification Block"); 
  }
  if (empty($dob2)) 
  { 
    array_push($errors, "Date of birth is required"); 
  }
  if (empty($admin_password1)) 
  { 
    array_push($errors, "Password is required"); 
  }
  if ($admin_password1 != $admin_password2) 
  {
    array_push($errors, "passwords do not match");
  }
  if (filter_var($a_email, FILTER_VALIDATE_EMAIL)) 
  {
  }
  else 
  {
    array_push($errors, "email is not a valid email address"); 
  }
  $stmt1 = $db->prepare("SELECT * FROM admin LIMIT 1");
  $stmt1->execute();
  $result1 = $stmt1->get_result();
  $user1 = $result1->fetch_assoc();
  $stmt1->close();

  if(!($user1))
  {
    $stmt = $db->prepare("SELECT * FROM admin WHERE ADMIN_NAME=? AND ADMIN_ID=? LIMIT 1");
    $stmt->bind_param('ss', $admin_name, $admin_id);
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
        array_push($errors, "Admin Id already exists");
      }
    }
        if (count($errors) == 0) 
        {
            $passwordHash = password_hash($admin_password1, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO admin (ADMIN_ID,ADMIN_NAME,A_PASSWORD,POST,EMAIL,CONTACT_NO,DOB,QUALIFICATION) VALUES(?,?,?,?,?,?,?,?)");
            $stmt->bind_param('ssssssss', $admin_id, $admin_name, $passwordHash, $post, $a_email, $con_number, $dob2, $qualification);
            if ($stmt->execute()) {
                    header('location: ../admin/login/admin_login.php');
                    exit;
            } else {
                    array_push($errors, 'Database error while creating admin');
            }
        }
  }
  else
  {
    array_push($errors, "Only Admin can register new admin");
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
            max-width: 700px;
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
            max-height: 200px;
            overflow-y: auto;
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
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

        .form-full {
            grid-column: 1 / -1;
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
            </div>
            
            <div class="card-body">
                <form method="post" action="../admin/register/admin_register.php">
                    <?php require_once __DIR__ . '/../includes/csrf.php'; echo csrf_field(); ?>
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
                            <label>Admin ID</label>
                            <input type="text" name="admin_id" value="<?php echo $admin_id; ?>" placeholder="e.g., ADMIN001">
                        </div>

                        <div class="input-group">
                            <label>Admin Name</label>
                            <input type="text" name="admin_name" value="<?php echo $admin_name; ?>" placeholder="Full Name">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="input-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob2" value="<?php echo $dob2; ?>">
                        </div>

                        <div class="input-group">
                            <label>Post</label>
                            <input type="text" name="post" value="<?php echo $post; ?>" placeholder="e.g., Faculty, Staff">
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="section-header">Contact Information</div>

                    <div class="form-grid">
                        <div class="input-group">
                            <label>Email</label>
                            <input type="email" name="a_email" value="<?php echo $a_email ?>" placeholder="admin@svnit.ac.in">
                        </div>

                        <div class="input-group">
                            <label>Contact Number</label>
                            <input type="text" name="con_number" value="<?php echo $con_number; ?>" placeholder="10-digit mobile number">
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="section-header">Additional Information</div>

                    <div class="input-group form-full">
                        <label>Qualification</label>
                        <input type="text" name="qualification" value="<?php echo $qualification; ?>" placeholder="e.g., B.Tech, M.Tech, Ph.D">
                    </div>

                    <!-- Account Security -->
                    <div class="section-header">Account Security</div>

                    <div class="form-grid">
                        <div class="input-group">
                            <label>Password</label>
                            <input type="password" name="admin_password1" placeholder="Enter password">
                        </div>

                        <div class="input-group">
                            <label>Confirm Password</label>
                            <input type="password" name="admin_password2" placeholder="Confirm password">
                        </div>
                    </div>

                    <div class="input-group">
                        <button type="submit" class="btn" name="reg_admin">Complete Registration</button>
                    </div>

                    <p class="footer-text">
                        Already registered? <a href="admin_login.php">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
