<h2><?php  echo __('Custom Template'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['name']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['description']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['details']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Header'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['header']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Template Body'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['template_body']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Footer'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['footer']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo $customTemplate['Schedule']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Report Type'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['report_type']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $customTemplate['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $customTemplate['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($customTemplate['CustomTemplate']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $customTemplate['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
