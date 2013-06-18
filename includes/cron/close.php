<?php
	require("../logging.php"); 
	require("../amcs_dbvars.php");
	require("../determine_issue.php");
	require("../email_functions.php");
	require("../functions.php");
	require("../invalid_price.php");
	require("../close_issue.php");
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	checkCloseProblem($db);
?>