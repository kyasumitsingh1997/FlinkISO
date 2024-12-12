<div id="meetingTopics_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="meetingTopics form col-md-8">
<h4><?php echo __('View Meeting Topic'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Meeting'); ?></td>
		<td>
			<?php echo $this->Html->link($meetingTopic['Meeting']['title'], array('controller' => 'meetings', 'action' => 'view', $meetingTopic['Meeting']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Change Addition Deletion Request'); ?></td>
		<td>
			<?php echo $this->Html->link($meetingTopic['ChangeAdditionDeletionRequest']['request_details'], array('controller' => 'change_addition_deletion_requests', 'action' => 'view', $meetingTopic['ChangeAdditionDeletionRequest']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Corrective Preventive Action'); ?></td>
		<td>
			<?php echo $this->Html->link($meetingTopic['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $meetingTopic['CorrectivePreventiveAction']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Document Amendment Record Sheet Id'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['document_amendment_record_sheet_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Customer Complaint'); ?></td>
		<td>
			<?php echo $this->Html->link($meetingTopic['CustomerComplaint']['name'], array('controller' => 'customer_complaints', 'action' => 'view', $meetingTopic['CustomerComplaint']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Customer Feedback'); ?></td>
		<td>
			<?php echo $this->Html->link($meetingTopic['CustomerFeedback']['id'], array('controller' => 'customer_feedbacks', 'action' => 'view', $meetingTopic['CustomerFeedback']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Supplier Evaluation Reevaluation Id'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['supplier_evaluation_reevaluation_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Summery Of Supplier Evaluation Id'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['summery_of_supplier_evaluation_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Internal Audit Plan Id'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['internal_audit_plan_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['current_status']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Action Plan'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['action_plan']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($meetingTopic['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $meetingTopic['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Target Date'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['target_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Notes'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['notes']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division Id'); ?></td>
		<td>
			<?php echo h($meetingTopic['MeetingTopic']['division_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($meetingTopic['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($meetingTopic['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($meetingTopic['MeetingTopic']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($meetingTopic['MeetingTopic']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $meetingTopic['MeetingTopic']['created_by'], 'recordId' => $meetingTopic['MeetingTopic']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#meetingTopics_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$meetingTopic['MeetingTopic']['id'] ,'ajax'),array('async' => true, 'update' => '#meetingTopics_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
