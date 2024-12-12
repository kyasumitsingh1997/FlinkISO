<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo __('Title for internal use'); ?></td>
					<td><?php echo h($proposal['Proposal']['title']); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo __('To'); ?></td>
					<td><?php echo $this->Html->link($proposal['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $proposal['Customer']['id'])); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo __('From'); ?></td>
					<td><?php echo $this->Html->link($proposal['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $proposal['Employee']['id'])); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo __('Subject'); ?></td>
					<td><?php echo h($proposal['Proposal']['proposal_heading']); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td colspan="2"><h5><?php echo __('High Level Details (Internal Use Only & will not be sent to customer)'); ?></h5>
					<?php echo $proposal['Proposal']['proposal_details']; ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td colspan="2"><h5><?php echo __('Email Content'); ?></h5>
					<?php echo $proposal['Proposal']['email_body']; ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td colspan="2"><h5><?php echo __('Notes (Internal use)'); ?></h5>
					<?php echo $proposal['Proposal']['notes']; ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td colspan="2"><h5><?php echo __('Proposal Sent Date'); ?></h5>
					<?php echo $proposal['Proposal']['proposal_sent_date']; ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo __('Prepared By'); ?></td>
					<td><?php echo h($proposal['PreparedBy']['name']); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo __('Approved By'); ?></td>
					<td><?php echo h($proposal['ApprovedBy']['name']); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo __('Publish'); ?></td>
					<td><?php if ($proposal['Proposal']['publish'] == 1) { ?>
						<span class="fa fa-check"></span>
						<?php } else { ?>
						<span class="fa fa-ban"></span>
						<?php } ?>
						&nbsp;</td>
					&nbsp; </tr>
</table>
			<br>
			<hr>
<?php foreach ($followups as $followup) : ?>
			<h4><?php echo __('View Proposal Followups'); ?>&nbsp;</h4>
			<table class="table table-responsive">
				<tr <tr bgcolor="#FFFFFF">>
					<td><b><?php echo __('Sr. No'); ?></td>
					<td><b><?php echo __('Followup Date'); ?></td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo h($followup['ProposalFollowup']['sr_no']); ?> &nbsp; </td>
					<td><?php echo h($followup['ProposalFollowup']['followup_date']); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><b><?php echo __('Customer'); ?></td>
					<td><b><?php echo __('Employee'); ?></td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo $this->Html->link($followup['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $followup['Customer']['id'])); ?> &nbsp; </td>
					<td><?php echo $this->Html->link($followup['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $followup['Employee']['id'])); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><b><?php echo __('Proposal Heading'); ?></td>
					<td><b><?php echo __('Proposal Details'); ?></td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo h($followup['ProposalFollowup']['followup_heading']); ?> &nbsp; </td>
					<td><?php echo h($followup['ProposalFollowup']['followup_details']); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><b><?php echo __('Next Followup Date'); ?></td>
					<td><b><?php echo __('Status'); ?></td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo h($followup['ProposalFollowup']['next_follow_up_date']); ?> &nbsp; </td>
					<td><?php echo h($followup['ProposalFollowup']['status']); ?> &nbsp; </td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><b><?php echo __('Publish'); ?></td>
					<td><?php if ($followup['ProposalFollowup']['publish'] == 1) { ?>
						<span class="fa fa-check"></span>
						<?php } else { ?>
						<span class="fa fa-ban"></span>
						<?php } ?>
						&nbsp;</td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><b><?php echo __('Branch'); ?></td>
					<td><b><?php echo __('Department'); ?></td>
				</tr>
				<tr <tr bgcolor="#FFFFFF">>
					<td><?php echo $this->Html->link($followup['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $followup['BranchIds']['id'])); ?> &nbsp; </td>
					<td><?php echo $this->Html->link($followup['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $followup['DepartmentIds']['id'])); ?> &nbsp; </td>
				</tr>
			</table>
			<?php endforeach; ?>
			
