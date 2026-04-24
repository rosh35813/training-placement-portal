<?php
    session_start();
    if(!isset($_SESSION['user']))
    {
        header("Location: svnit.php");
    }
    if($_SESSION['user']=='student_int')
    {
        header("Location: index_student_intern.php");
    }
    if($_SESSION['user']=='student_place')
    {
        header("Location: index_student_placement.php");
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
    <title>Selected Interns - T&P SVNIT</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .data-card {
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

        /* Stats Bar */
        .stats-bar {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
        }

        .stats-text {
            color: #475569;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .stats-number {
            color: #059669;
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Table Styles */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8fafc;
        }

        th {
            padding: 1rem;
            text-align: left;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.95rem;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.95rem;
        }

        tbody tr {
            transition: all 0.2s;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
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
                text-align: center;
            }

            .header-left {
                flex-direction: column;
                width: 100%;
            }

            .table-wrapper {
                overflow-x: auto;
            }

            th, td {
                padding: 0.75rem;
                font-size: 0.85rem;
            }

            .stats-bar {
                flex-direction: column;
                gap: 0.5rem;
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
            <a href="logout.php" class="logout-link" title="<?php echo $_SESSION['admin_name']; ?>">Log Out</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <div class="data-card">
            <div class="card-header">
                <div class="card-header-icon">✅</div>
                <h1>Selected Interns</h1>
                <p>All students selected for internship positions</p>
            </div>
            
            <div class="card-body">
                <?php
                require_once __DIR__ . '/../config/config.php';
                $db = db_connect();
                
                $user_check_query = "SELECT * FROM student_internship ORDER BY COMPANY_NAME";
                $stmt = $db->prepare($user_check_query);
                $stmt->execute();
                $result = $stmt->get_result();
                $rowCount = $result->num_rows;
                
                if($rowCount > 0) {
                ?>
                    <div class="stats-bar">
                        <span class="stats-text">Total Selected:</span>
                        <span class="stats-number"><?php echo $rowCount; ?></span>
                    </div>

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Company Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while(($count = $result->fetch_array(MYSQLI_ASSOC))) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($count['STUDENT_ID']); ?></td>
                                        <td><?php echo htmlspecialchars($count['STUDENT_NAME']); ?></td>
                                        <td><?php echo htmlspecialchars($count['COMPANY_NAME']); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                } else {
                ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <h3>No Selections Yet</h3>
                        <p>No students have been selected for internship positions at this time.</p>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>
