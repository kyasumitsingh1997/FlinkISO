<div id="invoiceDetails_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="invoiceDetails form col-md-8">
<h4><?php echo __('View Invoice Detail'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Purchase Order'); ?></td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['PurchaseOrder']['name'], array('controller' => 'purchase_orders', 'action' => 'view', $invoiceDetail['PurchaseOrder']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Purchase Order Detail Id'); ?></td>
		<td>
			<?php echo h($invoiceDetail['InvoiceDetail']['purchase_order_detail_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Invoice'); ?></td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Invoice']['id'], array('controller' => 'invoices', 'action' => 'view', $invoiceDetail['Invoice']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Product'); ?></td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Product']['name'], array('controller' => 'products', 'action' => 'view', $invoiceDetail['Product']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Device'); ?></td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Device']['name'], array('controller' => 'devices', 'action' => 'view', $invoiceDetail['Device']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Material'); ?></td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Material']['name'], array('controller' => 'materials', 'action' => 'view', $invoiceDetail['Material']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Other'); ?></td>
		<td>
			<?php echo h($invoiceDetail['InvoiceDetail']['other']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Item Number'); ?></td>
		<td>
			<?php echo h($invoiceDetail['InvoiceDetail']['item_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Quantity'); ?></td>
		<td>
			<?php echo h($invoiceDetail['InvoiceDetail']['quantity']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($invoiceDetail['InvoiceDetail']['description']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Rate'); ?></td>
		<td>
			<?php echo h($invoiceDetail['InvoiceDetail']['rate']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Discount'); ?></td>
		<td>
			<?php echo h($invoiceDetail['InvoiceDetail']['discount']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Total'); ?></td>
		<td>
			<?php echo h($invoiceDetail['InvoiceDetail']['total']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division'); ?></td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $invoiceDetail['Division']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($invoiceDetail['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($invoiceDetail['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($invoiceDetail['InvoiceDetail']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($invoiceDetail['InvoiceDetail']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $invoiceDetail['InvoiceDetail']['created_by'], 'recordId' => $invoiceDetail['InvoiceDetail']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#invoiceDetails_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$invoiceDetail['InvoiceDetail']['id'] ,'ajax'),array('async' => true, 'update' => '#invoiceDetails_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
