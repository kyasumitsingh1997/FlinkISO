<?php echo '<div id="user">'. $this->Form->input('user_id',
	array(
		'name'=>'data[ObjectiveMonitoring][process_id]',
		'id'=>'ObjectiveMonitoringUserId',
		'options'=>array($team)
		)).'</div>'; ?>
<script type="text/javascript">
	$('#ObjectiveMonitoringUserId').chosen();
</script>