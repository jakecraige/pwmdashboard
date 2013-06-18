<?php if(!$pageUpdated): ?>
<form method="post">
	<table class="form-inline collection noHover">

		<?php if($_GET['option'] == 'refresh'): ?>
			<tr>
				<td>
					Problem Refresh Time: 
					<select name="refresh" class="input-medium">
						<option value="5" <?php if($refresh == 5 ) echo 'selected="selected"'; ?>>
							5 Minutes</option>
						<option value="15" <?php if($refresh == 15 ) echo 'selected="selected"'; ?>>
							15 Minutes</option>
						<option value="30" <?php if($refresh == 30 ) echo 'selected="selected"'; ?>>
							30 Minutes</option>
						<option value="60" <?php if($refresh == 60 ) echo 'selected="selected"'; ?>>
							1 Hour</option>
						<option value="120" <?php if($refresh == 120 ) echo 'selected="selected"'; ?>>
							2 Hours</option>
						<option value="180" <?php if($refresh == 180 ) echo 'selected="selected"'; ?>>
							3 Hours</option>
						<option value="240" <?php if($refresh == 240 ) echo 'selected="selected"'; ?>>
							4 Hours</option>
						<option value="300" <?php if($refresh == 300 ) echo 'selected="selected"'; ?>>
							5 Hours</option>
						<option value="0" <?php if($refresh == 0 ) echo 'selected="selected"'; ?>>
							Never</option>
					</select>
					<input type="submit" class="btn btn-primary" name="edit_refresh" value="Save">
				</td>
			</tr>
		<?php endif; ?>

		<?php if($_GET['option'] == 'trans_rate'): ?>
			<tr>
			<td>
				Transmission Rate: 
				<select name="trans_rate" class="input-medium">
					<option value="5" <?php if($trans_rate == 5 ) echo 'selected="selected"'; ?>>
						5 Minutes</option>
					<option value="15" <?php if($trans_rate == 15 ) echo 'selected="selected"'; ?>>
						15 Minutes</option>
					<option value="30" <?php if($trans_rate == 30 ) echo 'selected="selected"'; ?>>
						30 Minutes</option>
					<option value="60" <?php if($trans_rate == 60 ) echo 'selected="selected"'; ?>>
						1 Hour</option>
					<option value="120" <?php if($trans_rate == 120 ) echo 'selected="selected"'; ?>>
						2 Hours</option>
					<option value="180" <?php if($trans_rate == 180 ) echo 'selected="selected"'; ?>>
						3 Hours</option>
					<option value="240" <?php if($trans_rate == 240 ) echo 'selected="selected"'; ?>>
						4 Hours</option>
					<option value="300" <?php if($trans_rate == 300 ) echo 'selected="selected"'; ?>>
						5 Hours</option>
				</select>
				<input type="submit" name="edit_trans_rate" class="btn btn-primary" value="Save">
			</td>
			</tr>
		<?php endif; ?>

		<?php if($_GET['option'] == 'max_price_change'): ?>
			<tr>
				<td>
					Max Price Change: 
					<select name="max_price_change" class="input-small">
						<option value="0.1" <?php if($max_price_change == 0.1 ) echo 'selected="selected"'; ?>>
							$0.10</option>
						<option value="0.15" <?php if($max_price_change == 0.15 ) echo 'selected="selected"'; ?>>
							$0.15</option>
						<option value="0.2" <?php if($max_price_change == 0.2 ) echo 'selected="selected"'; ?>>
							$0.20</option>
						<option value="0.25" <?php if($max_price_change == 0.25 ) echo 'selected="selected"'; ?>>
							$0.25</option>
						<option value="0.3" <?php if($max_price_change == 0.3 ) echo 'selected="selected"'; ?>>
							$0.30</option>
						<option value="0.4" <?php if($max_price_change == 0.4 ) echo 'selected="selected"'; ?>>
							$0.40</option>
						<option value="0.5" <?php if($max_price_change == 0.5 ) echo 'selected="selected"'; ?>>
							$0.50</option>
					</select>
					<input type="submit" name="edit_max_price_change" class="btn btn-primary" value="Save">
				</td>
			</tr>
		<?php endif; ?>

		<?php if($_GET['option'] == 'forward_cns_time'): ?>	
			<tr>
				<td>
					<i>Forward CNS Time:</i> <input type="text" class="input-small" name="forward_cns_time" size="3" maxlength="3" value="<?= $forward_cns_time ?>">
					<input type="submit" class="btn btn-primary" name="edit_forward_cns_time" value="Save">						
				</td>
			</tr>
		<?php endif; ?>

		<?php if($_GET['option'] == 'back_cns_time'): ?>		
			<tr>
				<td>
					<i>Backward CNS Time:</i> <input type="text" class="input-small" name="backward_cns_time" size="3" maxlength="3" value="<?= $backward_cns_time ?>">
					<input type="submit" class="btn btn-primary" name="edit_back_cns_time" value="Save">						
				</td>
			</tr>
		<?php endif; ?>

		<?php if($_GET['option'] == 'emails'): ?>
			<tr>
				<td>Alert Email List: <input type="submit" name="edit_emails" class="btn btn-primary" value="Save"></td>
			</tr>
			<tr><td>
				<textarea name="email_list" rows="5"><?php 
						$email_list = explode(' ', $email_list);
						foreach ($email_list as $email) {
							if(!empty($email)) {
								echo $email."\n";
							}
						}?>
				</textarea>
			</td>
		<?php endif; ?>	

		<?php if($_GET['option'] == 'uac'): ?>
			<tr>
				<td>
					<i>Unauthorized Price Change Timeframe: </i>
					 <input type="text" class="input-mini" name="uac_start_time" placeholder="18:00" value="<?= $uac_start_time ?>">
					  to 
					 <input type="text" class="input-mini" name="uac_end_time" placeholder = "06:00" value="<?= $uac_end_time ?>">
					 <input type="submit" name="edit_uac_times" class="btn btn-primary" value="Save">
				</td>
			</tr>
		<?php endif; ?>

	</table>
</form>
<?php endif; ?>