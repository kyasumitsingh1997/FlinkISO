
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
	<div class="autoApprovalSteps ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Auto Approval Steps','modelClass'=>'AutoApprovalStep','options'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","details"=>"Details","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'autoApprovalSteps'))); ?>
	
		
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

			<?php foreach ($autoApprovalSteps as $autoApprovalStep): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $autoApprovalStep['AutoApprovalStep']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $autoApprovalStep['AutoApprovalStep']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $autoApprovalStep['AutoApprovalStep']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $autoApprovalStep['AutoApprovalStep']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $autoApprovalStep['AutoApprovalStep']['id']),array('class'=>''), __('Are you sure ?', $autoApprovalStep['AutoApprovalStep']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($autoApprovalStep['AutoApprovalStep']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('auto_approval_id') ."</dt><dd>:". $this->Html->link($autoApprovalStep['AutoApproval']['name'], array('controller' => 'auto_approvals', 'action' => 'view', $autoApprovalStep['AutoApproval']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($autoApprovalStep['AutoApprovalStep']['name']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('step_number') ."</dt><dd>: ". h($autoApprovalStep['AutoApprovalStep']['step_number']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('user_id') ."</dt><dd>:". $this->Html->link($autoApprovalStep['User']['name'], array('controller' => 'users', 'action' => 'view', $autoApprovalStep['User']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('details') ."</dt><dd>: ". h($autoApprovalStep['AutoApprovalStep']['details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('system_table') ."</dt><dd>: ". h($autoApprovalStep['AutoApprovalStep']['system_table']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($autoApprovalStep['AutoApprovalStep']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($autoApprovalStep['AutoApprovalStep']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($autoApprovalStep['AutoApprovalStep']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($autoApprovalStep['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $autoApprovalStep['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($autoApprovalStep['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $autoApprovalStep['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($autoApprovalStep['Company']['name'], array('controller' => 'companies', 'action' => 'view', $autoApprovalStep['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$autoApprovalStep['AutoApprovalStep']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","details"=>"Details","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","details"=>"Details","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>