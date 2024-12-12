<h2><?php  echo __('Summery Of Supplier Evaluation'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Supplier'); ?></td>
		<td>
			<?php echo $summeryOfSupplierEvaluation['SupplierRegistration']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Supplier Category'); ?></td>
		<td>
			<?php echo $summeryOfSupplierEvaluation['SupplierCategory']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Remarks'); ?></td>
		<td>
			<?php echo h($summeryOfSupplierEvaluation['SummeryOfSupplierEvaluation']['remarks']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Evaluation Date'); ?></td>
		<td>
			<?php echo h($summeryOfSupplierEvaluation['SummeryOfSupplierEvaluation']['evaluation_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $summeryOfSupplierEvaluation['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($summeryOfSupplierEvaluation['SummeryOfSupplierEvaluation']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $summeryOfSupplierEvaluation['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $summeryOfSupplierEvaluation['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($summeryOfSupplierEvaluation['SummeryOfSupplierEvaluation']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($summeryOfSupplierEvaluation['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $summeryOfSupplierEvaluation['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />
