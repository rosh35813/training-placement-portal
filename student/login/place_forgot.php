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
$st_mail="";
require_once __DIR__ . '/../config/config.php';
$errors = array();
$positives=array();
$db = db_connect();

if (isset($_POST['place_forgot']))
{
    $student_id = $_POST['student_id'] ?? '';
    if (empty($student_id)) 
    {
        array_push($errors, "Student ID is required");
    }
    
    $stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_ID=? LIMIT 1");
    $stmt->bind_param('s', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if($user)
    {
        $st_mail = $user['EMAIL'];
        $student_name = $user['STUDENT_NAME'];

        // Create token and store in password_resets
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour
        $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        if (!$stmt) {
            array_push($errors, 'Database error: ' . $db->error);
        } else {
            $stmt->bind_param('sss', $student_id, $token, $expires);
            $stmt->execute();
            $stmt->close();
        }
        
        if (count($errors) === 0) {

        // Build reset URL. Prefer `BASE_URL` from config if set (handy for shared hosts).
        if (defined('BASE_URL') && !empty(BASE_URL)) {
            $resetLink = rtrim(BASE_URL, '/') . '/reset_password.php?token=' . $token;
        } else {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $resetLink = $scheme . '://' . $host . $path . '/reset_password.php?token=' . $token;
        }

        $email_subject = 'Password reset for ' . $student_name;
        $email_body = 'Click the following link to reset your password (valid 1 hour):<br><a href="' . htmlspecialchars($resetLink) . '">' . htmlspecialchars($resetLink) . '</a>';

        $result = send_email($st_mail, $email_subject, $email_body, SMTP_EMAIL);
        if ($result['success']) {
            array_push($positives, 'A password reset link has been sent to your email');
        } else {
            array_push($errors, 'Unable to send reset email: ' . $result['message']);
        }
        }
    }
    else
    {
        array_push($errors, "Student does not exist");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | T&P SVNIT</title>
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

        .forgot-card {
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

        .card-header-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .card-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.5;
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

        /* Success Messages */
        .success-container {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .success-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .success-container p {
            color: #059669;
            font-size: 0.875rem;
            margin: 0.25rem 0;
            font-weight: 500;
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        .back-link:hover {
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

            .card-header-icon {
                font-size: 2.5rem;
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
    <div class="forgot-card">
        <div class="card-header">
            <div class="card-header-icon">🔐</div>
            <h2>Forgot Password?</h2>
            <p>Enter your Student ID and we'll send a new password to your registered email</p>
        </div>
        
        <div class="card-body">
            <form method="post" action="place_forgot.php">
                <?php if (count($errors) > 0) : ?>
                <div class="error-container">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

                <?php if (count($positives) > 0) : ?>
                <div class="success-container">
                    <span class="success-icon">✅</span>
                    <div>
                        <?php foreach ($positives as $positive) : ?>
                            <p><?php echo $positive; ?></p>
                        <?php endforeach ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (count($positives) == 0) : ?>
                <div class="info-box">
                    <p>💡 A new password will be generated and sent to your registered email address. You can change it later in your profile settings.</p>
                </div>
                <?php endif ?>

                <?php if (count($positives) == 0) : ?>
                <div class="input-group">
                    <label>Student ID</label>
                    <input type="text" name="student_id" placeholder="Enter your student ID" value="<?php echo $student_id; ?>">
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="place_forgot">Reset Password</button>
                </div>
                <?php endif ?>

                <div class="links-section">
                    <?php if (count($positives) > 0) : ?>
                        <p>
                            Check your email and <a href="student_login_placement.php">login with new password</a>
                        </p>
                    <?php else : ?>
                        <p>
                            Remember your password? <a href="student_login_placement.php">Back to Login</a>
                        </p>
                    <?php endif ?>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
