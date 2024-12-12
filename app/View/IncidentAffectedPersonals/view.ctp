<div id="incidentAffectedPersonals_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="incidentAffectedPersonals form col-md-8">
<h4><?php echo __('View Incident Affected Personal'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Incident'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentAffectedPersonal['Incident']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Person Type'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['person_type']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentAffectedPersonal['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Address'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['address']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Phone'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['phone']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentAffectedPersonal['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Designation'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentAffectedPersonal['Designation']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Age'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['age']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Gender'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['gender']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('First Aid Provided'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_provided']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('First Aid Details'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('First Aid Provided By'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_provided_by']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Follow Up Action Taken'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['follow_up_action_taken']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Other'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['other']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Illhealth Reported'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['illhealth_reported']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Normal Work Affected'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['normal_work_affected']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Number Of Work Affected Dates'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['number_of_work_affected_dates']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Incident Investigator'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['IncidentInvestigator']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentAffectedPersonal['IncidentInvestigator']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Date Of Interview'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['date_of_interview']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Investigation Interview Findings'); ?></td>
		<td>
			<?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['investigation_interview_findings']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($incidentAffectedPersonal['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($incidentAffectedPersonal['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($incidentAffectedPersonal['IncidentAffectedPersonal']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($incidentAffectedPersonal['IncidentAffectedPersonal']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $incidentAffectedPersonal['IncidentAffectedPersonal']['created_by'], 'recordId' => $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentAffectedPersonals_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$incidentAffectedPersonal['IncidentAffectedPersonal']['id'] ,'ajax'),array('async' => true, 'update' => '#incidentAffectedPersonals_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
