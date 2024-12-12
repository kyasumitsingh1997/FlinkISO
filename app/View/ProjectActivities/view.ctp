<div id="projectActivities_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectActivities form col-md-8">
<h4><?php echo __('View Project Activity'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($projectActivity['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectActivity['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone'); ?></td>
		<td>
			<?php echo $this->Html->link($projectActivity['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectActivity['Milestone']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($projectActivity['ProjectActivity']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($projectActivity['ProjectActivity']['details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Estimated Cost'); ?></td>
		<td>
			<?php echo h($projectActivity['ProjectActivity']['estimated_cost']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($projectActivity['ProjectActivity']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($projectActivity['ProjectActivity']['end_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Sequence'); ?></td>
		<td>
			<?php echo h($projectActivity['ProjectActivity']['sequence']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo ($projectActivity['ProjectActivity']['current_status']? 'Close': 'Open'); ?>&nbsp;
		</td></tr>
		
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($projectActivity['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($projectActivity['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($projectActivity['ProjectActivity']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($projectActivity['ProjectActivity']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $projectActivity['ProjectActivity']['created_by'], 'recordId' => $projectActivity['ProjectActivity']['id'],'showUpload'=>'no')); ?>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectActivities_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$projectActivity['ProjectActivity']['id'] ,'ajax'),array('async' => true, 'update' => '#projectActivities_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
