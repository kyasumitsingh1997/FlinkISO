<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="employeeKras ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Employee Kras','modelClass'=>'EmployeeKra','options'=>array("sr_no"=>"Sr No","title"=>"Title","description"=>"Description","target"=>"Target","target_achieved"=>"Target Achieved","final_rating"=>"Final Rating"),'pluralVar'=>'employeeKras'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('description'); ?></th>
				<th><?php echo $this->Paginator->sort('target'); ?></th>
				<th><?php echo $this->Paginator->sort('target_achieved'); ?></th>
				<th><?php echo $this->Paginator->sort('final_rating'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($employeeKras){ ?>
<?php foreach ($employeeKras as $employeeKra): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $employeeKra['EmployeeKra']['created_by'], 'postVal' => $employeeKra['EmployeeKra']['id'], 'softDelete' => $employeeKra['EmployeeKra']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($employeeKra['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $employeeKra['Employee']['id'])); ?>
		</td>
		<td><?php echo h($employeeKra['EmployeeKra']['title']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['description']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['target']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['target_achieved']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['final_rating']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($employeeKra['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $employeeKra['Division']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$employeeKra['EmployeeKra']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$employeeKra['EmployeeKra']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($employeeKra['EmployeeKra']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","description"=>"Description","target"=>"Target","target_achieved"=>"Target Achieved","final_rating"=>"Final Rating"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","description"=>"Description","target"=>"Target","target_achieved"=>"Target Achieved","final_rating"=>"Final Rating"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
