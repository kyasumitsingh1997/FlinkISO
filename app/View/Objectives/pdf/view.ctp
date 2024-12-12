
<h2><?php echo __('View Objective'); ?></h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td width="20%"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($objective['Objective']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Clauses'); ?></td>
		<td>
			<?php echo h($objective['Objective']['clauses']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Objective'); ?></td>
		<td>
			<?php echo h($objective['Objective']['objective']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Desired Output'); ?></td>
		<td>
			<?php echo h($objective['Objective']['desired_output']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Requirments'); ?></td>
		<td>
			<?php echo h($objective['Objective']['requirments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($objective['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>

	<td><?php echo h($objective['ApprovedBy']['name']); ?>&nbsp;</td></tr>
</table>

<?php if($objective['Process']){ 
	echo "<h3>".__('Processes')."</h3>";
	foreach ($objective['Process'] as $process) { 
?>		
		<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr><td width="20%" class="head-strong"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($process['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Clauses'); ?></td>
		<td>
			<?php echo h($process['clauses']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Process Requirments'); ?></td>
		<td>
			<?php echo h($process['process_requirments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branches'); ?></td>
		<td>
			<?php if($process['ProcessTeam']['Branches']){				
				foreach ($process['ProcessTeam']['Branches'] as $id => $branches) {
					echo $branches .", ";
				}
			} ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Departments'); ?></td>
		<td>
			<?php if($process['ProcessTeam']['Departments']){				
				foreach ($process['ProcessTeam']['Departments'] as $id => $departments) {
					echo $departments .", ";
				}
			} ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Process Owner'); ?></td>
		<td>
			<?php echo $process['Owner']['name']; ?>
			&nbsp;
		</td></tr>

		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Team'); ?></td>
		<td>
			<?php if($process['ProcessTeam']['Users']){				
				foreach ($process['ProcessTeam']['Users'] as $id => $users) {
					echo $users .", ";
				}
			} ?>
			
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Measurement Details'); ?></td>
		<td>
			<?php echo h($process['ProcessTeam']['measurement_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo h($process['Schedule']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($process['ProcessTeam']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($process['ProcessTeam']['end_date']); ?>
			&nbsp;
		</td></tr>

		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Input Process'); ?></td>
		<td>
			<?php echo h($process['InputProcess']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Output Process'); ?></td>
		<td>
			<?php echo h($process['OutputProcess']['title']); ?>
			&nbsp;
		</td></tr>
		
</table>

	<?php } ?> 

<?php } ?>
