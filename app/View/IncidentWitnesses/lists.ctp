<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidentWitnesses ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Witnesses','modelClass'=>'IncidentWitness','options'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'incidentWitnesses'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Incident Witness'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Incident'), array('controller' => 'incidents', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Department'), array('controller' => 'departments', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Designation'), array('controller' => 'designations', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Incident Investigation'), array('controller' => 'incident_investigations', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="incidentWitnesses_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>