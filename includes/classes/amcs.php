<?php
<?php
	/*
	* Created on 3/28/13 10:33 AM by Jake Craige
	* 
	* This class is created when an email is received and use to parse
	* through and find specific data in the KSS emails that are used
	* for processing it into the AMCS2 Dashboard
	*
	*/
	class Amcs {
		private $lines = null;
		
		public function __construct($body)
		{
			$this->lines = preg_split("/(\r\n|\n|\r)/", $body);
		}
		public function get_message()
		{
			foreach($this->lines as $line)
			{
				if($message = $this->strstr_after($line, 'Message: '))
				{
					return trim($message);	
				}
			}
			return FALSE;
		}
		public function get_stage()
		{
			foreach($this->lines as $line)
			{
				if($stage = $this->strstr_after($line, 'Stage: '))
				{
					return trim($stage);	
				}
			}
			return FALSE;
		}
		public function get_site_id()
		{
			//$store = explode(' ', $lines[0]);
			//return $store[4];
			
			foreach($this->lines as $line)
			{
				if(strstr($line, 'Email from '))
				{
					if(preg_match('|\d{3}\d+|', $line, $matches))
					{ //matches any 4 or more digit string of numbers
						return $matches[0];	
					}
				}
			}
			return FALSE;
		}
		private function strstr_after($haystack, $needle, $case_insensitive = false) {
		    $strpos = ($case_insensitive) ? 'stripos' : 'strpos';
		    $pos = $strpos($haystack, $needle);
		    if (is_int($pos)) {
		        return substr($haystack, $pos + strlen($needle));
		    }
		    // Most likely false or null
		    return $pos;
		}
	}
?>
?>