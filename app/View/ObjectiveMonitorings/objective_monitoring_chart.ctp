
<?php if (($this->Session->read('User.is_mr') == true) ){ ?>

<?php
    echo $this->Html->css(array('chartist/chartist.min'));
    echo $this->Html->script(array('chartist/chartist.min'));
    echo $this->fetch('script');
    echo $this->fetch('css');
?>
<style>
	.performance{height: 310px;}
	.btn-margin{margin-top: 25px;}
</style>


<div class="row row-max">
  
  	<?php 
  		echo $this->Form->create('ObjectiveMonitoring',array('role'=>'form','class'=>'form'));
  		echo '<div class="col-md-3">'. $this->Form->input('objective_id',array('default'=>$selected_objective)).'</div>';
  		echo '<div class="col-md-3"><div id="process">'. $this->Form->input('process_id',array('default'=>$selected_process)).'</div></div>';
  		echo '<div class="col-md-3"><div id="user">'. $this->Form->input('user_id',array('options'=>$PublishedUserList)).'</div></div>';
  		echo '<div class="col-md-1">'. $this->Form->input('from_date',array('label'=>'From')).'</div>';
  		echo '<div class="col-md-1">'. $this->Form->input('to_date',array('label'=>'To')).'</div>';
  		echo '<div class="col-md-1">'. $this->Form->submit('Reload',array('div'=>false,'class'=>'btn btn-primary btn-success btn-margin','update'=>'#objectiveMonitorings_ajax','async' => 'false')).'</div>';
  		echo $this->Form->end(); 
  	?>
  </div>

  <div class="col-md-12">
	<h1><?php echo __('Objective Monitoring Chart'); ?></h1>
		<div id="objective_monitoring_tabs">
  			
  			<?php foreach ($final as $schedule=>$details) { 
  				$lables = $series = NULL;
  				?>
  				<h3><a href="#<?php echo $schedule; ?>">Scheduled <?php echo $schedule; ?></a> </h3>
    			<div id="<?php echo $schedule; ?>" style="padding:0 !important; border:0 !important">
      				<?php       	
      					foreach ($details as $date => $value) {
      						$lables .= "'". $date ."',";
      						$series .= $value .", ";
      					}

      				?>
      				<script type="text/javascript">
      				$('document').ready(function(){
      					new Chartist.Line('#performance_monitoring_<?php echo $schedule; ?>', {
	      				labels: [<?php echo $lables; ?>],
	      				series: [[<?php echo $series; ?>]]
						}, {
							high: 100,
		  					fullWidth: true,
		  					chartPadding: {right: 40},
		  					axisY: {onlyInteger: true,offset: 10},
		  					showArea: true
						});
						});
      				</script>
				<div class="ct-chart panel panel-body panel-default performance " id="performance_monitoring_<?php echo $schedule; ?>"></div>
		</div>
  <?php } ?>
  		</div>
    </div>
<?php } ?>
<script> 
$('#ObjectiveMonitoringObjectiveId').chosen();
$('#ObjectiveMonitoringProcessId').chosen();
$('#ObjectiveMonitoringUserId').chosen();
$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); 
$('#ObjectiveMonitoringObjectiveId').change(function(){  
  $('#process').load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_process_list/" + $('#ObjectiveMonitoringObjectiveId').val() );
});
$('#ObjectiveMonitoringProcessId').change(function(){  
  $('#user').load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_process_team/" + $('#ObjectiveMonitoringProcessId').val() );
});
</script>
<?php echo $this->Js->writeBuffer();?>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>    
