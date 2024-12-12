<table class="table table-responsive table-striped">
	<thead>
		<tr>
			<th>Employee</th>
			<th>Start Time</th>
			<th>End Time</th>
			<th>Hold Start Time</th>
			<th>Hold End Time</th>
			<th>Hold Time</th>
			<th>Actual Time</th>
			<th>Units Completed</th>
			<th>Total Completed Units</th>
			<!-- <th>total_hours</th>			 -->
		</tr>
	</thead>
	<?php foreach($fileProcesses as $fileProcess){ ?>
		<tr>
			<td><?php echo $fileProcess['Employee']['name']?></td>
			<td><?php echo $fileProcess['FileProcess']['start_time']?></td>
			<td><?php echo $fileProcess['FileProcess']['end_time']?></td>
			<td><?php echo $fileProcess['FileProcess']['hold_start_time']?></td>
			<td><?php echo $fileProcess['FileProcess']['hold_end_time']?></td>
			<td><?php echo $fileProcess['FileProcess']['hold_time']?></td>
			<td><?php echo $fileProcess['FileProcess']['actual_time']?></td>
			<td><?php echo $fileProcess['FileProcess']['units_completed']?></td>
			<td><?php echo $fileProcess['FileProcess']['total_completed_units']?></td>
			<!-- <td><?php echo $fileProcess['FileProcess']['total_hours']?></td> -->
		</tr>
	<?php } ?>
</table>
