<?php
class Pending_queries
{
	private $db;
	private $f_cns_time = null;
	private $body = null;
	//private $queries = array();

	public function __construct($body = '')
	{
		$this->db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME);
		$this->body = $body;

		//get forward and backward cns time and set it
		$cns = mysqli_query($this->db, "SELECT forward_cns_time FROM system_config");
		while($row = mysqli_fetch_array($cns)) {
			$this->f_cns_time = $row['forward_cns_time'];
		}
	}

	public function add($problem_id)
	{
		$curr_time = time();
		$execution_ts = $curr_time + $this->f_cns_time*60;

		$sql = "INSERT INTO pending_queries VALUES(0, $problem_id, $curr_time, $execution_ts, 0)";
		$result = mysqli_query($this->db, $sql) or die($sql);
		//print_r($this->queries);
	}	

	public function run()
	{
		//call function for each query in row
		//set to is processing when doign it, 
		//delete when done
		$time = time();
		$queries = mysqli_query($this->db, "SELECT * FROM pending_queries WHERE execution_ts < '$time' AND is_processing='0'");

		while($query = mysqli_fetch_array($queries)) {
			echo 'query!<br>';
			$added_ts = $query['added_ts'];
			$execution_ts = $query['execution_ts'];
			$store_number = $this->get_store_number($query['event_id']);
			$events = mysqli_query($this->db, "SELECT * FROM events 
												WHERE store_number='$store_number'
												AND event <> 'PCE' 
												AND timestamp > '$added_ts' 
												AND timestamp < '$execution_ts' "
			);
			echo "SELECT * FROM events 
												WHERE store_number='$store_number'
												AND event <> 'PCE' 
												AND timestamp > '$added_ts' 
												AND timestamp < '$execution_ts' <br>";
			//When an event is returned meaning that a bad event happend in the timeframe
			if(mysqli_num_rows($events) > 0)
			{
				echo 'EVENTS > 0<br>';
				$this->set_processing($query['id']);
				createProblem($store_number, 'CNS', $this->body, $this->db);
			}
			else { echo 'No Events'; }
			//Delete each after processing
			//$this->delete($query['id']);
		}
	}	

	public function delete($row_id)
	{
		$result = mysqli_query($this->db, "DELETE FROM pending_queries WHERE id='$row_id'");
	}

	private function set_processing($id)
	{
		mysqli_query($this->db, "UPDATE pending_queries SET is_processing='1' WHERE id='$id'");
	}

	private function get_store_number($event_id)
	{
		$result = mysqli_query($this->db, "SELECT store_number FROM events WHERE id='$event_id'");
		while($row = mysqli_fetch_array($result)) {
			return $row['store_number'];
		}
	}
}

?>