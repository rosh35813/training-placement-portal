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
    if($_SESSION['user']=='admin')
    {
      header("Location: index_admin.php");
    }

?>

<?php
$company_id="";
$company_name="";
$company_type="";
$website="";
$address2="";
$coming_date="";
$c_type="";
$branch1="";
$min_cgpa="";
$max_backlogs="";
$max_salary="";
$max_stipend="";
$job_profile="";
$place_of_posting="";
$disp="";
$errors = array();
$positives=array();
require_once __DIR__ . '/../config/config.php';
$db = db_connect();

if (isset($_POST['reg_company_details'])) 
{
  require_once __DIR__ . '/../includes/csrf.php';
  if (!verify_csrf()) {
      die('CSRF token validation failed');
  }
  
  $company_name = mysqli_real_escape_string($db, $_POST['company_name']);
  $c_type = mysqli_real_escape_string($db, $_POST['c_type']);
  $branch1 = mysqli_real_escape_string($db, $_POST['branch1']);
  $min_cgpa = mysqli_real_escape_string($db, $_POST['min_cgpa']);
  $max_backlogs = mysqli_real_escape_string($db, $_POST['max_backlogs']);
  $max_salary = mysqli_real_escape_string($db, $_POST['max_salary']);
  $max_stipend = mysqli_real_escape_string($db, $_POST['max_stipend']);
  $job_profile = mysqli_real_escape_string($db, $_POST['job_profile']);
  $place_of_posting = mysqli_real_escape_string($db, $_POST['place_of_posting']);

if($_SESSION['company_name']==$company_name)
{
    if (empty($company_name)) 
  { 
    array_push($errors, "Company name is required"); 
  }
  if (empty($c_type)) 
  { 
    array_push($errors, "company type is required"); 
  }
  if (empty($branch1)) 
  { 
    array_push($errors, "Branch is required"); 
  }
  if (empty($min_cgpa)) 
  { 
    array_push($errors, "Min CGPA is required"); 
  }
  if (empty($job_profile)) 
  { 
    array_push($errors, "Job_Profile is required"); 
  }
  if (empty($place_of_posting)) 
  { 
    array_push($errors, "Place_of_Posting is required"); 
  }
  if (empty($max_stipend) &&(($c_type=='internship') or ($c_type=='Internship') or($c_type=='intern') or ($c_type=='Intern')))
  { 
    array_push($errors, "Stipend is required"); 
  }
  if (empty($max_salary)  &&(($c_type=='placement') or ($c_type=='Placement'))) 
  { 
    array_push($errors, "Salary is required"); 
  }

  if (!(empty($max_salary)) &&($c_type=='Internship'))
  { 
    array_push($errors, "Company is of type internship,No salary"); 
  }
  if (!(empty($max_stipend))  && ($c_type=='Placement') )
  { 
    array_push($errors, "Company is of type Placement,No Stipend"); 
  }

  $stmt = $db->prepare("SELECT * FROM companybranch WHERE COMPANY_NAME=? AND C_TYPE=? AND BRANCH=? LIMIT 1");
  $stmt->bind_param('sss', $company_name, $c_type, $branch1);
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
    if ($user['C_TYPE'] === $c_type) 
    {
      array_push($errors, "Company Type already exists");
    }
    if ($user['BRANCH'] === $branch1) 
    {
      array_push($errors, "Branch already exists");
    }
  }

  if (count($errors) == 0) 
  {
    $stmt = $db->prepare("INSERT INTO companybranch (COMPANY_NAME, C_TYPE, BRANCH, MIN_CGPA, MAX_BACKLOGS, MAX_SALARY, MAX_STIPEND, JOB_PROFILE, PLACE_OF_POSTING) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssddddss', $company_name, $c_type, $branch1, $min_cgpa, $max_backlogs, $max_salary, $max_stipend, $job_profile, $place_of_posting);
    $stmt->execute();
    $stmt->close();
    $_SESSION['company_name'] = $company_name;
    $_SESSION['success'] = "Company Successfully updated details";
    $disp="Company Successfully Enter details";
    array_push($positives, "Company Successfully updated details");
  }
}
else
{
    array_push($errors, "Company name is wrong"); 
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Details - T&P SVNIT</title>
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

        .logout-btn {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        }

        /* Main Container */
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .details-card {
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

        /* Success Messages */
        .success-container {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .success-container p {
            color: #16a34a;
            font-size: 0.875rem;
            margin: 0.25rem 0;
            font-weight: 500;
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

        .input-group input[readonly] {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
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

        .form-grid .input-group {
            margin-bottom: 0;
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
                gap: 1.5rem;
            }

            .form-grid .input-group {
                margin-bottom: 0;
            }

            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
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
        <a href="logout.php" class="logout-btn" title="<?php echo $_SESSION['company_name']; ?>">Log Out</a>
    </div>
</div>

<!-- Main Container -->
<div class="container">
    <div class="details-card">
        <div class="card-header">
            <h2>Company Recruitment Details</h2>
        </div>
        
        <div class="card-body">
            <form method="post" action="../company/dashboard/index_company_details.php">
                <?php require_once __DIR__ . '/../includes/csrf.php'; echo csrf_field(); ?>
                <?php if (count($positives) > 0) : ?>
                <div class="success-container">
                    <?php foreach ($positives as $positive) : ?>
                        <p>✓ <?php echo $positive; ?></p>
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
                    <label>Company Name</label>
                    <input type="text" name="company_name" value="<?php echo $_SESSION['company_name']; ?>" readonly>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Company Type</label>
                        <select name="c_type">
                            <option value="Internship">Internship</option>
                            <option value="Placement">Placement</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Branch</label>
                        <select name="branch1">
                            <option value="CSE">Computer Science and Engineering</option>
                            <option value="ECE">Electronics and Comm. Engineering</option>
                            <option value="EE">Electrical Engineering</option>
                            <option value="CE">Civil Engineering</option>
                            <option value="ME">Mechanical Engineering</option>
                            <option value="CHE">Chemical Engineering</option>
                            <option value="AI">Artificial Intelligence</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Minimum CGPA</label>
                        <input type="text" name="min_cgpa" value="<?php echo $min_cgpa ?>" placeholder="e.g., 7.5">
                    </div>

                    <div class="input-group">
                        <label>Max Backlogs</label>
                        <input type="text" name="max_backlogs" value="<?php echo $max_backlogs ?>" placeholder="e.g., 2">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Max Salary (for Placement)</label>
                        <input type="text" name="max_salary" value="<?php echo $max_salary ?>" placeholder="e.g., 800000">
                    </div>

                    <div class="input-group">
                        <label>Max Stipend (for Internship)</label>
                        <input type="text" name="max_stipend" value="<?php echo $max_stipend ?>" placeholder="e.g., 50000">
                    </div>
                </div>

                <div class="input-group">
                    <label>Job Profile</label>
                    <input type="text" name="job_profile" value="<?php echo $job_profile ?>" placeholder="e.g., Software Engineer">
                </div>

                <div class="input-group">
                    <label>Place of Posting</label>
                    <input type="text" name="place_of_posting" value="<?php echo $place_of_posting ?>" placeholder="e.g., Mumbai, Bangalore">
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="reg_company_details">Submit Details</button>
                </div>

                <p class="footer-text">
                    Finished adding details? <a href="index_company.php">Go Back to Dashboard</a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>