<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="autoApprovalSteps ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Auto Approval Steps','modelClass'=>'AutoApprovalStep','options'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","allow_approval"=>"Allow Approval","show_details"=>"Show Details","details"=>"Details","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'autoApprovalSteps'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Auto Approval Step'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Auto Approval'), array('controller' => 'auto_approvals', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add User'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="autoApprovalSteps_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","allow_approval"=>"Allow Approval","show_details"=>"Show Details","details"=>"Details","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","allow_approval"=>"Allow Approval","show_details"=>"Show Details","details"=>"Details","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
