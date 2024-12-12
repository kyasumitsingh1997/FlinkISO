<div class="expected_hours" id="expected_hours">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-responsive table-bordered table-condenced tablesorter">
				<tr>
					<th>File Name</th>
					<th>Unit</th>
					<th>File Category</th>
					<th>City</th>
					<th>Block</th>
					<th>Priority</th>
					<th>Current Status</th>
					<th>Total Time</th>
				</tr>
				<?php foreach($files as $file){ 


				?>
					<tr>
						<td><?php echo $this->html->link($file['ProjectFile']['name'],array('controller'=>'project_files','action'=>'view',$file['ProjectFile']['id']),array('target'=>'_blank'));?></td>
						<td><?php echo $file['ProjectFile']['unit'];?></td>
						<td><?php echo $file['ProjectFile']['file_category_id'];?></td>
						<td><?php echo $file['ProjectFile']['city'];?></td>
						<td><?php echo $file['ProjectFile']['block'];?></td>
						<td><?php echo $file['ProjectFile']['priority'];?></td>
						<td><?php echo $file['ProjectFile']['current_status'];?></td>
						<td><?php echo $file['ProjectFile']['total_time'];?></td>
						<?php $tt = $tt + $file['ProjectFile']['total_time'];?>
					</tr>
				<?php } ?>
				<tr>
					<th colspan="7">Total</th>
					<th><?php echo $tt;?></th>
				</tr>
			</table>
		</div>
	</div>
</div>