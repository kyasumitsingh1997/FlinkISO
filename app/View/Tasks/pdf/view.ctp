<h2><?php  echo __('Task'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($task['Task']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $task['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('User'); ?></td>
		<td>
			<?php echo $task['User']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($task['Task']['description']); ?>
			&nbsp;
                </td></tr>
<!--
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php //echo __('Task Type'); ?></td>
		<td>
			<?php // echo h($task['Task']['task_type']); ?>
			&nbsp;
		</td></tr>
-->
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo $task['Schedule']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($task['Task']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $task['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $task['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($task['Task']['modified']); ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
