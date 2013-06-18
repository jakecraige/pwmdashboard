<?php
	function getNumPriceChanged($body, $lines) {
		/* Works kind of off. Splits it up into lines then once it reaches the
		first blank line it will return the key. The key -1 equals the amount
		of lines.*/
		foreach($lines as $key=>$line) {
			if(empty($line)) {
				return $key-1;
			}
		}
	}
	function checkPriceChange($store_number, $body, $db) {
		//Line index is equal to the actual line i'm on
		$lines = preg_split("/(\r\n|\n|\r)/", $body);
		$num_changes = getNumPriceChanged($body, $lines);
		for($i = 1; $i <= $num_changes; $i++) {
			$price = explode(' ', trim($lines[$i]));
			$price_from = $price[3];
			$price_to = $price[6];
			//If invalid price change
			if(comparePrices($price_from, $price_to, $db) > 0) {
				$value = 1;
				break;
			}
			//if valid price change return 0 
			else {
				$value = 0;
			}
		}
		return $value;
	}
	function formatPriceChange($body) {
		$lines = preg_split("/(\r\n|\n|\r)/", $body);
		$num_changes = getNumPriceChanged($body, $lines);
		$text = '';
		for($i = 1; $i <= $num_changes; $i++) {
			$text .= $lines[$i] . '<br>';
			//echo $lines[i] . '<br>';
		}
		return $text;
	}

	function comparePrices($from, $to, $db) {
		$range_sql = "SELECT max_price_change FROM system_config LIMIT 1";
		$range_query = mysqli_query($db, $range_sql);
		while($range = mysqli_fetch_array($range_query)) {
			$difference = abs($to - $from);
			if($difference > $range['max_price_change']) {
				return $difference;
			}
			else {
				return 0;
			}
		}
	}
	/******************************
	*	Below is for checking for Unauthorized Price Chance(UAC)
	*******************************/
	function valid_pricechange_time($db)
	{
		$time = date('H:i');
		$result = mysqli_query($db, "SELECT uac_start_time, uac_end_time FROM system_config");
		while($row = mysqli_fetch_array($result))
		{
			$start_time = str_replace(':', '', $row['uac_start_time']);
			$end_time = str_replace(':', '', $row['uac_end_time']);
			if(is_overnight($start_time, $end_time))
			{
				echo 'OVERNIGHT';
				if(($time > $start_time && $time < '2400') || ($time < $end_time && $time > '0000'))
				{
					return FALSE;
				}
				return TRUE;
			}
			else
			{
				echo 'NOT OVERNIGHT';
				if($time > $start_time && $time < $end_time)
				{
					return FALSE;
				}
				return TRUE;
			}
		}
	}
?>