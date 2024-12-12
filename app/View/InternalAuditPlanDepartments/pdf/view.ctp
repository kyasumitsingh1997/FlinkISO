<h2><?php  echo __('Internal Audit Plan Department'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Internal Audit Plan'); ?></td>
		<td>
			<?php echo $internalAuditPlanDepartment['InternalAuditPlan']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $internalAuditPlanDepartment['Department']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $internalAuditPlanDepartment['Branch']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Clauses'); ?></td>
		<td>
			<?php echo h($internalAuditPlanDepartment['InternalAuditPlanDepartment']['clauses']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $internalAuditPlanDepartment['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('List Of Trained Internal Auditor'); ?></td>
		<td>
			<?php echo $internalAuditPlanDepartment['ListOfTrainedInternalAuditor']['id']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Start Time'); ?></td>
		<td>
			<?php echo h($internalAuditPlanDepartment['InternalAuditPlanDepartment']['start_time']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('End Time'); ?></td>
		<td>
			<?php echo h($internalAuditPlanDepartment['InternalAuditPlanDepartment']['end_time']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Note'); ?></td>
		<td>
			<?php echo h($internalAuditPlanDepartment['InternalAuditPlanDepartment']['note']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($internalAuditPlanDepartment['InternalAuditPlanDepartment']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $internalAuditPlanDepartment['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $internalAuditPlanDepartment['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($internalAuditPlanDepartment['InternalAuditPlanDepartment']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $internalAuditPlanDepartment['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
