<div id="internalAuditDetails_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="internalAuditDetails form col-md-8">
<h4><?php echo __('View Internal Audit Detail'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Internal Audit'); ?></td>
		<td>
			<?php echo $this->Html->link($internalAuditDetail['InternalAudit']['start_time'], array('controller' => 'internal_audits', 'action' => 'view', $internalAuditDetail['InternalAudit']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee Id'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['employee_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Nc Found'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['nc_found']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Question'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['question']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Findings'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['findings']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Opportunities For Improvement'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['opportunities_for_improvement']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Clause Number'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['clause_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['current_status']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Comments'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['comments']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division Id'); ?></td>
		<td>
			<?php echo h($internalAuditDetail['InternalAuditDetail']['division_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($internalAuditDetail['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($internalAuditDetail['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($internalAuditDetail['InternalAuditDetail']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($internalAuditDetail['InternalAuditDetail']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $internalAuditDetail['InternalAuditDetail']['created_by'], 'recordId' => $internalAuditDetail['InternalAuditDetail']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#internalAuditDetails_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$internalAuditDetail['InternalAuditDetail']['id'] ,'ajax'),array('async' => true, 'update' => '#internalAuditDetails_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
