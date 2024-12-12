<h2><?php  echo __('Computer Software'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Computer Name'); ?></td>
		<td>
			<?php echo $listOfComputerListOfSoftware['ListOfComputer']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Software Name'); ?></td>
		<td>
			<?php echo $listOfComputerListOfSoftware['ListOfSoftware']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Installation Date'); ?></td>
		<td>
			<?php echo h($listOfComputerListOfSoftware['ListOfComputerListOfSoftware']['installation_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Other Details'); ?></td>
		<td>
			<?php echo h($listOfComputerListOfSoftware['ListOfComputerListOfSoftware']['other_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($listOfComputerListOfSoftware['ListOfComputerListOfSoftware']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $listOfComputerListOfSoftware['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $listOfComputerListOfSoftware['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($listOfComputerListOfSoftware['ListOfComputerListOfSoftware']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($listOfComputerListOfSoftware['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $listOfComputerListOfSoftware['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />