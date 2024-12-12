<div id="fmeaActions_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fmeaActions form col-md-8">
<h4><?php echo __('View Fmea Action'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Fmea'); ?></td>
		<td>
			<?php echo $this->Html->link($fmeaAction['Fmea']['id'], array('controller' => 'fmeas', 'action' => 'view', $fmeaAction['Fmea']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($fmeaAction['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $fmeaAction['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Actions Recommended'); ?></td>
		<td>
			<?php echo h($fmeaAction['FmeaAction']['actions_recommended']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Target Date'); ?></td>
		<td>
			<?php echo h($fmeaAction['FmeaAction']['target_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Action Taken'); ?></td>
		<td>
			<?php echo h($fmeaAction['FmeaAction']['action_taken']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Action Taken Date'); ?></td>
		<td>
			<?php echo h($fmeaAction['FmeaAction']['action_taken_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Fmea Severity Type'); ?></td>
		<td>
			<?php echo $this->Html->link($fmeaAction['FmeaSeverityType']['id'], array('controller' => 'fmea_severity_types', 'action' => 'view', $fmeaAction['FmeaSeverityType']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Fmea Occurence'); ?></td>
		<td>
			<?php echo $this->Html->link($fmeaAction['FmeaOccurence']['id'], array('controller' => 'fmea_occurences', 'action' => 'view', $fmeaAction['FmeaOccurence']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Fmea Detection'); ?></td>
		<td>
			<?php echo $this->Html->link($fmeaAction['FmeaDetection']['id'], array('controller' => 'fmea_detections', 'action' => 'view', $fmeaAction['FmeaDetection']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Rpn'); ?></td>
		<td>
			<?php echo h($fmeaAction['FmeaAction']['rpn']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($fmeaAction['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($fmeaAction['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($fmeaAction['FmeaAction']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($fmeaAction['FmeaAction']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo ('upload-edit', array('usersId' => fmeaAction['FmeaAction']['created_by'], 'recordId' => fmeaAction['FmeaAction']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fmeaActions_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$fmeaAction['FmeaAction']['id'] ,'ajax'),array('async' => true, 'update' => '#fmeaActions_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
