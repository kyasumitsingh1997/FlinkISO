<div id="productionWeeklyPlans_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="productionWeeklyPlans form col-md-8">
<h4><?php echo __('View Production Weekly Plan'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr>
		<td colspan="2">
			<h2><?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['name']); ?></h2>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['end_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Product'); ?></td>
		<td>
			<?php echo $this->Html->link($productionWeeklyPlan['Product']['name'], array('controller' => 'products', 'action' => 'view', $productionWeeklyPlan['Product']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Production Planned'); ?></td>
		<td>
			<?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['production_planned']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo h($currentStatus[$productionWeeklyPlan['ProductionWeeklyPlan']['current_status']]); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($productionWeeklyPlan['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($productionWeeklyPlan['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($productionWeeklyPlan['ProductionWeeklyPlan']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<h3><?php echo __('Production Batches');?></h3>
	<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
	    <tr>
	        <th><?php echo $this->Paginator->sort('production_date'); ?></th>
	        <th><?php echo $this->Paginator->sort('batch_number'); ?></th>
	        <th><?php echo $this->Paginator->sort('actual_production_number'); ?></th>
	        <th><?php echo $this->Paginator->sort('rejections'); ?></th>
	        <th><?php echo $this->Paginator->sort('branch_id'); ?></th>
	        <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
	        <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
	        <th><?php echo $this->Paginator->sort('current_status'); ?></th>
	        <th><?php echo $this->Paginator->sort('publish'); ?></th>
	    </tr>
	    <?php if ($productionWeeklyPlan['Production']) {
	            $x = 0;
	            foreach ($productionWeeklyPlan['Production'] as $production):
	    ?>
	    <tr class="on_page_src">
	        <td><?php echo h(date('d M Y',strtotime($production['production_date']))); ?>&nbsp;</td>
	        <td><?php echo h($production['batch_number']); ?>&nbsp;</td>
			<td><?php echo h($production['actual_production_number']); ?>&nbsp;</td>
	        <td><?php echo h($production['rejections']); ?>&nbsp;</td>

	        <td>
	            <?php echo $PublishedBranchList[$production['branch_id']]; ?>
	        </td>
	        <td><?php echo h($PublishedEmployeeList[$production['prepared_by']]); ?>&nbsp;</td>
	        <td><?php echo h($PublishedEmployeeList[$production['approved_by']]); ?>&nbsp;</td>
	        <td><?php echo h($currentStatus[$production['current_status']]); ?>&nbsp;</td>
	        
	        
	        <td width="60">
	            <?php if ($production['publish'] == 1) { ?>
	                <span class="fa fa-check"></span>
	            <?php } else { ?>
	                <span class="fa fa-ban"></span>
	            <?php } ?>&nbsp;</td>
	    </tr>
	    <?php
	    	$total = $total + $production['actual_production_number'];
	    	$rejections = $rejections + $production['rejections'];
	        $x++;
	        endforeach;
	        } else {
	    ?>
	    <tr><td colspan=19>No results found</td></tr>
	    <?php } ?>
	</table>
	<div class="row">
		<div class="col-md-6"><h2>Balance : <?php echo $productionWeeklyPlan['ProductionWeeklyPlan']['production_planned'] - $total;?></h2></div>
		<div class="col-md-6"><h2>Rejections : <?php echo $rejections;?></h2></div>
	</div>
	
	

<?php echo $this->element('upload-edit', array('usersId' => $productionWeeklyPlan['ProductionWeeklyPlan']['created_by'], 'recordId' => $productionWeeklyPlan['ProductionWeeklyPlan']['id'])); ?>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#productionWeeklyPlans_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$productionWeeklyPlan['ProductionWeeklyPlan']['id'] ,'ajax'),array('async' => true, 'update' => '#productionWeeklyPlans_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
