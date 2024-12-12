<?php 

// Configure::write('debug',1);
// debug($edata); 

$units_completed = 0;
?>

<h3>Employee File</h3>
<table class="table table-responsive table-bordered">
		<tr>
			<th>To</th>
			<th>Process/Tasks</th>
			<th>Assigned Time</th>
			<th>Current Status</th>
			<th>Units Completed</th>
			<th>Start Time</th>
			<th>End time</th>
			<th>Estimated Time</th>
			<th>Hold start time</th>
			<th>Hold end time</th>
			<th>Reason for hold</th>
			<th>Comments</th>
			<th>Start Delay</th>
			<th>Total Time</th>
			<th>Hold Time</th>
		</tr>
		<?php foreach ($edata as $file_name => $data) { ?>
			<tr>
				<th colspan="11"><?php echo $file_name;?></th>
			</tr>

			<?php foreach($data as $pro){
				if($pro['Employee']['id'] == $this->request->params['pass'][0]){
					$units_completed = $units_completed + $pro['FileProcess']['units_completed'];	
				}
				
		?>
			<tr>
				<td><?php echo $pro['Employee']['name']?></td>
				<td>

					<!-- <?php echo $pro['FileProcess']['id']?><br /> -->
					<?php echo $projectProcesses[$pro['FileProcess']['project_process_plan_id']]?></td>
				
				<td><?php echo $pro['FileProcess']['created']?></td>
				<td><?php echo $currentStatuses[$pro['FileProcess']['current_status']]?></td>
				<td><?php echo $pro['FileProcess']['units_completed']?></td>
				<td><?php echo $pro['FileProcess']['start_time']?></td>
				<td><?php echo $pro['FileProcess']['end_time']?></td>
				<td><?php echo $pro['FileProcess']['estimated_time']?></td>
				<td><?php echo $pro['FileProcess']['hold_start_time']?></td>
				<td><?php echo $pro['FileProcess']['hold_end_time']?></td>
				<td></td>
				<td><?php echo $pro['FileProcess']['comments']?></td>
				<td><?php echo $pro['FileProcess']['start_delay']?></td>
				<td><?php echo $pro['FileProcess']['sum_total']?></td>
				<td><?php echo $pro['FileProcess']['sum_hold']?></td>
			</tr>
		<?php }} ?>
		<tr>
			<td colspan="4"><h3>Units Comepleted : </h3></td>
			<td colspan="11"><h3><?php echo $units_completed;?></h3></td>
		</tr>
	</table>
