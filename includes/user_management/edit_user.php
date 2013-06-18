<?php

	$user_email = mysqli_real_escape_string($db, $_GET['user']);

	if(isset($_POST['save']))
	{
		//Update User
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$level = mysqli_real_escape_string($db, $_POST['perm']);
		$error = '';
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$error .= 'Email is not valid.<br>';
		}

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
		//else { $error .= 'You must enter a password and the confirmation.'; }

		if(empty($error))
		{
			if(!empty($pass) && !empty($pass_conf))
			{ //Meaning password is set so we should update it
				$pass = md5($pass);
				if($update = mysqli_query($db, "UPDATE users SET email='$email', password='$pass', level='$level' WHERE email='$user_email'"))
				{
					$success = TRUE;
				}
			}
			else
			{
				if($update = mysqli_query($db, "UPDATE users SET email='$email', level='$level' WHERE email='$user_email'"))
				{
					$success = TRUE;
				}
			}
			if($success)
			{
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>User Updated.</div>';
			}
		}
		else 
		{
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$error.'</div>';
		}
	}
	$result = mysqli_query($db, "SELECT email, level, id FROM users WHERE email='$user_email' LIMIT 1");
?>

<form class="form-inline" method="post">
	<table class="collection noHover">
		<tr>
			<th>Email</th>
			<th>New Password</th>
			<th>Password Confirmation</th>
			<th>Permissions</th>
			<th></th>
		</tr>
		<tr>
			<?php while($usr = mysqli_fetch_array($result)): ?>

				<td><input type="text" class="input-medium" name="email" value=<?= $usr['email'] ?>></td>
				<td><input type="password" class="input-medium" name="password"></td>
				<td><input type="password" class="input-medium" name="pass_conf"></td>
				<td>
					<select name="perm" class="input-medium">						
						<option value="<?= USER_LEVEL ?>" <?= $usr['level'] == USER_LEVEL ? 'selected="selected"' : ''; ?>>User</option>
						<option value="<?= SUPERVISOR_LEVEL ?>" <?= $usr['level'] == SUPERVISOR_LEVEL ? 'selected="selected"' : ''; ?>>Supervisor</option>
						
						<?php if($_SESSION['level'] >= ADMIN_LEVEL): ?>
							<option value="<?= ADMIN_LEVEL ?>" <?= $usr['level'] == ADMIN_LEVEL ? 'selected="selected"' : ''; ?>>Admin</option>
						<?php endif; ?>

						<?php if($_SESSION['level'] >= PWM_LEVEL): ?>
							<option value="<?= PWM_LEVEL ?>" <?= $usr['level'] == PWM_LEVEL ? 'selected="selected"' : ''; ?>>PWM Admin</option>
						<?php endif; ?>
					</select>
				</td>
				<?php $id = $usr['id']; ?>
			<?php endwhile; ?>
			<td>	
				<div class="btn-group">
					<input name="save" type="submit" class="btn btn-success" value="Save">
					<!-- <input name="delete" type="submit" class="btn btn-danger" value="Delete"> -->
					<span class="btn btn-danger" onclick="confirmDelete('<?= $id ?>')">Delete</span>
				</div>
			</td>
		</tr>
	</table>
</form>