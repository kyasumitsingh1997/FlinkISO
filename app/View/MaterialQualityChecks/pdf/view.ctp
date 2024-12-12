<h2><?php  echo __('Material Quality Check'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4"  width="100%">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Material'); ?></td>
		<td>
			<?php echo $materialName['Material']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo h($PublishedEmployeeList[$materialQualityChecks[0]['MaterialQualityCheck']['approved_by']]); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo h($PublishedEmployeeList[$materialQualityChecks[0]['MaterialQualityCheck']['prepared_by']]); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($materialQualityCheck['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $materialQualityCheck['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
        <p>&nbsp;</p>

	<h3><?php echo __('Related Material Quality Checks'); ?></h3>
	<?php if (!empty($materialQualityChecks)): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
	<?php
		$i = 0;
                foreach ($materialQualityChecks as $materialQualityCheck): ?>

                <tr bgcolor="#FFFFFF"><th colspan="2"><label><?php
                        echo h('Step - ');
                        echo ++$i;
                    ?></label></th>
                </tr>
                    <tr bgcolor="#FFFFFF"><td><?php echo __('Name'); ?></td>
                        <td>
                            <?php echo h($materialQualityCheck['MaterialQualityCheck']['name']); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"><td><?php echo __('Details'); ?></td>
                        <td>
                            <?php echo h($materialQualityCheck['MaterialQualityCheck']['details']); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"><td><?php echo __('QC Template'); ?></td>
                        <td>
                            <?php echo $materialQualityCheck['MaterialQualityCheck']['qc_template']; ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"><td><?php echo __('Is Last Step'); ?></td>
                        <td>
                            <?php if ($materialQualityCheck['MaterialQualityCheck']['is_last_step'] == 1) { ?>
                                <?php echo __('Yes'); ?>
                            <?php } else { ?>
                                <?php echo __('No'); ?>
                            <?php } ?>&nbsp;</td>
                    </tr>
                    <tr bgcolor="#FFFFFF"><td><?php echo __('Is Active'); ?></td>
                        <td>
                            <?php if ($materialQualityCheck['MaterialQualityCheck']['active_status'] == 1) { ?>
                                <?php echo __('Yes'); ?>
                            <?php } else { ?>
                                <?php echo __('No'); ?>
                            <?php } ?>&nbsp;
                        </td>
                    </tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
