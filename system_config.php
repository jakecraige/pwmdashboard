<?php
	include('includes/header.php');
	require('includes/includes.php');
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	$page = 'rules';
	$mode = $_GET['mode'];
	$id = $_GET['id'];
	$type = $_GET['type'];
	$pageUpdated = false;

	?>
	<ul class="nav nav-tabs">
  <li>
    <a href="rules.php">Problem</a>
  </li>
  <li><a href="dropdown_rules.php">Dropdowns</a></li>
  <li class="active"><a href="sites_rules.php">Sites</a></li>
  <li><a href="system_rules.php">System</a></li>
</ul>
	<?php
/*******************************************
*  
*******************************************/
	echo '<div class="row">';
 		/*  Display bottom FLASH messages and inputs
**********************************************/
		echo '<div class="span12">';
			if($type == 'system') {
				$refresh_time = mysqli_real_escape_string($db, $_POST['refresh']);
				$max_price_change = mysqli_real_escape_string($db, $_POST['max_price_change']);
				$trans_rate = mysqli_real_escape_string($db, $_POST['trans_rate']);
				//$email_list = mysqli_real_escape_string($db, $_POST['email_list']);
				$email_list = $_POST['email_list'];
				$forward_cns = mysqli_real_escape_string($db, $_POST['forward_cns_time']);
				$backward_cns = mysqli_real_escape_string($db, $_POST['backward_cns_time']);
				
				if($id != '') {
					if(isset($_POST['edit_refresh'])) {
						//Update current status db code
						$sql = "UPDATE system_config SET refresh_time='$refresh_time' WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Configuration.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Configuration. Contact Administrator.</div>';
							do_log('rules', 'MySql Error Updating Configuration', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if(isset($_POST['edit_max_price_change'])) {
						//Update current status db code
						$sql = "UPDATE system_config SET max_price_change='$max_price_change' WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Max Price Change.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Max Price Change. Contact Administrator.</div>';
							do_log('rules', 'MySql Error Updating Configuration', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if(isset($_POST['edit_trans_rate'])) {
						//Update current status db code
						$sql = "UPDATE system_config SET trans_rate='$trans_rate' WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Transmission Rate.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Transmission Rate. Contact Administrator.</div>';
							do_log('rules', 'MySql Error Updating Configuration', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if(isset($_POST['edit_back_cns_time'])) {
						//Update current status db code
						$sql = "UPDATE system_config SET backward_cns_time='$backward_cns' WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Backward CNS Time.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Backward CNS Time. Contact Administrator.</div>';
							do_log('rules', 'MySql Error Updating CNS Times', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if(isset($_POST['edit_forward_cns_time'])) {
						//Update current status db code
						$sql = "UPDATE system_config SET forward_cns_time='$forward_cns' WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Forward CNS Times.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Foward CNS Time. Contact Administrator.</div>';
							do_log('rules', 'MySql Error Updating CNS Times', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if(isset($_POST['edit_emails'])) {
						//Allows email to be entered with commas, spaces, or new lines
						$new_email_list = preg_replace('/[,\s]/', "\n", $email_list);
						$new_email_list = preg_split("/(\r\n|\n|\r|\s|,)/", $email_list);
						$errors = '';
						$valid_email_list = array();
						foreach ( $new_email_list as $email ) {
							$email = trim($email);
							if(filter_var($email, FILTER_VALIDATE_EMAIL)){
								$valid_email_list[] = $email;
							}
							else {
								if(!empty($email)) {
									$errors .= 'Error: Invalid Email Entered ('.$email.')<br>';
								}
							}
						}
						if(!empty($errors)) {
							echo "<p class=\"error\">$errors</p>";
						}
						$valid_email_list = implode(' ', $valid_email_list);
						if(!empty($valid_email_list)) {
							$sql = "UPDATE system_config SET email_list='$valid_email_list' WHERE id='$id'"; 
							if($result = mysqli_query($db, $sql)) {
								echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated alert email list with valid emails.</div>';
								$pageUpdated = true;
							}
							else {
								echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Alert Email List. Contact Administrator.</div>';
								do_log('rules', 'MySql Error Updating Configuration', mysqli_real_escape_string($db, $sql), 'Failure', $db);
							}
						}
					} // end isset post emails
				} //end type == system and id set
			} //end type == system
		/////////////////////////////////////////////////////////////////////
		// 			Start PC Manufacture Loop
		/////////////////////////////////////////////////////////////////////
			if($type == 'pc_manufacture') {
				$pc_manufacture = mysqli_real_escape_string($db, $_POST['pc_manufacture']);
				if(isset($_POST['add_pc_manufacture'])) { //Update current status in db
					$sql = "INSERT INTO pc_manufacturers VALUES (0, '$pc_manufacture')"; 
					if($result = mysqli_query($db, $sql)) {
						echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Added P.C. Manufacturer.</div>';
						$pageUpdated = true;
					}
					else {
						echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error adding P.C. Manufacturer.</div>';
						do_log('rules', 'MySql Error adding resolution type', mysqli_real_escape_string($db, $sql), 'Failure', $db);
					}
				}
				if($id != '') {
					if(isset($_POST['delete_pc_manufacture'])) { //Update current status in db
						$sql = "DELETE from pc_manufacturers WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Deleted P.C. Manufacturer.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error deleting P.C. Manufacturer.</div>';
							do_log('rules', 'MySql Error deleting resolution type', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if(isset($_POST['edit_pc_manufacture'])) {
						//Update current status db code
						$sql = "UPDATE pc_manufacturers SET name='$pc_manufacture' WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated P.C. Manufacturer.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error updating P.C. Manufacturer.</div>';
							do_log('rules', 'MySql Error Updating resolution type', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if($mode == 'edit' && $pageUpdated == false) {
						//Show table of data to edit. 
						$sql = "SELECT id, name FROM pc_manufacturers WHERE id='$id' LIMIT 1";
						$result = mysqli_query($db, $sql);
						if(mysqli_num_rows($result) != 0) {
							while($row = mysqli_fetch_array($result)) {
								$pc_manufacture = $row['name'];
								echo '<form method="post" action='.$_SERVER['REQUEST_URI'].'><table class="collection statusAmcs noBorder">
										<tr>
											<td><b>Power Conditioner Manufacturer:<b></td>
											<td><input class="wideTextBox" type="text" name="pc_manufacture" value="'.$pc_manufacture.'"></td>
											<td><input type="submit" class="btn btn-success" name="edit_pc_manufacture" value="Edit">
											<td><input type="submit" class="btn btn-danger" name="delete_pc_manufacture" value="Delete">
										</tr>
									</table>
									</form>';
							}
						}
					}
				} //End if ID is set loop
				else if($mode == 'add' && $pageUpdated == false) {
					echo '<form method="post" action='.$_SERVER['REQUEST_URI'].'><table class="collection statusAmcs noBorder">
							<tr>
								<td><b>Power Conditioner Manufacturer:<b></td>
								<td><input class="wideTextBox" type="text" name="pc_manufacture" value="'.$_POST['pc_manufacture'].'"></td>
								<td><input type="submit" class="btn btn-primary" name="add_pc_manufacture" value="Add">
							</tr>
						</table>
						</form>';
					echo '<div id="resolution_types"></div>';
				} //end else if 
			} // end type= manufacture
		echo '</div>'; //end span12 flash div
/***********************
*  End FLASH messages and inputs display
*  Display P.C. Manufacturers
********************************/

		/******************************
		*  Display System Configuration
		************************/
		echo '<div class="span6">';
		echo '<table class="collection noBorder">
					<tr>
						<th>System Configuration Options</th>
					</tr>';
		$sql = "SELECT * FROM system_config";
		$result = mysqli_query($db, $sql);
		while($row = mysqli_fetch_array($result)) {
			$link = $_SERVER['PHP_SELF'].'?type=system&mode=edit&id='.$row['id'];
			$refresh = $row['refresh_time'];
			$trans_rate = $row['trans_rate'];
			$max_price_change = $row['max_price_change'];
			$email_list = $row['email_list'];
			$forward_cns_time = $row['forward_cns_time'];
			$backward_cns_time = $row['backward_cns_time'];

			echo '<tr>
				<div id="system_config"></div>
				<form method="post" class="form-inline" action="'.$link.'#system_config">'; 
			if($_GET['option'] == 'refresh' && $mode == 'edit') {
				?>
				<tr>
					<td>
						Problem Refresh Time: 
						<select name="refresh" class="input-medium">
							<option value="5" <?php if($refresh == 5 ) echo 'selected="selected"'; ?>>
								5 Minutes</option>
							<option value="15" <?php if($refresh == 15 ) echo 'selected="selected"'; ?>>
								15 Minutes</option>
							<option value="30" <?php if($refresh == 30 ) echo 'selected="selected"'; ?>>
								30 Minutes</option>
							<option value="60" <?php if($refresh == 60 ) echo 'selected="selected"'; ?>>
								1 Hour</option>
							<option value="120" <?php if($refresh == 120 ) echo 'selected="selected"'; ?>>
								2 Hours</option>
							<option value="180" <?php if($refresh == 180 ) echo 'selected="selected"'; ?>>
								3 Hours</option>
							<option value="240" <?php if($refresh == 240 ) echo 'selected="selected"'; ?>>
								4 Hours</option>
							<option value="300" <?php if($refresh == 300 ) echo 'selected="selected"'; ?>>
								5 Hours</option>
							<option value="0" <?php if($refresh == 0 ) echo 'selected="selected"'; ?>>
								Never</option>
						</select>
						<input type="submit" class="btn btn-primary" name="edit_refresh" value="Save">
					</td>
					<?php }
					else { //Display mode, not editing.
						$link .= '&option=refresh';
						echo "<td><a href=\"$link#system_config\"><i>Problem Refresh Time:</i> $refresh </a>";
					}
					echo '</tr>
				<tr>';
				if($_GET['option'] == 'trans_rate' && $mode == 'edit') { ?>
					<td>
						Transmission Rate: 
						<select name="trans_rate" class="input-medium">
							<option value="5" <?php if($trans_rate == 5 ) echo 'selected="selected"'; ?>>
								5 Minutes</option>
							<option value="15" <?php if($trans_rate == 15 ) echo 'selected="selected"'; ?>>
								15 Minutes</option>
							<option value="30" <?php if($trans_rate == 30 ) echo 'selected="selected"'; ?>>
								30 Minutes</option>
							<option value="60" <?php if($trans_rate == 60 ) echo 'selected="selected"'; ?>>
								1 Hour</option>
							<option value="120" <?php if($trans_rate == 120 ) echo 'selected="selected"'; ?>>
								2 Hours</option>
							<option value="180" <?php if($trans_rate == 180 ) echo 'selected="selected"'; ?>>
								3 Hours</option>
							<option value="240" <?php if($trans_rate == 240 ) echo 'selected="selected"'; ?>>
								4 Hours</option>
							<option value="300" <?php if($trans_rate == 300 ) echo 'selected="selected"'; ?>>
								5 Hours</option>
						</select>
						<input type="submit" name="edit_trans_rate" class="btn btn-primary" value="Save">
					</td>
				<?php }
				else { //Display mode, not editing.
					$link .= '&option=trans_rate';
					echo "<td><a href=\"$link#system_config\"><i>Transmission Rate:</i> $trans_rate </a>";
				}
				echo '</tr><tr>';
				if($_GET['option'] == 'max_price_change' && $mode == 'edit') { ?>
					<td>
						Max Price Change: 
						<select name="max_price_change" class="input-small">
							<option value="0.1" <?php if($max_price_change == 0.1 ) echo 'selected="selected"'; ?>>
								$0.10</option>
							<option value="0.15" <?php if($max_price_change == 0.15 ) echo 'selected="selected"'; ?>>
								$0.15</option>
							<option value="0.2" <?php if($max_price_change == 0.2 ) echo 'selected="selected"'; ?>>
								$0.20</option>
							<option value="0.25" <?php if($max_price_change == 0.25 ) echo 'selected="selected"'; ?>>
								$0.25</option>
							<option value="0.3" <?php if($max_price_change == 0.3 ) echo 'selected="selected"'; ?>>
								$0.30</option>
							<option value="0.4" <?php if($max_price_change == 0.4 ) echo 'selected="selected"'; ?>>
								$0.40</option>
							<option value="0.5" <?php if($max_price_change == 0.5 ) echo 'selected="selected"'; ?>>
								$0.50</option>
						</select>
						<input type="submit" name="edit_max_price_change" class="btn btn-primary" value="Save">
					</td>
				<? }
				else { //Display mode, not editing.
					switch($max_price_change) {
						case 0.1:
							$textRate = '$0.10';
							break;
						case 0.15:
							$textRate = '$0.15';
							break;
						case 0.20:
							$textRate = '$0.20';
							break;
						case 0.25:
							$textRate = '$0.25';
							break;
						case 0.30:
							$textRate = '$0.30';
							break;
						case 0.40:
							$textRate = '$0.40';
							break;
						case 0.50: 
							$textRate = '$0.50';
							break;
					}
					$link .= '&option=max_price_change';
					echo "<td><a href=\"$link#system_config\"><i>Max Price Change:</i> $textRate </a>";
				}
			echo '</tr>';
			if($_GET['option'] == 'forward_cns_time' && $mode == 'edit') {
				echo'	
					<tr>
						<td>
							<i>Forward CNS Time:</i> <input type="text" class="input-small" name="forward_cns_time" size="3" maxlength="3" value="'.$forward_cns_time.'">
							<input type="submit" class="btn btn-primary" name="edit_forward_cns_time" value="Save">						
						</td>
					</tr>';
			}
			else { //Display mode for Forward CNS time
				$link .= '&option=forward_cns_time#system_config';
				echo '
						<tr>
							<td>
								<a href="'.$link.'"><i>Forward CNS Time:</i> '.$forward_cns_time.'</a>
							</td>
						</tr>';
			}
			if($_GET['option'] == 'back_cns_time' && $mode == 'edit') {
				echo'	
					<tr>
						<td>
							<i>Backward CNS Time:</i> <input type="text" class="input-small" name="backward_cns_time" size="3" maxlength="3" value="'.$backward_cns_time.'">
							<input type="submit" class="btn btn-primary" name="edit_back_cns_time" value="Save">						
						</td>
					</tr>';
			}
			else { //Display mode for Back CNS Time
				$link .= '&option=back_cns_time#system_config';
				echo'	
					<tr>
						<td>
						<a href="'.$link.'"><i>Backward CNS Time:</i> '.$backward_cns_time.'</a>
						</td>
					</tr>';
			}
			if($_GET['option'] == 'emails' && $mode == 'edit') { ?>
				<tr>
					<td>Alert Email List: <input type="submit" name="edit_emails" class="btn btn-primary" value="Save"></td>
				</tr>
				<tr><td>
					<textarea name="email_list" rows="5"><?php 
							$email_list = explode(' ', $email_list);
							foreach ($email_list as $email) {
								if(!empty($email)) {
									echo $email."\n";
								}
							}?></textarea>
				</td>
		<?php }
			else { //Display mode, not editing.
					$link .= '&option=emails#system_config';
					echo "<td><a href=\"$link\"><i>Alert Email List</i></a></td>";
			}
			echo '</tr>';
		} //end system_config while loop
/*********************************************
*/

		echo '</form></table>';
	echo '</div>'; //end row div
	include('includes/footer.php');
	mysqli_close($db);
?>