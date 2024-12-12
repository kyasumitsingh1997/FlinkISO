<div id="projectActivityRequirements_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectActivityRequirements form col-md-8">
<h4><?php echo __('View Project Activity Requirement'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectActivityRequirement['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone'); ?></td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectActivityRequirement['Milestone']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project Activity'); ?></td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['ProjectActivity']['title'], array('controller' => 'project_activities', 'action' => 'view', $projectActivityRequirement['ProjectActivity']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($projectActivityRequirement['ProjectActivityRequirement']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($projectActivityRequirement['ProjectActivityRequirement']['details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Manpower'); ?></td>
		<td>
			<?php echo h($projectActivityRequirement['ProjectActivityRequirement']['manpower']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Manpower Details'); ?></td>
		<td>
			<?php echo h($projectActivityRequirement['ProjectActivityRequirement']['manpower_Details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Infrastructure'); ?></td>
		<td>
			<?php echo h($projectActivityRequirement['ProjectActivityRequirement']['infrastructure']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Other'); ?></td>
		<td>
			<?php echo h($projectActivityRequirement['ProjectActivityRequirement']['other']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $projectActivityRequirement['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Users'); ?></td>
		<td>
			<?php echo h($projectActivityRequirement['ProjectActivityRequirement']['users']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('User Session'); ?></td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $projectActivityRequirement['UserSession']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($projectActivityRequirement['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($projectActivityRequirement['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($projectActivityRequirement['ProjectActivityRequirement']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($projectActivityRequirement['ProjectActivityRequirement']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $projectActivityRequirement['ProjectActivityRequirement']['created_by'], 'recordId' => $projectActivityRequirement['ProjectActivityRequirement']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectActivityRequirements_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$projectActivityRequirement['ProjectActivityRequirement']['id'] ,'ajax'),array('async' => true, 'update' => '#projectActivityRequirements_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
