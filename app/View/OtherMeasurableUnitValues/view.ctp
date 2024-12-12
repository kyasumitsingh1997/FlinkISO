<div id="otherMeasurableUnitValues_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="otherMeasurableUnitValues form col-md-8">
<h4><?php echo __('View Other Measurable Unit Value'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Other Measurable Unit'); ?></td>
		<td>
			<?php echo $this->Html->link($otherMeasurableUnitValue['OtherMeasurableUnit']['id'], array('controller' => 'other_measurable_units', 'action' => 'view', $otherMeasurableUnitValue['OtherMeasurableUnit']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Value'); ?></td>
		<td>
			<?php echo h($otherMeasurableUnitValue['OtherMeasurableUnitValue']['value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project Process Plan'); ?></td>
		<td>
			<?php echo $this->Html->link($otherMeasurableUnitValue['ProjectProcessPlan']['id'], array('controller' => 'project_process_plans', 'action' => 'view', $otherMeasurableUnitValue['ProjectProcessPlan']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($otherMeasurableUnitValue['Project']['title'], array('controller' => 'projects', 'action' => 'view', $otherMeasurableUnitValue['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone'); ?></td>
		<td>
			<?php echo $this->Html->link($otherMeasurableUnitValue['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $otherMeasurableUnitValue['Milestone']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($otherMeasurableUnitValue['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($otherMeasurableUnitValue['ApprovedBy']['name']); ?>&nbsp;</td></tr>
	<tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($otherMeasurableUnitValue['OtherMeasurableUnitValue']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($otherMeasurableUnitValue['OtherMeasurableUnitValue']['soft_delete'] == 1) { ?>
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#otherMeasurableUnitValues_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$otherMeasurableUnitValue['OtherMeasurableUnitValue']['id'] ,'ajax'),array('async' => true, 'update' => '#otherMeasurableUnitValues_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
