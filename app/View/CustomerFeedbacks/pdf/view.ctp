<h2><?php  echo __('Customer Feedback'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Customer'); ?></td>
		<td>
			<?php echo $customerFeedback['Customer']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($customerFeedback['CustomerFeedback']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $customerFeedback['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $customerFeedback['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($customerFeedback['CustomerFeedback']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($customerFeedback['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $customerFeedback['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />

        <h3><?php echo "Questions"?></h3>
        <?php foreach ($customerFeedbackDetails as $customerFeedbackDetail) { ?>
            <h5><?php echo $customerFeedbackDetail['CustomerFeedbackQuestion']['title']; ?></h5>
            <?php if ($customerFeedbackDetail['CustomerFeedbackQuestion']['question_type'] == 0) { ?>
                <strong><?php echo 'Answer: '; ?></strong><?php echo $customerFeedbackDetail['CustomerFeedback']['answer']; ?><br />
            <?php } ?>
                <strong><?php echo 'Comments: '; ?></strong><?php echo h($customerFeedbackDetail['CustomerFeedback']['comments']); ?>
        <?php } ?>
        <br />


