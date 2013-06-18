<?php

if($_SESSION['level'] >= SUPERVISOR_LEVEL)

{

	if(isset($_POST['save']))

	{

		$number = mysqli_real_escape_string($db, $_POST['number']);

		$name = mysqli_real_escape_string($db, $_POST['name']);

		$phone = mysqli_real_escape_string($db, $_POST['phone']);

		$address = mysqli_real_escape_string($db, $_POST['address']);

		$num_signs = mysqli_real_escape_string($db, $_POST['num_signs']);

		$trans_rate = mysqli_real_escape_string($db, $_POST['trans_rate']);



		$connections = $_POST['connection1'] .' '. $_POST['connection2'] .' '.

						$_POST['connection3'] .' '. $_POST['connection4'];

		$connection = mysqli_real_escape_string($db, $connections);

		//print_r(unserialize(serialize($_POST['sign1'])));



		$sign1 = mysqli_real_escape_string($db, serialize($_POST['sign1']));

		$sign2 = mysqli_real_escape_string($db, serialize($_POST['sign2']));

		$sign3 = mysqli_real_escape_string($db, serialize($_POST['sign3']));

		$sign4 = mysqli_real_escape_string($db, serialize($_POST['sign4']));



		$cu_version = mysqli_real_escape_string($db, $_POST['cu_version']);

		$amcs_version = mysqli_real_escape_string($db, $_POST['amcs_version']);

		$active = mysqli_real_escape_string($db, $_POST['active']);

		$pos = mysqli_real_escape_string($db, $_POST['pos']);

		

		$warranty_expires = mysqli_real_escape_string($db, $_POST['warranty_expires']);

		//echo "EXPIRES: $warranty_expires";

		$warranty_expires = strtotime(str_replace('-', '/', $warranty_expires)); //replace for american date

		//echo "<br>STRTOTIME: $warranty_expires";

		$pc_manufacture = mysqli_real_escape_string($db, $_POST['pc_manufacture']);



		//Update DB with new data

		$sql = "UPDATE sites SET number='$store_number', name='$name', address='$address',

					phone='$phone', num_signs='$num_signs', trans_rate='$trans_rate', connection='$connection', cu_version='$cu_version',

					amcs_version='$amcs_version', active='$active', pc_manufacture='$pc_manufacture', pos='$pos', warranty_expires='$warranty_expires',

					sign1='$sign1', sign2='$sign2', sign3='$sign3', sign4='$sign4'

					WHERE number='$store_number'";

		if($result = mysqli_query($db, $sql))

		{

			echo '<p class="alert alert-success">Updated Successfully.</p>';

		}

		else

		{

			echo '<p class="alert alert-error">Error Updating.<br>'.mysqli_error($db).'<br></p>';

		}

	}

	



	$result = mysqli_query($db, "SELECT * FROM sites WHERE number='$store_number' LIMIT 1");

	if(mysqli_num_rows($result) < 1)

	{

		echo '<h3 class="text-center">No results found.</h3>';

	}

	while($row = mysqli_fetch_array($result)){

		//echo $connection.'<br>';

		//echo $grade_on_sign;

		echo '<form method="post" class="form-horizontal">';

		echo '<table class="newsite">';

			echo '<tr>';

				echo '<th>Number:</th>';

				echo '<td><input type="text" name="store_number" value="'.$row['number'].'"></td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Name:</th>';

				echo '<td><input type="text" name="name" value="'.$row['name'].'"></td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Address:</th>';

				echo '<td><input type="text" name="address" value="'.$row['address'].'"></td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Phone:</th>';

				echo '<td><input type="text" name="phone" value="'.$row['phone'].'"></td>';

			echo '</tr>';

			echo '<tr>';

				echo '<th>Warranty Expiration:</th>';

				echo '<td><input type="text" name="warranty_expires" value="'.formatDate($row['warranty_expires']).'"></td>';

			echo '</tr>';

			?>

			<tr>

				<th>Signs(#):</th>

				<td>

					<select name="num_signs">

						<option value="1" <?= $row['num_signs'] == '1' ? 'selected="selected"' : ''; ?>>1</option>

						<option value="2" <?= $row['num_signs'] == '2' ? 'selected="selected"' : ''; ?>>2</option>

						<option value="3" <?= $row['num_signs'] == '3' ? 'selected="selected"' : ''; ?>>3</option>

						<option value="4" <?= $row['num_signs'] == '4' ? 'selected="selected"' : ''; ?>>4</option>

					</select>

				</td>

			</tr>

			<tr>

				<th>CU v.:</th>

				<td>

					<select name="cu_version">

						<option value="9.04" <?= $row['cu_version'] == '9.04' ? 'selected="selected"' : ''; ?>>9.04</option>

						<option value="8.00" <?= $row['cu_version'] == '8.00' ? 'selected="selected"' : ''; ?>>8.00</option>

					</select>

				</td>

			</tr>

			<tr>

				<th>AMCS v.:</th>

				<td>

					<select name="amcs_version">

						<option value="1.00" <?= $row['amcs_version'] == '1.00' ? 'selected="selected"' : ''; ?>>1.00</option>

						<option value="0.94" <?= $row['amcs_version'] == '0.94' ? 'selected="selected"' : ''; ?>>0.94</option>

					</select>

				</td>

			</tr>

			<tr>

				<th>POS Type:</th>

				<td>

					<select name="pos">

						<option value="Andi" <?= $row['pos'] == 'Andi' ? 'selected="selected"' : ''; ?>>Andi</option>

						<option value="Sapphire" <?= $row['pos'] == 'Sapphire' ? 'selected="selected"' : ''; ?>>Sapphire</option>

					</select>

				</td>

			</tr>

			<tr>

				<th>Tramsission Rate:</th>

				<td>

					<select name="trans_rate">

						<option value="5" <?= $row['trans_rate'] == '5' ? 'selected="selected"' : ''; ?>> 5 Minutes</option>

						<option value="15" <?= $row['trans_rate'] == '15' ? 'selected="selected"' : ''; ?>> 15 Minutes</option>

						<option value="30" <?= $row['trans_rate'] == '30' ? 'selected="selected"' : ''; ?>> 30 Minutes</option>

						<option value="60" <?= $row['trans_rate'] == '60' ? 'selected="selected"' : ''; ?>> 1 Hour</option>

						<option value="120" <?= $row['trans_rate'] == '120' ? 'selected="selected"' : ''; ?>> 2 Hours</option>

						<option value="180" <?= $row['trans_rate'] == '180' ? 'selected="selected"' : ''; ?>> 3 Hours</option>

						<option value="240" <?= $row['trans_rate'] == '240' ? 'selected="selected"' : ''; ?>> 4 Hours</option>

						<option value="300" <?= $row['trans_rate'] == '300' ? 'selected="selected"' : ''; ?>> 5 Hours</option>

						<option value="0" <?= $row['trans_rate'] == '0' ? 'selected="selected"' : ''; ?>> Never</option>

					</select>

				</td>

			</tr>

			<tr>

				<th>P.C. Manufacturer:</th>

				<td>

					<select name="pc_manufacture">

						<option value="None">None</option>

						<?

							$pc = mysqli_query($db, "SELECT name FROM pc_manufacturers");

							while($manu = mysqli_fetch_array($pc)): ?>

								<option value="<?= $manu['name'] ?>"

									<?= $row['pc_manufacture'] == $manu['name'] ? 'selected="selected"' : ''; ?>> <?= $manu['name'] ?></option>

						<?	endwhile; ?>

					</select>

				</td>

			</tr>

			<tr>

				<th>Active:</th>

				<td>

					<select name="active">

						<option value="Yes" <?= $row['active'] == 'Yes' ? 'selected="selected"' : ''; ?>>Yes</option>

						<option value="No" <?= $row['active'] == 'No' ? 'selected="selected"' : ''; ?>>No</option>

					</select>

				</td>

			</tr>

		</table>

			</div> <!-- end span6 in sites.php -->

			<div class="span5">

				<?php

					$sign[0] = unserialize($row['sign1']);

					$sign[1] = unserialize($row['sign2']);

					$sign[2] = unserialize($row['sign3']);

					$sign[3] = unserialize($row['sign4']);

					

					$connection = explode(' ', $row['connection']);

					$count = 0; //used for picking right value for checkboxes

					$connCount = 0; //used for picking right value for connection radio

					$signCount = 0; //you get the idea? for selecting correct sign

					for($x = 0; $x<$row['num_signs']; $x++)

					{

				?>

					<fieldset>

						<legend>

							<b>Sign <?= $x+1 ?>:</b>

							

						</legend>

						<label class="checkbox inline">

						  	<input type="checkbox" name="sign<?= $x+1 ?>[0]" value="UNL/Cash"

						  		<?= $sign[$signCount][$count++] == 'UNL/Cash' ? 'checked' : ''; ?>> UNL/Cash

						</label>

						<label class="checkbox inline">

						  	<input type="checkbox" name="sign<?= $x+1 ?>[1]" value="UNL/Credit"

						  		<?= $sign[$signCount][$count++] == 'UNL/Credit' ? 'checked' : ''; ?>> UNL/Credit

						</label>

						<label class="checkbox inline">

						  	<input type="checkbox" name="sign<?= $x+1 ?>[2]" value="Super"

						  		<?= $sign[$signCount][$count++] == 'Super' ? 'checked' : ''; ?>> Super

						</label>

						<label class="checkbox inline">

						  	<input type="checkbox" name="sign<?= $x+1 ?>[3]" value="Plus"

						  		<?= $sign[$signCount][$count++] == 'Plus' ? 'checked' : ''; ?>> Plus

						</label>

						<label class="checkbox inline">

						  	<input type="checkbox" name="sign<?= $x+1 ?>[4]" value="Diesel"

						  		<?= $sign[$signCount][$count++] == 'Diesel' ? 'checked' : ''; ?>> Diesel

						</label>

						<label class="radio inline">

						  	<input type="radio" name="connection<?= $x+1 ?>" value="Wireless"

						  		<?= $connection[$connCount] == 'Wireless' ? 'checked' : ''; ?>>

						  	Wireless

						</label>

						<label class="radio inline">

						 	<input type="radio" name="connection<?= $x+1 ?>" value="Wired"

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

					} //end for loop		

					echo '		

							<div class="btn-group">

								<input name="save" type="submit" class="btn btn-success" value="Save Changes">

								

								<a class="btn btn-danger" name="delete" id ="delete_site" value="Delete" onclick="confirmDelete(\'' . $row['id'] . '\')">Delete</a>

							</div>';

					echo'</form>';

	} //END DISPLAY EDIT TABLE ELSE LOOP

} /*end session level is valid*/

?>