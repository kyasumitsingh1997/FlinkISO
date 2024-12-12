<h2><?php  echo __('Supplier Evaluation Reevaluation'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td><?php echo __('Supplier Registration'); ?></td>
		<td>
			<?php echo $supplierEvaluationReevaluation['SupplierRegistration']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Delivery Challan'); ?></td>
		<td>
			<?php echo $supplierEvaluationReevaluation['DeliveryChallan']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Challan Date'); ?></td>
		<td>
			<?php echo h($supplierEvaluationReevaluation['SupplierEvaluationReevaluation']['challan_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Material'); ?></td>
		<td>
			<?php echo $supplierEvaluationReevaluation['Material']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Product'); ?></td>
		<td>
			<?php echo $supplierEvaluationReevaluation['Product']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Device'); ?></td>
		<td>
			<?php echo $supplierEvaluationReevaluation['Device']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Quantity Supplied'); ?></td>
		<td>
			<?php echo h($supplierEvaluationReevaluation['SupplierEvaluationReevaluation']['quantity_supplied']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Quantity Accepted'); ?></td>
		<td>
			<?php echo h($supplierEvaluationReevaluation['SupplierEvaluationReevaluation']['quantity_accepted']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Required Delivery Date'); ?></td>
		<td>
			<?php echo h($supplierEvaluationReevaluation['SupplierEvaluationReevaluation']['required_delivery_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Actual Delivery Date'); ?></td>
		<td>
			<?php echo h($supplierEvaluationReevaluation['SupplierEvaluationReevaluation']['actual_delivery_date']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Remarks'); ?></td>
		<td>
			<?php echo h($supplierEvaluationReevaluation['SupplierEvaluationReevaluation']['remarks']); ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($supplierEvaluationReevaluation['SupplierEvaluationReevaluation']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $supplierEvaluationReevaluation['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $supplierEvaluationReevaluation['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr  bgcolor="#FFFFFF"><td><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($supplierEvaluationReevaluation['SupplierEvaluationReevaluation']['modified']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $supplierEvaluationReevaluation['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
	</table>
	<br />
