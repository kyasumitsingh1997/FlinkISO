<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectPayments ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Payments','modelClass'=>'ProjectPayment','options'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","unit"=>"Unit","received_date"=>"Received Date"),'pluralVar'=>'projectPayments'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
				<th><?php echo $this->Paginator->sort('purchase_order_id'); ?></th>
				<th><?php echo $this->Paginator->sort('invoice_id'); ?></th>
				<th><?php echo $this->Paginator->sort('amount'); ?></th>
				<th><?php echo $this->Paginator->sort('amount_received'); ?></th>
				<th><?php echo $this->Paginator->sort('unit'); ?></th>
				<th><?php echo $this->Paginator->sort('received_date'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($projectPayments){ ?>
<?php foreach ($projectPayments as $projectPayment): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $projectPayment['ProjectPayment']['created_by'], 'postVal' => $projectPayment['ProjectPayment']['id'], 'softDelete' => $projectPayment['ProjectPayment']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($projectPayment['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectPayment['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectPayment['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectPayment['Milestone']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectPayment['PurchaseOrder']['name'], array('controller' => 'purchase_orders', 'action' => 'view', $projectPayment['PurchaseOrder']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectPayment['Invoice']['id'], array('controller' => 'invoices', 'action' => 'view', $projectPayment['Invoice']['id'])); ?>
		</td>
		<td><?php echo h($projectPayment['ProjectPayment']['amount']); ?>&nbsp;</td>
		<td><?php echo h($projectPayment['ProjectPayment']['amount_received']); ?>&nbsp;</td>
		<td><?php echo h($projectPayment['ProjectPayment']['unit']); ?>&nbsp;</td>
		<td><?php echo h($projectPayment['ProjectPayment']['received_date']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectPayment['ProjectPayment']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$projectPayment['ProjectPayment']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectPayment['ProjectPayment']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","unit"=>"Unit","received_date"=>"Received Date"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","unit"=>"Unit","received_date"=>"Received Date"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
