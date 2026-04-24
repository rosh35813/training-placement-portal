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
$noti="";
$errors = array();
$positives = array();
$db = db_connect();

if (isset($_POST['add_place_gen_noti'])) 
{
  $noti = mysqli_real_escape_string($db, $_POST['noti']);
  
  if(empty($noti) || trim($noti) == '')
  {
    array_push($errors, "Notification message cannot be empty");
  }
  elseif(strlen($noti) < 10)
  {
    array_push($errors, "Notification must be at least 10 characters long");
  }
  else
  {
    $stmt = $db->prepare("INSERT INTO place_notification (noti) VALUES(?)");
    $stmt->bind_param('s', $noti);
    $stmt->execute();
    $stmt->close();
    array_push($positives, "Notification published successfully! All placement students will see this message.");
    $noti = ""; // Clear after success
  }
}

// Get recent notifications count
$stmt = $db->prepare("SELECT COUNT(*) as count FROM place_notification");
$stmt->execute();
$recent_result = $stmt->get_result();
$recent_data = $recent_result->fetch_assoc();
$notification_count = $recent_data['count'];
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Notification - T&P SVNIT</title>
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

        .notification-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 800px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
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

        /* Stats Box */
        .stats-box {
            background: #ede9fe;
            border-left: 4px solid #6366f1;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stats-icon {
            font-size: 2rem;
        }

        .stats-content h4 {
            color: #4338ca;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .stats-content p {
            color: #5b21b6;
            font-size: 0.85rem;
        }

        /* Info Box */
        .info-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #92400e;
        }

        .info-box strong {
            display: block;
            margin-bottom: 0.25rem;
            color: #78350f;
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

        .char-counter {
            float: right;
            color: #64748b;
            font-size: 0.85rem;
        }

        .input-group textarea {
            width: 100%;
            min-height: 180px;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            background: white;
            resize: vertical;
            line-height: 1.6;
        }

        .input-group textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .input-group textarea:hover {
            border-color: #cbd5e1;
        }

        .input-group textarea::placeholder {
            color: #94a3b8;
        }

        /* Formatting Tips */
        .formatting-tips {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .formatting-tips h4 {
            color: #1e293b;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .tips-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #64748b;
        }

        .tip-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tip-item::before {
            content: "•";
            color: #6366f1;
            font-weight: bold;
        }

        /* Preview Box */
        .preview-box {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            min-height: 100px;
            display: none;
        }

        .preview-box.active {
            display: block;
        }

        .preview-label {
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .preview-content {
            color: #1e293b;
            font-size: 0.95rem;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Footer Text */
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.95rem;
        }

        .footer-text a {
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .footer-text a:hover {
            color: #8b5cf6;
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

            .tips-grid {
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
    <div class="notification-card">
        <div class="card-header">
            <h2>Placement Notification</h2>
            <p>Broadcast announcements to all placement students</p>
        </div>
        
        <div class="card-body">
            <div class="stats-box">
                <div class="stats-icon">🎓</div>
                <div class="stats-content">
                    <h4>Placement Notification Center</h4>
                    <p><?php echo $notification_count; ?> total notification<?php echo $notification_count != 1 ? 's' : ''; ?> published</p>
                </div>
            </div>

            <form method="post" action="add_place_gen_noti.php" id="notificationForm">
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

                <div class="info-box">
                    <strong>📝 Notification Guidelines</strong>
                    Keep messages clear and professional. Include company names, drive dates, eligibility criteria, and registration deadlines. Students will receive this as a general announcement.
                </div>

                <div class="input-group">
                    <label>
                        Notification Message
                        <span class="char-counter" id="charCount">0 / 500 characters</span>
                    </label>
                    <textarea 
                        name="noti" 
                        id="notificationText"
                        placeholder="Example: Important Placement Drive: Microsoft will be visiting campus on November 20, 2025. Eligible branches: CSE, ECE, EE. Minimum CGPA: 7.5. Registration deadline: November 15, 2025. Register through the placement portal."
                        maxlength="500"
                        required
                    ><?php echo htmlspecialchars($noti); ?></textarea>
                </div>

                <div class="formatting-tips">
                    <h4>💡 Writing Tips for Placement Notifications</h4>
                    <div class="tips-grid">
                        <div class="tip-item">Mention company name clearly</div>
                        <div class="tip-item">Include drive date and time</div>
                        <div class="tip-item">Specify eligibility criteria</div>
                        <div class="tip-item">State registration deadline</div>
                        <div class="tip-item">Add CTC/package details if available</div>
                        <div class="tip-item">Include venue or platform info</div>
                    </div>
                </div>

                <div class="preview-box" id="previewBox">
                    <div class="preview-label">Preview</div>
                    <div class="preview-content" id="previewContent"></div>
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="add_place_gen_noti" id="submitBtn">Publish Notification</button>
                </div>

                <p class="footer-text">
                    <a href="index_admin.php">← Back to Admin Dashboard</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('notificationText');
    const charCount = document.getElementById('charCount');
    const previewBox = document.getElementById('previewBox');
    const previewContent = document.getElementById('previewContent');
    const submitBtn = document.getElementById('submitBtn');

    // Character counter and preview
    textarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length + ' / 500 characters';
        
        // Show/hide preview
        if (length > 0) {
            previewBox.classList.add('active');
            previewContent.textContent = this.value;
        } else {
            previewBox.classList.remove('active');
        }

        // Update button state
        if (length < 10) {
            submitBtn.disabled = true;
        } else {
            submitBtn.disabled = false;
        }
    });

    // Auto-focus
    textarea.focus();
});
</script>

</body>
</html>