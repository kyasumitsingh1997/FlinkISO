<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="continualImprovements ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Continual Improvements','modelClass'=>'ContinualImprovement','options'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details"),'pluralVar'=>'continualImprovements'))); ?>

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
				<th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
				<th><?php echo $this->Paginator->sort('process_id'); ?></th>
				<th><?php echo $this->Paginator->sort('internal_audit_id'); ?></th>
				<th><?php echo $this->Paginator->sort('internal_audit_detail_id'); ?></th>
				<th><?php echo $this->Paginator->sort('details'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($continualImprovements){ ?>
<?php foreach ($continualImprovements as $continualImprovement): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $continualImprovement['ContinualImprovement']['created_by'], 'postVal' => $continualImprovement['ContinualImprovement']['id'], 'softDelete' => $continualImprovement['ContinualImprovement']['soft_delete'])); ?>	</td>		<td><?php echo h($continualImprovement['ContinualImprovement']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($continualImprovement['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $continualImprovement['CorrectivePreventiveAction']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($continualImprovement['Process']['title'], array('controller' => 'processes', 'action' => 'view', $continualImprovement['Process']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($continualImprovement['InternalAudit']['start_time'], array('controller' => 'internal_audits', 'action' => 'view', $continualImprovement['InternalAudit']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($continualImprovement['InternalAuditDetail']['id'], array('controller' => 'internal_audit_details', 'action' => 'view', $continualImprovement['InternalAuditDetail']['id'])); ?>
		</td>
		<td><?php echo h($continualImprovement['ContinualImprovement']['details']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($continualImprovement['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $continualImprovement['Division']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$continualImprovement['ContinualImprovement']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$continualImprovement['ContinualImprovement']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($continualImprovement['ContinualImprovement']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
