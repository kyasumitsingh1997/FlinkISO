<h2><?php  echo __('Fire Extinguisher'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Fire Extinguisher Type'); ?></td>
		<td>
			<?php echo $fireExtinguisher['FireExtinguisherType']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Company Name'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['company_name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['description']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Purchase Date'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['purchase_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Expeiry Date'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['expeiry_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Warrenty Expiry Date'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['warrenty_expiry_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Model Type'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['model_type']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Other Remarks'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['other_remarks']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $fireExtinguisher['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $fireExtinguisher['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($fireExtinguisher['FireExtinguisher']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($fireExtinguisher['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $fireExtinguisher['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />