<h2><?php echo __('Internal Audit Plan'); ?> </h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<td class="head-strong"><?php echo __('Title'); ?></td>
		<td><?php echo $internalAuditPlan['InternalAuditPlan']['title']; ?> &nbsp; </td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td class="head-strong"><?php echo __('Audit Date'); ?></td>
		<td>From : <?php echo $internalAuditPlan['InternalAuditPlan']['schedule_date_from']; ?> To : <?php echo $internalAuditPlan['InternalAuditPlan']['schedule_date_to']; ?> &nbsp; </td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td class="head-strong"><?php echo __('Note'); ?></td>
		<td><?php echo html_entity_decode($internalAuditPlan['InternalAuditPlan']['note']); ?> &nbsp; </td>
	</tr>
</table>
<br />&nbsp;

<h2><?php echo __('Internal Audit Plan Details'); ?> </h2>
<?php foreach ($PublishedBranchList as $key => $value): ?>
<?php if(count($plan[$key]) > 0){ ?>
<h5><?php echo $value; ?></h5>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Department'); ?></th>
		<th><?php echo __('Clauses'); ?></th>
		<th><?php echo __('Auditee'); ?></th>
		<th><?php echo __('Auditor'); ?></th>
		<th><?php echo __('Schedule'); ?></th>
	</tr>
	<?php
       $i = 1;
        foreach ($plan[$key] as $finalPlan):
 	?>
	<tr bgcolor="#FFFFFF">
		<td><?php echo $finalPlan['Department']['name']; ?>&nbsp;</td>
		<td><?php echo $finalPlan['InternalAuditPlanDepartment']['clauses']; ?>&nbsp;</td>
		<td><?php echo $finalPlan['Employee']['name']; ?>&nbsp;</td>
		<td><?php echo $PublishedEmployeeList[$finalPlan['ListOfTrainedInternalAuditor']['employee_id']]; ?>&nbsp;</td>
		<td>From : <?php echo $finalPlan['InternalAuditPlanDepartment']['start_time'] ?><br />
			To : <?php echo$finalPlan['InternalAuditPlanDepartment']['end_time'] ?> &nbsp; </td>
		<td><?php echo $this->Html->link('Edit', array('controller' => 'internal_audit_plan_departments', 'action' => 'edit', $finalPlan['InternalAuditPlanDepartment']['id']), array('class' => 'btn btn-xs btn-info')); ?></td>
	</tr>
	<?php
		$i++;
       endforeach;
	?>
</table>
<br />&nbsp;
<?php } ?>
<?php endforeach ?>
