
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
	<div class="capaRootCauseAnalysis ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Capa Root Cause Analysis','modelClass'=>'CapaRootCauseAnalysi','options'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'pluralVar'=>'capaRootCauseAnalysis'))); ?>
	
		
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

			<?php foreach ($capaRootCauseAnalysis as $capaRootCauseAnalysi): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id']),array('class'=>''), __('Are you sure ?', $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('corrective_preventive_action_id') ."</dt><dd>:". $this->Html->link($capaRootCauseAnalysi['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRootCauseAnalysi['CorrectivePreventiveAction']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($capaRootCauseAnalysi['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('root_cause_details') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['root_cause_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('determined_by') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['determined_by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('determined_on_date') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['determined_on_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('root_cause_remarks') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['root_cause_remarks']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('proposed_action') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['proposed_action']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('action_assigned_to') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_assigned_to']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('action_completed_on_date') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completed_on_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('action_completion_remarks') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completion_remarks']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('effectiveness') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['effectiveness']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('closure_remarks') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['closure_remarks']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('current_status') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['current_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($capaRootCauseAnalysi['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($capaRootCauseAnalysi['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['PreparedBy']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($capaRootCauseAnalysi['CapaRootCauseAnalysi']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($capaRootCauseAnalysi['Company']['name'], array('controller' => 'companies', 'action' => 'view', $capaRootCauseAnalysi['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>