<?php
	function do_log($page, $activity, $sql='', $success, $db) {       
		$time = time();
		$sql = "insert into log values (0, '$page','$activity','$sql','$success',$time)";   
		$result = mysqli_query($db, $sql) or die ("Cannot connect to log database!"); 	
	}     
?>