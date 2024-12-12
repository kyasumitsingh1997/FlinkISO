<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="productionRejections ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Production Rejections','modelClass'=>'ProductionRejection','options'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'productionRejections'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Production Rejection'), array('action' => 'add_ajax','production_id'=>$this->request->params['named']['production_id'],'product_id'=>$this->request->params['named']['product_id'],'production_weekly_plan_id'=>$this->request->params['named']['production_weekly_plan_id'])); ?></li>
					<li><?php // echo $this->Html->link(__('Add Production'), array('controller' => 'productions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Product'), array('controller' => 'products', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Production Inspection Template'), array('controller' => 'production_inspection_templates', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Supplier Registration'), array('controller' => 'supplier_registrations', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Customer Contact'), array('controller' => 'customer_contacts', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="productionRejections_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
