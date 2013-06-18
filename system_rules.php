<?php
	include('includes/header.php');
	require('includes/includes.php');
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	$page = 'system_rules';
	$mode = $_GET['mode'];
	$id = $_GET['id'];
	$type = $_GET['type'];
	$pageUpdated = false;

	if($_SESSION['level'] >= SUPERVISOR_LEVEL) {
		include('includes/rules/tabs.php');
		echo '<div class="row">';
			
			include('includes/rules/system_updates.php');
			//Set values
			$sql = "SELECT * FROM system_config";
			$result = mysqli_query($db, $sql);
			while($row = mysqli_fetch_array($result)) {
				$link = $_SERVER['PHP_SELF'].'?mode=edit';
				$refresh = $row['refresh_time'];
				$trans_rate = $row['trans_rate'];
				$max_price_change = $row['max_price_change'];
				$email_list = $row['email_list'];
				$forward_cns_time = $row['forward_cns_time'];
				$backward_cns_time = $row['backward_cns_time'];
				$uac_start_time = $row['uac_start_time'];
				$uac_end_time = $row['uac_end_time'];

				switch($max_price_change) {
						case 0.1:
							$textRate = '$0.10';
							break;
						case 0.15:
							$textRate = '$0.15';
							break;
						case 0.20:
							$textRate = '$0.20';
							break;
						case 0.25:
							$textRate = '$0.25';
							break;
						case 0.30:
							$textRate = '$0.30';
							break;
						case 0.40:
							$textRate = '$0.40';
							break;
						case 0.50: 
							$textRate = '$0.50';
							break;
				}
			}
			//end set values
			if($mode == 'edit')
			{
				include('includes/rules/system_edits.php');
			}
			include('includes/rules/system_values.php');

		echo '</div>'; //end row div
	} /*end session level auth*/
	include('includes/footer.php');
	mysqli_close($db);
?>