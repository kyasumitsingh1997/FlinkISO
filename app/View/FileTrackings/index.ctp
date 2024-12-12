<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fileTrackings ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'File Trackings','modelClass'=>'FileTracking','options'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment"),'pluralVar'=>'fileTrackings'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('project_file_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
				<th><?php echo $this->Paginator->sort('from'); ?></th>
				<th><?php echo $this->Paginator->sort('to'); ?></th>
				<th><?php echo $this->Paginator->sort('by'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
				<th><?php echo $this->Paginator->sort('changed_on'); ?></th>
				<th><?php echo $this->Paginator->sort('changetype'); ?></th>
				<th><?php echo $this->Paginator->sort('function'); ?></th>
				<th><?php echo $this->Paginator->sort('comment'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($fileTrackings){ ?>
<?php foreach ($fileTrackings as $fileTracking): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $fileTracking['FileTracking']['created_by'], 'postVal' => $fileTracking['FileTracking']['id'], 'softDelete' => $fileTracking['FileTracking']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($fileTracking['ProjectFile']['name'], array('controller' => 'project_files', 'action' => 'view', $fileTracking['ProjectFile']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fileTracking['Project']['title'], array('controller' => 'projects', 'action' => 'view', $fileTracking['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fileTracking['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $fileTracking['Milestone']['id'])); ?>
		</td>
		<td><?php echo h($fileTracking['FileTracking']['from']); ?>&nbsp;</td>
		<td><?php echo h($fileTracking['To']['name']); ?>&nbsp;</td>
		<td><?php echo h($fileTracking['By']['name']); ?>&nbsp;</td>
		<td><?php echo h($fileTracking['FileTracking']['current_status']); ?>&nbsp;</td>
		<td><?php echo h($fileTracking['FileTracking']['changed_on']); ?>&nbsp;</td>
		<td><?php echo h($fileTracking['FileTracking']['changetype']); ?>&nbsp;</td>
		<td><?php echo h($fileTracking['FileTracking']['function']); ?>&nbsp;</td>
		<td><?php echo h($fileTracking['FileTracking']['comment']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fileTracking['FileTracking']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fileTracking['FileTracking']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($fileTracking['FileTracking']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=81>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
