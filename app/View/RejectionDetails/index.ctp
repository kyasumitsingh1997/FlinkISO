<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="rejectionDetails ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Rejection Details','modelClass'=>'RejectionDetail','options'=>array("sr_no"=>"Sr No"),'pluralVar'=>'rejectionDetails'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('production_rejection_id'); ?></th>
				<th><?php echo $this->Paginator->sort('value_driver_id'); ?></th>
				<th><?php echo $this->Paginator->sort('defect_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('performance_indicator_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($rejectionDetails){ ?>
<?php foreach ($rejectionDetails as $rejectionDetail): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $rejectionDetail['RejectionDetail']['created_by'], 'postVal' => $rejectionDetail['RejectionDetail']['id'], 'softDelete' => $rejectionDetail['RejectionDetail']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($rejectionDetail['ProductionRejection']['name'], array('controller' => 'production_rejections', 'action' => 'view', $rejectionDetail['ProductionRejection']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($rejectionDetail['ValueDriver']['name'], array('controller' => 'value_drivers', 'action' => 'view', $rejectionDetail['ValueDriver']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($rejectionDetail['DefectType']['name'], array('controller' => 'defect_types', 'action' => 'view', $rejectionDetail['DefectType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($rejectionDetail['PerformanceIndicator']['name'], array('controller' => 'performance_indicators', 'action' => 'view', $rejectionDetail['PerformanceIndicator']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$rejectionDetail['RejectionDetail']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$rejectionDetail['RejectionDetail']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($rejectionDetail['RejectionDetail']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
