<h2><?php  echo __('Company'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($company['Company']['name']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($company['Company']['description']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Number Of Branches'); ?></td>
		<td>
			<?php echo h($company['Company']['number_of_branches']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Allow Multiple Login'); ?></td>
		<td>
			<?php echo h($company['Company']['allow_multiple_login']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Limit Login Attempt'); ?></td>
		<td>
			<?php echo h($company['Company']['limit_login_attempt']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Flinkiso Start Date'); ?></td>
		<td>
			<?php echo h($company['Company']['flinkiso_start_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Flinkiso End Date'); ?></td>
		<td>
			<?php echo h($company['Company']['flinkiso_end_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Welcome Message'); ?></td>
		<td>
			<?php echo h($company['Company']['welcome_message']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Quality Policy'); ?></td>
		<td>
			<?php echo h($company['Company']['quality_policy']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Vision Statement'); ?></td>
		<td>
			<?php echo h($company['Company']['vision_statement']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Mission Statement'); ?></td>
		<td>
			<?php echo h($company['Company']['mission_statement']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo $company['Schedule']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Smtp Setup'); ?></td>
		<td>
			<?php echo h($company['Company']['smtp_setup']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Is Smtp'); ?></td>
		<td>
			<?php echo h($company['Company']['is_smtp']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Liscence Key'); ?></td>
		<td>
			<?php echo h($company['Company']['liscence_key']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Sample Data'); ?></td>
		<td>
			<?php echo h($company['Company']['sample_data']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Audit Plan'); ?></td>
		<td>
			<?php echo h($company['Company']['audit_plan']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($company['Company']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $company['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $company['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($company['Company']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $company['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
