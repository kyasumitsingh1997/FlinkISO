
<script>
	function getVals(){
		
	var checkedValue = null;
	$("#recs_selected").val(null);
	var inputElements = document.getElementsByTagName('input');
	
	for(var i=0; inputElements[i]; ++i){
		
	      if(inputElements[i].className==="rec_ids" && 
		 inputElements[i].checked){
		   $("#recs_selected").val($("#recs_selected").val() + '+' + inputElements[i].value);
		   
	      }
	}
	}
</script><?php echo $this->Session->flash();?>	
	<div class="invoices ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Invoices','modelClass'=>'Invoice','options'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'invoices'))); ?>
	
		
<script type="text/javascript">
$(document).ready(function(){
$('dl dt a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>
		<div class="container row  row table-responsive">

			<?php foreach ($invoices as $invoice): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $invoice['Invoice']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invoice['Invoice']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invoice['Invoice']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $invoice['Invoice']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $invoice['Invoice']['id']),array('class'=>''), __('Are you sure ?', $invoice['Invoice']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($invoice['Invoice']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('purchase_order_id') ."</dt><dd>:". $this->Html->link($invoice['PurchaseOrder']['name'], array('controller' => 'purchase_orders', 'action' => 'view', $invoice['PurchaseOrder']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('invoice_number') ."</dt><dd>: ". h($invoice['Invoice']['invoice_number']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('work_order_number') ."</dt><dd>: ". h($invoice['Invoice']['work_order_number']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('customer_id') ."</dt><dd>:". $this->Html->link($invoice['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $invoice['Customer']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('customer_contact_id') ."</dt><dd>:". $this->Html->link($invoice['CustomerContact']['name'], array('controller' => 'customer_contacts', 'action' => 'view', $invoice['CustomerContact']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('invoice_date') ."</dt><dd>: ". h($invoice['Invoice']['invoice_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('details') ."</dt><dd>: ". h($invoice['Invoice']['details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('banking_details') ."</dt><dd>: ". h($invoice['Invoice']['banking_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('subtotal') ."</dt><dd>: ". h($invoice['Invoice']['subtotal']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('vat') ."</dt><dd>: ". h($invoice['Invoice']['vat']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('sales_tax') ."</dt><dd>: ". h($invoice['Invoice']['sales_tax']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('discount') ."</dt><dd>: ". h($invoice['Invoice']['discount']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('total') ."</dt><dd>: ". h($invoice['Invoice']['total']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('notes') ."</dt><dd>: ". h($invoice['Invoice']['notes']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('invoice_due_date') ."</dt><dd>: ". h($invoice['Invoice']['invoice_due_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('send_to_customer') ."</dt><dd>: ". h($invoice['Invoice']['send_to_customer']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($invoice['Invoice']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($invoice['Invoice']['record_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>:". $this->Html->link($invoice['StatusUser']['name'], array('controller' => 'users', 'action' => 'view', $invoice['StatusUser']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>: ". h($invoice['Invoice']['approved_by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>: ". h($invoice['Invoice']['prepared_by']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('division_id') ."</dt><dd>:". $this->Html->link($invoice['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $invoice['Division']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($invoice['Company']['name'], array('controller' => 'companies', 'action' => 'view', $invoice['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$invoice['Invoice']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>