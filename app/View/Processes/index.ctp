<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="processes ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Processes','modelClass'=>'Process','options'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","process_requirments"=>"Process Requirments"),'pluralVar'=>'processes'))); ?>

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
				<th><?php echo $this->Paginator->sort('objective_id'); ?></th>
				<th><?php echo $this->Paginator->sort('clauses'); ?></th>
				<th><?php echo $this->Paginator->sort('schedule_id','Monitoring Schedule'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th style="width:120px"><?php echo __('Tasks'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($processes){ ?>
<?php foreach ($processes as $process): ?>
	<tr class="on_page_src">
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $process['Process']['created_by'], 'postVal' => $process['Process']['id'], 'softDelete' => $process['Process']['soft_delete'])); ?>	</td>		<td><?php echo h($process['Process']['title']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($process['Objective']['title'], array('controller' => 'objectives', 'action' => 'view', $process['Objective']['id'])); ?>
		</td>
		<td><?php echo h($process['Process']['clauses']); ?>&nbsp;</td>		
		<td>
			<?php echo $this->Html->link($process['Schedule']['name'], array('controller' => 'schedules', 'action' => 'view', $process['Schedule']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$process['Process']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$process['Process']['approved_by']]); ?>&nbsp;</td>
		<td>
			<div class="btn-group">
				<?php echo $this->Html->link('Add Tasks',array('controller'=>'tasks','action'=>'lists','process_id'=>$process['Process']['id'],'objective_id'=>$process['Process']['objective_id']),array('class'=>'btn btn-info btn-xs')); ?>
				<?php if($process['TaskCount'] == 0){ ?>
					<span class="btn btn-xs btn-danger"><?php echo $process['TaskCount']; ?></span>
				<?php }else{ ?> 
					<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $process['TaskCount']; ?>					
    			</button>
					<ul class="dropdown-menu">
						<?php foreach ($process['Tasks'] as $taskKey => $taskName) {
							echo "<li>" . $this->Html->link($taskName , array('controller'=>'tasks','action'=>'view' ,$taskKey)) . "</li>";
						} ?>					    
					  </ul>
				<?php } ?>
				
			</div>
		</td>

		<td width="60">
			<?php if($process['Process']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","process_requirments"=>"Process Requirments"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","process_requirments"=>"Process Requirments"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>

</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
