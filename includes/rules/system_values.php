<div class="span6">
	<table class="collection noBorder">
		<tr>
			<th>System Configuration Options</th>
		</tr>
		
		<tr>
			<form method="post" class="form-inline" action="<?= $link ?>">
			<td>
				<a href="<?= $link.'&option=refresh' ?>"><i>Problem Refresh Time:</i> <?= $refresh ?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?= $link.'&option=trans_rate' ?>"><i>Transmission Rate:</i> <?= $trans_rate ?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?= $link.'&option=max_price_change' ?>"><i>Max Price Change:</i> <?= $textRate ?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?= $link.'&option=forward_cns_time' ?>"><i>Forward CNS Time:</i> <?= $forward_cns_time ?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?= $link.'&option=back_cns_time' ?>"><i>Backward CNS Time:</i> <?= $backward_cns_time ?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?= $link.'&option=uac' ?>"><i>UAC Timeframe:</i> <?= $uac_start_time.' to '.$uac_end_time ?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?= $link.'&option=emails' ?>"><i>Alert Email List</i></a>
			</td>
		</tr>
		</form>
	</table>
</div>
