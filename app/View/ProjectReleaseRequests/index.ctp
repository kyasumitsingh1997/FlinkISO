<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectReleaseRequests ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Release Requests','modelClass'=>'ProjectReleaseRequest','options'=>array("sr_no"=>"Sr No","request_status"=>"Request Status"),'pluralVar'=>'projectReleaseRequests'))); ?>

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
					<th><?php echo $this->Paginator->sort('project_employee_id'); ?></th>
					<th><?php echo $this->Paginator->sort('current_project_id'); ?></th>
					<th><?php echo $this->Paginator->sort('new_project_id'); ?></th>
					<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
					<th><?php echo $this->Paginator->sort('request_from_id'); ?></th>
					<th><?php echo $this->Paginator->sort('request_status'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>						
				</tr>
				<?php if($projectReleaseRequests){ ?>
<?php foreach ($projectReleaseRequests as $projectReleaseRequest): ?>
	<tr>
		<td class=" actions">	
		<?php echo $this->element('actions', array('created' => $projectReleaseRequest['ProjectReleaseRequest']['created_by'], 'postVal' => $projectReleaseRequest['ProjectReleaseRequest']['id'], 'softDelete' => $projectReleaseRequest['ProjectReleaseRequest']['soft_delete'])); ?>	
		</td>		
		<td>
			<?php echo $this->Html->link($PublishedEmployeeList[$projectReleaseRequest['ProjectEmployee']['employee_id']], array('controller' => 'employees', 'action' => 'view', $projectReleaseRequest['ProjectEmployee']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectReleaseRequest['CurrentProject']['title'], array('controller' => 'projects', 'action' => 'view', $projectReleaseRequest['CurrentProject']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectReleaseRequest['NewProject']['title'], array('controller' => 'projects', 'action' => 'view', $projectReleaseRequest['NewProject']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectReleaseRequest['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $projectReleaseRequest['Employee']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectReleaseRequest['RequestFrom']['name'], array('controller' => 'employees', 'action' => 'view', $projectReleaseRequest['RequestFrom']['id'])); ?>
		</td>
		<td><?php echo h($requestStatuses[$projectReleaseRequest['ProjectReleaseRequest']['request_status']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectReleaseRequest['ProjectReleaseRequest']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectReleaseRequest['ProjectReleaseRequest']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectReleaseRequest['ProjectReleaseRequest']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=63>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","request_status"=>"Request Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","request_status"=>"Request Status"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
