<h2><?php  echo __('Data Back Up'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($dataBackUp['DataBackUp']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Data Type'); ?></td>
		<td>
			<?php echo $dataBackUp['DataType']['name']; ?>
			&nbsp;
		</td></tr>
<!--		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php // echo __('Data Back Up Id'); ?></td>
		<td>
			<?php // echo h($dataBackUp['DataBackUp']['data_back_up_id']); ?>
			&nbsp;
		</td></tr>-->
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $dataBackUp['Branch']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo $dataBackUp['Schedule']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('User Responsible'); ?></td>
		<td>
			<?php echo $dataBackUp['User']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($dataBackUp['DataBackUp']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $dataBackUp['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $dataBackUp['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($dataBackUp['DataBackUp']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($dataBackUp['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $dataBackUp['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />