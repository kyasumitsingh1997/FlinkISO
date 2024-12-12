<div id="processTeams_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="processTeams form col-md-8">
<h4><?php echo __('View Process Team'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Process'); ?></td>
		<td>
			<?php echo $this->Html->link($processTeam['Process']['title'], array('controller' => 'processes', 'action' => 'view', $processTeam['Process']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Objective'); ?></td>
		<td>
			<?php echo $this->Html->link($processTeam['Objective']['title'], array('controller' => 'objectives', 'action' => 'view', $processTeam['Objective']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Team'); ?></td>
		<td>
			<?php echo h($processTeam['ProcessTeam']['team']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process Type'); ?></td>
		<td>
			<?php echo h($processTeam['ProcessTeam']['process_type']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($processTeam['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $processTeam['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($processTeam['Department']['name'], array('controller' => 'departments', 'action' => 'view', $processTeam['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Target'); ?></td>
		<td>
			<?php echo h($processTeam['ProcessTeam']['target']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Measurement Details'); ?></td>
		<td>
			<?php echo h($processTeam['ProcessTeam']['measurement_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($processTeam['ProcessTeam']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($processTeam['ProcessTeam']['end_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('System Table'); ?></td>
		<td>
			<?php echo h($processTeam['ProcessTeam']['system_table']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($processTeam['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($processTeam['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($processTeam['ProcessTeam']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($processTeam['ProcessTeam']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $processTeam['ProcessTeam']['created_by'], 'recordId' => $processTeam['ProcessTeam']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#processTeams_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$processTeam['ProcessTeam']['id'] ,'ajax'),array('async' => true, 'update' => '#processTeams_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
