<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="meetingTopics ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Meeting Topics','modelClass'=>'MeetingTopic','options'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes"),'pluralVar'=>'meetingTopics'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('meeting_id'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('change_addition_deletion_request_id'); ?></th>
				<th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
				<th><?php echo $this->Paginator->sort('document_amendment_record_sheet_id'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_complaint_id'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_feedback_id'); ?></th>
				<th><?php echo $this->Paginator->sort('supplier_evaluation_reevaluation_id'); ?></th>
				<th><?php echo $this->Paginator->sort('summery_of_supplier_evaluation_id'); ?></th>
				<th><?php echo $this->Paginator->sort('internal_audit_plan_id'); ?></th> -->
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('action_plan'); ?></th> -->
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('target_date'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('notes'); ?></th> -->
				<!-- <th><?php echo $this->Paginator->sort('division_id'); ?></th> -->
				<!-- <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		 -->
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($meetingTopics){ ?>
<?php foreach ($meetingTopics as $meetingTopic): ?>
	<tr>
		<td class=" actions">	
			<?php echo $this->element('actions', array('created' => $meetingTopic['MeetingTopic']['created_by'], 'postVal' => $meetingTopic['MeetingTopic']['id'], 'softDelete' => $meetingTopic['MeetingTopic']['soft_delete'])); ?>	
		</td>		
		<td>
			<?php echo $this->Html->link($meetingTopic['Meeting']['title'], array('controller' => 'meetings', 'action' => 'view', $meetingTopic['Meeting']['id'])); ?>
		</td>
		<!-- <td>
			<?php echo $this->Html->link($meetingTopic['ChangeAdditionDeletionRequest']['request_details'], array('controller' => 'change_addition_deletion_requests', 'action' => 'view', $meetingTopic['ChangeAdditionDeletionRequest']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($meetingTopic['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $meetingTopic['CorrectivePreventiveAction']['id'])); ?>
		</td>
		<td><?php echo h($meetingTopic['MeetingTopic']['document_amendment_record_sheet_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($meetingTopic['CustomerComplaint']['name'], array('controller' => 'customer_complaints', 'action' => 'view', $meetingTopic['CustomerComplaint']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($meetingTopic['CustomerFeedback']['id'], array('controller' => 'customer_feedbacks', 'action' => 'view', $meetingTopic['CustomerFeedback']['id'])); ?>
		</td>
		<td><?php echo h($meetingTopic['MeetingTopic']['supplier_evaluation_reevaluation_id']); ?>&nbsp;</td>
		<td><?php echo h($meetingTopic['MeetingTopic']['summery_of_supplier_evaluation_id']); ?>&nbsp;</td>
		<td><?php echo h($meetingTopic['MeetingTopic']['internal_audit_plan_id']); ?>&nbsp;</td> -->
		<td><?php echo h($meetingTopic['MeetingTopic']['title']); ?>&nbsp;</td>
		<td><?php echo h($meetingTopic['MeetingTopic']['current_status']); ?>&nbsp;</td>
		<!-- <td><?php echo h($meetingTopic['MeetingTopic']['action_plan']); ?>&nbsp;</td> -->
		<td>
			<?php echo $this->Html->link($meetingTopic['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $meetingTopic['Employee']['id'])); ?>
		</td>
		<td><?php echo h($meetingTopic['MeetingTopic']['target_date']); ?>&nbsp;</td>
		<!-- <td><?php echo h($meetingTopic['MeetingTopic']['notes']); ?>&nbsp;</td>
		<td><?php echo h($meetingTopic['MeetingTopic']['division_id']); ?>&nbsp;</td> -->
		<!-- <td><?php echo h($PublishedEmployeeList[$meetingTopic['MeetingTopic']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$meetingTopic['MeetingTopic']['approved_by']]); ?>&nbsp;</td> -->

		<td width="60">
			<?php if($meetingTopic['MeetingTopic']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=99>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","current_status"=>"Current Status","action_plan"=>"Action Plan","target_date"=>"Target Date","notes"=>"Notes"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
