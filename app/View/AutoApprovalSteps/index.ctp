<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="autoApprovalSteps ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Auto Approval Steps','modelClass'=>'AutoApprovalStep','options'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","allow_approval"=>"Allow Approval","show_details"=>"Show Details","details"=>"Details","system_table"=>"System Table"),'pluralVar'=>'autoApprovalSteps'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('auto_approval_id'); ?></th>
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('step_number'); ?></th>
				<th><?php echo $this->Paginator->sort('allow_approval'); ?></th>
				<th><?php echo $this->Paginator->sort('show_details'); ?></th>
				<th><?php echo $this->Paginator->sort('user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('department_id'); ?></th>
				<th><?php echo $this->Paginator->sort('details'); ?></th>
				<th><?php echo $this->Paginator->sort('system_table'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($autoApprovalSteps){ ?>
<?php foreach ($autoApprovalSteps as $autoApprovalStep): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $autoApprovalStep['AutoApprovalStep']['created_by'], 'postVal' => $autoApprovalStep['AutoApprovalStep']['id'], 'softDelete' => $autoApprovalStep['AutoApprovalStep']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($autoApprovalStep['AutoApproval']['name'], array('controller' => 'auto_approvals', 'action' => 'view', $autoApprovalStep['AutoApproval']['id'])); ?>
		</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['name']); ?>&nbsp;</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['step_number']); ?>&nbsp;</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['allow_approval']); ?>&nbsp;</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['show_details']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($autoApprovalStep['User']['name'], array('controller' => 'users', 'action' => 'view', $autoApprovalStep['User']['id'])); ?>
		</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['branch_id']); ?>&nbsp;</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['department_id']); ?>&nbsp;</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['details']); ?>&nbsp;</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['system_table']); ?>&nbsp;</td>
		<td><?php echo h($autoApprovalStep['AutoApprovalStep']['division_id']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$autoApprovalStep['AutoApprovalStep']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$autoApprovalStep['AutoApprovalStep']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($autoApprovalStep['AutoApprovalStep']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=84>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","allow_approval"=>"Allow Approval","show_details"=>"Show Details","details"=>"Details","system_table"=>"System Table"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","step_number"=>"Step Number","allow_approval"=>"Allow Approval","show_details"=>"Show Details","details"=>"Details","system_table"=>"System Table"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
