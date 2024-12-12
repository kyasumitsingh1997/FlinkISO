<h2><?php  echo __('Daily Backup Detail'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
<!--
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php // echo __('Name'); ?></td>
		<td>
			<?php // echo h($dailyBackupDetail['DailyBackupDetail']['name']); ?>
			&nbsp;
		</td></tr>
                -->
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $dailyBackupDetail['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Data Back Up'); ?></td>
		<td>
			<?php echo $dailyBackupDetail['DataBackUp']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Backup Date'); ?></td>
		<td>
			<?php echo h($dailyBackupDetail['DailyBackupDetail']['backup_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Device Name'); ?></td>
		<td>
			<?php echo $dailyBackupDetail['Device']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Computer Name'); ?></td>
		<td>
			<?php echo $dailyBackupDetail['ListOfComputer']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Task Performed'); ?></td>
		<td>
			<?php switch ($dailyBackupDetail['DailyBackupDetail']['task_performed']) {
                            case 0 : echo "Unread"; break;
                            case 1 : echo "Yes"; break;
                            case 2 : echo "No"; break;
                        } ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Comments'); ?></td>
		<td>
			<?php echo h($dailyBackupDetail['DailyBackupDetail']['comments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($dailyBackupDetail['DailyBackupDetail']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $dailyBackupDetail['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $dailyBackupDetail['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($dailyBackupDetail['DailyBackupDetail']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($dailyBackupDetail['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $dailyBackupDetail['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />
