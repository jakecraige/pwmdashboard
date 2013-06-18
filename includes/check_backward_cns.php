<?php
	//checks backward time to see if bad event exists
	//Returns TRUE when it does and creates a CNS
	//Returns FALSE when it does not generate one. 
	function check_backward_cns($store_number, $body, $db) {
		//First make sure no CNS problem exists to prevent duplicates.
		if(checkProblemExists($store_number, 'CNS', $db) == 0) {
			$times = "SELECT backward_cns_time FROM system_config";
			$times_result = mysqli_query($db, $times);
			while($row = mysqli_fetch_array($times_result)) {
				//$forward = $row['forward_cns_time'];
				$backward = $row['backward_cns_time'];
			}
			if(!empty($backward)) {
				$backward = time() - minToSec($backward);

				$nopce_result = mysqli_query($db, "SELECT * FROM events WHERE store_number='$store_number' 
													AND event<>'PCE' AND timestamp>'$backward'");

				//When an event is returned meaning that a bad event happend in the timeframe
				if(mysqli_num_rows($nopce_result) > 0 ) {
					createProblem($store_number, 'CNS', $body, $db);
					return TRUE;
				}
			}
		} //end if loop
		return FALSE;
		//Else problem exists and we don't create another.
	} //end function
?>