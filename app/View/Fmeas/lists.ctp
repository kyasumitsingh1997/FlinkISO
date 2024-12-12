<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fmeas ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'FMEA','modelClass'=>'Fmea','options'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'fmeas'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Fmea'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Process'), array('controller' => 'processes', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Product'), array('controller' => 'products', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Fmea Severity Type'), array('controller' => 'fmea_severity_types', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Fmea Occurence'), array('controller' => 'fmea_occurences', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Fmea Detection'), array('controller' => 'fmea_detections', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Fmea Action'), array('controller' => 'fmea_actions', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="fmeas_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>