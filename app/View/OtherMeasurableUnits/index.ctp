<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="otherMeasurableUnits ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Other Measurable Units','modelClass'=>'OtherMeasurableUnit','options'=>array("sr_no"=>"Sr No","unit_name"=>"Unit Name"),'pluralVar'=>'otherMeasurableUnits'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('unit_name'); ?></th>
				<th><?php echo $this->Paginator->sort('project_process_plan_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($otherMeasurableUnits){ ?>
<?php foreach ($otherMeasurableUnits as $otherMeasurableUnit): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $otherMeasurableUnit['OtherMeasurableUnit']['created_by'], 'postVal' => $otherMeasurableUnit['OtherMeasurableUnit']['id'], 'softDelete' => $otherMeasurableUnit['OtherMeasurableUnit']['soft_delete'])); ?>	</td>		<td><?php echo h($otherMeasurableUnit['OtherMeasurableUnit']['unit_name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($otherMeasurableUnit['ProjectProcessPlan']['id'], array('controller' => 'project_process_plans', 'action' => 'view', $otherMeasurableUnit['ProjectProcessPlan']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($otherMeasurableUnit['Project']['title'], array('controller' => 'projects', 'action' => 'view', $otherMeasurableUnit['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($otherMeasurableUnit['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $otherMeasurableUnit['Milestone']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$otherMeasurableUnit['OtherMeasurableUnit']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$otherMeasurableUnit['OtherMeasurableUnit']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($otherMeasurableUnit['OtherMeasurableUnit']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=60>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","unit_name"=>"Unit Name"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","unit_name"=>"Unit Name"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>