
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
	<div class="internalAuditQuestions ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Internal Audit Questions','modelClass'=>'InternalAuditQuestion','options'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'internalAuditQuestions'))); ?>
	
		
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

			<?php foreach ($internalAuditQuestions as $internalAuditQuestion): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $internalAuditQuestion['InternalAuditQuestion']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $internalAuditQuestion['InternalAuditQuestion']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $internalAuditQuestion['InternalAuditQuestion']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $internalAuditQuestion['InternalAuditQuestion']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $internalAuditQuestion['InternalAuditQuestion']['id']),array('class'=>''), __('Are you sure ?', $internalAuditQuestion['InternalAuditQuestion']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($internalAuditQuestion['InternalAuditQuestion']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('department_id') ."</dt><dd>:". $this->Html->link($internalAuditQuestion['Department']['name'], array('controller' => 'departments', 'action' => 'view', $internalAuditQuestion['Department']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('clause') ."</dt><dd>: ". h($internalAuditQuestion['InternalAuditQuestion']['clause']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($internalAuditQuestion['InternalAuditQuestion']['title']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($internalAuditQuestion['InternalAuditQuestion']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($internalAuditQuestion['InternalAuditQuestion']['record_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>:". $this->Html->link($internalAuditQuestion['StatusUserId']['name'], array('controller' => 'users', 'action' => 'view', $internalAuditQuestion['StatusUserId']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($internalAuditQuestion['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $internalAuditQuestion['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($internalAuditQuestion['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $internalAuditQuestion['PreparedBy']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>: ". h($internalAuditQuestion['InternalAuditQuestion']['company_id']); ?>&nbsp;<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$internalAuditQuestion['InternalAuditQuestion']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>