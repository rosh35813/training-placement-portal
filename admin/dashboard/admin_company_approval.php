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
$company_id="";
$company_name="";
$company_type="";
$website="";
$address2="";
$coming_date="";
$errors = array();
$positives = array();
$db = db_connect();

if (isset($_POST['approve_company'])) 
{
  $company_name = mysqli_real_escape_string($db, $_POST['company_name']);

  if ($company_name=='Company Name' || empty($company_name)) 
  { 
    array_push($errors, "Please select a company to approve"); 
  }

  if (count($errors) == 0) 
  {
    $stmt = $db->prepare("UPDATE company SET APPROVAL=? WHERE COMPANY_NAME=?");
    $approval = 'approved';
    $stmt->bind_param('ss', $approval, $company_name);
    $stmt->execute();
    $stmt->close();
    array_push($positives, "Company '".$company_name."' has been successfully approved!");
  }
}

if (isset($_POST['disapprove_company']))
{
  $company_name = mysqli_real_escape_string($db, $_POST['company_name']);

  if ($company_name=='Company Name' || empty($company_name)) 
  { 
    array_push($errors, "Please select a company to reject"); 
  }

  if (count($errors) == 0) 
  {
    $stmt = $db->prepare("UPDATE company SET APPROVAL=? WHERE COMPANY_NAME=?");
    $approval = 'rejected';
    $stmt->bind_param('ss', $approval, $company_name);
    $stmt->execute();
    $stmt->close();

    $stmt = $db->prepare("DELETE FROM companybranch WHERE COMPANY_NAME=?");
    $stmt->bind_param('s', $company_name);
    $stmt->execute();
    $stmt->close();
    
    array_push($positives, "Company '".$company_name."' has been rejected and removed from the system.");
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Approval - T&P SVNIT</title>
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

        .approval-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 700px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
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

        /* Warning Box */
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #92400e;
        }

        .warning-box strong {
            display: block;
            margin-bottom: 0.25rem;
            color: #78350f;
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
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .input-group select:hover {
            border-color: #cbd5e1;
        }

        /* Button Container */
        .button-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Buttons */
        .btn {
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .btn-approve {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
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
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .footer-text a:hover {
            color: #a78bfa;
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

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* No Companies Message */
        .no-companies {
            text-align: center;
            padding: 2rem;
            color: #64748b;
        }

        .no-companies-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
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

            .button-container {
                grid-template-columns: 1fr;
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
    <div class="approval-card">
        <div class="card-header">
            <h2>Company Approval</h2>
            <p>Review and approve companies for campus recruitment</p>
        </div>
        
        <div class="card-body">
            <?php
            // Check if there are pending companies
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM company WHERE STATUS=? AND APPROVAL<>? AND APPROVAL<>?");
            $status = 'visiting';
            $approval_not = 'approved';
            $rejected = 'rejected';
            $stmt->bind_param('sss', $status, $approval_not, $rejected);
            $stmt->execute();
            $check_result = $stmt->get_result();
            $check_data = $check_result->fetch_assoc();
            $stmt->close();
            $pending_count = $check_data['count'];
            ?>

            <?php if ($pending_count > 0): ?>
            <div class="info-box">
                <strong>📋 Pending Approvals</strong>
                You have <?php echo $pending_count; ?> compan<?php echo $pending_count > 1 ? 'ies' : 'y'; ?> awaiting approval.
            </div>
            <?php endif; ?>

            <form method="post" action="admin_company_approval.php">
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

                <?php if ($pending_count > 0): ?>
                <div class="warning-box">
                    <strong>⚠ Important Actions</strong>
                    Approving will allow the company to participate in placement/internship drives. Rejecting will permanently remove the company from the system.
                </div>

                <div class="input-group">
                    <label>Select Company 
                        <span class="status-badge status-pending">Pending Approval</span>
                    </label>
                    <select name="company_name" required>
                        <option value="">Select Company to Review</option>
                        <?php
                        $stmt = $db->prepare("SELECT COMPANY_NAME FROM company WHERE STATUS=? AND APPROVAL<>? AND APPROVAL<>? ORDER BY COMPANY_NAME ASC");
                        $status = 'visiting';
                        $approval_not = 'approved';
                        $rejected = 'rejected';
                        $stmt->bind_param('sss', $status, $approval_not, $rejected);
                        $stmt->execute();
                        $result1 = $stmt->get_result();
                        $stmt->close();

                        while($count = $result1->fetch_array())
                        {
                            $selected = ($company_name == $count["COMPANY_NAME"]) ? 'selected' : '';
                            echo '<option value="'.$count["COMPANY_NAME"].'" '.$selected.'>'.$count["COMPANY_NAME"].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-approve" name="approve_company" onclick="return confirm('Are you sure you want to approve this company?');">
                        ✓ Approve Company
                    </button>
                    <button type="submit" class="btn btn-reject" name="disapprove_company" onclick="return confirm('Are you sure you want to reject this company? This action will remove all company data.');">
                        ✕ Reject Company
                    </button>
                </div>
                <?php else: ?>
                <div class="no-companies">
                    <div class="no-companies-icon">📋</div>
                    <h3 style="color: #64748b; margin-bottom: 0.5rem;">No Pending Approvals</h3>
                    <p style="color: #94a3b8;">All companies have been reviewed. Check back later for new registrations.</p>
                </div>
                <?php endif; ?>

                <p class="footer-text">
                    <a href="index_admin.php">← Back to Admin Dashboard</a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>