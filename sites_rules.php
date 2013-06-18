<?php
	include('includes/header.php');
	require('includes/includes.php');
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	$page = 'sites_rules';
	$mode = $_GET['mode'];
	$id = $_GET['id'];
	$type = $_GET['type'];
	$pageUpdated = false;

	if($_SESSION['level'] >= SUPERVISOR_LEVEL) {
		include('includes/rules/tabs.php');
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
					echo $sql; 
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
								echo '<form method="post" class="form-inline" action='.$_SERVER['REQUEST_URI'].'><table class="collection statusAmcs noBorder">
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
					echo '<form method="post" class="form-inline" action='.$_SERVER['REQUEST_URI'].'><table class="collection statusAmcs noBorder">
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
		echo '<div class="span6">';
			/***************************
			* Display Current Status's
			****************************/
			echo '<table class="collection noBorder">
						<tr>
							<th>Power Conditioner Manufacturers</th>
						</tr>';
			$sql = "SELECT id,name FROM pc_manufacturers";
			$result = mysqli_query($db, $sql);
			echo '<tr><td><a href="'.$_SERVER['PHP_SELF'].'?type=pc_manufacture&mode=add"><em>Add New Manufacturer</em></a></td></tr>';
			while($row = mysqli_fetch_array($result)) {
				$link = $_SERVER['PHP_SELF'].'?type=pc_manufacture&mode=edit&id='.$row['id'];
				echo '<tr>
						<td><a href="'.$link.'">'.$row['name'].'</a></td>
					</tr>';
			}
			echo '</table>';
		echo '</div>'; //end span6 div

		echo '</form></table>';
	echo '</div>'; //end row div
	} /*end session level auth*/
	include('includes/footer.php');
	mysqli_close($db);
?>