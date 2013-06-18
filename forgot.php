<?php 
	session_start();
	require("includes/setup.php");
	require("includes/functions.php");
	$page = "forgot_password";
	$headerText = 'AMCS2 Dashboard';
	
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	if(isset($_POST['send']))
	{
		$error = '';
		$email = mysqli_real_escape_string($db, $_POST['email']);
		
		if(mysqli_num_rows(mysqli_query($db, "SELECT email FROM users WHERE email='$email'")) == 0)
		{
			$error .= 'User with this email does not exist.<br>';
		}
		else 
		{
			//create password
			$new_password = generatePassword(8);
			$hashed_pass = md5($new_password);
			//update password
			if($update = mysqli_query($db, "UPDATE users SET password='$hashed_pass' WHERE email='$email'"))
			{
				//send email with new password
				$headers = "From: " . strip_tags($email) . "\r\n";
				$headers .= "Reply-To: ". strip_tags($email) . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$message = '<html><body>';
				$message .= '<h2>Password Reset<h2>';
				$message .= "<p>Your new password is: $new_password</p>";
				$message .= '<p>Please login with it <a href="http://jakecraige.com/projects/pwm-bootstrap/signin.php">here.</a></p>'; 
				$message .= '<p>Once you log in you can update your password if you wish.</p>';
				$message .= '</body></html>';

				if(mail($email, 'Your new password!', $message, $headers)) { $success = 'Email Sent!'; }
			}
		}
	}
	/*$user_sql = "SELECT * FROM users WHERE email='$email'";
	$user_query = mysqli_query($db, $user_sql);
	while($user = mysqli_fetch_array($user_query)) {
		$db_pass = $user['password'];
		$user_level = $user['level'];
	}
	*/

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
		
        <title>AMCS2 Forgot Password</title>
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
				<form class="form-signin" method="post">
					<?php 
						if(!empty($error)) {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$error.'</div>';
						} 
						else if(!empty($success)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$success.'</div>';
						}
					?>
			        <h2 class="form-signin-heading">Forgot password</h2>
			        <input type="text" name="email" class="input-block-level" placeholder="Email address">
			        <button class="btn btn-large btn-primary" name="send" type="submit">Send me a new password</button>
			        <br><br><a href="signin.php">Sign In</a>
			      </form>

<?php include('includes/footer.php'); ?>
