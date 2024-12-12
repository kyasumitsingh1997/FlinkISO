<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fileErrors ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'File Errors','modelClass'=>'FileError','options'=>array("sr_no"=>"Sr No","name"=>"Name","total_units"=>"Total Units","total_errors"=>"Total Errors"),'pluralVar'=>'fileErrors'))); ?>

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
				<th><?php echo $this->Paginator->sort('project_file_id'); ?></th>
				<th><?php echo $this->Paginator->sort('file_process_id'); ?></th>
				<th><?php echo $this->Paginator->sort('file_error_master_id'); ?></th>
				<th><?php echo $this->Paginator->sort('total_units'); ?></th>
				<th><?php echo $this->Paginator->sort('total_errors'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($fileErrors){ ?>
<?php foreach ($fileErrors as $fileError): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $fileError['FileError']['created_by'], 'postVal' => $fileError['FileError']['id'], 'softDelete' => $fileError['FileError']['soft_delete'])); ?>	</td>		<td><?php echo h($fileError['FileError']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($fileError['Project']['title'], array('controller' => 'projects', 'action' => 'view', $fileError['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fileError['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $fileError['Milestone']['id'])); ?>
		</td>
		<td><?php echo h($fileError['FileError']['project_file_id']); ?>&nbsp;</td>
		<td><?php echo h($fileError['FileError']['file_process_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($fileError['FileErrorMaster']['name'], array('controller' => 'file_error_masters', 'action' => 'view', $fileError['FileErrorMaster']['id'])); ?>
		</td>
		<td><?php echo h($fileError['FileError']['total_units']); ?>&nbsp;</td>
		<td><?php echo h($fileError['FileError']['total_errors']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fileError['FileError']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fileError['FileError']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($fileError['FileError']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_units"=>"Total Units","total_errors"=>"Total Errors"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_units"=>"Total Units","total_errors"=>"Total Errors"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
