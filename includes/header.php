<?php
	//path for home button in header
	session_start();
	require('includes/setup.php');
	$headerText = 'AMCS2 Dashboard';
	
	if(!isset($_SESSION['user']) || $_SESSION['user'] == 'incomplete') {
		header("Location: signin.php");
		exit();
	}
	else { //User is set so we check if they are trying to go to admin and not authenticated
		if(basename($_SERVER['PHP_SELF']) == 'admin.php' && $_SESSION['level'] < PWM_LEVEL) {
			header("Location: index.php");
			exit();
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="includes/css/bootstrap.min.css" media="all" />
		<link rel="stylesheet" type="text/css" href="includes/css/simpletip.css" media="all" />
		<link rel="stylesheet" href="includes/css/style.css" media="all" />
		<link rel="stylesheet" href="includes/css/shadowbox.css" media="all" />

		<script src="includes/js/shadowbox.js"></script>
		<script type="text/javascript">
		Shadowbox.init();
		</script>
		<!--[if lt IE 9]>
			<script src="includes/js/html5shiv.js"></script>
		<![endif]-->
		
        <title>AMCS2</title>
	</head>
	<body>
		<div id="wrap">
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">	
						<a class="brand" href="<?php echo SITE_URL; ?>"><?php echo $headerText; ?> <span class="label label-success">Alpha</span></a>
						<ul class="nav pull-right">
							<li><a href="<?php echo SITE_URL; ?>">Home</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Sites <b class="caret"></b></a>
								 <ul class="dropdown-menu">
					                  <li><a href="sites.php"><i class="icon-th-list"></i> View All</a></li>
					                <?php
					                 	if($_SESSION['level'] >= SUPERVISOR_LEVEL) {
					                  		echo '<li><a href="sites.php?mode=add"><i class="icon-plus-sign"></i> Add New Site</a></li>';
					                 	}
									?>
								</ul>
							</li>
							<li><a href="history.php">History</a></li>
							<?php if($_SESSION['level'] >= SUPERVISOR_LEVEL): ?>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
										 <ul class="dropdown-menu">
							             	<li><a href="rules.php"><i class="icon-th-list"></i> Rules</a></li>
									        <li><a href="#"><i class="icon-print"></i> Reports</a></li>
									        <?php if ($_SESSION['level'] >= ADMIN_LEVEL): ?>
									        	<li><a href="users.php"><i class="icon-user"></i> Users</a></li>
									    	<?php endif; ?>
										</ul>
									</li>
							<?php endif; ?>

							<?php if($_SESSION['level'] >= PWM_LEVEL ): ?>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">PWM Admin <b class="caret"></b></a>
										 <ul class="dropdown-menu">
							             	<li><a href="events.php"><i class="icon-th-list"></i> Events</a></li>
			          		             	<li><a href="licensing.php"><i class="icon-th-list"></i> Licensing</a></li>	
										</ul>
									</li>
							<?php endif; ?>

							<li><a href="changepassword.php">Change Password</a></li>
							<li><a href="signout.php">Sign Out</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="container content">
					<div class="alert alert-notice">
						Site still under development. Please email <a href="mailto:jakec@p-w-m.com">jakec@p-w-m.com</a> to report issues.
					</div>
		