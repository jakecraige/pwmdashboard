<?php
	$refresh_time = mysqli_real_escape_string($db, $_POST['refresh']);
	$max_price_change = mysqli_real_escape_string($db, $_POST['max_price_change']);
	$trans_rate = mysqli_real_escape_string($db, $_POST['trans_rate']);
	$email_list = $_POST['email_list']; //I escape later
	$forward_cns = mysqli_real_escape_string($db, $_POST['forward_cns_time']);
	$backward_cns = mysqli_real_escape_string($db, $_POST['backward_cns_time']);
	$uac_start_time = mysqli_real_escape_string($db, $_POST['uac_start_time']);
	$uac_end_time = mysqli_real_escape_string($db, $_POST['uac_end_time']);
	

	if(isset($_POST['edit_refresh'])) {
		$sql = "UPDATE system_config SET refresh_time='$refresh_time' WHERE id='1'"; 
		if($result = mysqli_query($db, $sql)) {
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Configuration.</div>';
			$pageUpdated = true;
		}
		else {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Configuration. Contact Administrator.</div>';
			do_log('rules', 'MySql Error Updating Configuration', mysqli_real_escape_string($db, $sql), 'Failure', $db);
		}
	}
	if(isset($_POST['edit_max_price_change'])) {
		$sql = "UPDATE system_config SET max_price_change='$max_price_change' WHERE id='1'"; 
		if($result = mysqli_query($db, $sql)) {
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Max Price Change.</div>';
			$pageUpdated = true;
		}
		else {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Max Price Change. Contact Administrator.</div>';
			do_log('rules', 'MySql Error Updating Configuration', mysqli_real_escape_string($db, $sql), 'Failure', $db);
		}
	}
	if(isset($_POST['edit_trans_rate'])) {
		$sql = "UPDATE system_config SET trans_rate='$trans_rate' WHERE id='1'"; 
		if($result = mysqli_query($db, $sql)) {
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Transmission Rate.</div>';
			$pageUpdated = true;
		}
		else {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Transmission Rate. Contact Administrator.</div>';
			do_log('rules', 'MySql Error Updating Configuration', mysqli_real_escape_string($db, $sql), 'Failure', $db);
		}
	}
	if(isset($_POST['edit_back_cns_time'])) {
		$sql = "UPDATE system_config SET backward_cns_time='$backward_cns' WHERE id='1'"; 
		if($result = mysqli_query($db, $sql)) {
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Backward CNS Time.</div>';
			$pageUpdated = true;
		}
		else {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Backward CNS Time. Contact Administrator.</div>';
			do_log('rules', 'MySql Error Updating CNS Times', mysqli_real_escape_string($db, $sql), 'Failure', $db);
		}
	}
	if(isset($_POST['edit_forward_cns_time'])) {
		$sql = "UPDATE system_config SET forward_cns_time='$forward_cns' WHERE id='1'"; 
		if($result = mysqli_query($db, $sql)) {
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated Forward CNS Times.</div>';
			$pageUpdated = true;
		}
		else {
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Foward CNS Time. Contact Administrator.</div>';
			do_log('rules', 'MySql Error Updating CNS Times', mysqli_real_escape_string($db, $sql), 'Failure', $db);
		}
	}
	if(isset($_POST['edit_emails'])) {
		//Allows email to be entered with commas, spaces, or new lines
		$new_email_list = preg_replace('/[,\s]/', "\n", $email_list);
		$new_email_list = preg_split("/(\r\n|\n|\r|\s|,)/", $email_list);
		$errors = '';
		$valid_email_list = array();
		foreach ( $new_email_list as $email ) {
			$email = trim($email);
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				$valid_email_list[] = $email;
			}
			else {
				if(!empty($email)) {
					$errors .= 'Error: Invalid Email Entered ('.$email.')<br>';
				}
			}
		}
		if(!empty($errors)) {
			echo "<p class=\"error\">$errors</p>";
		}
		$valid_email_list = implode(' ', $valid_email_list);
		if(!empty($valid_email_list)) {
			$sql = "UPDATE system_config SET email_list='$valid_email_list' WHERE id='1'"; 
			if($result = mysqli_query($db, $sql)) {
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated alert email list with valid emails.</div>';
				$pageUpdated = true;
			}
			else {
				echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating Alert Email List. Contact Administrator.</div>';
				do_log('rules', 'MySql Error Updating Configuration', mysqli_real_escape_string($db, $sql), 'Failure', $db);
			}
		}
	} // end isset post emails
	if(isset($_POST['edit_uac_times'])) {
		if(preg_match('|^\d{2}:\d{2}$|', $uac_start_time)
			&& preg_match('|^\d{2}:\d{2}$|', $uac_end_time))
		{
			$sql = "UPDATE system_config 
					SET uac_start_time='$uac_start_time', uac_end_time='$uac_end_time' 
					WHERE id='1'"; 
			if($result = mysqli_query($db, $sql)) {
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated UAC Times.</div>';
				$pageUpdated = true;
			}
			else {
				echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error Updating UAC Times. Contact Administrator.</div>';
				do_log('rules', 'MySql Error Updating UAC Times', mysqli_real_escape_string($db, $sql), 'Failure', $db);
			}
		}
		else 
		{ //Improper Formatting
			echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Please format times in HH:MM 24-hour format.</div>';
		}
	}
?>