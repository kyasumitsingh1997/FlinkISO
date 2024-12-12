<div id="productionRejections_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="productionRejections form col-md-8">
<h4><?php echo __('View Production Rejection'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($productionRejection['ProductionRejection']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Production'); ?></td>
		<td>
			<?php echo $this->Html->link($productionRejection['Production']['batch_number'], array('controller' => 'productions', 'action' => 'view', $productionRejection['Production']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Product'); ?></td>
		<td>
			<?php echo $this->Html->link($productionRejection['Product']['name'], array('controller' => 'products', 'action' => 'view', $productionRejection['Product']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Production Inspection Template'); ?></td>
		<td>
			<?php echo $this->Html->link($productionRejection['ProductionInspectionTemplate']['name'], array('controller' => 'production_inspection_templates', 'action' => 'view', $productionRejection['ProductionInspectionTemplate']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td colspan="2"><?php echo __('Inspection Report'); ?>
			<?php echo $productionRejection['ProductionRejection']['inspection_report']; ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Total Quantity'); ?></td>
		<td>
			<?php echo h($productionRejection['ProductionRejection']['total_quantity']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Sample Quantity'); ?></td>
		<td>
			<?php echo h($productionRejection['ProductionRejection']['sample_quantity']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Quality Check Date'); ?></td>
		<td>
			<?php echo h($productionRejection['ProductionRejection']['quality_check_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Sr Number'); ?></td>
		<td>
			<?php echo h($productionRejection['ProductionRejection']['start_sr_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Sr Number'); ?></td>
		<td>
			<?php echo h($productionRejection['ProductionRejection']['end_sr_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Number Of Rejections'); ?></td>
		<td>
			<?php echo h($productionRejection['ProductionRejection']['number_of_rejections']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($productionRejection['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $productionRejection['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Supplier Registration'); ?></td>
		<td>
			<?php echo $this->Html->link($productionRejection['SupplierRegistration']['title'], array('controller' => 'supplier_registrations', 'action' => 'view', $productionRejection['SupplierRegistration']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Customer Contact'); ?></td>
		<td>
			<?php echo $this->Html->link($productionRejection['CustomerContact']['name'], array('controller' => 'customer_contacts', 'action' => 'view', $productionRejection['CustomerContact']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($productionRejection['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($productionRejection['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($productionRejection['ProductionRejection']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($productionRejection['ProductionRejection']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $productionRejection['ProductionRejection']['created_by'], 'recordId' => $productionRejection['ProductionRejection']['id'])); ?>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#productionRejections_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$productionRejection['ProductionRejection']['id'] ,'ajax'),array('async' => true, 'update' => '#productionRejections_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
