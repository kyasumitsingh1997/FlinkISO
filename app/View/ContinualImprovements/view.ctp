<div id="continualImprovements_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="continualImprovements form col-md-8">
<h4><?php echo __('View Continual Improvement'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($continualImprovement['ContinualImprovement']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Corrective Preventive Action'); ?></td>
		<td>
			<?php echo $this->Html->link($continualImprovement['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $continualImprovement['CorrectivePreventiveAction']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process'); ?></td>
		<td>
			<?php echo $this->Html->link($continualImprovement['Process']['title'], array('controller' => 'processes', 'action' => 'view', $continualImprovement['Process']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Internal Audit'); ?></td>
		<td>
			<?php echo $this->Html->link($continualImprovement['InternalAudit']['start_time'], array('controller' => 'internal_audits', 'action' => 'view', $continualImprovement['InternalAudit']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Internal Audit Detail'); ?></td>
		<td>
			<?php echo $this->Html->link($continualImprovement['InternalAuditDetail']['id'], array('controller' => 'internal_audit_details', 'action' => 'view', $continualImprovement['InternalAuditDetail']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($continualImprovement['ContinualImprovement']['details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division'); ?></td>
		<td>
			<?php echo $this->Html->link($continualImprovement['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $continualImprovement['Division']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($continualImprovement['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($continualImprovement['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($continualImprovement['ContinualImprovement']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($continualImprovement['ContinualImprovement']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $continualImprovement['ContinualImprovement']['created_by'], 'recordId' => $continualImprovement['ContinualImprovement']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#continualImprovements_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$continualImprovement['ContinualImprovement']['id'] ,'ajax'),array('async' => true, 'update' => '#continualImprovements_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
