<h4><?php echo __('Incident Witnesses'); ?></h4>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Incident'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentWitness['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentWitness['Incident']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Person Type'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['person_type']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentWitness['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentWitness['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Address'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['address']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Phone'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['phone']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentWitness['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentWitness['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Designation'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentWitness['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentWitness['Designation']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Age'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['age']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Gender'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['gender']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Investigation Interview Taken By'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['investigation_interview_taken_by']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Date Of Interview'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['date_of_interview']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Investigation Interview Findings'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['investigation_interview_findings']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($incidentWitness['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($incidentWitness['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($incidentWitness['IncidentWitness']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($incidentWitness['IncidentWitness']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
