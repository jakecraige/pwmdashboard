<?php
	include('includes/header.php');
	require('includes/includes.php');

	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo "Error connecting to database.";
	}
	if($_SESSION['level'] >= PWM_LEVEL) {
		if(isset($_GET['license']))
		{
			include('includes/licensing/edit_license.php');
		}
		else if(isset($_GET['mode']) && $_GET['mode'] == 'add')
		{
			include('includes/licensing/add_license.php');
		}

		if($_GET['mode'] == 'delete' && isset($_GET['id']))
		{
			$id = $_GET['id'];
			$query = "DELETE FROM licensed_sites WHERE id='$id'";
			if($res = mysqli_query($db, $query)) {
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>';
				echo 'License for Site Deleted.';
			echo '</div>';
			}
			else {
				echo '<p class="error">Error Deleting License for Site. Contact Administrator.</p>';
				do_log('licensing.php', 'MySql Error deleting license for site.', mysqli_real_escape_string($db, $query), 'Failure', $db);
			}
		}
?>	
	<!-- Display Users Table -->
	<table class="collection">
		<tr>
			<th>Site Number Licensed</th>
		<tr>
		<tr>
			<td><a href="licensing.php?mode=add"><i>Add New Site</i></a></td>
		</tr>
		<?php $sites = mysqli_query($db, "SELECT id, site_number FROM licensed_sites ORDER BY site_number ASC"); ?>
		
		<?php while($site = mysqli_fetch_array($sites)): ?>
			<tr>
				<td><a href="licensing.php?license=<?= $site['id'] ?>"><?= $site['site_number'] ?></a></td>
			</tr>
		<?php endwhile; ?>

	</table>

<?php
	} /*end session level auth*/
	else {
		echo '<p class="alert alert-error">You are not authorized to view this page.</p>';
	}
	include('includes/footer.php');
	mysqli_close($db);
?>