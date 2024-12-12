<h2><?php  echo __('Software Type'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($softwareType['SoftwareType']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($softwareType['SoftwareType']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $softwareType['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $softwareType['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($softwareType['SoftwareType']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($softwareType['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $softwareType['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />