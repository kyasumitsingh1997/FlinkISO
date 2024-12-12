<h2><?php  echo __('Benchmark'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $benchmark['Branch']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $benchmark['Department']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Benchmark'); ?></td>
		<td>
			<?php echo h($benchmark['Benchmark']['benchmark']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($benchmark['Benchmark']['created']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo h($benchmark['Benchmark']['approved_by']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo h($benchmark['Benchmark']['prepared_by']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($benchmark['Benchmark']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format Id'); ?></td>
		<td>
			<?php echo h($benchmark['Benchmark']['master_list_of_format_id']); ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
