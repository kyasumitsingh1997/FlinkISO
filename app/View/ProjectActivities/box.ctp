
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
	<div class="projectActivities ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Activities','modelClass'=>'ProjectActivity','options'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","sequence"=>"Sequence","current_status"=>"Current Status","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'projectActivities'))); ?>
	
		
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

			<?php foreach ($projectActivities as $projectActivity): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $projectActivity['ProjectActivity']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectActivity['ProjectActivity']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectActivity['ProjectActivity']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $projectActivity['ProjectActivity']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $projectActivity['ProjectActivity']['id']),array('class'=>''), __('Are you sure ?', $projectActivity['ProjectActivity']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('project_id') ."</dt><dd>:". $this->Html->link($projectActivity['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectActivity['Project']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('milestone_id') ."</dt><dd>:". $this->Html->link($projectActivity['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectActivity['Milestone']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['title']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('details') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('estimated_cost') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['estimated_cost']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('start_date') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['start_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('end_date') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['end_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('sequence') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['sequence']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('current_status') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['current_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('branch_id') ."</dt><dd>:". $this->Html->link($projectActivity['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $projectActivity['Branch']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('users') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['users']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('user_session_id') ."</dt><dd>:". $this->Html->link($projectActivity['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $projectActivity['UserSession']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($projectActivity['ProjectActivity']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($projectActivity['ProjectActivity']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($projectActivity['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectActivity['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($projectActivity['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectActivity['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($projectActivity['Company']['name'], array('controller' => 'companies', 'action' => 'view', $projectActivity['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$projectActivity['ProjectActivity']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","sequence"=>"Sequence","current_status"=>"Current Status","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","sequence"=>"Sequence","current_status"=>"Current Status","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>