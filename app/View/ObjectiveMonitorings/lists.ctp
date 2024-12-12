<div id="main">
<?php echo $this->Session->flash();?>	
	<div class="objectiveMonitorings ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Objective Monitorings','modelClass'=>'ObjectiveMonitoring','options'=>array("sr_no"=>"Sr No","analysis"=>"Analysis","improvements_required"=>"Improvements Required","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'objectiveMonitorings'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Objective Monitoring'), array('action' => 'add_ajax',
						'objective_id'=>$this->request->params['named']['objective_id'],
						'process_id'=>$this->request->params['named']['process_id'])); ?></li>
					<li><?php // echo $this->Html->link(__('Add Objective'), array('controller' => 'objectives', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Process'), array('controller' => 'processes', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="objectiveMonitorings_tab_ajax"></div>
</div>

<script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
	ui.jqXHR.error(function() {
	  ui.panel.html(
	    "Error Loading ... " +
	    "Please contact administrator." );
	});
      }
    });
  });
</script>

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","analysis"=>"Analysis","improvements_required"=>"Improvements Required","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","analysis"=>"Analysis","improvements_required"=>"Improvements Required","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
