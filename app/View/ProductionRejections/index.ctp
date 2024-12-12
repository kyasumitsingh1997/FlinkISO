<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="productionRejections ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Production Rejections','modelClass'=>'ProductionRejection','options'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections"),'pluralVar'=>'productionRejections'))); ?>

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
				<th><?php echo $this->Paginator->sort('production_id'); ?></th>
				<th><?php echo $this->Paginator->sort('product_id'); ?></th>
				<th><?php echo $this->Paginator->sort('production_inspection_template_id'); ?></th>
				<th><?php echo $this->Paginator->sort('total_quantity'); ?></th>
				<th><?php echo $this->Paginator->sort('sample_quantity'); ?></th>
				<th><?php echo $this->Paginator->sort('quality_check_date'); ?></th>
				<th><?php echo $this->Paginator->sort('start_sr_number'); ?></th>
				<th><?php echo $this->Paginator->sort('end_sr_number'); ?></th>
				<th><?php echo $this->Paginator->sort('number_of_rejections'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('supplier_registration_id'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_contact_id'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($productionRejections){ ?>
<?php foreach ($productionRejections as $productionRejection): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $productionRejection['ProductionRejection']['created_by'], 'postVal' => $productionRejection['ProductionRejection']['id'], 'softDelete' => $productionRejection['ProductionRejection']['soft_delete'])); ?>	</td>		<td><?php echo h($productionRejection['ProductionRejection']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($productionRejection['Production']['batch_number'], array('controller' => 'productions', 'action' => 'view', $productionRejection['Production']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['Product']['name'], array('controller' => 'products', 'action' => 'view', $productionRejection['Product']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['ProductionInspectionTemplate']['name'], array('controller' => 'production_inspection_templates', 'action' => 'view', $productionRejection['ProductionInspectionTemplate']['id'])); ?>
		</td>
		<td><?php echo h($productionRejection['ProductionRejection']['total_quantity']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['sample_quantity']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['quality_check_date']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['start_sr_number']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['end_sr_number']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['number_of_rejections']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($productionRejection['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $productionRejection['Employee']['id'])); ?>
		</td>
		<!-- <td>
			<?php echo $this->Html->link($productionRejection['SupplierRegistration']['title'], array('controller' => 'supplier_registrations', 'action' => 'view', $productionRejection['SupplierRegistration']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['CustomerContact']['name'], array('controller' => 'customer_contacts', 'action' => 'view', $productionRejection['CustomerContact']['id'])); ?>
		</td> -->
		<td><?php echo h($PublishedEmployeeList[$productionRejection['ProductionRejection']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$productionRejection['ProductionRejection']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($productionRejection['ProductionRejection']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=90>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
