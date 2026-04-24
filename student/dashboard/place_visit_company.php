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
    <title>Placement Companies | T&P SVNIT</title>
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
            max-width: 1400px;
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
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            color: #1e293b;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #64748b;
            font-size: 1rem;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }

        th {
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 1rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        td {
            padding: 1rem;
            color: #475569;
            font-size: 0.875rem;
        }

        td:first-child {
            font-weight: 600;
            color: #1e293b;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
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

        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-salary {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge-cgpa {
            background: #dbeafe;
            color: #2563eb;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .container {
                padding: 1rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            th, td {
                padding: 0.75rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .header-left {
                flex-direction: column;
            }

            .page-header h1 {
                font-size: 1.25rem;
            }

            th, td {
                padding: 0.5rem;
                font-size: 0.75rem;
            }

            /* Stack table for mobile */
            .table-wrapper {
                overflow-x: scroll;
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
    <div class="page-header">
        <h1>💼 Companies - Placement</h1>
        <p>View all approved companies visiting for placement opportunities</p>
    </div>

    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Visiting Date</th>
                        <th>Branch</th>
                        <th>Min CGPA</th>
                        <th>Max Backlogs</th>
                        <th>Max Salary</th>
                        <th>Job Profile</th>
                        <th>Place Of Posting</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once __DIR__ . '/../config/config.php';
                    $db = db_connect();
                    $stmt = $db->prepare("SELECT * FROM companybranch inner join company on companybranch.COMPANY_NAME=company.COMPANY_NAME where company.APPROVAL=? and companybranch.C_TYPE=? AND company.STATUS=?");
                    $approval = 'approved';
                    $c_type = 'Placement';
                    $status = 'visiting';
                    $stmt->bind_param('sss', $approval, $c_type, $status);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    
                    $rowCount = $result->num_rows;
                    
                    if($rowCount > 0) {
                        while($count = $result->fetch_array()) {
                    ?>
                        <tr>
                            <td><?php echo $count['COMPANY_NAME']; ?></td>
                            <td><?php echo date('d M Y', strtotime($count['COMING_DATE'])); ?></td>
                            <td><?php echo $count['BRANCH']; ?></td>
                            <td><span class="badge badge-cgpa"><?php echo $count['MIN_CGPA']; ?></span></td>
                            <td><?php echo $count['MAX_BACKLOGS']; ?></td>
                            <td><span class="badge badge-salary"><?php echo $count['MAX_SALARY']; ?></span></td>
                            <td><?php echo $count['JOB_PROFILE']; ?></td>
                            <td><?php echo $count['PLACE_OF_POSTING']; ?></td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📭</div>
                                    <h3>No Companies Visiting</h3>
                                    <p>There are currently no approved placement companies scheduled to visit.</p>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
