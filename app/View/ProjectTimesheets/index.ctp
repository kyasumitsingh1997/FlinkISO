<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectTimesheets ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Timesheets','modelClass'=>'ProjectTimesheet','options'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost"),'pluralVar'=>'projectTimesheets'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_activity_id'); ?></th>
				<th><?php echo $this->Paginator->sort('start_time'); ?></th>
				<th><?php echo $this->Paginator->sort('end_time'); ?></th>
				<th><?php echo $this->Paginator->sort('activity_description'); ?></th>
				<th><?php echo $this->Paginator->sort('total_time'); ?></th>
				<th><?php echo $this->Paginator->sort('total_cost'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($projectTimesheets){ ?>
<?php foreach ($projectTimesheets as $projectTimesheet): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $projectTimesheet['ProjectTimesheet']['created_by'], 'postVal' => $projectTimesheet['ProjectTimesheet']['id'], 'softDelete' => $projectTimesheet['ProjectTimesheet']['soft_delete'])); ?>	</td>		
		<td>
			<?php echo $this->Html->link($projectTimesheet['User']['name'], array('controller' => 'users', 'action' => 'view', $projectTimesheet['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectTimesheet['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectTimesheet['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectTimesheet['ProjectActivity']['title'], array('controller' => 'project_activities', 'action' => 'view', $projectTimesheet['ProjectActivity']['id'])); ?>
		</td>
		<td><?php echo h($projectTimesheet['ProjectTimesheet']['start_time']); ?>&nbsp;</td>
		<td><?php echo h($projectTimesheet['ProjectTimesheet']['end_time']); ?>&nbsp;</td>
		<td><?php echo h($projectTimesheet['ProjectTimesheet']['activity_description']); ?>&nbsp;</td>
		<td><?php echo h($projectTimesheet['ProjectTimesheet']['total_time']); ?>&nbsp;</td>
		<td><?php echo h($projectTimesheet['ProjectTimesheet']['total_cost']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectTimesheet['ProjectTimesheet']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectTimesheet['ProjectTimesheet']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectTimesheet['ProjectTimesheet']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=72>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","start_time"=>"Start Time","end_time"=>"End Time","activity_description"=>"Activity Description","total_time"=>"Total Time","total_cost"=>"Total Cost"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
