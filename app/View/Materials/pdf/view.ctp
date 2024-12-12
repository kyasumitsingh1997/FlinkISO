<h2><?php  echo __('Material'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($material['Material']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($material['Material']['description']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Qc Required'); ?></td>
		<td>
			<?php echo h($material['Material']['qc_required']) ? __('Yes') : __('No'); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($material['Material']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $material['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $material['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($material['Material']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($material['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $material['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
        <p>&nbsp;</p>

	<h3><?php echo __('Related Material List With Shelf Lives'); ?></h3>
	<?php if (!empty($material['MaterialListWithShelfLife'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Shelflife By Manufacturer'); ?></th>
		<th><?php echo __('Shelflife By Company'); ?></th>
		<th><?php echo __('Remarks'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($material['MaterialListWithShelfLife'] as $materialListWithShelfLife): ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $materialListWithShelfLife['shelflife_by_manufacturer']; ?></td>
			<td><?php echo $materialListWithShelfLife['shelflife_by_company']; ?></td>
			<td><?php echo $materialListWithShelfLife['remarks']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>



	<h3><?php echo __('Related Material Quality Checks'); ?></h3>
	<?php if (!empty($material['MaterialQualityCheck'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Sr. No'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Details'); ?></th>
		<th><?php echo __('Active Status'); ?></th>
<!--
		<th><?php // echo __('Approved By'); ?></th>
		<th><?php // echo __('Prepared By'); ?></th>
-->
	</tr>
	<?php
		$i = 1;
		foreach ($material['MaterialQualityCheck'] as $materialQualityCheck): ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $i; ?></td>
			<td><?php echo $materialQualityCheck['name']; ?></td>
			<td><?php echo $materialQualityCheck['details']; ?></td>
			<td><?php echo $materialQualityCheck['active_status'] ? 'Active' : 'Inactive'; ?></td>
<!--
			<td><?php // echo $materialQualityCheck['approved_by']; ?></td>
			<td><?php // echo $materialQualityCheck['prepared_by']; ?></td>
-->
		</tr>
	<?php $i++; endforeach; ?>
	</table>
<?php endif; ?>


