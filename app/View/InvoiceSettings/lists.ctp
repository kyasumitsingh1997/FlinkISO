<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="invoiceSettings ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Invoice Settings','modelClass'=>'InvoiceSetting','options'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'invoiceSettings'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Invoice Setting'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Division'), array('controller' => 'divisions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="invoiceSettings_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","vat_number"=>"Vat Number","sales_tax_number"=>"Sales Tax Number","service_tax_number"=>"Service Tax Number","company_name"=>"Company Name","banking_details"=>"Banking Details","footer"=>"Footer","contact_details"=>"Contact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>