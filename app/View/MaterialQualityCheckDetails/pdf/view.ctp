<h2><?php  echo __('Material Quality Check Detail'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Material Quality Check'); ?></td>
		<td>
			<?php echo $materialQualityCheckDetail['MaterialQualityCheck']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Delivery Challan'); ?></td>
		<td>
			<?php echo $materialQualityCheckDetail['DeliveryChallan']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $materialQualityCheckDetail['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Check Performed Date'); ?></td>
		<td>
			<?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['check_performed_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Quantity Received'); ?></td>
		<td>
			<?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['quantity_received']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Quantity Accepted'); ?></td>
		<td>
			<?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['quantity_accepted']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $materialQualityCheckDetail['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $materialQualityCheckDetail['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $materialQualityCheckDetail['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
