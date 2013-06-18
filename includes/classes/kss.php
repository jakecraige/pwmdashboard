<?php
	/*
	* Created on 3/28/13 10:33 AM by Jake Craige
	* 
	* This class is created when an email is received and use to parse
	* through and find specific data in the KSS emails that are used
	* for processing it into the AMCS2 Dashboard
	*
	*/
	class Kss {
		private $body = null;
		private $lines = null;
		
		public function __construct($body)
		{
			$this->body = $body;
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
			foreach($this->lines as $line)
			{
				if($site_id = $this->strstr_after($line, 'Site ID: '))
				{
					return trim($site_id);	
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