<?php
	$license = mysqli_real_escape_string($db, $_GET['license']);

	if(isset($_POST['save']))
	{
		$site_number = mysqli_real_escape_string($db, $_POST['site_number']);
		$error = '';


		if(empty($_POST['site_number'])) { $error = 'You must fill in site number field.'; } 
		else if(!preg_match('|^\d+$|', $site_number)) { $error = 'You must only enter numbers as the site number.'; }

		if(empty($error))
		{
			if($update = mysqli_query($db, "UPDATE licensed_sites SET site_number='$site_number' WHERE id='$license'"))
			{
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>
						Site License Updated.</div>';
			}
			else 
			{
				echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>
						Error updating. Contact Administrator.</div>';
			}
		}
		else 
		{
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'
					.$error.'</div>';
		}
	}
	$result = mysqli_query($db, "SELECT id, site_number FROM licensed_sites WHERE id='$license' LIMIT 1");
?>

<form class="form-inline" method="post">
	<table class="collection noHover">
		<tr>
			<td>
				Site Number: 
			<?php while($site = mysqli_fetch_array($result)): ?>
				<input type="text" class="input-medium" name="site_number" value=<?= $site['site_number'] ?>>
				<?php $id = $site['id']; ?>
			<?php endwhile; ?>
		
				<div class="btn-group">
					<input name="save" type="submit" class="btn btn-success" value="Save">
					<!-- <input name="delete" type="submit" class="btn btn-danger" value="Delete"> -->
					<span class="btn btn-danger" onclick="confirmDelete('<?= $id ?>')">Delete</span>
				</div>
			</td>
		</tr>
	</table>
</form>