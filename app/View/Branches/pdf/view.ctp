<h2><?php  echo __('Branch'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($branch['Branch']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($branch['Branch']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $branch['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $branch['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($branch['Branch']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($branch['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $branch['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
        <p>&nbsp;</p>

	<h3><?php echo __('Related Users'); ?></h3>
	<?php if (!empty($branch['User'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Employee'); ?></th>
		<th><?php echo __('Username'); ?></th>
		<th><?php echo __('Is MR?'); ?></th>
		<th><?php echo __('Is View All?'); ?></th>
		<th><?php echo __('Is Approvar?'); ?></th>
		<th><?php echo __('Last Login'); ?></th>
		<th><?php echo __('Last Activity'); ?></th>
		<th><?php echo __('Benchmark'); ?></th>
		<th><?php echo __('Publish'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($branch['User'] as $user): ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $user['name']; ?></td>
			<td><?php echo $user['username']; ?></td>
			<td><?php echo $user['is_mr'] ? 'Yes' : 'No'; ?></td>
			<td><?php echo $user['is_view_all'] ? 'Yes' : 'No'; ?></td>
			<td><?php echo $user['is_approvar'] ? 'Yes' : 'No'; ?></td>
			<td><?php echo $user['last_login']; ?></td>
			<td><?php echo $user['last_activity']; ?></td>
			<td><?php echo $user['benchmark']; ?></td>
			<td><?php echo $user['publish'] ? 'Yes' : 'No'; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>


