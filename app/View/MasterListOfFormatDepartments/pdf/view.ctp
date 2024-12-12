<h2><?php  echo __('Master List Of Format Department'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $masterListOfFormatDepartment['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $masterListOfFormatDepartment['Department']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($masterListOfFormatDepartment['MasterListOfFormatDepartment']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $masterListOfFormatDepartment['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $masterListOfFormatDepartment['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($masterListOfFormatDepartment['MasterListOfFormatDepartment']['modified']); ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
