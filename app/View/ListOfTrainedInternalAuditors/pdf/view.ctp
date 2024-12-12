<h2><?php  echo __('List Of Trained Internal Auditor'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $listOfTrainedInternalAuditor['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Training'); ?></td>
		<td>
			<?php echo $listOfTrainedInternalAuditor['Training']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Note'); ?></td>
		<td>
			<?php echo h($listOfTrainedInternalAuditor['ListOfTrainedInternalAuditor']['note']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($listOfTrainedInternalAuditor['ListOfTrainedInternalAuditor']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $listOfTrainedInternalAuditor['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $listOfTrainedInternalAuditor['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($listOfTrainedInternalAuditor['ListOfTrainedInternalAuditor']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($listOfTrainedInternalAuditor['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $listOfTrainedInternalAuditor['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />
