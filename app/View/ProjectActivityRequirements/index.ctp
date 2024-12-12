<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectActivityRequirements ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Activity Requirements','modelClass'=>'ProjectActivityRequirement','options'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users"),'pluralVar'=>'projectActivityRequirements'))); ?>

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
				<th><?php echo $this->Paginator->sort('project_activity_id'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('details'); ?></th> -->
				<th><?php echo $this->Paginator->sort('manpower'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('manpower_Details'); ?></th>
				<th><?php echo $this->Paginator->sort('infrastructure'); ?></th>
				<th><?php echo $this->Paginator->sort('other'); ?></th>
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('users'); ?></th>
				<th><?php echo $this->Paginator->sort('user_session_id'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($projectActivityRequirements){ ?>
<?php foreach ($projectActivityRequirements as $projectActivityRequirement): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $projectActivityRequirement['ProjectActivityRequirement']['created_by'], 'postVal' => $projectActivityRequirement['ProjectActivityRequirement']['id'], 'softDelete' => $projectActivityRequirement['ProjectActivityRequirement']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($projectActivityRequirement['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectActivityRequirement['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectActivityRequirement['Milestone']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['ProjectActivity']['title'], array('controller' => 'project_activities', 'action' => 'view', $projectActivityRequirement['ProjectActivity']['id'])); ?>
		</td>
		<td><?php echo h($projectActivityRequirement['ProjectActivityRequirement']['title']); ?>&nbsp;</td>
		<!-- <td><?php echo h($projectActivityRequirement['ProjectActivityRequirement']['details']); ?>&nbsp;</td> -->
		<td><?php echo h($projectActivityRequirement['ProjectActivityRequirement']['manpower']); ?>&nbsp;</td>
		<!-- <td><?php echo h($projectActivityRequirement['ProjectActivityRequirement']['manpower_Details']); ?>&nbsp;</td>
		<td><?php echo h($projectActivityRequirement['ProjectActivityRequirement']['infrastructure']); ?>&nbsp;</td>
		<td><?php echo h($projectActivityRequirement['ProjectActivityRequirement']['other']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $projectActivityRequirement['Branch']['id'])); ?>
		</td>
		<td><?php echo h($projectActivityRequirement['ProjectActivityRequirement']['users']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectActivityRequirement['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $projectActivityRequirement['UserSession']['id'])); ?>
		</td> -->
		<td><?php echo h($PublishedEmployeeList[$projectActivityRequirement['ProjectActivityRequirement']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectActivityRequirement['ProjectActivityRequirement']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectActivityRequirement['ProjectActivityRequirement']['publish'] == 1) { ?>
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

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","manpower"=>"Manpower","manpower_Details"=>"Manpower Details","infrastructure"=>"Infrastructure","other"=>"Other","users"=>"Users"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
