<h2><?php  echo __('Appraisal'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee Name'); ?></td>
		<td>
			<?php echo $appraisal['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Appraisal Date'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['appraisal_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Appraiser By'); ?></td>
		<td>
			<?php echo $appraisal['AppraiserBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Reason'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['reason']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Self Appraisal Needed'); ?></td>
		<td>
			<?php echo ($appraisal['Appraisal']['self_appraisal_needed'] == 1)? "Yes" : "No"; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Self Appraisal Status'); ?></td>
		<td>
			<?php echo ($appraisal['Appraisal']['self_appraisal_status'] == 1)? "Done" : "Pending"; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Rating'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['rating']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee Comments'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['employee_comments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Appraiser Comments'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['appraiser_comments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Promotion'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['promotion']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Warning'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['warning']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Status Remained Unchanged'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['status_remained_unchanged']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Successful Probation Completion'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['successful_probation_completion']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Salary Increment'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['salary_increment']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Termination'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['termination']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Training Requirements'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['training_requirements']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Specific Requirement'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['specific_requirement']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Increament'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['increament']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Appraisal Token'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['appraisal_token']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Appraisal Token Expires'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['appraisal_token_expires']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $appraisal['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $appraisal['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($appraisal['Appraisal']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($appraisal['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $appraisal['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
        <p>&nbsp;</p>

	<!--<h3><?php // echo __('Related Employee Appraisal Questions'); ?></h3>-->
	<?php /*if (!empty($appraisal['EmployeeAppraisalQuestion'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Appraisal'); ?></th>
		<th><?php echo __('Appraisal Question'); ?></th>
		<th><?php echo __('Answer'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Approved By'); ?></th>
		<th><?php echo __('Prepared By'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Master List Of Format'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($appraisal['EmployeeAppraisalQuestion'] as $employeeAppraisalQuestion): ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $employeeAppraisalQuestion['appraisal_id']; ?></td>
			<td><?php echo $employeeAppraisalQuestion['appraisal_question_id']; ?></td>
			<td><?php echo $employeeAppraisalQuestion['answer']; ?></td>
			<td><?php echo $employeeAppraisalQuestion['created']; ?></td>
			<td><?php echo $employeeAppraisalQuestion['approved_by']; ?></td>
			<td><?php echo $employeeAppraisalQuestion['prepared_by']; ?></td>
			<td><?php echo $employeeAppraisalQuestion['modified']; ?></td>
			<td><?php echo $employeeAppraisalQuestion['master_list_of_format_id']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; */?>
			<div class="row">
                <div class="col-md-12">
                    <h5 class="text-center"><strong><?php echo __('Employee Appraisal Questions'); ?></strong></h5>&nbsp;
                    <br />
                    <?php foreach ($appraisal['EmployeeAppraisalQuestion'] as $quest): ?>
                        <strong><?php echo h($questions[$quest['appraisal_question_id']]); ?></strong>
                        <br/>
                        <?php echo h($quest['answer']); ?>
                        <br/>&nbsp;
                        <br/>
                    <?php endforeach; ?>
                </div>
            </div>

<h3><?php echo __('Key Responsibility Areas'); ?> </h3>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
    <tr bgcolor="#FFFFFF">
        <th><?php echo __('Sr. No'); ?></th>
        <th><?php echo __('Title'); ?></th>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Target'); ?></th>
        <th><?php echo __('Target Achieved'); ?></th>
    </tr>
    <?php $key=0; $i = 1; foreach ($kras as $kra):?>
        <tr bgcolor="#FFFFFF">
            <td><?php echo $i; ?>&nbsp;</td>
            <td><?php echo $kra['EmployeeKra']['title']; ?>&nbsp;</td>
            <td><?php echo $kra['EmployeeKra']['description']; ?>&nbsp;</td>
            <td><?php echo $kra['EmployeeKra']['target']; ?>&nbsp;</td>
            <td><?php echo $kra['EmployeeKra']['target_achieved']; ?>%</td>
        </tr>
    <?php $key++; $i++; endforeach; ?>
</table>
