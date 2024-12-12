
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
	<div class="productionDetails ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Production Details','modelClass'=>'ProductionDetail','options'=>array("sr_no"=>"Sr No","week"=>"Week","production_accepted"=>"Production Accepted","production_rejected"=>"Production Rejected","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'productionDetails'))); ?>
	
		
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

			<?php foreach ($productionDetails as $productionDetail): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $productionDetail['ProductionDetail']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $productionDetail['ProductionDetail']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $productionDetail['ProductionDetail']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $productionDetail['ProductionDetail']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $productionDetail['ProductionDetail']['id']),array('class'=>''), __('Are you sure ?', $productionDetail['ProductionDetail']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($productionDetail['ProductionDetail']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('week') ."</dt><dd>: ". h($productionDetail['ProductionDetail']['week']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('unit_id') ."</dt><dd>:". $this->Html->link($productionDetail['Unit']['name'], array('controller' => 'units', 'action' => 'view', $productionDetail['Unit']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('production_id') ."</dt><dd>:". $this->Html->link($productionDetail['Production']['batch_number'], array('controller' => 'productions', 'action' => 'view', $productionDetail['Production']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('value_driver_id') ."</dt><dd>:". $this->Html->link($productionDetail['ValueDriver']['name'], array('controller' => 'value_drivers', 'action' => 'view', $productionDetail['ValueDriver']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('performance_indicator_id') ."</dt><dd>:". $this->Html->link($productionDetail['PerformanceIndicator']['name'], array('controller' => 'performance_indicators', 'action' => 'view', $productionDetail['PerformanceIndicator']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('production_accepted') ."</dt><dd>: ". h($productionDetail['ProductionDetail']['production_accepted']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('production_rejected') ."</dt><dd>: ". h($productionDetail['ProductionDetail']['production_rejected']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($productionDetail['ProductionDetail']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($productionDetail['ProductionDetail']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($productionDetail['ProductionDetail']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($productionDetail['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $productionDetail['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($productionDetail['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $productionDetail['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($productionDetail['Company']['name'], array('controller' => 'companies', 'action' => 'view', $productionDetail['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$productionDetail['ProductionDetail']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","week"=>"Week","production_accepted"=>"Production Accepted","production_rejected"=>"Production Rejected","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","week"=>"Week","production_accepted"=>"Production Accepted","production_rejected"=>"Production Rejected","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>