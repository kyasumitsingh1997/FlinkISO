<div id="fmeas_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fmeas form col-md-8">
<h4><?php echo __('View FMEA'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Process'); ?></td>
		<td>
			<?php echo $this->Html->link($fmea['Process']['title'], array('controller' => 'processes', 'action' => 'view', $fmea['Process']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Product'); ?></td>
		<td>
			<?php echo $this->Html->link($fmea['Product']['name'], array('controller' => 'products', 'action' => 'view', $fmea['Product']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process Step'); ?></td>
		<td>
			<?php echo h($fmea['Fmea']['process_step']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process Sub Step'); ?></td>
		<td>
			<?php echo h($fmea['Fmea']['process_sub_step']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Contribution Of Sub Step'); ?></td>
		<td>
			<?php echo h($fmea['Fmea']['contribution_of_sub_step']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Potential Failure Mode'); ?></td>
		<td>
			<?php echo h($fmea['Fmea']['potential_failure_mode']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Potential Failure Effects'); ?></td>
		<td>
			<?php echo h($fmea['Fmea']['potential_failure_effects']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Fmea Severity Type'); ?></td>
		<td>
			<?php echo $this->Html->link($fmea['FmeaSeverityType']['id'], array('controller' => 'fmea_severity_types', 'action' => 'view', $fmea['FmeaSeverityType']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Potential Causes'); ?></td>
		<td>
			<?php echo h($fmea['Fmea']['potential_causes']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Fmea Occurence'); ?></td>
		<td>
			<?php echo $this->Html->link($fmea['FmeaOccurence']['id'], array('controller' => 'fmea_occurences', 'action' => 'view', $fmea['FmeaOccurence']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Controls'); ?></td>
		<td>
			<?php echo h($fmea['Fmea']['current_controls']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Fmea Detection'); ?></td>
		<td>
			<?php echo $this->Html->link($fmea['FmeaDetection']['id'], array('controller' => 'fmea_detections', 'action' => 'view', $fmea['FmeaDetection']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Rpn'); ?></td>
		<td>
			<?php echo h($fmea['Fmea']['rpn']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($fmea['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($fmea['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($fmea['Fmea']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($fmea['Fmea']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $fmea['Fmea']['created_by'], 'recordId' => $fmea['Fmea']['id'])); ?></div>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fmeas_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$fmea['Fmea']['id'] ,'ajax'),array('async' => true, 'update' => '#fmeas_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
