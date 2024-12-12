<div id="invoiceSettings_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="invoiceSettings form col-md-8">
<h4><?php echo __('View Invoice Setting'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Vat Number'); ?></td>
		<td>
			<?php echo h($invoiceSetting['InvoiceSetting']['vat_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Sales Tax Number'); ?></td>
		<td>
			<?php echo h($invoiceSetting['InvoiceSetting']['sales_tax_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Service Tax Number'); ?></td>
		<td>
			<?php echo h($invoiceSetting['InvoiceSetting']['service_tax_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Company Name'); ?></td>
		<td>
			<?php echo h($invoiceSetting['InvoiceSetting']['company_name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Banking Details'); ?></td>
		<td>
			<?php echo h($invoiceSetting['InvoiceSetting']['banking_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Footer'); ?></td>
		<td>
			<?php echo h($invoiceSetting['InvoiceSetting']['footer']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Contact Details'); ?></td>
		<td>
			<?php echo h($invoiceSetting['InvoiceSetting']['contact_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division'); ?></td>
		<td>
			<?php echo $this->Html->link($invoiceSetting['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $invoiceSetting['Division']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($invoiceSetting['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($invoiceSetting['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($invoiceSetting['InvoiceSetting']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($invoiceSetting['InvoiceSetting']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $invoiceSetting['InvoiceSetting']['created_by'], 'recordId' => $invoiceSetting['InvoiceSetting']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#invoiceSettings_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$invoiceSetting['InvoiceSetting']['id'] ,'ajax'),array('async' => true, 'update' => '#invoiceSettings_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
