<?php
	include('includes/header.php');
	require('includes/includes.php');
	if(!$db = mysqli_connect(AMCS_HOST, AMCS_USER, AMCS_PASS, AMCS_NAME)) {
		echo 'Error connecting to database.';
	}
	$page = 'sites';
	$mode = $_GET['mode']; //view, add, edit
	$id = $_GET['id'];
	$store_number= $_GET['store_number'];

	if($_SESSION['level'] < 3) {
		if($id != '') {
			if($mode == "delete") {
				deleteSite($id, $db);
			}
		}
	} /*end session level > 3*/
?>
<div class="row">
		<aside class="">
			<form action="" method="GET" class="form-search">
				<input type="text" class="input-small" placeholder="Site Number" name="store_number" value="">
				<input type="submit" class="btn" value="Search">
			</form>
		</aside>
	<div class="span1">
		<?php

			$sql = "SELECT * FROM sites ORDER BY number ASC";

			//$sql .= ' ';
			$result = mysqli_query($db, $sql);
			if($_SESSION['level'] < 3) {
				echo '<p><a href="sites.php?mode=add">Add Site</a></p>';
			}
			while($row = mysqli_fetch_array($result)) {
				echo '<p><a href="sites.php?store_number='.$row['number'].'">'.$row['number'].'</a></p>';
			}
		?>
	</div> <!-- end span1 div -->
	<div class="span6">
		<?php

			if(isset($store_number) && $mode == 'edit') {	
				include('includes/sites/edit_site.php');
			}
			else if($mode == 'add') {
				include('includes/sites/add_site.php');
			}
			else { include('includes/sites/view_site.php'); }
		?>	
	<!-- span6 closed in includes -->
	</div> <!-- end span11 Div -->
</div>	<!-- end row div -->
<?php
	include('includes/footer.php');
	mysqli_close($db);
?>