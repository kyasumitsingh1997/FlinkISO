
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
	<div class="fileTrackings ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'File Trackings','modelClass'=>'FileTracking','options'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'fileTrackings'))); ?>
	
		
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

			<?php foreach ($fileTrackings as $fileTracking): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $fileTracking['FileTracking']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $fileTracking['FileTracking']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $fileTracking['FileTracking']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $fileTracking['FileTracking']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $fileTracking['FileTracking']['id']),array('class'=>''), __('Are you sure ?', $fileTracking['FileTracking']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($fileTracking['FileTracking']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('project_file_id') ."</dt><dd>:". $this->Html->link($fileTracking['ProjectFile']['name'], array('controller' => 'project_files', 'action' => 'view', $fileTracking['ProjectFile']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('project_id') ."</dt><dd>:". $this->Html->link($fileTracking['Project']['title'], array('controller' => 'projects', 'action' => 'view', $fileTracking['Project']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('milestone_id') ."</dt><dd>:". $this->Html->link($fileTracking['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $fileTracking['Milestone']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('from') ."</dt><dd>: ". h($fileTracking['FileTracking']['from']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('to') ."</dt><dd>: ". h($fileTracking['FileTracking']['to']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('by') ."</dt><dd>: ". h($fileTracking['FileTracking']['by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('current_status') ."</dt><dd>: ". h($fileTracking['FileTracking']['current_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('changed_on') ."</dt><dd>: ". h($fileTracking['FileTracking']['changed_on']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('changetype') ."</dt><dd>: ". h($fileTracking['FileTracking']['changetype']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('function') ."</dt><dd>: ". h($fileTracking['FileTracking']['function']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('comment') ."</dt><dd>: ". h($fileTracking['FileTracking']['comment']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($fileTracking['FileTracking']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($fileTracking['FileTracking']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($fileTracking['FileTracking']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($fileTracking['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $fileTracking['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($fileTracking['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $fileTracking['ApprovedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$fileTracking['FileTracking']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>