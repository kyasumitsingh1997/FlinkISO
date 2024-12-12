<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidentInvestigations ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Investigations','modelClass'=>'IncidentInvestigation','options'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'incidentInvestigations'))); ?>
		<div class="nav">
                   
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Incident Investigation'), array('action' => 'add_ajax', $this->request->params['pass'][0])); ?></li>
					<li><?php // echo $this->Html->link(__('Add Incident'), array('controller' => 'incidents', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Incident Affected Personal'), array('controller' => 'incident_affected_personals', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Incident Witness'), array('controller' => 'incident_witnesses', 'action' => 'add_ajax')); ?> </li>
					<li><?php  echo $this->Html->link(__('Add Incident Investigator'), array('controller' => 'incident_investigators', 'action' => 'add_ajax', 
					$this->request->params['controller'])); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Corrective Preventive Action'), array('controller' => 'corrective_preventive_actions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="incidentInvestigations_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
