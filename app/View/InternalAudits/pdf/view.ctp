<h2><?php  echo __('Internal Audit'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Internal Audit Plan'); ?></td>
		<td>
			<?php echo $internalAudit['InternalAuditPlan']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Internal Audit Plan Department'); ?></td>
		<td>
			<?php echo $internalAudit['InternalAuditPlanDepartment']['id']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $internalAudit['Department']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $internalAudit['Branch']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Section'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['section']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Start Time'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['start_time']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('End Time'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['end_time']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('List Of Trained Internal Auditor'); ?></td>
		<td>
			<?php echo $internalAudit['ListOfTrainedInternalAuditor']['id']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $internalAudit['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Clauses'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['clauses']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Question Asked'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['question_asked']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Finding'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['finding']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Non Conformity Found'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['non_conformity_found']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Corrective Preventive Action'); ?></td>
		<td>
			<?php echo $internalAudit['CorrectivePreventiveAction']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['current_status']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('EmployeeId'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['employeeId']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Target Date'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['target_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Notes'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['notes']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $internalAudit['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $internalAudit['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($internalAudit['InternalAudit']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $internalAudit['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
