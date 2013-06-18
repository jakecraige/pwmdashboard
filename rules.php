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
if($_SESSION['level'] >= SUPERVISOR_LEVEL) {
	include('includes/rules/tabs.php');
		/*****************************
		*  Start Problem Descriptions
		******************************/
	if($id != '' && $type == 'problem') {
		if(isset($_POST['edit_problem'])) {
			$priority = mysqli_real_escape_string($db, $_POST['priority']);
			$error_to_problem = mysqli_real_escape_string($db, $_POST['error_to_problem']);
			$system_resolved = mysqli_real_escape_string($db, $_POST['system_resolved']);
			$trim = mysqli_real_escape_string($db, $_POST['trim']);

			$sql = "UPDATE ruleset SET priority='$priority',
					error_to_problem='$error_to_problem', system_resolved='$system_resolved',trim='$trim' WHERE id='$id'"; 
			if($result = mysqli_query($db, $sql)) {
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated ruleset.</div>';
				$pageUpdated = true;
			}
			else {
				echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error updating ruleset.</div>';
				do_log('rules', 'MySql Error Updating ruleset', mysqli_real_escape_string($db, $sql), 'Failure', $db);
			}
		}
		/**********************************
			If trying to edit a rule
		*********************************/
		if($mode == 'edit' && $pageUpdated == false) {
			$sql = "SELECT * FROM ruleset WHERE id='$id' LIMIT 1";
			$result = mysqli_query($db, $sql);
			if(mysqli_num_rows($result) != 0) {
				while($row = mysqli_fetch_array($result)) {
					$priority = getPriority($row['event'], $db);
					$error_to_problem = $row['error_to_problem'];
					$system_resolved = $row['system_resolved'];
					$trim = $row['trim'];
					echo '<form method="post" class="form-inline" action='.$_SERVER['REQUEST_URI'].'><table class="collection noBorder">
								<tr>
									<th>Problem</th>
									<th>Description</th>
									<th>Priority</th>
									<th>Error to Problem</th>
									<th>Trim</th>
									<th>System Closure Period</th>
									<th></th>
								</tr>
								<tr>
									<td>'.$row['event'].'</td>
									<td>'.longEvent($row['event']).'</td>';
								?>
									<td>
										<select name="priority" class="input-medium">
										  <option value="1"	<?php if($priority == 1 ) echo 'selected"selected"'; ?>>
												Priority 1</option>
										  <option value="2"  <?php if($priority == 2 ) echo 'selected="selected"'; ?>>
											Priority 2</option>
										  <option value="3" <?php if($priority == 3 ) echo 'selected="selected"'; ?>>
											Priority 3</option>
										</select>
									</td>
									<?php
										if($row['event'] == 'IVP' || $row['event'] == 'CNS') {
											echo '<td>N/A</td><td>N/A</td><td>N/A</td>';
										}
										else { //Run all the code displaying options to edit error to problem, trim, system closure
									?>
									<td>
										<select name="error_to_problem"  class="input-small">
											<option value="15" <?php if($error_to_problem == 15 ) echo 'selected="selected"'; ?>>
												15 Minutes</option>
											<option value="30" <?php if($error_to_problem == 30 ) echo 'selected="selected"'; ?>>
												30 Minutes</option>
											<option value="60" <?php if($error_to_problem == 60 ) echo 'selected="selected"'; ?>>
												1 Hour</option>
											<option value="120" <?php if($error_to_problem == 120 ) echo 'selected="selected"'; ?>>
												2 Hours</option>
											<option value="180" <?php if($error_to_problem == 180 ) echo 'selected="selected"'; ?>>
												3 Hours</option>
											<option value="240" <?php if($error_to_problem == 240 ) echo 'selected="selected"'; ?>>
												4 Hours</option>
											<option value="300" <?php if($error_to_problem == 300 ) echo 'selected="selected"'; ?>>
												5 Hours</option>
											<option value="0" <?php if($error_to_problem == 0 ) echo 'selected="selected"'; ?>>
												Never</option>
										</select>
									</td>
									<td>
										<select name="trim"  class="input-small">
											<option value="-1" <?php if($trim == '-1' ) echo 'selected="selected"'; ?>>
												-1</option>
											<option value="-2" <?php if($trim == '-2' ) echo 'selected="selected"'; ?>>
												-2</option>
											<option value="-2" <?php if($trim == '-3' ) echo 'selected="selected"'; ?>>
												-3</option>
											<option value="0" <?php if($trim == '0' ) echo 'selected="selected"'; ?>>
												0</option>
											<option value="1" <?php if($trim == '1' ) echo 'selected="selected"'; ?>>
												1</option>
											<option value="2" <?php if($trim == '2' ) echo 'selected="selected"'; ?>>
												2</option>
											<option value="2" <?php if($trim == '3' ) echo 'selected="selected"'; ?>>
												3</option>
										</select>
									</td>
									<td>
										<select name="system_resolved" class="input-small">
											<option value="15" <?php if($system_resolved == 15 ) echo 'selected="selected"'; ?>>
												15 Minutes</option>
											<option value="30" <?php if($system_resolved == 30 ) echo 'selected="selected"'; ?>>
												30 Minutes</option>
											<option value="60" <?php if($system_resolved == 60 ) echo 'selected="selected"'; ?>>
												1 Hour</option>
											<option value="120" <?php if($system_resolved == 120 ) echo 'selected="selected"'; ?>>
												2 Hours</option>
											<option value="180" <?php if($system_resolved == 180 ) echo 'selected="selected"'; ?>>
												3 Hours</option>
											<option value="240" <?php if($system_resolved == 240 ) echo 'selected="selected"'; ?>>
												4 Hours</option>
											<option value="300" <?php if($system_resolved == 300 ) echo 'selected="selected"'; ?>>
												5 Hours</option>
											<option value="0" <?php if($system_resolved == 0 ) echo 'selected="selected"'; ?>>
												Never</option>
										</select>
									</td>
									<?php } /*Closing of else.*/ ?>
									<td><input type="submit" name="edit_problem" class="btn btn-primary" value="Save Changes"></td>
								</tr>
						</table></form>
					<?php
				}
			}
		}
	} // end if problem id set
		/***************************
		* Display Problem Descriptions
		****************************/
				//echo '<h3 class="centerText">Ruleset</h3>';
				//echo '<h4 class="centerText">Click on rules to edit</h4>
	echo'		<table class="collection noBorder">
				<tr>
					<th>Problem</th>
					<th>Description</th>
					<th>Priority</th>
					<th>Error to Problem</th>
					<th>Trim</th>
					<th>System Closure Period</th>
				</tr>';
	$sql = "SELECT * FROM ruleset WHERE event<>'PCE'";
	$result2 = mysqli_query($db, $sql);
	while($row = mysqli_fetch_array($result2)) {
		$link = $_SERVER['PHP_SELF'].'?type=problem&mode=edit&id='.$row['id'];
		echo '<tr>
				<td><a href="'.$link.'">'.$row['event'].'</a></td>
				<td><a href="'.$link.'">'.longEvent($row['event']).'</a></td>
				<td><a href="'.$link.'">'.getPriority($row['event'], $db).'</a></td>';
				//This if loop makes rue that etp, trim and system closure are only shown
				//When the event uses it.
				if($row['event'] == 'IVP' || $row['event'] == 'CNS' || $row['event'] == 'UAC' ||
					$row['event'] == 'KSS-SPM' || $row['event'] == 'KSS-RPS') {
					echo '
						<td><a href="'.$link.'">N/A</a></td>
						<td><a href="'.$link.'">N/A</a></td>
						<td><a href="'.$link.'">N/A</a></td>';
				}
				else {
					echo '<td><a href="'.$link.'">'.$row['error_to_problem'].'</a></td>
							<td><a href="'.$link.'">'.$row['trim'].'</a></td>
							<td><a href="'.$link.'">'.$row['system_resolved'].'</a></td>';
				}
			echo '</tr>';
	}
	echo '</table><br>';
/*******************************************
*  End Problem Descriptions
*******************************************/
	
		echo '</form></table>';
	echo '</div>'; //end row div
} /*end session level auth*/
	include('includes/footer.php');
	mysqli_close($db);
?>