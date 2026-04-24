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
if($_SESSION['user']=='student_int' )
{
    header("Location: index_student_intern.php");
}
if($_SESSION['user']=='company')
{
    header("Location: index_company.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications | T&P SVNIT</title>
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
            max-width: 700px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .applications-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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

        .card-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .card-body {
            padding: 2rem;
        }

        /* Company List */
        .company-list {
            list-style: none;
        }

        .company-item {
            padding: 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
        }

        .company-item:last-child {
            border-bottom: none;
        }

        .company-item:hover {
            background: #f8fafc;
            padding-left: 1.5rem;
        }

        .company-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .company-name {
            color: #1e293b;
            font-size: 1rem;
            font-weight: 600;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #64748b;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
        }

        .empty-state-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .empty-state-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.4);
        }

        /* Already Placed State */
        .placed-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .placed-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .placed-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #059669;
        }

        .placed-state p {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        /* Stats */
        .stats-bar {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-text {
            color: #475569;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stats-number {
            color: #1e40af;
            font-size: 1.25rem;
            font-weight: 700;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-header h1 {
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

            .company-item {
                padding: 1rem;
            }

            .company-item:hover {
                padding-left: 1.25rem;
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
    <div class="applications-card">
        <div class="card-header">
            <div class="card-header-icon">📋</div>
            <h1>My Applications</h1>
            <p>Companies you have applied to for placement</p>
        </div>
        
        <div class="card-body">
            <?php
            require_once __DIR__ . '/../config/config.php';
            $db = db_connect();
            $stu_id = $_SESSION['student_id'];

            $stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_ID=? LIMIT 1");
            $stmt->bind_param('s', $stu_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            $status = $user['STATUS'];
            $decider = 0;
            
            if($status == 'NS')
            {
                $stmt = $db->prepare("SELECT DISTINCT COMPANY_NAME from registered_placements where STUDENT_ID=?");
                $stmt->bind_param('s', $stu_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                $decider = 1;
                $rowCount = $result->num_rows;
                
                if($rowCount > 0) {
            ?>
                    <div class="stats-bar">
                        <span class="stats-text">Total Applications:</span>
                        <span class="stats-number"><?php echo $rowCount; ?></span>
                    </div>

                    <ul class="company-list">
                        <?php
                        while($count = mysqli_fetch_array($result)) {
                        ?>
                            <li class="company-item">
                                <div class="company-icon">🏢</div>
                                <div class="company-name"><?php echo $count['COMPANY_NAME']; ?></div>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
            <?php
                } else {
            ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <h3>No Applications Yet</h3>
                        <p>You haven't applied to any companies yet. Start applying to placement opportunities!</p>
                        <a href="apply_place.php" class="empty-state-button">Apply Now</a>
                    </div>
            <?php
                }
            } else {
            ?>
                <div class="placed-state">
                    <div class="placed-icon">🎉</div>
                    <h3>Congratulations!</h3>
                    <p>You have been successfully placed. Application tracking is no longer available.</p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
