<h2><?php  echo __('Customer Complaint'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Type'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['type'] ? __('Customer Feedback') : __('Customer Complaint')); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Customer'); ?></td>
		<td>
			<?php echo $customerComplaint['Customer']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Complaint Source'); ?></td>
		<td>
			<?php
                            if ($customerComplaint['CustomerComplaint']['complaint_source'] == 0) {
                                echo h($customerComplaint['Product']['name']);
                            } elseif ($customerComplaint['CustomerComplaint']['complaint_source'] == 1) {
                                echo "Service";
                            } elseif ($customerComplaint['CustomerComplaint']['complaint_source'] == 2) {
                                echo "Delivery Challan No: " . h($customerComplaint['DeliveryChallan']['challan_number']);
                            } else {
                                echo "Customer Care";
                            }
                        ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Complaint Number'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['complaint_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Complaint Date'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['complaint_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Target Date'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['target_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Assigned to'); ?></td>
		<td>
			<?php echo $customerComplaint['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Action Taken'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['action_taken']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Action Taken Date'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['action_taken_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo $customerComplaint['CustomerComplaint']['current_status'] ? __('Close') : __('Open'); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Settled Date'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['settled_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Authorised By'); ?></td>
		<td>
			<?php echo $customerComplaint['AuthorisedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $customerComplaint['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $customerComplaint['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($customerComplaint['CustomerComplaint']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($customerComplaint['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $customerComplaint['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />