<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="internalAuditQuestions ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Internal Audit Questions','modelClass'=>'InternalAuditQuestion','options'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title"),'pluralVar'=>'internalAuditQuestions'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('department_id'); ?></th>
				<th><?php echo $this->Paginator->sort('clause'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($internalAuditQuestions){ ?>
<?php foreach ($internalAuditQuestions as $internalAuditQuestion): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $internalAuditQuestion['InternalAuditQuestion']['created_by'], 'postVal' => $internalAuditQuestion['InternalAuditQuestion']['id'], 'softDelete' => $internalAuditQuestion['InternalAuditQuestion']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($internalAuditQuestion['Department']['name'], array('controller' => 'departments', 'action' => 'view', $internalAuditQuestion['Department']['id'])); ?>
		</td>
		<td><?php echo h($internalAuditQuestion['InternalAuditQuestion']['clause']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditQuestion['InternalAuditQuestion']['title']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$internalAuditQuestion['InternalAuditQuestion']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$internalAuditQuestion['InternalAuditQuestion']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($internalAuditQuestion['InternalAuditQuestion']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>