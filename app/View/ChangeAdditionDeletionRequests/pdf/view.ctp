<h2><?php  echo __('Change Addition Deletion Request'); ?></h2>
	<div style="width:100%; flot:left">
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
                <?php if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id'] != -1) { ?>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Request From: Branch'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['Branch']['name']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id'] != -1) { ?>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Request From: Department'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['Department']['name']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id'] != -1) { ?>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Request From: Employee'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['Employee']['name']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id'] != -1) { ?>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Request From: Customer'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['Customer']['name']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id'] != -1) { ?>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Request From: Suggestion Form'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['SuggestionForm']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } else { ?>
                <tr bgcolor="#FFFFFF"><td><?php echo __('Request From: Other'); ?></td>
		<td>
			<?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']); ?>
			&nbsp;
		</td></tr>
                <?php } ?>
                <tr bgcolor="#FFFFFF"><td><?php echo __('Request Details'); ?></td>
		<td>
			<?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['request_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	</div>
	<div style="margin:40px; width:100%; flot:left">
		<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
			<tr bgcolor="#FFFFFF">
				<td><strong><?php echo __('Previous Document Details'); ?></strong></td>
				<td><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['previous_document_details']; ?>&nbsp;</td>			
			</tr>
			<tr bgcolor="#FFFFFF">
				<td><strong><?php echo __('Current Document Details'); ?></strong></td>
				<td><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['current_document_details']; ?>&nbsp;</td>			
			</tr>
			<tr bgcolor="#FFFFFF">
				<td><strong><?php echo __('Current Work Instructions'); ?></strong></td>
				<td><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['current_work_instructions']; ?>&nbsp;</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td><strong><?php echo __('Proposed Document Changes'); ?></strong></td>
				<td><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['proposed_document_changes']; ?>&nbsp;</tr>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td><strong><?php echo __('Proposed Work Instruction Changes'); ?></strong></td>
				<td><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes']; ?>&nbsp;</td>
			</tr>
		</table>
	</div>

<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Reason For Change'); ?></td>
		<td>
			<?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['reason_for_change']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Document Change Accepted'); ?></td>
		<td>
			<?php switch ($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted']) {
                            case 0 : echo "Rejected"; break;
                            case 1 : echo "Accepted"; break;
                            case 2 : echo "Open"; break;
                        } ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Flinkiso Functionality Change Required'); ?></td>
		<td>
			<?php echo ($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['flinkiso_functionality_change_required'] == 1)? "Yes" : "No"; ?>
			&nbsp;
		</td></tr>
                <?php if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['flinkiso_functionality_change_required'] == 1) { ?>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Flinkiso Functionality Change Details'); ?></td>
		<td>
			<?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['flinkiso_functionality_change_details']); ?>
			&nbsp;
		</td></tr>
                <?php } ?>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Meeting'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['Meeting']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $changeAdditionDeletionRequest['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format Id'); ?></td>
		<td>
			<?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['master_list_of_format_id']); ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
