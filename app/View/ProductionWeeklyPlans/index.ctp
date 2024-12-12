<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="productionWeeklyPlans ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Production Weekly Plans','modelClass'=>'ProductionWeeklyPlan','options'=>array("sr_no"=>"Sr No","week"=>"Week","production_planned"=>"Production Planned"),'pluralVar'=>'productionWeeklyPlans'))); ?>

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
					<th><?php echo $this->Paginator->sort('start_date'); ?></th>
					<th><?php echo $this->Paginator->sort('end_date'); ?></th>
					<th><?php echo $this->Paginator->sort('product_id'); ?></th>
					<th><?php echo $this->Paginator->sort('production_planned'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
					<th><?php echo $this->Paginator->sort('current_status'); ?></th>
					<th></th>
					<th><?php echo $this->Paginator->sort('publish'); ?></th>
				</tr>
		<?php if($productionWeeklyPlans){ ?>
			<?php foreach ($productionWeeklyPlans as $productionWeeklyPlan): ?>
				<tr>
					<td class=" actions">	
						<?php echo $this->element('actions', array('created' => $productionWeeklyPlan['ProductionWeeklyPlan']['created_by'], 'postVal' => $productionWeeklyPlan['ProductionWeeklyPlan']['id'], 'softDelete' => $productionWeeklyPlan['ProductionWeeklyPlan']['soft_delete'])); ?>	
					</td>		
					<td><?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['name']); ?>&nbsp;</td>
					<td><?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['start_date']); ?>&nbsp;</td>
					<td><?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['end_date']); ?>&nbsp;</td>
					<td>
						<?php echo $this->Html->link($productionWeeklyPlan['Product']['name'], array('controller' => 'products', 'action' => 'view', $productionWeeklyPlan['Product']['id'])); ?>
					</td>
					<td><?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['production_planned']); ?>&nbsp;</td>
					<td><?php echo h($PublishedEmployeeList[$productionWeeklyPlan['ProductionWeeklyPlan']['prepared_by']]); ?>&nbsp;</td>
					<td><?php echo h($PublishedEmployeeList[$productionWeeklyPlan['ProductionWeeklyPlan']['approved_by']]); ?>&nbsp;</td>
					<td><?php echo h($currentStatus[$productionWeeklyPlan['ProductionWeeklyPlan']['current_status']]); ?>&nbsp;</td>
					<td><?php echo $this->Html->link('Add Batch',array('controller'=>'productions','action'=>'lists','product_id'=>$productionWeeklyPlan['Product']['id'],'production_weekly_plan_id'=>$productionWeeklyPlan['ProductionWeeklyPlan']['id']),array('class'=>'btn btn-xs btn-info'));?></td>
					<td width="60">
						<?php if($productionWeeklyPlan['ProductionWeeklyPlan']['publish'] == 1) { ?>
						<span class="fa fa-check"></span>
						<?php } else { ?>
						<span class="fa fa-ban"></span>
						<?php } ?>&nbsp;
					</td>
				</tr>
			<?php endforeach; ?>
		<?php }else{ ?>
			<tr><td colspan=60>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","week"=>"Week","production_planned"=>"Production Planned"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","week"=>"Week","production_planned"=>"Production Planned"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
