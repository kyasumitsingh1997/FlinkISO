<h2><?php  echo __('Customer Feedback Question'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Question Type'); ?></td>
		<td>
			<?php echo ($customerFeedbackQuestion['CustomerFeedbackQuestion']['question_type'] == 0)? "Optional" : "Comment"; ?>
			&nbsp;
		</td></tr>
                <?php if($customerFeedbackQuestion['CustomerFeedbackQuestion']['question_type'] != 1){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Option One'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['option_one']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Option Two'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['option_two']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Option Three'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['option_three']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Option Four'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['option_four']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Option Five'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['option_five']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Option Six'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['option_six']); ?>
			&nbsp;
		</td></tr>
                <?php } ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $customerFeedbackQuestion['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $customerFeedbackQuestion['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($customerFeedbackQuestion['CustomerFeedbackQuestion']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($customerFeedbackQuestion['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $customerFeedbackQuestion['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />