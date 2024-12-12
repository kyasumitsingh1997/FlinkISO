<h2><?php  echo __('Proposal Followup'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Proposal'); ?></td>
		<td>
			<?php echo $proposalFollowup['Proposal']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Customer'); ?></td>
		<td>
			<?php echo $proposalFollowup['Customer']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Customer Meeting'); ?></td>
		<td>
			<?php echo $proposalFollowup['CustomerMeeting']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Client'); ?></td>
		<td>
			<?php echo $proposalFollowup['Client']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $proposalFollowup['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Followup Date'); ?></td>
		<td>
			<?php echo h($proposalFollowup['ProposalFollowup']['followup_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Followup Heading'); ?></td>
		<td>
			<?php echo h($proposalFollowup['ProposalFollowup']['followup_heading']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Followup Details'); ?></td>
		<td>
			<?php echo h($proposalFollowup['ProposalFollowup']['followup_details']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Next Follow Up Date'); ?></td>
		<td>
			<?php echo h($proposalFollowup['ProposalFollowup']['next_follow_up_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Status'); ?></td>
		<td>
			<?php echo h($proposalFollowup['ProposalFollowup']['status']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($proposalFollowup['ProposalFollowup']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $proposalFollowup['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $proposalFollowup['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($proposalFollowup['ProposalFollowup']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $proposalFollowup['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
