<div id="capaRootCauseAnalysis_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="capaRootCauseAnalysis form col-md-8">
<h4><?php echo __('View Capa Root Cause Analysi'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Corrective Preventive Action'); ?></td>
		<td>
			<?php echo $this->Html->link($capaRootCauseAnalysi['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRootCauseAnalysi['CorrectivePreventiveAction']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($capaRootCauseAnalysi['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Root Cause Details'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['root_cause_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Determined By'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['DeterminedBy']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Determined On Date'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['determined_on_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Root Cause Remarks'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['root_cause_remarks']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Proposed Action'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['proposed_action']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Action Assigned To'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['ActionAssignedTo']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Action Completed On Date'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completed_on_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Action Completion Remarks'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completion_remarks']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Effectiveness'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['effectiveness']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Closure Remarks'); ?></td>
		<td>
			<?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['closure_remarks']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td> <?php echo $capaRootCauseAnalysi['CapaRootCauseAnalysi']['current_status'] ? __('Close') : __('Open'); ?>
                        &nbsp;
			
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($capaRootCauseAnalysi['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($capaRootCauseAnalysi['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($capaRootCauseAnalysi['CapaRootCauseAnalysi']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		
</table>
<?php echo $this->Form->create('Upload',array('role'=>'form','class'=>'form')); ?>
	<fieldset>		<?php 
			echo $this->Upload->edit('upload',$this->Session->read('User.id').'/'.$this->request->params['controller'].'/'.$capaRootCauseAnalysi['CapaRootCauseAnalysi']['id']);
			echo $this->Form->end(); ?>
	</fieldset></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#capaRootCauseAnalysis_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'] ,'ajax'),array('async' => true, 'update' => '#capaRootCauseAnalysis_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
