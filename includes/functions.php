<?php
	function longEvent($event) {
		switch($event) {
			case 'NOS':
				return 'No Sensor';
			case 'OVT':
				return 'Over Temperature';
			case 'LCP':
				return 'Line Controller Problem';
			case 'SCP':
				return 'Sign Controller Problem';
			case 'NCP':
				return 'CU/Price Sign Comm Alert';
			case 'NCA':
				return 'CU/AMCS2 Comm Alert';
			case 'CPF':
				return 'CPI Failure';
			case 'PCS':
				return 'Price Sign Change Successful';
			case 'PCE':
				return 'Price Change Event';
			case 'IVP':
				return 'Invalid Price Change';
			case 'RST':
				return 'Reset';
			case 'CNS':
				return 'Price Change Not Successful';
			case 'KSS-SPM':
				return 'Sign Price Mismatch';
			case 'KSS-RPS':
				return 'Retreive Prices From Sign';
			case 'UAC':
				return 'Unauthorized Price Change';
		}
	}
	function shortEvent($event) {
		switch($event) {
			case 'No Sensor':
				return 'NOS';
			case 'Over Temperature':
				return 'OVT';
			case 'Line Controller Problem':
				return 'LCP';
			case 'Sign Controller Problem':
				return 'SCP';
			case 'CU/Price Sign Communication Alert':
				return 'NCP';
			case 'CU/AMCS2 Communication Alert':
				return 'NCA';
			case 'CPI Failure':
				return 'CPF';
			case 'Price Sign Change Successful':
				return 'PCS';
			case 'Price Change Event':
				return 'PCE';
			case 'Invalid Price Change': //Unneccesary really. 
				return 'IVP';
			case 'Reset':
				return 'RST';
			case 'Price Change Not Successful':
				return 'CNS';
			case 'Sign price mismatch':
				return 'KSS-SPM';
			case 'Retrieve Prices From Electronic Sign':
				return 'KSS-RPS';
		}
	}
	function getPriority($event, $db) {
		$sql = "SELECT * FROM ruleset WHERE event='$event' LIMIT 1";
		if(!$result = mysqli_query($db, $sql)) {
			do_log('getPriority', 'Error Querying Database', mysqli_real_escape_string($db, $sql), 'Failure', $db);
			return 'Error: Retreiving Priority';
		}
		if(mysqli_num_rows($result) != 0) {
			while($row = mysqli_fetch_array($result)) {
				return $row['priority'];
			}
		}
		else {
			do_log('getPriority', 'Error Querying Database', mysqli_real_escape_string($db, $sql), 'Failure', $db);
			return 'Error: Retreiving Priority';
		}
	}
	function getExpiration($id, $db) {
		$sql = "SELECT status, resolution_timestamp FROM problems WHERE id='$id' LIMIT 1";
		$result = mysqli_query($db, $sql);
		while($row = mysqli_fetch_array($result)) {
			$timestamp = $row['resolution_timestamp'];
			$status = $row['status'];
		}
		if($status == 'Closed' && $timestamp != 0) {
			return $timestamp;
		}
		else {
			return 0;
		}
	}
	// Returns most recent problem in linked events timestamp if it exists. 
	function timedExpiration($problem, $db) {
		if(empty($problem['linked_events'])) {
			return $problem['timestamp'];
		}
		else {
			//Get id of last element, aka most recent, in linked events.
			//Then returns the timestamp of that
			$linked = explode(' ', $problem['linked_events']);
			$id = end($linked);
			$sql = "SELECT timestamp FROM events WHERE id='$id' LIMIT 1";
			$result = mysqli_query($db, $sql);
			while($row = mysqli_fetch_array($result)) {
				return $row['timestamp'];
			}
		}
	}	
	//issue history and site history
	function checkRepeatIssues($store, $event, $db) {
		$sql = "SELECT * FROM problems WHERE store_number='$store' AND event='$event'";
		$result = mysqli_query($db, $sql);
		return mysqli_num_rows($result);
	}
	function checkSiteIssues($store, $db) {
		$sql = "SELECT * FROM problems WHERE store_number='$store'";
		$result = mysqli_query($db, $sql);
		return mysqli_num_rows($result);
	}
	function timeToText($difference) {
		$hours = floor($difference / 3600);
		$minutes = floor(($difference-($hours*3600))/60);
		$hrs = 'hrs';
		$mins = 'mins';
		if($hours == '1') {
			$hrs = 'hr';
		}
		if($minutes == 1) {
			$mins = 'min';
		}
		return $hours.' '.$hrs.' '.$minutes.' '.$mins; 
	}
	function calcHoursOutstanding($row) {
		$start = $row['status_timestamp'];
		if($row['resolution'] != 'Closed') { //If issue still open
			$difference = time()-$start;
		}
		else { //If issue closed use close time to calculate outstanding time
			$difference = $row['resolution_timestamp']-$start;
		}
		return timeToText($difference);
	}
	function calcTotalHoursOutstanding($row) {
		$start = $row['timestamp'];
		if($row['resolution'] != 'Closed') { //If issue still open
			$difference = time()-$start;
		}
		else { //If issue closed use close time to calculate outstanding time
			$difference = $row['resolution_timestamp']-$start;
		}
		return timeToText($difference);
	}
	function calcStatusHoursOutstanding($past, $current) {
		//takes in past status for calculating time difference between that and current one
		$difference = $past-$current;
		return timeToText($difference);
	}
	function formatTime($time) {
		return date("m-d-Y H:i",$time);
	}
	function formatDate($time) {
		if($time == 'Expired') {
			return $time; //This is for when the warranty check calls it and it's already deemed expired
		}
		return date("m-d-Y", $time);
	}
	function listActions($array, $radio) {
		$actionCount = 1;
		for($x = 0; $x < count($array); $x++) {
			if(($x % 2) == 0) { //If it's an even value
				echo '<tr>
						<td class="actions"><b>'.$actionCount.'. </b></td>
						<td>'.$array[$x].'</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"';
							if($radio == $actionCount ) 
								echo ' checked ';
					echo	'name="radio" class="text-center" value="'.$actionCount++.'"></td></tr>';
			}
		}
	}
	function minToSec($min) {
		//Converts minutes to seconds
		return $min*60;
	}
	function updateStatus($id, $newStatus, $currStatus, $status_timestamp, $notes, $previous, $radio, $db) {
		//previous status format status1:timestamp:notes!-!
		//Run explode on !-!
		$time = time();
		
		$modStatus = $currStatus.'::'.$status_timestamp.'::'.$notes.'!-!';
		$save = "UPDATE problems SET previous_status=CONCAT(previous_status,'$modStatus') WHERE id='$id'";
		$saved = mysqli_query($db, $save);
		
		//Now update status with new status
		if($newStatus == 'Closed' || $newStatus == 'Unresolved') {
			$newNote = 'By System'; }
		else if($newStatus == "Manual Close") {
			$newNote = 'By User('.$_SESSION['user'].')';
		}
		else {
			$newNote = 'None'; }
		$update = "UPDATE problems SET status='$newStatus', status_timestamp='$time', notes='$newNote', fixed_action='$radio' WHERE id='$id'";
		$updated = mysqli_query($db, $update);
	}
	function getPrevious($type, $previous, $backCount) {
		$explode_one = explode('!-!', $previous);
		foreach ($explode_one as $x) {
			$string .= ($x.'::'); //Adds in the seperator to split the next one. 1::2::34::5::6 to 1::2::3::4::5::6
		}
		$array = explode('::', $string);
		$array = array_diff($array, array('')); //This does nothing? Remove?
		$backCount *= 3;
		
		if($type == 'status') {
			$adj = 0;
		}
		else if($type == 'time') {
			$adj = 1;
		}
		else if($type == 'notes') {
			$adj = 2; 
		}
		else {
			return 'Error Getting Data';
		}
		$id = count($array) - $backCount + $adj;
		return $array[$id];
	}
	function americanDate($date) {
		$y = substr($date, 6, 4);
		$md = substr($date, 0, 5);
		return $y . '/' . $md;
	}
	function adminDateTime($date) {
		//Take in 01-22-2012 05:23:12, return 2012-01-22 05:23:12
		$format_date = americanDate($date);
		return $format_date . substr($date, 11); 
	}
	function dateToUnixTime($date) {
		$date = preg_replace('/-/', '/', $date);
		return strtotime(americanDate($date));
	}
	function genPageLinks($url, $num_pages) {
		$pageLinks = '<div class="pagination"><ul>';
		$p_string = '?page=';
		//Checks if the page is set, if not, defaults to page 1 for $currPage		
		if(!$currPage = $_GET['page']) {
			$currPage = 1;	
		}
		//this removes page variable each time so it does not
		//stack onto the url
		$url = preg_replace('/mode=edit&/', '', $url);
		$url = preg_replace('/[\?&]page=\d\d?$/', '', $url);
		if(preg_match('/.php\?/', $url)) {
			$p_string = '&page=';
		}
		
		if($currPage > 1) {
			$pageLinks .= '<li><a href="'.$url.$p_string.($currPage - 1).'">&lt;&lt;</a></li>'; }
		else {
			$pageLinks .= '<li class="disabled"><a>&lt;&lt;</a></li>'; }
		for($i = 1; $i <= $num_pages; $i++) {
			if($currPage == $i) {
				$pageLinks .= '<li class="active"><a>' . $i . '</a></li>'; }
			else {
				$pageLinks .= '<li><a href="'.$url.$p_string.$i.'">'.$i.'</a></li>'; }
		}
		if($currPage < $num_pages) {
			$pageLinks .= '<li><a href="'.$url.$p_string.($currPage + 1).'">&gt;&gt;</a></li>'; }
		else {
			$pageLinks .= '<li class="disabled"><a>&gt;&gt;</a></li>'; }
		
		$pageLinks .= '</ul></div>';
		return $pageLinks;
	}
	function siteExists($store_number, $db) {
		$sql = "SELECT * FROM sites WHERE number='$store_number' AND active='Yes'";
		//echo '<br>' . $sql;
		$result = mysqli_query($db, $sql);
		if(mysqli_num_rows($result) > 0) {
			//echo ' -> exists.';
			return true;
		}
	}
	function numSigns($store_number, $db) {
		$sql = "SELECT num_signs FROM sites WHERE number='$store_number' LIMIT 1";
		$result = mysqli_query($db, $sql);
		if(mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$signs = $row['num_signs'];
			}	
		}
		return $signs;
	}
	function checkLinkedEvents($id, $db) {
		$sql = "SELECT linked_events FROM problems WHERE id='$id'";
		$result = mysqli_query($db, $sql);
		$array = array();
		while($row = mysqli_fetch_array($result)) {
			if(!empty($row['linked_events'])) {
				$array = explode(' ', $row['linked_events']);
			}
		}
		return $array;
	}
	function deleteSite($id, $db) {
		$query = "DELETE FROM sites WHERE id='$id'";
		if($result = mysqli_query($db, $query)) {
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>';
			echo 'Site Deleted.';
		echo '</div>';
		}
		else {
			echo '<p class="error">Error Deleting Site. Contact Administrator.</p>';
			do_log('rules', 'MySql Error deleting site.', mysqli_real_escape_string($db, $query), 'Failure', $db);
		}
	}
	function formatResolution($resolution) {
		switch($resolution) {
			case 'Closed':
				return 'By System';
				break;
			case 'Manual Close':
				return 'By User';
				break;
			return;
		}
	}
	function checkWarranty($expiration) {
		if($expiration < time()) {
			return "Expired";
		}
		return $expiration;
	}
	function isResolutionType($status, $db) {
		$sql = "SELECT type FROM resolution_type";
		$result = mysqli_query($db, $sql);
		while($reso = mysqli_fetch_array($result)) {
			if($status == $reso['type']) {
				return true;
			}
		}
		return false;
	}
	function generatePassword($length) {
		$lowercase = "qwertyuiopasdfghjklzxcvbnm";
		$uppercase = "ASDFGHJKLZXCVBNMQWERTYUIOP";
		$numbers = "1234567890";
		$specialcharacters = "";
		$randomCode = "";
		mt_srand(crc32(microtime()));
		$max = strlen($lowercase) - 1;
		for ($x = 0; $x < abs($length/3); $x++) {
		$randomCode .= $lowercase{mt_rand(0, $max)};
		}
		$max = strlen($uppercase) - 1;
		for ($x = 0; $x < abs($length/3); $x++) {
		$randomCode .= $uppercase{mt_rand(0, $max)};
		}
		$max = strlen($specialcharacters) - 1;
		for ($x = 0; $x < abs($length/3); $x++) {
		$randomCode .= $specialcharacters{mt_rand(0, $max)};
		}
		$max = strlen($numbers) - 1;
		for ($x = 0; $x < abs($length/3); $x++) {
		$randomCode .= $numbers{mt_rand(0, $max)};
		}
		return str_shuffle($randomCode);
		//Read more: http://jaspreetchahal.org/php-random-password-generator-function/#ixzz2OlmCZD7m
	}
	function is_overnight($start_time, $end_time)
	{ //UNUSED
		if($start_time > $end_time)
		{
			return TRUE;
		}
		return FALSE;
	}

?>