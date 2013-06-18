<?php
	function checkCloseProblem($db) {
		//Todo: add timed closing of IVP issues. If they set status to closed it should then use the timeout
		$problems_sql = "SELECT * FROM problems WHERE resolution<>'Closed' OR resolution<>'Manual Close";
		//Todo: add logging for error querying
		$problems_query = mysqli_query($db, $problems_sql);
		while($prob = mysqli_fetch_array($problems_query)) {
			$event = $prob['event'];
			$sys_resolv_sql = "SELECT system_resolved FROM ruleset WHERE event='$event' LIMIT 1";
			$sys_resolv_query = mysqli_query($db, $sys_resolv_sql);
			while($sys = mysqli_fetch_array($sys_resolv_query)) {
				$sys_resolv = $sys['system_resolved'];
			}	
			// Timed expiration returning most recent problem emails timestamp
			$currTime = time();
			$expireTime = timedExpiration($prob, $db) + minToSec($sys_resolv);
			if($expireTime < $currTime)  { //If time is past the expired time, meaning it's expired and should close it
				closeProblem($prob['id'], 'By System', $expireTime, $db);
				//Commented out because I added this into close problem.
				//updateStatus($prob['id'], 'Closed',$prob['status'], $prob['status_timestamp'], $prob['notes'], $prob['previous_status'], $prob['fixed_action'], $db);
			}	
		}
	}
	function closeProblem ($id, $resolution, $expireTime, $db) {
		//This part updates the status so that the history.php and detail.php(status history) are listed correct
		$info = "SELECT * FROM problems WHERE id='$id' LIMIT 1";
		$res = mysqli_query($db, $info);
		while($i = mysqli_fetch_array($res)) {
			//Checks function is not resolved first. Prevent multiple resolutions being created by a refresh
			if(!empty($i['resolution'])) {
				return;
			}
			updateStatus($id, $resolution ,$i['status'], $i['status_timestamp'], $i['notes'], $i['previous_status'], $i['fixed_action'], $db);
		}

		if(empty($expireTime)) { //Add note for what this does, i'm not really sure.
			$expireTime = time(); 
		}
		$update_sql = "UPDATE problems SET resolution='$resolution', resolution_timestamp='$expireTime' WHERE id='$id'";
		//Todo: add logging if query errors out
		$update_query = mysqli_query($db, $update_sql);
		
	}
	function clearResolution($db) {
		$sql = "UPDATE problems set resolution='', resolution_timestamp=''";
		$problems_query = mysqli_query($db, $sql);
	}
?>