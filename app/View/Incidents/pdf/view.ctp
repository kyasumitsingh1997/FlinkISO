<h2><?php echo __('Incident'); ?></h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td width="30%"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($incident['Incident']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Risk Assessment'); ?></td>
		<td>
			<?php echo h($incident['RiskAssessment']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Reported By'); ?></td>
		<td>
			<?php echo h($incident['ReportedBy']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($incident['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incident['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Incident Date'); ?></td>
		<td>
			<?php echo h($incident['Incident']['incident_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Incident Reported Lag Time'); ?></td>
		<td>
			<?php echo h($incident['Incident']['incident_reported_lag_time']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($incident['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $incident['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Location'); ?></td>
		<td>
			<?php echo h($incident['Incident']['location']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Location Details'); ?></td>
		<td>
			<?php echo h($incident['Incident']['location_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Activity'); ?></td>
		<td>
			<?php echo h($incident['Incident']['activity']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Activity Details'); ?></td>
		<td>
			<?php echo h($incident['Incident']['activity_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Damage Details'); ?></td>
		<td>
			<?php echo h($incident['Incident']['damage_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Incident Classification'); ?></td>
		<td>
			<?php echo h($incident['IncidentClassification']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('First Aid Provided'); ?></td>
		<td>
			<?php echo h($incident['Incident']['first_aid_provided']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('First Aid Details'); ?></td>
		<td>
			<?php echo h($incident['Incident']['first_aid_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('First Aid Provided By'); ?></td>
		<td>
			<?php echo h($incident['Incident']['first_aid_provided_by']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Person Responsible'); ?></td>
		<td>
			<?php echo $this->Html->link($incident['PersonResponsible']['name'], array('controller' => 'employees', 'action' => 'view', $incident['PersonResponsible']['id'])); ?>
			&nbsp;
		</td></tr>		
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($incident['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($incident['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Publish'); ?></td>

		<td>
			<?php ($incident['Incident']['publish'])? "Yes":"No";?>
		&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Soft Delete'); ?></td>
			<?php ($incident['Incident']['soft_delete'])? "Yes":"No";?>
		<td>&nbsp;</td></tr>
</table>
<tcpdf method="AddPage" />
<h3><?php echo __('Affected Persons'); ?></h3>
<?php foreach($incident['IncidentAffectedPersonal'] as $affectedPersons): ?>
	<table border="1" cellpadding="4" wdith="100%">
	<tr bgcolor="#FFFFFF"><th colspan="3"><h4><?php echo h($affectedPersons['name']); ?></h4></th></tr>
	<tr bgcolor="#FFFFFF">
		<th width="20%"><?php echo __('address'); ?></th><td width="20%"><?php echo h($affectedPersons['address'] ? $affectedPersons['address'] .',' : ''); ?></td>
		<td><strong><?php echo __('Date Of Investigation Interview'); ?></strong></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('phone'); ?></th><td><?php echo h($affectedPersons['phone'] ? $affectedPersons['phone'] .',' : ''); ?></td>
		<td colspan="2"><?php echo h($affectedPersons['date_of_interview'] ? $affectedPersons['date_of_interview'] .',' : 'Pending/Not Done'); ?></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('age'); ?></th><td><?php echo h($affectedPersons['age'] ? $affectedPersons['age'] .',' : ''); ?></td>
		<td colspan="1"><strong><?php echo __('Investigation By'); ?></strong></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('gender'); ?></th><td><?php echo h($affectedPersons['gender'] ? $affectedPersons['gender'] .',' : ''); ?></td>
		<td><?php echo h($affectedPersons['incident_investigator_id'] ? $affectedPersons['incident_investigator_id'] .',' : 'Pending/Not Done'); ?></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Department'); ?></th><td><?php echo h($PublishedDepartmentList[$affectedPersons['department_id']]); ?></td>
		<td colspan="2"><strong><?php echo __('Investigation Findings'); ?></strong></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Designation'); ?></th><td><?php echo h($designations[$affectedPersons['designation_id']]); ?></td>
		<td rowspan="6"><?php echo h($affectedPersons['investigation_interview_findings'] ? $affectedPersons['investigation_interview_findings'] .',' : 'Pending/Not Done'); ?></td>
	</tr>
	<tr bgcolor="#FFFFFF"><th><?php echo __('Ill Health Reported'); ?></th><td><?php echo h($affectedPersons['illhealth_reported'] ? "Yes":"No") ; ?></td></tr>
	<tr bgcolor="#FFFFFF"><th><?php echo __('First Aid Provided'); ?></th><td><?php echo h($affectedPersons['first_aid_provided'] ? "Yes":"No") ; ?></td></tr>
	<tr bgcolor="#FFFFFF"><th><?php echo __('# Working days affected'); ?></th><td><?php echo h($affectedPersons['number_of_work_affected_dates']); ?></td></tr>
	<tr bgcolor="#FFFFFF"><th><?php echo __('First Aid Provided By'); ?></th><td>
		<?php echo h($affectedPersons['first_aid_provided_by'] ? $affectedPersons['first_aid_provided_by']  : 'N/A'); ?>
	</td></tr>
	
	<tr bgcolor="#FFFFFF"><th><?php echo __('First Aid Details'); ?></th><td ><?php echo h($affectedPersons['first_aid_details'] ? $affectedPersons['first_aid_details']  : 'N/A'); ?></td></tr>
	<tr bgcolor="#FFFFFF"><td colspan="3"><strong><?php echo __('Followup Action'); ?></strong><br />
	<?php echo h($affectedPersons['follow_up_action_taken'] ? $affectedPersons['follow_up_action_taken']  : 'N/A'); ?></td></tr>
</table>	
<?php endforeach; ?>
<tcpdf method="AddPage" />
<h3><?php echo __('Witnesses'); ?></h3>
<?php foreach($incident['IncidentWitness'] as $incidentWitness): ?>
	<p><br />&nbsp;</p>
	<table border="1" cellpadding="4" wdith="100%">
	<tr bgcolor="#FFFFFF"><th colspan="3"><h4><?php echo h($incidentWitness['name']); ?></h4></th></tr>
	<tr bgcolor="#FFFFFF">
		<th width="20%"><?php echo __('address'); ?></th><td width="20%"><?php echo h($incidentWitness['address'] ? $incidentWitness['address'] .',' : ''); ?></td>
		<td><strong><?php echo __('Date Of Investigation Interview'); ?></strong></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('phone'); ?></th><td><?php echo h($incidentWitness['phone'] ? $incidentWitness['phone'] .',' : ''); ?></td>
		<td colspan="2"><?php echo h($incidentWitness['date_of_interview'] ? $incidentWitness['date_of_interview'] .',' : 'Pending/Not Done'); ?></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('age'); ?></th><td><?php echo h($incidentWitness['age'] ? $incidentWitness['age'] .',' : ''); ?></td>
		<td colspan="1"><strong><?php echo __('Investigation By'); ?></strong></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('gender'); ?></th><td><?php echo h($incidentWitness['gender'] ? $incidentWitness['gender'] .',' : ''); ?></td>
		<td><?php echo h($incidentWitness['incident_investigator_id'] ? $incidentWitness['incident_investigator_id'] .',' : 'Pending/Not Done'); ?></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Department'); ?></th><td><?php echo h($PublishedDepartmentList[$incidentWitness['department_id']]); ?></td>
		<td colspan="2"><strong><?php echo __('Investigation Findings'); ?></strong></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Designation'); ?></th><td><?php echo h($designations[$incidentWitness['designation_id']]); ?></td>
		<td><?php echo h($incidentWitness['investigation_interview_findings'] ? $incidentWitness['investigation_interview_findings'] .',' : 'Pending/Not Done'); ?></td>
	</tr>
	
</table>	
<?php endforeach; ?>
<tcpdf method="AddPage" />
<h3><?php echo __('Investigation'); ?></h3>
<?php foreach($incident['IncidentInvestigation'] as $incidentInvestigation): ?>
	<table border="1" cellpadding="4" wdith="100%">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Reference Number'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['reference_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Incident Investigator'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['IncidentInvestigator']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentInvestigation['IncidentInvestigator']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Investigation Date From'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['investigation_date_from']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Investigation Date To'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['investigation_date_to']); ?>
			&nbsp;
		</td></tr>
	</table>			
		<h1><?php echo __('Control Measures Currently In Place'); ?></h1>
		<?php echo $incidentInvestigation['control_measures_currently_in_place']; ?>&nbsp;
<tcpdf method="AddPage" />	
		<h1><?php echo __('Summery Of Findings'); ?></h1>
			<?php echo $incidentInvestigation['summery_of_findings']; ?>&nbsp;
<tcpdf method="AddPage" />	
		<h1><?php echo __('Reason For Incidence'); ?></h1>
			<?php echo $incidentInvestigation['reason_for_incidence']; ?>&nbsp;
<tcpdf method="AddPage" />	
		<h1><?php echo __('Immediate Action Taken'); ?></h1>
			<?php echo $incidentInvestigation['immediate_action_taken']; ?>&nbsp;
<tcpdf method="AddPage" />	
		<h1><?php echo __('Risk Assessment'); ?></h1>
			<?php echo $incidentInvestigation['risk_assessment']; ?>&nbsp;
<tcpdf method="AddPage" />	
		<h1><?php echo __('Investigation Reviewd By'); ?></h1>
			<?php echo h($incidentInvestigation['investigation_reviewd_by']); ?>&nbsp;
<tcpdf method="AddPage" />	
		<h1><?php echo __('Action Taken'); ?></h1>
			<?php echo $incidentInvestigation['action_taken']; ?>&nbsp;

<table border="1" cellpadding="4" wdith="100%">
	<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
	<td><?php echo h($incidentInvestigation['PreparedBy']['name']); ?>&nbsp;</td></tr>
	
	<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td><?php echo h($incidentInvestigation['ApprovedBy']['name']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Publish'); ?></td>
		<td><?php ($incidentInvestigation['publish'])? "Yes" : "No"; ?>&nbsp;</td>
	</tr>
</table>
<?php endforeach; ?>
