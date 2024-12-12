<div id="milestones_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="milestones form col-md-8">
<h4><?php echo __('View Milestone'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($milestone['Project']['title'], array('controller' => 'projects', 'action' => 'view', $milestone['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($milestone['Milestone']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($milestone['Milestone']['details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Challenges'); ?></td>
		<td>
			<?php echo h($milestone['Milestone']['challenges']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Estimated Cost'); ?></td>
		<td>
			<?php echo h($milestone['Milestone']['estimated_cost']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($milestone['Milestone']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($milestone['Milestone']['end_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo h($milestone['Milestone']['current_status']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($milestone['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $milestone['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Users'); ?></td>
		<td>
			<?php echo h($milestone['Milestone']['users']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('User Session'); ?></td>
		<td>
			<?php echo $this->Html->link($milestone['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $milestone['UserSession']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($milestone['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($milestone['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($milestone['Milestone']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($milestone['Milestone']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $milestone['Milestone']['created_by'], 'recordId' => $milestone['Milestone']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#milestones_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$milestone['Milestone']['id'] ,'ajax'),array('async' => true, 'update' => '#milestones_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
