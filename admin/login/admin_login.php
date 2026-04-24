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
require_once __DIR__ . '/../config/config.php';
$errors = array();
$db = db_connect();
if (isset($_POST['login_admin']))
{
  require_once __DIR__ . '/../includes/csrf.php';
  if (!verify_csrf()) {
      die('CSRF token validation failed');
  }
  
  $admin_name = mysqli_real_escape_string($db, $_POST['admin_name']);
  $admin_id = mysqli_real_escape_string($db, $_POST['admin_id']);
  $admin_password = mysqli_real_escape_string($db, $_POST['admin_password']);

  if (empty($admin_name)) 
  {
    array_push($errors, "Username is required");
  }
  if (empty($admin_id)) 
  {
    array_push($errors, "Id is required");
  }
  if (empty($admin_password)) 
  {
    array_push($errors, "Password is required");
  }

    if (count($errors) == 0) 
    {
        $stmt = $db->prepare("SELECT ADMIN_ID, A_PASSWORD, ADMIN_NAME FROM admin WHERE ADMIN_NAME = ? AND ADMIN_ID = ? LIMIT 1");
        $stmt->bind_param('ss', $admin_name, $admin_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
                if (password_verify($admin_password, $row['A_PASSWORD'])) {
                        session_regenerate_id(true);
                        $_SESSION['admin_id'] = $row['ADMIN_ID'];
                        $_SESSION['admin_name'] = $row['ADMIN_NAME'];
                        $_SESSION['success'] = 'Admin logged in';
                        $_SESSION['user'] = 'admin';
                        header('location: ../admin/dashboard/index_admin.php');
                        exit;
                }
        }
        array_push($errors, 'Wrong username/password combination');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - T&P SVNIT</title>
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
            max-height: 200px;
            overflow-y: auto;
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

        /* Additional Info */
        .login-info {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #64748b;
        }

        .login-info strong {
            color: #1e293b;
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
                <h2>Admin Login</h2>
            </div>
            
            <div class="card-body">
                <form method="post" action="../admin/login/admin_login.php">
                    <?php require_once __DIR__ . '/../includes/csrf.php'; echo csrf_field(); ?>
                    <?php if (count($errors) > 0) : ?>
                    <div class="error-container">
                        <?php foreach ($errors as $error) : ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach ?>
                    </div>
                    <?php endif ?>

                    <div class="input-group">
                        <label>Admin Name</label>
                        <input type="text" name="admin_name" value="<?php echo $admin_name; ?>" placeholder="Enter your admin name">
                    </div>

                    <div class="input-group">
                        <label>Admin ID</label>
                        <input type="text" name="admin_id" value="<?php echo $admin_id; ?>" placeholder="Enter your admin ID">
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="admin_password" placeholder="Enter your password">
                    </div>

                    <div class="input-group">
                        <button type="submit" class="btn" name="login_admin">Sign In</button>
                    </div>

                    <p class="footer-text">
                        Not enrolled yet? <a href="admin_register.php">Sign up</a>
                    </p>

                    <div class="login-info">
                        <strong>Note:</strong> Use your registered admin credentials to login. If you don't have an account, please register first.
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
