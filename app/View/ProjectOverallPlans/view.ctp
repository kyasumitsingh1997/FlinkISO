<div id="projectOverallPlans_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectOverallPlans form col-md-8">
<h4><?php echo __('View Project Overall Plan'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($projectOverallPlan['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectOverallPlan['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone Id'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['milestone_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Plan Type'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['plan_type']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Lot Process'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['lot_process']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Estimated Units'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_units']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Overall Metrics'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['overall_metrics']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['end_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Estimated Resource'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_resource']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Estimated Manhours'); ?></td>
		<td>
			<?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_manhours']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($projectOverallPlan['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($projectOverallPlan['ApprovedBy']['name']); ?>&nbsp;</td></tr>
	<tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($projectOverallPlan['ProjectOverallPlan']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($projectOverallPlan['ProjectOverallPlan']['soft_delete'] == 1) { ?>
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectOverallPlans_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$projectOverallPlan['ProjectOverallPlan']['id'] ,'ajax'),array('async' => true, 'update' => '#projectOverallPlans_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
