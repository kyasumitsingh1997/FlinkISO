<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="milestones ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Milestones','modelClass'=>'Milestone','options'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","challenges"=>"Challenges","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'milestones'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Milestone'), array('action' => 'add_ajax','project_id'=>$this->request->params['named']['project_id'])); ?></li>
					<li><?php echo $this->Html->link(__('Projects'),'#' ,array('escape'=>true,'id'=>'project')); ?></li>
					<!-- <li><?php // echo $this->Html->link(__('Projects'), array('controller' => 'projects', 'action' => 'lists'),array('escape'=>true)); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Branch'), array('controller' => 'branches', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add User Session'), array('controller' => 'user_sessions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project Activity'), array('controller' => 'project_activities', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project Activity Requirement'), array('controller' => 'project_activity_requirements', 'action' => 'add_ajax')); ?> </li> -->
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>				
			</div>
		</div>
	<div id="milestones_tab_ajax"></div>
</div>

<script>
  $(function() {
  	$("#project").on('click',function(){
  		$("#main").load('<?php echo Router::url('/', true); ?>/projects');
  		return false;
  	});
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","challenges"=>"Challenges","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","challenges"=>"Challenges","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>