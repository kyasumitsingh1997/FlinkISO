<div id="projectTimesheets_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectTimesheets form col-md-8">
<h4><?php echo __('View Project Timesheet'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('User'); ?></td>
		<td>
			<?php echo $this->Html->link($projectTimesheet['User']['name'], array('controller' => 'users', 'action' => 'view', $projectTimesheet['User']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($projectTimesheet['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectTimesheet['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project Activity'); ?></td>
		<td>
			<?php echo $this->Html->link($projectTimesheet['ProjectActivity']['title'], array('controller' => 'project_activities', 'action' => 'view', $projectTimesheet['ProjectActivity']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Time'); ?></td>
		<td>
			<?php echo h($projectTimesheet['ProjectTimesheet']['start_time']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Time'); ?></td>
		<td>
			<?php echo h($projectTimesheet['ProjectTimesheet']['end_time']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Activity Description'); ?></td>
		<td>
			<?php echo h($projectTimesheet['ProjectTimesheet']['activity_description']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Total Time'); ?></td>
		<td>
			<?php echo h($projectTimesheet['ProjectTimesheet']['total_time']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Total Cost'); ?></td>
		<td>
			<?php echo h($projectTimesheet['ProjectTimesheet']['total_cost']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($projectTimesheet['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($projectTimesheet['ApprovedBy']['name']); ?>&nbsp;</td></tr>
	<tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($projectTimesheet['ProjectTimesheet']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($projectTimesheet['ProjectTimesheet']['soft_delete'] == 1) { ?>
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectTimesheets_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$projectTimesheet['ProjectTimesheet']['id'] ,'ajax'),array('async' => true, 'update' => '#projectTimesheets_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
