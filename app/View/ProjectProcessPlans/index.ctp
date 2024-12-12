<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectProcessPlans ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Process Plans','modelClass'=>'ProjectProcessPlan','options'=>array("sr_no"=>"Sr No","process"=>"Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours"),'pluralVar'=>'projectProcessPlans'))); ?>

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
				<th><?php echo $this->Paginator->sort('project_overall_plan_id'); ?></th>
				<th><?php echo $this->Paginator->sort('process'); ?></th>
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
				<?php if($projectProcessPlans){ ?>
<?php foreach ($projectProcessPlans as $projectProcessPlan): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $projectProcessPlan['ProjectProcessPlan']['created_by'], 'postVal' => $projectProcessPlan['ProjectProcessPlan']['id'], 'softDelete' => $projectProcessPlan['ProjectProcessPlan']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($projectProcessPlan['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectProcessPlan['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectProcessPlan['Milestone']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['ProjectOverallPlan']['id'], array('controller' => 'project_overall_plans', 'action' => 'view', $projectProcessPlan['ProjectOverallPlan']['id'])); ?>
		</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['process']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_units']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['overall_metrics']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['end_date']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_resource']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_manhours']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectProcessPlan['ProjectProcessPlan']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectProcessPlan['ProjectProcessPlan']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectProcessPlan['ProjectProcessPlan']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","process"=>"Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","process"=>"Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
