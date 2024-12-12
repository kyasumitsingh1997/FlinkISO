<div id="fmeaDetections_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fmeaDetections form col-md-8">
<h4><?php echo __('View Fmea Detection'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Detection'); ?></td>
		<td>
			<?php echo h($fmeaDetection['FmeaDetection']['detection']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Criteria'); ?></td>
		<td>
			<?php echo h($fmeaDetection['FmeaDetection']['criteria']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Ranking'); ?></td>
		<td>
			<?php echo h($fmeaDetection['FmeaDetection']['ranking']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($fmeaDetection['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($fmeaDetection['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($fmeaDetection['FmeaDetection']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($fmeaDetection['FmeaDetection']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo ('upload-edit', array('usersId' => fmeaDetection['FmeaDetection']['created_by'], 'recordId' => fmeaDetection['FmeaDetection']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fmeaDetections_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$fmeaDetection['FmeaDetection']['id'] ,'ajax'),array('async' => true, 'update' => '#fmeaDetections_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
