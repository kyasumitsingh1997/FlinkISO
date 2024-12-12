
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
	<div class="projectActivityRequirements ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Activity Requirements','modelClass'=>'ProjectActivityRequirement','options'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'projectActivityRequirements'))); ?>
	
		
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

			<?php foreach ($projectActivityRequirements as $projectActivityRequirement): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $projectActivityRequirement['ProjectActivityRequirement']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectActivityRequirement['ProjectActivityRequirement']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectActivityRequirement['ProjectActivityRequirement']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $projectActivityRequirement['ProjectActivityRequirement']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $projectActivityRequirement['ProjectActivityRequirement']['id']),array('class'=>''), __('Are you sure ?', $projectActivityRequirement['ProjectActivityRequirement']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('project_id') ."</dt><dd>:". $this->Html->link($projectActivityRequirement['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectActivityRequirement['Project']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('milestone_id') ."</dt><dd>:". $this->Html->link($projectActivityRequirement['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectActivityRequirement['Milestone']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('project_activity_id') ."</dt><dd>:". $this->Html->link($projectActivityRequirement['ProjectActivity']['title'], array('controller' => 'project_activities', 'action' => 'view', $projectActivityRequirement['ProjectActivity']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['title']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('details') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('manpower') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['manpower']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('manpower_Details') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['manpower_Details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('infrastructure') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['infrastructure']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('other') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['other']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('branch_id') ."</dt><dd>:". $this->Html->link($projectActivityRequirement['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $projectActivityRequirement['Branch']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('users') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['users']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('user_session_id') ."</dt><dd>:". $this->Html->link($projectActivityRequirement['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $projectActivityRequirement['UserSession']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($projectActivityRequirement['ProjectActivityRequirement']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($projectActivityRequirement['ProjectActivityRequirement']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($projectActivityRequirement['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectActivityRequirement['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($projectActivityRequirement['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectActivityRequirement['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($projectActivityRequirement['Company']['name'], array('controller' => 'companies', 'action' => 'view', $projectActivityRequirement['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$projectActivityRequirement['ProjectActivityRequirement']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>