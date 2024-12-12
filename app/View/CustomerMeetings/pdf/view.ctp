<h2><?php  echo __('Customer Meeting'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Customer'); ?></td>
		<td>
			<?php echo $customerMeeting['Customer']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Client Id'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['client_id']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $customerMeeting['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Proposal Followup'); ?></td>
		<td>
			<?php echo $customerMeeting['ProposalFollowup']['followup_date']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Followup Id'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['followup_id']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Meeting Date'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['meeting_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Action Point'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['action_point']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['details']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Next Meeting Date'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['next_meeting_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Status'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['status']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Active Lock'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['active_lock']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $customerMeeting['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $customerMeeting['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($customerMeeting['CustomerMeeting']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $customerMeeting['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
        <p>&nbsp;</p>

        <h3><?php echo __('Related Followups')?>&nbsp;</h3>
        <?php
                $x = 0;
                if (isset($followups)) {
                    foreach ($followups as $followup):
                    ++$x;
        ?>
            <h5><?php echo __('Followup') . ' - ' . $x; ?>&nbsp;</h5>
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
                <tr bgcolor="#FFFFFF">
                    <td class="head-strong"><?php echo __('Employee'); ?></td>
                    <td><?php echo $followup['Employee']['name']; ?>&nbsp;</td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td class="head-strong"><?php echo __('Action Point'); ?></td>
                    <td><?php echo h($followup['CustomerMeeting']['action_point']); ?>&nbsp;</td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td class="head-strong"><?php echo __('Meeting Date'); ?></td>
                    <td><?php echo h($followup['CustomerMeeting']['meeting_date']); ?></td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td class="head-strong"><?php echo __('Next Meeting Date'); ?></td>
                    <td><?php echo h($followup['CustomerMeeting']['next_meeting_date']); ?>&nbsp;</td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td class="head-strong"><?php echo __('Details'); ?></td>
                    <td><?php echo h($followup['CustomerMeeting']['details']); ?>&nbsp;</td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td class="head-strong"><?php echo __('Status'); ?></td>
                    <td><?php echo h($followup['CustomerMeeting']['status']); ?>&nbsp;</td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td class="head-strong"><?php echo __('Branch'); ?></td>
                    <td><?php echo $followup['BranchIds']['name']; ?>&nbsp;</td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td class="head-strong"><?php echo __('Department'); ?></td>
                    <td><?php echo $followup['DepartmentIds']['name']; ?>&nbsp;</td>
                </tr>
            </table>
            <p>&nbsp;</p>
        <?php
            endforeach;
        }
        ?>
