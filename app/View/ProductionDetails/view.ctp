<div id="productionDetails_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="productionDetails form col-md-8">
<h4><?php echo __('View Production Detail'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Week'); ?></td>
		<td>
			<?php echo h($productionDetail['ProductionDetail']['week']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Unit'); ?></td>
		<td>
			<?php echo $this->Html->link($productionDetail['Unit']['name'], array('controller' => 'units', 'action' => 'view', $productionDetail['Unit']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Production'); ?></td>
		<td>
			<?php echo $this->Html->link($productionDetail['Production']['batch_number'], array('controller' => 'productions', 'action' => 'view', $productionDetail['Production']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Value Driver'); ?></td>
		<td>
			<?php echo $this->Html->link($productionDetail['ValueDriver']['name'], array('controller' => 'value_drivers', 'action' => 'view', $productionDetail['ValueDriver']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Performance Indicator'); ?></td>
		<td>
			<?php echo $this->Html->link($productionDetail['PerformanceIndicator']['name'], array('controller' => 'performance_indicators', 'action' => 'view', $productionDetail['PerformanceIndicator']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Production Accepted'); ?></td>
		<td>
			<?php echo h($productionDetail['ProductionDetail']['production_accepted']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Production Rejected'); ?></td>
		<td>
			<?php echo h($productionDetail['ProductionDetail']['production_rejected']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($productionDetail['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($productionDetail['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($productionDetail['ProductionDetail']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($productionDetail['ProductionDetail']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo ('upload-edit', array('usersId' => productionDetail['ProductionDetail']['created_by'], 'recordId' => productionDetail['ProductionDetail']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#productionDetails_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$productionDetail['ProductionDetail']['id'] ,'ajax'),array('async' => true, 'update' => '#productionDetails_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
