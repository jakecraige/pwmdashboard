<?php
	include('includes/header.php');
	require('includes/includes.php');

	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo "Error connecting to database.";
	}
	
	$store_number = mysqli_real_escape_string($db, $_GET['site']);
	$event = mysqli_real_escape_string($db, $_GET['event']);
	$startDate = mysqli_real_escape_string($db, $_GET['startDate']);
	$endDate = mysqli_real_escape_string($db, $_GET['endDate']);
	$getReso = mysqli_real_escape_string($db, $_GET['resolution']);
	$hrs = mysqli_real_escape_string($db, $_GET['hrs']);
	$close_type = mysqli_real_escape_string($db, $_GET['close_type']);
	$priority = mysqli_real_escape_string($db, $_GET['priority']);
	
	$date_regex = "/^\d{2}[-\/]\d{2}[-\/]\d{4}$/";
	$site_regex = "/^[0-9]/";

	if(!empty($startDate) && !empty($endDate)) {
		if(!preg_match($date_regex, $startDate) || !preg_match($date_regex, $endDate)) {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error: Date Formatting - Accepts 01-01-2013 - Try Again</div>'; }
	}
	if(!empty($store_number)) {
		if(!preg_match($site_regex, $store_number)) {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error: Store Number - Accepts Numbers Only - Try Again</div>'; }
	}
?>
	<form method="get" class="form-horizontal">
		<table class="history">
			<tr>
				<td>Start Date:</td>
				<td><input type="text" name="startDate" placeholder="<?php echo '01-01-'.date('Y'); ?>" 
				value="<?= isset($startDate) ? $startDate : '' ?>">
				</td>
			</tr>
			<tr>
				<td>End Date:</td>
				 <td><input type="text" name="endDate" placeholder="<?= date('m-d-Y'); ?>" 
				 value="<?= isset($endDate) ? $endDate : '' ?>">	
				</td>
			</tr>
			<tr>
				<td>Site #:</td>
				<td><input type="text" name="site" value="<?php echo $store_number; ?>"></td>
			</tr>
			<tr>
				<td>Total Hrs Exceeds:</td>
				<td><input type="text" name="hrs" value="<?php echo $hrs; ?>"></td>
			</tr>
			<tr>
				<td>Priority:</td>
				<td>
					<select name="priority">
						<option value="">Select Priority</option>
						<option value="">All</option>
						<option value="1" <?php if($priority == '1' ) echo 'selected="selected"'; ?>>Priority 1</option>
						<option value="2" <?php if($priority == '2' ) echo 'selected="selected"'; ?>>Priority 2</option>
						<option value="3" <?php if($priority == '3' ) echo 'selected="selected"'; ?>>Priority 3</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Problem Code:</td>
				<td>
					<select name="event">
						<option value="">Select Code</option>
						<option value="">All</option>
						<option value="LCP" <?php if($event == 'LCP' ) echo 'selected="selected"'; ?>>Line Controller Problem</option>
						<option value="SCP" <?php if($event == 'SCP' ) echo 'selected="selected"'; ?>>Sign Controller Problem</option>
						<option value="NCP" <?php if($event == 'NCP' ) echo 'selected="selected"'; ?>>No Comm - CU to Price Sign</option>
						<option value="NCA" <?php if($event == 'NCA' ) echo 'selected="selected"'; ?>>No Comm - CU to AMCS</option>
						<option value="NOS" <?php if($event == 'NOS' ) echo 'selected="selected"'; ?>>No Sensor</option>
						<option value="OVT" <?php if($event == 'OVT' ) echo 'selected="selected"'; ?>>Over Temperature</option>
						<option value="CPF" <?php if($event == 'CPF' ) echo 'selected="selected"'; ?>>CPI Failure</option>
						<option value="PCS" <?php if($event == 'PCS' ) echo 'selected="selected"'; ?>>Price Change Successful</option>
						<option value="PCE" <?php if($event == 'PCE' ) echo 'selected="selected"'; ?>>Price Change Event</option>
						<option value="IVP" <?php if($event == 'IVP' ) echo 'selected="selected"'; ?>>Invalid Price Change</option>
						<option value="RST" <?php if($event == 'RST' ) echo 'selected="selected"'; ?>>Reset</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Resolution Type:</td>
				<td>
				<select name="resolution">
					<option value="">Select Resolution</option>
					<option value="">All</option>
					<?php
						$sql = "SELECT type FROM resolution_type";
						$result = mysqli_query($db, $sql);
						while($reso = mysqli_fetch_array($result)) {
							$resolution = $reso['type'];
							echo "<option value=\"$resolution\"";
							if($resolution == $getReso) {
								echo 'selected="selected"';
							}
							echo ">$resolution</option>";
						}
					?>
				</select>
				</td>
			</tr>
			<tr>
				<td>Close Type:</td>
				<td>
					<select name="close_type">
						<option value="">Select Close Type</option>
						<option value="">All</option>
						<option value="By System" <?php if($close_type == 'By System' ) echo 'selected="selected"'; ?>>By System</option>
						<option value="By User" <?php if($close_type == 'By User' ) echo 'selected="selected"'; ?>>By User</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" class="btn btn-primary btn-block" value="Search">
				</td>
				<td>
					<input type="reset" class="btn btn-danger" value="Clear">
				</td>
			</tr>
		</table>
	</form>
		<?php
			echo '<table class="collection">
				<tr>
					<th>Ended</th>
					<th>Store</th>
					<th>Total Hours</th>
					<th>Priority</th>
					<th>Description</th>
					<th>Resolution Type</th>
					<th>Close Type</th>
				</tr>';
			
			//Selecting All. loop below will concatenate where clause if necessary
			$sql = "SELECT * FROM problems WHERE ( resolution='Closed' OR resolution='Manual Close' )";
			//If at least one value is set. start checking search. 
			if(!empty($event) || !empty($getReso) || !empty($store_number) || !empty($startDate) || !empty($endDate)
				|| !empty($hrs) || !empty($priority) || !empty($close_type)) {
				$where_clause = array();
				if(!empty($event)) { $where_clause[] = "event='$event'"; }
				if(!empty($getReso)) { 
					$where_clause[] = "previous_status LIKE '%$getReso%'"; 
				}
				if(!empty($store_number)) { $where_clause[] = "store_number='$store_number'"; }
				/*For both dates I replace the - with / to make it
				standardized for strtotime to read it and convert
				to unix time successfuly. I also call americanDate
				to put the year in the front for standards */
				if(!empty($startDate)) {
					$unixStartDate = dateToUnixTime($startDate);
					$where_clause[] = "timestamp>='$unixStartDate'"; 
				}
				if(!empty($endDate)) { 
					$unixEndDate = dateToUnixTime($endDate); 
					//sets time to end of day because it defaults to midnight
					$unixEndDate += 3600*23+3599;			
					if(!empty($unixEndDate)) { //resolution_timestamp may not always be set
						$where_clause[] = "resolution_timestamp<='$unixEndDate'";  
					}
				}
				if(!empty($hrs)) {
					$hrs *= 3600; //convert to sec
					$where_clause[] = "resolution_timestamp-timestamp >= '$hrs'"; 
				}
				if(!empty($close_type)) {
					if($close_type == 'By System')
					{
						$close = 'Closed';
					}
					else //By User
					{	
						$close = 'Manual Close';
					}
					$where_clause[] = "resolution='$close'"; 
				}
				if(!empty($priority))
				{
					$event_clause = array();

					$events = mysqli_query($db, "SELECT event FROM ruleset WHERE priority='$priority'");
					while($ev = mysqli_fetch_array($events))
					{
						$e = $ev['event'];
						$event_clause[] = "event='$e'";
					}
					$event_sql = implode(' OR ', $event_clause);
				}

				$where_sql = implode(' AND ', $where_clause);

				if(!empty($where_sql))
				{
					$sql .= " AND $where_sql"; 					
				}
				if(!empty($event_sql))
				{
					$sql .=	" AND ( $event_sql )";
				}


			}
			$currPage = isset($_GET['page']) ? $_GET['page'] : 1;
			$resultsPerPage = 25;
			$skip = (($currPage - 1) * $resultsPerPage);
			
			$result = mysqli_query($db, $sql);
			$total = @mysqli_num_rows($result);
			//echo $sql;
			$num_pages = ceil($total / $resultsPerPage);

			$url = $_SERVER['REQUEST_URI'];
			if($num_pages > 1) {
				echo genPageLinks($url, $num_pages);
			}
			$sql .= ' ORDER BY resolution_timestamp DESC';
			$sql .= " LIMIT $skip, $resultsPerPage";
			
			$result = @mysqli_query($db, $sql);
			if($total > 0) {
				while($row = @mysqli_fetch_array($result)) {
					$e = longEvent($row['event']);
					//echo $nosite;
					
					if(!siteExists($row['store_number'], $db)) {
						$nosite = "alert-error";
					}
					else { $nosite = ''; }
					$i = $row['id'];
					$link = "detail.php?id=$i";
					
					//echo '<br>';

					//echo "<br>Last Status:" . getLastStatus($row['previous_status']) . '<br>';

					echo '<tr>
							<td><a href="'.$link.'">'.formatTime($row['resolution_timestamp']).'</a></td>
							<td class="'.$nosite.'"><a href="'.$link.'">'.$row['store_number'].'</a></td>
							<td><a href="'.$link.'">'.calcTotalHoursOutstanding($row).'</a></td>
							<td><a href="'.$link.'">'.getPriority($row['event'], $db).'</a></td>
							<td><a href="'.$link.'">'.$e.'</a></td>
							<td><a href="'.$link.'">'.$row['resolution_type'].'</a></td>
							<td><a href="'.$link.'">'.formatResolution($row['resolution']).'</a></td>
						  </tr>'; //todo: add store name here from sites database set to unknown if site not added 
				}
			}
			else {
				echo '<tr><td colspan="7">No Results Found</td><tr>';
			}
			echo '</table>';
			
	include('includes/footer.php');
?>