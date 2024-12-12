<h4><?php echo __('View Incident Investigation'); ?></h4>

<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Incident'); ?></td>
		<td>
			<?php echo $incidentInvestigation['Incident']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Reference Number'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['reference_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Incident Investigator'); ?></td>
		<td>
			<?php echo $incidentInvestigation['IncidentInvestigator']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Investigation Date From'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_date_from']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Investigation Date To'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_date_to']); ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
		<h4>Affected Persons</h4>
			<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
				<tr bgcolor="#FFFFFF">
					<th>Name</th>
					<th>Investigation Interview Findings</th>					
				</tr>
			<?php if($incidentAffectedPersonals){
				foreach($incidentAffectedPersonals as $personals): ?>
				<tr bgcolor="#FFFFFF">
					<td><?php echo $personals['IncidentAffectedPersonal']['name']?></td>
					<td><?php echo $personals['IncidentAffectedPersonal']['investigation_interview_findings']?></td>					
				</tr>		

			<?php	endforeach;
			} ?>
		</table>
		<br />
			<h4>Witnesses</h4>
			<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
				<tr bgcolor="#FFFFFF">
					<th>Name</th>
					<th>Interview Findings</th>					
				</tr>
			<?php if($incidentWitnesses){
				foreach($incidentWitnesses as $witness): ?>
				<tr bgcolor="#FFFFFF">
					<td><?php echo $witness['IncidentWitness']['name']?></td>
					<td><?php echo $witness['IncidentWitness']['investigation_interview_findings']?></td>					
				</tr>		

			<?php	endforeach;
			} ?>
		</table>

<tcpdf method="AddPage" />
        <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
        <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
        <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
        <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
        <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
        <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
        <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
        <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
        <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td colspan="2"><h2><?php echo __('Control Measures Currently In Place'); ?></h2>
			<?php echo $incidentInvestigation['IncidentInvestigation']['control_measures_currently_in_place']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td colspan="2"><h2><?php echo __('Summery Of Findings'); ?></h2>
			<?php echo $incidentInvestigation['IncidentInvestigation']['summery_of_findings']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td colspan="2"><h2><?php echo __('Reason For Incidence'); ?></h2>
			<?php echo $incidentInvestigation['IncidentInvestigation']['reason_for_incidence']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td colspan="2"><h2><?php echo __('Immediate Action Taken'); ?></h2>
			<?php echo $incidentInvestigation['IncidentInvestigation']['immediate_action_taken']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td colspan="2"><h2><?php echo __('Risk Assessment'); ?></h2>
			<?php echo $incidentInvestigation['IncidentInvestigation']['risk_assessment']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td colspan="2"><h2><?php echo __('Investigation Reviewd By'); ?></h2>
			<?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_reviewd_by']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td colspan="2"><h2><?php echo __('Action Taken'); ?></h2>
			<?php echo $incidentInvestigation['IncidentInvestigation']['action_taken']; ?>
			&nbsp;
		</td></tr>		
</table>
