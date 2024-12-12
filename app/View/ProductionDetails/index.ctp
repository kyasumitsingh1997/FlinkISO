<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="productionDetails ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Production Details','modelClass'=>'ProductionDetail','options'=>array("sr_no"=>"Sr No","week"=>"Week","production_accepted"=>"Production Accepted","production_rejected"=>"Production Rejected"),'pluralVar'=>'productionDetails'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('week'); ?></th>
				<th><?php echo $this->Paginator->sort('unit_id'); ?></th>
				<th><?php echo $this->Paginator->sort('production_id'); ?></th>
				<th><?php echo $this->Paginator->sort('value_driver_id'); ?></th>
				<th><?php echo $this->Paginator->sort('performance_indicator_id'); ?></th>
				<th><?php echo $this->Paginator->sort('production_accepted'); ?></th>
				<th><?php echo $this->Paginator->sort('production_rejected'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($productionDetails){ ?>
<?php foreach ($productionDetails as $productionDetail): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $productionDetail['ProductionDetail']['created_by'], 'postVal' => $productionDetail['ProductionDetail']['id'], 'softDelete' => $productionDetail['ProductionDetail']['soft_delete'])); ?>	</td>		<td><?php echo h($productionDetail['ProductionDetail']['week']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($productionDetail['Unit']['name'], array('controller' => 'units', 'action' => 'view', $productionDetail['Unit']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionDetail['Production']['batch_number'], array('controller' => 'productions', 'action' => 'view', $productionDetail['Production']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionDetail['ValueDriver']['name'], array('controller' => 'value_drivers', 'action' => 'view', $productionDetail['ValueDriver']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionDetail['PerformanceIndicator']['name'], array('controller' => 'performance_indicators', 'action' => 'view', $productionDetail['PerformanceIndicator']['id'])); ?>
		</td>
		<td><?php echo h($productionDetail['ProductionDetail']['production_accepted']); ?>&nbsp;</td>
		<td><?php echo h($productionDetail['ProductionDetail']['production_rejected']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$productionDetail['ProductionDetail']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$productionDetail['ProductionDetail']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($productionDetail['ProductionDetail']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","week"=>"Week","production_accepted"=>"Production Accepted","production_rejected"=>"Production Rejected"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","week"=>"Week","production_accepted"=>"Production Accepted","production_rejected"=>"Production Rejected"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
