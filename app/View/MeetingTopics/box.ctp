
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
	<div class="meetingTopics ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Meeting Topics','modelClass'=>'MeetingTopic','options'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'meetingTopics'))); ?>
	
		
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

			<?php foreach ($meetingTopics as $meetingTopic): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $meetingTopic['MeetingTopic']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $meetingTopic['MeetingTopic']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $meetingTopic['MeetingTopic']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $meetingTopic['MeetingTopic']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $meetingTopic['MeetingTopic']['id']),array('class'=>''), __('Are you sure ?', $meetingTopic['MeetingTopic']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('meeting_id') ."</dt><dd>:". $this->Html->link($meetingTopic['Meeting']['title'], array('controller' => 'meetings', 'action' => 'view', $meetingTopic['Meeting']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('change_addition_deletion_request_id') ."</dt><dd>:". $this->Html->link($meetingTopic['ChangeAdditionDeletionRequest']['request_details'], array('controller' => 'change_addition_deletion_requests', 'action' => 'view', $meetingTopic['ChangeAdditionDeletionRequest']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('corrective_preventive_action_id') ."</dt><dd>:". $this->Html->link($meetingTopic['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $meetingTopic['CorrectivePreventiveAction']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('document_amendment_record_sheet_id') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['document_amendment_record_sheet_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('customer_complaint_id') ."</dt><dd>:". $this->Html->link($meetingTopic['CustomerComplaint']['name'], array('controller' => 'customer_complaints', 'action' => 'view', $meetingTopic['CustomerComplaint']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('customer_feedback_id') ."</dt><dd>:". $this->Html->link($meetingTopic['CustomerFeedback']['id'], array('controller' => 'customer_feedbacks', 'action' => 'view', $meetingTopic['CustomerFeedback']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('supplier_evaluation_reevaluation_id') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['supplier_evaluation_reevaluation_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('summery_of_supplier_evaluation_id') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['summery_of_supplier_evaluation_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('internal_audit_plan_id') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['internal_audit_plan_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['title']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('current_status') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['current_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('action_plan') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['action_plan']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($meetingTopic['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $meetingTopic['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('target_date') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['target_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('notes') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['notes']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($meetingTopic['MeetingTopic']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['record_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>:". $this->Html->link($meetingTopic['StatusUserId']['name'], array('controller' => 'users', 'action' => 'view', $meetingTopic['StatusUserId']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($meetingTopic['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $meetingTopic['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($meetingTopic['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $meetingTopic['PreparedBy']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('division_id') ."</dt><dd>: ". h($meetingTopic['MeetingTopic']['division_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($meetingTopic['Company']['name'], array('controller' => 'companies', 'action' => 'view', $meetingTopic['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$meetingTopic['MeetingTopic']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>