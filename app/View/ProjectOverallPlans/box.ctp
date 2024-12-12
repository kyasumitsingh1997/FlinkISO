
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
	<div class="projectOverallPlans ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Overall Plans','modelClass'=>'ProjectOverallPlan','options'=>array("sr_no"=>"Sr No","plan_type"=>"Plan Type","lot_process"=>"Lot Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'projectOverallPlans'))); ?>
	
		
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

			<?php foreach ($projectOverallPlans as $projectOverallPlan): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $projectOverallPlan['ProjectOverallPlan']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectOverallPlan['ProjectOverallPlan']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectOverallPlan['ProjectOverallPlan']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $projectOverallPlan['ProjectOverallPlan']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $projectOverallPlan['ProjectOverallPlan']['id']),array('class'=>''), __('Are you sure ?', $projectOverallPlan['ProjectOverallPlan']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('project_id') ."</dt><dd>:". $this->Html->link($projectOverallPlan['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectOverallPlan['Project']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('milestone_id') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['milestone_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('plan_type') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['plan_type']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('lot_process') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['lot_process']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('estimated_units') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['estimated_units']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('overall_metrics') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['overall_metrics']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('start_date') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['start_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('end_date') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['end_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('estimated_resource') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['estimated_resource']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('estimated_manhours') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['estimated_manhours']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($projectOverallPlan['ProjectOverallPlan']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($projectOverallPlan['ProjectOverallPlan']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($projectOverallPlan['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectOverallPlan['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($projectOverallPlan['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectOverallPlan['ApprovedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$projectOverallPlan['ProjectOverallPlan']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","plan_type"=>"Plan Type","lot_process"=>"Lot Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","plan_type"=>"Plan Type","lot_process"=>"Lot Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>