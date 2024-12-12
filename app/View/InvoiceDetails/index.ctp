<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="invoiceDetails ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Invoice Details','modelClass'=>'InvoiceDetail','options'=>array("sr_no"=>"Sr No","other"=>"Other","item_number"=>"Item Number","quantity"=>"Quantity","description"=>"Description","rate"=>"Rate","discount"=>"Discount","total"=>"Total"),'pluralVar'=>'invoiceDetails'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('purchase_order_id'); ?></th>
				<th><?php echo $this->Paginator->sort('purchase_order_detail_id'); ?></th>
				<th><?php echo $this->Paginator->sort('invoice_id'); ?></th>
				<th><?php echo $this->Paginator->sort('product_id'); ?></th>
				<th><?php echo $this->Paginator->sort('device_id'); ?></th>
				<th><?php echo $this->Paginator->sort('material_id'); ?></th>
				<th><?php echo $this->Paginator->sort('other'); ?></th>
				<th><?php echo $this->Paginator->sort('item_number'); ?></th>
				<th><?php echo $this->Paginator->sort('quantity'); ?></th>
				<th><?php echo $this->Paginator->sort('description'); ?></th>
				<th><?php echo $this->Paginator->sort('rate'); ?></th>
				<th><?php echo $this->Paginator->sort('discount'); ?></th>
				<th><?php echo $this->Paginator->sort('total'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($invoiceDetails){ ?>
<?php foreach ($invoiceDetails as $invoiceDetail): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $invoiceDetail['InvoiceDetail']['created_by'], 'postVal' => $invoiceDetail['InvoiceDetail']['id'], 'softDelete' => $invoiceDetail['InvoiceDetail']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($invoiceDetail['PurchaseOrder']['name'], array('controller' => 'purchase_orders', 'action' => 'view', $invoiceDetail['PurchaseOrder']['id'])); ?>
		</td>
		<td><?php echo h($invoiceDetail['InvoiceDetail']['purchase_order_detail_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Invoice']['id'], array('controller' => 'invoices', 'action' => 'view', $invoiceDetail['Invoice']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Product']['name'], array('controller' => 'products', 'action' => 'view', $invoiceDetail['Product']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Device']['name'], array('controller' => 'devices', 'action' => 'view', $invoiceDetail['Device']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Material']['name'], array('controller' => 'materials', 'action' => 'view', $invoiceDetail['Material']['id'])); ?>
		</td>
		<td><?php echo h($invoiceDetail['InvoiceDetail']['other']); ?>&nbsp;</td>
		<td><?php echo h($invoiceDetail['InvoiceDetail']['item_number']); ?>&nbsp;</td>
		<td><?php echo h($invoiceDetail['InvoiceDetail']['quantity']); ?>&nbsp;</td>
		<td><?php echo h($invoiceDetail['InvoiceDetail']['description']); ?>&nbsp;</td>
		<td><?php echo h($invoiceDetail['InvoiceDetail']['rate']); ?>&nbsp;</td>
		<td><?php echo h($invoiceDetail['InvoiceDetail']['discount']); ?>&nbsp;</td>
		<td><?php echo h($invoiceDetail['InvoiceDetail']['total']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($invoiceDetail['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $invoiceDetail['Division']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$invoiceDetail['InvoiceDetail']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$invoiceDetail['InvoiceDetail']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($invoiceDetail['InvoiceDetail']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=93>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#main',
			'evalScripts' => true,
			'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
			));
			
			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
			?>			</p>
			<ul class="pagination">
			<?php
		echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
		echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
		echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
	?>
			</ul>
		</div>
	</div>
	</div>	

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","other"=>"Other","item_number"=>"Item Number","quantity"=>"Quantity","description"=>"Description","rate"=>"Rate","discount"=>"Discount","total"=>"Total"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","other"=>"Other","item_number"=>"Item Number","quantity"=>"Quantity","description"=>"Description","rate"=>"Rate","discount"=>"Discount","total"=>"Total"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
