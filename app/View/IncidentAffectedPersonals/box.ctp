
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
	<div class="incidentAffectedPersonals ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Affected Personals','modelClass'=>'IncidentAffectedPersonal','options'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'incidentAffectedPersonals'))); ?>
	
		
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

			<?php foreach ($incidentAffectedPersonals as $incidentAffectedPersonal): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $incidentAffectedPersonal['IncidentAffectedPersonal']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $incidentAffectedPersonal['IncidentAffectedPersonal']['id']),array('class'=>''), __('Are you sure ?', $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('incident_id') ."</dt><dd>:". $this->Html->link($incidentAffectedPersonal['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentAffectedPersonal['Incident']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('person_type') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['person_type']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($incidentAffectedPersonal['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentAffectedPersonal['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['name']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('address') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['address']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('phone') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['phone']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('department_id') ."</dt><dd>:". $this->Html->link($incidentAffectedPersonal['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentAffectedPersonal['Department']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('designation_id') ."</dt><dd>:". $this->Html->link($incidentAffectedPersonal['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentAffectedPersonal['Designation']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('age') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['age']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('gender') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['gender']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('first_aid_provided') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_provided']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('first_aid_details') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('first_aid_provided_by') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_provided_by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('follow_up_action_taken') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['follow_up_action_taken']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('other') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['other']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('illhealth_reported') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['illhealth_reported']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('normal_work_affected') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['normal_work_affected']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('number_of_work_affected_dates') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['number_of_work_affected_dates']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('incident_investigator_id') ."</dt><dd>:". $this->Html->link($incidentAffectedPersonal['IncidentInvestigator']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentAffectedPersonal['IncidentInvestigator']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('date_of_interview') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['date_of_interview']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('investigation_interview_findings') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['investigation_interview_findings']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($incidentAffectedPersonal['IncidentAffectedPersonal']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($incidentAffectedPersonal['IncidentAffectedPersonal']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($incidentAffectedPersonal['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentAffectedPersonal['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($incidentAffectedPersonal['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentAffectedPersonal['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($incidentAffectedPersonal['Company']['name'], array('controller' => 'companies', 'action' => 'view', $incidentAffectedPersonal['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$incidentAffectedPersonal['IncidentAffectedPersonal']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>