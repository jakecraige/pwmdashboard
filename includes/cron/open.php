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
	$login = 'amcs@jakecraige.com'; 
	$pass = 'amcs2';
	if(!$inbox = imap_open("{mail.jakecraige.com:143/novalidate-cert}INBOX", $login, $pass)) {
		do_log('index', 'Failure to connect to Mailbox. Changed password or username?', '', 'Failure', $db);
	}

	$emails = imap_search($inbox,'ALL');
	/* if emails are returned, cycle through each... */
	if($emails) {
	  foreach($emails as $email_number) {
			$num = $email_number;
			$overview = @imap_fetch_overview($inbox,$num);
			$body = @imap_fetchbody($inbox,$num,"1");
			$subject = $overview[0]->subject;
			createEvent($subject, $body, $email_number, $inbox, $db);
	  }
	}
	imap_expunge($inbox);
	mysqli_close($db);
	imap_close($inbox);
?>