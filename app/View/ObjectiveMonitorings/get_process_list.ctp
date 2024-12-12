<?php echo '<div id="process">'. $this->Form->input('process_id',
	array(
		'name'=>'data[ObjectiveMonitoring][process_id]',
		'id'=>'ObjectiveMonitoringProcessId',
		'options'=>array($processes)
		)).'</div>'; ?>
<script type="text/javascript">
	$('#ObjectiveMonitoringProcessId').chosen();
	$('#ObjectiveMonitoringProcessId').change(function(){  
  $('#user').load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_process_team/" + $('#ObjectiveMonitoringProcessId').val() );
});
</script>