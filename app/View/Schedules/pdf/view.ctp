<h2><?php  echo __('Schedule'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($schedule['Schedule']['name']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($schedule['Schedule']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $schedule['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $schedule['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($schedule['Schedule']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $schedule['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
