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
$company_id="";
$company_name="";
$company_type="";
$student_id="";
$student_name="";
$apply_for="";
$branch="";
$status="";
$approval="";
$status_company="";
$stipend="";
$package="";
$st_mail="";

$errors = array();
$positives = array();
require_once __DIR__ . '/../config/config.php';
$db = db_connect();

if (isset($_POST['admin_student_status'])) 
{
  $company_name = $_POST['company_name'];
  $student_id = $_POST['student_id'];

  if (empty($student_id)) 
  { 
    array_push($errors, "Student ID is required"); 
  }
  if ($company_name=='Company Name' || empty($company_name)) 
  { 
    array_push($errors, "Please select a company"); 
  }
  
  $user_check_query = "SELECT * FROM student WHERE STUDENT_ID=? LIMIT 1";
  $stmt = $db->prepare($user_check_query);
  $stmt->bind_param('s', $student_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  
  if($user)
  {
    $student_name = $user['STUDENT_NAME'];
    $apply_for = $user['APPLY_FOR'];
    $branch = $user['BRANCH'];
    $status = $user['STATUS'];
    $st_mail = $user['EMAIL'];
    
    if($status=='S' && $apply_for=='Internship')
    {
      array_push($errors, "Student already got an Internship");
    }
    else if($status=='S' && $apply_for=='Placement')
    {
      array_push($errors, "Student already got Placed");
    }
    else
    {
      $user_check_query = "SELECT * FROM company WHERE COMPANY_NAME=? LIMIT 1";
      $stmt = $db->prepare($user_check_query);
      $stmt->bind_param('s', $company_name);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();
      
      if($user)
      {
        $approval = $user['APPROVAL'];
        $status_company = $user['STATUS'];
        $company_id = $user['COMPANY_ID'];

        $user_check_query = "SELECT * FROM companybranch WHERE COMPANY_NAME=? AND C_TYPE=? AND BRANCH=? LIMIT 1";
        $stmt = $db->prepare($user_check_query);
        $stmt->bind_param('sss', $company_name, $apply_for, $branch);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if($user)
        {
          $stipend = $user['MAX_STIPEND'];
          $package = $user['MAX_SALARY'];

          if (count($errors) == 0) 
          {
            require 'class.smtp.php';
            require 'class.phpmailer.php';
            require 'credential.php';
            $mail = new PHPMailer;

            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL;
            $mail->Password = PASS;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom(EMAIL, 'Placement and Training Cell SVNIT SURAT');
            $mail->addAddress($st_mail);
            $mail->isHTML(true);
            $mail->Subject = 'Congratulations '.$student_name.'!';
            $mail->Body = 'You got selected for the company: '.$company_name;
            $mail->AltBody = 'You got selected for the company: '.$company_name;

            $mail->send();

            if($apply_for=='Internship')
            {
              $query = "INSERT INTO student_internship (STUDENT_ID,COMPANY_ID,STUDENT_NAME,COMPANY_NAME,STIPEND) VALUES(?,?,?,?,?)";
              $stmt = $db->prepare($query);
              $stmt->bind_param('sssss', $student_id, $company_id, $student_name, $company_name, $stipend);
              $stmt->execute();
              
              $query = "UPDATE student SET STATUS=? WHERE STUDENT_ID=?";
              $stmt = $db->prepare($query);
              $status_val = 'S';
              $stmt->bind_param('ss', $status_val, $student_id);
              $stmt->execute();
              
              $query = "UPDATE company SET STATUS=? WHERE COMPANY_NAME=?";
              $stmt = $db->prepare($query);
              $status_val = 'visited';
              $stmt->bind_param('ss', $status_val, $company_name);
              $stmt->execute();
              
              array_push($positives, "Student successfully marked as Interned! Confirmation email sent to ".$st_mail);
            }
            else
            {
              $query = "INSERT INTO student_placement (STUDENT_ID,COMPANY_ID,STUDENT_NAME,COMPANY_NAME,PACKAGE) VALUES(?,?,?,?,?)";
              $stmt = $db->prepare($query);
              $stmt->bind_param('sssss', $student_id, $company_id, $student_name, $company_name, $package);
              $stmt->execute();
              
              $query = "UPDATE student SET STATUS=? WHERE STUDENT_ID=?";
              $stmt = $db->prepare($query);
              $status_val = 'S';
              $stmt->bind_param('ss', $status_val, $student_id);
              $stmt->execute();
              
              $query = "UPDATE company SET STATUS=? WHERE COMPANY_NAME=?";
              $stmt = $db->prepare($query);
              $status_val = 'visited';
              $stmt->bind_param('ss', $status_val, $company_name);
              $stmt->execute();
              
              array_push($positives, "Student successfully marked as Placed! Confirmation email sent to ".$st_mail);
            }
          }
        }
        else
        {
          array_push($errors, "Company does not recruit for this branch or ".$apply_for);
        }
      }
      else
      {
        array_push($errors, "Company not found in database");
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
    <title>Update Student Status - T&P SVNIT</title>
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
            justify-content: space-between;
            gap: 1rem;
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

        .logout-btn {
            padding: 0.5rem 1.25rem;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        /* Main Container */
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .status-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 700px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
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

        /* Success Messages */
        .success-container {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            animation: slideDown 0.3s ease-out;
        }

        .success-container p {
            color: #065f46;
            font-size: 0.95rem;
            margin: 0.25rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .success-container p::before {
            content: "✓";
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background: #059669;
            color: white;
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: bold;
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
            animation: slideDown 0.3s ease-out;
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

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
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

        /* Button */
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
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
            color: #059669;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .footer-text a:hover {
            color: #10b981;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
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

            .header-content {
                padding: 0 1rem;
            }

            .header-text {
                font-size: 0.9rem;
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
        <a href="logout.php" class="logout-btn" title="<?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin'; ?>">Log Out</a>
    </div>
</div>

<!-- Main Container -->
<div class="container">
    <div class="status-card">
        <div class="card-header">
            <h2>Update Student Status</h2>
            <p>Mark students as Placed or Interned</p>
        </div>
        
        <div class="card-body">
            <div class="info-box">
                <strong>📋 Action Information</strong>
                This action will update the student's status and send a confirmation email to their registered address.
            </div>

            <form method="post" action="admin_student_status.php">
                <?php if (count($positives) > 0) : ?>
                <div class="success-container">
                    <?php foreach ($positives as $positive) : ?>
                        <p><?php echo $positive; ?></p>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

                <?php if (count($errors) > 0) : ?>
                <div class="error-container">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

                <div class="input-group">
                    <label>Student ID</label>
                    <input type="text" name="student_id" value="<?php echo $student_id; ?>" placeholder="e.g., 2021CSE001" required>
                </div>

                <div class="input-group">
                    <label>Company Name 
                        <span class="status-badge status-approved">Approved Companies Only</span>
                    </label>
                    <select name="company_name" required>
                        <option value="">Select Company</option>
                        <?php
                        $user_check_query1 = "SELECT COMPANY_NAME FROM company WHERE APPROVAL=? ORDER BY COMPANY_NAME ASC";
                        $stmt = $db->prepare($user_check_query1);
                        $approval = 'approved';
                        $stmt->bind_param('s', $approval);
                        $stmt->execute();
                        $result1 = $stmt->get_result();

                        while($count = $result1->fetch_array(MYSQLI_ASSOC))
                        {
                            $selected = ($company_name == $count["COMPANY_NAME"]) ? 'selected' : '';
                            echo '<option value="'.$count["COMPANY_NAME"].'" '.$selected.'>'.$count["COMPANY_NAME"].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="admin_student_status">Update Student Status</button>
                </div>

                <p class="footer-text">
                    <a href="index_admin.php">← Back to Admin Dashboard</a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>