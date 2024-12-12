<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="processes ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Processes','modelClass'=>'Process','options'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","process_requirments"=>"Process Requirments","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'processes'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Process'), array('action' => 'add_ajax',$this->request->params['pass'][0])); ?></li>
					<li><?php // echo $this->Html->link(__('Add Objective'), array('controller' => 'objectives', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Input Process'), array('controller' => 'input_processes', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Output Process'), array('controller' => 'output_processes', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Schedule'), array('controller' => 'schedules', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Objective Monitoring'), array('controller' => 'objective_monitorings', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Process Team'), array('controller' => 'process_teams', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="processes_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","process_requirments"=>"Process Requirments","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","process_requirments"=>"Process Requirments","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>