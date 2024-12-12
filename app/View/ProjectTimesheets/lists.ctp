<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectTimesheets ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Timesheets','modelClass'=>'ProjectTimesheet','options'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'projectTimesheets'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Project Timesheet'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add User'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project'), array('controller' => 'projects', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project Activity'), array('controller' => 'project_activities', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="projectTimesheets_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>