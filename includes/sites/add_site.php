<?php

if($_SESSION['level'] >= SUPERVISOR_LEVEL)

{

	if(isset($_POST['add']))

	{

		$number = mysqli_real_escape_string($db, $_POST['number']);

		$name = mysqli_real_escape_string($db, $_POST['name']);

		$phone = mysqli_real_escape_string($db, $_POST['phone']);

		$address = mysqli_real_escape_string($db, $_POST['address']);

		$num_signs = mysqli_real_escape_string($db, $_POST['num_signs']);

		$trans_rate = mysqli_real_escape_string($db, $_POST['trans_rate']);

		$connection = mysqli_real_escape_string($db, $_POST['connection1']);

		$sign1 = mysqli_real_escape_string($db, serialize($_POST['sign1']));

		$cu_version = mysqli_real_escape_string($db, $_POST['cu_version']);

		$amcs_version = mysqli_real_escape_string($db, $_POST['amcs_version']);

		$active = mysqli_real_escape_string($db, $_POST['active']);

		$pos = mysqli_real_escape_string($db, $_POST['pos']);

		$warranty_expires = mysqli_real_escape_string($db, $_POST['warranty_expires']);



		$pc_manufacture = mysqli_real_escape_string($db, $_POST['pc_manufacture']);

		$error = '';

		

		if(!preg_match($checkSite, $number)) {

			$error .= 'Error: Site Format - Accepts Numbers Only<br>'; }

		if(!preg_match($checkPhone, $phone)) {

			$error .= 'Error: Phone Format - Accepts 123-456-7890<br>'; }

		if(!preg_match($checkSigns, $num_signs)) {

			$error .= 'Error: Number of Signs Format - Accepts Numbers Only<br>'; }

		if(!preg_match($checkName, $name)) {

			$error .= 'Error: Name Format - Accepts Alphanumberic Entries<br>'; }

		if(empty($_POST['address'])) {

			$error .= 'Error: Please Enter an Address<br>'; }

		if(!preg_match($date_regex, $warranty_expires)) {

			$error .= 'Error: Warranty Date Format - Accepts 01-01-2013<br>'; }

		if(!preg_match($checkName, $pc_manufacture)) {

			$error .= 'Error: P.C. Manufacturer - Accepts Alphanumberic Entries<br>'; }

		

		if(empty($error)) {

			$check = "SELECT number FROM sites WHERE number='$number' LIMIT 1";

			$check_result = mysqli_query($db, $check); //check if site exists

			

			//format phone number to default formatting

			$phone = preg_replace('/[\(\)]/', '', $phone);

			$phone = preg_replace('/[\s]/', '-', $phone);

			

			if(mysqli_num_rows($check_result) == 0) {

				$warranty_expires = strtotime(str_replace('-', '/', $warranty_expires)); //replace for american date

				$sql = "INSERT INTO sites VALUES (0, '$number', '$name', '$phone', '$address', 

						'$num_signs', '$trans_rate', '$connection', '$cu_version', '$amcs_version', '$active', 

						'$pos', '$warranty_expires', '$pc_manufacture', '$sign1', '', '', '')"; 

				if($result = mysqli_query($db, $sql)) {

					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Added Site.</div>';

					$pageUpdated = true;

				}

				else {

					echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Sdding Site. Contact Administrator.</div>';

					do_log('rules', 'MySql Error Adding site', mysqli_real_escape_string($db, $sql), 'Failure', $db);

				}

			}

			else {

				echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error: Site Already Exists. You must use a different store number.</div>';

			}

		}

		else {

			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$error.'</div>';

		}

	}

	?>

	<form action="" method="post" class="form-inline">

	<table class="newsite">

		<tr>

			<th>Number:</th>

			<td><input name="number" type="text" value="<?= $_POST['number'] ?>"></td>

		</tr>

		<tr>

			<th>Name:</th>

			<td><input name="name" type="text" value="<?= $_POST['name'] ?>"></td>

		</tr>

		<tr>

			<th>Address:</th>

			<td><input name="address" type="text" value="<?= $_POST['address'] ?>"></td>

		</tr>

		<tr>

			<th>Phone:</th>

			<td><input name="phone" type="text" value="<?= $_POST['phone'] ?>" placeholder="123-456-7890"></td>

		</tr>

		<tr>

			<th>Warranty Expiration:</th>

			<td><input name="warranty_expires" type="text" value="<?= $_POST['warranty_expires'] ?>" placeholder="01-01-2013"></td>

		</tr>

		<tr>

			<th>Signs(#):</th>

			<td>

				<select name="num_signs">

					<option value="1">1</option>

					<option value="2">2</option>

					<option value="3">3</option>

					<option value="4">4</option>

				</select>

			</td>

		</tr>

		<tr>

			<th>CU v.:</th>

			<td>

				<select name="cu_version">

					<option>9.04</option>

					<option>8.00</option>

				</select>

			</td>

		</tr>

		<tr>

			<th>AMCS v.:</th>

			<td>

				<select name="amcs_version">

					<option>1.00</option>

					<option>0.94</option>

				</select>

			</td>

		</tr>

		<tr>

			<th>POS Type:</th>

			<td>

				<select name="pos">

					<option>Andi</option>

					<option>Sapphire</option>

				</select>

			</td>

		</tr>

		<tr>

			<th>Tramsission Rate:</th>

			<td>

				<select name="trans_rate" class="">

					<option value="5"> 5 Minutes</option>

					<option value="15" > 15 Minutes</option>

					<option value="30" > 30 Minutes</option>

					<option value="60" > 1 Hour</option>

					<option value="120"> 2 Hours</option>

					<option value="180"> 3 Hours</option>

					<option value="240"> 4 Hours</option>

					<option value="300"> 5 Hours</option>

					<option value="0"> Never</option>

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

					<option>Yes</option>

					<option>No</option>

				</select>

			</td>

		</tr>

	</table>

	</div> <!-- end span6 in sites.php -->

	<div class="span5">

		<fieldset>

			<legend>

				<b>Sign 1:</b>

			</legend>

			<label class="checkbox inline">

			  	<input type="checkbox" name="sign1[0]" value="UNL/Cash"> UNL/Cash

			</label>

			<label class="checkbox inline">

			  	<input type="checkbox" name="sign1[1]" value="UNL/Credit"> UNL/Credit

			</label>

			<label class="checkbox inline">

			  	<input type="checkbox" name="sign1[2]" value="Super"> Super

			</label>

			<label class="checkbox inline">

			  	<input type="checkbox" name="sign1[3]" value="Plus"> Plus

			</label>

			<label class="checkbox inline">

			  	<input type="checkbox" name="sign1[4]" value="Diesel"> Diesel

			</label>

			<label class="radio inline">

			  	<input type="radio" name="connection1" value="Wireless">

			  	Wireless

			</label>

			<label class="radio inline">

			 	<input type="radio" name="connection1" value="Wired">

			  	Wired

			</label>

		</fieldset>

		<br>

	<?php

		echo'	<div class="btn-group">

					<input name="add" type="submit" class="btn btn-success" value="Add Site">

				</div>';

		echo'</form>';

} /*end if session level is valid*/

?>