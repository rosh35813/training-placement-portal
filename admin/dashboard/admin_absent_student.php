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
$student_id="";
$status="";
$student_name="";
$current_absences="";
$errors = array();
$positives = array();
$db = db_connect();

if (isset($_POST['admin_absent_student'])) 
{
  $student_id = mysqli_real_escape_string($db, $_POST['student_id']);

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
    $status = $user['STATUS'];
    $student_name = $user['STUDENT_NAME'];
    $current_absences = $user['ABSENT'];

    if($status=='NS')
    {
      $stmt = $db->prepare("UPDATE student SET ABSENT=ABSENT+1 WHERE STUDENT_ID=?");
      $stmt->bind_param('s', $student_id);
      $stmt->execute();
      $stmt->close();
      
      $new_absence_count = $current_absences + 1;
      array_push($positives, "Absence marked for ".$student_name." (ID: ".$student_id."). Total absences: ".$new_absence_count);
    }
    else
    {
      array_push($errors, "Cannot mark absence - Student is already Placed/Interned"); 
    }
  }
  else
  {
    array_push($errors, "Student ID not found in database"); 
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Student Absence - T&P SVNIT</title>
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

        .absence-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 700px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
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

        /* Info Box */
        .info-box {
            background: #fff7ed;
            border-left: 4px solid #ea580c;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #9a3412;
        }

        .info-box strong {
            display: block;
            margin-bottom: 0.25rem;
            color: #7c2d12;
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
            border-color: #ea580c;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
        }

        .input-group input:hover {
            border-color: #cbd5e1;
        }

        /* Quick Actions */
        .quick-actions {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .quick-actions h4 {
            color: #1e293b;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .quick-action-btn {
            padding: 0.5rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-action-btn:hover {
            border-color: #ea580c;
            color: #ea580c;
            background: #fff7ed;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(234, 88, 12, 0.4);
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
            color: #ea580c;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .footer-text a:hover {
            color: #f97316;
        }

        /* Stats Display */
        .stats-box {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ea580c;
            display: block;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.25rem;
            display: block;
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

            .stats-box {
                grid-template-columns: 1fr;
            }

            .quick-actions-grid {
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
    <div class="absence-card">
        <div class="card-header">
            <h2>Mark Student Absence</h2>
            <p>Track student attendance for placement/internship drives</p>
        </div>
        
        <div class="card-body">
            <?php
            // Get attendance statistics
            $stats_query = "SELECT 
                COUNT(*) as total_students,
                SUM(CASE WHEN ABSENT > 0 THEN 1 ELSE 0 END) as students_with_absences,
                SUM(ABSENT) as total_absences
                FROM student WHERE STATUS='NS'";
            $stats_result = mysqli_query($db, $stats_query);
            $stats = mysqli_fetch_assoc($stats_result);
            ?>

            <div class="stats-box">
                <div class="stat-item">
                    <span class="stat-value"><?php echo $stats['total_students']; ?></span>
                    <span class="stat-label">Active Students</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $stats['students_with_absences']; ?></span>
                    <span class="stat-label">With Absences</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $stats['total_absences']; ?></span>
                    <span class="stat-label">Total Absences</span>
                </div>
            </div>

            <div class="info-box">
                <strong>📋 Attendance Tracking</strong>
                This will increment the absence counter for students who missed placement/internship events. Only active students (not yet placed/interned) can be marked absent.
            </div>

            <form method="post" action="admin_absent_student.php" id="absenceForm">
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
                    <input type="text" 
                           name="student_id" 
                           id="studentIdInput"
                           value="<?php echo $student_id; ?>" 
                           placeholder="e.g., 2021CSE001" 
                           autocomplete="off"
                           autofocus
                           required>
                </div>

                <div class="quick-actions">
                    <h4>💡 Quick Tips</h4>
                    <div style="font-size: 0.85rem; color: #64748b; line-height: 1.6;">
                        • Press Enter after typing Student ID to submit quickly<br>
                        • Only students with status "Not Selected" can be marked<br>
                        • Absence counter increases by 1 with each submission<br>
                        • Form clears automatically after successful submission
                    </div>
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="admin_absent_student">Mark Absence</button>
                </div>

                <p class="footer-text">
                    <a href="index_admin.php">← Back to Admin Dashboard</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-clear form after successful submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('absenceForm');
    const input = document.getElementById('studentIdInput');
    
    // Check if there's a success message
    const successContainer = document.querySelector('.success-container');
    if (successContainer) {
        // Clear the input field
        setTimeout(function() {
            input.value = '';
            input.focus();
        }, 1500);
    }
    
    // Auto-focus on input
    input.focus();
});
</script>

</body>
</html>