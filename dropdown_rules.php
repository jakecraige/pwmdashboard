<?php
	include('includes/header.php');
	require('includes/includes.php');
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	$page = 'dropdown_rules';
	$mode = $_GET['mode'];
	$id = $_GET['id'];
	$type = $_GET['type'];
	$pageUpdated = false;
if($_SESSION['level'] >= SUPERVISOR_LEVEL) {
	include('includes/rules/tabs.php');
/*******************************************
*  Start Current Status
*******************************************/
	echo '<div class="row">';
		echo '<div class="span12">';
			if($type == 'status') {
				if(isset($_POST['add_status'])) { //Update current status in db
					$status = mysqli_real_escape_string($db, $_POST['status']);

					$sql = "INSERT INTO current_status VALUES (0, '$status')"; 
					if($result = mysqli_query($db, $sql)) {
						echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Added status.</div>';
						$pageUpdated = true;
					}
					else {
						echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error adding status.</div>';
						do_log('rules', 'MySql Error adding status', mysqli_real_escape_string($db, $sql), 'Failure', $db);
					}
				}
				if($id != '') {
					if(isset($_POST['delete_status'])) { //Update current status in db
						$sql = "DELETE from current_status WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Deleted status.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error deleting status.</div>';
							do_log('rules', 'MySql Error deleting status', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if(isset($_POST['edit_status'])) { //Update current status in db
						$status = mysqli_real_escape_string($db, $_POST['status']);

						$sql = "UPDATE current_status SET status='$status' WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated ruleset.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error updating status. Contact Administrator.</div>';
							do_log('rules', 'MySql Error Updating status', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if($mode == 'edit' && $pageUpdated == false) {
						//Show table of data to edit. 
						$sql = "SELECT id, status FROM current_status WHERE id='$id' LIMIT 1";
						$result = mysqli_query($db, $sql);
						if(mysqli_num_rows($result) != 0) {
							while($row = mysqli_fetch_array($result)) {
								$status = $row['status'];
								echo '<form method="post" class="form-inline" action="'.$_SERVER['REQUEST_URI'].'"><table class="collection statusAmcs noBorder noHover">
										<tr>
											<td><b>Status<b></td>
											<td><input class="input-large" type="text" name="status" value="'.$status.'"></td>
											<td><input type="submit" class="btn btn-success" name="edit_status" value="Edit">
												<input type="submit" class="btn btn-danger" name="delete_status" value="Delete">
											</td>
										</tr>
									</table>
									</form>';
							}
						}
					}
				}
				else if($mode == 'add' && $pageUpdated == false) {
					//Show add form
					echo '<form method="post" class="form-inline" action='.$_SERVER['REQUEST_URI'].'><table class="collection statusAmcs noBorder noHover">
									<tr>
										<td><b>Current Status:<b></td>
										<td><input class="input-medium" type="text" name="status" value="'.$_POST['status'].'"></td>
										<td><input type="submit" class="btn btn-primary" name="add_status" value="Add">
									</tr>
								</table>
								</form>';
					echo '<div id="current_status"></div>';
				}
			} // end if type = status
//////////////////////////////////////////////////////////////////
//				When type = resolution			
//////////////////////////////////////////////////////////////////
			if($type == 'resolution') {
				$type = mysqli_real_escape_string($db, $_POST['type']);
				if(isset($_POST['add_resolution'])) { //Update current status in db
						$sql = "INSERT INTO resolution_type VALUES (0, '$type')"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Added resolution type.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error adding resolution type.</div>';
							do_log('rules', 'MySql Error adding resolution type', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
				}
				if($id != '') {
					if(isset($_POST['delete_resolution'])) { //Update current status in db
						$sql = "DELETE from resolution_type WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Deleted resolution type.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error deleting resolution type.</div>';
							do_log('rules', 'MySql Error deleting resolution type', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if(isset($_POST['edit_resolution'])) {
						//Update current status db code
						$sql = "UPDATE resolution_type SET type='$type' WHERE id='$id'"; 
						if($result = mysqli_query($db, $sql)) {
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Resolution Type.</div>';
							$pageUpdated = true;
						}
						else {
							echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error updating resolution type.</div>';
							do_log('rules', 'MySql Error Updating resolution type', mysqli_real_escape_string($db, $sql), 'Failure', $db);
						}
					}
					if($mode == 'edit' && $pageUpdated == false) {
						//Show table of data to edit. 
						$sql = "SELECT id, type FROM resolution_type WHERE id='$id' LIMIT 1";
						$result = mysqli_query($db, $sql);
						if(mysqli_num_rows($result) != 0) {
							while($row = mysqli_fetch_array($result)) {
								$type = $row['type'];
								echo '<form method="post" class="form-inline" action='.$_SERVER['REQUEST_URI'].'><table class="collection statusAmcs noBorder noHover">
										<tr>
											<td><b>Resolution Type<b></td>
											<td><input class="input-medium" type="text" name="type" value="'.$type.'"></td>
											<td><input type="submit" class="btn btn-success" name="edit_resolution" value="Edit">
												<input type="submit" class="btn btn-danger" name="delete_resolution" value="Delete"></td>
										</tr>
									</table>
									</form>';
							}
						}
					}
				}
				else if($mode == 'add' && $pageUpdated == false) {
					echo '<form method="post" class="form-inline" action='.$_SERVER['REQUEST_URI'].'><table class="collection statusAmcs noBorder noHover">
							<tr>
								<td><b>Resolution Type<b></td>
								<td><input class="" type="text" name="type" value="'.$_POST['type'].'"></td>
								<td><input type="submit" class="btn btn-primary" name="add_resolution" value="Add">
							</tr>
						</table>
						</form>';
					echo '<div id="resolution_types"></div>';
				}
			}
		echo '</div>'; // span12 end div
/************************************************
End Display of input fields for edits
 Display Current Status's
************************************************/
		echo '<div class="span6">';
			echo '<table class="collection noBorder">
						<tr>
							<th>Current Status Options</th>
						</tr>';
			$sql = "SELECT id,status FROM current_status";
			$result = mysqli_query($db, $sql);
			echo '<tr><td><a href="'.$_SERVER['PHP_SELF'].'?type=status&mode=add"><em>Add New Status</em></a></td></tr>';
			while($row = mysqli_fetch_array($result)) {
				$link = $_SERVER['PHP_SELF'].'?type=status&mode=edit&id='.$row['id'];
				echo '<tr>
						<td><a href="'.$link.'">'.$row['status'].'</a></td>
					</tr>';
			}
			echo '</table><br>';
		echo '</div>'; //end span6 div
/***********************************************
*  End Current Status
*  Start Resolution Type
************************************************/
		echo '<div class="span6">';
			echo '<table class="collection noBorder">
						<tr>
							<th>Resolution Type Options</th>
						</tr>';
			$sql = "SELECT id,type FROM resolution_type";
			$result = mysqli_query($db, $sql);
			echo '<tr><td><a href="'.$_SERVER['PHP_SELF'].'?type=resolution&mode=add"><em>Add New Resolution Type</em></a></td></tr>';
			while($row = mysqli_fetch_array($result)) {
				$link = $_SERVER['PHP_SELF'].'?type=resolution&mode=edit&id='.$row['id'];
				echo '<tr>
						<td><a href="'.$link.'">'.$row['type'].'</a></td>
					</tr>';
			}
			echo '</table><br>';
		echo '</div>'; // end span 6 div		 		
/*********************************************
*  End Resolution Type Display */

		echo '</form></table>';
	echo '</div>'; //end row div
} /*end session level auth*/
	include('includes/footer.php');
	mysqli_close($db);
?>