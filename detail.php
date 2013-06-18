<?php
		include('includes/header.php');
		require('includes/includes.php');
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
			echo "Error connecting to database.";
		}
		if($_SESSION['level'] >= SUPERVISOR_LEVEL) {
			if($_GET['mode'] == 'close' && isset($_GET['id'])) {
				closeProblem ($_GET['id'], 'Manual Close', '', $db);
			}	
		}
?>	
				<table class="detail_top">
				<?php
					$sql = "SELECT * from problems WHERE id='$id' LIMIT 1";
					if(!$before_update = mysqli_query($db, $sql)) {
						do_log('detail', 'Error Querying Database', mysqli_real_escape_string($sql), 'Failure', $db);
					}
					//Todo: this is a dumb way to do this. probably a memory hog. 
					//need to come up with a better way to update before querying for info to show
					while($row = mysqli_fetch_array($before_update)) {	
						if(isset($_POST['change_status_button'])) {
							if($_POST['change_status'] != 'unset') {
								updateStatus($row['id'], $_POST['change_status'],$row['status'], 
									$row['status_timestamp'], $row['notes'], $row['previous_status'],
									'', $db);
							}
						}
						if(isset($_POST['resolve'])) {
							if($_POST['resolve_option'] != 'open') {
								if(isset($_POST['radio'])) {
									updateStatus($row['id'], $_POST['resolve_option'], $row['status'], 
										$row['status_timestamp'], $row['notes'], $row['previous_status'], 
											$_POST['radio'], $db);
									updateResolutionType($row['id'], $_POST['resolve_option'], $db);
								}
								else {
									echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error: You must select what solution you used on the right side to resolve an issue.</div>';
								}
							}
						}
						if(isset($_POST['save_notes'])) {
							if(!empty($_POST['note_text'])) {
								$text = $_POST['note_text'];
							}
							else {
								$text = 'None';
							}
							$text = mysqli_real_escape_string($db, $text);
							$note_sql = "UPDATE problems SET notes='$text' WHERE id='$id'";
							if(!$note_update = mysqli_query($db, $note_sql)) {
								do_log('detail', 'Error Querying Database', $text, 'Failure', $db);
							}
						}
					}
					if(!$result = mysqli_query($db, $sql)) {
						do_log('detail', 'Error Querying Database', mysqli_real_escape_string($sql), 'Failure', $db);
					}
					mysqli_data_seek($result, 0);
					while($row = mysqli_fetch_array($result)) {
						$longEvent = longEvent($row['event']);
						$event = $row['event'];
						$priority = getPriority($row['event'], $db);
						$start = formatTime($row['timestamp']);
						$id = $row['id'];
						$currStatus = $row['status'];
						$status_timestamp = $row['status_timestamp'];
						$previous = $row['previous_status'];
						$resolution = $row['resolution'];
						$expiration = $row['resolution_timestamp'];
						$notes = $row['notes'];
						$radio = $row['fixed_action'];
						if($event == 'IVP') {
							$body = $row['body']; }
						
						$repeat_issues = checkRepeatIssues($row['store_number'], $row['event'], $db);
						$site_issues = checkSiteIssues($row['store_number'], $db);
						
						//Set to check how many past status to show. Possibly find a better way to do this.	
						$statusPrev1 = getPrevious('status', $previous, 1);
						$statusPrev2 = getPrevious('status', $previous, 2);
						$statusPrev3 = getPrevious('status', $previous, 3);
					
						//Displays blank expiration if issue is not closed because default int value is 0.
						if($expiration == 0) {
							$expiration = '';
						}
						else {
							$expiration = formatTime($expiration);
						}
						
						//Used to query for solution in ruleset table
						$eventShort = $row['event'];
					
						//Query to find Site information
						$store_number = $row['store_number'];
						$sql3 = "SELECT * FROM sites WHERE number='$store_number' LIMIT 1";
						if(!$result3 = mysqli_query($db, $sql3)) {
							do_log('detail', 'Error Querying Database', mysqli_real_escape_string($sql), 'Failure', $db);
						}
						while ($row3 = mysqli_fetch_array($result3)) {
							$phone = $row3['phone'];
							$name = $row3['name'];
							$address = $row3['address'];
						}
						//Checks if the site exists and if it doesn't it changes the name variable to say
						//that the site is not in the db
						if(!siteExists($store_number, $db)) {
							$name = '<span class="label label-important">Not in Database or Inactive</span>';
						}
						echo '
							<tr>
								<td><b>Store #:</b></td>
								<td><a href="sites.php?store_number='.$store_number.'">'.$store_number.'</a></td>
								<td><b>Phone:</b></td>
								<td>'.$phone.'</td>
							</tr>
							<tr>
								<td><b>Store Name:</b></td>
								<td>'.$name.'</td>
								<td><b>Address:</b></td>
								<td>'.nl2br($address).'</td>
							</tr>
							<tr></tr>
							<tr>
								<td><b>Started:</b></td>
								<td>'.$start.'</td>
								<td><b>Ended:</b></td>
								<td>'.$expiration.'</td>
							</tr>
							<tr>
								<td><b>Hours Outstanding:</b></td>
								<td>'.calcTotalHoursOutstanding($row).'</td>
							</tr>
							<tr>
								<td><b>Problem Code:</b></td>
								<td>'.$event.'</td>
								<td><b>Description:</b></td>
								<td>'.$longEvent.'</td>
							</tr>
							<tr>
								<td><b>Priority:</b></td>
								<td>'.$priority.'</td>
								<td><b>Tracking #:</b></td>
								<td>'.$id.'</td>
							</tr>';
							//If IVP it will display incorrect price change. 
							if($event == 'IVP') {
								$info = array();
								$info[] = formatPriceChange($body);
								$ids = checkLinkedEvents($id, $db);
								//If loop fills array with messages from events related. 
								if(!empty($ids)) {
									foreach($ids as $i) {
										$msg_sql = "SELECT message FROM events WHERE id='$i'";
										$msg_result = mysqli_query($db, $msg_sql);
										while($msg_row = mysqli_fetch_array($msg_result)) {
											$info[] = formatPriceChange($msg_row['message']);
										}
									}
								}
								$info = implode('<br>', $info);
								echo '
									<tr>
										<td><b>Details:</b></td>
										<td colspan="3">'.$info.'</td>
									</tr>
									';
							}
							else if(preg_match('/KSS/', $event))
							{
								$msg_sql = "SELECT body FROM problems WHERE id='".$row['id']."'";
								$msg_result = mysqli_query($db, $msg_sql);
								while($msg_row = mysqli_fetch_array($msg_result)) {
									$msg = $msg_row['body'];
								}
								echo '
									<tr>
										<td><b>Details:</b></td>
										<td colspan="3">'.nl2br($msg).'</td>
									</tr>
									';
							}
						echo	'</table>
							<table class="detail_status">';
								if($row['resolution'] == '') {	
							echo	'<tr>
										<td colspan="2">
											<form action="'.$_SERVER['REQUEST_URI'].'" class="form-inline" method=post>
												<select name="change_status" class="input-medium">
													<option value="unset">Change Status</option>';
													$status_list = "SELECT status FROM current_status";
													$result_status = mysqli_query($db, $status_list);
													while($stat = mysqli_fetch_array($result_status)) {
														$status = $stat['status'];
														echo "<option value=\"$status\">$status</option>";
													}
													echo' </select>
														<input type="submit" name="change_status_button" class="btn btn-primary" value="Save">										
											</form>
										</td>';
										
										if(!isset($_POST['edit_notes'])) {
											echo'	<td><form method="post" class="form-inline" action="'.$_SERVER['REQUEST_URI'].'">
														<input type="submit" class="btn btn-primary" name="edit_notes" value="Edit Recent Note">
														</form>
													</td>';
										}
										else {
											echo'	<td><form method="post" class="form-inline" action="'.$_SERVER['REQUEST_URI'].'">
														<input type="submit" class="btn btn-success" name="save_notes" value="Save Note">										
													</td>';
										}
									echo '</tr>';
								}
								echo '<tr>
										<th>Status History</th>
										<th>Outstanding Hours</th>
										<th>User Notes</th>
									</tr>';
							//Determines how many past status's to show. I can probably
							//figure out a way to make this more efficient
							if($statusPrev1 != '' && $statusPrev2 != '' && $statusPrev3 != '') {
								$dispCount = 3;
							}
							else if($statusPrev1 != '' && $statusPrev2 != '') {
								$dispCount = 2;
							}
							else if($statusPrev1 != '') {
								$dispCount = 1;
							}
							else { //No status set
								$dispCount = 0;
							}
							if($resolution == 'Closed' || $resolution == 'Manual Close') {
								$hours = '';
							}
							else {
								$hours = calcHoursOutstanding($row);
							}
							echo	'<tr class="alert alert-info">';
										//todo: change color of alert according to status
										echo '<td class="status_1"><b>'.$currStatus.'<b></td>
										<td>'.$hours.'</td>
										<td>';
										if(!isset($_POST['edit_notes'])) {
											echo $notes;											
										}
										else {
											if(isset($_POST['note_text'])) { //Probably Unneccesary? 
												echo '<textarea rows="3" cols="50" name="note_text">'.$_POST['note_text'].'</textarea>';
											}
											else {
												echo '<textarea rows="3" cols="50" name="note_text">'.$notes.'</textarea>';
											}
										}
											//End of Save note form. Have to end it here to have the note_text be POSTed
										echo '</form>';		
										echo' </td>
									</tr>'; ///End current info row
									for($x=1;$x<=$dispCount;$x++) {
										echo '<tr>
												<td class="status_1">'.getPrevious('status', $previous, $x).'</td>';
												//$past = getPrevious('time', $previous, $x);
												if($x == 1) { $past = $row['status_timestamp']; }
												if($x == 2 || $x == 3) { $past = getPrevious('time', $previous, $x-1); }
												echo'
												<td class="status_2">'.calcStatusHoursOutstanding($past, getPrevious('time', $previous, $x)).'</td>
												<td class="status_3">'.getPrevious('notes', $previous, $x).'</td>
											  </tr>';
									}
							echo '</table>'; //End of Status table
								
							$sql = "SELECT actions FROM ruleset WHERE event='$event'";
							if($action_query = mysqli_query($db, $sql)) {
								while($a = mysqli_fetch_array($action_query)) {
									$actions = explode("\n", $a['actions']);
								}
							}
							else {
								echo "Error Reading Actions";
							}
							echo '
							<form method="post" class="form-inline" action="'.$_SERVER['REQUEST_URI'].'">
								<table class="th_bottom_border detail_actions">
									<tr>
										<th colspan="2">Steps to Solution</th>
										<th>Solution</th>
									</tr>';
									listActions($actions, $radio);
									//first tr is filler space. should be done in css
								
								echo'<tr><td>&nbsp;</td></tr><tr>
										<td><b>Resolution:</b></td>
										<td>';
											$sql = "SELECT type FROM resolution_type";
											$result = mysqli_query($db, $sql);
											while($reso = mysqli_fetch_array($result)) {
												$resolution_type[] = $reso['type'];
											}
											foreach($resolution_type as $type) {
												// Checks if status = any of the resolution types
												// if so it won't display the option 
												if($currStatus == $type || $resolution == 'Closed' || $resolution == 'Manual Close') {
													$match = true;
												}
											}
											if(!$match) {
												echo '<select name="resolve_option" class="input-medium">
															<option value="open">Open</option>';	
													foreach($resolution_type as $type) {
														if(!empty($type))
														{
															echo "<option value=\"$type\">$type</option>";
														}
													}
												echo 	'</select>
														<input type="submit" class="btn btn-success" name="resolve" value="Resolve"></form>';
											}
											else {
												if($resolution == 'Closed' || $resolution == 'Manual Close')
													echo $resolution;
												else
													echo $currStatus;
											}
									//The -1 for each of the issues accounts for the current open issue. 
									//it takes that away because history will only show past ones
									echo '</td></tr>
									<tr>
										<td><b>History:</b></td>
										<td><a href="history.php?site='.$row['store_number'].'&event='.$row['event'].'">
											Specific Problem ('.($repeat_issues-1).')
										</a></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td><a href="history.php?site='.$row['store_number'].'">
											Full Site ('.($site_issues-1).')
										</a></td>
									</tr>
								</table>		
					';
					echo '<a href="related_events.php?prob_id='.$row['id'].'&store_number='.$row['store_number']
							.'&event='.$row['event'].'" rel="shadowbox;width=350px;height=400px" class="btn btn-primary">Linked Events</a><br>';
					if($_SESSION['level'] >= SUPERVISOR_LEVEL && $resolution == null) {
						echo '<a href="#" class="btn btn-danger force_close" id="force_close" data-id="'.$id.'">Manual Close</a>';
					}
				}
				
	include('includes/footer.php');
	mysqli_close($db);
?>
