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
if (isset($_POST['login_company']))
{
  require_once __DIR__ . '/../includes/csrf.php';
  if (!verify_csrf()) {
      die('CSRF token validation failed');
  }
  
  $company_name = mysqli_real_escape_string($db, $_POST['company_name']);
  $company_id = mysqli_real_escape_string($db, $_POST['company_id']);
  $c_password = mysqli_real_escape_string($db, $_POST['c_password']);
  if (empty($company_name)) 
  {
    array_push($errors, "Company name is required");
  }
  if (empty($company_id)) 
  {
    array_push($errors, "Company Id is required");
  }
  if (empty($c_password)) 
  {
    array_push($errors, "Password is required");
  }
    if (count($errors) == 0) 
    {
        $stmt = $db->prepare("SELECT COMPANY_ID, C_PASSWORD, COMPANY_NAME, APPROVAL FROM company WHERE COMPANY_NAME = ? AND COMPANY_ID = ? LIMIT 1");
        $stmt->bind_param('ss', $company_name, $company_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
                if ($row['APPROVAL'] === 'rejected') {
                        array_push($errors, 'Company is not valid');
                } elseif (password_verify($c_password, $row['C_PASSWORD'])) {
                        session_regenerate_id(true);
                        $_SESSION['company_name'] = $row['COMPANY_NAME'];
                        $_SESSION['company_id'] = $row['COMPANY_ID'];
                        $_SESSION['user'] = 'company';
                        $_SESSION['success'] = 'Company logged in';
                        header('location:index_company.php');
                        exit;
                } else {
                        array_push($errors, 'Wrong username/password combination');
                }
        } else {
                array_push($errors, 'Wrong username/password combination');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Login - T&P SVNIT</title>
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

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
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
    <div class="login-card">
        <div class="card-header">
            <h2>Company Login</h2>
        </div>
        
        <div class="card-body">
            <form method="post" action="../company/login/company_login.php">
                <?php require_once __DIR__ . '/../includes/csrf.php'; echo csrf_field(); ?>
                <?php if (count($errors) > 0) : ?>
                <div class="error-container">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

                <div class="input-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" placeholder="Enter company name">
                </div>

                <div class="input-group">
                    <label>Company ID</label>
                    <input type="text" name="company_id" placeholder="Enter company ID">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="c_password" placeholder="Enter password">
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="login_company">Sign In</button>
                </div>

                <p class="footer-text">
                    Not enrolled yet? <a href="company_register.php">Sign up</a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>