<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectOverallPlans ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Overall Plans','modelClass'=>'ProjectOverallPlan','options'=>array("sr_no"=>"Sr No","plan_type"=>"Plan Type","lot_process"=>"Lot Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours"),'pluralVar'=>'projectOverallPlans'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
				<th><?php echo $this->Paginator->sort('plan_type'); ?></th>
				<th><?php echo $this->Paginator->sort('lot_process'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_units'); ?></th>
				<th><?php echo $this->Paginator->sort('overall_metrics'); ?></th>
				<th><?php echo $this->Paginator->sort('start_date'); ?></th>
				<th><?php echo $this->Paginator->sort('end_date'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_resource'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_manhours'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($projectOverallPlans){ ?>
<?php foreach ($projectOverallPlans as $projectOverallPlan): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $projectOverallPlan['ProjectOverallPlan']['created_by'], 'postVal' => $projectOverallPlan['ProjectOverallPlan']['id'], 'softDelete' => $projectOverallPlan['ProjectOverallPlan']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($projectOverallPlan['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectOverallPlan['Project']['id'])); ?>
		</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['milestone_id']); ?>&nbsp;</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['plan_type']); ?>&nbsp;</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['lot_process']); ?>&nbsp;</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_units']); ?>&nbsp;</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['overall_metrics']); ?>&nbsp;</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['end_date']); ?>&nbsp;</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_resource']); ?>&nbsp;</td>
		<td><?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_manhours']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectOverallPlan['ProjectOverallPlan']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectOverallPlan['ProjectOverallPlan']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectOverallPlan['ProjectOverallPlan']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=78>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","plan_type"=>"Plan Type","lot_process"=>"Lot Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","plan_type"=>"Plan Type","lot_process"=>"Lot Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
