<div id="riskAssessments_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="riskAssessments form col-md-8">
<h4><?php echo __('View Risk Assessment'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process'); ?></td>
		<td>
			<?php echo $this->Html->link($riskAssessment['Process']['title'], array('controller' => 'processes', 'action' => 'view', $riskAssessment['Process']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($riskAssessment['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $riskAssessment['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Task'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['task']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Ra Date'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['ra_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Ra Expert 1'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['ra_expert_1']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Ra Expert 2'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['ra_expert_2']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Management'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['management']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Technical Expert'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['technical_expert']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Risk Control Exprt'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['risk_control_exprt']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Reference Number'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['reference_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Hazard Type'); ?></td>
		<td>
			<?php echo $this->Html->link($riskAssessment['HazardType']['name'], array('controller' => 'hazard_types', 'action' => 'view', $riskAssessment['HazardType']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Hazard Source'); ?></td>
		<td>
			<?php echo $this->Html->link($riskAssessment['HazardSource']['name'], array('controller' => 'hazard_sources', 'action' => 'view', $riskAssessment['HazardSource']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Injury Type'); ?></td>
		<td>
			<?php echo $this->Html->link($riskAssessment['InjuryType']['name'], array('controller' => 'injury_types', 'action' => 'view', $riskAssessment['InjuryType']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('What Could Happan'); ?></td>
		<td>
			<?php echo html_entity_decode($riskAssessment['RiskAssessment']['what_could_happan']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Accident Type'); ?></td>
		<td>
			<?php echo $this->Html->link($riskAssessment['AccidentType']['name'], array('controller' => 'accident_types', 'action' => 'view', $riskAssessment['AccidentType']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Severiry Type'); ?></td>
		<td>
			<?php echo $this->Html->link($riskAssessment['SeveriryType']['name'], array('controller' => 'severiry_types', 'action' => 'view', $riskAssessment['SeveriryType']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Existing Controls'); ?></td>
		<td>
			<?php echo html_entity_decode($riskAssessment['RiskAssessment']['existing_controls']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Likelihood'); ?></td>
		<td>
			<?php echo html_entity_decode($riskAssessment['RiskAssessment']['likelihood']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Risk Rating Id'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['risk_rating_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Additional Control Needed'); ?></td>
		<td>
			<?php echo html_entity_decode($riskAssessment['RiskAssessment']['additional_control_needed']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Person Responsible'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['person_responsible']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Target Date'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['target_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Completions Date'); ?></td>
		<td>
			<?php echo h($riskAssessment['RiskAssessment']['completions_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process Notes'); ?></td>
		<td>
			<?php echo html_entity_decode($riskAssessment['RiskAssessment']['process_notes']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Task Notes'); ?></td>
		<td>
			<?php echo html_entity_decode($riskAssessment['RiskAssessment']['task_notes']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($riskAssessment['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($riskAssessment['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($riskAssessment['RiskAssessment']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($riskAssessment['RiskAssessment']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $riskAssessment['RiskAssessment']['created_by'], 'recordId' => $riskAssessment['RiskAssessment']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#riskAssessments_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$riskAssessment['RiskAssessment']['id'] ,'ajax'),array('async' => true, 'update' => '#riskAssessments_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
