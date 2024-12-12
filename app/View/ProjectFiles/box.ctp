
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
	<div class="projectFiles ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Files','modelClass'=>'ProjectFile','options'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'projectFiles'))); ?>
	
		
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

			<?php foreach ($projectFiles as $projectFile): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $projectFile['ProjectFile']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectFile['ProjectFile']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectFile['ProjectFile']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $projectFile['ProjectFile']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $projectFile['ProjectFile']['id']),array('class'=>''), __('Are you sure ?', $projectFile['ProjectFile']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($projectFile['ProjectFile']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($projectFile['ProjectFile']['name']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('project_id') ."</dt><dd>:". $this->Html->link($projectFile['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectFile['Project']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('milestone_id') ."</dt><dd>:". $this->Html->link($projectFile['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectFile['Milestone']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($projectFile['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $projectFile['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('assigned_date') ."</dt><dd>: ". h($projectFile['ProjectFile']['assigned_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('estimated_time') ."</dt><dd>: ". h($projectFile['ProjectFile']['estimated_time']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('completed_date') ."</dt><dd>: ". h($projectFile['ProjectFile']['completed_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('start_date') ."</dt><dd>: ". h($projectFile['ProjectFile']['start_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('end_date') ."</dt><dd>: ". h($projectFile['ProjectFile']['end_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('actual_time') ."</dt><dd>: ". h($projectFile['ProjectFile']['actual_time']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('comments') ."</dt><dd>: ". h($projectFile['ProjectFile']['comments']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('current_status') ."</dt><dd>: ". h($projectFile['ProjectFile']['current_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('checked_by') ."</dt><dd>: ". h($projectFile['ProjectFile']['checked_by']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($projectFile['ProjectFile']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($projectFile['ProjectFile']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($projectFile['ProjectFile']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($projectFile['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectFile['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($projectFile['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectFile['ApprovedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$projectFile['ProjectFile']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>