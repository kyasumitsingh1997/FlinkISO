<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="invoices ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Invoices','modelClass'=>'Invoice','options'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer"),'pluralVar'=>'invoices'))); ?>

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
				<th><?php echo $this->Paginator->sort('invoice_number'); ?></th>
				<th><?php echo $this->Paginator->sort('work_order_number'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_id'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_contact_id'); ?></th>
				<th><?php echo $this->Paginator->sort('invoice_date'); ?></th>
				<!--<th><?php echo $this->Paginator->sort('details'); ?></th> 
				<th><?php echo $this->Paginator->sort('banking_details'); ?></th>
				<th><?php echo $this->Paginator->sort('subtotal'); ?></th>
				<th><?php echo $this->Paginator->sort('vat'); ?></th>
				<th><?php echo $this->Paginator->sort('sales_tax'); ?></th>
				<th><?php echo $this->Paginator->sort('discount'); ?></th>
				<th><?php echo $this->Paginator->sort('total'); ?></th>
				<th><?php echo $this->Paginator->sort('notes'); ?></th>-->
				<th><?php echo $this->Paginator->sort('invoice_due_date'); ?></th>
				<th><?php echo $this->Paginator->sort('send_to_customer'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($invoices){ ?>
<?php foreach ($invoices as $invoice): ?>
	<tr>
	<td class=" actions">	
		<?php echo $this->element('actions', array('created' => $invoice['Invoice']['created_by'], 'postVal' => $invoice['Invoice']['id'], 'softDelete' => $invoice['Invoice']['soft_delete'], 'send_to_customer'=>$invoice['Invoice']['send_to_customer'],'publish'=>$invoice['Invoice']['publish'])); ?>	
	</td>		
		<td>
			<?php echo $this->Html->link($invoice['PurchaseOrder']['name'], array('controller' => 'purchase_orders', 'action' => 'view', $invoice['PurchaseOrder']['id'])); ?>
		</td>
		<td><?php echo h($invoice['Invoice']['invoice_number']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['work_order_number']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($invoice['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $invoice['Customer']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($invoice['CustomerContact']['name'], array('controller' => 'customer_contacts', 'action' => 'view', $invoice['CustomerContact']['id'])); ?>
		</td>
		<td><?php echo h($invoice['Invoice']['invoice_date']); ?>&nbsp;</td>
		<!--<td><?php echo h($invoice['Invoice']['details']); ?>&nbsp;</td> 
		<td><?php echo h($invoice['Invoice']['banking_details']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['subtotal']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['vat']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['sales_tax']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['discount']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['total']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['notes']); ?>&nbsp;</td>-->
		<td><?php echo h($invoice['Invoice']['invoice_due_date']); ?>&nbsp;</td>
		<td><?php 
			if($invoice['Invoice']['send_to_customer'] == 0){
				echo $this->Html->link('Send To Customer',array('controller'=>'invoices','action'=>'send_to_customer',$invoice['Invoice']['id']),array('class'=>'btn btn-xs btn-info'));
			}else{
				echo "Invoice Sent";
			}
		?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$invoice['Invoice']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$invoice['Invoice']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($invoice['Invoice']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=102>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
