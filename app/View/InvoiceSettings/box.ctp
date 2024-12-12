
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
	<div class="invoiceSettings ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Invoice Settings','modelClass'=>'InvoiceSetting','options'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'invoiceSettings'))); ?>
	
		
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

			<?php foreach ($invoiceSettings as $invoiceSetting): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $invoiceSetting['InvoiceSetting']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invoiceSetting['InvoiceSetting']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invoiceSetting['InvoiceSetting']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $invoiceSetting['InvoiceSetting']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $invoiceSetting['InvoiceSetting']['id']),array('class'=>''), __('Are you sure ?', $invoiceSetting['InvoiceSetting']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('vat_number') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['vat_number']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('sales_tax_number') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['sales_tax_number']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('service_tax_number') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['service_tax_number']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('company_name') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['company_name']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('banking_details') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['banking_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('footer') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['footer']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('contact_details') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['contact_details']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($invoiceSetting['InvoiceSetting']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($invoiceSetting['InvoiceSetting']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($invoiceSetting['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $invoiceSetting['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($invoiceSetting['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $invoiceSetting['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('division_id') ."</dt><dd>:". $this->Html->link($invoiceSetting['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $invoiceSetting['Division']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($invoiceSetting['Company']['name'], array('controller' => 'companies', 'action' => 'view', $invoiceSetting['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$invoiceSetting['InvoiceSetting']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>