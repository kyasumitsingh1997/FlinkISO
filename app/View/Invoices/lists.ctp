<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="invoices ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Invoices','modelClass'=>'Invoice','options'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'invoices'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Invoice'), array('action' => 'add_ajax',
						'project_id'=>$this->request->params['named']['project_id'],
						'milestone_id'=>$this->request->params['named']['milestone_id'],
						$this->request->params['pass'][0])); ?></li>
					<li><?php // echo $this->Html->link(__('Add Purchase Order'), array('controller' => 'purchase_orders', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Customer'), array('controller' => 'customers', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Customer Contact'), array('controller' => 'customer_contacts', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Status User'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Division'), array('controller' => 'divisions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Invoice Detail'), array('controller' => 'invoice_details', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="invoices_tab_ajax"></div>
</div>

<script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
	ui.jqXHR.error(function() {
	  ui.panel.html(
	    "Error Loading ... " +
	    "Please contact administrator." );
	});
      }
    });
  });
</script>

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>