<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="invoiceSettings ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Invoice Settings','modelClass'=>'InvoiceSetting','options'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details"),'pluralVar'=>'invoiceSettings'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('vat_number'); ?></th>
				<th><?php echo $this->Paginator->sort('sales_tax_number'); ?></th>
				<th><?php echo $this->Paginator->sort('service_tax_number'); ?></th>
				<th><?php echo $this->Paginator->sort('company_name'); ?></th>
				<th><?php echo $this->Paginator->sort('banking_details'); ?></th>
				<th><?php echo $this->Paginator->sort('footer'); ?></th>
				<th><?php echo $this->Paginator->sort('contact_details'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('publish'); ?></th>						
				</tr>
				<?php if($invoiceSettings){ ?>
<?php foreach ($invoiceSettings as $invoiceSetting): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $invoiceSetting['InvoiceSetting']['created_by'], 'postVal' => $invoiceSetting['InvoiceSetting']['id'], 'softDelete' => $invoiceSetting['InvoiceSetting']['soft_delete'])); ?>	</td>		<td><?php echo h($invoiceSetting['InvoiceSetting']['vat_number']); ?>&nbsp;</td>
		<td><?php echo h($invoiceSetting['InvoiceSetting']['sales_tax_number']); ?>&nbsp;</td>
		<td><?php echo h($invoiceSetting['InvoiceSetting']['service_tax_number']); ?>&nbsp;</td>
		<td><?php echo h($invoiceSetting['InvoiceSetting']['company_name']); ?>&nbsp;</td>
		<td><?php echo h($invoiceSetting['InvoiceSetting']['banking_details']); ?>&nbsp;</td>
		<td><?php echo h($invoiceSetting['InvoiceSetting']['footer']); ?>&nbsp;</td>
		<td><?php echo h($invoiceSetting['InvoiceSetting']['contact_details']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($invoiceSetting['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $invoiceSetting['Division']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$invoiceSetting['InvoiceSetting']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$invoiceSetting['InvoiceSetting']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($invoiceSetting['InvoiceSetting']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=75>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
