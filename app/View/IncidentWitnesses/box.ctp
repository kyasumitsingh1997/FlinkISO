
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
	<div class="incidentWitnesses ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Witnesses','modelClass'=>'IncidentWitness','options'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'incidentWitnesses'))); ?>
	
		
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

			<?php foreach ($incidentWitnesses as $incidentWitness): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $incidentWitness['IncidentWitness']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $incidentWitness['IncidentWitness']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $incidentWitness['IncidentWitness']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $incidentWitness['IncidentWitness']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $incidentWitness['IncidentWitness']['id']),array('class'=>''), __('Are you sure ?', $incidentWitness['IncidentWitness']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('incident_id') ."</dt><dd>:". $this->Html->link($incidentWitness['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentWitness['Incident']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('person_type') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['person_type']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($incidentWitness['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentWitness['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['name']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('address') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['address']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('phone') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['phone']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('department_id') ."</dt><dd>:". $this->Html->link($incidentWitness['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentWitness['Department']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('designation_id') ."</dt><dd>:". $this->Html->link($incidentWitness['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentWitness['Designation']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('age') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['age']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('gender') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['gender']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('investigation_interview_taken_by') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['investigation_interview_taken_by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('date_of_interview') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['date_of_interview']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('investigation_interview_findings') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['investigation_interview_findings']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($incidentWitness['IncidentWitness']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($incidentWitness['IncidentWitness']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($incidentWitness['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentWitness['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($incidentWitness['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentWitness['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($incidentWitness['Company']['name'], array('controller' => 'companies', 'action' => 'view', $incidentWitness['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$incidentWitness['IncidentWitness']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>