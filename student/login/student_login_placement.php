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

$student_id="";
$student_name="";
$apply="";
$st_password="";
$errors = array();
require_once __DIR__ . '/../config/config.php';
$db = db_connect();

if (isset($_POST['login_student_place']))
{
    require_once __DIR__ . '/../includes/csrf.php';
    if (!verify_csrf()) {
        die('CSRF token validation failed');
    }
    
    $student_name = mysqli_real_escape_string($db, $_POST['student_name']);
    $student_id = mysqli_real_escape_string($db, $_POST['student_id']);
    $apply = mysqli_real_escape_string($db, $_POST['apply']);
    $st_password = mysqli_real_escape_string($db, $_POST['st_password']);

    if (empty($student_name)) 
    {
        array_push($errors, "Student Name is required");
    }
    if (empty($student_id)) 
    {
        array_push($errors, "Student ID is required");
    }
    if (empty($apply)) 
    {
        array_push($errors, "Apply for is required");
    }
    if (empty($st_password)) 
    {
        array_push($errors, "Password is required");
    }
    if(($apply!='placement' and $apply!='Placement' ))
    {
        array_push($errors, "You entered incorrect info for Apply");
    }

    if (count($errors) == 0 ) 
    {
        $stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_NAME=? AND STUDENT_ID=? AND APPLY_FOR=?");
        $stmt->bind_param('sss', $student_name, $student_id, $apply);
        $stmt->execute();
        $results = $stmt->get_result();
        
        if ($results->num_rows == 1 )
        {
            $user = $results->fetch_assoc();
            if (password_verify($st_password, $user['S_PASSWORD'])) {
                session_regenerate_id(true);
                $_SESSION['student_name'] = $student_name;
                $_SESSION['success'] = "placement";
                $_SESSION['student_id'] = $student_id;
                $_SESSION['user'] = "student_place";
                header('location: ../student/dashboard/index_student_placement.php');
            } else {
                array_push($errors, "Wrong username/password combination or Not Registered yet");
            }
        }
        else 
        {
            array_push($errors, "Wrong username/password combination or Not Registered yet");
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Placement | T&P SVNIT</title>
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
            margin-bottom: 0.5rem;
        }

        .card-header p {
            font-size: 0.95rem;
            opacity: 0.95;
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

        .input-group input:read-only {
            background: #f8fafc;
            color: #64748b;
            cursor: not-allowed;
        }

        /* Info Badge */
        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #dbeafe;
            color: #1e40af;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .info-icon {
            font-size: 1rem;
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

        /* Links Section */
        .links-section {
            margin-top: 1.5rem;
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
        }

        .links-section a {
            color: #1e40af;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .links-section a:hover {
            color: #3b82f6;
        }

        .divider {
            margin: 1rem 0;
            color: #cbd5e1;
        }

        .forgot-link {
            display: inline-block;
            margin-top: 0.75rem;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .forgot-link:hover {
            background: #e2e8f0;
            color: #1e40af;
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
            <h2>Student Login</h2>
            <p>Access your placement portal</p>
        </div>
        
        <div class="card-body">
            <div class="info-badge">
                <span class="info-icon">💼</span>
                <span>Placement Portal Login</span>
            </div>

            <form method="post" action="../student/login/student_login_placement.php">
                <?php require_once __DIR__ . '/../includes/csrf.php'; echo csrf_field(); ?>
                <?php if (count($errors) > 0) : ?>
                <div class="error-container">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

                <div class="input-group">
                    <label>Student Name</label>
                    <input type="text" name="student_name" placeholder="Enter your full name" value="<?php echo $student_name; ?>">
                </div>

                <div class="input-group">
                    <label>Student ID</label>
                    <input type="text" name="student_id" placeholder="Enter your student ID" value="<?php echo $student_id; ?>">
                </div>

                <div class="input-group">
                    <label>Apply For</label>
                    <input type="text" name="apply" value="Placement" readonly>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="st_password" placeholder="Enter your password">
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="login_student_place">Sign In</button>
                </div>

                <div class="links-section">
                    <p>
                        Not enrolled yet? <a href="student_register.php">Sign up</a>
                    </p>
                    <div class="divider">•</div>
                    <a href="place_forgot.php" class="forgot-link">🔑 Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
