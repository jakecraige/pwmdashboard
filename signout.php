<?php
	session_start();
	$_SESSION['user'] = "incomplete"; 

	require("includes/setup.php");
	
	$page = "signin";
	$headerText = 'AMCS2 Dashboard';
	
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	$errors = '';
	$email = mysqli_real_escape_string($db, $_REQUEST['email']);
	
	$user_sql = "SELECT * FROM users WHERE email='$email'";
	$user_query = mysqli_query($db, $user_sql);
	while($user = mysqli_fetch_array($user_query)) {
		$db_pass = $user['password'];
		$user_level = $user['level'];
	}
	
	if($_REQUEST['password'] && $_REQUEST['email']) {
		if(md5($_REQUEST['password']) == $db_pass) {
			$_SESSION['user'] = $email;
			$_SESSION['level'] = $user_level;
			header("Location: index.php");
			exit(0);
		}
	}
	if($_SESSION['user'] != "incomplete") {
		header("Location: index.php");
		exit(0);
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="includes/css/bootstrap.min.css" media="all" />
		<link rel="stylesheet" href="includes/css/shadowbox.css" media="all" />
		<link rel="stylesheet" type="text/css" href="includes/css/simpletip.css" media="all" />
		<link rel="stylesheet" href="includes/css/style.css" media="all" />

		
		<!--[if lt IE 9]>
			<script src="includes/js/html5shiv.js"></script>
		<![endif]-->
		
        <title>AMCS2 Sign In</title>
        <style type="text/css">
				      body {
				        padding-top: 40px;
				        padding-bottom: 40px;
				        background-color: #f5f5f5;
				      }
				
				      .form-signin {
				        max-width: 300px;
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
				
			</style>
	</head>
	<body>
		<div id="wrap">
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">	
						<a class="brand" href="<?php echo SITE_URL; ?>"><?php echo $headerText; ?> <span class="label label-success">Alpha</span></a>					
					</div>
				</div>
			</div>
			<div class="container">
				<p class="alert alert-success">You have successfully logged out.</p>
				<form class="form-signin" method="post">
			        <h2 class="form-signin-heading">Please sign in</h2>
			        <input type="text" name="email" class="input-block-level" placeholder="Email address">
			        <input type="password" name="password" class="input-block-level" placeholder="Password">
			        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
			        <br><br><a href="forgot.php">Forgot your password?</a>
			      </form>
<?php include('includes/footer.php'); ?>