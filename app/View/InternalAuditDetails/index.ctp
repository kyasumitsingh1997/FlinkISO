<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="internalAuditDetails ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Internal Audit Details','modelClass'=>'InternalAuditDetail','options'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments"),'pluralVar'=>'internalAuditDetails'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('internal_audit_id'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('nc_found'); ?></th>
				<th><?php echo $this->Paginator->sort('question'); ?></th>
				<th><?php echo $this->Paginator->sort('findings'); ?></th>
				<th><?php echo $this->Paginator->sort('opportunities_for_improvement'); ?></th>
				<th><?php echo $this->Paginator->sort('clause_number'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
				<th><?php echo $this->Paginator->sort('comments'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($internalAuditDetails){ ?>
<?php foreach ($internalAuditDetails as $internalAuditDetail): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $internalAuditDetail['InternalAuditDetail']['created_by'], 'postVal' => $internalAuditDetail['InternalAuditDetail']['id'], 'softDelete' => $internalAuditDetail['InternalAuditDetail']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($internalAuditDetail['InternalAudit']['start_time'], array('controller' => 'internal_audits', 'action' => 'view', $internalAuditDetail['InternalAudit']['id'])); ?>
		</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['employee_id']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['nc_found']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['question']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['findings']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['opportunities_for_improvement']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['clause_number']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['current_status']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['comments']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['division_id']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$internalAuditDetail['InternalAuditDetail']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$internalAuditDetail['InternalAuditDetail']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($internalAuditDetail['InternalAuditDetail']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
