<?php
	function sendMail($subject, $message, $count) {
		for($x = 0; $x<$count; $x++) {
			$email_from = "james.craige@gmail.com";
			$email_to = "amcs@jakecraige.com";
			$email_subject = $subject;
			$email_message = $message;
			 
		// create email headers
		$headers = 'From: '.$email_from."\r\n".
		'Reply-To: '.$email_from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
		if(@mail($email_to, $email_subject, $email_message, $headers)) {
			echo '<p class="success"> Email Sent Successfully </p>';
			}
		}
	}
	if(isset($_GET["submit"])) {
		if(isset($_GET["quantity"])) {
			sendMail('PriceNet - Sapphire Failover Notification', $_GET["message"], $_GET["quantity"]);	
		}
	}
	if(isset($_GET["submit2"])) {
		if(isset($_GET["quantity2"])) {
			sendMail('PriceNet - Sapphire Failover Notification', $_GET["message2"], $_GET["quantity2"]);	
		}
	}
?>		
	<form action="" method="get">
		Quantity: 
		<input type="text" name="quantity" maxlength="2" size="3" value="<?php if(isset($_GET['quantity'])) echo $_GET['quantity']; else echo '1';?>">
		<br>
		<textarea name="message" rows="20" cols="100">Sapphire Post/Initialize Error Received:

Date/Time: Feb 13 2013  5:15PM
Site ID: 4528
Cause Category: Sign price mismatch
Message: 4528 UNLC: Proposed(3.379) Sign(3.319)

Stage: Sign price mismatch
		</textarea>
		<br>
		<input type="submit" name="submit" value="Send">
	</form>

	<form action="" method="get">
		Quantity: 
		<input type="text" name="quantity2" maxlength="2" size="3" value="<?php if(isset($_GET['quantity'])) echo $_GET['quantity']; else echo '1';?>">
		<br>
		<textarea name="message2" rows="20" cols="100">Sapphire Post/Initialize Error Received:

Date/Time: Feb 11 2013 12:37AM
Site ID: 4114
Cause Category: Network connectivity/Other
Message: The remote server returned an error: 150 Opening BINARY mode data connection for 'pricenet.xml' (4301 bytes).
.
Stage: Retrieve Prices From Electronic Sign
		</textarea>
		<br>
		<input type="submit" name="submit2" value="Send">
	</form>
