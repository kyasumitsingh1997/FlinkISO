
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
	<div class="emailTriggers ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Email Triggers','modelClass'=>'EmailTrigger','options'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","system_table"=>"System Table","changed_field"=>"Changed Field","if_added"=>"If Added","if_edited"=>"If Edited","if_publish"=>"If Publish","if_approved"=>"If Approved","if_soft_delete"=>"If Soft Delete","recipents"=>"Recipents","cc"=>"Cc","bcc"=>"Bcc","subject"=>"Subject","template"=>"Template","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'emailTriggers'))); ?>
	
		
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

			<?php foreach ($emailTriggers as $emailTrigger): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $emailTrigger['EmailTrigger']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $emailTrigger['EmailTrigger']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $emailTrigger['EmailTrigger']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $emailTrigger['EmailTrigger']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $emailTrigger['EmailTrigger']['id']),array('class'=>''), __('Are you sure ?', $emailTrigger['EmailTrigger']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['name']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('details') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('system_table') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['system_table']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('changed_field') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['changed_field']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('if_added') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['if_added']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('if_edited') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['if_edited']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('if_publish') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['if_publish']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('if_approved') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['if_approved']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('if_soft_delete') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['if_soft_delete']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('recipents') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['recipents']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('cc') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['cc']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('bcc') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['bcc']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('subject') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['subject']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('template') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['template']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($emailTrigger['EmailTrigger']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($emailTrigger['EmailTrigger']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($emailTrigger['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $emailTrigger['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($emailTrigger['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $emailTrigger['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($emailTrigger['Company']['name'], array('controller' => 'companies', 'action' => 'view', $emailTrigger['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$emailTrigger['EmailTrigger']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","system_table"=>"System Table","changed_field"=>"Changed Field","if_added"=>"If Added","if_edited"=>"If Edited","if_publish"=>"If Publish","if_approved"=>"If Approved","if_soft_delete"=>"If Soft Delete","recipents"=>"Recipents","cc"=>"Cc","bcc"=>"Bcc","subject"=>"Subject","template"=>"Template","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","system_table"=>"System Table","changed_field"=>"Changed Field","if_added"=>"If Added","if_edited"=>"If Edited","if_publish"=>"If Publish","if_approved"=>"If Approved","if_soft_delete"=>"If Soft Delete","recipents"=>"Recipents","cc"=>"Cc","bcc"=>"Bcc","subject"=>"Subject","template"=>"Template","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>