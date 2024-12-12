<h2><?php  echo __('Notification User'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Notification'); ?></td>
		<td>
			<?php echo $notificationUser['Notification']['title']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('User Id'); ?></td>
		<td>
			<?php echo h($notificationUser['NotificationUser']['user_id']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $notificationUser['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Status'); ?></td>
		<td>
			<?php echo h($notificationUser['NotificationUser']['status']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($notificationUser['NotificationUser']['created']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($notificationUser['NotificationUser']['modified']); ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
