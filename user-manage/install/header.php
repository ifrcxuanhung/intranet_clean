<?php include_once( '../classes/translate.class.php' ); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Jigowatt: PHP Login</title>

		<meta name="description" content="Jigowatt PHP Login script">
		<meta name="author" content="Matt Gates | Jigowatt">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le styles -->
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="../assets/css/jigowatt.css" rel="stylesheet">

		<link rel="shortcut icon" href="//jigowatt.co.uk/favicon.ico">
	</head>

	<body>

<!-- Navigation
================================================== -->

	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="nav navbar-nav">
				<div class="container">

				<a class="btn navbar-btn" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="glyphicon glyphicon-bar"></span>
					<span class="glyphicon glyphicon-bar"></span>
					<span class="glyphicon glyphicon-bar"></span>
				</a>

				<a class="navbar-brand" href="../home.php"><?php _e('Jigowatt'); ?></a>
				<div class="navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="../home.php"><?php _e('Home'); ?></a></li>
						<li><a href="../protected.php"><?php _e('Secure page'); ?></a></li>
						<li><a href="../admin/"><?php _e('Admin panel'); ?></a></li>
					</ul>
					<ul class="nav pull-right">
						<li><a href="../login.php" class="signup-link"><em><?php _e('Have an account?'); ?></em> <strong><?php _e('Sign in!'); ?></strong></a></li>
					</ul>
				</div>
				</div>
			</div><!-- /nav navbar-nav -->
		</div><!-- /navbar -->
	</div><!-- /navbar-wrapper -->

<!-- Main content
================================================== -->
		<div class="container">
			<div class="row">
				<div class="col-md-12">
