
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
	<div class="internalAuditDetails ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Internal Audit Details','modelClass'=>'InternalAuditDetail','options'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'pluralVar'=>'internalAuditDetails'))); ?>
	
		
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

			<?php foreach ($internalAuditDetails as $internalAuditDetail): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $internalAuditDetail['InternalAuditDetail']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $internalAuditDetail['InternalAuditDetail']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $internalAuditDetail['InternalAuditDetail']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $internalAuditDetail['InternalAuditDetail']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $internalAuditDetail['InternalAuditDetail']['id']),array('class'=>''), __('Are you sure ?', $internalAuditDetail['InternalAuditDetail']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('internal_audit_id') ."</dt><dd>:". $this->Html->link($internalAuditDetail['InternalAudit']['start_time'], array('controller' => 'internal_audits', 'action' => 'view', $internalAuditDetail['InternalAudit']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['employee_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('nc_found') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['nc_found']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('question') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['question']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('findings') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['findings']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('opportunities_for_improvement') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['opportunities_for_improvement']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('clause_number') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['clause_number']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('current_status') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['current_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('comments') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['comments']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($internalAuditDetail['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $internalAuditDetail['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($internalAuditDetail['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $internalAuditDetail['PreparedBy']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($internalAuditDetail['InternalAuditDetail']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['record_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>:". $this->Html->link($internalAuditDetail['StatusUserId']['name'], array('controller' => 'users', 'action' => 'view', $internalAuditDetail['StatusUserId']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('division_id') ."</dt><dd>: ". h($internalAuditDetail['InternalAuditDetail']['division_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($internalAuditDetail['Company']['name'], array('controller' => 'companies', 'action' => 'view', $internalAuditDetail['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$internalAuditDetail['InternalAuditDetail']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>