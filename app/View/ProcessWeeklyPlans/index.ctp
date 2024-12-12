<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="processWeeklyPlans ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Process Weekly Plans','modelClass'=>'ProcessWeeklyPlan','options'=>array("sr_no"=>"Sr No","year"=>"Year","week"=>"Week","planned"=>"Planned"),'pluralVar'=>'processWeeklyPlans'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('project_process_plan_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
				<th><?php echo $this->Paginator->sort('year'); ?></th>
				<th><?php echo $this->Paginator->sort('week'); ?></th>
				<th><?php echo $this->Paginator->sort('planned'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($processWeeklyPlans){ ?>
<?php foreach ($processWeeklyPlans as $processWeeklyPlan): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $processWeeklyPlan['ProcessWeeklyPlan']['created_by'], 'postVal' => $processWeeklyPlan['ProcessWeeklyPlan']['id'], 'softDelete' => $processWeeklyPlan['ProcessWeeklyPlan']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($processWeeklyPlan['ProjectProcessPlan']['id'], array('controller' => 'project_process_plans', 'action' => 'view', $processWeeklyPlan['ProjectProcessPlan']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($processWeeklyPlan['Project']['title'], array('controller' => 'projects', 'action' => 'view', $processWeeklyPlan['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($processWeeklyPlan['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $processWeeklyPlan['Milestone']['id'])); ?>
		</td>
		<td><?php echo h($processWeeklyPlan['ProcessWeeklyPlan']['year']); ?>&nbsp;</td>
		<td><?php echo h($processWeeklyPlan['ProcessWeeklyPlan']['week']); ?>&nbsp;</td>
		<td><?php echo h($processWeeklyPlan['ProcessWeeklyPlan']['planned']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$processWeeklyPlan['ProcessWeeklyPlan']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$processWeeklyPlan['ProcessWeeklyPlan']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($processWeeklyPlan['ProcessWeeklyPlan']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=66>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","year"=>"Year","week"=>"Week","planned"=>"Planned"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","year"=>"Year","week"=>"Week","planned"=>"Planned"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
