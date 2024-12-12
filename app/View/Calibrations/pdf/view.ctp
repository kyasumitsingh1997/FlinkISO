<h2><?php  echo __('Calibration'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Device'); ?></td>
		<td>
			<?php echo $calibration['Device']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Calibration Date'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['calibration_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Measurement For'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['measurement_for']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Least Count'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['least_count']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Required Accuracy'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['required_accuracy']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Range'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['range']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Default Calibration'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['default_calibration']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Required Calibration'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['required_calibration']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Actual Calibration'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['actual_calibration']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Errors'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['errors']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Comments'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['comments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Next Calibration Date'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['next_calibration_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $calibration['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $calibration['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($calibration['Calibration']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($calibration['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $calibration['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />