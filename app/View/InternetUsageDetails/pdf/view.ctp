<h2><?php  echo __('Internet Usage Detail'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Internet Provider Name'); ?></td>
		<td>
			<?php echo h($internetUsageDetail['InternetUsageDetail']['internet_provider_name']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Plan Details'); ?></td>
		<td>
			<?php echo h($internetUsageDetail['InternetUsageDetail']['plan_details']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('From Date'); ?></td>
		<td>
			<?php echo h($internetUsageDetail['InternetUsageDetail']['from_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('To Date'); ?></td>
		<td>
			<?php echo h($internetUsageDetail['InternetUsageDetail']['to_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Download'); ?></td>
		<td>
			<?php echo h($internetUsageDetail['InternetUsageDetail']['download']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($internetUsageDetail['InternetUsageDetail']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $internetUsageDetail['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $internetUsageDetail['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($internetUsageDetail['InternetUsageDetail']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $internetUsageDetail['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
