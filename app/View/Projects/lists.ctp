<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projects ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Projects','modelClass'=>'Project','options'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'projects'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Project'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Branch'), array('controller' => 'branches', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add User Session'), array('controller' => 'user_sessions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Milestone'), array('controller' => 'milestones', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project Activity'), array('controller' => 'project_activities', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project Activity Requirement'), array('controller' => 'project_activity_requirements', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="projects_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>