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

$company_id="";
$company_name="";
$company_type="";
$branch1="";
$branch2="";
$cgpa1="";
$cgpa2="";
$backlogs1="";
$backlogs2="";
$website="";
$address2="";
$coming_date="";
$absent="";
$status="";
$branch_st="";
$errors = array();
$positives=array();
require_once __DIR__ . '/../config/config.php';
$db = db_connect();

if (isset($_POST['apply_intern']))
{
    require_once __DIR__ . '/../includes/csrf.php';
    if (!verify_csrf()) {
        die('CSRF token validation failed');
    }
    
    $company_name = mysqli_real_escape_string($db, $_POST['company_name']);
    $vari=$_SESSION['student_id'];

    if ($company_name=='Company Name') 
    { 
        array_push($errors, "No Company is Coming"); 
    }

    $stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_ID=? LIMIT 1");
    $stmt->bind_param('s', $vari);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    $student_name=$user['STUDENT_NAME'];
    $branch1=$user['BRANCH'];
    $cgpa1=$user['CGPA'];
    $cgpa1=(double)$cgpa1;
    $backlogs1=$user['BACKLOGS'];
    $backlogs1=(int)$backlogs1;
    $absent=$user['ABSENT'];
    $absent=(int)$absent;
    $status=$user['STATUS'];
    $apply_count=$user['APPLY_COUNT'];

    if($status=='NS')
    {
        $stmt = $db->prepare("SELECT * FROM companybranch inner join company on companybranch.COMPANY_NAME=company.COMPANY_NAME where company.APPROVAL=? and companybranch.C_TYPE=? AND companybranch.BRANCH=? AND company.STATUS=? and company.COMPANY_NAME=? LIMIT 1");
        $approval = 'approved';
        $c_type = 'Internship';
        $c_status = 'visiting';
        $stmt->bind_param('sssss', $approval, $c_type, $branch1, $c_status, $company_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        $cgpa2=$user['MIN_CGPA'];
        $cgpa2=(double)$cgpa2;
        $backlogs2=$user['MAX_BACKLOGS'];
        $backlogs2=(int)$backlogs2;

        if($cgpa1>=$cgpa2)
        {
        }
        else
        {
            array_push($errors, "CGPA is less than company requirement"); 
        }
        if($backlogs1<=$backlogs2)
        {
        }
        else
        {
            array_push($errors, "Backlogs are more than company requirement"); 
        }
        if($absent<=2)
        {
        }
        else
        {
            array_push($errors, "Absent reached its maximum limit"); 
        }

        $stmt = $db->prepare("SELECT * FROM registered_interns WHERE STUDENT_ID=? AND COMPANY_NAME=? LIMIT 1");
        $stmt->bind_param('ss', $vari, $company_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user)
        {
            array_push($errors, "Already Applied"); 
        }
    }
    else
    {
        array_push($errors, "Already Got Internship"); 
    }

    if (count($errors) == 0) 
    {
        $stmt = $db->prepare("INSERT INTO registered_interns (STUDENT_ID, STUDENT_NAME, COMPANY_NAME) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $vari, $student_name, $company_name);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $db->prepare("UPDATE student SET APPLY_COUNT=APPLY_COUNT+1 WHERE STUDENT_ID=?");
        $stmt->bind_param('s', $vari);
        $stmt->execute();
        $stmt->close();
        array_push($positives, "Successfully applied for the company");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Internship | T&P SVNIT</title>
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
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .apply-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 600px;
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

        .input-group select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 3rem;
        }

        .input-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-group select:hover {
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

            .card-header-icon {
                font-size: 2.5rem;
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
    <div class="apply-card">
        <div class="card-header">
            <div class="card-header-icon">🚀</div>
            <h2>Apply for Internship</h2>
            <p>Select a company to apply for internship opportunities</p>
        </div>
        
        <div class="card-body">
            <form method="post" action="../student/dashboard/apply_intern.php">
                <?php require_once __DIR__ . '/../includes/csrf.php'; echo csrf_field(); ?>
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
                    <p>ℹ️ Make sure you meet the eligibility criteria (CGPA, backlogs, attendance) before applying. You can only apply if you haven't secured an internship yet.</p>
                </div>

                <div class="input-group">
                    <label>Select Company for Internship</label>
                    <select name="company_name">
                        <option>Company Name</option>
                        <?php
                        $stid=$_SESSION['student_id'];
                        $stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_ID=? LIMIT 1");
                        $stmt->bind_param('s', $stid);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result->fetch_assoc();
                        $stmt->close();
                        $branch_st=$user['BRANCH'];
                        $stmt = $db->prepare("SELECT DISTINCT company.COMPANY_NAME FROM companybranch inner join company on companybranch.COMPANY_NAME=company.COMPANY_NAME where companybranch.C_TYPE=? and company.STATUS=? and company.APPROVAL=? and companybranch.BRANCH=?");
                        $c_type = 'Internship';
                        $status = 'visiting';
                        $approval = 'approved';
                        $stmt->bind_param('ssss', $c_type, $status, $approval, $branch_st);
                        $stmt->execute();
                        $result1 = $stmt->get_result();
                        $stmt->close();

                        while($count=$result1->fetch_array())
                        {
                        ?>
                            <option><?php echo $count["COMPANY_NAME"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="apply_intern">Submit Application</button>
                </div>
                <?php endif ?>

                <p class="footer-text">
                    <?php if (count($positives) > 0) : ?>
                        Application submitted! <a href="index_student_intern.php">Go Back to Dashboard</a>
                    <?php else : ?>
                        Changed your mind? <a href="index_student_intern.php">Go Back</a>
                    <?php endif ?>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>
