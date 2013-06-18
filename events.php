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
	//$date_regex = "/^\d{2}[-\/]\d{2}[-\/]\d{4}$/";
	$site_regex = "/^[0-9]/";

	
	if(!empty($store_number)) {
		if(!preg_match($site_regex, $store_number)) {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error: Store Number - Accepts Numbers Only - Try Again</div>'; }
	}
?>
	<form method="get" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table class="history">
			<tr>
				<td>Start Date:</td>
				<td>
					<input type="text" name="startDate" value="<?php echo $_GET['startDate']; ?>" placeholder="Ex: 01-01-2013 14:35"></td>
			</tr>
			<tr>
				<td>End Date:</td>
				 <td><input type="text" name="endDate" value="<?php echo $_GET['endDate']; ?>"placeholder="<?php echo 'Ex: '.date('m-d-Y H:i', time()); ?>"></td>
			</tr>
			<tr>
				<td>Site #:</td>
				<td><input type="text" name="site" value="<?php echo $store_number; ?>"></td>
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
					<th>Received</th>
					<th>Store</th>
					<th>Total Hours</th>
					<th>Priority</th>
					<th>Description</th>
				</tr>';
			
			//Selecting All. loop below will concatenate where clause if necessary
			$sql = "SELECT * FROM events";
			//If one value is set. start checking search. 
			if(!empty($event) || !empty($store_number) || !empty($startDate) || !empty($endDate)) {
				$where_clause = array();
				if(!empty($event)) { $where_clause[] = "event='$event'"; }
				if(!empty($store_number)) { $where_clause[] = "store_number='$store_number'"; }
				/*For both dates I replace the - with / to make it
				standardized for strtotime to read it and convert
				to unix time successfuly. I also call americanDate
				to put the year in the front for standards */
				if(!empty($startDate)) {
					$startDate = preg_replace('/-/', '/', $startDate);
					$unixStartDate = strtotime(adminDateTime($startDate)); 
					$where_clause[] = "timestamp>='$unixStartDate'"; }
					echo "startDate = $unixStartDate";
				if(!empty($endDate)) { 
					$endDate = preg_replace('/-/', '/', $endDate);
					$unixEndDate = strtotime(adminDateTime($endDate)); 
					//sets time to end of day because it defaults to midnight if only date entered
					if(preg_match("|^\d{2}/\d{2}/\d{4}$|", $endDate)) {
						$unixEndDate += 3600*23+3599;			
					}
					echo "endDate = $unixEndDate";
					if(!empty($unixEndDate)) { //timestamp may not always be set
						$where_clause[] = "timestamp<='$unixEndDate'";  }
				}
				$where = implode(' AND ', $where_clause);
				$sql .= " WHERE $where";
			}
			$currPage = isset($_GET['page']) ? $_GET['page'] : 1;
			$resultsPerPage = 25;
			$skip = (($currPage - 1) * $resultsPerPage);
			
			$result = mysqli_query($db, $sql);
			$total = @mysqli_num_rows($result);
			$num_pages = ceil($total / $resultsPerPage);

			$url = $_SERVER['REQUEST_URI'];
			if($num_pages > 1) {
				echo genPageLinks($url, $num_pages);
			}
			$sql .= ' ORDER BY timestamp DESC';
			$sql .= " LIMIT $skip, $resultsPerPage";
			$result = @mysqli_query($db, $sql);
			if($total > 0) {
				while($row = @mysqli_fetch_array($result)) {
					$e = longEvent($row['event']);
					if(!siteExists($row['store_number'], $db)) {
						$e = 'No Site - '.$e;
					}
				
					$i = $row['id'];
					$link = "detail.php?id=$i";
					
					//echo '<br>';

					//echo "<br>Last Status:" . getLastStatus($row['previous_status']) . '<br>';

					echo '<tr>
							<td>'.formatTime($row['timestamp']).'</td>
							<td>'.$row['store_number'].'</td>
							<td>'.calcTotalHoursOutstanding($row).'</td>
							<td>'.getPriority($row['event'], $db).'</td>
							<td>'.$e.'</td>
						  </tr>';
				}
			}
			else {
				echo '<tr><td colspan="7">No Results Found</td><tr>';
			}
			echo '</table>';
	include('includes/footer.php');
?>