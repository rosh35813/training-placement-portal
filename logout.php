<?php
session_start();
if(isset($_SESSION['user']))
{
	session_unset();
	unset($_SESSION['admin']);
	unset($_SESSION['company']);
	session_destroy();
}
header("Location:svnit.php");
?>
<!-- for pages before login 
	this is for if tab close then direct open to login page		
			-->
<?php 
/*
	sessionn_start();
	if(isset($_SESSION['user']))
	{
		if($_SESSION['user']=='admin')
		{
			header("Location: index_admin.php")
		}
		if($_SESSION['user']=='student' and $_SESSION['success'] == "internship")
		{
			header("Location: index_student_intern.php")
		}
		if($_SESSION['user']=='student' and $_SESSION['success'] == "placement")
		{
			header("Location: index_student_placement.php")
		}
		if($_SESSION['user']=='company')
		{
			header("Location: index_company.php")
		}
	}
*/
?>
<?php
/*
	sessionn_start();
		if(!isset($_SESSION['user']))
		{
			header("Location: svnit.php")
		}
		if($_SESSION['user']=='student' and $_SESSION['success'] == "internship")
		{
			header("Location: index_student_intern.php")
		}
		if($_SESSION['user']=='student' and $_SESSION['success'] == "placement")
		{
			header("Location: index_student_placement.php")
		}
		if($_SESSION['user']=='company')
		{
			header("Location: index_company.php")
		}
*/
?>
<?php
/*
	sessionn_start();
		if(!isset($_SESSION['user']))
		{
			header("Location: svnit.php")
		}
		if($_SESSION['user']=='admin')
		{
			header("Location: index_admin.php")
		}
		if($_SESSION['user']=='student' and $_SESSION['success'] == "placement")
		{
			header("Location: index_student_placement.php")
		}
		if($_SESSION['user']=='company')
		{
			header("Location: index_company.php")
		}
*/
?>
<?php
/*
	sessionn_start();
		if(!isset($_SESSION['user']))
		{
			header("Location: svnit.php")
		}
		if($_SESSION['user']=='student' and $_SESSION['success'] == "internship")
		{
			header("Location: index_student_intern.php")
		}
		if($_SESSION['user']=='admin')
		{
			header("Location: index_admin.php")
		}
		if($_SESSION['user']=='company')
		{
			header("Location: index_company.php")
		}
*/
?>
<?php
/*
	sessionn_start();
		if(!isset($_SESSION['user']))
		{
			header("Location: svnit.php")
		}
		if($_SESSION['user']=='student' and $_SESSION['success'] == "internship")
		{
			header("Location: index_student_intern.php")
		}
		if($_SESSION['user']=='student' and $_SESSION['success'] == "placement")
		{
			header("Location: index_student_placement.php")
		}
		if($_SESSION['user']=='admin')
		{
			header("Location: index_admin.php")
		}
*/
?>
<?php
/*
<?php include(__DIR__ . '/../config/server.php') ?>
<!doctype html>
<html>
<head>
	 <link rel="shortcut icon" type="image/png" href="../assets/images/Svnit_logo.png">
	<link rel="stylesheet" type="text/css" href="st.css">
</head>
<body>
				<div class="list-unstyled3" >
					<ul>
						<li><img src="../assets/images/Svnit_logo.png" alt="SVNIT SURAT"  class="logo2" height="55"  align="left"/></li>
					    <li style="float:right"><a  href="svnit.php"><img src="out11.png" alt="SVNIT SURAT"   height="24"   /></a></li>
					  <div class="para">
						<p> Training &amp; Placement SVNIT SURAT</p>
			          </div>	
                   </ul>
					
				</div>
			

				
			
</body>
</html>
*/
?>