<h2><?php  echo __('Training Need Identification'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Course'); ?></td>
		<td>
			<?php echo $trainingNeedIdentification['Course']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Remarks'); ?></td>
		<td>
			<?php echo h($trainingNeedIdentification['TrainingNeedIdentification']['remarks']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo $trainingNeedIdentification['Schedule']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($trainingNeedIdentification['TrainingNeedIdentification']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $trainingNeedIdentification['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $trainingNeedIdentification['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($trainingNeedIdentification['TrainingNeedIdentification']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($trainingNeedIdentification['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $trainingNeedIdentification['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
        <p>&nbsp;</p>

        <h3><?php echo __('Employee Details'); ?></h3>
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['name']; ?>
                        &nbsp;
                    </td><td class="head-strong"><?php echo __('Employee Number'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['employee_number']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['BranchIds']['name']; ?>
                        &nbsp;
                    </td><td class="head-strong"><?php echo __('Designation'); ?></td>
                    <td>
                        <?php echo h($trainingNeedIdentification['Designation']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Joining Date'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['joining_date']; ?>
                        &nbsp;
                    </td><td class="head-strong"><?php echo __('Date Of Birth'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['date_of_birth']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Pancard Number'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['pancard_number']; ?>
                        &nbsp;
                    </td><td class="head-strong"><?php echo __('Personal Telephone'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['personal_telephone']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Office Telephone'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['office_telephone']; ?>
                        &nbsp;
                    </td><td class="head-strong"><?php echo __('Mobile'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['mobile']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Personal Email'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['personal_email']; ?>
                        &nbsp;
                    </td><td class="head-strong"><?php echo __('Office Email'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['office_email']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Residence Address'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['residence_address']; ?>
                        &nbsp;
                    </td><td class="head-strong"><?php echo __('Permanent Address'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['permenant_address']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Marital Status'); ?></td>
                    <td>
                        <?php
                        if ($trainingNeedIdentification['Employee']['maritial_status'] != -1)
                            echo $trainingNeedIdentification['Employee']['maritial_status'];
                        else
                            echo '';
                        ?>
                        &nbsp;
                    </td><td class="head-strong"><?php echo __('Driving License'); ?></td>
                    <td>
                        <?php echo $trainingNeedIdentification['Employee']['driving_license']; ?>
                        &nbsp;
                    </td>
                </tr>
            </table>
        <p>&nbsp;</p>

        <h3><?php echo __('Trainings Attended'); ?></h3>
        <?php if(!empty($trainings)){ ?>
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
                <tr bgcolor="#FFFFFF">
                    <th><?php echo __('Course') ?></th>
                    <th><?php echo __('Training Details') ?></th>
                    <th><?php echo __('Training date') ?></th>
                </tr>
                <?php
                    foreach ($trainings as $training):
                        if ($training) {
                ?>
                <tr bgcolor="#FFFFFF">
                    <td><strong><?php echo $training['Course']['title'] ?></strong></td>
                    <td><?php echo $training['Training']['title'] ?><br /><?php echo $training['Training']['description'] ?></td>
                    <td><?php echo date('Y-m-d', strtotime($training['Training']['start_date_time'])) ?></td>
                </tr>
                <?php } endforeach; } ?>
            </table>