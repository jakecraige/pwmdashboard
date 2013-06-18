<?php	

	$result = mysqli_query($db, "SELECT * FROM sites WHERE number='$store_number' LIMIT 1");

	if(mysqli_num_rows($result) < 1)

	{

		echo '<h3 class="text-center">No results found.</h3>';

	}

	while($row = mysqli_fetch_array($result)){

		echo '<table class="newsite">';

			echo '<tr>';

				echo '<th>Number:</th>';

				echo '<td>'.$row['number'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Name:</th>';

				echo '<td>'.$row['name'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Address:</th>';

				echo '<td>'.$row['address'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Phone:</th>';

				echo '<td>'.$row['phone'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Warranty Expiration:</th>';

				echo '<td>'.formatDate(checkWarranty($row['warranty_expires'])).'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Signs(#):</th>';

				echo '<td>'.$row['num_signs'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>CU v.:</th>';

				echo '<td>'.$row['cu_version'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>AMCS v.:</th>';

				echo '<td>'.$row['amcs_version'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>POS Type:</th>';

				echo '<td>'.$row['pos'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Tramsission Rate:</th>';

				$trans_rate = $row['trans_rate'];

				switch($trans_rate) {

						case 5:

							$trans_rate = '5 Minutes';

							break;

						case 15:

							$trans_rate = '15 Minutes';

							break;

						case 30:

							$trans_rate = '30 Minutes';

							break;

						case 60:

							$trans_rate = '1 Hour';

							break;

						case 120:

							$trans_rate = '2 Hour';

							break;

						case 180:

							$trans_rate = '3 Hour';

							break;

						case 240:

							$trans_rate = '4 Hour';

							break;

						case 300:

							$trans_rate = '5 Hour';

							break;

						case 0:

							$trans_rate = 'Never';

							break;

					}

				echo '<td>'.$trans_rate.'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>P.C. Manufacturer:</th>';

				echo '<td>'.$row['pc_manufacture'].'</td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Active:</th>';

				echo '<td>'.$row['active'].'</td>';

			echo '</tr>';

					echo '</table>';	

			?>

		</div> <!-- end span6 -->

		<div class="span5">

			<?php

					$sign[0] = unserialize($row['sign1']);

					$sign[1] = unserialize($row['sign2']);

					$sign[2] = unserialize($row['sign3']);

					$sign[3] = unserialize($row['sign4']);

					

					$connection = explode(' ', $row['connection']);

					$count = 0; //used for picking right value for checkboxes

					$connCount = 0; //used for picking right value for connection radio

					$signCount = 0;

					for($x = 0; $x<$row['num_signs']; $x++)

					{

				?>

					<fieldset>

						<legend>

							<b>Sign <?= $x+1 ?>:</b>

							

						</legend>

						<label class="checkbox inline">

						  	<input type="checkbox" disabled name="sign<?= $x+1 ?>[0]" value="UNL/Cash"

						  		<?= $sign[$signCount][$count++] == 'UNL/Cash' ? 'checked' : ''; ?>> UNL/Cash

						</label>

						<label class="checkbox inline">

						  	<input type="checkbox" disabled name="sign<?= $x+1 ?>[1]" value="UNL/Credit"

						  		<?= $sign[$signCount][$count++] == 'UNL/Credit' ? 'checked' : ''; ?>> UNL/Credit

						</label>

						<label class="checkbox inline">

						  	<input type="checkbox" disabled name="sign<?= $x+1 ?>[2]" value="Super"

						  		<?= $sign[$signCount][$count++] == 'Super' ? 'checked' : ''; ?>> Super

						</label>

						<label class="checkbox inline">

						  	<input type="checkbox" disabled name="sign<?= $x+1 ?>[3]" value="Plus"

						  		<?= $sign[$signCount][$count++] == 'Plus' ? 'checked' : ''; ?>> Plus

						</label>

						<label class="checkbox inline">

						  	<input type="checkbox" disabled name="sign<?= $x+1 ?>[4]" value="Diesel"

						  		<?= $sign[$signCount][$count++] == 'Diesel' ? 'checked' : ''; ?>> Diesel

						</label>

						<label class="radio inline">

						  	<input type="radio" disabled name="connection<?= $x+1 ?>" value="Wireless"

						  		<?= $connection[$connCount] == 'Wireless' ? 'checked' : ''; ?>>

						  	Wireless

						</label>

						<label class="radio inline">

						 	<input type="radio" disabled name="connection<?= $x+1 ?>" value="Wired"

						 		<?= $connection[$connCount] == 'Wired' ? 'checked' : ''; ?>>

						  	Wired

						</label>

							<?php 

								$connCount++; //have to increment after both have been displayed 

								$signCount++;

								$count = 0;

							?>

					</fieldset>

					<br>

			<?php	

				} //edn for loop

			if($_SESSION['level'] >= SUPERVISOR_LEVEL)

			{		

				echo'	<div class="btn-group">

							<a href="'.$_SERVER['REQUEST_URI'].'&mode=edit" class="btn btn-success">Edit</a>

							<a class="btn btn-danger" name="delete" id ="delete_site" value="Delete" 

									onclick="confirmDelete(\'' . $row['id'] . '\')">Delete</a>

						</div>';

			}

	} /*end while loop*/

?>