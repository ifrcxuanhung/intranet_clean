<?php ob_start(); ?>
<?php include_once('classes/translate.class.php'); ?>
<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>MANAGE USERS</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="VNDMI PHP Login script">
		<meta name="author" content="VNDMI">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le styles -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/jigowatt.css" rel="stylesheet">

		<link rel="shortcut icon" href="//jigowatt.co.uk/favicon.ico">
	</head>

	<body>

<!-- Navigation
================================================== -->

	<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	    </div>
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <div class="page-title col-md-6">
        <?php $url = explode("user-manage",$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			$baseURL = $url[0];
		?>
               <a href="http://<?php echo $baseURL;?>" style="text-decoration:none;"> <h3 style="margin-top: 18px; color:#fcd116 !important; font-weight: bold;">BACKEND MINI</h3></a>
                  
           </div>
	    <!--  <ul class="nav navbar-nav">
				<li><a href="http://local.itvn.fr/test/vnvdm_demo/"><?php _e('Home'); ?></a></li>
				<li><a href="protected.php"><?php _e('Secure page'); ?></a></li>
				<li><a href="admin/"><?php _e('Admin panel'); ?></a></li>
			</ul>-->
		<?php if(isset($_SESSION['intranet_clean']['username'])) { ?>
		<!--<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<p class="navbar-text dropdown-toggle" data-toggle="dropdown" id="userDrop">
					<?php echo $_SESSION['intranet_clean']['gravatar']; ?>
					<a href="#"><?php echo $_SESSION['intranet_clean']['username']; ?></a>
					<b class="caret"></b>
				</p>
				<ul class="dropdown-menu">
		<?php if(in_array(1, $_SESSION['intranet_clean']['user_level'])) { ?>
					<li><a href="admin/index.php"><i class="glyphicon glyphicon-home"></i> <?php _e('Control Panel'); ?></a></li>
					<li><a href="admin/settings.php"><i class="glyphicon glyphicon-cog"></i> <?php _e('Settings'); ?></a></li> <?php } ?>
					<li><a href="profile.php"><i class="glyphicon glyphicon-user"></i> <?php _e('My Account'); ?></a></li>
					<li><a href="http://phplogin.jigowatt.co.uk/install.php"><i class="glyphicon glyphicon-info-sign"></i> <?php _e('Help'); ?></a></li>
					<li class="divider"></li>
					<li><a href="logout.php"><?php _e('Sign out'); ?></a></li>
				</ul>
			</li>
		</ul>-->
       
		<a href="http://<?php echo $baseURL;?>" class="btn btn-primary navbar-right" style="margin-right:0px; margin-top:10px; text-decoration:none;">
		<?php _e('BACK TO BACKEND'); ?>
        </a>
		<?php } else { ?>
        
        <a href="http://<?php echo $baseURL;?>" class="btn btn-primary navbar-right" style="margin-right:0px; margin-top:10px; text-decoration:none;">
		<?php _e('BACK TO BACKEND'); ?>
        </a>
        
		<!--<ul class="nav navbar-nav navbar-right">
			<li><a href="login.php" class="signup-link"><em><?php _e('Have an account?'); ?></em> <strong><?php _e('Sign in!'); ?></strong></a></li>
		</ul>-->
		<?php } ?>
   </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>

<!-- Main content
================================================== -->
		<div class="container" >
			<div class="row">

				<div class="col-md-12">
