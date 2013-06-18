<?php
	include('includes/header.php');
	require('includes/includes.php');

	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo "Error connecting to database.";
	}
	if($_SESSION['level'] >= ADMIN_LEVEL) {
		if(isset($_GET['user']))
		{
			include('includes/user_management/edit_user.php');
		}
		else if(isset($_GET['mode']) && $_GET['mode'] == 'add')
		{
			include('includes/user_management/add_user.php');
		}

		if($_GET['mode'] == 'delete' && isset($_GET['id']))
		{
			$id = $_GET['id'];
			$query = "DELETE FROM users WHERE id='$id'";

			if($res = mysqli_query($db, $query)) {
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>';
				echo 'User Deleted.';
			echo '</div>';
			}
			else {
				echo '<p class="error">Error Deleting User. Contact Administrator.</p>';
				do_log('rules', 'MySql Error deleting user.', mysqli_real_escape_string($db, $query), 'Failure', $db);
			}
		}
?>	
	<!-- Display Users Table -->
	<table class="collection">
		<tr>
			<th>Email</th>
			<th>Permissions</th>
		<tr>
		<tr>
			<td colspan="2"><a href="users.php?mode=add"><i>Add New User</i></a></td>
		</tr>
		<?php
			if($_SESSION['level'] >= ADMIN_LEVEL) {
				$users = mysqli_query($db, "SELECT email, level FROM users ORDER BY email ASC");
			}
			else {
				$users = mysqli_query($db, "SELECT email, level FROM users WHERE level>'1' ORDER BY email ASC");
			}
			while($user = mysqli_fetch_array($users)) 
			{
				switch($user['level']) {
					case PWM_LEVEL: 
						$perm = 'PWM Admin';
						break;
					case ADMIN_LEVEL:
						$perm = 'Admin';
						break;
					case SUPERVISOR_LEVEL:
						$perm = 'Supervisor';
						break;
					case USER_LEVEL:
						$perm = 'User';
						break;
					default:
						$perm = 'Error';
				}	
				?>
				<tr>
					<td><a href="users.php?user=<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
					<td><a href="users.php?user=<?= $user['email'] ?>"><?= $perm ?></a></td>
				</tr>
			
		<?php } //end while ?>
	</table>

<?php
	} /*end session level auth*/
	else {
		echo '<p class="alert alert-error">You are not authorized to view this page.</p>';
		//echo $_SESSION['level'].'>='.ADMIN_LEVEL;
	}
	include('includes/footer.php');
	mysqli_close($db);
?>