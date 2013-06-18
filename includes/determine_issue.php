<?php
	//First check that happens when new event is added. 
	function checkProblem($store_number, $event, $body, $email_id, $db) {
		if(siteLicensed($store_number, $db))
		{
			if($event == 'PCE') {
				//I have to change it early to check for problems that match
				//it's already added as PCE in event so it shouldn't cause
				//any issues
				$event = 'IVP'; 

				//Check if this is a valid time to be sending a price through
				if(!valid_pricechange_time($db))
				{
					createProblem($store_number, 'UAC', $body, $db);
				}

				// Instant search back x time for any event that's a problem
				// if no problem gen'd then add to queue to search forward time
				if(!check_backward_cns($store_number, $body, $db))
				{
					$pending_query = new Pending_queries($body);
					$pending_query->add($email_id);
				}
			}
			if($event == 'KSS-SPM' || $event == 'KSS-RPS')
			{
				createProblem($store_number, $event, $body, $db);
			}


			//If problem Exists - Returns id of problem, or 0 for no problem
			if($prob_id = checkProblemExists($store_number, $event, $db)) 
			{	
				updateProblem($email_id, $prob_id, $db);
				if($event == 'IVP') {
					///	checkPriceChange function returning 0 when valid price change 
					//	then code here will close out problems when correct price change received.
					if(checkPriceChange($store_number, $body, $db) == 0) {
						closeProblem ($prob_id, 'Closed', '', $db);
					}
				} 

				//Here we check if the status of the problem_id equals one of the resolution types
				//if so, then update status to unresolved.
				$p_sql = "SELECT * FROM problems WHERE id='$prob_id' AND resolution<>'Closed' AND resolution<>'Manual Close'";
				$p_query = mysqli_query($db, $p_sql);
				while($prob = mysqli_fetch_array($p_query)) {	
					$r_sql = "SELECT type FROM resolution_type";
					$r_query = mysqli_query($db, $r_sql);
					$r = array();
					while($resolution = mysqli_fetch_array($r_query)) {
						if($prob['status'] == $resolution['type']) {
							updateStatus($prob_id, 'Unresolved', $prob['status'], $prob['status_timestamp'], $prob['notes'], $prob['previous_status'], $prob['fixed_action'], $db);
						}
					}
				}
			}
			//Else a problem doesn't exist
			else {
				//If its not an invalid price change and an issue doesnt
				//exist this will check the ruels
				if(checkRuleSet($store_number, $event, $body, $db) && $event != 'IVP') {
						createProblem($store_number, $event, $body, $db);
				}
				else {
					//if it is an invalid price change OR Price Change event
					//that is not a problem yet. 
					if($event == 'IVP') {
						check_backward_cns($store_number, $body, $db);
						if(checkPriceChange($store_number, $body, $db) == 1) {
							createProblem($store_number, 'IVP', $body, $db);
							sendMail("Invalid Price Change - Store: $store_number", 
								"Please contact store number $store_number and correct the invalid price.", $db);
						}
					}
				}
			}
		} 
		else //No License for Site
		{
			sendMail("Unlicensed Site Information Received", 
					"Store Number: $store_number - Contact PWM at 713-290-0626 to purchase a license for this site to have it monitored through the dashboard.", $db);
		}
	}
	function checkProblemExists($store_number, $event, $db) {
		$prob_sql = "SELECT * FROM problems WHERE store_number='$store_number' AND
						event='$event' AND resolution<>'Closed' AND resolution<>'Manual Close' LIMIT 1";
		$query = mysqli_query($db, $prob_sql);
		if(mysqli_num_rows($query) > 0) {
			while($prob = mysqli_fetch_array($query)) {
				return $prob['id'];
			}
		}
		else {
			return 0;
		}
	}
	function checkStoreProblemExists($store_number, $db) {
		$prob_sql = "SELECT * FROM problems WHERE store_number='$store_number' AND resolution<>'Closed' AND resolution<>'Manual Close' LIMIT 1";
		$query = mysqli_query($db, $prob_sql);
		if(mysqli_num_rows($query) > 0) {
			while($prob = mysqli_fetch_array($query)) {
				return $prob['id'];
			}
		}
		else {
			return 0;
		}
	}
	function updateProblem($email_id, $prob_id, $db) {
		$sql = "UPDATE problems SET linked_events=CONCAT_WS(' ',linked_events,'$email_id') WHERE id='$prob_id'";
		return ($result = mysqli_query($db, $sql));
	}
	function updateResolutionType($id, $resolution, $db) {
		$sql = "UPDATE problems SET resolution_type='$resolution' WHERE id='$id'";
		$result = mysqli_query($db, $sql);
	}
	function checkRuleset($store_number, $event, $body, $db) {
		$etp_sql = "SELECT error_to_problem, trim FROM ruleset WHERE event='$event'";
		$etp_query = mysqli_query($db, $etp_sql);
		while($etp_result = mysqli_fetch_array($etp_query)) {
			//etp is same as transmission rate
			$etp = $etp_result['error_to_problem'];
			$trim = $etp_result['trim'];
		}
		$trans_sql = "SELECT trans_rate, active FROM sites WHERE number='$store_number' LIMIT 1";
		$trans_query = mysqli_query($db, $trans_sql);
		while($trans_result = @mysqli_fetch_array($trans_query)) {
			$trans_rate = $trans_result['trans_rate'];
			$active = $trans_result['active'];
		}
		//Site not configured
		//Pull trans_rate from system config
		if(empty($trans_rate)) {
			$trans_sql = "SELECT trans_rate FROM system_config";
			$trans_query = mysqli_query($db, $trans_sql);
			while($trans_result = mysqli_fetch_array($trans_query)) {
				$trans_rate = $trans_result['trans_rate'];
			}
		}
		
		$currTime = time();
		// $etp * 60 to turn minutes into seconds
		$adjTime = $currTime - ($etp*60);
		$num_errors = floor($etp/$trans_rate) + $trim;
	//	echo "$etp / $trans_rate + $trim = $num_errors <br>";
	//	echo "CALCULATION NUM ERROSRS: $etp / $trans_rate";
		$sql = "SELECT * FROM events WHERE store_number='$store_number' AND event='$event' AND timestamp > '$adjTime'";
		$result = mysqli_query($db, $sql);

		//echo "Results from DB: $results <br>";
		//This encompasses adding problems that have no site as well as
		//denying adding sites that are added and set to not active
		if($active != 'No') {
			//echo $num_errors . ' errors allowed <br>';
			//echo "result = " . mysqli_num_rows($result) . '<br>';
			if(mysqli_num_rows($result) > $num_errors) {
				createProblem($store_number, $event, $body, $db);	
			}
		}
		//Todo: Else log that site not active but emails coming in
	}
	function createProblem($store_number, $event, $body, $db) {
		$time = time();
		$body = mysqli_real_escape_string($db, $body);
		//Problems Table Structure
		//id, store_number, event, timestamp, linked_events, status, status_timestamp, notes, previous_status, resolution_type,
		//resolution, resolution_timestamp, fixed_action, body
		$sql = "INSERT INTO problems values(0, '$store_number', '$event', '$time', '', 'New', '$time', 'None', '', '', '', '', '', '$body')";
		//echo $sql;
		if(!$result = mysqli_query($db, $sql)) {
			do_log('detemine_issue', 'Cannot create problem', mysqli_real_escape_string($db, $sql), 'Failure', $db);

		}
	}
	function siteLicensed($site_number, $db)
	{
		if(mysqli_num_rows(mysqli_query($db, "SELECT id FROM licensed_sites WHERE site_number='$site_number'")) > 0)
		{
			return TRUE;
		}
		return FALSE;
	}
?>