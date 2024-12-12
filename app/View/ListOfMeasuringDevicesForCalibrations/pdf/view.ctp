<h2><?php  echo __('List Of Measuring Devices For Calibration'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Device'); ?></td>
		<td>
			<?php echo $listOfMeasuringDevicesForCalibration['Device']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Least Count'); ?></td>
		<td>
			<?php echo h($listOfMeasuringDevicesForCalibration['ListOfMeasuringDevicesForCalibration']['least_count']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Required Accuracy'); ?></td>
		<td>
			<?php echo h($listOfMeasuringDevicesForCalibration['ListOfMeasuringDevicesForCalibration']['required_accuracy']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Range'); ?></td>
		<td>
			<?php echo h($listOfMeasuringDevicesForCalibration['ListOfMeasuringDevicesForCalibration']['range']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Default Calibration'); ?></td>
		<td>
			<?php echo h($listOfMeasuringDevicesForCalibration['ListOfMeasuringDevicesForCalibration']['default_calibration']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Required Calibration'); ?></td>
		<td>
			<?php echo h($listOfMeasuringDevicesForCalibration['ListOfMeasuringDevicesForCalibration']['required_calibration']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Actual Calibration'); ?></td>
		<td>
			<?php echo h($listOfMeasuringDevicesForCalibration['ListOfMeasuringDevicesForCalibration']['actual_calibration']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Calibration Frequency'); ?></td>
		<td>
			<?php echo $listOfMeasuringDevicesForCalibration['Schedule']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($listOfMeasuringDevicesForCalibration['ListOfMeasuringDevicesForCalibration']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $listOfMeasuringDevicesForCalibration['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $listOfMeasuringDevicesForCalibration['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($listOfMeasuringDevicesForCalibration['ListOfMeasuringDevicesForCalibration']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($listOfMeasuringDevicesForCalibration['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $listOfMeasuringDevicesForCalibration['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />