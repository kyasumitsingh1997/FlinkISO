<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fileShares ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'File Shares','modelClass'=>'FileShare','options'=>array("sr_no"=>"Sr No","everyone"=>"Everyone","users"=>"Users"),'pluralVar'=>'fileShares'))); ?>

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
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('everyone'); ?></th>
				<th><?php echo $this->Paginator->sort('users'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('user_session_id'); ?></th> -->
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($fileShares){ ?>
<?php foreach ($fileShares as $fileShare): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $fileShare['FileShare']['created_by'], 'postVal' => $fileShare['FileShare']['id'], 'softDelete' => $fileShare['FileShare']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($fileShare['FileUpload']['name'], array('controller' => 'file_uploads', 'action' => 'view', $fileShare['FileUpload']['id'])); ?>
			<span class="label label-info"><?php echo $fileShare['FileUpload']['version'];?></span>
		</td>
		<td>
			<?php echo $this->Html->link($fileShare['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $fileShare['Branch']['id'])); ?>
		</td>
		<td><?php echo ($fileShare['FileShare']['everyone'])? 'Yes':'No'; ?>&nbsp;</td>
		<td>
			<?php 
				$user_shares = json_decode($fileShare['FileShare']['users']); 
				foreach ($user_shares as $user) {
					echo $users[$user];
				}
			?>&nbsp;</td>
		<!-- <td>
			<?php echo $this->Html->link($fileShare['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $fileShare['UserSession']['id'])); ?>
		</td> -->
		<td><?php echo h($PublishedEmployeeList[$fileShare['FileShare']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fileShare['FileShare']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($fileShare['FileShare']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","everyone"=>"Everyone","users"=>"Users"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","everyone"=>"Everyone","users"=>"Users"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
