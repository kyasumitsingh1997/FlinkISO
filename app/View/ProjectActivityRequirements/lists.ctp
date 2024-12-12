<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectActivityRequirements ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Activity Requirements','modelClass'=>'ProjectActivityRequirement','options'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'projectActivityRequirements'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Project Activity Requirement'), array('action' => 'add_ajax','project_activity_id'=>$this->request->params['named']['project_activity_id'])); ?></li>
					<li><?php // echo $this->Html->link(__('Add Project'), array('controller' => 'projects', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Milestone'), array('controller' => 'milestones', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project Activity'), array('controller' => 'project_activities', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Branch'), array('controller' => 'branches', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add User Session'), array('controller' => 'user_sessions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="projectActivityRequirements_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>