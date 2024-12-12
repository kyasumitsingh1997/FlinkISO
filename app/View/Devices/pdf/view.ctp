<h2><?php  echo __('Device'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($device['Device']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Category'); ?></td>
		<td>
			<?php echo h($device['DeviceCategory']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Number'); ?></td>
		<td>
			<?php echo h($device['Device']['number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Serial'); ?></td>
		<td>
			<?php echo h($device['Device']['serial']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Manual'); ?></td>
		<td>
			<?php
                        if ($device['Device']['manual'] == 0) {
                            echo "Available";
                        } elseif ($device['Device']['manual'] == 1) {
                            echo "Not Available";
                        } else {
                            echo "Not Required";
                        }
                        ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Sparelist'); ?></td>
		<td>
			<?php
                        if ($device['Device']['sparelist'] == 0) {
                            echo "Available";
                        } elseif ($device['Device']['sparelist'] == 1) {
                            echo "Not Available";
                        } else {
                            echo "Not Required";
                        }
                        ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($device['Device']['description']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Make Type'); ?></td>
		<td>
			<?php echo h($device['Device']['make_type']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Supplier Registration'); ?></td>
		<td>
			<?php echo $device['SupplierRegistration']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Purchase Date'); ?></td>
		<td>
			<?php echo h($device['Device']['purchase_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Person Responsible for Maintenance'); ?></td>
		<td>
			<?php echo $device['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $device['Branch']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Department'); ?></td>
		<td>
			<?php echo $device['Department']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Calibration Required'); ?></td>
		<td>
			<?php echo ($device['Device']['calibration_required'] == 0) ? 'Yes' : 'No'; ?>
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Maintenance Required'); ?></td>
		<td>
			<?php echo ($device['Device']['maintenance_required'] == 1) ? 'Yes' : 'No'; ?>
                    &nbsp;
		</td></tr>
                <?php if($device['Device']['maintenance_required'] == 1) { ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Maintenance Frequency'); ?></td>
		<td>
			<?php echo $device['MaintenanceFrequency']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Maintenance Details'); ?></td>
		<td>
			<?php echo h($device['Device']['maintenance_details']); ?>
			&nbsp;
		</td></tr>
                <?php } ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($device['Device']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $device['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $device['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($device['Device']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($device['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $device['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
        <p>&nbsp;</p>

            <?php if ($device['Device']['calibration_required'] == 0) { ?>
                <h3><?php echo h($device['Device']['name'] . ' Calibration Details'); ?></h3>
                <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
                    <tr bgcolor="#FFFFFF">
                        <th><?php echo __('Calibration Frequency'); ?></th>
                        <th><?php echo __('Least Count'); ?></th>
                        <th><?php echo __('Required Accuracy'); ?></th>
                        <th><?php echo __('Range'); ?></th>
                        <th><?php echo __('Default Calibration'); ?></th>
                        <th><?php echo __('Required Calibration'); ?></th>
                        <th><?php echo __('Actual Calibration'); ?></th>
                    </tr>

                    <tr bgcolor="#FFFFFF">
                        <td><?php
                            $calibrationFrequencies = $this->requestAction('App/get_model_list/Schedule');
                            echo $calibrationFrequencies[$device['Device']['calibration_frequency']];
                            ?>&nbsp;</td>
                        <td><?php echo $device['Device']['least_count']; ?>&nbsp;</td>
                        <td><?php echo $device['Device']['required_accuracy']; ?>&nbsp;</td>
                        <td><?php echo $device['Device']['range']; ?>&nbsp;</td>
                        <td><?php echo $device['Device']['default_calibration']; ?>&nbsp;</td>
                        <td><?php echo $device['Device']['required_calibration']; ?>&nbsp;</td>
                        <td><?php echo $device['Device']['actual_calibration']; ?>&nbsp;</td>
                    </tr>
                </table>

            <?php } ?>
