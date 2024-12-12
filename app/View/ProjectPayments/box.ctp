
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
	<div class="projectPayments ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Payments','modelClass'=>'ProjectPayment','options'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","unit"=>"Unit","received_date"=>"Received Date","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'projectPayments'))); ?>
	
		
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

			<?php foreach ($projectPayments as $projectPayment): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $projectPayment['ProjectPayment']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectPayment['ProjectPayment']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectPayment['ProjectPayment']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $projectPayment['ProjectPayment']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $projectPayment['ProjectPayment']['id']),array('class'=>''), __('Are you sure ?', $projectPayment['ProjectPayment']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($projectPayment['ProjectPayment']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('project_id') ."</dt><dd>:". $this->Html->link($projectPayment['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectPayment['Project']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('milestone_id') ."</dt><dd>:". $this->Html->link($projectPayment['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectPayment['Milestone']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('purchase_order_id') ."</dt><dd>:". $this->Html->link($projectPayment['PurchaseOrder']['name'], array('controller' => 'purchase_orders', 'action' => 'view', $projectPayment['PurchaseOrder']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('invoice_id') ."</dt><dd>:". $this->Html->link($projectPayment['Invoice']['id'], array('controller' => 'invoices', 'action' => 'view', $projectPayment['Invoice']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('amount') ."</dt><dd>: ". h($projectPayment['ProjectPayment']['amount']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('amount_received') ."</dt><dd>: ". h($projectPayment['ProjectPayment']['amount_received']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('unit') ."</dt><dd>: ". h($projectPayment['ProjectPayment']['unit']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('received_date') ."</dt><dd>: ". h($projectPayment['ProjectPayment']['received_date']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($projectPayment['ProjectPayment']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($projectPayment['ProjectPayment']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($projectPayment['ProjectPayment']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($projectPayment['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectPayment['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($projectPayment['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectPayment['ApprovedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$projectPayment['ProjectPayment']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","unit"=>"Unit","received_date"=>"Received Date","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","unit"=>"Unit","received_date"=>"Received Date","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>