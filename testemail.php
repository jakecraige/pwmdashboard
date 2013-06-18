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
		if(isset($_GET["quantity"]) && isset($_GET["event"])) {
			sendMail($_GET["event"], $_GET["message"], $_GET["quantity"]);	
		}
	}
?>		
	<form action="" method="get">
		<select name="event">
			<option value="Sign Controller Problem">Sign Controller Problem</option>
			<option value="Line Controller Problem">Line Controller Problem</option>
			<option value="Over Temperature">Over Temperature</option>
			<option value="Reset">Reset</option>
			<option value="Price Change Event">Price Change Event</option>
			<option value="CPI Failure">CPI Failure</option>
			<option value="CU/Price Sign Communication Alert">CU/Price Sign Communication Alert</option>
			<option value="CU/AMCS2 Communication Alert">CU/AMCS2 Communication Alert</option>
		</select>
		Quantity: 
		<input type="text" name="quantity" maxlength="2" size="3" value="<?php if(isset($_GET['quantity'])) echo $_GET['quantity']; else echo '1';?>">
		<br>
		<textarea name="message" rows="20" cols="100">Email from Kangaroo Express 0000 AMCS2 UnitReason for this mail:
        pricechange (#1) from 3.409 $US to 3.359 $US
        pricechange (#2) from 3.589 $US to 3.539 $US
        pricechange (#3) from 3.769 $US to 3.719 $US

Grades: 
        Grade # 1 (Unleaded): 3.359 $US (13:32:11 31.01.2013)
        Grade # 2 (Midgrade): 3.539 $US (13:32:21 31.01.2013)
        Grade # 3 (Premium): 3.719 $US (13:32:21 31.01.2013)
        Grade # 4 (Diesel): 3.929 $US (05:28:10 31.01.2013)

Errors:

Temperatures:
        Sign # 1: 27 °C / 80 °F, min. 5 °C / 41 °F (06:59:04 25.11.2012), max. 64 °C / 147 °F (17:56:00 02.08.2012)

Fast, Friendly, and Clean
		</textarea>
		<br>
		<input type="submit" name="submit" value="Send">
	</form>

