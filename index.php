<?php
	include('includes/header.php');
	require('includes/includes.php');
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	$page = 'index';

	$email_string = '{'.EMAIL_HOST.':143/novalidate-cert}INBOX';

	//if(!$inbox = imap_open($email_string, $login, $pass)) {
	if(!$inbox = imap_open($email_string, EMAIL_USER, EMAIL_PASS)) {
		do_log('index', 'Failure to connect to Mailbox. Changed password or username?', '', 'Failure', $db);
	}

	//Page Content
	$unr_sql = "SELECT status FROM problems WHERE status='Unresolved'";
	$unr_query = mysqli_query($db, $unr_sql);
	if(mysqli_num_rows($unr_query) > 0) {
		drawAmcsTable('UNR', $page, $db);
	}
	drawAmcsTable('1', $page, $db);
	drawAmcsTable('2', $page, $db);
	drawAmcsTable('3', $page, $db);			
	//End Page Content
	include('includes/footer.php');

	$emails = imap_search($inbox,'UNSEEN');
	/* if emails are returned, cycle through each... */
	if($emails) {
	  foreach($emails as $email_number) {
			$num = $email_number;
			$overview = @imap_fetch_overview($inbox,$num);
			$body = @imap_fetchbody($inbox,$num,"1");
			$subject = $overview[0]->subject;
			//echo "$subject <br>";
			createEvent($subject, $body, $email_number, $inbox, $db);
	  }
	}
	//checkCloseProblem($db);
	//clearResolution($db);
	
	imap_expunge($inbox);
	imap_close($inbox);
?>