<?php 
  session_start();
  if(isset($_SESSION['user']))
  {
    if($_SESSION['user']=='admin')
    {
      header("Location: index_admin.php");
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
  }
?>
<?php
$student_id="";
$student_name="";
$dob="";
$gender="";
$st_email="";
$address1="";
$contact_num="";
$branch="";
$tenth_per="";
$tenth_pass="";
$twelfth_per="";
$twelfth_pass="";
$cgpa="";
$pass="";
$backlogs="";
$apply="";
$st_password="";

$errors = array();
require_once __DIR__ . '/../config/config.php';
$db = db_connect();
if (isset($_POST['reg_student'])) 
{
  require_once __DIR__ . '/../includes/csrf.php';
  if (!verify_csrf()) {
      die('CSRF token validation failed');
  }
  
  $student_id = mysqli_real_escape_string($db, $_POST['student_id']);
  $student_name = mysqli_real_escape_string($db, $_POST['student_name']);
  $dob = mysqli_real_escape_string($db, $_POST['dob']);
  $gender = mysqli_real_escape_string($db, $_POST['gender']);
  $st_email = mysqli_real_escape_string($db, $_POST['st_email']);
  $address1 = mysqli_real_escape_string($db, $_POST['address1']);
  $contact_num = mysqli_real_escape_string($db, $_POST['contact_num']);
  $branch = mysqli_real_escape_string($db, $_POST['branch']);
  $tenth_per = mysqli_real_escape_string($db, $_POST['tenth_per']);
  $tenth_pass = mysqli_real_escape_string($db, $_POST['tenth_pass']);
  $twelfth_per = mysqli_real_escape_string($db, $_POST['twelfth_per']);
  $twelfth_pass = mysqli_real_escape_string($db, $_POST['twelfth_pass']);
  $cgpa = mysqli_real_escape_string($db, $_POST['cgpa']);
  $pass = mysqli_real_escape_string($db, $_POST['pass']);
  $backlogs = mysqli_real_escape_string($db, $_POST['backlogs']);
  $apply = mysqli_real_escape_string($db, $_POST['apply']);
  $st_password1 = mysqli_real_escape_string($db, $_POST['st_password1']);
  $st_password2 = mysqli_real_escape_string($db, $_POST['st_password2']);

  if (empty($student_id)) 
  { 
    array_push($errors, "Student Id is required"); 
  }
  if (empty($student_name)) 
  { 
    array_push($errors, "Student Name is required"); 
  }
  if (empty($dob)) 
  { 
    array_push($errors, "Date of birth is required"); 
  }
  if (empty($gender)) 
  { 
    array_push($errors, "Gender is required"); 
  }
  if (empty($st_email)) 
  { 
    array_push($errors, "Email is required"); 
  }
  if (empty($branch)) 
  { 
    array_push($errors, "Branch is required"); 
  }
  if (empty($tenth_per)) 
  { 
    array_push($errors, "10th % is required"); 
  }
  if (empty($tenth_pass)) 
  { 
    array_push($errors, "10th pass year is required"); 
  }
  if (empty($twelfth_per)) 
  { 
    array_push($errors, "12th % is required"); 
  }
  if (empty($twelfth_pass)) 
  { 
    array_push($errors, "12th pass year is required"); 
  }
  if (empty($cgpa)) 
  { 
    array_push($errors, "cgpa is required"); 
  }
  if (empty($pass)) 
  { 
    array_push($errors, "College passing year  is required"); 
  }
  if (empty($address1)) 
  { 
    array_push($errors, "Address is required"); 
  }
  if (empty($apply)) 
  { 
    array_push($errors, "apply for field is required"); 
  }
  if (empty($st_password1)) 
  { 
    array_push($errors, "Password is required"); 
  }
  if ($st_password1 != $st_password2) 
  {
    array_push($errors, "passwords do not match");
  }
  
  if (filter_var($st_email, FILTER_VALIDATE_EMAIL)) 
  {
  }
  else 
  {
    array_push($errors, "email is not a valid email address"); 
  }

  if(($apply=='internship' or $apply=='Internship') and $pass!='2027')
  {
    array_push($errors, "Not eligible for internship i.e not 3rd year student"); 
  }
  if(($apply=='placement' or $apply=='Placement') and $pass!='2026')
  {
    array_push($errors, "Not eligible for placement i.e not 4th year student"); 
  }
  if(strlen($contact_num)!=10)
  {
     array_push($errors, "Contact Number not correct"); 
  }
  if($cgpa<1 or $cgpa>10)
  {
    array_push($errors, "This CGPA not possible");
  }
  if($tenth_per<1 or $tenth_per>100)
  {
    array_push($errors, "This 10th % not possible");
  }
  if($twelfth_per<1 or $twelfth_per>100)
  {
    array_push($errors, "This 12th %  not possible");
  }
  if(((int)$twelfth_pass-(int)$tenth_pass)<2)
  {
    array_push($errors, "10th and 12th pass not correct");
  }
  $stmt = $db->prepare("SELECT * FROM student WHERE STUDENT_NAME=? AND STUDENT_ID=? LIMIT 1");
  $stmt->bind_param('ss', $student_name, $student_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();
  
  if ($user)
  {
    if ($user['STUDENT_NAME'] === $student_name) 
    {
      array_push($errors, "Student already registered");
    }
    if ($user['STUDENT_ID'] === $student_id) 
    {
      array_push($errors, "Id already exists");
    }
    if ($user['EMAIL'] === $st_email) 
    {
      array_push($errors, "email already exists");
    }
  }

  if (count($errors) == 0) 
  {
    // Securely hash password
    $passwordHash = password_hash($st_password1, PASSWORD_DEFAULT);

    // Handle image server-side: size and MIME checks
    $file = null;
    if (isset($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $maxBytes = 512 * 1024; // 512KB
        if ($_FILES['image']['size'] > $maxBytes) {
            array_push($errors, 'Image exceeds 512KB limit');
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['image']['tmp_name']);
            $allowed = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/jpg' => 'jpg'];
            if (!array_key_exists($mime, $allowed)) {
                array_push($errors, 'Invalid image type');
            } else {
                $file = file_get_contents($_FILES['image']['tmp_name']);
            }
        }
    }

    if (count($errors) == 0) {
        // Insert with IMAGE nullable; we'll store NULL if no valid file
        $imageParam = null;
        $stmt = $db->prepare("INSERT INTO student (STUDENT_ID,S_PASSWORD,STUDENT_NAME,GENDER,DOB,EMAIL,ADDRESS,CONTACT_NO,BRANCH,TENTH_PER,TENTH_PASS_YEAR,TWELTH_PER,TWELTH_PASS_YEAR,CGPA,PASSING_YEAR,BACKLOGS,APPLY_FOR,IMAGE) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $types = 'ssssssssssisidiiss';
        $stmt->bind_param($types, $student_id, $passwordHash, $student_name, $gender, $dob, $st_email, $address1, $contact_num, $branch, $tenth_per, $tenth_pass, $twelfth_per, $twelfth_pass, $cgpa, $pass, $backlogs, $apply, $imageParam);
        if ($stmt->execute()) {
            $stmt->close();
            $_SESSION['success'] = "Student registered successfully";
            header('location:student.php');
            exit;
        } else {
            array_push($errors, 'Database error: unable to register');
        }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - T&P SVNIT</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/Svnit_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        /* Main Container */
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .registration-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 900px;
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

        /* Error Messages */
        .error-container {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .error-container p {
            color: #dc2626;
            font-size: 0.875rem;
            margin: 0.25rem 0;
        }

        /* Section Headers */
        .section-header {
            color: #1e293b;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-header:first-of-type {
            margin-top: 0;
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

        .form-grid-three {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
        }

        /* File Upload */
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 1rem;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            color: #64748b;
            font-weight: 500;
        }

        .file-input-label:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #1e40af;
        }

        .file-name {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
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

            .form-grid,
            .form-grid-three {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Top Header -->
<div class="top-header">
    <div class="header-content">
        <div class="logo">
            <a href="svnit.php">
                <img src="../assets/images/Svnit_logo.png" alt="SVNIT SURAT" />
            </a>
        </div>
        <div class="header-text">
            Training &amp; Placement, SVNIT SURAT
        </div>
    </div>
</div>

<!-- Main Container -->
<div class="container">
    <div class="registration-card">
        <div class="card-header">
            <h2>Student Registration</h2>
        </div>
        
        <div class="card-body">
            <form method="post" action="../student/register/student_register.php" enctype="multipart/form-data">
                <?php require_once __DIR__ . '/../includes/csrf.php'; echo csrf_field(); ?>
                <?php if (count($errors) > 0) : ?>
                <div class="error-container">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

                <!-- Personal Information -->
                <div class="section-header">Personal Information</div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Student ID</label>
                        <input type="text" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" placeholder="e.g., 2021CSE001">
                    </div>

                    <div class="input-group">
                        <label>Student Name</label>
                        <input type="text" name="student_name" value="<?php echo htmlspecialchars($student_name); ?>" placeholder="Full Name">
                    </div>
                </div>

                <div class="form-grid-three">
                    <div class="input-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                    </div>

                    <div class="input-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Branch</label>
                        <select name="branch">
                            <option value="">Select Branch</option>
                            <option value="CSE" <?php echo ($branch == 'CSE') ? 'selected' : ''; ?>>Computer Science and Engineering</option>
                            <option value="ECE" <?php echo ($branch == 'ECE') ? 'selected' : ''; ?>>Electronics and Comm. Engineering</option>
                            <option value="EE" <?php echo ($branch == 'EE') ? 'selected' : ''; ?>>Electrical Engineering</option>
                            <option value="CE" <?php echo ($branch == 'CE') ? 'selected' : ''; ?>>Civil Engineering</option>
                            <option value="ME" <?php echo ($branch == 'ME') ? 'selected' : ''; ?>>Mechanical Engineering</option>
                            <option value="CHE" <?php echo ($branch == 'CHE') ? 'selected' : ''; ?>>Chemical Engineering</option>
                            <option value="AI" <?php echo ($branch == 'AI') ? 'selected' : ''; ?>>Artificial Intelligence</option>
                        </select>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="section-header">Contact Information</div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="st_email" value="<?php echo htmlspecialchars($st_email); ?>" placeholder="student@svnit.ac.in">
                    </div>

                    <div class="input-group">
                        <label>Contact Number</label>
                        <input type="number" name="contact_num" value="<?php echo htmlspecialchars($contact_num); ?>" placeholder="10-digit mobile number">
                    </div>
                </div>

                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="address1" value="<?php echo htmlspecialchars($address1); ?>" placeholder="Complete Address">
                </div>

                <!-- Academic Information -->
                <div class="section-header">Academic Information</div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>10th Percentage</label>
                        <input type="text" name="tenth_per" value="<?php echo htmlspecialchars($tenth_per); ?>" placeholder="e.g., 85.5">
                    </div>

                    <div class="input-group">
                        <label>10th Pass Year</label>
                        <select name="tenth_pass">
                            <option value="">Select Year</option>
                            <option value="2018" <?php echo ($tenth_pass == '2018') ? 'selected' : ''; ?>>2018</option>
                            <option value="2019" <?php echo ($tenth_pass == '2019') ? 'selected' : ''; ?>>2019</option>
                            <option value="2020" <?php echo ($tenth_pass == '2020') ? 'selected' : ''; ?>>2020</option>
                            <option value="2021" <?php echo ($tenth_pass == '2021') ? 'selected' : ''; ?>>2021</option>
                            <option value="2022" <?php echo ($tenth_pass == '2022') ? 'selected' : ''; ?>>2022</option>
                            <option value="2023" <?php echo ($tenth_pass == '2023') ? 'selected' : ''; ?>>2023</option>
                            <option value="2024" <?php echo ($tenth_pass == '2024') ? 'selected' : ''; ?>>2024</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>12th Percentage</label>
                        <input type="text" name="twelfth_per" value="<?php echo htmlspecialchars($twelfth_per); ?>" placeholder="e.g., 90.5">
                    </div>

                    <div class="input-group">
                        <label>12th Pass Year</label>
                        <select name="twelfth_pass">
                            <option value="">Select Year</option>
                            <option value="2020" <?php echo ($twelfth_pass == '2020') ? 'selected' : ''; ?>>2020</option>
                            <option value="2021" <?php echo ($twelfth_pass == '2021') ? 'selected' : ''; ?>>2021</option>
                            <option value="2022" <?php echo ($twelfth_pass == '2022') ? 'selected' : ''; ?>>2022</option>
                            <option value="2023" <?php echo ($twelfth_pass == '2023') ? 'selected' : ''; ?>>2023</option>
                            <option value="2024" <?php echo ($twelfth_pass == '2024') ? 'selected' : ''; ?>>2024</option>
                            <option value="2025" <?php echo ($twelfth_pass == '2025') ? 'selected' : ''; ?>>2025</option>
                            <option value="2026" <?php echo ($twelfth_pass == '2026') ? 'selected' : ''; ?>>2026</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-three">
                    <div class="input-group">
                        <label>CGPA</label>
                        <input type="text" name="cgpa" value="<?php echo htmlspecialchars($cgpa); ?>" placeholder="e.g., 8.5">
                    </div>

                    <div class="input-group">
                        <label>Passing Year</label>
                        <select name="pass">
                            <option value="">Select Year</option>
                            <option value="2024" <?php echo ($pass == '2024') ? 'selected' : ''; ?>>2024</option>
                            <option value="2025" <?php echo ($pass == '2025') ? 'selected' : ''; ?>>2025</option>
                            <option value="2026" <?php echo ($pass == '2026') ? 'selected' : ''; ?>>2026</option>
                            <option value="2027" <?php echo ($pass == '2027') ? 'selected' : ''; ?>>2027</option>
                            <option value="2028" <?php echo ($pass == '2028') ? 'selected' : ''; ?>>2028</option>
                            <option value="2029" <?php echo ($pass == '2029') ? 'selected' : ''; ?>>2029</option>
                            <option value="2030" <?php echo ($pass == '2030') ? 'selected' : ''; ?>>2030</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Backlogs (if any)</label>
                        <input type="text" name="backlogs" value="<?php echo htmlspecialchars($backlogs); ?>" placeholder="0">
                    </div>
                </div>

                <div class="input-group">
                    <label>Apply For</label>
                    <select name="apply">
                        <option value="">Select Option</option>
                        <option value="Internship" <?php echo ($apply == 'Internship') ? 'selected' : ''; ?>>Internship</option>
                        <option value="Placement" <?php echo ($apply == 'Placement') ? 'selected' : ''; ?>>Placement</option>
                    </select>
                </div>

                <!-- Account Security -->
                <div class="section-header">Account Security</div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="st_password1" placeholder="Enter password">
                    </div>

                    <div class="input-group">
                        <label>Confirm Password</label>
                        <input type="password" name="st_password2" placeholder="Confirm password">
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="section-header">Document Upload</div>

                <div class="input-group">
                    <label>Upload Profile Image (Max 512KB)</label>
                    <div class="file-input-wrapper">
                        <label for="image" class="file-input-label">
                            📁 Choose Image File
                        </label>
                        <input type="file" name="image" id="image" accept="image/png, image/jpg, image/jpeg" required>
                        <div class="file-name" id="fileName">No file chosen</div>
                    </div>
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="reg_student" id="submit">Complete Registration</button>
                </div>

                <p class="footer-text">
                    Already registered? <a href="student.php">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // File input display
    $('#image').change(function() {
        var fileName = $(this).val().split('\\').pop();
        $('#fileName').text(fileName || 'No file chosen');
    });

    // Form validation
    $('#submit').click(function(e){
        var image_name = $('#image').val();
        if(image_name == '') {
            alert("Please Select Image");
            e.preventDefault();
            return false;
        } else {
            var extension = $('#image').val().split('.').pop().toLowerCase();
            if(jQuery.inArray(extension, ['png','jpg','jpeg']) == -1) {
                alert('Invalid Image File. Only PNG, JPG, and JPEG are allowed.');
                $('#image').val('');
                $('#fileName').text('No file chosen');
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>

</body>
</html>