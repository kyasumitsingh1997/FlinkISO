<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fileViews ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'File Views','modelClass'=>'FileView','options'=>array("sr_no"=>"Sr No"),'pluralVar'=>'fileViews'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('file_upload_id'); ?></th>
				<th><?php echo $this->Paginator->sort('user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('user_session_id'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($fileViews){ ?>
<?php foreach ($fileViews as $fileView): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $fileView['FileView']['created_by'], 'postVal' => $fileView['FileView']['id'], 'softDelete' => $fileView['FileView']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($fileView['FileUpload']['name'], array('controller' => 'file_uploads', 'action' => 'view', $fileView['FileUpload']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fileView['User']['name'], array('controller' => 'users', 'action' => 'view', $fileView['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fileView['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $fileView['UserSession']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fileView['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $fileView['Division']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$fileView['FileView']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fileView['FileView']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($fileView['FileView']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=57>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
