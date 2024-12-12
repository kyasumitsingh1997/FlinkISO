<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectFiles ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Files','modelClass'=>'ProjectFile','options'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'projectFiles'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Project File'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Project'), array('controller' => 'projects', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Milestone'), array('controller' => 'milestones', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="projectFiles_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>