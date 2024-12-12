<div class="employee_reports">
	<div class="employee_reports_form">
		<?php echo $this->Form->create('Project',array('action'=>'employee_reports'),array('class'=>'form-conttrol'))?>
		<div class="row">
			<div class="col-md-4"><?php echo $this->Form->input('employee_id')?></div>
			<div class="col-md-4"><?php echo $this->Form->input('date_range')?></div>
			<div class="col-md-4"><?php echo $this->Form->submit('Search',array('class'=>'btn btn-sm btn-success'))?></div>
		</div>
		<?php echo $this->Form->end();?>
	</div>
	<script type="text/javascript"></script>
</div>
<script type="text/javascript">
	$('.chosen-select').chosen();
	$('#ProjectDateRange').daterangepicker();
</script>

<table class="table table-responsive table-bordered">
	<tr>
		<th>Project</th>
		<th>Activity</th>
		<th>Days worked</th>
		<th>Hrs Worked</th>
		<th>Output</th>
		<th>Planned Metric</th>
		<th>Achieved Metric</th>
	</tr>
	<?php foreach ($res as $project_id => $datas) { ?>
		<?php foreach ($datas as $data) { ?>
			<tr>
				<td><?php echo $data['Project']['title']?></td>
				<td><?php echo $data['ProjectProcessPlan']['process']?></td>
				<td><?php echo $data['FileProcess']['total_time'] / 86400 ?></td>
				<td><?php echo $data['FileProcess']['total_time'] / 86400 * 24 ?></td>
				<td><?php echo $data['FileProcess']['units_completed']?></td>
				<td>--</td>
				<td><?php echo $data['FileProcess']['overall_metrics']?></td>
			</tr>
		<?php } ?>		
	<?php } ?>
</table>