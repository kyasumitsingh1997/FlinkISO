<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="invoiceDetails ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Invoice Details','modelClass'=>'InvoiceDetail','options'=>array("sr_no"=>"Sr No","other"=>"Other","item_number"=>"Item Number","quantity"=>"Quantity","description"=>"Description","rate"=>"Rate","discount"=>"Discount","total"=>"Total","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'invoiceDetails'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Invoice Detail'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Purchase Order'), array('controller' => 'purchase_orders', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Purchase Order Details'), array('controller' => 'purchase_order_details', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Invoice'), array('controller' => 'invoices', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Product'), array('controller' => 'products', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Device'), array('controller' => 'devices', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Material'), array('controller' => 'materials', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Status User'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Division'), array('controller' => 'divisions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="invoiceDetails_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","other"=>"Other","item_number"=>"Item Number","quantity"=>"Quantity","description"=>"Description","rate"=>"Rate","discount"=>"Discount","total"=>"Total","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","other"=>"Other","item_number"=>"Item Number","quantity"=>"Quantity","description"=>"Description","rate"=>"Rate","discount"=>"Discount","total"=>"Total","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>