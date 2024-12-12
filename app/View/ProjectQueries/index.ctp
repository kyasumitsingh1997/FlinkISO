<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectQueries ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Queries','modelClass'=>'ProjectQuery','options'=>array("sr_no"=>"Sr No","name"=>"Name","sent_to"=>"Sent To","query"=>"Query","current_status"=>"Current Status"),'pluralVar'=>'projectQueries'))); ?>

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
				<th><?php echo $this->Paginator->sort('query_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_file_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_process_plan_id'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('sent_to'); ?></th>
				<th><?php echo $this->Paginator->sort('query'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($projectQueries){ ?>
<?php foreach ($projectQueries as $projectQuery): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $projectQuery['ProjectQuery']['created_by'], 'postVal' => $projectQuery['ProjectQuery']['id'], 'softDelete' => $projectQuery['ProjectQuery']['soft_delete'])); ?>	</td>		<td><?php echo h($projectQuery['ProjectQuery']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectQuery['QueryType']['name'], array('controller' => 'query_types', 'action' => 'view', $projectQuery['QueryType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectQuery['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectQuery['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectQuery['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectQuery['Milestone']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectQuery['ProjectFile']['name'], array('controller' => 'project_files', 'action' => 'view', $projectQuery['ProjectFile']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectQuery['ProjectProcessPlan']['id'], array('controller' => 'project_process_plans', 'action' => 'view', $projectQuery['ProjectProcessPlan']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectQuery['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $projectQuery['Employee']['id'])); ?>
		</td>
		<td><?php echo h($projectQuery['ProjectQuery']['sent_to']); ?>&nbsp;</td>
		<td><?php echo h($projectQuery['ProjectQuery']['query']); ?>&nbsp;</td>
		<td><?php echo h($projectQuery['ProjectQuery']['current_status']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectQuery['ProjectQuery']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectQuery['ProjectQuery']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectQuery['ProjectQuery']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","sent_to"=>"Sent To","query"=>"Query","current_status"=>"Current Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","sent_to"=>"Sent To","query"=>"Query","current_status"=>"Current Status"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
