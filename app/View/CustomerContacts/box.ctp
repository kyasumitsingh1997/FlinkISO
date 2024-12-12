
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
	<div class="customerContacts ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Customer Contacts','modelClass'=>'CustomerContact','options'=>array("sr_no"=>"Sr No","name"=>"Name","phone"=>"Phone","mobile"=>"Mobile","email"=>"Email","address"=>"Address","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'customerContacts'))); ?>
	
		
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

			<?php foreach ($customerContacts as $customerContact): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $customerContact['CustomerContact']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $customerContact['CustomerContact']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $customerContact['CustomerContact']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $customerContact['CustomerContact']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $customerContact['CustomerContact']['id']),array('class'=>''), __('Are you sure ?', $customerContact['CustomerContact']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($customerContact['CustomerContact']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($customerContact['CustomerContact']['name']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('customer_id') ."</dt><dd>:". $this->Html->link($customerContact['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $customerContact['Customer']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('phone') ."</dt><dd>: ". h($customerContact['CustomerContact']['phone']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('mobile') ."</dt><dd>: ". h($customerContact['CustomerContact']['mobile']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('email') ."</dt><dd>: ". h($customerContact['CustomerContact']['email']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('address') ."</dt><dd>: ". h($customerContact['CustomerContact']['address']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($customerContact['CustomerContact']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($customerContact['CustomerContact']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($customerContact['CustomerContact']['status_user_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('state_id') ."</dt><dd>: ". h($customerContact['CustomerContact']['state_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>: ". h($customerContact['CustomerContact']['approved_by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>: ". h($customerContact['CustomerContact']['prepared_by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>: ". h($customerContact['CustomerContact']['company_id']); ?>&nbsp;<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$customerContact['CustomerContact']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","phone"=>"Phone","mobile"=>"Mobile","email"=>"Email","address"=>"Address","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","phone"=>"Phone","mobile"=>"Mobile","email"=>"Email","address"=>"Address","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>