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
$company_id="";
$company_name="";
$company_type="";
$website="";
$address2="";
$coming_date="";
require_once __DIR__ . '/../config/config.php';
$errors = array();
$db = db_connect();
if (isset($_POST['reg_comp']))
{
  require_once __DIR__ . '/../includes/csrf.php';
  if (!verify_csrf()) {
      die('CSRF token validation failed');
  }
  
  $company_id = mysqli_real_escape_string($db, $_POST['company_id']);
  $company_name = mysqli_real_escape_string($db, $_POST['company_name']);
  $website = mysqli_real_escape_string($db, $_POST['website']);
  $address2 = mysqli_real_escape_string($db, $_POST['address2']);
  $coming_date = mysqli_real_escape_string($db, $_POST['coming_date']);
  $c_password1 = mysqli_real_escape_string($db, $_POST['c_password1']);
  $c_password2 = mysqli_real_escape_string($db, $_POST['c_password2']);
  if (filter_var($website, FILTER_VALIDATE_URL)) 
  {
   //its good
  } 
  else 
  {
   array_push($errors, "Invalid URl"); 
  }
  if (empty($company_name)) 
  { 
    array_push($errors, "Company name is required"); 
  }
  if (empty($company_id)) 
  { 
    array_push($errors, "Company Id is required"); 
  }
  if (empty($address2)) 
  { 
    array_push($errors, "Address is required"); 
  }
  if (empty($coming_date)) 
  { 
    array_push($errors, "Coming Date is required"); 
  }
  if (empty($c_password1)) 
  { 
    array_push($errors, "Password is required"); 
  }
  if ($c_password1 != $c_password2) 
  {
  array_push($errors, "passwords do not match");
  }
  $stmt = $db->prepare("SELECT * FROM company WHERE COMPANY_NAME=? AND COMPANY_ID=? LIMIT 1");
  $stmt->bind_param('ss', $company_name, $company_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();
  
  if ($user)
  {
    if ($user['COMPANY_NAME'] === $company_name) 
    {
      array_push($errors, "Company name already exists");
    }
    if ($user['COMPANY_ID'] === $company_id) 
    {
      array_push($errors, "Company Id already exists");
    }
  }
    if (count($errors) == 0) 
    {
        $passwordHash = password_hash($c_password1, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO COMPANY (COMPANY_ID,COMPANY_NAME,C_PASSWORD,WEBSITE,ADDRESS,COMING_DATE) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param('ssssss', $company_id, $company_name, $passwordHash, $website, $address2, $coming_date);
        if ($stmt->execute()) {
                header('location: ../company/login/company_login.php');
                exit;
        } else {
                array_push($errors, 'Database error while registering company');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registration - T&P SVNIT</title>
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
            max-width: 550px;
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
        }

        .input-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-group input:hover {
            border-color: #cbd5e1;
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
            <h2>Company Registration</h2>
        </div>
        
        <div class="card-body">
            <form method="post" action="../company/register/company_register.php">
                <?php require_once __DIR__ . '/../includes/csrf.php'; echo csrf_field(); ?>
                <?php if (count($errors) > 0) : ?>
                <div class="error-container">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

                <div class="input-group">
                    <label>Company ID</label>
                    <input type="text" name="company_id" value="<?php echo $company_id; ?>" placeholder="Enter company ID">
                </div>

                <div class="input-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" value="<?php echo $company_name; ?>" placeholder="Enter company name">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="c_password1" placeholder="Enter password">
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="c_password2" placeholder="Confirm password">
                </div>

                <div class="input-group">
                    <label>Website</label>
                    <input type="text" name="website" value="<?php echo $website ?>" placeholder="https://example.com">
                </div>

                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="address2" value="<?php echo $address2 ?>" placeholder="Enter company address">
                </div>

                <div class="input-group">
                    <label>Visiting Date</label>
                    <input type="date" name="coming_date" value="<?php echo $coming_date ?>">
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="reg_comp">Sign Up</button>
                </div>

                <p class="footer-text">
                    Already registered? <a href="company_login.php">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>