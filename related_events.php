<?php
	require('includes/setup.php');
	require('includes/includes.php');
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
			echo "Error connecting to database.";
		}

?>
<!doctype html>
<html>
<head>
		<link rel="stylesheet" href="includes/css/bootstrap.min.css" media="all" />	
		<link rel="stylesheet" href="includes/css/shadowbox.css" media="all" />
		<link rel="stylesheet" href="includes/css/related_events.css" media="all" />

	<title>Related Events</title>
</head>
<body>
	<h2 class="text-center">Linked Events</h2>
	<table>
		<tr>
			<th>Store #</th>
			<th>Event</th>
			<th>Time</th>
		</tr>
	<?php
			$id = $_GET['prob_id'];
			$event = $_GET['event'];
			$store_number = $_GET['store_number'];

			//startTime - Err_to_problem gives all leading up to event
			//get error_to_problem time
			$etp_query = mysqli_query($db, "SELECT error_to_problem FROM ruleset WHERE event='$event' LIMIT 1");
			while($row = mysqli_fetch_array($etp_query)) {
				$etp = $row['error_to_problem'];
			}

			//get linked events into array
			$linked = mysqli_query($db, "SELECT linked_events, timestamp FROM problems WHERE id='$id' LIMIT 1");
			while($row = mysqli_fetch_array($linked)) {
				$linked_events = explode(' ', $row['linked_events']);
				$start_time = $row['timestamp'];
			}
			//display all linked events
			echo '<tr><td colspan="3"><i>After Problem</i></td></tr>';
			$displayed_event = FALSE;
			if(!empty($linked_events)) {
				foreach($linked_events as $event_id) {
					$result = mysqli_query($db, "SELECT * FROM events WHERE id='$event_id' LIMIT 1");
					while($row = mysqli_fetch_array($result)) {
						echo '<tr>
								<td>'.$row['store_number'].'</td>
								<td>'.$row['event'].'</td>
								<td>'.formatTime($row['timestamp']).'</td>
							  </tr>';
						$displayed_event = TRUE;
					}
				}
			}
			if(!$displayed_event) {
				echo '<tr><td colspan="3">No results found</td></tr>';
			}
			echo '<tr><td colspan="3">&nbsp;</td></tr>';

			//get prior events
			$start_search_time = $start_time - minToSec($etp);
			$sql = "SELECT * FROM events WHERE store_number='$store_number'
											AND timestamp < '$start_time' 
											AND timestamp > '$start_search_time'";

			//Display Prior Events
			echo '<tr><td colspan="3"><i>Before Problem</i></td></tr>';								
			$event_query = mysqli_query($db, $sql);
			if(mysqli_num_rows($event_query) > 0) {
				while($row = mysqli_fetch_array($event_query)) {
						echo '<tr>
								<td>'.$row['store_number'].'</td>
								<td>'.$row['event'].'</td>
								<td>'.formatTime($row['timestamp']).'</td>
							  </tr>';
				}
			}
			else {
				echo '<tr><td colspan="3">No results found</td></tr>';
			}
			/*
			$sql = "SELECT * FROM events";
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
			echo '</table>';*/
		?>
	</table>
</body>
</html>