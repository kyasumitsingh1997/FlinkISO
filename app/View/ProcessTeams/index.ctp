<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="processTeams ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Process Teams','modelClass'=>'ProcessTeam','options'=>array("sr_no"=>"Sr No","team"=>"Team","process_type"=>"Process Type","target"=>"Target","measurement_details"=>"Measurement Details","start_date"=>"Start Date","end_date"=>"End Date","system_table"=>"System Table"),'pluralVar'=>'processTeams'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('process_id'); ?></th>
				<th><?php echo $this->Paginator->sort('objective_id'); ?></th>
				<th><?php echo $this->Paginator->sort('team'); ?></th>
				<th><?php echo $this->Paginator->sort('process_type'); ?></th>
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('department_id'); ?></th>
				<th><?php echo $this->Paginator->sort('target'); ?></th>
				<th><?php echo $this->Paginator->sort('measurement_details'); ?></th>
				<th><?php echo $this->Paginator->sort('start_date'); ?></th>
				<th><?php echo $this->Paginator->sort('end_date'); ?></th>
				<th><?php echo $this->Paginator->sort('system_table'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($processTeams){ ?>
<?php foreach ($processTeams as $processTeam): ?>
	<tr class="on_page_src">
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $processTeam['ProcessTeam']['created_by'], 'postVal' => $processTeam['ProcessTeam']['id'], 'softDelete' => $processTeam['ProcessTeam']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($processTeam['Process']['title'], array('controller' => 'processes', 'action' => 'view', $processTeam['Process']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($processTeam['Objective']['title'], array('controller' => 'objectives', 'action' => 'view', $processTeam['Objective']['id'])); ?>
		</td>
		<td><?php echo h($processTeam['ProcessTeam']['team']); ?>&nbsp;</td>
		<td><?php echo h($processTeam['ProcessTeam']['process_type']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($processTeam['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $processTeam['Branch']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($processTeam['Department']['name'], array('controller' => 'departments', 'action' => 'view', $processTeam['Department']['id'])); ?>
		</td>
		<td><?php echo h($processTeam['ProcessTeam']['target']); ?>&nbsp;</td>
		<td><?php echo h($processTeam['ProcessTeam']['measurement_details']); ?>&nbsp;</td>
		<td><?php echo h($processTeam['ProcessTeam']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($processTeam['ProcessTeam']['end_date']); ?>&nbsp;</td>
		<td><?php echo h($processTeam['ProcessTeam']['system_table']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$processTeam['ProcessTeam']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$processTeam['ProcessTeam']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($processTeam['ProcessTeam']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","team"=>"Team","process_type"=>"Process Type","target"=>"Target","measurement_details"=>"Measurement Details","start_date"=>"Start Date","end_date"=>"End Date","system_table"=>"System Table"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","team"=>"Team","process_type"=>"Process Type","target"=>"Target","measurement_details"=>"Measurement Details","start_date"=>"Start Date","end_date"=>"End Date","system_table"=>"System Table"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>

</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
