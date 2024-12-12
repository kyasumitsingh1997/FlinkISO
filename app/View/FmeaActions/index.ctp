<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fmeaActions ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Fmea Actions','modelClass'=>'FmeaAction','options'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn"),'pluralVar'=>'fmeaActions'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('fmea_id'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('actions_recommended'); ?></th>
				<th><?php echo $this->Paginator->sort('target_date'); ?></th>
				<th><?php echo $this->Paginator->sort('action_taken'); ?></th>
				<th><?php echo $this->Paginator->sort('action_taken_date'); ?></th>
				<th><?php echo $this->Paginator->sort('fmea_severity_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('fmea_occurence_id'); ?></th>
				<th><?php echo $this->Paginator->sort('fmea_detection_id'); ?></th>
				<th><?php echo $this->Paginator->sort('rpn'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($fmeaActions){ ?>
<?php foreach ($fmeaActions as $fmeaAction): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $fmeaAction['FmeaAction']['created_by'], 'postVal' => $fmeaAction['FmeaAction']['id'], 'softDelete' => $fmeaAction['FmeaAction']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($fmeaAction['Fmea']['name'], array('controller' => 'fmeas', 'action' => 'view', $fmeaAction['Fmea']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fmeaAction['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $fmeaAction['Employee']['id'])); ?>
		</td>
		<td><?php echo h($fmeaAction['FmeaAction']['actions_recommended']); ?>&nbsp;</td>
		<td><?php echo h($fmeaAction['FmeaAction']['target_date']); ?>&nbsp;</td>
		<td><?php echo h($fmeaAction['FmeaAction']['action_taken']); ?>&nbsp;</td>
		<td><?php echo h($fmeaAction['FmeaAction']['action_taken_date']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($fmeaAction['FmeaSeverityType']['effect'], array('controller' => 'fmea_severity_types', 'action' => 'view', $fmeaAction['FmeaSeverityType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fmeaAction['FmeaOccurence']['probability_of_failure'], array('controller' => 'fmea_occurences', 'action' => 'view', $fmeaAction['FmeaOccurence']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fmeaAction['FmeaDetection']['detection'], array('controller' => 'fmea_detections', 'action' => 'view', $fmeaAction['FmeaDetection']['id'])); ?>
		</td>
		<td><?php echo h($fmeaAction['FmeaAction']['rpn']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fmeaAction['FmeaAction']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fmeaAction['FmeaAction']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($fmeaAction['FmeaAction']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
