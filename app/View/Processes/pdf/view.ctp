<h2><?php echo __('View Process'); ?></h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong" width="20%"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($process['Process']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Objective'); ?></td>
		<td>
			<?php echo $this->Html->link($process['Objective']['title'], array('controller' => 'objectives', 'action' => 'view', $process['Objective']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Clauses'); ?></td>
		<td>
			<?php echo h($process['Process']['clauses']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Process Requirments'); ?></td>
		<td>
			<?php echo h($process['Process']['process_requirments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Input Process'); ?></td>
		<td>
			<?php echo $this->Html->link($process['InputProcess']['title'], array('controller' => 'input_processes', 'action' => 'view', $process['InputProcess']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Output Process'); ?></td>
		<td>
			<?php echo $this->Html->link($process['OutputProcess']['title'], array('controller' => 'output_processes', 'action' => 'view', $process['OutputProcess']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo $this->Html->link($process['Schedule']['name'], array('controller' => 'schedules', 'action' => 'view', $process['Schedule']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($process['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>

	<td><?php echo h($process['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Publish'); ?></td>

		<td>
			<?php if($process['Process']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($process['Process']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<h2><?php echo __('Process Team'); ?></h2>
<table class="table table-responsive">
	<?php foreach($process['ProcessTeam'] as $team) : ?>  
	<tr bgcolor="#FFFFFF"><td width="20%"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($team['name']); ?>
			&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Owner'); ?></td>
		<td>
			<?php echo h($PublishedUserList[$team['owner_id']]); ?>
			&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Team'); ?></td>
		<td>
			<?php 
				$process_teams = json_decode($team['team']); 
				foreach ($process_teams as $process_team) {
					echo h($PublishedUserList[$process_team]) . ', ';
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branch'); ?></td>
		<td>
			<?php 
				$branches = json_decode($team['branch_id']); 
				foreach ($branches as $branch) {
					echo h($PublishedBranchList[$branch]) . ', ';
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Department'); ?></td>
		<td>
			<?php 
				$departments = json_decode($team['department_id']); 
				foreach ($departments as $department) {
					echo h($PublishedDepartmentList[$department]) . ', ';
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Target'); ?></td>
		<td>
			<?php echo h($team['target']); ?>
			&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo date('Y-m-d',strtotime($team['start_date'])); ?>
			&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('End Date'); ?></td>
		<td>
			<?php echo date('Y-m-d',strtotime($team['end_date'])); ?>
			&nbsp;
		</td>
	</tr>
	
</table>
<?php endforeach ?>
<h2><?php echo __('Process Related Tasks'); ?></h2>
<?php foreach ($process['Task'] as $task) : ?>
	<table class="table table-responsive">
		<tr bgcolor="#FFFFFF">
			<td width="20%"><?php echo __('Tasks'); ?></td>
			<td>
				<?php echo h($task['name']); ?>
				&nbsp;
			</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Assigned To'); ?></td>
			<td>
				<?php echo h($schedules[$task['user_id']]); ?>
				&nbsp;
			</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Task Description'); ?></td>
			<td>
				<?php echo h($task['description']); ?>
				&nbsp;
			</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Task Schedule'); ?></td>
			<td>
				<?php echo h($schedules[$task['schedule_id']]); ?>
				&nbsp;
			</td>
		</tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo date('Y-m-d',strtotime($task['start_date'])); ?>
			&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('End Date'); ?></td>
		<td>
			<?php echo date('Y-m-d',strtotime($task['end_date'])); ?>
			&nbsp;
		</td>
	</tr>
	</table>	
<?php endforeach; ?>
