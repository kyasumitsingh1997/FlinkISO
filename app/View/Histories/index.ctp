

<?php echo $this->element('checkbox-script'); ?>

<div  id="main">
<?php echo $this->Session->flash();?>
	<div class="histories ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Histories','modelClass'=>'History','options'=>array("sr_no"=>"Sr No","model_name"=>"Model Name","controller_name"=>"Controller Name","action"=>"Action","get_values"=>"Get Values","post_values"=>"Post Values"),'pluralVar'=>'histories'))); ?>

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
					<th><input type="checkbox" id="selectAll"></th>
					<th><?php echo $this->Paginator->sort('model_name'); ?></th>
					<th><?php echo $this->Paginator->sort('controller_name'); ?></th>
					<th><?php echo $this->Paginator->sort('action'); ?></th>
					<th><?php echo $this->Paginator->sort('get_values'); ?></th>
					<th><?php echo $this->Paginator->sort('post_values'); ?></th>
					<th><?php echo $this->Paginator->sort('user_session_id'); ?></th>
					<th><?php echo $this->Paginator->sort('branch_id', __('Branch')); ?></th>
					<th><?php echo $this->Paginator->sort('department_id'); ?></th>
					<th><?php echo $this->Paginator->sort('publish', __('Publish')); ?></th>
				</tr>
				<?php if($histories){ ?>
<?php $x=0;
 foreach ($histories as $history): ?>
	<tr>

		<td class=" actions">

		<?php echo  $this->element('actions',array('created'=>$history['History']['created_by'],'postVal'=>$history['History']['id'], 'softDelete'=>$history['History']['soft_delete'])); ?>

		</td>
		<td><?php echo h($history['History']['model_name']); ?>&nbsp;</td>
		<td><?php echo h($history['History']['controller_name']); ?>&nbsp;</td>
		<td><?php echo h($history['History']['action']); ?>&nbsp;</td>
		<td><?php echo h($history['History']['get_values']); ?>&nbsp;</td>
		<td><?php echo h($history['History']['post_values']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($history['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $history['UserSession']['id'])); ?>
		</td>
		<td><?php echo h($history['History']['branch_id']); ?>&nbsp;</td>
		<td><?php echo h($history['History']['department_id']); ?>&nbsp;</td>

		<td width="60">
			<?php if($history['History']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php $x++;
 endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=20><?php echo __('No results found');?></td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","model_name"=>"Model Name","controller_name"=>"Controller Name","action"=>"Action","get_values"=>"Get Values","post_values"=>"Post Values"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","model_name"=>"Model Name","controller_name"=>"Controller Name","action"=>"Action","get_values"=>"Get Values","post_values"=>"Post Values"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>
<?php echo $this->Js->writeBuffer();?>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>