<h2><?php  echo __('Device Maintenance'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Device'); ?></td>
		<td>
			<?php echo $deviceMaintenance['Device']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Person Responsible for Maintenance'); ?></td>
		<td>
			<?php echo $deviceMaintenance['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Maintenance Performed Date'); ?></td>
		<td>
			<?php echo h($deviceMaintenance['DeviceMaintenance']['maintenance_performed_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Findings'); ?></td>
		<td>
			<?php echo h($deviceMaintenance['DeviceMaintenance']['findings']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Status'); ?></td>
		<td>
			<?php echo h($deviceMaintenance['DeviceMaintenance']['status'] ? 'In use' : 'Not in use'); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Intimation Sent To Employee'); ?></td>
		<td>
			<?php echo $deviceMaintenance['IntimationSentToEmployee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Intimation Sent To Department'); ?></td>
		<td>
			<?php echo $deviceMaintenance['IntimationSentToDepartment']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Next Maintanence Date'); ?></td>
		<td>
			<?php echo h($deviceMaintenance['DeviceMaintenance']['next_maintanence_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($deviceMaintenance['DeviceMaintenance']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $deviceMaintenance['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $deviceMaintenance['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($deviceMaintenance['DeviceMaintenance']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($deviceMaintenance['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $deviceMaintenance['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />