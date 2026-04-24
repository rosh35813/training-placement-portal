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
    <title>General Notifications | T&P SVNIT</title>
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
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .notifications-card {
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

        /* Notification List */
        .notification-list {
            list-style: none;
        }

        .notification-item {
            padding: 1.25rem;
            border-left: 4px solid #3b82f6;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: all 0.2s;
        }

        .notification-item:last-child {
            margin-bottom: 0;
        }

        .notification-item:hover {
            background: #eff6ff;
            border-left-color: #1e40af;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .notification-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .notification-text {
            flex: 1;
            color: #1e293b;
            font-size: 0.95rem;
            line-height: 1.6;
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

        /* Error State */
        .error-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .error-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #dc2626;
        }

        .error-state p {
            color: #64748b;
        }

        /* Stats Bar */
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

            .notification-item {
                padding: 1rem;
            }

            .notification-content {
                flex-direction: column;
            }

            .notification-icon {
                width: 36px;
                height: 36px;
                font-size: 1.1rem;
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
    <div class="notifications-card">
        <div class="card-header">
            <div class="card-header-icon">🔔</div>
            <h1>General Notifications</h1>
            <p>Stay updated with important announcements from the placement cell</p>
        </div>
        
        <div class="card-body">
            <?php
            require_once __DIR__ . '/../config/config.php';
            $db = db_connect();
            
            // Check database connection
            if (!$db) {
                ?>
                <div class="error-state">
                    <div class="error-icon">⚠️</div>
                    <h3>Connection Error</h3>
                    <p>Unable to connect to the database. Please try again later.</p>
                </div>
                <?php
            } else {
                // Table `place_notification` does not have an `id` column in the DB schema.
                // Ordering by a non-existent column causes the query to fail. Use a safe
                // ordering (by the notification text) or remove ordering until an
                // auto-increment `id`/`created_at` column is added to the table.
                $stmt = $db->prepare("SELECT * FROM place_notification ORDER BY noti DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                
                // Check if query was successful
                if (!$result) {
                    ?>
                    <div class="error-state">
                        <div class="error-icon">⚠️</div>
                        <h3>Query Error</h3>
                        <p>Error fetching notifications. The table might not exist yet.</p>
                    </div>
                    <?php
                } else {
                    $rowCount = mysqli_num_rows($result);
                    
                    if($rowCount > 0) {
                    ?>
                        <div class="stats-bar">
                            <span class="stats-text">Total Notifications:</span>
                            <span class="stats-number"><?php echo $rowCount; ?></span>
                        </div>

                        <ul class="notification-list">
                            <?php
                            while($count = mysqli_fetch_array($result)) {
                            ?>
                                <li class="notification-item">
                                    <div class="notification-content">
                                        <div class="notification-icon">📢</div>
                                        <div class="notification-text"><?php echo htmlspecialchars($count['noti']); ?></div>
                                    </div>
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
                            <h3>No Notifications</h3>
                            <p>There are currently no general notifications. Check back later for updates!</p>
                        </div>
                    <?php
                    }
                }
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
