<?php
	require('../includes/setup.php');
	$headerText = 'AMCS2 Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="../includes/css/bootstrap.min.css" media="all" />
		<link rel="stylesheet" href="../includes/css/shadowbox.css" media="all" />
		<link rel="stylesheet" href="../includes/css/style.css" media="all" />

		
		<!--[if lt IE 9]>
			<script src="../includes/js/html5shiv.js"></script>
		<![endif]-->
		
        <title>AMCS2 Install Procedure</title>
        <style type="text/css">
				      body {
				        padding-top: 40px;
				        padding-bottom: 40px;
				        background-color: #f5f5f5;
				      }
				
				      .form-signin {
				        max-width: 650px;
				        padding: 19px 29px 29px;
				        margin: 0 auto 60px;
				        background-color: #fff;
				        border: 1px solid #e5e5e5;
				        -webkit-border-radius: 5px;
				           -moz-border-radius: 5px;
				                border-radius: 5px;
				        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
				           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
				                box-shadow: 0 1px 2px rgba(0,0,0,.05);
				        margin-top: 30px;
					 
				      }
				      .form-signin .form-signin-heading,
				      .form-signin .checkbox {
				        margin-bottom: 10px;
				      }
				      .form-signin input[type="text"],
				      .form-signin input[type="password"] {
				        font-size: 16px;
				        height: auto;
				        margin-bottom: 15px;
				        padding: 7px 9px;
				      }
				      table { max-width: 350px; margin-left: 5%;}
				
			</style>
	</head>
	<body>
		<div id="wrap">
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">	
						<a class="brand" href="<?php echo SITE_URL; ?>"><?php echo $headerText; ?> <span class="label label-success">v1.0</span></a>
						
					</div>
				</div>
			</div>
			<div class="container">
				<form class="form-signin" method="post">
			        <h2 class="form-signin-heading">Install Procedure</h2>
			        <h4>Step 1:</h4>
			        <p>Create A MySQL Database and User that has access to it on this server.</p>
			        <h4>Step 2:</h4>
			        <p>
			        	Import <a href="amcs_db_setup.sql">amcs_db_setup.sql</a> into the database.
			        	It is located in the install folder in the website's base directory. This 
			        	will create the tables and default configuration data in the database.
			        	<em>You should delete or move this file outside of the public web directory
			        	to prevent others from seeing the default login information or database
			        	structure.</em>
			        </p>
			        <h4>Step 3:</h4>
			        <p>
			        	Enable the IMAP Extension in your php.ini. It is disabled by default
			        	but is necessary for this application to read the AMCS emails.
			        </p>
			        <h4>Step 4:</h4>
			        <p>
			        	Manually configure your <a href="../includes/setup.php">setup.php</a> file to contain
			        	your mail server and database server information. It is located in the includes folder
			        	in this website's base directory. This must be correct so that the application can
			        	connect and access the AMCS emails and the information that will be stored in the 
			        	database.
			        </p>
			        <h4>Step 5:</h4>
			        <p>
			        	Create 3 cron jobs to run on your server periodicially. These will run infinitely.
			        	<ol>
			        		<li><a href="">open.php</a> should run at a minimum of every 5 minutes</li>
			        		<li><a href="">close.php</a> should run at a minimum of every 5 minutes</li>
			        		<li><a href="">forward_cns.php</a> should run at a minimum of every 5 minutes</li>
			        	</ol>
			        </p>
			        <h4>Step 6:</h4>
			        <p>
			        	Login <a href="../signin.php">here</a> using one of the default logins. We reccommend you
			        	change the passwords of these accounts after logging in or remove them and add your own users.
			        	Once you login as the admin you can access the users under the Admin section in the 
			        	navigation bar.
			        	<table class="table table-striped">
			        		<tr><th>Username</th><th>Password</th></tr>
			        		<tr><td>admin@p-w-m.com</td><td>admin</td></td></tr>
			        		<tr><td>supervisor@p-w-m.com</td><td>supervisor</td></td></tr>
			        		<tr><td>user@p-w-m.com</td><td>user</td></td></tr>
			        	</table>
			        </p>
			        <h4>Step 7:</h4>
			        <p>
			        	For security reasons, please remove the install directory this page is located in and the 
			        	files within from public access. They can be deleted or moved to a location not reachable
			        	from the interent.
			        </p>
			        <h4>Step 8:</h4>
			         <p>
			        	Contact PWM with your dashboard link. We will have to input your licensed sites before you
			        	can use the software. 
			        </p>
			        <h4>Step 9:</h4>
			        <p>Enjoy!</p>
			        <br>
			        <p class="muted">
			        	Contact <a href="mailto:support@p-w-m.com">support@p-w-m.com</a> with any
			        	questions or comments.
			        </p>
			    </form>

