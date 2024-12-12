<div id="projectProcessPlans_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectProcessPlans form col-md-8">
<h4><?php echo __('View Project Process Plan'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectProcessPlan['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone'); ?></td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectProcessPlan['Milestone']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project Overall Plan'); ?></td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['ProjectOverallPlan']['id'], array('controller' => 'project_overall_plans', 'action' => 'view', $projectProcessPlan['ProjectOverallPlan']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process'); ?></td>
		<td>
			<?php echo h($projectProcessPlan['ProjectProcessPlan']['process']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Estimated Units'); ?></td>
		<td>
			<?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_units']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Overall Metrics'); ?></td>
		<td>
			<?php echo h($projectProcessPlan['ProjectProcessPlan']['overall_metrics']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($projectProcessPlan['ProjectProcessPlan']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($projectProcessPlan['ProjectProcessPlan']['end_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Estimated Resource'); ?></td>
		<td>
			<?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_resource']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Estimated Manhours'); ?></td>
		<td>
			<?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_manhours']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($projectProcessPlan['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($projectProcessPlan['ApprovedBy']['name']); ?>&nbsp;</td></tr>
	<tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($projectProcessPlan['ProjectProcessPlan']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($projectProcessPlan['ProjectProcessPlan']['soft_delete'] == 1) { ?>
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectProcessPlans_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$projectProcessPlan['ProjectProcessPlan']['id'] ,'ajax'),array('async' => true, 'update' => '#projectProcessPlans_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
