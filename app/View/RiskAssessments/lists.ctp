<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="riskAssessments ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Risk Assessments','modelClass'=>'RiskAssessment','options'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'riskAssessments'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Risk Assessment'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Process'), array('controller' => 'processes', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Branch'), array('controller' => 'branches', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Hazard Type'), array('controller' => 'hazard_types', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Hazard Source'), array('controller' => 'hazard_sources', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Accident Type'), array('controller' => 'accident_types', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Severiry Type'), array('controller' => 'severiry_types', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="riskAssessments_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>