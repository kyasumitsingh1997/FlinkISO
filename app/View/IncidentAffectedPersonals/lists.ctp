<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidentAffectedPersonals ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Affected Personals','modelClass'=>'IncidentAffectedPersonal','options'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'incidentAffectedPersonals'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Incident Affected Personal'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Incident'), array('controller' => 'incidents', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Department'), array('controller' => 'departments', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Designation'), array('controller' => 'designations', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Incident Investigator'), array('controller' => 'incident_investigators', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Incident Investigation'), array('controller' => 'incident_investigations', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="incidentAffectedPersonals_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>