
<script>
	function getVals(){
		
	var checkedValue = null;
	$("#recs_selected").val(null);
	var inputElements = document.getElementsByTagName('input');
	
	for(var i=0; inputElements[i]; ++i){
		
	      if(inputElements[i].className==="rec_ids" && 
		 inputElements[i].checked){
		   $("#recs_selected").val($("#recs_selected").val() + '+' + inputElements[i].value);
		   
	      }
	}
	}
</script><?php echo $this->Session->flash();?>	
	<div class="incidentInvestigations ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Investigations','modelClass'=>'IncidentInvestigation','options'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'incidentInvestigations'))); ?>
	
		
<script type="text/javascript">
$(document).ready(function(){
$('dl dt a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>
		<div class="container row  row table-responsive">

			<?php foreach ($incidentInvestigations as $incidentInvestigation): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $incidentInvestigation['IncidentInvestigation']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $incidentInvestigation['IncidentInvestigation']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $incidentInvestigation['IncidentInvestigation']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $incidentInvestigation['IncidentInvestigation']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $incidentInvestigation['IncidentInvestigation']['id']),array('class'=>''), __('Are you sure ?', $incidentInvestigation['IncidentInvestigation']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('incident_id') ."</dt><dd>:". $this->Html->link($incidentInvestigation['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentInvestigation['Incident']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('incident_affected_personal_id') ."</dt><dd>:". $this->Html->link($incidentInvestigation['IncidentAffectedPersonal']['name'], array('controller' => 'incident_affected_personals', 'action' => 'view', $incidentInvestigation['IncidentAffectedPersonal']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('incident_witness_id') ."</dt><dd>:". $this->Html->link($incidentInvestigation['IncidentWitness']['name'], array('controller' => 'incident_witnesses', 'action' => 'view', $incidentInvestigation['IncidentWitness']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('reference_number') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['reference_number']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('incident_investigator_id') ."</dt><dd>:". $this->Html->link($incidentInvestigation['IncidentInvestigator']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentInvestigation['IncidentInvestigator']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('investigation_date_from') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['investigation_date_from']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('investigation_date_to') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['investigation_date_to']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['title']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('control_measures_currently_in_place') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['control_measures_currently_in_place']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('summery_of_findings') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['summery_of_findings']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('reason_for_incidence') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['reason_for_incidence']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('immediate_action_taken') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['immediate_action_taken']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('risk_assessment') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['risk_assessment']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('investigation_reviewd_by') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['investigation_reviewd_by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('action_taken') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['action_taken']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('corrective_preventive_action_id') ."</dt><dd>:". $this->Html->link($incidentInvestigation['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $incidentInvestigation['CorrectivePreventiveAction']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($incidentInvestigation['IncidentInvestigation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($incidentInvestigation['IncidentInvestigation']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($incidentInvestigation['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentInvestigation['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($incidentInvestigation['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentInvestigation['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($incidentInvestigation['Company']['name'], array('controller' => 'companies', 'action' => 'view', $incidentInvestigation['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$incidentInvestigation['IncidentInvestigation']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
		</div>
		
		
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#main',
			'evalScripts' => true,
			'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
			));
			
			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
			?>			</p>
			<ul class="pagination">
			<?php
		echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
		echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
		echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
	?>
			</ul>
		</div>
	</div>
	</div>

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>