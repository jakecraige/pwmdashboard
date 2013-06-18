<?php
	include('includes/header.php');
	require('includes/includes.php');

	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo "Error connecting to database.";
	}
	if(isset($_POST['update']))
	{
		$error = '';
		//check old passwod
		$old_password = mysqli_real_escape_string($db, $_POST['old_password']);
		$old_password = md5($old_password);
		$email = $_SESSION['user'];

		//check if logged in email and password match
		if(mysqli_num_rows(mysqli_query($db, "SELECT id FROM users WHERE password='$old_password' AND email='$email'")) == 0)
		{
			$error .= 'Current password is incorrect<br>';
		}

		//check if new passwords are filled in and valid
		if(!empty($_POST['password']) && !empty($_POST['pass_conf']))
		{

			if($_POST['password'] == $_POST['pass_conf'])
			{
				$pass = mysqli_real_escape_string($db, $_POST['password']);
				$pass_conf = mysqli_real_escape_string($db, $_POST['pass_conf']);
			}
			else
			{
				$error .= 'Passwords do not match';
			}
		}
		else if(!empty($_POST['password'])) { $error .= 'You must fill in the confirmation field.'; } 
		else if(!empty($_POST['pass_conf'])) { $error .= 'You must fill in the password field.'; }
		else { $error .= 'You must enter a new password and the confirmation.'; }

		if(empty($error))
		{
			$pass = md5($pass);
			if(mysqli_query($db, "UPDATE users SET password='$pass' WHERE email='$email'"))
			{
				$success = 'Updated password.';
			}
		}
	}
?>
				
	<form method="post">
		<fieldset>
			<legend>Change your Password</legend>
			<?php 
				if(!empty($error)) {
					echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$error.'</div>';
				} 
				else if(!empty($success)) {
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$success.'</div>';
				}
			?>
			<label for="old_password">Current Password</label>
			<input type="password" class="input-medium" id="old_password" name="old_password">

			<label for="password">New Password</label>
			<input type="password" class="input-medium" id="password" name="password">

			<label for="pass_conf">Password Confirmation</label>
			<input type="password" class="input-medium" id="pass_conf" name="pass_conf">

			<br>
			<button type="submit" name="update" class="btn btn-primary">Update</button>
		</fieldset>
	</form>


<?php
	include('includes/footer.php');
	mysqli_close($db);
?>