
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
	<div class="productionRejections ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Production Rejections','modelClass'=>'ProductionRejection','options'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'productionRejections'))); ?>
	
		
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

			<?php foreach ($productionRejections as $productionRejection): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $productionRejection['ProductionRejection']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $productionRejection['ProductionRejection']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $productionRejection['ProductionRejection']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $productionRejection['ProductionRejection']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $productionRejection['ProductionRejection']['id']),array('class'=>''), __('Are you sure ?', $productionRejection['ProductionRejection']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['name']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('production_id') ."</dt><dd>:". $this->Html->link($productionRejection['Production']['batch_number'], array('controller' => 'productions', 'action' => 'view', $productionRejection['Production']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('product_id') ."</dt><dd>:". $this->Html->link($productionRejection['Product']['name'], array('controller' => 'products', 'action' => 'view', $productionRejection['Product']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('production_inspection_template_id') ."</dt><dd>:". $this->Html->link($productionRejection['ProductionInspectionTemplate']['name'], array('controller' => 'production_inspection_templates', 'action' => 'view', $productionRejection['ProductionInspectionTemplate']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('total_quantity') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['total_quantity']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('sample_quantity') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['sample_quantity']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('quality_check_date') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['quality_check_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('start_sr_number') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['start_sr_number']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('end_sr_number') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['end_sr_number']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('number_of_rejections') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['number_of_rejections']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($productionRejection['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $productionRejection['Employee']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('supplier_registration_id') ."</dt><dd>:". $this->Html->link($productionRejection['SupplierRegistration']['title'], array('controller' => 'supplier_registrations', 'action' => 'view', $productionRejection['SupplierRegistration']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('customer_contact_id') ."</dt><dd>:". $this->Html->link($productionRejection['CustomerContact']['name'], array('controller' => 'customer_contacts', 'action' => 'view', $productionRejection['CustomerContact']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($productionRejection['ProductionRejection']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($productionRejection['ProductionRejection']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($productionRejection['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $productionRejection['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($productionRejection['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $productionRejection['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($productionRejection['Company']['name'], array('controller' => 'companies', 'action' => 'view', $productionRejection['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$productionRejection['ProductionRejection']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>