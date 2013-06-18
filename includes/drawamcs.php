<?php
	function drawAmcsTable($set_priority, $page, $db) {
		if($set_priority == 'vault') {
			$sql = "SELECT * from events ORDER BY timestamp DESC LIMIT 20";
			$unr = true; //Used to make page display
		}
		else if($set_priority == 'UNR') {
			$sql = "SELECT * from problems WHERE resolution<>'Closed' AND resolution<>'Manual Close' AND status='Unresolved' ORDER BY timestamp DESC";
			echo '<h4 class="text-center"><b>Unresolved</b></h4>';
			$unr = true;
		}
		else {
			$sql = "SELECT * from problems WHERE resolution<>'Closed' AND resolution<>'Manual Close' AND status<>'Unresolved' ORDER BY timestamp DESC";
			$unr = false;
			echo '<h4 class="text-center"><b>Priority '.$set_priority.'</b></h4>';
		}	
	//	}
		$result = mysqli_query($db, $sql);
		echo '<table class="collection">
				<tr>
					<th>Store #</th>
					<th>Start</th>
					<th>Total Hours</th>
					<th>Description</th>
					<th>Status</th>
					<th>Status Hours</th>
					<th>Tracking#</th>
				</tr>';
		//$matches = 0; //Used to keep track if all terms are matched.
		//$count = 0; //how many counts sucessful to compare if new one made
		$ids = array();
		$keywords = $_GET['keywords'];
		$keywordCount = count(explode(' ', $keywords));
		$matches = 0;
		$displayed_count = 0;
		while($row = mysqli_fetch_array($result)) {	
			$event = $row['event'];
			if($page == 'vault') { //Show all issues including finished ones
				if(isset($keywords) && $keywords != '') { //If searching do this
					$id = search($page, $row, $keywords, getPriority($event, $db));
					if($row['id'] == $id) {
						array_push($ids, $row['id']);
					}
				}
				else { //If not searching
						array_push($ids, $row['id']);
						$keywordCount = 0;
				}
			}
			else if($page != 'vault') { //Only show issues that aren't finished
				if(isset($keywords) && $keywords != '') { //If searching do this
					$id = search($page, $row, $keywords, getPriority($event, $db));
					if($row['id'] == $id) {
						array_push($ids, $row['id']);
					}
				}
				else { //If not searching
						array_push($ids, $row['id']);
						$keywordCount = 0;
				}
			}
			$priority_sql = "SELECT priority FROM ruleset WHERE event='$event' LIMIT 1";
			$priority_query = mysqli_query($db, $priority_sql);
			if(mysqli_num_rows($priority_query) > 0) {
				while($row2 = mysqli_fetch_array($priority_query)) {
					$eventPriority = $row2['priority'];
				}
			}
			else { //Todo: Add logging if query doesn't work
				$eventPriority = 'Error';
			}
			$e = longEvent($row['event']);
			foreach ($ids as $i) {
				/*
					If specific event priority = priority set when calling this function meaning
					that it should display the event because it has the same priority we are looking for
					
					$unr variable tells if status is UNR so that it can ignore checking the priority stuff
					since it doesnt apply because UNR supercedes priority
					
					$dispay is set to true when an event meets this criteria so it will show the problem
					if false it won't show it
					
				*/
				$display = false;
				if($unr || $eventPriority == $set_priority) { $display = true; }
				if($display) { 
					if($row['id'] == $i) {
						$class = 'alert';
						$link = "detail.php?id=$i";
						if(!siteExists($row['store_number'], $db)) {
							$class = 'alert-error';
						}
						if(isResolutionType($row['status'], $db)) {
							$class = "alert-success";
						}
						echo '<tr>
								<td class="amcs_1 '.$class.'"><a href="'.$link.'">'.$row['store_number'].'</a></td>
								<td class="amcs_2"><a href="'.$link.'">'.formatTime($row['timestamp']).'</a></td>
								<td class="amcs_3"><a href="'.$link.'">'.calcTotalHoursOutstanding($row).'</a></td>
								<td class="amcs_4"><a href="'.$link.'">'.$e.'</a></td>
								<td class="amcs_5"><a href="'.$link.'">'.$row['status'].'</a></td>
								<td class="amcs_6"><a href="'.$link.'">'.calcHoursOutstanding($row).'</a></td>
								<td class="amcs_7"><a href="'.$link.'">'.$row['id'].'</a></td>
							  </tr>';
						$displayed_count++;
					}
				}
			}
		}
		if($displayed_count == 0) {
			//If nothing was displayed. Show no results
			echo '<tr><td colspan="7">No results found.</td></tr>';
		}
		echo '</table>';
	}

?>