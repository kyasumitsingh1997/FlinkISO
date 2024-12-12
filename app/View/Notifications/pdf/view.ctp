<h2><?php  echo __('Notification'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Notification Type'); ?></td>
		<td>
			<?php echo $notification['NotificationType']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Internal Audit Plan'); ?></td>
		<td>
			<?php echo $notification['InternalAuditPlan']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Meeting'); ?></td>
		<td>
			<?php echo $notification['Meeting']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($notification['Notification']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Message'); ?></td>
		<td>
			<?php echo h($notification['Notification']['message']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($notification['Notification']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($notification['Notification']['end_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $notification['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $notification['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($notification['Notification']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($notification['Notification']['modified']); ?>
			&nbsp;
		</td></tr>
	</table>
	<br />

	<h3><?php echo __('Related Notification Users'); ?></h3>
	<?php if (!empty($notification['NotificationUser'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Employee'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($notification['NotificationUser'] as $notificationUser): ?>
		<tr bgcolor="#FFFFFF">
                    <td><?php if(isset($PublishedEmployeeList[$notificationUser['employee_id']])){
                        echo $PublishedEmployeeList[$notificationUser['employee_id']];
                    } else {
                        echo "<strong><em><small>Employee deleted or unpublished.</small></em></strong>";
                    }?>
                    </td>
                    <td><?php echo ($notificationUser['status'] == 1)? "Read" : "Unread";?></td>
                    <td><?php echo $notificationUser['created']; ?></td>
                    <td><?php echo $notificationUser['modified']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
