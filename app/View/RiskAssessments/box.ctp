
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
	<div class="riskAssessments ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Risk Assessments','modelClass'=>'RiskAssessment','options'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'riskAssessments'))); ?>
	
		
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

			<?php foreach ($riskAssessments as $riskAssessment): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $riskAssessment['RiskAssessment']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $riskAssessment['RiskAssessment']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $riskAssessment['RiskAssessment']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $riskAssessment['RiskAssessment']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $riskAssessment['RiskAssessment']['id']),array('class'=>''), __('Are you sure ?', $riskAssessment['RiskAssessment']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['title']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('process_id') ."</dt><dd>:". $this->Html->link($riskAssessment['Process']['title'], array('controller' => 'processes', 'action' => 'view', $riskAssessment['Process']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('branch_id') ."</dt><dd>:". $this->Html->link($riskAssessment['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $riskAssessment['Branch']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('task') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['task']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('ra_date') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['ra_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('ra_expert_1') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['ra_expert_1']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('ra_expert_2') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['ra_expert_2']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('management') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['management']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('technical_expert') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['technical_expert']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('risk_control_exprt') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['risk_control_exprt']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('reference_number') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['reference_number']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('hazard_type_id') ."</dt><dd>:". $this->Html->link($riskAssessment['HazardType']['name'], array('controller' => 'hazard_types', 'action' => 'view', $riskAssessment['HazardType']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('hazard_source_id') ."</dt><dd>:". $this->Html->link($riskAssessment['HazardSource']['name'], array('controller' => 'hazard_sources', 'action' => 'view', $riskAssessment['HazardSource']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('what_could_happan') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['what_could_happan']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('accident_type_id') ."</dt><dd>:". $this->Html->link($riskAssessment['AccidentType']['name'], array('controller' => 'accident_types', 'action' => 'view', $riskAssessment['AccidentType']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('severiry_type_id') ."</dt><dd>:". $this->Html->link($riskAssessment['SeveriryType']['name'], array('controller' => 'severiry_types', 'action' => 'view', $riskAssessment['SeveriryType']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('existing_controls') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['existing_controls']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('likelihood') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['likelihood']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('risk_rating_id') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['risk_rating_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('additional_control_needed') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['additional_control_needed']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('person_responsible') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['person_responsible']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('target_date') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['target_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('completions_date') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['completions_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('process_notes') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['process_notes']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('task_notes') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['task_notes']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($riskAssessment['RiskAssessment']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($riskAssessment['RiskAssessment']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($riskAssessment['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $riskAssessment['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($riskAssessment['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $riskAssessment['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($riskAssessment['Company']['name'], array('controller' => 'companies', 'action' => 'view', $riskAssessment['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$riskAssessment['RiskAssessment']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>