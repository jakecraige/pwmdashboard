<?php
	
	require('../setup.php'); //DB Variables
	require('../classes/pending_queries.php'); //Class
	require('../determine_issue.php'); //createProblem()
	$queries = new Pending_queries();
	$queries->run();
?>