<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="capaRootCauseAnalysis ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Capa Root Cause Analysis','modelClass'=>'CapaRootCauseAnalysi','options'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status"),'pluralVar'=>'capaRootCauseAnalysis'))); ?>

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
			<table cellpadding="0" cellspacing="0" class="table table-bordered">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				
				<th><?php echo $this->Paginator->sort('determined_by'); ?></th>
				<th><?php echo $this->Paginator->sort('determined_on_date'); ?></th>
				<th><?php echo $this->Paginator->sort('action_assigned_to'); ?></th>
				<th><?php echo $this->Paginator->sort('action_completed_on_date'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($capaRootCauseAnalysis){ ?>
<?php foreach ($capaRootCauseAnalysis as $capaRootCauseAnalysi): 
    ?>
	 <?php if(!$capaRootCauseAnalysi['CapaRootCauseAnalysi']['current_status']){ ?>
                <tr class="text-danger on_page_src">
                    <?php } else{ ?>
                <tr class="on_page_src"> <?php } ?>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $capaRootCauseAnalysi['CapaRootCauseAnalysi']['created_by'], 'postVal' => $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'], 'softDelete' => $capaRootCauseAnalysi['CapaRootCauseAnalysi']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($capaRootCauseAnalysi['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRootCauseAnalysi['CorrectivePreventiveAction']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($capaRootCauseAnalysi['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['Employee']['id'])); ?>
		</td>
		
                <td><?php echo $this->Html->link($capaRootCauseAnalysi['DeterminedBy']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['DeterminedBy']['id'])); ?></td>
		
		<td><?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['determined_on_date']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($capaRootCauseAnalysi['ActionAssignedTo']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['ActionAssignedTo']['id'])); ?></td>
		<td><?php if($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completed_on_date'] != '1970-01-01') echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completed_on_date']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$capaRootCauseAnalysi['CapaRootCauseAnalysi']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$capaRootCauseAnalysi['CapaRootCauseAnalysi']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($capaRootCauseAnalysi['CapaRootCauseAnalysi']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
