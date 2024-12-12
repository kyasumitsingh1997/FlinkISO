<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectFiles ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Files','modelClass'=>'ProjectFile','options'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By"),'pluralVar'=>'projectFiles'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('assigned_date'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_time'); ?></th>
				<th><?php echo $this->Paginator->sort('completed_date'); ?></th>
				<th><?php echo $this->Paginator->sort('start_date'); ?></th>
				<th><?php echo $this->Paginator->sort('end_date'); ?></th>
				<th><?php echo $this->Paginator->sort('actual_time'); ?></th>
				<th><?php echo $this->Paginator->sort('comments'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
				<th><?php echo $this->Paginator->sort('checked_by'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($projectFiles){ ?>
<?php foreach ($projectFiles as $projectFile): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $projectFile['ProjectFile']['created_by'], 'postVal' => $projectFile['ProjectFile']['id'], 'softDelete' => $projectFile['ProjectFile']['soft_delete'])); ?>	</td>		<td><?php echo h($projectFile['ProjectFile']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectFile['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectFile['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectFile['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectFile['Milestone']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectFile['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $projectFile['Employee']['id'])); ?>
		</td>
		<td><?php echo h($projectFile['ProjectFile']['assigned_date']); ?>&nbsp;</td>
		<td><?php echo h($projectFile['ProjectFile']['estimated_time']); ?>&nbsp;</td>
		<td><?php echo h($projectFile['ProjectFile']['completed_date']); ?>&nbsp;</td>
		<td><?php echo h($projectFile['ProjectFile']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($projectFile['ProjectFile']['end_date']); ?>&nbsp;</td>
		<td><?php echo h($projectFile['ProjectFile']['actual_time']); ?>&nbsp;</td>
		<td><?php echo h($projectFile['ProjectFile']['comments']); ?>&nbsp;</td>
		<td><?php echo h($projectFile['ProjectFile']['current_status']); ?>&nbsp;</td>
		<td><?php echo h($projectFile['ProjectFile']['checked_by']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectFile['ProjectFile']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectFile['ProjectFile']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectFile['ProjectFile']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=87>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","assigned_date"=>"Assigned Date","estimated_time"=>"Estimated Time","completed_date"=>"Completed Date","start_date"=>"Start Date","end_date"=>"End Date","actual_time"=>"Actual Time","comments"=>"Comments","current_status"=>"Current Status","checked_by"=>"Checked By"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
