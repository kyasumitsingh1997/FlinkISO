<h4><?php echo __('View Incident Investigator'); ?></h4>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigator['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentInvestigator['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Address'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['address']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Phone'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['phone']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigator['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentInvestigator['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Designation'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigator['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentInvestigator['Designation']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Age'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['age']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Gender'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['gender']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($incidentInvestigator['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($incidentInvestigator['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		
</table>