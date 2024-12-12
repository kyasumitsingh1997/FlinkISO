<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="meetingTopics ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Meeting Topics','modelClass'=>'MeetingTopic','options'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'meetingTopics'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Meeting Topic'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Approved By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Meeting'), array('controller' => 'meetings', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Change Addition Deletion Request'), array('controller' => 'change_addition_deletion_requests', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Corrective Preventive Action'), array('controller' => 'corrective_preventive_actions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Customer Complaint'), array('controller' => 'customer_complaints', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Customer Feedback'), array('controller' => 'customer_feedbacks', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Status User Id'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="meetingTopics_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>