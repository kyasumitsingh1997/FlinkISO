<div id="incidentInvestigations_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="incidentInvestigations form col-md-8">
<h4><?php echo __('View Incident Investigation'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Incident'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentInvestigation['Incident']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Reference Number'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['reference_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Incident Investigator'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['IncidentInvestigator']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentInvestigation['IncidentInvestigator']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Investigation Date From'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_date_from']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Investigation Date To'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_date_to']); ?>
			&nbsp;
		</td></tr>
	</table>
			<h4>Affected Persons</h4>
			<table class="table table-responsive">
				<tr>
					<th>Name</th>
					<th>Investigation Interview Findings</th>
					<th>Action</th>
				</tr>
			<?php if($incidentAffectedPersonals){
				foreach($incidentAffectedPersonals as $personals): ?>
				<tr>
					<td><?php echo $personals['IncidentAffectedPersonal']['name']?></td>
					<td><?php echo $personals['IncidentAffectedPersonal']['investigation_interview_findings']?></td>
					<td><?php echo $this->Html->link('Edit',array('controller'=>'incident_affected_personals','action'=>'edit',$personals['IncidentAffectedPersonal']['id']),array('class'=>'btn btn-xs btn-info')); ?></td>	
				</tr>		

			<?php	endforeach;
			} ?>
		</table>
			<h4>Witnesses</h4>
			<table class="table table-responsive">
				<tr>
					<th>Name</th>
					<th>Interview Findings</th>
					<th>Action</th>
				</tr>
			<?php if($incidentWitnesses){
				foreach($incidentWitnesses as $witness): ?>
				<tr>
					<td><?php echo $witness['IncidentWitness']['name']?></td>
					<td><?php echo $witness['IncidentWitness']['investigation_interview_findings']?></td>
					<td><?php echo $this->Html->link('Edit',array('controller'=>'incident_witnesses','action'=>'edit',$witness['IncidentWitness']['id']),array('class'=>'btn btn-xs btn-info')); ?></td>	
				</tr>		

			<?php	endforeach;
			} ?>
		</table>


	<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Control Measures Currently In Place'); ?></td>
		<td>
			<?php echo $incidentInvestigation['IncidentInvestigation']['control_measures_currently_in_place']; ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Summery Of Findings'); ?></td>
		<td>
			<?php echo $incidentInvestigation['IncidentInvestigation']['summery_of_findings']; ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Reason For Incidence'); ?></td>
		<td>
			<?php echo $incidentInvestigation['IncidentInvestigation']['reason_for_incidence']; ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Immediate Action Taken'); ?></td>
		<td>
			<?php echo $incidentInvestigation['IncidentInvestigation']['immediate_action_taken']; ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Risk Assessment'); ?></td>
		<td>
			<?php echo $incidentInvestigation['IncidentInvestigation']['risk_assessment']; ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Investigation Reviewd By'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_reviewd_by']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Action Taken'); ?></td>
		<td>
			<?php echo $incidentInvestigation['IncidentInvestigation']['action_taken']; ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Corrective Preventive Action'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $incidentInvestigation['CorrectivePreventiveAction']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($incidentInvestigation['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($incidentInvestigation['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($incidentInvestigation['IncidentInvestigation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($incidentInvestigation['IncidentInvestigation']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $incidentInvestigation['IncidentInvestigation']['created_by'], 'recordId' => $incidentInvestigation['IncidentInvestigation']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentInvestigations_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$incidentInvestigation['IncidentInvestigation']['id'] ,'ajax'),array('async' => true, 'update' => '#incidentInvestigations_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
