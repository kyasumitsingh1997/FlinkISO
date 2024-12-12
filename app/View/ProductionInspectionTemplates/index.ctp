<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="productionInspectionTemplates ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Production Inspection Templates','modelClass'=>'ProductionInspectionTemplate','options'=>array("sr_no"=>"Sr No","name"=>"Name","template"=>"Template"),'pluralVar'=>'productionInspectionTemplates'))); ?>

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
				<!-- <th><?php echo $this->Paginator->sort('template'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($productionInspectionTemplates){ ?>
<?php foreach ($productionInspectionTemplates as $productionInspectionTemplate): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $productionInspectionTemplate['ProductionInspectionTemplate']['created_by'], 'postVal' => $productionInspectionTemplate['ProductionInspectionTemplate']['id'], 'softDelete' => $productionInspectionTemplate['ProductionInspectionTemplate']['soft_delete'])); ?>	</td>		
		<td><?php echo h($productionInspectionTemplate['ProductionInspectionTemplate']['name']); ?>&nbsp;</td>
		<!-- <td><?php echo h($productionInspectionTemplate['ProductionInspectionTemplate']['template']); ?>&nbsp;</td> -->
		<td><?php echo h($PublishedEmployeeList[$productionInspectionTemplate['ProductionInspectionTemplate']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$productionInspectionTemplate['ProductionInspectionTemplate']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($productionInspectionTemplate['ProductionInspectionTemplate']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=57>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","template"=>"Template"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","template"=>"Template"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
