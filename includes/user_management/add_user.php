<?php

	if(isset($_POST['add']))

	{

		//Update User

		$email = mysqli_real_escape_string($db, $_POST['email']);

		$level = mysqli_real_escape_string($db, $_POST['perm']);

		$error = '';

		if(!filter_var($email, FILTER_VALIDATE_EMAIL))

		{

			$error .= 'Email is not valid.<br>';

		}

		else {

			if(mysqli_num_rows(mysqli_query($db, "SELECT email FROM users WHERE email='$email'")) > 0)

			{

				$error .= 'User with this email already exists.<br>';

			}

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

				$error .= 'Passwords do not match.<br>';

			}

		}

		else if(!empty($_POST['password'])) { $error .= 'You must fill in the confirmation field.'; } 

		else if(!empty($_POST['pass_conf'])) { $error .= 'You must fill in the password field.'; }

		else { $error .= 'You must enter a password and the confirmation.'; }



		if(empty($error))

		{

			if(!empty($pass) && !empty($pass_conf))

			{ //Meaning password is set so we should update it

				$pass = md5($pass);

				if($insert = mysqli_query($db, "INSERT INTO users VALUES (0, '$email', '$pass', '$level')"))

				{

					$success = TRUE;

				}

			}

			if($success)

			{

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>User Added.</div>';

			}

		}

		else 

		{

			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$error.'</div>';

		}



	}

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

				<td><input type="text" class="input-medium" name="email" value=<?= $_POST['email'] ?>></td>

				<td><input type="password" class="input-medium" name="password"></td>

				<td><input type="password" class="input-medium" name="pass_conf"></td>

				<td>

					<select name="perm" class="input-medium">

						<option value="<?= USER_LEVEL ?>" <?= $_POST['level'] == USER_LEVEL ? 'selected="selected"' : ''; ?>>User</option>

						<option value="<?= SUPERVISOR_LEVEL ?>" <?= $_POST['level'] == SUPERVISOR_LEVEL ? 'selected="selected"' : ''; ?>>Supervisor</option>
						<?php if($_SESSION['level'] >= ADMIN_LEVEL): ?>

							<option value="<?= ADMIN_LEVEL ?>" <?= $_POST['level'] == ADMIN_LEVEL ? 'selected="selected"' : ''; ?>>Admin</option>

						<?php endif; ?>

						<?php if($_SESSION['level'] >= PWM_LEVEL): ?>

							<option value="<?= PWM_LEVEL ?>" <?= $_POST['level'] == PWM_LEVEL ? 'selected="selected"' : ''; ?>>PWM Admin</option>

						<?php endif; ?>

					</select>

				</td>

				<td>	

					<input name="add" type="submit" class="btn btn-success" value="Add">

				</td>

			</tr>

		</table>

	</form>