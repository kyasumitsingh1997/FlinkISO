
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
	<div class="projectTimesheets ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Timesheets','modelClass'=>'ProjectTimesheet','options'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'projectTimesheets'))); ?>
	
		
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

			<?php foreach ($projectTimesheets as $projectTimesheet): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $projectTimesheet['ProjectTimesheet']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectTimesheet['ProjectTimesheet']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectTimesheet['ProjectTimesheet']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $projectTimesheet['ProjectTimesheet']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $projectTimesheet['ProjectTimesheet']['id']),array('class'=>''), __('Are you sure ?', $projectTimesheet['ProjectTimesheet']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($projectTimesheet['ProjectTimesheet']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('user_id') ."</dt><dd>:". $this->Html->link($projectTimesheet['User']['name'], array('controller' => 'users', 'action' => 'view', $projectTimesheet['User']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('project_id') ."</dt><dd>:". $this->Html->link($projectTimesheet['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectTimesheet['Project']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('project_activity_id') ."</dt><dd>:". $this->Html->link($projectTimesheet['ProjectActivity']['title'], array('controller' => 'project_activities', 'action' => 'view', $projectTimesheet['ProjectActivity']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('start_time') ."</dt><dd>: ". h($projectTimesheet['ProjectTimesheet']['start_time']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('end_time') ."</dt><dd>: ". h($projectTimesheet['ProjectTimesheet']['end_time']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('activity_description') ."</dt><dd>: ". h($projectTimesheet['ProjectTimesheet']['activity_description']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('total_time') ."</dt><dd>: ". h($projectTimesheet['ProjectTimesheet']['total_time']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('total_cost') ."</dt><dd>: ". h($projectTimesheet['ProjectTimesheet']['total_cost']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($projectTimesheet['ProjectTimesheet']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($projectTimesheet['ProjectTimesheet']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($projectTimesheet['ProjectTimesheet']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($projectTimesheet['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectTimesheet['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($projectTimesheet['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectTimesheet['ApprovedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$projectTimesheet['ProjectTimesheet']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>