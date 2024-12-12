<div id="processWeeklyPlans_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="processWeeklyPlans form col-md-8">
<h4><?php echo __('View Process Weekly Plan'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Project Process Plan'); ?></td>
		<td>
			<?php echo $this->Html->link($processWeeklyPlan['ProjectProcessPlan']['id'], array('controller' => 'project_process_plans', 'action' => 'view', $processWeeklyPlan['ProjectProcessPlan']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($processWeeklyPlan['Project']['title'], array('controller' => 'projects', 'action' => 'view', $processWeeklyPlan['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone'); ?></td>
		<td>
			<?php echo $this->Html->link($processWeeklyPlan['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $processWeeklyPlan['Milestone']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Year'); ?></td>
		<td>
			<?php echo h($processWeeklyPlan['ProcessWeeklyPlan']['year']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Week'); ?></td>
		<td>
			<?php echo h($processWeeklyPlan['ProcessWeeklyPlan']['week']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Planned'); ?></td>
		<td>
			<?php echo h($processWeeklyPlan['ProcessWeeklyPlan']['planned']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($processWeeklyPlan['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($processWeeklyPlan['ApprovedBy']['name']); ?>&nbsp;</td></tr>
	<tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($processWeeklyPlan['ProcessWeeklyPlan']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($processWeeklyPlan['ProcessWeeklyPlan']['soft_delete'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => ${$singularVar}['{$modelClass}']['created_by'], 'recordId' => ${$singularVar}['{$modelClass}']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#processWeeklyPlans_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$processWeeklyPlan['ProcessWeeklyPlan']['id'] ,'ajax'),array('async' => true, 'update' => '#processWeeklyPlans_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
