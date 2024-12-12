<h2><?php  echo __('Employee'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($employee['Employee']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee Number'); ?></td>
		<td>
			<?php echo h($employee['Employee']['employee_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Identification Number'); ?></td>
		<td>
			<?php echo h($employee['Employee']['identification_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $employee['Branch']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Designation'); ?></td>
		<td>
			<?php echo $employee['Designation']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Qualification'); ?></td>
		<td>
			<?php echo h($employee['Employee']['qualification']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Joining Date'); ?></td>
		<td>
			<?php echo h($employee['Employee']['joining_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Date Of Birth'); ?></td>
		<td>
			<?php echo h($employee['Employee']['date_of_birth']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Pancard Number'); ?></td>
		<td>
			<?php echo h($employee['Employee']['pancard_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Personal Telephone'); ?></td>
		<td>
			<?php echo h($employee['Employee']['personal_telephone']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Office Telephone'); ?></td>
		<td>
			<?php echo h($employee['Employee']['office_telephone']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Mobile'); ?></td>
		<td>
			<?php echo h($employee['Employee']['mobile']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Personal Email'); ?></td>
		<td>
			<?php echo h($employee['Employee']['personal_email']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Office Email'); ?></td>
		<td>
			<?php echo h($employee['Employee']['office_email']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Residence Address'); ?></td>
		<td>
			<?php echo h($employee['Employee']['residence_address']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Permenant Address'); ?></td>
		<td>
			<?php echo h($employee['Employee']['permenant_address']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Maritial Status'); ?></td>
		<td>
                    <?php
                        if ($employee['Employee']['maritial_status'] != -1)
                            echo $employee['Employee']['maritial_status'];
                        else
                            echo '';
                        ?>
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Driving License'); ?></td>
		<td>
			<?php echo h($employee['Employee']['driving_license']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employment Status'); ?></td>
		<td>
			<?php echo $employee['Employee']['employment_status'] ? __('Active') : __('Resigned'); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($employee['Employee']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $employee['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $employee['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($employee['Employee']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($employee['MasterListOfFormat']['title'])) { ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $employee['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
        <p>&nbsp;</p>

        <h3><?php echo __('Key Responsibilty Areas'); ?></h3>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Target'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($kraLists as $kraList): ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $kraList['EmployeeKra']['title']; ?></td>
			<td><?php echo $kraList['EmployeeKra']['description']; ?></td>
			<td><?php echo $kraList['EmployeeKra']['target']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
        <p>&nbsp;</p>

        <h3><?php echo __('Related Employee Trainings'); ?></h3>
	<?php if (!empty($employee['EmployeeTraining'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Employee'); ?></th>
		<th><?php echo __('Training'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Approved By'); ?></th>
		<th><?php echo __('Prepared By'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Master List Of Format'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($employeeTrainings as $employeeTraining): ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $employeeTraining['Employee']['name']; ?></td>
			<td><?php echo $employeeTraining['Training']['title']; ?></td>
			<td><?php echo $employeeTraining['EmployeeTraining']['created']; ?></td>
			<td><?php echo $employeeTraining['ApprovedBy']['name']; ?></td>
			<td><?php echo $employeeTraining['PreparedBy']['name']; ?></td>
			<td><?php echo $employeeTraining['EmployeeTraining']['modified']; ?></td>
			<td><?php echo $employeeTraining['MasterListOfFormat']['title']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
        <?php endif; ?>
        <p>&nbsp;</p>

	<h3><?php echo __('Related Training Need Identifications'); ?></h3>
	<?php if (!empty($employee['TrainingNeedIdentification'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Employee'); ?></th>
		<th><?php echo __('Course'); ?></th>
		<th><?php echo __('Remarks'); ?></th>
		<th><?php echo __('Schedule'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Approved By'); ?></th>
		<th><?php echo __('Prepared By'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Master List Of Format'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($trainings as $training):?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $training['Employee']['name']; ?></td>
			<td><?php echo $training['Course']['title']; ?></td>
			<td><?php echo $training['TrainingNeedIdentification']['remarks']; ?></td>
			<td><?php echo $training['Schedule']['name']; ?></td>
			<td><?php echo $training['TrainingNeedIdentification']['created']; ?></td>
			<td><?php echo $training['ApprovedBy']['name']; ?></td>
			<td><?php echo $training['PreparedBy']['name']; ?></td>
			<td><?php echo $training['TrainingNeedIdentification']['modified']; ?></td>
			<td><?php echo $training['MasterListOfFormat']['title']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
        <?php endif; ?>


