<h2><?php  echo __('List Of Computer'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $listOfComputer['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Make'); ?></td>
		<td>
			<?php echo h($listOfComputer['ListOfComputer']['make']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Serial Number'); ?></td>
		<td>
			<?php echo h($listOfComputer['ListOfComputer']['serial_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Supplier Registration'); ?></td>
		<td>
			<?php echo $listOfComputer['SupplierRegistration']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Purchase Order'); ?></td>
		<td>
			<?php echo $listOfComputer['PurchaseOrder']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Price'); ?></td>
		<td>
			<?php echo h($listOfComputer['ListOfComputer']['price']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Installation Date'); ?></td>
		<td>
			<?php echo h($listOfComputer['ListOfComputer']['installation_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Other Details'); ?></td>
		<td>
			<?php echo h($listOfComputer['ListOfComputer']['other_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($listOfComputer['ListOfComputer']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $listOfComputer['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $listOfComputer['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($listOfComputer['ListOfComputer']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $listOfComputer['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />

	<h3><?php echo __('Related List Of Computer List Of Softwares'); ?></h3>
	<?php if (!empty($listOfComputer['ListOfComputerListOfSoftware'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Software Name'); ?></th>
		<th><?php echo __('Installation Date'); ?></th>
		<th><?php echo __('Other Details'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Approved By'); ?></th>
		<th><?php echo __('Prepared By'); ?></th>
		<th><?php echo __('Modified'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($listOfComputerSoftware as $listOfComputerListOfSoftware): ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $listOfComputerListOfSoftware['ListOfSoftware']['name']; ?></td>
			<td><?php echo $listOfComputerListOfSoftware['ListOfComputerListOfSoftware']['installation_date']; ?></td>
			<td><?php echo $listOfComputerListOfSoftware['ListOfComputerListOfSoftware']['other_details']; ?></td>
			<td><?php echo $listOfComputerListOfSoftware['ListOfComputerListOfSoftware']['created']; ?></td>
			<td><?php echo $listOfComputerListOfSoftware['ApprovedBy']['name']; ?></td>
			<td><?php echo $listOfComputerListOfSoftware['PreparedBy']['name']; ?></td>
			<td><?php echo $listOfComputerListOfSoftware['ListOfComputerListOfSoftware']['modified']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>


