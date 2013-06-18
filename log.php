<?php
	require('includes/includes.php');
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	include('includes/header.php');
?>
<!-- ------PAGE CONTENT-------- -->
	<div class="span-24" id="content">
		<?php
			$sql = "SELECT * FROM log";
			$result = mysqli_query($db, $sql);
			echo '<table>
					<tr>
						<th>Page</th>
						<th>Activity</th>
						<th>Query</th>
						<th>Status</th>
						<th>Time</th>
					</tr>';
				while($row = mysqli_fetch_array($result)) {
					echo '<tr>
							<td>'.$row['page'].'</td>
							<td>'.$row['activity'].'</td>
							<td>'.$row['sql'].'</td>
							<td>'.$row['status'].'</td>
							<td>'.$row['unix_timestamp'].'</td>
						</tr>';
				}
			echo '</table>';
		?>
	</div>
<!-- ------END PAGE CONTENT-------- -->	
<?php 
	include('includes/footer.php');
	mysqli_close($db);
?>