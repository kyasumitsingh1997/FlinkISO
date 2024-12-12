<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="otherMeasurableUnits ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Other Measurable Units','modelClass'=>'OtherMeasurableUnit','options'=>array("sr_no"=>"Sr No","unit_name"=>"Unit Name","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'otherMeasurableUnits'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Other Measurable Unit'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Project Process Plan'), array('controller' => 'project_process_plans', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project'), array('controller' => 'projects', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Milestone'), array('controller' => 'milestones', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="otherMeasurableUnits_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","unit_name"=>"Unit Name","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","unit_name"=>"Unit Name","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>