<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Amazing Wristbands | Login </title>
<?php include("includes/files.php"); ?>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php //get_header();  ?>
<?php include ('includes/header.php'); ?>
<div class="outer-wrapper">
    <?php include ('includes/social-media-bar.php'); ?>
    <div class="main-content">
    	<?php include("class/left-bar-class.php"); ?>
        <div class="content">
        	<div class="con-top"></div>
            <div class="con-middle">
              <?php include("class/login-class.php"); ?>
              </div>
            <div class="con-bottom"></div>
        </div>
    </div>
    <?php get_footer(); ?>
</div>
</body>
</html>
