<?php echo $this->element('checkbox-script'); ?>
<div id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectActivities ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Activities','modelClass'=>'ProjectActivity','options'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","sequence"=>"Sequence","current_status"=>"Current Status","users"=>"Users"),'pluralVar'=>'projectActivities'))); ?>

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
				<th><?php echo $this->Paginator->sort('title'); ?></th>	
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('details'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_cost'); ?></th> -->
				<th><?php echo $this->Paginator->sort('start_date'); ?></th>
				<th><?php echo $this->Paginator->sort('end_date'); ?></th>
				<th><?php echo $this->Paginator->sort('sequence'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
				<th width="140"><?php echo __('Activity Requirments') ?></th>
				<th width="140"><?php echo __('Activity Tasks') ?></th>
				<!-- <th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('users'); ?></th>
				<th><?php echo $this->Paginator->sort('user_session_id'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($projectActivities){ ?>
<?php foreach ($projectActivities as $projectActivity): ?>
	<tr>
	<td class=" actions">	
		<?php echo $this->element('actions', array('created' => $projectActivity['ProjectActivity']['created_by'], 'postVal' => $projectActivity['ProjectActivity']['id'], 'softDelete' => $projectActivity['ProjectActivity']['soft_delete'])); ?>	</td>		
		<td><?php echo h($projectActivity['ProjectActivity']['title']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectActivity['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectActivity['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectActivity['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectActivity['Milestone']['id'])); ?>
		</td>
		<!-- <td><?php echo h($projectActivity['ProjectActivity']['details']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['estimated_cost']); ?>&nbsp;</td> -->
		<td><?php echo h($projectActivity['ProjectActivity']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['end_date']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['sequence']); ?>&nbsp;</td>
		<td><?php echo ($projectActivity['ProjectActivity']['current_status']? 'Close': 'Open'); ?>&nbsp;</td>
		
		<td>	

		<?php if(count($projectActivity['ProjectActivityRequirement']) == 0)$class = 'danger';
			else $class = 'info'; ?>
			<div class="btn-group">
				<button type="button" class="btn btn-<?php echo $class; ?> btn-xs activity_button" id="<?php echo $projectActivity['ProjectActivity']['project_id']; ?>">Requirments</button>
					<button type="button" class="btn btn-<?php echo $class; ?> btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class=""><?php echo count($projectActivity['ProjectActivityRequirement']);?></span>
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu">
					<?php
					if($projectActivity['ProjectActivityRequirement']){ 
						foreach ($projectActivity['ProjectActivityRequirement'] as $requirment_key => $requirment_value) {
							echo "<li>".$this->Html->link($requirment_value,array('controller'=>'project_activity_requirements',
							 'action'=>'edit',$requirment_key))."</li>";
						} ?>
						<li role="separator" class="divider"></li>
						<?php
					}?>
				    
				    <li><a href="#"><?php echo $this->Html->link('Add New Requirment',
				    	array('controller'=>'project_activity_requirements',
				    		'action'=>'lists',
				    		'project_activity_id'=>$projectActivity['ProjectActivity']['id']
				    		)); ?></a></li>
			  	</ul>
			</div>
		&nbsp;</td>

		<td>	

		<?php if(count($projectActivity['Task']) == 0)$class = 'danger';
			else $class = 'info'; ?>
			<div class="btn-group">
				<button type="button" class="btn btn-<?php echo $class; ?> btn-xs task_button" id="<?php echo $projectActivity['ProjectActivity']['id']; ?>">Tasks</button>
					<button type="button" class="btn btn-<?php echo $class; ?> btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class=""><?php echo count($projectActivity['Task']);?></span>
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu">
					<?php
					if($projectActivity['Task']){ 
						foreach ($projectActivity['Task'] as $task_key => $task_value) {
							echo "<li>".$this->Html->link($task_value,array('controller'=>'tasks',
							 'action'=>'edit',$task_key))."</li>";
						} ?>
						<li role="separator" class="divider"></li>
						<?php
					}?>
				    
				    <li><a href="#"><?php echo $this->Html->link('Add New Task',
				    	array('controller'=>'tasks',
				    		'action'=>'lists',
				    		'project_activity_id'=>$projectActivity['ProjectActivity']['id']
				    		)); ?></a></li>
			  	</ul>
			</div>
		&nbsp;</td>
		<!-- <td>
			<?php echo $this->Html->link($projectActivity['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $projectActivity['Branch']['id'])); ?>
		</td>
		<td><?php echo h($projectActivity['ProjectActivity']['users']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectActivity['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $projectActivity['UserSession']['id'])); ?>
		</td> -->
		<td><?php echo h($PublishedEmployeeList[$projectActivity['ProjectActivity']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectActivity['ProjectActivity']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectActivity['ProjectActivity']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=84>No results found</td></tr>
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
<script type="text/javascript">
	$(".activity_button").on('click',function(){
		$("#main").load('<?php echo Router::url('/', true); ?>project_activity_requirements/index/project_id:'+ this.id);
	});
	$(".task_button").on('click',function(){
		$("#main").load('<?php echo Router::url('/', true); ?>tasks/index/project_activity_id:'+ this.id);
	});
</script>
<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","sequence"=>"Sequence","current_status"=>"Current Status","users"=>"Users"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","sequence"=>"Sequence","current_status"=>"Current Status","users"=>"Users"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
