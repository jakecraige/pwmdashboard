<?php

	if(isset($_POST['add']))
	{
		$site_number = mysqli_real_escape_string($db, $_POST['site_number']);
		$error = '';


		if(empty($_POST['site_number'])) { $error = 'You must fill in site number.'; } 
		else if(!preg_match('|^\d+$|', $site_number)) { $error = 'You must only enter numbers as the site number.'; }

		if(empty($error))
		{
			//First check if value is unique
			if(mysqli_num_rows(mysqli_query($db, "SELECT id FROM licensed_sites WHERE site_number='$site_number'")) == 0)
			{
				if($insert = mysqli_query($db, "INSERT INTO licensed_sites VALUES(0, '$site_number')"))
				{
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>
							Site License Added.</div>';
				}
				else 
				{
					echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>
							Error updating. Contact Administrator.</div>';
				}
			}
			else 
			{
				echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>
							License for this site exists. Try again with another site number.</div>';
			}
		}
		else
		{
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'
					.$error.'</div>';
		}
	}
?>

<form class="form-inline" method="post">
	<table class="collection noHover">
		<tr>
			<td>
				Site Number: 
				<input type="text" class="input-medium" name="site_number" value="<?= $_POST['site_number'] ?>">
		
				<div class="btn-group">
					<input name="add" type="submit" class="btn btn-success" value="Add License">
				</div>
			</td>
		</tr>
	</table>
</form>