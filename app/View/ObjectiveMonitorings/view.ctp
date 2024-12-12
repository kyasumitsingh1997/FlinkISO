<div id="objectiveMonitorings_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="objectiveMonitorings form col-md-8">
<h4><?php echo __('View Objective Monitoring'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Objective'); ?></td>
		<td>
			<?php echo $this->Html->link($objectiveMonitoring['Objective']['title'], array('controller' => 'objectives', 'action' => 'view', $objectiveMonitoring['Objective']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process'); ?></td>
		<td>
			<?php echo $this->Html->link($objectiveMonitoring['Process']['title'], array('controller' => 'processes', 'action' => 'view', $objectiveMonitoring['Process']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Analysis'); ?></td>
		<td>
			<?php echo h($objectiveMonitoring['ObjectiveMonitoring']['analysis']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Improvements Required'); ?></td>
		<td>
			<?php echo h($objectiveMonitoring['ObjectiveMonitoring']['improvements_required']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($objectiveMonitoring['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($objectiveMonitoring['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($objectiveMonitoring['ObjectiveMonitoring']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($objectiveMonitoring['ObjectiveMonitoring']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $objectiveMonitoring['ObjectiveMonitoring']['created_by'], 'recordId' => $objectiveMonitoring['ObjectiveMonitoring']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#objectiveMonitorings_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$objectiveMonitoring['ObjectiveMonitoring']['id'] ,'ajax'),array('async' => true, 'update' => '#objectiveMonitorings_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
